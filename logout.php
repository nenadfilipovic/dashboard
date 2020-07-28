<?php

/* ...Initialise session... */
session_start();

/* ...Unset all sessions... */
$_SESSION = array();

/* ...Destroy all session... */
session_destroy();

/* ...Redirect to login screen... */
header("location: ./");
exit;