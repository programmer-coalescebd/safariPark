<?php
/**
 * Created by PhpStorm.
 * User: ahstanin
 * Date: 8/7/17
 * Time: 7:01 PM
 */

(strpos($_SERVER["REQUEST_URI"], "view") !== false) ? exit('Direct access not allowed') : '';

define("ADM_DIR", 'view/admin/');

if (isset($_GET["for"]) && $_GET["for"] == 'forget') {

    $page = $_GET["for"];

    if (file_exists(ADM_DIR . $page . '.php')) {
        require_once ADM_DIR . $page . '.php';
    } else {
        echo '<h1 align="center">404</h1>';
    }

} elseif (isset($_SESSION["admin_data"])) {

    $username = $_SESSION["admin_data"]["email"];
    $_key = $_SESSION["admin_data"]["key"];

    $login_checking = $DB->sql("SELECT * FROM `user` WHERE email = '" . $username . "' AND `key` = '" . $_key . "' AND status != '2'", 0, 0, 0);

    if (count($login_checking) > 0) {
        if (isset($_GET["for"]) && !empty($_GET["for"])) {
            $page = $_GET["for"];
            $admin_data = $_SESSION["admin_data"];

            if (file_exists(ADM_DIR . $page . '.php')) {
                require_once ADM_DIR . $page . '.php';
            } else {
                echo '<h1 align="center">404</h1>';
            }


        } else {
            require_once ADM_DIR . 'index.php';
        }
    } else {
        session_destroy();
        echo '<meta http-equiv="refresh" content="0;url=' . APP_DOM . 'admin/" />';
        echo 'Something wrong';
    }

} elseif (isset($_COOKIE["admin_cookie_login"])) {
    $cookie_user = $encryption->decrypt($_COOKIE["admin_cookie_login"]);

    if (!empty($cookie_user)) {

        $login_checking = $DB->sql("SELECT * FROM `user` WHERE token = '" . $cookie_user . "' AND status != '2'", 0, 0, 0);

        if (count($login_checking) > 0) {
            $_user_data = $login_checking["0"];
            $_SESSION["admin_data"] = $_user_data;
            $admin_data = $_SESSION["admin_data"];

            if (isset($_GET["for"]) && !empty($_GET["for"])) {
                $page = $_GET["for"];

                if (file_exists(ADM_DIR . $page . '.php')) {
                    require_once ADM_DIR . $page . '.php';
                } else {
                    echo '<h1 align="center">404</h1>';
                }


            } else {
                require_once ADM_DIR . 'index.php';
            }
        } else {
            session_destroy();
            unset($_COOKIE['admin_cookie_login']);
            setcookie('admin_cookie_login', '', time() - 3600, '/');
            echo '<meta http-equiv="refresh" content="0;url=' . APP_DOM . 'admin/" />';
            echo 'Something wrong';
        }

    } else {
        session_destroy();
        setcookie("admin_cookie_login", "", time() - 3600);
        echo '<meta http-equiv="refresh" content="0;url=' . APP_DOM . 'admin/" />';
    }

} else {
    require_once ADM_DIR . 'login.php';
}