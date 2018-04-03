<?php
/**
 * Created by PhpStorm.
 * User: ahstanin
 * Date: 8/23/17
 * Time: 5:24 PM
 */

if (isset($_POST["searchBy"])) {
    $searchBy = $_POST["searchBy"];
    $statusBy = $_POST["statusBy"];
    if ($searchBy == 'invoice') {
        $addSQL = 'AND invoice_id LIKE "%' . $_POST["invoice"] . '%"';
        $addSQL .= ($statusBy != '' ? ' AND status = "' . $statusBy . '"' : '');
    } elseif ($searchBy == 'mobile') {
        $addSQL = 'AND phone_number LIKE "%' . $_POST["mobile"] . '%"';
        $addSQL .= ($statusBy != '' ? ' AND status = "' . $statusBy . '"' : '');
    } elseif ($searchBy == 'transaction') {
        $addSQL = 'AND transaction_id LIKE "%' . $_POST["transaction"] . '%"';
        $addSQL .= ($statusBy != '' ? ' AND status = "' . $statusBy . '"' : '');
    } elseif ($searchBy == 'purchaseDate') {
        $dateRaw = $_POST["purchaseDate"];
        if (strlen($dateRaw) > 16) {
            $dateVar = explode("-", $dateRaw);
            $startDate = DateTime::createFromFormat("d/m/Y h:i A", trim($dateVar[0] . " 12:01 AM"));
            $startPoint = $startDate->format("U");
            $endDate = DateTime::createFromFormat("d/m/Y h:i A", trim($dateVar[1] . " 11:59 PM"));
            $endPoint = $endDate->format("U");
            $addSQL = 'AND `time` > "' . $startPoint . '" AND `time` < "' . $endPoint . '"';
            $addSQL .= ($statusBy != '' ? ' AND status = "' . $statusBy . '"' : '');
        } else {
            $addSQL = '';
        }
    } elseif ($searchBy == 'travelDate') {
        $dateRaw = $_POST["travelDate"];
        if (strlen($dateRaw) > 16) {
            $dateVar = explode("-", $dateRaw);
            $startDate = DateTime::createFromFormat("d/m/Y h:i A", trim($dateVar[0] . " 12:01 AM"));
            $startPoint = $startDate->format("U");
            $endDate = DateTime::createFromFormat("d/m/Y h:i A", trim($dateVar[1] . " 11:59 PM"));
            $endPoint = $endDate->format("U");
            $addSQL = 'AND `travel_date` > "' . $startPoint . '" AND `travel_date` < "' . $endPoint . '"';
            $addSQL .= ($statusBy != '' ? ' AND status = "' . $statusBy . '"' : '');
        } else {
            $addSQL = '';
        }
    } elseif ($searchBy == 'visitDate') {
        $dateRaw = $_POST["visitDate"];
        if (strlen($dateRaw) > 16) {
            $dateVar = explode("-", $dateRaw);
            $startDate = DateTime::createFromFormat("d/m/Y h:i A", trim($dateVar[0] . " 12:01 AM"));
            $startPoint = $startDate->format("U");
            $endDate = DateTime::createFromFormat("d/m/Y h:i A", trim($dateVar[1] . " 11:59 PM"));
            $endPoint = $endDate->format("U");
            $addSQL = 'AND `visit_date` > "' . $startPoint . '" AND `visit_date` < "' . $endPoint . '"';
            $addSQL .= ($statusBy != '' ? ' AND status = "' . $statusBy . '"' : '');
        } else {
            $addSQL = '';
        }
    } elseif ($searchBy == 'expireDate') {
        $dateRaw = $_POST["expireDate"];
        if (strlen($dateRaw) > 16) {
            $dateVar = explode("-", $dateRaw);
            $startDate = DateTime::createFromFormat("d/m/Y h:i A", trim($dateVar[0] . " 12:01 AM"));
            $startPoint = $startDate->format("U");
            $endDate = DateTime::createFromFormat("d/m/Y h:i A", trim($dateVar[1] . " 11:59 PM"));
            $endPoint = $endDate->format("U");
            $addSQL = 'AND `valid_date` > "' . $startPoint . '" AND `valid_date` < "' . $endPoint . '"';
            $addSQL .= ($statusBy != '' ? ' AND status = "' . $statusBy . '"' : '');
        } else {
            $addSQL = '';
        }
    }

} else {
    $addSQL = '';
}


$orders = $DB->sql("SELECT order_id, unique_id, invoice_id, travel_date, phone_number, pnr_number, ticket_amount, `time`, status FROM `orders` WHERE ticket_category = 'entryTicket' " . $addSQL . " ORDER BY order_id DESC", 0, 1000, 0);

$sql = "SELECT";
$sql .= "(SELECT COUNT(order_id) FROM orders WHERE ticket_category = 'entryTicket' " . $addSQL . ") AS orders,";
$sql .= "(SELECT SUM(total_ticket) FROM orders WHERE ticket_category = 'entryTicket' " . $addSQL . ") AS tickets,";
$sql .= "(SELECT SUM(total_ticket) FROM orders WHERE ticket_category = 'entryTicket' AND visit_date != '' " . $addSQL . ") AS visits,";
//$addSQL = preg_replace('/ AND status = "\d"/', "", $addSQL);
//$sql .= "(SELECT SUM(total_amount) FROM orders WHERE ticket_category = 'entryTicket' AND status = '5' " . $addSQL . ") AS amount";
$sql .= "(SELECT SUM(ticket_amount) FROM orders WHERE ticket_category = 'entryTicket' " . $addSQL . ") AS amount";

$state = $DB->sql($sql, 0, 0, 0);
if (isset($state[0])) {
    $statics = $state[0];
}

?>

    <div class="row">
        <h4 class="m-t-0 header-title"><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_entry_list[$lang] . '</span>' : $lang_ticket_entry_list[$lang]); ?></h4>

        <div class="col-md-12">
            <div class="panel panel-default panel-fill">
                <div class="panel-body">
                    <form action="" method="post">
                        <div class="col-sm-12">
                            <div class="col-sm-3">
                                <select id="searchBy" class="form-control<?php echo($lang == 'bn' ? ' bangla' : ''); ?>"
                                        name="searchBy">
                                    <option value="invoice" <?php echo(isset($searchBy) && $searchBy == 'invoice' ? 'selected' : ''); ?>><?php echo $lang_search_invoice[$lang]; ?></option>
                                    <option value="mobile" <?php echo(isset($searchBy) && $searchBy == 'mobile' ? 'selected' : ''); ?>><?php echo $lang_search_mobile[$lang]; ?></option>
                                    <option value="transaction" <?php echo(isset($searchBy) && $searchBy == 'transaction' ? 'selected' : ''); ?>><?php echo $lang_search_transaction[$lang]; ?></option>
                                    <option value="purchaseDate" <?php echo(isset($searchBy) && $searchBy == 'purchaseDate' ? 'selected' : ''); ?>><?php echo $lang_ticket_purchase_date[$lang]; ?></option>
                                    <option value="travelDate" <?php echo(isset($searchBy) && $searchBy == 'travelDate' ? 'selected' : ''); ?>><?php echo $lang_ticket_visit_date[$lang]; ?></option>
                                    <option value="visitDate" <?php echo(isset($searchBy) && $searchBy == 'visitDate' ? 'selected' : ''); ?>><?php echo $lang_ticket_visited_date[$lang]; ?></option>
                                    <option value="expireDate" <?php echo(isset($searchBy) && $searchBy == 'expireDate' ? 'selected' : ''); ?>><?php echo $lang_ticket_expire_date[$lang]; ?></option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <div id="invoiceDIV" class="searchBy">
                                    <input type="text"
                                           class="form-control<?php echo($lang == 'bn' ? ' bangla' : ''); ?>"
                                           name="invoice"
                                           placeholder="<?php echo $lang_search_invoice[$lang]; ?>" <?php echo(isset($_POST["invoice"]) ? 'value="' . $_POST["invoice"] . '"' : ''); ?>>
                                </div>

                                <div id="mobileDIV" style="display: none" class="searchBy">
                                    <input type="text"
                                           class="form-control<?php echo($lang == 'bn' ? ' bangla' : ''); ?>"
                                           name="mobile"
                                           placeholder="<?php echo $lang_search_mobile[$lang]; ?>" <?php echo(isset($_POST["mobile"]) ? 'value="' . $_POST["mobile"] . '"' : ''); ?>>
                                </div>

                                <div id="transactionDIV" style="display: none" class="searchBy">
                                    <input type="text"
                                           class="form-control<?php echo($lang == 'bn' ? ' bangla' : ''); ?>"
                                           name="transaction"
                                           placeholder="<?php echo $lang_search_transaction[$lang]; ?>" <?php echo(isset($_POST["transaction"]) ? 'value="' . $_POST["transaction"] . '"' : ''); ?>>
                                </div>

                                <div id="purchaseDateDIV" style="display: none" class="searchBy">
                                    <input type="text"
                                           class="form-control input-daterange-datepicker-back<?php echo($lang == 'bn' ? ' bangla' : ''); ?>"
                                           name="purchaseDate"
                                           placeholder="<?php echo $lang_ticket_purchase_date[$lang]; ?>" <?php echo(isset($_POST["purchaseDate"]) ? 'value="' . $_POST["purchaseDate"] . '"' : ''); ?>>
                                </div>

                                <div id="travelDateDIV" style="display: none" class="searchBy">
                                    <input type="text"
                                           class="form-control input-daterange-datepicker-all<?php echo($lang == 'bn' ? ' bangla' : ''); ?>"
                                           name="travelDate"
                                           placeholder="<?php echo $lang_ticket_visit_date[$lang]; ?>" <?php echo(isset($_POST["travelDate"]) ? 'value="' . $_POST["travelDate"] . '"' : ''); ?>>
                                </div>

                                <div id="visitDateDIV" style="display: none" class="searchBy">
                                    <input type="text"
                                           class="form-control input-daterange-datepicker-back2<?php echo($lang == 'bn' ? ' bangla' : ''); ?>"
                                           name="visitDate"
                                           placeholder="<?php echo $lang_ticket_visited_date[$lang]; ?>" <?php echo(isset($_POST["visitDate"]) ? 'value="' . $_POST["visitDate"] . '"' : ''); ?>>
                                </div>

                                <div id="expireDateDIV" style="display: none" class="searchBy">
                                    <input type="text"
                                           class="form-control input-daterange-datepicker-all2<?php echo($lang == 'bn' ? ' bangla' : ''); ?>"
                                           name="expireDate"
                                           placeholder="<?php echo $lang_ticket_expire_date[$lang]; ?>" <?php echo(isset($_POST["expireDate"]) ? 'value="' . $_POST["expireDate"] . '"' : ''); ?>>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <select class="form-control<?php echo($lang == 'bn' ? ' bangla' : ''); ?>"
                                        name="statusBy">
                                    <option value=""><?php echo $lang_peyment_all[$lang]; ?></option>
                                    <option value="0" <?php echo(isset($statusBy) && $statusBy == '0' ? 'selected' : ''); ?>><?php echo $lang_peyment_default[$lang]; ?></option>
                                    <option value="1" <?php echo(isset($statusBy) && $statusBy == '1' ? 'selected' : ''); ?>><?php echo $lang_peyment_canceled[$lang]; ?></option>
                                    <option value="2" <?php echo(isset($statusBy) && $statusBy == '2' ? 'selected' : ''); ?>><?php echo $lang_peyment_failed[$lang]; ?></option>
                                    <option value="3" <?php echo(isset($statusBy) && $statusBy == '3' ? 'selected' : ''); ?>><?php echo $lang_peyment_expired[$lang]; ?></option>
                                    <option value="4" <?php echo(isset($statusBy) && $statusBy == '4' ? 'selected' : ''); ?>><?php echo $lang_peyment_used[$lang]; ?></option>
                                    <option value="5" <?php echo(isset($statusBy) && $statusBy == '5' ? 'selected' : ''); ?>><?php echo $lang_peyment_paid[$lang]; ?></option>
                                </select>
                            </div>
                            <div class="col-sm-1">
                                <button type="submit"
                                        class="btn btn-primary<?php echo($lang == 'bn' ? ' bangla' : ''); ?>">
                                    <?php echo $lang_search[$lang]; ?>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-sm-12">
            <div class="card-box widget-inline">
                <div class="row">
                    <div class="col-lg-3 col-sm-6">
                        <div class="widget-inline-box text-center">
                            <h3><i class="text-primary mdi mdi-receipt"></i>
                                <b data-plugin="counterup"><?php echo(isset($statics) && $statics["orders"] != NULL ? $statics["orders"] : '0'); ?></b>
                            </h3>
                            <p class="text-muted">
                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_total_invoice[$lang] . '</span>' : $lang_ticket_total_invoice[$lang]); ?>
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-6">
                        <div class="widget-inline-box text-center">
                            <h3><i class="ti-ticket text-custom"></i>
                                <b data-plugin="counterup"><?php echo(isset($statics) && $statics["tickets"] != NULL ? $statics["tickets"] : '0'); ?></b>
                            </h3>
                            <p class="text-muted">
                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_total_ticket[$lang] . '</span>' : $lang_ticket_total_ticket[$lang]); ?>
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-6">
                        <div class="widget-inline-box text-center b-0">
                            <h3><i class="text-danger mdi mdi-account-multiple"></i>
                                <b data-plugin="counterup"><?php echo(isset($statics) && $statics["visits"] != NULL ? $statics["visits"] : '0'); ?></b>
                            </h3>
                            <p class="text-muted">
                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_total_visit[$lang] . '</span>' : $lang_ticket_total_visit[$lang]); ?>
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-6">
                        <div class="widget-inline-box text-center">
                            <h3><i class="text-info  mdi mdi-currency-usd"></i>
                                <b data-plugin="counterup"><?php echo(isset($statics) ? number_format($statics["amount"], 2) : '0'); ?></b>
                            </h3>
                            <p class="text-muted">
                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_total_paid[$lang] . '</span>' : $lang_ticket_total_paid[$lang]); ?>
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-sm-12 parentTab">
            <div class="table-responsive m-b-20">

                <table id="datatable-buttons" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_purchase_date[$lang] . '</span>' : $lang_ticket_purchase_date[$lang]); ?></th>
                        <th><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_invoice[$lang] . '</span>' : $lang_ticket_invoice[$lang]); ?></th>
                        <th><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_mobile[$lang] . '</span>' : $lang_ticket_mobile[$lang]); ?></th>
                        <th><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_pnrNumber[$lang] . '</span>' : $lang_ticket_pnrNumber[$lang]); ?></th>
                        <th><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_total_amount[$lang] . '</span>' : $lang_ticket_total_amount[$lang]); ?></th>
                        <th><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_order_status[$lang] . '</span>' : $lang_ticket_order_status[$lang]); ?></th>
                    </tr>
                    </thead>


                    <tbody>

                    <?php
                    if (count($orders) > 0) {


                        foreach ($orders as $order) {
                            echo '<tr>
                                        <td>' . date("d/m/Y  h:i A", $order["time"]) . '</td>
                                        <td><a href="/admin/readOrder/' . $order["order_id"] . '">' . $order["invoice_id"] . '</a></td>
                                        <td>' . $order["phone_number"] . '</td>
                                        <td>' . $order["pnr_number"] . '</td>
                                        <td>' . number_format($order["ticket_amount"], 2) . ' BDT</td>
                                        <td class="bangla">';

                            if ($order["status"] == 0) {
                                echo $lang_peyment_default[$lang];
                            } elseif ($order["status"] == 1) {
                                echo $lang_peyment_canceled[$lang];
                            } elseif ($order["status"] == 2) {
                                echo $lang_peyment_failed[$lang];
                            } elseif ($order["status"] == 3) {
                                echo $lang_peyment_expired[$lang];
                            } elseif ($order["status"] == 4) {
                                echo $lang_peyment_used[$lang];
                            } elseif ($order["status"] == 5) {
                                echo $lang_peyment_paid[$lang];
                            }

                            echo '</td></tr>';
                        }

                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php
$childJS = '
<script>

var selectedVal = $("#searchBy").val();
$(".searchBy").css("display","none");
$("#"+selectedVal+"DIV").css("display","block");

$("#searchBy").change(function() {
    var selectVal = $(this).val();
    $(".searchBy").css("display","none");
    $("#"+selectVal+"DIV").css("display","block");
});
</script>';
?>