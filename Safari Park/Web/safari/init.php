<?php
/**
 * Created by PhpStorm.
 * User: ahstanin
 * Date: 8/7/17
 * Time: 6:01 PM
 */
session_start();
date_default_timezone_set("Asia/Dhaka");

///Important Headers
define("DIR_ROOT", "");
define("DIR_INC", "includes/");
define("DIR_CLS", "classes/");
define("DIR_CTRL", "controller/");
define("DIR_VIEW", "view/");

//Config
require_once DIR_ROOT . 'config.php';

if (APP_SSL) {
    @$_request_type = $_SERVER["REQUEST_SCHEME"];
    if (strpos($_SERVER["HTTP_HOST"], "www.") !== false) {
        if (!headers_sent()) {
            header("Status: 301 Moved Permanently");
            header(sprintf(
                'Location: https://%s%s',
                str_replace("www.", "", $_SERVER['HTTP_HOST']),
                $_SERVER['REQUEST_URI']
            ));
            exit();
        }
    } elseif (@$_request_type == "http") {
        if (!headers_sent()) {
            header("Status: 302 Moved Permanently");
            header(sprintf(
                'Location: https://%s%s',
                $_SERVER['HTTP_HOST'],
                $_SERVER['REQUEST_URI']
            ));
            exit();
        }
    }
}


require_once DIR_CLS . 'connect.php';
require_once DIR_CLS . 'sms.php';
require_once DIR_CLS . 'encryption.php';
require_once DIR_CTRL . 'email_template.php';
require_once DIR_INC . 'vendor/autoload.php';
require_once DIR_INC . 'language.php';


$DB = new connect($data_base["host"], $data_base["database"], $data_base["username"], $data_base["password"]);
$sms = new sms("", "", "");
$encryption = new encryption;
$key = $encryption->random_key();
$token = $encryption->random_token();
$email_template = new email_template;
$time = time();
///Important Headers


//ContentVersion
$contentVersion = $DB->sql("SELECT `value` FROM core WHERE `key` = 'content_version' ", 0, 0, 0);
$contentVersion = (isset($contentVersion[0]["value"]) ? $contentVersion[0]["value"] : exit($contentVersion));


// User Location
if (strpos($_SERVER['REMOTE_ADDR'], '192.168') !== false) {
    $ip = file_get_contents("http://ipecho.net/plain");
} elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}

/*
if ($ip != '::1' && $ip != '127.0.0.1' && $ip != '192.168.1.1' && $ip != '192.168.0.51') {
    $reader = new GeoIp2\Database\Reader(DIR_INC . 'GeoLite2-City.mmdb');
    $record = $reader->city($ip);

    $country = $record->country->name;
    $country_code = $record->country->isoCode;
    $state = $record->mostSpecificSubdivision->isoCode;
    $city = $record->city->name;

} else {
    $country = 'Bangladesh';
    $country_code = 'BD';
    $city = 'Dhaka';
}
*/

function ip_loc($ip)
{
    $reader = new GeoIp2\Database\Reader(DIR_INC . 'GeoLite2-City.mmdb');
    $record = $reader->city($ip);
    $data = array();
    $data["country"] = $record->country->name;
    $data["country_code"] = $record->country->isoCode;
    $data["state"] = $record->mostSpecificSubdivision->isoCode;
    $data["city"] = $record->city->name;

    return $data;
}

//User Location End


//Email
$mail = new PHPMailer;
$mail->isSMTP();
$mail->Host = $smtp_mailer["host"];
$mail->SMTPAuth = true;
$mail->Username = $smtp_mailer["username"];
$mail->Password = $smtp_mailer["password"];
$mail->SMTPSecure = $smtp_mailer["security"];
$mail->Port = $smtp_mailer["port"];
$mail->setFrom($smtp_mailer["senderEmail"], $smtp_mailer["senderName"]);
//Email


//Language
if (isset($_GET['lang'])) {
    if ($_GET['lang'] == 'en') {
        $lang = 'en';
        $_SESSION['lang'] = 'en';
    }
    if ($_GET['lang'] == 'bn') {
        $lang = 'bn';
        $_SESSION['lang'] = 'bn';
    }

    @header("Location:" . $_SERVER["HTTP_REFERER"]);

    exit();

} elseif (isset($_SESSION['lang'])) {
    $lang = $_SESSION['lang'];
} else {
    $lang = 'en';
}
//Language


//Minify
function sanitize_output($buffer)
{

    $search = array(
        '/\>[^\S ]+/s',     // strip whitespaces after tags, except space
        '/[^\S ]+\</s',     // strip whitespaces before tags, except space
        '/(\s)+/s',         // shorten multiple whitespace sequences
        '/<!--(.|\s)*?-->/' // Remove HTML comments
    );

    $replace = array(
        '>',
        '<',
        '\\1',
        ''
    );

    $buffer = preg_replace($search, $replace, $buffer);

    return $buffer;
}

//Minify

if (isset($_GET["action"]) && !empty($_GET["action"])) {
    $action = $_GET["action"];

    if (file_exists(DIR_VIEW . $action . '.php')) {
        include DIR_VIEW . $action . '.php';
    } else {
        header('Content-Type: application/json');
        exit('{"Status":404,"Type":"Not Found","Message":"The file you are trying to access not exist.","Token":"' . md5(rand()) . '"}');
    }
} elseif (isset($_GET["site"]) && !empty($_GET["site"])) {
    $action = $_GET["site"];

    if (file_exists(DIR_VIEW . 'site/' . $action . '.php')) {
        include DIR_VIEW . 'site/' . $action . '.php';
    } else {
        header('Content-Type: application/json');
        exit('{"Status":404,"Type":"Not Found","Message":"The file you are trying to access not exist.","Token":"' . md5(rand()) . '"}');
    }
} else {
    include DIR_VIEW . 'site/index.php';
}