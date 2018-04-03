<?php
/**
 * Created by PhpStorm.
 * User: ahstanin
 * Date: 8/7/17
 * Time: 9:45 PM
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
$page_title = $lang_dash_menu[$lang];

$startDate = DateTime::createFromFormat("d/m/Y h:i A", date("d/m/Y") . " 12:01 AM");
$startPoint = $startDate->format("U");
$endDate = DateTime::createFromFormat("d/m/Y h:i A", date("d/m/Y") . " 11:59 PM");
$endPoint = $endDate->format("U");
$user_id = $gate_data["user_id"];

$sql = "SELECT
(SELECT SUM(total_ticket) FROM orders WHERE travel_date > '" . $startPoint . "' AND travel_date < '" . $endPoint . "' AND status = '5') AS expecting,
(SELECT SUM(total_ticket) FROM orders WHERE visit_date > '" . $startPoint . "' AND visit_date < '" . $endPoint . "' AND status = '4') AS visited,
(SELECT SUM(total_ticket) FROM orders WHERE valid_date > '" . $startPoint . "' AND valid_date < '" . $endPoint . "' AND status = '3') AS expired,
(SELECT COUNT(order_id) FROM orders WHERE modified_by = '" . $user_id . "') AS you";


$state = $DB->sql($sql, 0, 0, 0);
if (isset($state[0])) {
    $statics = $state[0];
}

require_once 'header.php';
?>

    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="card-box widget-inline">
                    <div class="row">
                        <div class="col-lg-3 col-sm-6">
                            <div class="widget-inline-box text-center">
                                <h3 class="m-t-10"><i class="text-primary mdi mdi-account-multiple-plus"></i>
                                    <b data-plugin="counterup">
                                        <?php echo(isset($statics) && $statics["expecting"] != NULL ? $statics["expecting"] : '0'); ?>
                                    </b></h3>
                                <p class="text-muted"><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_gate_expecting[$lang] . '</span>' : $lang_gate_expecting[$lang]); ?></p>
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-6">
                            <div class="widget-inline-box text-center">
                                <h3 class="m-t-10"><i class="text-custom mdi mdi-account-multiple"></i>
                                    <b data-plugin="counterup">
                                        <?php echo(isset($statics) && $statics["visited"] != NULL ? $statics["visited"] : '0'); ?>
                                    </b></h3>
                                <p class="text-muted"><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_gate_visited[$lang] . '</span>' : $lang_gate_visited[$lang]); ?></p>
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-6">
                            <div class="widget-inline-box text-center">
                                <h3 class="m-t-10"><i class="text-info mdi mdi-calendar"></i>
                                    <b data-plugin="counterup">
                                        <?php echo(isset($statics) && $statics["expired"] != NULL ? $statics["expired"] : '0'); ?>
                                    </b></h3>
                                <p class="text-muted"><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_gate_expired[$lang] . '</span>' : $lang_gate_expired[$lang]); ?></p>
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-6">
                            <div class="widget-inline-box text-center b-0">
                                <h3 class="m-t-10"><i class="text-danger mdi mdi-account"></i>
                                    <b data-plugin="counterup">
                                        <?php echo(isset($statics) && $statics["you"] != NULL ? $statics["you"] : '0'); ?>
                                    </b></h3>
                                <p class="text-muted"><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_gate_yours[$lang] . '</span>' : $lang_gate_yours[$lang]); ?></p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!--end row -->


        <?php


        echo '<div class="row allocationBar"><div class="col-lg-12">';
        echo '<ul class="nav nav-tabs tabs-bordered">
        <li class="' . (isset($_GET["job"]) && $_GET["job"] == 'processOTS' ? 'active' : '') . (!isset($_GET["job"]) ? 'active' : '') . '">
            <a href="/gate/index/processOTS">
                <i class="mdi mdi-cellphone"></i>
                ' . ($lang == 'bn' ? '<span class="bangla">' . $lang_gate_ots[$lang] . '</span>' : $lang_gate_ots[$lang]) . '
            </a>
        </li>
        <li class="' . (isset($_GET["job"]) && $_GET["job"] == 'processQR' ? 'active' : '') . '">
            <a href="/gate/index/processQR">
                <i class="mdi mdi-qrcode-scan"></i>
                ' . ($lang == 'bn' ? '<span class="bangla">' . $lang_gate_qr[$lang] . '</span>' : $lang_gate_qr[$lang]) . '
            </a>
        </li>
        </ul>';

        echo '</div></div>';


        if (isset($_GET["job"])) {
            $job = trim($_GET["job"]);
            if ($job == 'processOTS') {
                require_once 'processOTS.php';
            } elseif ($job == 'processQR') {
                require_once 'processQR.php';
            } else {
                require_once 'processOTS.php';
            }
        } else {
            require_once 'processOTS.php';
        }


        ?>


    </div>
    <!-- end container -->

<?php
$additional_footer .= '';
require_once 'footer.php';
?>