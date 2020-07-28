<?php

/* ...Database info... */
define('DB_SERVER', 'localhost');
define('DB_USERNAME', '');
define('DB_PASSWORD', '');
define('DB_NAME', '');

/* ...Connect to database... */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

/* ...Throw error if you can't connect to database... */
if ($link === false) {
    die("Failed to connect to MySQL: " . mysqli_connect_error());
}