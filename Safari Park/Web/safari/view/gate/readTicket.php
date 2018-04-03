<?php
/**
 * Created by PhpStorm.
 * User: ahstanin
 * Date: 8/10/17
 * Time: 11:29 AM
 */

$additional_header = '<style>
.statusDiv {
    padding: 0;
    margin: 0 30px 0 1px;
    height: 115px;
}
.statusDefault{
    border: 1px solid #626773;
    background: #626773;
    color: white;
}

.statusCanceled{
    border: 1px solid #d57171;
    background: #d57171;
    color: white;
}

.statusFailed{
    border: 1px solid #d57171;
    background: #d57171;
    color: white;
}

.statusExpired{
    border: 1px solid #e2ab3b;
    background: #e2ab3b;
    color: white;
}

.statusUsed{
    border: 1px solid #23b195;
    background: #23b195;
    color: white;
}

.statusPaid{
    border: 1px solid #4fc55b;
    background: #4fc55b;
    color: white;
}
</style>';
$page_title = $lang_invoice_title[$lang];

require_once 'header.php';

if (isset($_GET["job"])) {
    $job = trim($_GET["job"]);
    $orders = $DB->sql("SELECT * FROM `orders` WHERE unique_id = '" . $job . "'", 0, 0, 0);
}


if (isset($orders) && count($orders) > 0) {
    $order = $orders[0];
    $userData = $DB->sql("SELECT unique_id, email, first_name FROM `user` WHERE user_id = '" . $order["user_id"] . "'", 0, 0, 0);
    if (count($userData) > 0) {
        $userData = $userData[0];
    }
    ?>

    <div class="container">

        <div class="row">
            <div class="col-sm-12 parentTab" style="margin-bottom: 20px">
                <h4 class="m-t-0 header-title">
                    <?php echo($lang == 'bn' && $order["ticket_category"] == 'entryTicket' ? '<span class="bangla">' . $lang_ticket_entry_menu[$lang] . '</span>' : ''); ?>
                    <?php echo($lang == 'bn' && $order["ticket_category"] == 'safariTicket' ? '<span class="bangla">' . $lang_ticket_safari_menu[$lang] . '</span>' : ''); ?>
                    <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_invoice_title[$lang] . '</span>' : $lang_invoice_title[$lang]); ?>
                </h4>

                <button class="btn btn-icon btn-success addNew" onclick="location.href='/gate/';">
                    <i class="fa fa-chevron-left"></i>
                    <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_go_back[$lang] . '</span>' : $lang_go_back[$lang]); ?>
                </button>
            </div>
        </div>


        <?php
        if ($order["modified_by"] != 0) {
            echo '<div class="row" style="padding: 10px 0;">';
            echo '<div class="col-md-12">';
            echo($lang == 'bn' ? '<span class="bangla">' . $lang_modified_by[$lang] . '</span>' : $lang_modified_by[$lang]);
            echo ' : ';
            $empData = $DB->sql("SELECT first_name FROM `user` WHERE user_id = '" . $order["modified_by"] . "'", 0, 0, 0);
            if (count($empData) > 0) {
                $empData = $empData[0];
            }

            echo $empData["first_name"];

            echo '</div>';
            echo '</div>';

        }
        ?>


        <div class="row">
            <div class="col-sm-12">
                <div class="card-box widget-inline">
                    <div class="row">
                        <div class="col-lg-3 col-sm-6">
                            <div class="widget-inline-box text-center">
                                <h4><i class="text-primary mdi mdi-receipt"></i> <b
                                            data-plugin="counterup"><?php echo $order["invoice_id"]; ?></b></h4>
                                <p class="text-muted"><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_invoice[$lang] . '</span>' : $lang_ticket_invoice[$lang]); ?></p>
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-6">
                            <div class="widget-inline-box text-center">
                                <h4><i class="text-custom mdi mdi-currency-usd"></i> <b
                                            data-plugin="counterup"><?php echo number_format($order["ticket_amount"], 2); ?>
                                        BDT</b></h4>
                                <p class="text-muted"><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_total_amount[$lang] . '</span>' : $lang_ticket_total_amount[$lang]); ?></p>
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-6">
                            <div class="widget-inline-box text-center">
                                <h4><i class="text-info mdi mdi-lock"></i> <b
                                            data-plugin="counterup"><?php echo $order["pnr_number"]; ?></b></h4>
                                <p class="text-muted"><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_pnrNumber[$lang] . '</span>' : $lang_ticket_pnrNumber[$lang]); ?></p>
                            </div>
                        </div>

                        <?php
                        if ($order["status"] == 0) {
                            $statusMSG = $lang_peyment_default[$lang];
                            $statusCLS = 'statusDefault';
                        } elseif ($order["status"] == 1) {
                            $statusMSG = $lang_peyment_canceled[$lang];
                            $statusCLS = 'statusCanceled';
                        } elseif ($order["status"] == 2) {
                            $statusMSG = $lang_peyment_failed[$lang];
                            $statusCLS = 'statusFailed';
                        } elseif ($order["status"] == 3) {
                            $statusMSG = $lang_peyment_expired[$lang];
                            $statusCLS = 'statusExpired';
                        } elseif ($order["status"] == 4) {
                            $statusMSG = $lang_peyment_used[$lang];
                            $statusCLS = 'statusUsed';
                        } elseif ($order["status"] == 5) {
                            $statusMSG = $lang_peyment_paid[$lang];
                            $statusCLS = 'statusPaid';
                        }
                        ?>
                        <div class="col-lg-3 col-sm-6">
                            <div class="widget-inline-box text-center b-0 statusDiv <?php echo $statusCLS;
                            echo($lang == 'bn' ? ' bangla' : ''); ?>">
                                <h3>
                                    <?php echo $statusMSG; ?>
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-6">
                <div class="p-20 m-b-20">
                    <h4 class="header-title m-t-0 m-b-20"><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_customer_details[$lang] . '</span>' : $lang_customer_details[$lang]); ?></h4>

                    <div class="row">
                        <div class="col-sm-12 form-control" style="margin-bottom: 5px">
                            <div class="col-sm-6">
                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_user_name[$lang] . '</span>' : $lang_user_name[$lang]); ?>
                            </div>

                            <div class="col-sm-6">
                                <?php //echo(isset($userData) ? '<a href="/admin/editUser/' . $userData["unique_id"] . '">' . $userData["first_name"] . '</a>' : ''); ?>
                                <?php echo(isset($userData) ? $userData["first_name"] : ''); ?>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-sm-12 form-control" style="margin-bottom: 5px">
                            <div class="col-sm-6">
                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_user_email[$lang] . '</span>' : $lang_user_email[$lang]); ?>
                            </div>

                            <div class="col-sm-6">
                                <?php echo(isset($userData) ? $userData["email"] : ''); ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 form-control" style="margin-bottom: 5px">
                            <div class="col-sm-6">
                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_user_mobile[$lang] . '</span>' : $lang_user_mobile[$lang]); ?>
                            </div>

                            <div class="col-sm-6">
                                <?php echo $order["phone_number"]; ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 form-control" style="margin-bottom: 5px">
                            <div class="col-sm-6">
                                IP
                            </div>

                            <div class="col-sm-6">
                                <?php echo $order["user_ip"]; ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-md-6">
                <div class="p-20 m-b-20">
                    <h4 class="header-title m-t-0 m-b-20"><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_travle_details[$lang] . '</span>' : $lang_travle_details[$lang]); ?></h4>

                    <div class="row">
                        <div class="col-sm-12 form-control" style="margin-bottom: 5px">
                            <div class="col-sm-6">
                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_purchase_date[$lang] . '</span>' : $lang_ticket_purchase_date[$lang]); ?>
                            </div>

                            <div class="col-sm-6">
                                <?php echo date("d/m/Y  h:i A", $order["time"]); ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 form-control" style="margin-bottom: 5px">
                            <div class="col-sm-6">
                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_visit_date[$lang] . '</span>' : $lang_ticket_visit_date[$lang]); ?>
                            </div>

                            <div class="col-sm-6">
                                <?php echo date("d/m/Y", $order["travel_date"]); ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 form-control" style="margin-bottom: 5px">
                            <div class="col-sm-6">
                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_visit_time[$lang] . '</span>' : $lang_ticket_visit_time[$lang]); ?>
                            </div>

                            <div class="col-sm-6">
                                <?php
                                $shiftID = $order["shift_id"];
                                $ticketType = $order["ticket_category"];
                                $travelDate = date("d/m/Y", $order["travel_date"]);
                                $dayName = date("l", $order["travel_date"]);


                                if ($shiftID != '1') {
                                    $shiftInfo = $DB->sql("SELECT shift_name, shift_time, duration FROM `shift` WHERE shift_id = '" . $shiftID . "'", 0, 0, 0);
                                    if (count($shiftInfo) > 0) {
                                        $shiftInfo = $shiftInfo[0];
                                        echo '<span class="bangla">' . $shiftInfo["shift_name"] . '</span> (';
                                        echo $shiftInfo["shift_time"];
                                        echo ' - ';
                                        echo(date("h:i A", strtotime($shiftInfo["shift_time"] . " +" . $shiftInfo["duration"] . " minutes")));
                                        echo ')';
                                    }
                                } else {
                                    $Allocation = $DB->sql("SELECT * FROM allocation WHERE ticket_category = '" . $ticketType . "' AND `status` = '1' ", 0, 0, 0);
                                    $AlloJson = json_decode($Allocation[0]["value"], true);

                                    if (isset($AlloJson["date"]) && array_key_exists($travelDate, $AlloJson["date"])) {
                                        echo '<span class="bangla">সারাদিন</span> (';
                                        echo $AlloJson["date"][$travelDate]["open"];
                                        echo ' - ';
                                        echo $AlloJson["date"][$travelDate]["close"];
                                        echo ')';
                                    } else {
                                        echo '<span class="bangla">সারাদিন</span> (';
                                        echo $AlloJson[$dayName]["open"];
                                        echo ' - ';
                                        echo $AlloJson[$dayName]["close"];
                                        echo ')';
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-sm-12 form-control" style="margin-bottom: 5px">
                            <div class="col-sm-6">
                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_expire_date[$lang] . '</span>' : $lang_ticket_expire_date[$lang]); ?>
                            </div>

                            <div class="col-sm-6">
                                <?php echo date("d/m/Y", $order["valid_date"]); ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>


        <div class="row">
            <div class="col-md-12">
                <div class="p-20 m-b-20">
                    <h4 class="header-title m-t-0 m-b-20"><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_tikcet_details[$lang] . '</span>' : $lang_tikcet_details[$lang]); ?></h4>


                    <div class="col-sm-12">

                        <div class="table-responsive">
                            <table class="table m-t-30">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_invoice_item[$lang] . '</span>' : $lang_invoice_item[$lang]); ?></th>
                                    <th><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_invoice_quantity[$lang] . '</span>' : $lang_invoice_quantity[$lang]); ?></th>
                                    <th><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_invoice_unit_cost[$lang] . '</span>' : $lang_invoice_unit_cost[$lang]); ?></th>
                                    <th class="text-right"><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_invoice_total[$lang] . '</span>' : $lang_invoice_total[$lang]); ?></th>
                                </tr>
                                </thead>
                                <tbody>

                                <?php
                                $ticketJson = json_decode($order["ticket_data"], true);
                                $num = 1;
                                $invoiceAmount = 0;
                                foreach ($ticketJson as $key => $val) {
                                    $bulletNum = $num++;
                                    $ticketName = $val[0]["name"];
                                    $ticketDetails = $val[0]["details"];
                                    $ticketCount = count($val);
                                    $ticketPrice = $val[0]["price"];
                                    $totalAmount = $ticketPrice * $ticketCount;
                                    $invoiceAmount += $totalAmount;

                                    echo '<tr>
                                    <td>' . $bulletNum . '</td>
                                    <td class="bangla">
                                        <b>' . $ticketName . '</b> <br>
                                        ' . $ticketDetails . '
                                    </td>
                                    <td>' . $ticketCount . '</td>
                                    <td>' . number_format($ticketPrice, 2) . ' BDT</td>
                                    <td class="text-right">' . number_format($totalAmount, 2) . ' BDT</td>
                                </tr>';

                                }

                                if ($invoiceAmount > 0) {
                                    $processFee = $order["ticket_amount"] - $invoiceAmount;
                                    $feeInPer = ($processFee / $invoiceAmount) * 100;


                                    echo '<tr>
                                    <td colspan="3"></td>
                                    <td><b>' . ($lang == 'bn' ? '<span class="bangla">' . $lang_invoice_sub_total[$lang] . '</span>' : $lang_invoice_sub_total[$lang]) . '</b></td>
                                    <td class="text-right"><b>' . number_format($invoiceAmount, 2) . ' BDT</b></td>
                                    </tr>';

                                    echo '<tr>
                                    <td colspan="3"></td>
                                    <td>' . $feeInPer . '% ' . ($lang == 'bn' ? '<span class="bangla">' . $lang_invoice_process_fee[$lang] . '</span>' : $lang_invoice_process_fee[$lang]) . '</td>
                                    <td class="text-right">' . number_format($processFee, 2) . ' BDT</td>
                                    </tr>';

                                    echo '<tr>
                                    <td colspan="3"></td>
                                    <td><b>' . ($lang == 'bn' ? '<span class="bangla">' . $lang_invoice_total[$lang] . '</span>' : $lang_invoice_total[$lang]) . '</b></td>
                                    <td class="text-right"><b>' . number_format($order["ticket_amount"], 2) . ' BDT</b></td>
                                    </tr>';

                                    if ($order["total_amount"] != '') {
                                        echo '<tr>
                                        <td colspan="3">
                                            <b>' . ($lang == 'bn' ? '<span class="bangla">' . $lang_search_transaction[$lang] . '</span>' : $lang_search_transaction[$lang]) . ' : </b>
                                           ' . $order["transaction_id"] . '
                                        </td>
                                        <td><b>' . ($lang == 'bn' ? '<span class="bangla">' . $lang_invoice_merchant[$lang] . '</span>' : $lang_invoice_merchant[$lang]) . '</b></td>
                                        <td class="text-right"><b>' . number_format($order["total_amount"], 2) . ' BDT</b></td>
                                        </tr>';
                                    }
                                }

                                ?>
                                </tbody>
                            </table>
                        </div>

                    </div>

                </div>
            </div>
        </div>


        <div class="row" style="padding-bottom: 20px">
            <div class="col-sm-12" style="text-align: right">

                <?php
                if ($order["status"] == 5) {
                    echo '<button type="button" class="btn btn-danger btn-bordered"
                        onclick="location.href=\'/gate/cancelTicket/' . $job . '\';" style="margin: 0 20px">
                    ' . ($lang == 'bn' ? '<span class="bangla">' . $lang_order_report[$lang] . '</span>' : $lang_order_report[$lang]) . '
                    </button>';

                    echo '<button type="button" class="btn btn-success btn-bordered"
                        onclick="location.href=\'/gate/claimTicket/' . $job . '\';" style="margin: 0 20px">
                    ' . ($lang == 'bn' ? '<span class="bangla">' . $lang_order_claim[$lang] . '</span>' : $lang_order_claim[$lang]) . '
                    </button>';
                }
                ?>

            </div>
        </div>


    </div>

    <?php
} else {
    ?>

    <div class="container">
        <div class="row">
            <div class="col-sm-12" style="text-align: center; margin-top: 100px; margin-bottom: 100px">
                <i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size: 200px"></i>
                <br>
                <br>
                <h2><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_not_found[$lang] . '</span>' : $lang_not_found[$lang]); ?></h2>
            </div>
        </div>
    </div>


    <?php
}
$additional_footer = '';
require_once 'footer.php';
?>
