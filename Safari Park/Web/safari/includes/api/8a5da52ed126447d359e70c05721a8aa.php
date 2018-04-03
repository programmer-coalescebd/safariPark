<?php

if (!function_exists('getallheaders')) {
    function getallheaders()
    {
        $headers = array();
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
}

///Important Headers
define("DIR_ROOT", "../../");
define("DIR_INC", "../");
define("DIR_CLS", "../../classes/");
define("DIR_CTRL", "../../controller/");
define("DIR_VIEW", "../../view/");

require_once DIR_ROOT . 'config.php';
require_once DIR_CLS . 'connect.php';
require_once DIR_CLS . 'encryption.php';
require_once DIR_CTRL . 'email_template.php';
require_once DIR_INC . 'vendor/autoload.php';

date_default_timezone_set("Asia/Dhaka");

$DB = new connect($data_base["host"], $data_base["database"], $data_base["username"], $data_base["password"]);
$encryption = new encryption;
$key = $encryption->random_key();
$token = $encryption->random_token();
$email_template = new email_template;
$time = time();
///Important Headers


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
if ($ip != '::1' && $ip != '127.0.0.1' && $ip != '192.168.1.1') {

    $reader = new GeoIp2\Database\Reader(DIR_INC . 'GeoLite2-City.mmdb');
    $record = $reader->city($ip);
    $country = ($record->country->name != '' ? $record->country->name : 'Unknown');
    $country_code = ($record->country->isoCode != '' ? $record->country->isoCode : 'Unknown');
    $state = ($record->mostSpecificSubdivision->isoCode != '' ? $record->mostSpecificSubdivision->isoCode : 'Unknown');
    $city = ($record->city->name != '' ? $record->city->name : 'Unknown');

} else {
    $country = 'Bangladesh';
    $country_code = 'BD';
    $state = 'Dhaka';
    $city = 'Dhaka';
    $ip = '103.198.139.230';
}
*/
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


if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers:{$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}


header('Content-Type: application/json');

$headers = getallheaders();
$postdata = file_get_contents("php://input");


//if (isset($headers['X-Auth-Token']) && $headers['X-Requested-With'] == 'info.akhaura.safari') {
if (isset($headers['X-Auth-Token']) || isset($headers['X-Auth-Ibn'])) {

    $raw_token = (isset($headers['X-Auth-Token']) ? base64_decode($headers['X-Auth-Token']) : '');
    $split_token = explode("|", $raw_token);
    $part_1 = (isset($split_token["0"]) ? base64_decode($split_token["0"]) : 0);
    $part_2 = (isset($split_token["2"]) ? base64_decode($split_token["2"]) : 0);
    $part_3 = $part_2 + 60;


    if (isset($split_token["1"]) && $split_token["1"] == 'SAFARI_PARK_APP' && $part_1 == $part_2 && $part_3 > time() || isset($headers['X-Auth-Ibn']) && $encryption->dec_token($headers['X-Auth-Ibn'], $ip)) {

        //Version
        if (isset($_GET["version"])) {
            $versionCheck = $DB->sql("SELECT `value` FROM core WHERE `key` = 'content_version' ", 0, 0, 0);
            echo '{"Status":408,"Type":"Success","Message":"Content version checked","Version":"' . $versionCheck[0]["value"] . '"}';
        }
        //Version


        //Pages
        if (isset($_GET["pages"])) {

            if (isset($headers['Uuid']) && $headers['Uuid'] != '') {
                $uuid = $headers['Uuid'];
                $platform = $headers['Platform'];

                $sql = "INSERT INTO `appInstall` (
                                  `id`,
                                  `deviceID`,
                                  `platform`,
                                  `time`,
                                  `ip`
                                )
                              VALUES (
                                  NULL,
                                  '" . $uuid . "',
                                  '" . $platform . "',
                                  '" . $time . "',
                                  '" . $ip . "'
                              );";

                $DB->sql($sql, 0, 0, 0);

            }

            function type_check($pageType, $pageID)
            {
                if ($pageType != 'page') {
                    $speak = $pageType;
                } else {
                    $parentCheck = $GLOBALS["DB"]->sql("SELECT COUNT(page_id) AS has FROM pages WHERE `parent_id` = '" . $pageID . "' ", 0, 0, 0);

                    if ($parentCheck[0]["has"] > 0) {
                        $speak = 'parent';
                    } else {
                        $speak = 'single';
                    }
                }

                return $speak;
            }

            $content = array();
            $pages = $DB->sql("SELECT * FROM pages WHERE `status` = '1' ", 0, 0, 0);

            $pageNum = 0;
            foreach ($pages as $page) {
                $pageIndex = $pageNum++;
                $content["pages"][$pageIndex]["id"] = $page["page_id"];
                $content["pages"][$pageIndex]["unique_id"] = $page["unique_id"];
                $content["pages"][$pageIndex]["parent_id"] = $page["parent_id"];
                $content["pages"][$pageIndex]["grid_item"] = $page["grid_menu"];
                $content["pages"][$pageIndex]["grid_item_order"] = $page["menu_position"];
                $content["pages"][$pageIndex]["name"] = $page["page_name"];
                $content["pages"][$pageIndex]["menu_name"] = $page["menu_name"];
                $content["pages"][$pageIndex]["menu_icon"] = $page["icon_base64"];
                $content["pages"][$pageIndex]["page_type"] = type_check($page["menu_category"], $page["page_id"]);
                $content["pages"][$pageIndex]["page_component"] = $page["menu_category"];
                $content["pages"][$pageIndex]["page_contents"] = $page["page_content"];
            }


            $sliders = $DB->sql("SELECT * FROM slider WHERE `status` = '1' ", 0, 0, 0);

            $slideNum = 0;
            foreach ($sliders as $slider) {
                $slideIndex = $slideNum++;
                $content["slider_images"][$slideIndex]["id"] = $slider["slide_id"];
                $content["slider_images"][$slideIndex]["unique_id"] = $slider["unique_id"];
                $content["slider_images"][$slideIndex]["slide_item_order"] = $slider["slide_position"];
                $content["slider_images"][$slideIndex]["name"] = $slider["slide_name"];
                $content["slider_images"][$slideIndex]["image"] = $slider["image_base64"];
            }

            $versionCheck = $DB->sql("SELECT `value` FROM core WHERE `key` = 'content_version' ", 0, 0, 0);
            $content["Version"] = $versionCheck[0]["value"];

            echo json_encode($content);
        }
        //Pages


        //Tickets
        if (isset($_GET["tickets"])) {
            $content = array();

            $entryTickets = $DB->sql("SELECT * FROM ticket WHERE ticket_category = 'entryTicket' AND `status` = '1' ", 0, 0, 0);

            $entryNum = 0;
            foreach ($entryTickets as $ticket) {
                $entryIndex = $entryNum++;
                $content["entry_tickets"][$entryIndex]["id"] = $ticket["ticket_id"];
                $content["entry_tickets"][$entryIndex]["sort_by"] = $ticket["ticket_position"];
                $content["entry_tickets"][$entryIndex]["slug"] = $ticket["unique_id"];
                $content["entry_tickets"][$entryIndex]["name"] = $ticket["ticket_name"];
                $content["entry_tickets"][$entryIndex]["price"] = number_format($ticket["ticket_price"], 2, '.', '');
                $content["entry_tickets"][$entryIndex]["description"] = $ticket["ticket_details"];
                $content["entry_tickets"][$entryIndex]["require"] = $ticket["requirement"];
            }


            $safariTickets = $DB->sql("SELECT * FROM ticket WHERE ticket_category = 'safariTicket' AND `status` = '1' ", 0, 0, 0);

            $safariNum = 0;
            foreach ($safariTickets as $ticket) {
                $safariIndex = $safariNum++;
                $content["safari_tickets"][$safariIndex]["id"] = $ticket["ticket_id"];
                $content["safari_tickets"][$safariIndex]["sort_by"] = $ticket["ticket_position"];
                $content["safari_tickets"][$safariIndex]["slug"] = $ticket["unique_id"];
                $content["safari_tickets"][$safariIndex]["name"] = $ticket["ticket_name"];
                $content["safari_tickets"][$safariIndex]["price"] = number_format($ticket["ticket_price"], 2, '.', '');
                $content["safari_tickets"][$safariIndex]["description"] = $ticket["ticket_details"];
            }


            $advanceDays = $DB->sql("SELECT `value` FROM core WHERE `key` = 'advance_ticket'", 0, 0, 0);
            $advanceDays = $advanceDays[0]["value"];
            $advanceDate = $advanceDays + 1;
            //$today = date("d/m/Y");
            $today = date("d/F/Y");

            $entryAllocation = $DB->sql("SELECT * FROM allocation WHERE ticket_category = 'entryTicket' AND `status` = '1' ", 0, 0, 0);
            $entryAlloJson = json_decode($entryAllocation[0]["value"], true);

            for ($i = 0; $i < $advanceDate; $i++) {
                $countDay = $i;
                $dateKey = date("d/m/Y", strtotime(strtotime($today) . ' + ' . $countDay . ' day'));
                $dayKey = date("l", strtotime(strtotime($today) . ' + ' . $countDay . ' day'));


                $closeArray = array();
                if ($entryAlloJson["closed"] != '') {
                    $closeEx = explode("-", $entryAlloJson["closed"]);
                    $closeStart = trim($closeEx[0]);
                    $closeEnd = trim($closeEx[1]);
                    $begin = DateTime::createFromFormat("d/m/Y", $closeStart);
                    $end = DateTime::createFromFormat("d/m/Y", $closeEnd);
                    $diff = $end->diff($begin)->format("%a");
                    $closeDays = $diff + 1;

                    for ($k = 0; $k < $closeDays; $k++) {
                        $begin->modify('+' . ($k == 0 ? $k : 1) . ' day');
                        $makeKey = $begin->format("d/m/Y");
                        $closeArray[$makeKey] = 'closed';
                    }
                }

                if (array_key_exists($dateKey, $closeArray)) {
                    $content["schedules"]["entry_tickets"]["dates"][$dateKey] = 'closed';
                } elseif (isset($entryAlloJson["date"]) && array_key_exists($dateKey, $entryAlloJson["date"])) {

                    $content["schedules"]["entry_tickets"]["dates"][$dateKey] = $entryAlloJson["date"][$dateKey];

                    $shiftCheck = $DB->sql("SELECT * FROM shift WHERE ticket_category = 'entryTicket' AND `shift_date` = '" . $dateKey . "'", 0, 0, 0);
                    if (count($shiftCheck) > 0) {
                        $shiftNum = 0;
                        foreach ($shiftCheck as $shift) {
                            $shiftIndex = $shiftNum++;

                            $content["schedules"]["entry_tickets"]["dates"][$dateKey]["shifts"][$shiftIndex]["id"] = $shift["shift_id"];
                            $content["schedules"]["entry_tickets"]["dates"][$dateKey]["shifts"][$shiftIndex]["name"] = $shift["shift_name"];
                            $content["schedules"]["entry_tickets"]["dates"][$dateKey]["shifts"][$shiftIndex]["start"] = $shift["shift_time"];
                            $content["schedules"]["entry_tickets"]["dates"][$dateKey]["shifts"][$shiftIndex]["end"] = date("h:i A", strtotime($shift["shift_time"] . " +" . $shift["duration"] . " minutes"));
                            //TODO Calculate tickets
                            $ticketAbility = $DB->sql("SELECT SUM(total_ticket) AS booked FROM orders WHERE ticket_category = 'entryTicket' AND `travel_date` = '" . $dateKey . "' AND shift_id = '" . $shift["shift_id"] . "' AND status = '5'", 0, 0, 0);
                            if (isset($ticketAbility[0]["booked"])) {
                                $content["schedules"]["entry_tickets"]["dates"][$dateKey]["shifts"][$shiftIndex]["tickets"] = $shift["tickets"] - $ticketAbility[0]["booked"];
                            } else {
                                $content["schedules"]["entry_tickets"]["dates"][$dateKey]["shifts"][$shiftIndex]["tickets"] = $shift["tickets"];
                            }

                        }
                    } else {
                        $ticketAbility = $DB->sql("SELECT SUM(total_ticket) AS booked FROM orders WHERE ticket_category = 'entryTicket' AND `travel_date` = '" . $dateKey . "' AND status = '5'", 0, 0, 0);
                        if (isset($ticketAbility[0]["booked"])) {
                            $hasTicket = $content["schedules"]["entry_tickets"]["dates"][$dateKey]["tickets"];
                            $content["schedules"]["entry_tickets"]["dates"][$dateKey]["tickets"] = $hasTicket - $ticketAbility[0]["booked"];
                        }
                        $content["schedules"]["entry_tickets"]["dates"][$dateKey]["shifts"] = [];
                    }
                } else {
                    $content["schedules"]["entry_tickets"]["dates"][$dateKey] = $entryAlloJson[$dayKey];

                    $shiftCheck = $DB->sql("SELECT * FROM shift WHERE ticket_category = 'entryTicket' AND `shift_date` = '" . $dayKey . "'", 0, 0, 0);
                    if (count($shiftCheck) > 0) {
                        $shiftNum = 0;
                        foreach ($shiftCheck as $shift) {
                            $shiftIndex = $shiftNum++;

                            $content["schedules"]["entry_tickets"]["dates"][$dateKey]["shifts"][$shiftIndex]["id"] = $shift["shift_id"];
                            $content["schedules"]["entry_tickets"]["dates"][$dateKey]["shifts"][$shiftIndex]["name"] = $shift["shift_name"];
                            $content["schedules"]["entry_tickets"]["dates"][$dateKey]["shifts"][$shiftIndex]["start"] = $shift["shift_time"];
                            $content["schedules"]["entry_tickets"]["dates"][$dateKey]["shifts"][$shiftIndex]["end"] = date("h:i A", strtotime($shift["shift_time"] . " +" . $shift["duration"] . " minutes"));
                            //TODO Calculate tickets
                            $ticketAbility = $DB->sql("SELECT SUM(total_ticket) AS booked FROM orders WHERE ticket_category = 'entryTicket' AND `travel_date` = '" . $dateKey . "' AND shift_id = '" . $shift["shift_id"] . "' AND status = '5'", 0, 0, 0);
                            if (isset($ticketAbility[0]["booked"])) {
                                $content["schedules"]["entry_tickets"]["dates"][$dateKey]["shifts"][$shiftIndex]["tickets"] = $shift["tickets"] - $ticketAbility[0]["booked"];
                            } else {
                                $content["schedules"]["entry_tickets"]["dates"][$dateKey]["shifts"][$shiftIndex]["tickets"] = $shift["tickets"];
                            }

                        }
                    } else {
                        $ticketAbility = $DB->sql("SELECT SUM(total_ticket) AS booked FROM orders WHERE ticket_category = 'entryTicket' AND `travel_date` = '" . $dateKey . "' AND status = '5'", 0, 0, 0);
                        if (isset($ticketAbility[0]["booked"])) {
                            $hasTicket = $content["schedules"]["entry_tickets"]["dates"][$dateKey]["tickets"];
                            $content["schedules"]["entry_tickets"]["dates"][$dateKey]["tickets"] = $hasTicket - $ticketAbility[0]["booked"];
                        }
                        $content["schedules"]["entry_tickets"]["dates"][$dateKey]["shifts"] = [];
                    }

                }
            }


            $safariAllocation = $DB->sql("SELECT * FROM allocation WHERE ticket_category = 'safariTicket' AND `status` = '1' ", 0, 0, 0);
            $safariAlloJson = json_decode($safariAllocation[0]["value"], true);

            for ($i = 0; $i < $advanceDate; $i++) {
                $countDay = $i;
                $dateKey = date("d/m/Y", strtotime(strtotime($today) . ' + ' . $countDay . ' day'));
                $dayKey = date("l", strtotime(strtotime($today) . ' + ' . $countDay . ' day'));

                $closeArray = array();
                if ($safariAlloJson["closed"] != '') {
                    $closeEx = explode("-", $safariAlloJson["closed"]);
                    $closeStart = trim($closeEx[0]);
                    $closeEnd = trim($closeEx[1]);
                    $begin = DateTime::createFromFormat("d/m/Y", $closeStart);
                    $end = DateTime::createFromFormat("d/m/Y", $closeEnd);
                    $diff = $end->diff($begin)->format("%a");
                    $closeDays = $diff + 1;

                    for ($k = 0; $k < $closeDays; $k++) {
                        $begin->modify('+' . ($k == 0 ? $k : 1) . ' day');
                        $makeKey = $begin->format("d/m/Y");
                        $closeArray[$makeKey] = 'closed';
                    }
                }

                if (array_key_exists($dateKey, $closeArray)) {
                    $content["schedules"]["safari_tickets"]["dates"][$dateKey] = 'closed';
                } elseif (isset($safariAlloJson["date"]) && array_key_exists($dateKey, $safariAlloJson["date"])) {

                    $content["schedules"]["safari_tickets"]["dates"][$dateKey] = $safariAlloJson["date"][$dateKey];

                    $shiftCheck = $DB->sql("SELECT * FROM shift WHERE ticket_category = 'safari_tickets' AND `shift_date` = '" . $dateKey . "'", 0, 0, 0);
                    if (count($shiftCheck) > 0) {
                        $shiftNum = 0;
                        foreach ($shiftCheck as $shift) {
                            $shiftIndex = $shiftNum++;

                            $content["schedules"]["safari_tickets"]["dates"][$dateKey]["shifts"][$shiftIndex]["id"] = $shift["shift_id"];
                            $content["schedules"]["safari_tickets"]["dates"][$dateKey]["shifts"][$shiftIndex]["name"] = $shift["shift_name"];
                            $content["schedules"]["safari_tickets"]["dates"][$dateKey]["shifts"][$shiftIndex]["start"] = $shift["shift_time"];
                            $content["schedules"]["safari_tickets"]["dates"][$dateKey]["shifts"][$shiftIndex]["end"] = date("h:i A", strtotime($shift["shift_time"] . " +" . $shift["duration"] . " minutes"));
                            //TODO Calculate tickets
                            $ticketAbility = $DB->sql("SELECT SUM(total_ticket) AS booked FROM orders WHERE ticket_category = 'safariTicket' AND `travel_date` = '" . $dateKey . "' AND shift_id = '" . $shift["shift_id"] . "' AND status = '5'", 0, 0, 0);
                            if (isset($ticketAbility[0]["booked"])) {
                                $content["schedules"]["safari_tickets"]["dates"][$dateKey]["shifts"][$shiftIndex]["tickets"] = $shift["tickets"] - $ticketAbility[0]["booked"];
                            } else {
                                $content["schedules"]["safari_tickets"]["dates"][$dateKey]["shifts"][$shiftIndex]["tickets"] = $shift["tickets"];
                            }

                        }
                    } else {
                        $ticketAbility = $DB->sql("SELECT SUM(total_ticket) AS booked FROM orders WHERE ticket_category = 'safariTicket' AND `travel_date` = '" . $dateKey . "' AND status = '5'", 0, 0, 0);
                        if (isset($ticketAbility[0]["booked"])) {
                            $hasTicket = $content["schedules"]["safari_tickets"]["dates"][$dateKey]["tickets"];
                            $content["schedules"]["safari_tickets"]["dates"][$dateKey]["tickets"] = $hasTicket - $ticketAbility[0]["booked"];
                        }

                        $content["schedules"]["safari_tickets"]["dates"][$dateKey]["shifts"] = [];
                    }
                } else {
                    $content["schedules"]["safari_tickets"]["dates"][$dateKey] = $safariAlloJson[$dayKey];

                    $shiftCheck = $DB->sql("SELECT * FROM shift WHERE ticket_category = 'safari_tickets' AND `shift_date` = '" . $dayKey . "'", 0, 0, 0);
                    if (count($shiftCheck) > 0) {
                        $shiftNum = 0;
                        foreach ($shiftCheck as $shift) {
                            $shiftIndex = $shiftNum++;

                            $content["schedules"]["safari_tickets"]["dates"][$dateKey]["shifts"][$shiftIndex]["id"] = $shift["shift_id"];
                            $content["schedules"]["safari_tickets"]["dates"][$dateKey]["shifts"][$shiftIndex]["name"] = $shift["shift_name"];
                            $content["schedules"]["safari_tickets"]["dates"][$dateKey]["shifts"][$shiftIndex]["start"] = $shift["shift_time"];
                            $content["schedules"]["safari_tickets"]["dates"][$dateKey]["shifts"][$shiftIndex]["end"] = date("h:i A", strtotime($shift["shift_time"] . " +" . $shift["duration"] . " minutes"));
                            //TODO Calculate tickets
                            $ticketAbility = $DB->sql("SELECT SUM(total_ticket) AS booked FROM orders WHERE ticket_category = 'safariTicket' AND `travel_date` = '" . $dateKey . "' AND shift_id = '" . $shift["shift_id"] . "' AND status = '5'", 0, 0, 0);
                            if (isset($ticketAbility[0]["booked"])) {
                                $content["schedules"]["safari_tickets"]["dates"][$dateKey]["shifts"][$shiftIndex]["tickets"] = $shift["tickets"] - $ticketAbility[0]["booked"];
                            } else {
                                $content["schedules"]["safari_tickets"]["dates"][$dateKey]["shifts"][$shiftIndex]["tickets"] = $shift["tickets"];
                            }

                        }
                    } else {
                        $ticketAbility = $DB->sql("SELECT SUM(total_ticket) AS booked FROM orders WHERE ticket_category = 'safariTicket' AND `travel_date` = '" . $dateKey . "' AND status = '5'", 0, 0, 0);
                        if (isset($ticketAbility[0]["booked"])) {
                            $hasTicket = $content["schedules"]["safari_tickets"]["dates"][$dateKey]["tickets"];
                            $content["schedules"]["safari_tickets"]["dates"][$dateKey]["tickets"] = $hasTicket - $ticketAbility[0]["booked"];
                        }

                        $content["schedules"]["safari_tickets"]["dates"][$dateKey]["shifts"] = [];
                    }

                }
            }


            $purchaseVat = $DB->sql("SELECT `value` FROM core WHERE `key` = 'purchase_vat'", 0, 0, 0);
            $purchaseVat = $purchaseVat[0]["value"];

            $ticketValid = $DB->sql("SELECT `value` FROM core WHERE `key` = 'ticket_valid'", 0, 0, 0);
            $ticketValid = $ticketValid[0]["value"];

            $ticketLimit = $DB->sql("SELECT `value` FROM core WHERE `key` = 'ticket_limit'", 0, 0, 0);
            $ticketLimit = $ticketLimit[0]["value"];

            $content["advance_date"] = $advanceDays;
            $content["valid"] = $ticketValid;
            $content["limit"] = $ticketLimit;
            $content["fee"] = $purchaseVat;
            $content["payment"] = APP_DOM . 'payment/';


            echo json_encode($content);

        }
        //Tickets


        //Account
        if (isset($_GET["account"])) {
            $userToken = $_GET["token"];
            $checkUser = $DB->sql("SELECT user_id, first_name, email, phone FROM `user` WHERE `token` = '" . $userToken . "'", 0, 0, 0);
            if (count($checkUser) > 0) {
                $checkUser = $checkUser[0];
                $content = array();
                $content["account"]["id"] = $checkUser["user_id"];
                $content["account"]["name"] = $checkUser["first_name"];
                $content["account"]["email"] = $checkUser["email"];
                $content["account"]["phone"] = $checkUser["phone"];
                echo json_encode($content);
            } else {
                echo '{"Status":405,"Type":"Warning","Message":"লগইন তথ্য পরিবর্তন করা হয়েছে","Token":"' . md5(rand()) . '"}';
            }
        }
        //Account


        //User Tickets
        if (isset($_GET["myTicket"])) {
            $userToken = $_GET["token"];
            $checkUser = $DB->sql("SELECT user_id, first_name, email, phone FROM `user` WHERE `token` = '" . $userToken . "'", 0, 0, 0);
            if (count($checkUser) > 0) {
                $checkUser = $checkUser[0];
                $content = array();
                $userTickets = $DB->sql("SELECT * FROM `orders` WHERE `user_id` = '" . $checkUser["user_id"] . "'", 0, 0, 0);
                if (count($userTickets) > 0) {
                    $num = 0;
                    foreach ($userTickets as $ticket) {
                        $index = $num++;

                        if ($ticket["status"] == '3' || $ticket["status"] == '4' || $ticket["status"] == '5') {
                            $shiftID = $ticket["shift_id"];
                            $ticketType = $ticket["ticket_category"];
                            $travelDate = date("d/m/Y", $ticket["travel_date"]);
                            $dayName = date("l", $ticket["travel_date"]);


                            if ($shiftID != '1') {
                                $shiftInfo = $DB->sql("SELECT shift_name, shift_time, duration FROM `shift` WHERE shift_id = '" . $shiftID . "'", 0, 0, 0);
                                if (count($shiftInfo) > 0) {
                                    $shiftInfo = $shiftInfo[0];
                                    $visit_time = '<span class="bangla">' . $shiftInfo["shift_name"] . '</span> (';
                                    $visit_time .= $shiftInfo["shift_time"];
                                    $visit_time .= ' - ';
                                    $visit_time .= (date("h:i A", strtotime($shiftInfo["shift_time"] . " +" . $shiftInfo["duration"] . " minutes")));
                                    $visit_time .= ')';
                                }
                            } else {
                                $Allocation = $DB->sql("SELECT * FROM allocation WHERE ticket_category = '" . $ticketType . "' AND `status` = '1' ", 0, 0, 0);
                                $AlloJson = json_decode($Allocation[0]["value"], true);

                                if (isset($AlloJson["date"]) && array_key_exists($travelDate, $AlloJson["date"])) {
                                    $visit_time = '<span class="bangla">সারাদিন</span> (';
                                    $visit_time .= $AlloJson["date"][$travelDate]["open"];
                                    $visit_time .= ' - ';
                                    $visit_time .= $AlloJson["date"][$travelDate]["close"];
                                    $visit_time .= ')';
                                } else {
                                    $visit_time = '<span class="bangla">সারাদিন</span> (';
                                    $visit_time .= $AlloJson[$dayName]["open"];
                                    $visit_time .= ' - ';
                                    $visit_time .= $AlloJson[$dayName]["close"];
                                    $visit_time .= ')';
                                }
                            }


                            $content["bookings"][$index]["id"] = $ticket["order_id"];
                            $content["bookings"][$index]["invoice_id"] = $ticket["invoice_id"];
                            $content["bookings"][$index]["qr_code"] = $ticket["base64_qr"];
                            $content["bookings"][$index]["PNR"] = $ticket["pnr_number"];
                            $content["bookings"][$index]["phone"] = $ticket["phone_number"];
                            $content["bookings"][$index]["status"] = $ticket["status"];
                            $content["bookings"][$index]["total_amount"] = number_format($ticket["ticket_amount"], 2);
                            $content["bookings"][$index]["type"] = $ticket["ticket_category"];
                            $content["bookings"][$index]["visit_date"] = $ticket["travel_date"];
                            $content["bookings"][$index]["visit_time"] = $visit_time;
                            $content["bookings"][$index]["valid_date"] = $ticket["valid_date"];
                            $content["bookings"][$index]["visited_date"] = $ticket["visit_date"];
                            $content["bookings"][$index]["created_at"] = $ticket["time"];
                        } else {
                            $content["bookings"][$index]["id"] = $ticket["order_id"];
                            $content["bookings"][$index]["invoice_id"] = $ticket["invoice_id"];
                            $content["bookings"][$index]["qr_code"] = $ticket["base64_qr"];
                            $content["bookings"][$index]["PNR"] = "NA";
                            $content["bookings"][$index]["phone"] = $ticket["phone_number"];
                            $content["bookings"][$index]["status"] = $ticket["status"];
                            $content["bookings"][$index]["total_amount"] = number_format($ticket["ticket_amount"], 2);
                            $content["bookings"][$index]["type"] = $ticket["ticket_category"];
                            $content["bookings"][$index]["visit_date"] = "NA";
                            $content["bookings"][$index]["visit_time"] = "NA";
                            $content["bookings"][$index]["valid_date"] = "NA";
                            $content["bookings"][$index]["visited_date"] = "NA";
                            $content["bookings"][$index]["created_at"] = $ticket["time"];
                        }


                        $ticketJson = json_decode($ticket["ticket_data"], true, 512, JSON_UNESCAPED_UNICODE);

                        $child = 0;
                        foreach ($ticketJson as $key => $val) {
                            $bit = $child++;
                            $ticketName = $val[0]["name"];
                            $content["bookings"][$index]["tickets"][$bit]["name"] = $ticketName;
                            $content["bookings"][$index]["tickets"][$bit]["total"] = count($val);

                        }
                    }
                } else {
                    $content["bookings"] = array();
                }

                echo json_encode($content);

            } else {
                echo '{"Status":405,"Type":"Warning","Message":"লগইন তথ্য পরিবর্তন করা হয়েছে","Token":"' . md5(rand()) . '"}';
            }
        }
        //User Tickets


        //Login Start
        if (isset($_GET["login"])) {
            if (isset($postdata)) {
                $request = json_decode($postdata);

                $username = (isset($request->username) ? strtolower($request->username) : '');
                $password = (isset($request->password) ? $request->password : '');
                $platform = (isset($request->platform) ? $request->platform : '');
                $lang = (isset($request->language) ? $request->language : 'bn');

                if ($username == "") {
                    if ($lang == 'bn') {
                        echo '{"Status":407,"Type":"Warning","Message":"ব্যবহারকারীর নাম ফাঁকা হতে পারে না।","Token":"' . md5(rand()) . '"}';
                    } else {
                        echo '{"Status":407,"Type":"Warning","Message":"Username cannot be empty.","Token":"' . md5(rand()) . '"}';
                    }
                } elseif ($password == "") {
                    if ($lang == 'bn') {
                        echo '{"Status":407,"Type":"Warning","Message":"পাসওয়ার্ড খালি হতে পারে না","Token":"' . md5(rand()) . '"}';
                    } else {
                        echo '{"Status":407,"Type":"Warning","Message":"Password cannot be empty.","Token":"' . md5(rand()) . '"}';
                    }
                } else {

                    //Login Function
                    $sql = "SELECT * FROM `user` WHERE email = '" . $username . "' AND password = '" . sha1($password) . "' OR phone = '" . $username . "' AND password = '" . sha1($password) . "'";
                    $check_login = $DB->sql($sql, 0, 0, 0);

                    if (count($check_login) > 0) {
                        $check_login = $check_login["0"];

                        if ($check_login["status"] == '1' || $check_login["status"] == '3' || $check_login["status"] == '4') {

                            $DB->sql("UPDATE `user` SET token = '" . $token . "', last_seen = '" . $time . "', platform = '" . $platform . "' WHERE user_id = '" . $check_login["user_id"] . "'", 0, 0, 0);

                            if ($lang == 'bn') {
                                echo '{"Status":408,"Type":"Verified","Message":"ব্যবহারকারী অ্যাকাউন্ট বৈধ।","Token":"' . $token . '"}';
                            } else {
                                echo '{"Status":408,"Type":"Verified","Message":"User account has been verified.","Token":"' . $token . '"}';
                            }

                        } elseif ($check_login["status"] == '0') {
                            if ($lang == 'bn') {
                                echo '{"Status":405,"Type":"Warning","Message":"ইমেল ঠিকানা যাচাই করা হয়নি।","Token":"' . md5(rand()) . '"}';
                            } else {
                                echo '{"Status":405,"Type":"Warning","Message":"Email address not verified yet.","Token":"' . md5(rand()) . '"}';
                            }
                        } elseif ($check_login["status"] == '2') {
                            if ($lang == 'bn') {
                                echo '{"Status":407,"Type":"Warning","Message":"আপনার অ্যাকাউন্ট নিষিদ্ধ করা হয়েছে।","Token":"' . md5(rand()) . '"}';
                            } else {
                                echo '{"Status":407,"Type":"Warning","Message":"Your account has been banned.","Token":"' . md5(rand()) . '"}';
                            }
                        } else {
                            if ($lang == 'bn') {
                                echo '{"Status":407,"Type":"Warning","Message":"অজানা ত্রুটি","Token":"' . md5(rand()) . '"}';
                            } else {
                                echo '{"Status":407,"Type":"Warning","Message":"Unknown error.","Token":"' . md5(rand()) . '"}';
                            }
                        }

                    } else {
                        if ($lang == 'bn') {
                            echo '{"Status":407,"Type":"Warning","Message":"ব্যবহারকারীর অ্যাকাউন্ট পাওয়া যায় নি","Token":"' . md5(rand()) . '"}';
                        } else {
                            echo '{"Status":407,"Type":"Warning","Message":"User account not found.","Token":"' . md5(rand()) . '"}';
                        }
                    }
                }

            } else {
                echo '{"Status":407,"Type":"Warning","Message":"No user login sent.","Token":"' . md5(rand()) . '"}';
            }
        }
        //Login End


        //Password Change
        if (isset($_GET["password"])) {
            if (isset($postdata)) {
                $request = json_decode($postdata);
                $userToken = (isset($_GET["token"]) ? $_GET["token"] : '');
                $old_password = (isset($request->old_password) ? $request->old_password : '');
                $password = (isset($request->password) ? $request->password : '');
                $re_password = (isset($request->re_password) ? $request->re_password : '');
                $lang = (isset($request->language) ? $request->language : 'bn');

                $passCheck = $DB->sql("SELECT user_id FROM `user` WHERE `password` = '" . sha1($old_password) . "'", 0, 0, 0);

                if ($userToken == '') {
                    if ($lang == 'bn') {
                        echo '{"Status":407,"Type":"Warning","Message":"ব্যবহারকারীর অ্যাক্সেস টোকেন অনুপস্থিত","Token":"' . md5(rand()) . '"}';
                    } else {
                        echo '{"Status":407,"Type":"Warning","Message":"User access token is missing.","Token":"' . md5(rand()) . '"}';
                    }
                } elseif ($old_password == '') {
                    if ($lang == 'bn') {
                        echo '{"Status":407,"Type":"Warning","Message":"পুরাতন পাসওয়ার্ড খালি হতে পারে না","Token":"' . md5(rand()) . '"}';
                    } else {
                        echo '{"Status":407,"Type":"Warning","Message":"Old Password cannot be empty.","Token":"' . md5(rand()) . '"}';
                    }
                } elseif (count($passCheck) == 0) {
                    if ($lang == 'bn') {
                        echo '{"Status":407,"Type":"Warning","Message":"পুরানো পাসওয়ার্ড অ্যাকাউন্টের সাথে মেলে না","Token":"' . md5(rand()) . '"}';
                    } else {
                        echo '{"Status":407,"Type":"Warning","Message":"Old Password not matching with account.","Token":"' . md5(rand()) . '"}';
                    }
                } elseif ($password == '') {
                    if ($lang == 'bn') {
                        echo '{"Status":407,"Type":"Warning","Message":"পাসওয়ার্ড খালি হতে পারে না","Token":"' . md5(rand()) . '"}';
                    } else {
                        echo '{"Status":407,"Type":"Warning","Message":"Password cannot be empty.","Token":"' . md5(rand()) . '"}';
                    }
                } elseif (strlen($password) < 6) {
                    if ($lang == 'bn') {
                        echo '{"Status":407,"Type":"Warning","Message":"দয়া করে একটি শক্তিশালী পাসওয়ার্ড ব্যবহার করুন","Token":"' . md5(rand()) . '"}';
                    } else {
                        echo '{"Status":407,"Type":"Warning","Message":"Please use a stronger password.","Token":"' . md5(rand()) . '"}';
                    }
                } elseif ($re_password == '') {
                    if ($lang == 'bn') {
                        echo '{"Status":407,"Type":"Warning","Message":"নিশ্চিত পাসওয়ার্ড খালি হতে পারে না","Token":"' . md5(rand()) . '"}';
                    } else {
                        echo '{"Status":407,"Type":"Warning","Message":"Confirm password cannot be empty.","Token":"' . md5(rand()) . '"}';
                    }
                } elseif ($password != $re_password) {
                    if ($lang == 'bn') {
                        echo '{"Status":407,"Type":"Warning","Message":"পাসওয়ার্ড একে অপরের সাথে মেলে না।","Token":"' . md5(rand()) . '"}';
                    } else {
                        echo '{"Status":407,"Type":"Warning","Message":"Password doesn\'t match with each other.","Token":"' . md5(rand()) . '"}';
                    }
                } else {
                    $checkUser = $DB->sql("SELECT user_id FROM `user` WHERE `token` = '" . $userToken . "'", 0, 0, 0);
                    if (count($checkUser) > 0) {
                        $DB->sql("UPDATE `user` SET token = '" . $token . "', last_seen = '" . $time . "', password = '" . sha1($password) . "' WHERE user_id = '" . $checkUser["user_id"] . "'", 0, 0, 0);

                        if ($lang == 'bn') {
                            echo '{"Status":408,"Type":"Verified","Message":"ব্যবহারকারী পাসওয়ার্ড পরিবর্তন করা হয়েছে","Token":"' . $token . '"}';
                        } else {
                            echo '{"Status":408,"Type":"Verified","Message":"User password has been changed.","Token":"' . $token . '"}';
                        }
                    } else {
                        if ($lang == 'bn') {
                            echo '{"Status":407,"Type":"Warning","Message":"ব্যবহারকারীর অ্যাকাউন্ট পাওয়া যায় নি","Token":"' . md5(rand()) . '"}';
                        } else {
                            echo '{"Status":407,"Type":"Warning","Message":"User account not found.","Token":"' . md5(rand()) . '"}';
                        }
                    }
                }

            } else {
                echo '{"Status":407,"Type":"Warning","Message":"No user password sent.","Token":"' . md5(rand()) . '"}';
            }
        }
        //Password Change


        //Registration Start
        if (isset($_GET["register"])) {
            if (isset($postdata)) {
                $request = json_decode($postdata);

                $first_name = (isset($request->name) ? $request->name : '');
                $email = (isset($request->email) ? $request->email : '');
                $phone = (isset($request->phone) ? $request->phone : '');
                $password = (isset($request->password) ? $request->password : '');
                $re_password = (isset($request->password_re) ? $request->password_re : '');
                $lang = (isset($request->language) ? $request->language : 'bn');


                $check_exist = $DB->sql("SELECT * FROM `user` WHERE email = '" . $email . "' OR phone = '" . $phone . "'", 0, 0, 0);

                if ($phone == "") {
                    if ($lang == 'bn') {
                        echo '{"Status":407,"Type":"Warning","Message":"মোবাইল নম্বর ফাঁকা হতে পারে না।","Token":"' . md5(rand()) . '"}';
                    } else {
                        echo '{"Status":407,"Type":"Warning","Message":"Mobile number cannot be empty.","Token":"' . md5(rand()) . '"}';
                    }
                } elseif (strlen($phone) < 11) {
                    if ($lang == 'bn') {
                        echo '{"Status":407,"Type":"Warning","Message":"মোবাইল নম্বরটি খুব ছোট।","Token":"' . md5(rand()) . '"}';
                    } else {
                        echo '{"Status":407,"Type":"Warning","Message":"Mobile number is too short.","Token":"' . md5(rand()) . '"}';
                    }
                } elseif ($password == "") {
                    if ($lang == 'bn') {
                        echo '{"Status":407,"Type":"Warning","Message":"পাসওয়ার্ড খালি হতে পারে না","Token":"' . md5(rand()) . '"}';
                    } else {
                        echo '{"Status":407,"Type":"Warning","Message":"Password cannot be empty.","Token":"' . md5(rand()) . '"}';
                    }
                } elseif (strlen($password) < 6) {
                    if ($lang == 'bn') {
                        echo '{"Status":407,"Type":"Warning","Message":"দয়া করে একটি শক্তিশালী পাসওয়ার্ড ব্যবহার করুন","Token":"' . md5(rand()) . '"}';
                    } else {
                        echo '{"Status":407,"Type":"Warning","Message":"Please use a stronger password.","Token":"' . md5(rand()) . '"}';
                    }
                } elseif ($re_password == '') {
                    if ($lang == 'bn') {
                        echo '{"Status":407,"Type":"Warning","Message":"পুরাতন পাসওয়ার্ড খালি হতে পারে না","Token":"' . md5(rand()) . '"}';
                    } else {
                        echo '{"Status":407,"Type":"Warning","Message":"Old password cannot be empty.","Token":"' . md5(rand()) . '"}';
                    }
                } elseif ($password != $re_password) {
                    if ($lang == 'bn') {
                        echo '{"Status":407,"Type":"Warning","Message":"পাসওয়ার্ড একে অপরের সাথে মেলে না।","Token":"' . md5(rand()) . '"}';
                    } else {
                        echo '{"Status":407,"Type":"Warning","Message":"Password doesn\'t match with each other.","Token":"' . md5(rand()) . '"}';
                    }
                } elseif ($email == "") {
                    if ($lang == 'bn') {
                        echo '{"Status":407,"Type":"Warning","Message":"ইমেল ফাঁকা হতে পারে না।","Token":"' . md5(rand()) . '"}';
                    } else {
                        echo '{"Status":407,"Type":"Warning","Message":"Email cannot be empty.","Token":"' . md5(rand()) . '"}';
                    }
                } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                    if ($lang == 'bn') {
                        echo '{"Status":407,"Type":"Warning","Message":"অকার্যকর ইমেইল ঠিকানা","Token":"' . md5(rand()) . '"}';
                    } else {
                        echo '{"Status":407,"Type":"Warning","Message":"Invalid email address.","Token":"' . md5(rand()) . '"}';
                    }
                } elseif (count($check_exist) > 0) {
                    if ($lang == 'bn') {
                        echo '{"Status":407,"Type":"Warning","Message":"ইমেল ঠিকানা বা মোবাইল নম্বর ইতিমধ্যে বিদ্যমান।","Token":"' . md5(rand()) . '"}';
                    } else {
                        echo '{"Status":407,"Type":"Warning","Message":"Email address of mobile number already exist.","Token":"' . md5(rand()) . '"}';
                    }
                } elseif ($first_name == "") {
                    if ($lang == 'bn') {
                        echo '{"Status":407,"Type":"Warning","Message":"নাম খালি হতে পারে না","Token":"' . md5(rand()) . '"}';
                    } else {
                        echo '{"Status":407,"Type":"Warning","Message":"Name cannot be empty.","Token":"' . md5(rand()) . '"}';
                    }
                } else {

                    $username = $encryption->random_key();
                    $unique_id = $encryption->random_key();

                    //Registration Function
                    $sql = "INSERT INTO `user` (
                                  `user_id`,
                                  `unique_id`,
                                  `username`,
                                  `password`,
                                  `email`,
                                  `phone`,
                                  `first_name`,
                                  `last_name`,
                                  `date_of_birth`,
                                  `picture`,
                                  `register_ip`,
                                  `register_time`,
                                  `last_seen`,
                                  `notification`,
                                  `key`,
                                  `token`,
                                  `deviceToken`,
                                  `platform`,
                                  `provider`,
                                  `social_id`,
                                  `social_url`,
                                  `state`,
                                  `status`,
                                  `verified`,
                                  `2_step`,
                                  `modify_by`
                                )
                              VALUES (
                                  NULL,
                                  '" . $unique_id . "',
                                  '" . $username . "',
                                  '" . sha1($password) . "',
                                  '" . $email . "',
                                  '" . $phone . "',
                                  '" . $first_name . "',
                                  '',
                                  '',
                                  '',
                                  '" . $ip . "',
                                  '" . $time . "',
                                  '" . $time . "',
                                  '1',
                                  '" . $key . "',
                                  '',
                                  '',
                                  '',
                                  '',
                                  '',
                                  '',
                                  '0',
                                  '0',
                                  '0',
                                  '0',
                                  '0'
                              );";

                    $DB->sql($sql, 0, 0, 0);

                    $_input = array();
                    $_input[] = 'You’re Almost Finished...'; //Heading
                    $_input[] = "We need to confirm your email address to complete the sign-up process. Please click the link below and confirm your email address for further notification and security."; //Message
                    $_input[] = "Confirm Email"; //Button text
                    $_input[] = (APP_DOM . "confirm/" . $key); //Button link

                    $_output = $email_template->execute(DIR_VIEW . "email_template", $_input);

                    $mail->addAddress($email, $first_name);
                    $mail->isHTML(true);
                    $mail->Subject = 'Email Confirmation';
                    $mail->Body = $_output;
                    $mail->send();


                    if ($lang == 'bn') {
                        echo '{"Status":408,"Type":"Verified","Message":"নতুন অ্যাকাউন্ট তৈরি করা হয়েছে, আপনার ইমেইল যাচাই করুন।","Token":"' . md5(rand()) . '"}';
                    } else {
                        echo '{"Status":408,"Type":"Verified","Message":"Account has been created, please verify your email.","Token":"' . md5(rand()) . '"}';
                    }
                }

            } else {
                echo '{"Status":407,"Type":"Warning","Message":"No user login sent.","Token":"' . md5(rand()) . '"}';
            }
        }
        //Registration End


        //Forget Start
        if (isset($_GET["forget"])) {
            if (isset($postdata)) {
                $request = json_decode($postdata);

                $email = (isset($request->email) ? $request->email : '');
                $lang = (isset($request->language) ? $request->language : 'bn');

                if ($email == "") {
                    if ($lang == 'bn') {
                        echo '{"Status":407,"Type":"Warning","Message":"আপনাকে অ্যাকাউন্ট তথ্য সরবরাহ করতে হবে।","Token":"' . md5(rand()) . '"}';
                    } else {
                        echo '{"Status":407,"Type":"Warning","Message":"You have to provide account information.","Token":"' . md5(rand()) . '"}';
                    }
                } elseif ($email != "") {
                    /////
                    $check_email = $DB->sql("SELECT * FROM `user` WHERE email =  '" . $email . "' OR phone = '" . $email . "'", 0, 0, 0);

                    if (count($check_email) > 0) {

                        $user_data = $check_email["0"];
                        $first_name = $user_data["first_name"];
                        $email = $user_data["email"];

                        $DB->sql("UPDATE `user` SET `key` = '" . $key . "', last_seen = '" . $time . "' WHERE `user_id` = '" . $user_data["user_id"] . "'", 0, 0, 0);

                        $_input = array();
                        $_input[] = $first_name; //Heading
                        $_input[] = "Seems like you have forgotten your password. Use a common password with mixed combination for security or save your password somewhere safe. Never share your password with anyone else nor use your cellphone number as password. A strong password insure security of your account and our system.<br/> - Thanks"; //Message
                        $_input[] = "Reset Password"; //Button text
                        $_input[] = (APP_DOM . "reset/" . $key); //Button link

                        $_output = $email_template->execute(DIR_VIEW . "email_template", $_input);

                        $mail->addAddress($email, $first_name);
                        $mail->isHTML(true);
                        $mail->Subject = 'Password Reset';
                        $mail->Body = $_output;
                        $mail->send();


                        if ($lang == 'bn') {
                            echo '{"Status":408,"Type":"Verified","Message":"দয়া করে আপনার ইমেল চেক করুন","Token":"' . md5(rand()) . '"}';
                        } else {
                            echo '{"Status":408,"Type":"Verified","Message":"Please check your email.","Token":"' . md5(rand()) . '"}';
                        }
                    } else {
                        if ($lang == 'bn') {
                            echo '{"Status":407,"Type":"Warning","Message":"ব্যবহারকারীর অ্যাকাউন্ট পাওয়া যায় নি","Token":"' . md5(rand()) . '"}';
                        } else {
                            echo '{"Status":407,"Type":"Warning","Message":"User account not found.","Token":"' . md5(rand()) . '"}';
                        }
                    }
                }
            } else {
                echo '{"Status":407,"Type":"Warning","Message":"No user login sent.","Token":"' . md5(rand()) . '"}';
            }
        }
        //Forget End


        //Resend Start
        if (isset($_GET["resend"])) {
            if (isset($postdata)) {
                $request = json_decode($postdata);

                $username = (isset($request->username) ? strtolower($request->username) : '');

                if ($username == "") {
                    echo '{"Status":407,"Type":"Warning","Message":"Username cannot be empty.","Token":"' . md5(rand()) . '"}';
                } else {

                    //Resend Function
                    $check_username = $DB->sql("SELECT * FROM `user` WHERE username =  '" . $username . "'", 0, 0, 0);

                    if (count($check_username) > 0) {
                        $user_data = $check_username["0"];
                        $first_name = $user_data["first_name"];
                        $email = $user_data["email"];

                        $DB->sql("UPDATE user SET `key` = '" . $key . "' WHERE user_id = '" . $user_data["user_id"] . "'", 0, 0, 0);

                        $_input = array();
                        $_input[] = 'You’re Almost Finished...'; //Heading
                        $_input[] = "We need to confirm your email address. To complete the sign-up process, please click the link below."; //Message
                        $_input[] = "Confirm Email"; //Button text
                        $_input[] = (APP_DOM . "confirm/" . $key); //Button link

                        $_output = $email_template->execute(DIR_VIEW . "email_template", $_input);

                        $mg->sendMessage($domain, array(
                            'from' => 'Retail Email <noreply@retail.email>',
                            'to' => $first_name . ' <' . $email . '>',
                            'subject' => 'Retail Email Confirmation',
                            'html' => $_output
                        ));


                        echo '{"Status":408,"Type":"Verified","Message":"User account verified.","Token":"' . $token . '"}';
                    } else {
                        echo '{"Status":407,"Type":"Warning","Message":"No user account found.","Token":"' . md5(rand()) . '"}';
                    }
                }

            } else {
                echo '{"Status":407,"Type":"Warning","Message":"No user login sent.","Token":"' . md5(rand()) . '"}';
            }
        }
        //Resend End

        //Post Data

    } else {
        echo '{"Status":403,"Type":"Invalid","Message":"The security token is invalid or expired.","Token":"' . md5(rand()) . '"}';
    }

} else {
    echo '{"Status":403,"Type":"Error","Message":"This server is protected by security token.","Token":"' . md5(rand()) . '"}';
}

