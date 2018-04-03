<?php
/**
 * Created by PhpStorm.
 * User: ahstanin
 * Date: 8/7/17
 * Time: 11:39 PM
 */
(strpos($_SERVER["REQUEST_URI"], "view") !== false) ? exit('Direct access not allowed') : '';
session_destroy();
if (isset($_COOKIE['admin_cookie_login'])) {
    unset($_COOKIE['admin_cookie_login']);
    setcookie('admin_cookie_login', '', time() - 3600, '/');
}
echo '<meta http-equiv="refresh" content="0;url=' . APP_DOM . 'admin/" />';
echo 'Loading....';