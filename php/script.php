<?php

/* ...Store file... */
function storeFile()
{
    $path = "../../";
    $path = $path . basename($_FILES['data']['name']);
    if (move_uploaded_file($_FILES['data']['tmp_name'], $path)) {
        $response = 200;
    } else {
        $response = 400;
    }
    eventLogger('storeFile', $response);
    echo $response;
}

/* ...Remove files... */
function requestMaintenance()
{
    $keep = array(
        '../../admin',
        '../../admin/css',
        '../../admin/css/dashboard.css',
        '../../admin/css/login.css',
        '../../admin/css/reset.css',
        '../../admin/img',
        '../../admin/img/background.png',
        '../../admin/img/profile.png',
        '../../admin/js',
        '../../admin/js/script.js',
        '../../admin/logs',
        '../../admin/logs/log.json',
        '../../admin/logs/log.txt',
        '../../admin/php',
        '../../admin/php/config.php',
        '../../admin/php/script.php',
        '../../admin/.htaccess',
        '../../admin/dashboard.php',
        '../../admin/favicon.ico',
        '../../admin/login.php',
        '../../admin/logout.php',
    );

    $directory = '../../';

    $recursive = new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS);
    $files = new RecursiveIteratorIterator($recursive, RecursiveIteratorIterator::CHILD_FIRST);
    foreach ($files as $file) {
        if (!in_array($file, $keep)) {
            if ($file->isDir()) {
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }
    }
    return true;
}

/* ...Extract update file... */
function requestUpdate()
{
    $archive = '../../update.zip';
    $destination = '../../';

    if (!class_exists('ZipArchive')) {
        $response = 400;
        echo $response;
        return;
    }

    $zip = new ZipArchive;
    if (file_exists($archive)) {
        if ($zip->open($archive) === TRUE) {
            if (is_writeable($destination . '/')) {
                $zip->extractTo($destination);
                $zip->close();
                unlink($archive);
                $response = 200;
            } else {
                $response = 400;
            }
        } else {
            $response = 400;
        }
    } else {
        $response = 400;
    }
    eventLogger('requestUpdate', $response);
    echo $response;
}

/* ...Return directory listing... */
function requestCheck()
{
    $fileList = glob('../../*');
    foreach ($fileList as $filename) {
        echo basename($filename), '<br>';
    }
    return true;
}

/* ...Event logger... */
function eventLogger($request, $status)
{
    $today = date("F j, Y, g:i a");

    /* ...JSON logger... */
    $json_object = file_get_contents('../logs/log.json');
    $data = json_decode($json_object, true);
    $data[$request] = $today;
    $json_object = json_encode($data);
    file_put_contents('../logs/log.json', $json_object);

    /* ...TXT logger... */
    $txt = '# Date: ' . $today . ' - ' . 'Request: ' . $request . ' - ' . 'Response: ' . $status;
    file_put_contents('../logs/log.txt', PHP_EOL . $txt, FILE_APPEND | LOCK_EX);
}

/* ...Handle requests... */
if (isset($_POST['data'])) {
    switch ($_POST['data']) {
        case 'requestMaintenance':
            if (requestMaintenance()) {
                $response = 200;
            } else {
                $response = 400;
            }
            eventLogger('requestMaintenance', $response);
            echo $response;
            break;
        case 'requestUpdate':
            requestUpdate();
            break;
        case 'requestCheck':
            if (requestCheck()) {
                $response = 200;
            } else {
                $response = 400;
            }
            eventLogger('requestCheck', $response);
            break;
    }
} elseif (!empty($_FILES['data'])) {
    storeFile();
}