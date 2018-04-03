<?php
/**
 * Created by PhpStorm.
 * User: ahstanin
 * Date: 8/22/17
 * Time: 11:23 AM
 */

use Endroid\QrCode\QrCode;


function _ipn_hash_varify($store_passwd = "")
{

    if (isset($_POST) && isset($_POST['verify_sign']) && isset($_POST['verify_key'])) {
        # NEW ARRAY DECLARED TO TAKE VALUE OF ALL POST

        $pre_define_key = explode(',', $_POST['verify_key']);

        $new_data = array();
        if (!empty($pre_define_key)) {
            foreach ($pre_define_key as $value) {
                if (isset($_POST[$value])) {
                    $new_data[$value] = ($_POST[$value]);
                }
            }
        }
        # ADD MD5 OF STORE PASSWORD
        $new_data['store_passwd'] = ($store_passwd);

        # SORT THE KEY AS BEFORE
        ksort($new_data);

        $hash_string = "";
        foreach ($new_data as $key => $value) {
            $hash_string .= $key . '=' . ($value) . '&';
        }
        $hash_string = rtrim($hash_string, '&');

        if (md5($hash_string) == $_POST['verify_sign']) {

            return true;

        } else {
            return false;
        }
    } else return false;
}


$storeID = "testbox";
$storePass = "qwerty";


if (isset($_GET["for"])) {
    $for = $_GET["for"];
    if (strlen($for) == 45) {
        $json = json_decode($_GET["job"], true);

        $userData = $DB->sql("SELECT * FROM `user` WHERE `token` = '" . $for . "'", 0, 0, 0);


        if (count($userData) > 0) {

            if (isset($json[0]["entry_tickets"])) {
                $json = $json[0];

                //InvoiceData
                $purchaseVat = $DB->sql("SELECT `value` FROM core WHERE `key` = 'purchase_vat'", 0, 0, 0);
                $purchaseVat = $purchaseVat[0]["value"];
                $invoiceID = $encryption->random_invoice();
                $PNR = $encryption->random_number();
                $userID = $userData[0]["user_id"];
                $userPhone = $userData[0]["phone"];
                $platform = (isset($json["platform"]) ? $json["platform"] : 'app');

                if (count($json["entry_tickets"]) > 0) {

                    $travelDate = $json["entry_travel_date"];
                    $validDate = $json["entry_valid_date"];
                    $shiftID = $json["entry_timeID"];

                    $cartAmount = 0;
                    $totalTicket = 0;
                    $numStore = 0;
                    $tickets = array();
                    $cartTickets = array();

                    $numVal = 0;
                    foreach ($json["entry_tickets"] as $ticket) {
                        $numIndex = $numVal++;
                        $ticketID = $ticket["ticketID"];
                        $ticketQuantity = $ticket["ticketQuantity"];
                        $ticketPriceDB = $DB->sql("SELECT ticket_name, ticket_details, ticket_price FROM `ticket` WHERE `ticket_id` = '" . $ticketID . "'", 0, 0, 0);
                        $ticketPrice = $ticketPriceDB[0]["ticket_price"];

                        if (count($ticketPriceDB) == 0) {
                            exit('{"Status:"407", "Message":"Error fetching tickets informations."}');
                        }


                        for ($i = 0; $i < $ticketQuantity; $i++) {
                            $numStore += $i;
                            $tickets[$ticketID][$i]["id"] = $ticketID;
                            $tickets[$ticketID][$i]["name"] = $ticketPriceDB[0]["ticket_name"];
                            $tickets[$ticketID][$i]["details"] = sanitize_output($ticketPriceDB[0]["ticket_details"]);
                            $tickets[$ticketID][$i]["price"] = $ticketPriceDB[0]["ticket_price"];

                            $cartTickets[$numIndex]["product"] = $ticketPriceDB[0]["ticket_name"];
                            $cartTickets[$numIndex]["details"] = $ticketPriceDB[0]["ticket_details"];
                        }

                        $cartTickets[$numIndex]["amount"] = $ticketPrice * $ticketQuantity;
                        $cartTickets[$numIndex]["price"] = $ticketPrice;
                        $cartTickets[$numIndex]["quantity"] = $ticketQuantity;
                        $cartAmount += $ticketPrice * $ticketQuantity;
                        $totalTicket += $ticketQuantity;
                    }

                    $subTotal = $cartAmount;

                    $parkEntryTime = '';

                    if ($shiftID != 1) {
                        $checkTicketAbility = $DB->sql("SELECT SUM(total_ticket) AS booked FROM orders WHERE ticket_category = 'entryTicket' AND `travel_date` = '" . $travelDate . "' AND shift_id = '" . $shiftID . "' AND status = '5'", 0, 0, 0);
                        $shiftTicket = $DB->sql("SELECT tickets, shift_time FROM `shift` WHERE `shift_id` = '" . $shiftID . "'", 0, 0, 0);

                        $parkEntryTime = $shiftTicket[0]["shift_time"];
                        $shiftTicket = $shiftTicket[0]["tickets"];

                        if (isset($checkTicketAbility[0]["booked"])) {
                            $checkTicketAbility = $checkTicketAbility[0]["booked"];
                            $hasTicket = $shiftTicket - $checkTicketAbility;
                        } else {
                            $hasTicket = $shiftTicket;
                        }

                        if ($totalTicket > $hasTicket) {

                            if ($platform == 'web') {
                                exit('{"Status:"407", "Message":"Not enough tickets found for selected date."}');
                            } else {
                                header("Location: " . APP_DOM . "payment/noticket/");
                                exit;
                            }


                        }

                    } else {

                        $dateFormat = DateTime::createFromFormat("d/m/Y", $travelDate);
                        $dayName = $dateFormat->format('l');

                        $checkTicketAbility = $DB->sql("SELECT SUM(total_ticket) AS booked FROM orders WHERE ticket_category = 'entryTicket' AND `travel_date` = '" . $travelDate . "' AND shift_id = '1' AND status = '5'", 0, 0, 0);

                        $entryAllocation = $DB->sql("SELECT * FROM allocation WHERE ticket_category = 'entryTicket' AND `status` = '1' ", 0, 0, 0);
                        $entryAlloJson = json_decode($entryAllocation[0]["value"], true);

                        if (isset($checkTicketAbility[0]["booked"])) {
                            $checkTicketAbility = $checkTicketAbility[0]["booked"];
                            if (isset($entryAlloJson["date"]) && array_key_exists($travelDate, $entryAlloJson["date"])) {
                                $jsonTicket = $entryAlloJson["date"][$travelDate]["tickets"];
                                $parkEntryTime = $entryAlloJson["date"][$travelDate]["open"];
                            } else {
                                $jsonTicket = $entryAlloJson[$dayName]["tickets"];
                                $parkEntryTime = $entryAlloJson[$dayName]["open"];
                            }

                            $hasTicket = $jsonTicket - $checkTicketAbility;
                        } else {
                            $checkTicketAbility = $checkTicketAbility[0]["booked"];
                            if (isset($entryAlloJson["date"]) && array_key_exists($travelDate, $entryAlloJson["date"])) {
                                $jsonTicket = $entryAlloJson["date"][$travelDate]["tickets"];
                                $parkEntryTime = $entryAlloJson["date"][$travelDate]["open"];
                            } else {
                                $jsonTicket = $entryAlloJson[$dayName]["tickets"];
                                $parkEntryTime = $entryAlloJson[$dayName]["open"];
                            }

                            $hasTicket = $jsonTicket;
                        }

                        if ($totalTicket > $hasTicket) {
                            if ($platform == 'web') {
                                exit('{"Status:"407", "Message":"Not enough tickets found for selected date."}');
                            } else {
                                header("Location: " . APP_DOM . "payment/noticket/");
                                exit;
                            }
                        }

                    }


                    $travelDate = DateTime::createFromFormat("d/m/Y h:i A", trim($travelDate . " " . $parkEntryTime));
                    $travelDate = $travelDate->format("U");

                    $validDate = DateTime::createFromFormat("d/m/Y h:i A", trim($validDate . " " . $parkEntryTime));
                    $validDate = $validDate->format("U");


                    $vatAmount = ($purchaseVat / 100) * $cartAmount;
                    $cartAmount = $cartAmount + $vatAmount;

                    $unique_id = $encryption->random_key();
                    $qrCode = new QrCode($unique_id);
                    $base64QR = 'data:image/png;base64,' . base64_encode($qrCode->writeString());

                    $sql = "INSERT INTO `orders` (
                          `order_id`,
                          `unique_id`,
                          `user_id`,
                          `shift_id`,
                          `invoice_id`,
                          `base64_qr`,
                          `ticket_category`,
                          `total_ticket`,
                          `travel_date`,
                          `valid_date`,
                          `phone_number`,
                          `pnr_number`,
                          `ticket_data`,
                          `transaction_id`,
                          `transaction_data`,
                          `ticket_amount`,
                          `total_amount`,
                          `time`,
                          `payment_time`,
                          `user_ip`,
                          `platform`,
                          `visit_date`,
                          `modified_by`,
                          `status`
                        )
                      VALUES (
                          NULL,
                          '" . $unique_id . "',
                          '" . $userID . "',
                          '" . $shiftID . "',
                          '" . $invoiceID . "',
                          '" . $base64QR . "',
                          'entryTicket',
                          '" . $totalTicket . "',
                          '" . $travelDate . "',
                          '" . $validDate . "',
                          '" . $userPhone . "',
                          '" . $PNR . "',
                          '" . json_encode($tickets, JSON_UNESCAPED_UNICODE) . "',
                          '',
                          '',
                          '" . $cartAmount . "',
                          '',
                          '" . $time . "',
                          '',
                          '" . $ip . "',
                          '" . $platform . "',
                          '',
                          '0',
                          '0'
                      );";
                    $DB->sql($sql, 0, 0, 0);


                    $post_data = [];
                    $post_data['store_id'] = $storeID;
                    $post_data['store_passwd'] = $storePass;
                    $post_data['total_amount'] = $cartAmount;
                    $post_data['currency'] = "BDT";
                    $post_data['tran_id'] = $invoiceID;
                    $post_data['success_url'] = APP_DOM . 'payment/success/' . ($platform == 'web' ? 'web' : '');
                    $post_data['fail_url'] = APP_DOM . 'payment/fail/' . ($platform == 'web' ? 'web' : '');
                    $post_data['cancel_url'] = APP_DOM . 'payment/cancel/' . ($platform == 'web' ? 'web' : '');
                    # CUSTOMER INFORMATION
                    $post_data['cus_name'] = $userData[0]["first_name"];
                    $post_data['cus_email'] = $userData[0]["email"];
                    $post_data['cus_add1'] = "Dhaka";
                    $post_data['cus_add2'] = "Dhaka";
                    $post_data['cus_city'] = "Dhaka";
                    $post_data['cus_state'] = "Dhaka";
                    $post_data['cus_postcode'] = "1000";
                    $post_data['cus_country'] = "Bangladesh";
                    $post_data['cus_phone'] = $userData[0]["phone"];
                    # REQUEST SEND TO SSLCOMMERZ

                    $cartNum = 0;
                    foreach ($cartTickets as $product) {
                        $cartIndex = $cartNum++;

                        $post_data['cart[' . $cartIndex . '][product]'] = $product["product"];
                        $post_data['cart[' . $cartIndex . '][amount]'] = $product["amount"];
                    }


					//TODO Change values

                    $direct_api_url = "https://sandbox.sslcommerz.com/gwprocess/v3/api.php";
                    $handle = curl_init();
                    curl_setopt($handle, CURLOPT_URL, $direct_api_url);
                    curl_setopt($handle, CURLOPT_TIMEOUT, 10);
                    curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 10);
                    curl_setopt($handle, CURLOPT_POST, 1);
                    curl_setopt($handle, CURLOPT_POSTFIELDS, $post_data);
                    curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
                    $content = curl_exec($handle);
                    $code = curl_getinfo($handle, CURLINFO_HTTP_CODE);
                    if ($code == 200 && !(curl_errno($handle))) {
                        curl_close($handle);
                        $sslcommerzResponse = $content;
                    } else {
                        curl_close($handle);
                        echo 'FAILED TO CONNECT WITH SSLCOMMERZ API';
                        exit;
                    }
                    # PARSE THE JSON RESPONSE
                    $sslcz = json_decode($sslcommerzResponse, TRUE);

                    ///Order Place Email
                    if (ORDER_EMAIL) {
                        $itemTable = '<table width="100%">';
                        $itemTable .= '<thead>
                                <tr>
                                    <th style="border-bottom: 2px solid #ddd;">#</th>
                                    <th style="text-align: left; border-bottom: 2px solid #ddd;">Ticket</th>
                                    <th style="text-align: left; border-bottom: 2px solid #ddd;">Quantity</th>
                                    <th style="text-align: left; border-bottom: 2px solid #ddd;">Unit Cost</th>
                                    <th style="text-align: right; border-bottom: 2px solid #ddd;">Total</th>
                                </tr>
                                </thead>';
                        $num = 1;
                        foreach ($cartTickets as $product) {
                            $bulletNum = $num++;

                            $itemTable .= '<tr>
                                    <td style="border-top: 1px solid #ddd;">' . $bulletNum . '</td>
                                    <td style="text-align: left; border-top: 1px solid #ddd;">
                                        <b>' . $product["product"] . '</b> <br>
                                        <span>' . strip_tags($product["details"]) . '</span>
                                    </td>
                                    <td style="text-align: left; border-top: 1px solid #ddd;">' . $product["quantity"] . '</td>
                                    <td style="text-align: left; border-top: 1px solid #ddd;">' . number_format($product["price"], 2) . ' BDT</td>
                                    <td style="text-align: right; border-top: 1px solid #ddd;">' . number_format($product["amount"], 2) . ' BDT</td>
                                </tr>';
                        }


                        $itemTable .= '<tr>
                                    <td colspan="3" style="border-top: 1px solid #ddd;"></td>
                                    <td style="text-align: left; border-top: 1px solid #ddd;">Sub Total</td>
                                    <td style="text-align: right; border-top: 1px solid #ddd;"><b>' . number_format($subTotal, 2) . ' BDT</b></td>
                                    </tr>';

                        $itemTable .= '<tr>
                                    <td colspan="3" style="border-top: 1px solid #ddd;"></td>
                                    <td style="text-align: left; border-top: 1px solid #ddd;">Transaction Fee</td>
                                    <td style="text-align: right; border-top: 1px solid #ddd;"><b>' . number_format($vatAmount, 2) . ' BDT</b></td>
                                    </tr>';

                        $itemTable .= '<tr>
                                    <td colspan="3" style="border-top: 1px solid #ddd;"></td>
                                    <td style="text-align: left; border-top: 1px solid #ddd;">Total</td>
                                    <td style="text-align: right; border-top: 1px solid #ddd;"><b>' . number_format($cartAmount, 2) . ' BDT</b></td>
                                    </tr>';


                        $itemTable .= '</table>';

                        $_input = array();
                        $_input[] = "Entry Ticket"; //Heading
                        $_input[] = 'Thank you <b>' . $userData[0]["first_name"] . '</b> for your recent order with ' . APP_NAME . '. Please make sure to pay your invoice before you receive your e-tickets. You can find your invoice details bellow.
                    <br>
                    <br>
                    <br>
                    
                    ' . $itemTable; //Message
                        $_input[] = "View Order"; //Button text
                        $_input[] = (APP_DOM); //Button link

                        $_output = $email_template->execute(DIR_VIEW . "email_template", $_input);

                        $mail->addAddress($userData[0]["email"], $userData[0]["first_name"]);
                        $mail->isHTML(true);
                        $mail->Subject = 'New order has been placed #' . $invoiceID;
                        $mail->Body = $_output;
                        $mail->send();
                    }
                    ///Order Place Email

                    if (isset($sslcz["GatewayPageURL"])) {
                        if ($platform == 'web') {
                            $response = array("Status" => "408", "Message" => "Order has been placed", "Invoice" => $invoiceID, "URL" => $sslcz["GatewayPageURL"]);
                            echo json_encode($response);
                        } else {
                            header("Location: " . $sslcz["GatewayPageURL"]);
                        }
                    } else {

                        if ($platform == 'web') {
                            echo '{"Status:"407", "Message":"Payment gateway error."}';
                        } else {
                            header("Location: " . APP_DOM . "payment/fail/");
                        }

                    }

                }

                if (count($json["safari_tickets"]) > 0) {

                    $travelDate = $json["safari_travel_date"];
                    $validDate = $json["safari_valid_date"];
                    $shiftID = $json["safari_timeID"];

                    $cartAmount = 0;
                    $totalTicket = 0;
                    $numStore = 0;
                    $tickets = array();
                    $cartTickets = array();

                    $numVal = 0;
                    foreach ($json["safari_tickets"] as $ticket) {
                        $numIndex = $numVal++;
                        $ticketID = $ticket["ticketID"];
                        $ticketQuantity = $ticket["ticketQuantity"];
                        $ticketPriceDB = $DB->sql("SELECT ticket_name, ticket_details, ticket_price FROM `ticket` WHERE `ticket_id` = '" . $ticketID . "'", 0, 0, 0);
                        $ticketPrice = $ticketPriceDB[0]["ticket_price"];

                        if (count($ticketPriceDB) == 0) {
                            exit;
                        }


                        for ($i = 0; $i < $ticketQuantity; $i++) {
                            $numStore += $i;
                            $tickets[$ticketID][$i]["id"] = $ticketID;
                            $tickets[$ticketID][$i]["name"] = $ticketPriceDB[0]["ticket_name"];
                            $tickets[$ticketID][$i]["details"] = sanitize_output($ticketPriceDB[0]["ticket_details"]);
                            $tickets[$ticketID][$i]["price"] = $ticketPriceDB[0]["ticket_price"];

                            $cartTickets[$numIndex]["product"] = $ticketPriceDB[0]["ticket_name"];
                            $cartTickets[$numIndex]["details"] = $ticketPriceDB[0]["ticket_details"];
                        }

                        $cartTickets[$numIndex]["amount"] = $ticketPrice * $ticketQuantity;
                        $cartTickets[$numIndex]["price"] = $ticketPrice;
                        $cartTickets[$numIndex]["quantity"] = $ticketQuantity;
                        $cartAmount += $ticketPrice * $ticketQuantity;
                        $totalTicket += $ticketQuantity;
                    }

                    $subTotal = $cartAmount;

                    $parkEntryTime = '';

                    if ($shiftID != 1) {
                        $checkTicketAbility = $DB->sql("SELECT SUM(total_ticket) AS booked FROM orders WHERE ticket_category = 'safariTicket' AND `travel_date` = '" . $travelDate . "' AND shift_id = '" . $shiftID . "' AND status = '5'", 0, 0, 0);
                        $shiftTicket = $DB->sql("SELECT tickets, shift_time FROM `shift` WHERE `shift_id` = '" . $shiftID . "'", 0, 0, 0);

                        $parkEntryTime = $shiftTicket[0]["shift_time"];
                        $shiftTicket = $shiftTicket[0]["tickets"];

                        if (isset($checkTicketAbility[0]["booked"])) {
                            $checkTicketAbility = $checkTicketAbility[0]["booked"];
                            $hasTicket = $shiftTicket - $checkTicketAbility;
                        } else {
                            $hasTicket = $shiftTicket;
                        }

                        if ($totalTicket > $hasTicket) {
                            if ($platform == 'web') {
                                exit('{"Status:"407", "Message":"Not enough tickets found for selected date."}');
                            } else {
                                header("Location: " . APP_DOM . "payment/noticket/");
                                exit;
                            }
                        }

                    } else {

                        $dateFormat = DateTime::createFromFormat("d/m/Y", $travelDate);
                        $dayName = $dateFormat->format('l');

                        $checkTicketAbility = $DB->sql("SELECT SUM(total_ticket) AS booked FROM orders WHERE ticket_category = 'safariTicket' AND `travel_date` = '" . $travelDate . "' AND shift_id = '1' AND status = '5'", 0, 0, 0);

                        $safariAllocation = $DB->sql("SELECT * FROM allocation WHERE ticket_category = 'safariTicket' AND `status` = '1' ", 0, 0, 0);
                        $safariAlloJson = json_decode($safariAllocation[0]["value"], true);

                        if (isset($checkTicketAbility[0]["booked"])) {
                            $checkTicketAbility = $checkTicketAbility[0]["booked"];
                            if (isset($safariAlloJson["date"]) && array_key_exists($travelDate, $safariAlloJson["date"])) {
                                $jsonTicket = $safariAlloJson["date"][$travelDate]["tickets"];
                                $parkEntryTime = $safariAlloJson["date"][$travelDate]["open"];
                            } else {
                                $jsonTicket = $safariAlloJson[$dayName]["tickets"];
                                $parkEntryTime = $safariAlloJson[$dayName]["open"];
                            }

                            $hasTicket = $jsonTicket - $checkTicketAbility;
                        } else {
                            $checkTicketAbility = $checkTicketAbility[0]["booked"];
                            if (isset($safariAlloJson["date"]) && array_key_exists($travelDate, $safariAlloJson["date"])) {
                                $jsonTicket = $safariAlloJson["date"][$travelDate]["tickets"];
                                $parkEntryTime = $safariAlloJson["date"][$travelDate]["open"];
                            } else {
                                $jsonTicket = $safariAlloJson[$dayName]["tickets"];
                                $parkEntryTime = $safariAlloJson[$dayName]["open"];
                            }

                            $hasTicket = $jsonTicket;
                        }

                        if ($totalTicket > $hasTicket) {
                            if ($platform == 'web') {
                                exit('{"Status:"407", "Message":"Not enough tickets found for selected date."}');
                            } else {
                                header("Location: " . APP_DOM . "payment/noticket/");
                                exit;
                            }
                        }

                    }


                    $travelDate = DateTime::createFromFormat("d/m/Y h:i A", trim($travelDate . " " . $parkEntryTime));
                    $travelDate = $travelDate->format("U");

                    $validDate = DateTime::createFromFormat("d/m/Y h:i A", trim($validDate . " " . $parkEntryTime));
                    $validDate = $validDate->format("U");

                    $vatAmount = ($purchaseVat / 100) * $cartAmount;
                    $cartAmount = $cartAmount + $vatAmount;

                    $unique_id = $encryption->random_key();
                    $qrCode = new QrCode($unique_id);
                    $base64QR = 'data:image/png;base64,' . base64_encode($qrCode->writeString());

                    $sql = "INSERT INTO `orders` (
                          `order_id`,
                          `unique_id`,
                          `user_id`,
                          `shift_id`,
                          `invoice_id`,
                          `base64_qr`,
                          `ticket_category`,
                          `total_ticket`,
                          `travel_date`,
                          `valid_date`,
                          `phone_number`,
                          `pnr_number`,
                          `ticket_data`,
                          `transaction_id`,
                          `transaction_data`,
                          `ticket_amount`,
                          `total_amount`,
                          `time`,
                          `payment_time`,
                          `user_ip`,
                          `platform`,
                          `visit_date`,
                          `modified_by`,
                          `status`
                        )
                      VALUES (
                          NULL,
                          '" . $unique_id . "',
                          '" . $userID . "',
                          '" . $shiftID . "',
                          '" . $invoiceID . "',
                          '" . $base64QR . "',
                          'safariTicket',
                          '" . $totalTicket . "',
                          '" . $travelDate . "',
                          '" . $validDate . "',
                          '" . $userPhone . "',
                          '" . $PNR . "',
                          '" . json_encode($tickets, JSON_UNESCAPED_UNICODE) . "',
                          '',
                          '',
                          '" . $cartAmount . "',
                          '',
                          '" . $time . "',
                          '',
                          '" . $ip . "',
                          '" . $platform . "',
                          '',
                          '0',
                          '0'
                      );";
                    $DB->sql($sql, 0, 0, 0);


                    $post_data = [];
                    $post_data['store_id'] = "testbox";
                    $post_data['store_passwd'] = "qwerty";
                    $post_data['total_amount'] = $cartAmount;
                    $post_data['currency'] = "BDT";
                    $post_data['tran_id'] = $invoiceID;
                    $post_data['success_url'] = APP_DOM . 'payment/success/' . ($platform == 'web' ? 'web' : '');
                    $post_data['fail_url'] = APP_DOM . 'payment/fail/' . ($platform == 'web' ? 'web' : '');
                    $post_data['cancel_url'] = APP_DOM . 'payment/cancel/' . ($platform == 'web' ? 'web' : '');
                    # CUSTOMER INFORMATION
                    $post_data['cus_name'] = $userData[0]["first_name"];
                    $post_data['cus_email'] = $userData[0]["email"];
                    $post_data['cus_add1'] = "Dhaka";
                    $post_data['cus_add2'] = "Dhaka";
                    $post_data['cus_city'] = "Dhaka";
                    $post_data['cus_state'] = "Dhaka";
                    $post_data['cus_postcode'] = "1000";
                    $post_data['cus_country'] = "Bangladesh";
                    $post_data['cus_phone'] = $userData[0]["phone"];
                    # REQUEST SEND TO SSLCOMMERZ

                    $cartNum = 0;
                    foreach ($cartTickets as $product) {
                        $cartIndex = $cartNum++;

                        $post_data['cart[' . $cartIndex . '][product]'] = $product["product"];
                        $post_data['cart[' . $cartIndex . '][amount]'] = $product["amount"];
                    }

					//TODO Change values

                    $direct_api_url = "https://sandbox.sslcommerz.com/gwprocess/v3/api.php";
                    $handle = curl_init();
                    curl_setopt($handle, CURLOPT_URL, $direct_api_url);
                    curl_setopt($handle, CURLOPT_TIMEOUT, 10);
                    curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 10);
                    curl_setopt($handle, CURLOPT_POST, 1);
                    curl_setopt($handle, CURLOPT_POSTFIELDS, $post_data);
                    curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
                    $content = curl_exec($handle);
                    $code = curl_getinfo($handle, CURLINFO_HTTP_CODE);
                    if ($code == 200 && !(curl_errno($handle))) {
                        curl_close($handle);
                        $sslcommerzResponse = $content;
                    } else {
                        curl_close($handle);
                        echo 'FAILED TO CONNECT WITH SSLCOMMERZ API';
                        exit;
                    }
                    # PARSE THE JSON RESPONSE
                    $sslcz = json_decode($sslcommerzResponse, TRUE);


                    ///Order Place Email
                    if (ORDER_EMAIL) {
                        $itemTable = '<table width="100%">';
                        $itemTable .= '<thead>
                                <tr>
                                    <th style="border-bottom: 2px solid #ddd;">#</th>
                                    <th style="text-align: left; border-bottom: 2px solid #ddd;">Ticket</th>
                                    <th style="text-align: left; border-bottom: 2px solid #ddd;">Quantity</th>
                                    <th style="text-align: left; border-bottom: 2px solid #ddd;">Unit Cost</th>
                                    <th style="text-align: right; border-bottom: 2px solid #ddd;">Total</th>
                                </tr>
                                </thead>';
                        $num = 1;
                        foreach ($cartTickets as $product) {
                            $bulletNum = $num++;

                            $itemTable .= '<tr>
                                    <td style="border-top: 1px solid #ddd;">' . $bulletNum . '</td>
                                    <td style="text-align: left; border-top: 1px solid #ddd;">
                                        <b>' . $product["product"] . '</b> <br>
                                        <span>' . strip_tags($product["details"]) . '</span>
                                    </td>
                                    <td style="text-align: left; border-top: 1px solid #ddd;">' . $product["quantity"] . '</td>
                                    <td style="text-align: left; border-top: 1px solid #ddd;">' . number_format($product["price"], 2) . ' BDT</td>
                                    <td style="text-align: right; border-top: 1px solid #ddd;">' . number_format($product["amount"], 2) . ' BDT</td>
                                </tr>';
                        }


                        $itemTable .= '<tr>
                                    <td colspan="3" style="border-top: 1px solid #ddd;"></td>
                                    <td style="text-align: left; border-top: 1px solid #ddd;">Sub Total</td>
                                    <td style="text-align: right; border-top: 1px solid #ddd;"><b>' . number_format($subTotal, 2) . ' BDT</b></td>
                                    </tr>';

                        $itemTable .= '<tr>
                                    <td colspan="3" style="border-top: 1px solid #ddd;"></td>
                                    <td style="text-align: left; border-top: 1px solid #ddd;">Transaction Fee</td>
                                    <td style="text-align: right; border-top: 1px solid #ddd;"><b>' . number_format($vatAmount, 2) . ' BDT</b></td>
                                    </tr>';

                        $itemTable .= '<tr>
                                    <td colspan="3" style="border-top: 1px solid #ddd;"></td>
                                    <td style="text-align: left; border-top: 1px solid #ddd;">Total</td>
                                    <td style="text-align: right; border-top: 1px solid #ddd;"><b>' . number_format($cartAmount, 2) . ' BDT</b></td>
                                    </tr>';


                        $itemTable .= '</table>';

                        $_input = array();
                        $_input[] = "Safari Ticket"; //Heading
                        $_input[] = 'Thank you <b>' . $userData[0]["first_name"] . '</b> for your recent order with ' . APP_NAME . '. Please make sure to pay your invoice before you receive your e-tickets. You can find your invoice details bellow.
                    <br>
                    <br>
                    <br>
                    
                    ' . $itemTable; //Message
                        $_input[] = "View Order"; //Button text
                        $_input[] = (APP_DOM); //Button link

                        $_output = $email_template->execute(DIR_VIEW . "email_template", $_input);

                        $mail->addAddress($userData[0]["email"], $userData[0]["first_name"]);
                        $mail->isHTML(true);
                        $mail->Subject = 'New order has been placed #' . $invoiceID;
                        $mail->Body = $_output;
                        $mail->send();
                    }
                    ///Order Place Email


                    if (isset($sslcz["GatewayPageURL"])) {
                        if ($platform == 'web') {
                            $response = array("Status" => "408", "Message" => "Order has been placed", "Invoice" => $invoiceID, "URL" => $sslcz["GatewayPageURL"]);
                            echo json_encode($response);
                        } else {
                            header("Location: " . $sslcz["GatewayPageURL"]);
                        }
                    } else {

                        if ($platform == 'web') {
                            echo '{"Status:"407", "Message":"Payment gateway error."}';
                        } else {
                            header("Location: " . APP_DOM . "payment/fail/");
                        }

                    }

                }


            } else {
                header("Location: " . APP_DOM . "payment/fail/");
            }

        } else {
            header("Location: " . APP_DOM . "payment/fail/");
        }

    } elseif ($for == 'fail') {

        if (isset($_GET["job"]) && $_GET["job"] == 'web') {
            echo '<title>Payment Failed</title>';
            echo '<h3>Payment Failed</h3>';
        } else {
            echo '{"Status":407,"Type":"Error","Message":"Request failed.","Token":"' . md5(rand()) . '"}';
        }

        if (isset($_POST["tran_id"])) {
            $invoiceID = $_POST["tran_id"];

            $ticketCat = $DB->sql("SELECT * FROM orders WHERE invoice_id = '" . $invoiceID . "'", 0, 0, 0);
            if (count($ticketCat) > 0) {
                $ticketCat = $ticketCat[0];

                $userData = $DB->sql("SELECT email, first_name FROM `user` WHERE user_id = '" . $ticketCat["user_id"] . "'", 0, 0, 0);

                //Fail Email
                $_input = array();
                $_input[] = "#" . $invoiceID; //Heading
                $_input[] = '<b>' . $userData[0]["first_name"] . '</b> your recent order with ' . APP_NAME . ' been failed. Thank you for using our system, if you wish you can place your order again.'; //Message
                $_input[] = "Replace Order"; //Button text
                $_input[] = (APP_DOM); //Button link

                $_output = $email_template->execute(DIR_VIEW . "email_template", $_input);

                $mail->addAddress($userData[0]["email"], $userData[0]["first_name"]);
                $mail->isHTML(true);
                $mail->Subject = 'Your order #' . $invoiceID . '  has been failed.';
                $mail->Body = $_output;
                $mail->send();
                //Fail Email

                $sql = "UPDATE `orders`  SET 
                  `payment_time` = '" . $time . "',
                  `status` = '2' WHERE invoice_id = '" . $invoiceID . "'";

                $DB->sql($sql, 0, 0, 0);
            }
        }

    } elseif ($for == 'cancel') {

        if (isset($_GET["job"]) && $_GET["job"] == 'web') {
            echo '<title>Payment Canceled</title>';
            echo '<h3>Payment Canceled</h3>';
        } else {
            echo '{"Status":407,"Type":"Error","Message":"Request canceled.","Token":"' . md5(rand()) . '"}';
        }

        if (isset($_POST["tran_id"])) {
            $invoiceID = $_POST["tran_id"];

            $ticketCat = $DB->sql("SELECT * FROM orders WHERE invoice_id = '" . $invoiceID . "'", 0, 0, 0);
            if (count($ticketCat) > 0) {
                $ticketCat = $ticketCat[0];

                $userData = $DB->sql("SELECT email, first_name FROM `user` WHERE user_id = '" . $ticketCat["user_id"] . "'", 0, 0, 0);

                //Cancel Email
                $_input = array();
                $_input[] = "#" . $invoiceID; //Heading
                $_input[] = '<b>' . $userData[0]["first_name"] . '</b> your recent order with ' . APP_NAME . ' been cancelled. Thank you for using our system, if you wish you can place your order again.'; //Message
                $_input[] = "Replace Order"; //Button text
                $_input[] = (APP_DOM); //Button link

                $_output = $email_template->execute(DIR_VIEW . "email_template", $_input);

                $mail->addAddress($userData[0]["email"], $userData[0]["first_name"]);
                $mail->isHTML(true);
                $mail->Subject = 'Your order #' . $invoiceID . '  has been cancelled.';
                $mail->Body = $_output;
                $mail->send();
                //Cancel Email


                $sql = "UPDATE `orders`  SET 
                  `payment_time` = '" . $time . "',
                  `status` = '1' WHERE invoice_id = '" . $invoiceID . "'";

                $DB->sql($sql, 0, 0, 0);
            }


        }
    } elseif ($for == 'success') {


        if (isset($_GET["job"]) && $_GET["job"] == 'web') {
            echo '<title>Payment Succeed</title>';
            echo '<h3>Payment Succeed</h3>';
        }

        if (isset($_POST["tran_id"])) {
            $invoiceID = $_POST["tran_id"];
            $status = $_POST["status"];

            $ticketCat = $DB->sql("SELECT * FROM orders WHERE invoice_id = '" . $invoiceID . "'", 0, 0, 0);


            if (count($ticketCat) > 0) {
                $ticketCat = $ticketCat[0];
                $ticketType = $ticketCat["ticket_category"];

                if (_ipn_hash_varify(md5($storePass))) {
                    if ($status == 'VALID') {

                        //Success Email
                        $userData = $DB->sql("SELECT email, first_name FROM `user` WHERE user_id = '" . $ticketCat["user_id"] . "'", 0, 0, 0);


                        $shiftID = $ticketCat["shift_id"];
                        $travelDate = date("d/m/Y", $ticketCat["travel_date"]);
                        $dayName = date("l", $ticketCat["travel_date"]);


                        if ($shiftID != '1') {
                            $shiftInfo = $DB->sql("SELECT shift_name, shift_time, duration FROM `shift` WHERE shift_id = '" . $shiftID . "'", 0, 0, 0);
                            if (count($shiftInfo) > 0) {
                                $shiftInfo = $shiftInfo[0];
                                $visit_time = $shiftInfo["shift_name"] . ' (';
                                $visit_time .= $shiftInfo["shift_time"];
                                $visit_time .= ' - ';
                                $visit_time .= (date("h:i A", strtotime($shiftInfo["shift_time"] . " +" . $shiftInfo["duration"] . " minutes")));
                                $visit_time .= ')';
                            }
                        } else {
                            $Allocation = $DB->sql("SELECT * FROM allocation WHERE ticket_category = '" . $ticketType . "' AND `status` = '1' ", 0, 0, 0);
                            $AlloJson = json_decode($Allocation[0]["value"], true);

                            if (isset($AlloJson["date"]) && array_key_exists($travelDate, $AlloJson["date"])) {
                                $visit_time = 'সারাদিন (';
                                $visit_time .= $AlloJson["date"][$travelDate]["open"];
                                $visit_time .= ' - ';
                                $visit_time .= $AlloJson["date"][$travelDate]["close"];
                                $visit_time .= ')';
                            } else {
                                $visit_time = 'সারাদিন (';
                                $visit_time .= $AlloJson[$dayName]["open"];
                                $visit_time .= ' - ';
                                $visit_time .= $AlloJson[$dayName]["close"];
                                $visit_time .= ')';
                            }
                        }

                        $itemTable = '<table width="100%">';

                        $itemTable .= '<tr>
                                    <td style="text-align: left;border-bottom: 1px solid #ddd;">Mobile Number</td>
                                    <td style="text-align: right; border-bottom: 1px solid #ddd;">
                                        ' . $ticketCat["phone_number"] . '
                                    </td>
                                </tr>';

                        $itemTable .= '<tr>
                                    <td style="text-align: left;border-bottom: 1px solid #ddd;">OTS (One Time Secret)</td>
                                    <td style="text-align: right; border-bottom: 1px solid #ddd;">
                                        ' . $ticketCat["pnr_number"] . '
                                    </td>
                                </tr>';

                        $itemTable .= '<tr>
                                    <td style="text-align: left;border-bottom: 1px solid #ddd;">Total Amount</td>
                                    <td style="text-align: right; border-bottom: 1px solid #ddd;">
                                        ' . number_format($ticketCat["ticket_amount"], 2) . ' BDT
                                    </td>
                                </tr>';


                        $itemTable .= '<tr>
                                    <td style="text-align: left;border-bottom: 1px solid #ddd;">Travel Date</td>
                                    <td style="text-align: right; border-bottom: 1px solid #ddd;">
                                        ' . date("d/m/Y", $ticketCat["travel_date"]) . '
                                    </td>
                                </tr>';


                        $itemTable .= '<tr>
                                    <td style="text-align: left;border-bottom: 1px solid #ddd;">Visit Time</td>
                                    <td style="text-align: right; border-bottom: 1px solid #ddd;">
                                        ' . $visit_time . '
                                    </td>
                                </tr>';


                        $itemTable .= '<tr>
                                    <td style="text-align: left;border-bottom: 1px solid #ddd;">Expiration</td>
                                    <td style="text-align: right; border-bottom: 1px solid #ddd;">
                                        ' . date("d/m/Y", $ticketCat["valid_date"]) . '
                                    </td>
                                </tr>';

                        $ticketJson = json_decode($ticketCat["ticket_data"], true, 512, JSON_UNESCAPED_UNICODE);

                        foreach ($ticketJson as $key => $val) {
                            //file_put_contents("/mnt/www/content/text.txt", $val[0]["name"]);
                            $itemTable .= '<tr>
                                    <td style="text-align: left;border-bottom: 1px solid #ddd;"><h4>' . $val[0]["name"] . '</h4></td>
                                    <td style="text-align: right; border-bottom: 1px solid #ddd;">
                                        ' . count($val) . '
                                    </td>
                                </tr>';
                        }


                        $itemTable .= '</table>';


                        $_input = array();
                        $_input[] = ($ticketCat["ticket_category"] == 'entryTicket' ? 'Entry Ticket' : 'Safari Ticket'); //Heading
                        $_input[] = 'Thank you <b>' . $userData[0]["first_name"] . '</b> for your recent purchase with ' . APP_NAME . '. Your order has been paid and your online tikets/e-tickets are available for use.
                        
                        <br>
                        <br>
                        <div align="center"><img src="' . $ticketCat["base64_qr"] . '" width="150" /></div>
                        <br>
                        <br>
                        <br>
                    
                        ' . $itemTable; //Message
                        $_input[] = "View Order"; //Button text
                        $_input[] = (APP_DOM); //Button link

                        $_output = $email_template->execute(DIR_VIEW . "email_template", $_input);

                        $mail->addAddress($userData[0]["email"], $userData[0]["first_name"]);
                        $mail->isHTML(true);
                        $mail->Subject = 'Your order #' . $invoiceID . '  has been paid.';
                        $mail->Body = $_output;
                        $mail->send();
                        //Success Email


                        $sql = "UPDATE `orders`  SET 
                  `transaction_id` = '" . $_POST["bank_tran_id"] . "',
                  `transaction_data` = '" . json_encode($_POST) . "',
                  `total_amount` = '" . $_POST["store_amount"] . "',
                  `payment_time` = '" . $time . "',
                  `status` = '5' WHERE invoice_id = '" . $invoiceID . "'";


                        $DB->sql($sql, 0, 0, 0);
                    }
                } else {
                    $sql = "UPDATE `orders`  SET 
                  `payment_time` = '" . $time . "',
                  `status` = '2' WHERE invoice_id = '" . $invoiceID . "'";

                    $DB->sql($sql, 0, 0, 0);
                }
            } else {
                $sql = "UPDATE `orders`  SET 
                  `payment_time` = '" . $time . "',
                  `status` = '2' WHERE invoice_id = '" . $invoiceID . "'";

                $DB->sql($sql, 0, 0, 0);
            }


        }
    } else {
        header('Content-Type: application/json');
        echo '{"Status":403,"Type":"Error","Message":"This server is protected by security token.","Token":"' . md5(rand()) . '"}';
    }

} else {
    header("Location: " . APP_DOM . "payment/fail/");
}