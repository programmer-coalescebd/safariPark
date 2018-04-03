<?php
/**
 * Created by PhpStorm.
 * User: ahstanin
 * Date: 8/7/17
 * Time: 7:01 PM
 */

(strpos($_SERVER["REQUEST_URI"], "view") !== false) ? exit('Direct access not allowed') : '';

define("ADM_DIR", 'view/gate/');

if (isset($_GET["for"]) && $_GET["for"] == 'forget') {

    $page = $_GET["for"];

    if (file_exists(ADM_DIR . $page . '.php')) {
        require_once ADM_DIR . $page . '.php';
    } else {
        echo '<h1 align="center">404</h1>';
    }

} elseif (isset($_SESSION["gate_data"])) {

    $username = $_SESSION["gate_data"]["email"];
    $_key = $_SESSION["gate_data"]["key"];

    $login_checking = $DB->sql("SELECT * FROM `user` WHERE email = '" . $username . "' AND `key` = '" . $_key . "' AND status != '2'", 0, 0, 0);

    if (count($login_checking) > 0) {
        $gate_data = $_SESSION["gate_data"];
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
        echo '<meta http-equiv="refresh" content="0;url=' . APP_DOM . 'gate/" />';
        echo 'Something wrong';
    }

} elseif (isset($_COOKIE["gate_cookie_login"])) {
    $cookie_user = $encryption->decrypt($_COOKIE["gate_cookie_login"]);

    if (!empty($cookie_user)) {

        $login_checking = $DB->sql("SELECT * FROM `user` WHERE token = '" . $cookie_user . "' AND status != '2'", 0, 0, 0);

        if (count($login_checking) > 0) {
            $_user_data = $login_checking["0"];
            $_SESSION["gate_data"] = $_user_data;
            $gate_data = $_SESSION["gate_data"];

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
            unset($_COOKIE['gate_cookie_login']);
            setcookie('gate_cookie_login', '', time() - 3600, '/');
            echo '<meta http-equiv="refresh" content="0;url=' . APP_DOM . 'gate/" />';
            echo 'Something wrong';
        }

    } else {
        session_destroy();
        setcookie("gate_cookie_login", "", time() - 3600);
        echo '<meta http-equiv="refresh" content="0;url=' . APP_DOM . 'gate/" />';
    }

} else {
    require_once ADM_DIR . 'login.php';
}