<?php

/* ...Initialise session... */
session_start();

/* ...Check if user is logged in... */
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: ./dashboard");
    exit;
}

/* ...Include config file... */
require_once "php/config.php";

/* ...Define empty variables... */
$username = $password = "";
$username_err = $password_err = "";

/* ...Process received data... */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    /* ...Check if user entered username... */
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter username.";
    } else {
        $username = trim($_POST["username"]);
    }

    /* ...Check if user entered password... */
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    /* ...Get data from database and validate... */
    if (empty($username_err) && empty($password_err)) {

        /* ...Prevent mysql injection... */
        $sql = "SELECT id, username, password FROM users WHERE username = ?";

        /* ...Check username and password... */
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            $param_username = $username;
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            session_start();
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
                            header("location: ./dashboard");
                        } else {
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else {
                    $username_err = "No account found with that username.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($link);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <meta name="Author" content="Nenad Filipovic">
    <meta name="description" content="Login page for admin panel.">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico"/>
    <link rel="stylesheet" type="text/css" href="css/login.css">
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
                <p>Please enter your login details.</p>
            </aside>
        </div>
    </header>
    <main>
        <article>
            <form autocomplete="off" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <label>
                    <span>
                        <svg width="485.21px" height="485.21px" enable-background="new 0 0 485.211 485.21" version="1.1"
                             viewBox="0 0 485.211 485.21" xml:space="preserve"
                             xmlns="http://www.w3.org/2000/svg">
                            <path d="m394.24 333.58h-30.327c-33.495 0-60.653-27.158-60.653-60.654v-19.484c13.418-15.948 23.042-34.812 29.024-54.745 0.621-3.36 3.855-5.02 6.012-7.33 11.611-11.609 13.894-31.2 5.185-45.149-1.186-2.117-3.322-3.953-3.201-6.576 0-17.784 0.089-35.596-0.023-53.366-0.476-21.455-6.608-43.773-21.65-59.66-12.144-12.836-28.819-20.479-46.022-23.75-21.739-4.147-44.482-3.937-66.013 1.54-18.659 4.709-36.189 15.637-47.028 31.836-9.598 14.083-13.803 31.183-14.513 48.036-0.266 18.094-0.061 36.233-0.116 54.371 0.413 3.631-2.667 6.088-4.058 9.094-8.203 14.881-4.592 35.155 8.589 45.978 3.344 2.308 3.97 6.515 5.181 10.142 5.748 17.917 15.282 34.487 27.335 48.925v20.138c0 33.496-27.157 60.654-60.651 60.654h-30.328s-54.964 15.158-90.978 90.975v30.327c0 16.759 13.564 30.321 30.327 30.321h424.56c16.759 0 30.322-13.562 30.322-30.321v-30.327c-36.012-75.811-90.976-90.975-90.976-90.975z"/>
                        </svg>
                    </span>
                    <input name="username" value="<?php echo $username; ?>" placeholder="Username" type="text" required>
                </label>
                <span><?php echo $username_err; ?></span>
                <label>
                    <span>
                        <svg width="416.21px" height="416.21px" enable-background="new 0 0 416.208 416.209"
                             version="1.1" viewBox="0 0 416.208 416.209" xml:space="preserve"
                             xmlns="http://www.w3.org/2000/svg">
                            <path d="m344.76 166.9h-20.543v-50.792c0-64.022-52.086-116.11-116.11-116.11-64.023 0-116.11 52.086-116.11 116.11v50.792h-20.543c-6.635 0-12.012 5.377-12.012 12.011v225.29c0 6.635 5.377 12.012 12.012 12.012h273.3c6.633 0 12.01-5.377 12.01-12.012v-225.29c-1e-3 -6.634-5.378-12.011-12.011-12.011zm-117.92 137.11v47.961c0 4.189-3.396 7.586-7.586 7.586h-22.286c-4.189 0-7.586-3.396-7.586-7.586v-47.961c-8.287-5.875-13.699-15.535-13.699-26.466 0-17.907 14.518-32.427 32.428-32.427 17.908 0 32.426 14.52 32.426 32.427 1e-3 10.931-5.411 20.591-13.697 26.466zm41.946-137.11h-121.35v-50.792c0-33.456 27.219-60.673 60.676-60.673 33.455 0 60.672 27.217 60.672 60.673v50.792z"/>
                        </svg>
                    </span>
                    <input name="password" placeholder="Password" type="password" required/>
                </label>
                <span><?php echo $password_err; ?></span>
                <button type="submit" value="Login">Login</button>
            </form>
        </article>
    </main>
</div>
</body>
</html>