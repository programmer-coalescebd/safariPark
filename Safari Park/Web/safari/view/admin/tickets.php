<?php
/**
 * Created by PhpStorm.
 * User: ahstanin
 * Date: 8/7/17
 * Time: 9:45 PM
 */

$additional_header = '<link href="/assets/admin/plugins/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/admin/plugins/datatables/buttons.bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/admin/plugins/datatables/responsive.bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/admin/plugins/datatables/scroller.bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/admin/plugins/datatables/dataTables.colVis.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/admin/plugins/datatables/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/admin/plugins/datatables/fixedColumns.dataTables.min.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/admin/plugins/timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
		<link href="/assets/admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
		<link href="/assets/admin/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">';

$page_title = $lang_ticket_menu[$lang];

require_once 'header.php';

echo '<div class="container">';

echo '<div class="row allocationBar"><div class="col-lg-12">';
echo '<ul class="nav nav-tabs tabs-bordered">
        <li class="' . (isset($_GET["job"]) && $_GET["job"] == 'entryTicket' ? 'active' : '') . (!isset($_GET["job"]) ? 'active' : '') . '">
            <a href="/admin/tickets/entryTicket">
                <i class="ti-envelope"></i>
                ' . ($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_entry_menu[$lang] . '</span>' : $lang_ticket_entry_menu[$lang]) . '
            </a>
        </li>
        <li class="' . (isset($_GET["job"]) && $_GET["job"] == 'safariTicket' ? 'active' : '') . '">
            <a href="/admin/tickets/safariTicket">
                <i class="ti-direction"></i>
                ' . ($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_safari_menu[$lang] . '</span>' : $lang_ticket_safari_menu[$lang]) . '
            </a>
        </li>
</ul>';

echo '</div></div>';

if (isset($_GET["job"])) {
    $job = trim($_GET["job"]);
    if ($job == 'entryTicket') {
        require_once 'entryTicket.php';
    } elseif ($job == 'safariTicket') {
        require_once 'safariTicket.php';
    } else {
        require_once 'entryTicket.php';
    }
} else {
    require_once 'entryTicket.php';
}


echo '</div>';


if ($lang == 'bn') {
    $additional_footer = '<script src="/assets/admin/plugins/datatables/jquery.dataTables.min.bn.js"></script>';
} else {
    $additional_footer = '<script src="/assets/admin/plugins/datatables/jquery.dataTables.min.js"></script>';
}

$additional_footer .= '
        <script src="/assets/admin/plugins/datatables/dataTables.bootstrap.js"></script>
        <script src="/assets/admin/plugins/datatables/dataTables.buttons.min.js"></script>
        <script src="/assets/admin/plugins/datatables/buttons.bootstrap.min.js"></script>
        <script src="/assets/admin/plugins/datatables/jszip.min.js"></script>
        <script src="/assets/admin/plugins/datatables/pdfmake.min.js"></script>
        <script src="/assets/admin/plugins/datatables/vfs_fonts.js"></script>
        <script src="/assets/admin/plugins/datatables/buttons.html5.min.js"></script>
        <script src="/assets/admin/plugins/datatables/buttons.print.min.js"></script>
        <script src="/assets/admin/plugins/datatables/dataTables.keyTable.min.js"></script>
        <script src="/assets/admin/plugins/datatables/dataTables.responsive.min.js"></script>
        <script src="/assets/admin/plugins/datatables/responsive.bootstrap.min.js"></script>
        <script src="/assets/admin/plugins/datatables/dataTables.scroller.min.js"></script>
        <script src="/assets/admin/plugins/datatables/dataTables.colVis.js"></script>
        <script src="/assets/admin/plugins/datatables/dataTables.fixedColumns.min.js"></script>
        <script src="/assets/admin/plugins/moment/moment.js"></script>
     	<script src="/assets/admin/plugins/timepicker/bootstrap-timepicker.js"></script>
     	<script src="/assets/admin/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
     	<script src="/assets/admin/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>

        
        <script>
        
            $(".input-daterange-datepicker-back").daterangepicker({
                buttonClasses: ["btn", "btn-sm"],
                applyClass: "btn-success",
                cancelClass: "btn-default",
                locale: {
                    format: "DD/MM/YYYY"
                },
                drops: "down",
                maxDate: "' . date("d/m/Y") . '"
            }, function(start, end, label) {
                $(".input-daterange-datepicker-back").val(start.format("DD/MM/YYYY") +" - "+ end.format("DD/MM/YYYY"));
            });
        
            $(".input-daterange-datepicker-back2").daterangepicker({
                buttonClasses: ["btn", "btn-sm"],
                applyClass: "btn-success",
                cancelClass: "btn-default",
                locale: {
                    format: "DD/MM/YYYY"
                },
                drops: "down",
                maxDate: "' . date("d/m/Y") . '"
            }, function(start, end, label) {
                $(".input-daterange-datepicker-back2").val(start.format("DD/MM/YYYY") +" - "+ end.format("DD/MM/YYYY"));
            });
        
            $(".input-daterange-datepicker-all").daterangepicker({
                buttonClasses: ["btn", "btn-sm"],
                applyClass: "btn-success",
                cancelClass: "btn-default",
                locale: {
                    format: "DD/MM/YYYY"
                },
                drops: "down"
            }, function(start, end, label) {
                $(".input-daterange-datepicker-all").val(start.format("DD/MM/YYYY") +" - "+ end.format("DD/MM/YYYY"));
            });
            
            
            $(".input-daterange-datepicker-all2").daterangepicker({
                buttonClasses: ["btn", "btn-sm"],
                applyClass: "btn-success",
                cancelClass: "btn-default",
                locale: {
                    format: "DD/MM/YYYY"
                },
                drops: "down"
            }, function(start, end, label) {
                $(".input-daterange-datepicker-all2").val(start.format("DD/MM/YYYY") +" - "+ end.format("DD/MM/YYYY"));
            });
        
           var handleDataTableButtons = function () {
                    "use strict";
                    0 !== $("#datatable-buttons").length && $("#datatable-buttons").DataTable({
                        dom: "Bfrtip",
                        buttons: [{
                            extend: "csvHtml5",
                            className: "btn-sm"
                        }, {
                            extend: "excelHtml5",
                            className: "btn-sm"
                        }, {
                            extend: "pdfHtml5",
                            className: "btn-sm",
                            title : ' . (isset($job) && $job == "safariTicket" ? "'Safari Tickets'" : "'Entry Tickets'") . '
                        }, {
                            extend: "print",
                            className: "btn-sm",
                            title : ' . (isset($job) && $job == "safariTicket" ? "'Safari Tickets'" : "'Entry Tickets'") . ',
                            customize: function (win) {
                                $(win.document.body).find("th").addClass(' . ($lang == 'bn' ? '"bangla"' : "") . ');
                            }
                        }],
                        order: [[ 0, "desc" ]],
                        responsive: !0
                    })
                },
                TableManageButtons = function () {
                    "use strict";
                    return {
                        init: function () {
                            handleDataTableButtons()
                        }
                    }
                }();
            TableManageButtons.init();
        </script>';

if (isset($childJS)) {
    $additional_footer .= $childJS;
}

require_once 'footer.php';
?>