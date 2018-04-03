<?php
/**
 * Created by PhpStorm.
 * User: ahstanin
 * Date: 8/7/17
 * Time: 9:45 PM
 */

$additional_header = '<link rel="stylesheet" href="/assets/admin/plugins/morris/morris.css">';
$page_title = $lang_dash_menu[$lang];

$sql = "SELECT
(SELECT SUM(total_ticket) FROM orders WHERE status = '3' OR status = '4' OR status = '5' AND ticket_category = 'entryTicket') AS entrySold,
(SELECT SUM(total_ticket) FROM orders WHERE status = '3' OR status = '4' OR status = '5' AND ticket_category = 'safariTicket') AS safariSold,
(SELECT SUM(total_amount) FROM orders WHERE status = '3' OR status = '4' OR status = '5' AND ticket_category = 'entryTicket') AS entryAmount,
(SELECT SUM(total_amount) FROM orders WHERE status = '3' OR status = '4' OR status = '5' AND ticket_category = 'safariTicket') AS safariAmount,
(SELECT COUNT(user_id) FROM `user` WHERE state = '0') AS users,
(SELECT COUNT(id) FROM appInstall) AS app";

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
                            <div class="widget-inline-box text-center" style="padding: 15px;">
                                <h6 class="m-t-10">
                                    <!-- <i class="text-primary mdi mdi-ticket-confirmation"></i> -->
                                    <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_entry_menu[$lang] . '</span>' : $lang_ticket_entry_menu[$lang]); ?>
                                    :

                                    <b data-plugin="counterup">
                                        <?php echo(isset($statics) && $statics["entrySold"] != NULL ? $statics["entrySold"] : '0'); ?>
                                    </b></h6>


                                <h6 class="m-t-10">
                                    <!-- <i class="text-primary mdi mdi-ticket-confirmation"></i> -->
                                    <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_safari_menu[$lang] . '</span>' : $lang_ticket_safari_menu[$lang]); ?>
                                    :

                                    <b data-plugin="counterup">
                                        <?php echo(isset($statics) && $statics["safariSold"] != NULL ? $statics["safariSold"] : '0'); ?>
                                    </b></h6>


                                <p class="text-muted"><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_dash_sold[$lang] . '</span>' : $lang_dash_sold[$lang]); ?></p>
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-6">
                            <div class="widget-inline-box text-center" style="padding: 15px;">
                                <h6 class="m-t-10">
                                    <!-- <i class="text-primary mdi mdi-ticket-confirmation"></i> -->
                                    <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_entry_menu[$lang] . '</span>' : $lang_ticket_entry_menu[$lang]); ?>
                                    :

                                    <b data-plugin="counterup">
                                        <?php echo(isset($statics) && $statics["entryAmount"] != NULL ? number_format($statics["entryAmount"], 2) : '0'); ?>
                                    </b></h6>


                                <h6 class="m-t-10">
                                    <!-- <i class="text-primary mdi mdi-ticket-confirmation"></i> -->
                                    <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_safari_menu[$lang] . '</span>' : $lang_ticket_safari_menu[$lang]); ?>
                                    :

                                    <b data-plugin="counterup">
                                        <?php echo(isset($statics) && $statics["safariAmount"] != NULL ? number_format($statics["safariAmount"], 2) : '0'); ?>
                                    </b></h6>

                                <p class="text-muted"><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_dash_earn[$lang] . '</span>' : $lang_dash_earn[$lang]); ?></p>
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-6">
                            <div class="widget-inline-box text-center">
                                <h3 class="m-t-10"><i class="text-info mdi mdi-account-multiple"></i>
                                    <b data-plugin="counterup">
                                        <?php echo(isset($statics) && $statics["users"] != NULL ? $statics["users"] : '0'); ?>
                                    </b></h3>
                                <p class="text-muted"><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_dash_user[$lang] . '</span>' : $lang_dash_user[$lang]); ?></p>
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-6">
                            <div class="widget-inline-box text-center b-0">
                                <h3 class="m-t-10"><i class="text-danger mdi mdi-cellphone"></i>
                                    <b data-plugin="counterup">
                                        <?php echo(isset($statics) && $statics["app"] != NULL ? $statics["app"] : '0'); ?>
                                    </b></h3>
                                <p class="text-muted"><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_dash_app[$lang] . '</span>' : $lang_dash_app[$lang]); ?></p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!--end row -->


        <div class="row">
            <div class="col-lg-6">
                <div class="card-box">
                    <h4 class="m-t-0"><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_dash_revenue[$lang] . '</span>' : $lang_dash_revenue[$lang]); ?></h4>
                    <div class="text-center">
                        <ul class="list-inline chart-detail-list">
                            <li>
                                <h5 class="font-normal"><i class="fa fa-circle m-r-10 text-primary"></i>
                                    <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_entry_menu[$lang] . '</span>' : $lang_ticket_entry_menu[$lang]); ?>
                                </h5>
                            </li>
                            <li>
                                <h5 class="font-normal"><i class="fa fa-circle m-r-10 text-muted"></i>
                                    <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_safari_menu[$lang] . '</span>' : $lang_ticket_safari_menu[$lang]); ?>
                                </h5>
                            </li>
                        </ul>
                    </div>
                    <div id="dashboard-bar-stacked" style="height: 300px;"></div>
                </div>
            </div> <!-- end col -->

            <div class="col-lg-6">
                <div class="card-box">
                    <h4 class="m-t-0"><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_dash_sales[$lang] . '</span>' : $lang_dash_sales[$lang]); ?></h4>
                    <div class="text-center">
                        <ul class="list-inline chart-detail-list">
                            <li>
                                <h5 class="font-normal"><i class="fa fa-circle m-r-10 text-primary"></i>
                                    <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_entry_menu[$lang] . '</span>' : $lang_ticket_entry_menu[$lang]); ?>
                                </h5>
                            </li>
                            <li>
                                <h5 class="font-normal"><i class="fa fa-circle m-r-10 text-info"></i>
                                    <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_safari_menu[$lang] . '</span>' : $lang_ticket_safari_menu[$lang]); ?>
                                </h5>
                            </li>
                        </ul>
                    </div>
                    <div id="dashboard-line-chart" style="height: 300px;"></div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->

    </div>
    <!-- end container -->

<?php

//First Chart


//Second Chart

$backSeven = date("d/F/Y", strtotime("- 6 days"));
$lineChart = '';
for ($i = 0; $i <= 6; $i++) {
    $countDay = $i;
    $thisDate = DateTime::createFromFormat("d/F/Y", $backSeven);
    $thisDate->modify($countDay . "day");
    $thisDay = $thisDate->format("d/m/Y");
    $startDate = DateTime::createFromFormat("d/m/Y h:i A", trim($thisDay . " 12:01 AM"));
    $startPoint = $startDate->format("U");
    $endDate = DateTime::createFromFormat("d/m/Y h:i A", trim($thisDay . " 11:59 PM"));
    $endPoint = $endDate->format("U");
    $dayCode = $thisDate->format("d M");

    $entryTickets = $DB->sql("SELECT SUM(total_amount) AS totalEntry FROM orders WHERE status = '3' OR status = '4' OR status = '5' AND ticket_category = 'entryTicket' AND `time` > '" . $startPoint . "' AND `time` < '" . $endPoint . "'", 0, 0, 0);
    $entryTickets = $entryTickets["0"]["totalEntry"];

    $safariTickets = $DB->sql("SELECT SUM(total_amount) AS totalSafari FROM orders WHERE status = '3' OR status = '4' OR status = '5' AND ticket_category = 'safariTicket' AND `time` > '" . $startPoint . "' AND `time` < '" . $endPoint . "'", 0, 0, 0);
    $safariTickets = $safariTickets["0"]["totalSafari"];

    $lineChart .= '{day: "' . $dayCode . '", a: ' . ($entryTickets != '' ? number_format($entryTickets, 2, '.','') : 0) . ', b: ' . ($safariTickets != '' ? number_format($safariTickets, 2,'.','') : 0) . '},';
}

$lineChart = rtrim($lineChart, ",");

$additional_footer = '<!--Morris Chart-->
<script src="/assets/admin/plugins/morris/morris.min.js"></script>
<script src="/assets/admin/plugins/raphael/raphael-min.js"></script>

<!-- Dashboard init -->
<script>
!function ($) {
    "use strict";

    var Dashboard = function () {
    };

    //creates line chart
    Dashboard.prototype.createLineChart = function (element, data, xkey, ykeys, labels, opacity, Pfillcolor, Pstockcolor, lineColors) {
        Morris.Line({
            element: element,
            data: data,
            xkey: xkey,
            ykeys: ykeys,
            parseTime: false,
            labels: labels,
            fillOpacity: opacity,
            pointFillColors: Pfillcolor,
            pointStrokeColors: Pstockcolor,
            behaveLikeLine: true,
            gridLineColor: "#eef0f2",
            hideHover: "auto",
            lineWidth: "3px",
            pointSize: 0,
            preUnits: "৳",
            resize: true, //defaulted to true
            lineColors: lineColors
        });
    },

        //creates Stacked chart
        Dashboard.prototype.createStackedChart = function (element, data, xkey, ykeys, labels, lineColors) {
            Morris.Bar({
                element: element,
                data: data,
                xkey: xkey,
                ykeys: ykeys,
                stacked: true,
                parseTime: false,
                labels: labels,
                hideHover: "auto",
                resize: true, //defaulted to true
                gridLineColor: "#eeeeee",
                barColors: lineColors
            });
        },

        Dashboard.prototype.init = function () {

            //create line chart
            var $data = [
                ' . $lineChart . '
            ];
            this.createLineChart("dashboard-line-chart", $data, "day", ["a", "b"], ["' . $lang_ticket_entry_menu[$lang] . '", "' . $lang_ticket_safari_menu[$lang] . '"], ["0.1"], ["#ffffff"], ["#999999"], ["#458bc4", "#23b195"]);

            //creating Stacked chart
            var $stckedData = [
                {y: "2005", a: 45, b: 180},
                {y: "2006", a: 75, b: 65},
                {y: "2007", a: 100, b: 90},
                {y: "2008", a: 75, b: 65},
                {y: "2009", a: 100, b: 90},
                {y: "2010", a: 75, b: 65},
                {y: "2011", a: 50, b: 40},
                {y: "2012", a: 75, b: 65},
                {y: "2013", a: 50, b: 40},
                {y: "2014", a: 75, b: 65},
                {y: "2015", a: 100, b: 90}
            ];
            this.createStackedChart("dashboard-bar-stacked", $stckedData, "y", ["a", "b"], ["' . $lang_ticket_entry_menu[$lang] . '", "' . $lang_ticket_safari_menu[$lang] . '"], ["#458bc4", "#ebeff2"]);

        },
        //init
        $.Dashboard = new Dashboard, $.Dashboard.Constructor = Dashboard
}(window.jQuery),

//initializing
    function ($) {
        "use strict";
        $.Dashboard.init();
    }(window.jQuery);
</script>
';
require_once 'footer.php';
?>