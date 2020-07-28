<?php

/* ...Initialise session... */
session_start();

/* ...Check if user is logged in... */
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ./");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <meta name="Author" content="Nenad Filipovic">
    <meta name="description" content="Dashboard page for my website.">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico"/>
    <link rel="stylesheet" type="text/css" href="css/dashboard.css">
    <meta name="robots" content="noindex,nofollow">
</head>
<body>
<div id="website-container">
    <header>
        <div class="header-content">
            <img src="img/profile.png"
                 alt="Profile Image">
            <aside>
                <h1>Dashboard</h1>
                <p>Website settings and maintenance, file uploading and update control.</p>
            </aside>
        </div>
    </header>
    <main>
        <details>
            <summary>
                <span class="icon">
                    <svg enable-background="new 0 0 296.973 296.973" version="1.1" viewBox="0 0 296.973 296.973"
                         xml:space="preserve" xmlns="http://www.w3.org/2000/svg">
                        <path d="m119.49 296.01c1.859-0.771 45.779-19.505 67.354-71.712 6.739-16.311 7.036-31.16 0.88-44.136-2.264-4.772-5.24-8.892-8.611-12.444l107.22-147.06c4.229-5.799 2.955-13.928-2.844-18.155-5.8-4.231-13.93-2.955-18.155 2.844l-107.67 147.67c-4.105-1.85-7.951-3.199-11.105-4.151-5.082-1.534-10.248-2.312-15.355-2.312-18.218 0-36.87 9.427-52.617 29.09-0.184 0.229-18.077 23.95-47.257 23.95-2.731 1e-3 -5.527-0.208-8.308-0.62-4.38-0.648-8.783 0.978-11.693 4.312-2.909 3.335-3.921 7.921-2.686 12.171 0.22 0.755 5.55 18.697 21.058 37.524 14.283 17.341 40.273 39.603 83.325 43.943 3.3 0.334 6.456-0.909 6.456-0.909zm11.722-123.47c2.598 0 5.163 0.393 7.844 1.203 5.915 1.785 20.21 7.07 25.186 17.559 2.919 6.153 2.454 13.699-1.418 23.072-1.269 3.071-2.641 5.992-4.086 8.784-10.558-4.785-34.988-16.554-58.424-32.863 10.71-11.774 21.096-17.755 30.898-17.755zm-18.886 98.088c-39.982-5.776-60.882-29.22-70.845-45.689 16.668-2.125 30.078-9.203 39.767-16.309 23.966 17.304 49.095 30.004 62.725 36.359-12.622 14.65-25.946 22.64-31.647 25.639z"/>
                    </svg>
                </span>
                <h1>Server cleanup</h1>
            </summary>
            <aside>
                <p>This action will remove all files, except admin panel, from root directory. It is advisable to
                    use this action before planned website update.</p>
                <button id="requestMaintenance" onclick="sendRequest('requestMaintenance')">
                    Proceed
                </button>
                <p class="maintenance-log"></p>
            </aside>
        </details>
        <details>
            <summary>
                <span class="icon">
                    <svg enable-background="new 0 0 32 32" version="1.1" viewBox="0 0 32 32" xml:space="preserve"
                         xmlns="http://www.w3.org/2000/svg">
	                    <g fill="#ffffff">
                            <path d="M27.586,12.712C26.66,10.251,24.284,8.5,21.5,8.5c-0.641,0-1.26,0.093-1.846,0.266    C18.068,6.205,15.233,4.5,12,4.5c-4.904,0-8.894,3.924-8.998,8.803C1.207,14.342,0,16.283,0,18.5c0,3.312,2.688,6,6,6h6v-2H5.997    C3.794,22.5,2,20.709,2,18.5c0-1.893,1.317-3.482,3.087-3.896C5.029,14.245,5,13.876,5,13.5c0-3.866,3.134-7,7-7    c3.162,0,5.834,2.097,6.702,4.975c0.769-0.611,1.739-0.975,2.798-0.975c2.316,0,4.225,1.75,4.473,4h0.03    c2.203,0,3.997,1.791,3.997,4c0,2.205-1.789,4-3.997,4H20v2h6c3.312,0,6-2.693,6-6C32,15.735,30.13,13.407,27.586,12.712z"/>
                            <polygon points="16 13.5 11 19.5 14 19.5 14 27.5 18 27.5 18 19.5 21 19.5"/>
                        </g>
                    </svg>
                </span>
                <h1>Upload files</h1>
            </summary>
            <aside>
                <p>Select file to be uploaded to root directory. In case of uploading update file it has to be named
                    <strong style="font-weight: bold">update.zip</strong>
                    to process correctly.</p>
                <input id="upload-file" type="file" style="display: none">
                <label for="upload-file" class="select-file-button"><span class="text">Select a file...</span><span
                        class="progress"></span></label>
                <button disabled onclick="storeFile()" id="store-file-button">Upload</button>
                <p class="upload-log"></p>
            </aside>
        </details>
        <details>
            <summary>
                <span class="icon">
                    <svg enable-background="new 0 0 32 32" version="1.1" viewBox="0 0 32 32" xml:space="preserve"
                         xmlns="http://www.w3.org/2000/svg">
	                    <g fill="#ffffff">
                            <path d="m19.973 20.5c0.017 0.164 0.027 0.331 0.027 0.5 0 2.484-2.016 4.5-4.5 4.5-2.485 0-4.5-2.016-4.5-4.5 0-2.316 1.75-4.225 4-4.473v1.973l4-3-4-3v2.019c-3.356 0.255-6 3.059-6 6.481 0 3.59 2.91 6.5 6.5 6.5s6.5-2.91 6.5-6.5c0-0.168-7e-3 -0.335-0.018-0.5h-2.009z"/>
                            <path d="M27.586,12.712C26.66,10.251,24.284,8.5,21.5,8.5c-0.641,0-1.26,0.093-1.846,0.266    C18.068,6.205,15.233,4.5,12,4.5c-4.905,0-8.893,3.924-8.998,8.803C1.208,14.342,0,16.283,0,18.5c0,3.312,2.687,6,6,6h1.752    c-0.285-0.63-0.495-1.3-0.62-2H5.997C3.794,22.5,2,20.709,2,18.5c0-1.893,1.318-3.482,3.086-3.896C5.03,14.245,5,13.876,5,13.5    c0-3.866,3.134-7,7-7c3.162,0,5.834,2.097,6.702,4.975c0.769-0.611,1.739-0.975,2.798-0.975c2.316,0,4.225,1.75,4.473,4h0.03    c2.203,0,3.997,1.791,3.997,4c0,2.205-1.789,4-3.997,4h-2.135c-0.125,0.7-0.335,1.37-0.62,2H26c3.312,0,6-2.693,6-6    C32,15.735,30.13,13.407,27.586,12.712z"/>
                        </g>
                    </svg>
                </span>
                <h1>Check for update</h1>
            </summary>
            <aside>
                <p>Check if update file
                    <strong style="font-weight: bold">update.zip</strong>
                    exist on server, if it doesn't please provide update file from upload section.
                </p>
                <div style="display: none" class="remote-directory-container">
                    <p class="remote-directory"></p>
                </div>
                <button id="requestCheck" onclick="sendRequest('requestCheck')">Check</button>
                <p class="check-log"></p>
            </aside>
        </details>
        <details>
            <summary>
                <span class="icon">
                    <svg enable-background="new 0 0 97.785 97.785" version="1.1" viewBox="0 0 97.785 97.785"
                         xml:space="preserve" xmlns="http://www.w3.org/2000/svg">
		                <path d="m77.457 14.56c-0.713-0.062-1.363-0.424-1.791-0.998-3.877-5.219-10.082-8.435-16.68-8.435-0.406 0-0.812 0.013-1.218 0.036-0.591 0.035-1.173-0.141-1.646-0.498-4.021-3.025-8.887-4.665-13.987-4.665-9.134 0-17.243 5.284-21.047 13.289-0.36 0.757-1.075 1.282-1.903 1.4-10.064 1.435-17.828 10.11-17.828 20.563 0 8.424 5.051 15.679 12.276 18.933-4e-3 -0.304-0.02-0.596-0.02-0.903 0-3.328 0.222-6.017 0.67-8.204-2.886-2.307-4.744-5.852-4.744-9.827 0-6.94 5.647-12.586 12.587-12.586 0.351 0 0.696 0.028 1.038 0.057 2 0.154 3.812-1.143 4.298-3.083 1.692-6.745 7.727-11.455 14.672-11.455 3.968 0 7.724 1.533 10.576 4.315 0.976 0.95 2.357 1.354 3.691 1.077 0.848-0.177 1.719-0.267 2.582-0.267 4.858 0 9.196 2.732 11.323 7.129 0.747 1.546 2.381 2.466 4.091 2.29 0.426-0.043 0.85-0.063 1.262-0.063 6.939 0 12.588 5.646 12.588 12.586 0 3.975-1.857 7.519-4.744 9.827 0.447 2.186 0.669 4.875 0.669 8.203 0 0.309-0.017 0.601-0.021 0.903 7.227-3.254 12.276-10.507 12.276-18.933 4e-3 -10.846-8.355-19.778-18.97-20.691z"/>
                        <path d="m48.896 30.221c-20.531 18.947-31.192-0.761-31.192 23.06 0 33.218 23.983 43.242 31.191 44.504 7.204-1.26 31.188-11.286 31.188-44.504 0-23.82-10.658-4.113-31.187-23.06zm17.215 30.513l-17.961 17.959c-0.951 0.953-2.199 1.43-3.446 1.43-1.248 0-2.495-0.477-3.447-1.43l-9.58-9.578c-1.903-1.904-1.903-4.989 0-6.895 1.901-1.902 4.99-1.902 6.892 0l6.135 6.135 14.516-14.514c1.9-1.904 4.99-1.904 6.893 0 1.903 1.901 1.903 4.987-2e-3 6.893z"/>
                    </svg>
                </span>
                <h1>Apply update</h1>
            </summary>
            <aside>
                <h1>Apply update</h1>
                <p>Update process will extract and apply update file. If you are sure that file exist on server
                    proceed
                    with updating. After process is completed update file will be removed.</p>
                <button disabled id="requestUpdate" onclick="sendRequest('requestUpdate')">Update</button>
                <p class="update-log"></p>
            </aside>
        </details>
        <details>
            <summary>
                <span class="icon">
                    <svg enable-background="new 0 0 488.5 488.5" version="1.1" viewBox="0 0 488.5 488.5"
                         xml:space="preserve" xmlns="http://www.w3.org/2000/svg">
		                <path d="m364.5 232.45c5.4 4.6 13.4 4.4 18.5-0.7 4.9-4.9 5.3-12.5 1.2-17.8l-0.2-0.2c-0.1-0.2-0.3-0.4-0.4-0.5l-33.6-40c-1.4-1.7-2.3-3.8-2.4-6.1l-4.9-75.3c-0.6-7-6.4-12.4-13.6-12.4-7 0-12.7 5.2-13.5 12v0.1c0 0.3 0 0.6-0.1 0.9l-5.4 82.7c-0.1 0.5-0.1 1.1-0.1 1.6v0.2 0.3 0.4c-0.1 5 1.8 10 5.6 13.8 0.8 0.8 1.6 1.5 2.5 2.1l46.4 38.9z"/>
                        <path d="m330.3 16.45c-69.4 0-128.4 44.9-149.7 107.1 13.8 1.6 27.2 4.6 40.1 9.1 16.9-44.1 59.7-75.5 109.6-75.5 64.7 0 117.4 52.7 117.4 117.4 0 58.6-43.1 107.2-99.3 116 0.9 7.6 1.4 15.4 1.4 23.2 0 6-0.3 11.9-0.8 17.8 78.4-9.3 139.5-76.1 139.5-157-0.1-87.2-71-158.1-158.2-158.1z"/>
                        <path d="m303.1 290.45l-20.4-0.1c-3.2-16.7-9.7-32.9-19.6-47.5l14.5-14.4c5.2-5.2 5.3-13.7 0.1-18.9l-14.6-14.6c-5.2-5.2-13.7-5.3-18.9-0.1l-14.5 14.4c-14.6-10-30.7-16.6-47.4-19.9l0.1-20.4c0-7.4-5.9-13.4-13.3-13.4l-20.6-0.1c-7.4 0-13.4 5.9-13.4 13.3l-0.1 20.3c-16.8 3.1-33.1 9.6-47.8 19.5l-14.2-14.2c-5.2-5.2-13.7-5.3-18.9 0l-14.6 14.6c-5.2 5.2-5.3 13.7-0.1 18.9l14 14.1c-10.1 14.6-16.8 31-20.1 47.8l-19.8-0.1c-7.4 0-13.4 5.9-13.4 13.3l-0.1 20.6c0 7.4 5.9 13.4 13.3 13.4l19.7 0.1c3.1 16.9 9.7 33.4 19.7 48.1l-13.9 13.8c-5.2 5.2-5.3 13.7-0.1 18.9l14.6 14.6c5.2 5.2 13.7 5.3 18.9 0l14.1-13.6c14.7 10.1 31.1 16.8 48 20l-0.1 19.7c0 7.4 5.9 13.4 13.3 13.4l20.6 0.1c7.4 0 13.4-5.9 13.4-13.3l0.1-19.8c16.9-3.2 33.2-9.8 47.9-19.9l14 14.1c5.2 5.2 13.7 5.3 18.9 0.1l14.6-14.6c5.2-5.2 5.3-13.7 0.1-18.9l-14.2-14.2c10-14.6 16.6-30.9 19.7-47.7l20.3 0.1c7.4 0 13.4-5.9 13.4-13.3l0.1-20.6c0.1-7.6-5.9-13.6-13.3-13.6zm-96.2 72.1c-27 26.8-70.6 26.7-97.4-0.3s-26.7-70.6 0.3-97.4 70.6-26.7 97.4 0.3 26.6 70.6-0.3 97.4z"/>
                    </svg>
                </span>
                <h1>Event logs</h1>
            </summary>
            <aside>
                <p>Monitoring system events and tracking requests and responses.</p>
                <div class="eventLogger-container">
                    <p class="eventLogger"></p>
                </div>
            </aside>
        </details>
        <details>
            <summary>
                <span class="icon">
                    <svg enable-background="new 0 0 27.965 27.965" version="1.1" viewBox="0 0 27.965 27.965"
                         xml:space="preserve" xmlns="http://www.w3.org/2000/svg">
		                <path d="m13.98 0c-7.721 0-13.98 6.261-13.98 13.983 0 7.721 6.259 13.982 13.98 13.982 7.725 0 13.985-6.262 13.985-13.982 0-7.722-6.26-13.983-13.985-13.983zm6.012 17.769l-2.227 2.224s-3.523-3.78-3.786-3.78c-0.259 0-3.783 3.78-3.783 3.78l-2.228-2.224s3.784-3.472 3.784-3.781c0-0.314-3.784-3.787-3.784-3.787l2.228-2.229s3.553 3.782 3.783 3.782c0.232 0 3.786-3.782 3.786-3.782l2.227 2.229s-3.785 3.523-3.785 3.787c0 0.251 3.785 3.781 3.785 3.781z"/>
                    </svg>
                </span>
                <h1>Logout</h1>
            </summary>
            <aside>
                <p>Exit from admin panel and clear all session data.</p>
                <form action="logout">
                    <button>Logout</button>
                </form>
            </aside>
        </details>
    </main>
</div>
<script src="js/script.js"></script>
</body>
</html>