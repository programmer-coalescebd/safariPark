<?php
/**
 * Created by PhpStorm.
 * User: ahstanin
 * Date: 8/7/17
 * Time: 6:01 PM
 */

define("APP_NAME", "Bangabandhu Sheikh Mujib Safari Park");
define("APP_DOM", "https://demo.mywire.org/");
define("APP_SSL", true);
define("ORDER_EMAIL", false);

$data_base = array(
    "host" => "127.0.0.1",
    "database" => "work",
    "username" => "root",
    "password" => ""
);

$smtp_mailer = array(
    "host" => "smtp.mailgun.org",
    "port" => 587,
    "security" => "tls",
    "username" => "",
    "password" => "",
    "senderEmail" => "",
    "senderName" => "Bangabandhu Sheikh Mujib Safari Park"
);