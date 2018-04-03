<?php
/**
 * Created by PhpStorm.
 * User: ahstanin
 * Date: 8/12/17
 * Time: 2:26 PM
 */

$additional_header = '
        <link href="/assets/admin/plugins/timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
		<link href="/assets/admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
		<link href="/assets/admin/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">';

$page_title = $lang_ticket_allo_menu[$lang];

require_once 'header.php';


echo '<div class="container">';

echo '<div class="row allocationBar"><div class="col-lg-12">';
echo '<ul class="nav nav-tabs tabs-bordered">
        <li class="' . (isset($_GET["job"]) && $_GET["job"] == 'entryAllocation' ? 'active' : '') . (!isset($_GET["job"]) ? 'active' : '') . '">
            <a href="/admin/allocation/entryAllocation">
                <i class="ti-envelope"></i>
                ' . ($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_entry_menu[$lang] . '</span>' : $lang_ticket_entry_menu[$lang]) . '
            </a>
        </li>
        <li class="' . (isset($_GET["job"]) && $_GET["job"] == 'safariAllocation' ? 'active' : '') . '">
            <a href="/admin/allocation/safariAllocation">
                <i class="ti-direction"></i>
                ' . ($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_safari_menu[$lang] . '</span>' : $lang_ticket_safari_menu[$lang]) . '
            </a>
        </li>
</ul>';

echo '</div></div>';

if (isset($_GET["job"])) {
    $job = trim($_GET["job"]);
    if ($job == 'entryAllocation') {
        require_once 'entryAllocation.php';
    } elseif ($job == 'safariAllocation') {
        require_once 'safariAllocation.php';
    } else {
        require_once 'entryAllocation.php';
    }
} else {
    require_once 'entryAllocation.php';
}


echo '</div>';

$additional_footer = '
<script src="/assets/admin/plugins/moment/moment.js"></script>
     	<script src="/assets/admin/plugins/timepicker/bootstrap-timepicker.js"></script>
     	<script src="/assets/admin/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
     	<script src="/assets/admin/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
     	
     	<script>
     	jQuery(".timepicker").timepicker({
            defaultTIme: false
        });
     	
     	
     	$(".input-daterange-datepicker-closed").daterangepicker({
            buttonClasses: ["btn", "btn-sm"],
            applyClass: "btn-success",
            cancelClass: "btn-default",
            locale: {
                format: "DD/MM/YYYY"
            },
            drops: "up",
            autoUpdateInput: false,
            minDate: "' . date("d/m/Y") . '"
        }, function(start, end, label) {
                $("#closedDate").val(start.format("DD/MM/YYYY") +" - "+ end.format("DD/MM/YYYY"));
        });
     	
     	
        $(".input-daterange-datepicker").daterangepicker({
            buttonClasses: ["btn", "btn-sm"],
            applyClass: "btn-success",
            cancelClass: "btn-default",
            locale: {
                format: "DD/MM/YYYY"
            },
            drops: "up",
            minDate: "' . date("d/m/Y") . '"
        }, function(start, end, label) {
            var daysCount = end.diff(start, "days");
            dateGen(start.format("DD/MM/YYYY"), end.format("DD/MM/YYYY"), daysCount);
        });
        
        
        function dateGen(dayStart, dayEnd, days) {
            var totalDays = days + 1;
            
            var html = "";
            for(var i = 0; i < totalDays; i++){
                var dayGen = moment(dayStart, "DD/MM/YYYY");
                var thisDay = dayGen.add(i, "days").format("DD/MM/YYYY");
                html += \'<div class="col-md-12"><div class="form-group"><label class="col-md-2 control-label" style="text-align: left">\'+thisDay+\'</label><div class="col-md-2"><input type="text" class="form-control timepicker' . ($lang == 'bn' ? ' bangla' : '') . '" name="date[\'+thisDay+\'][open]" maxlength="8" required="required" placeholder="' . $lang_allo_open_time[$lang] . '"></div><div class="col-md-2"><input type="text" class="form-control timepicker' . ($lang == 'bn' ? ' bangla' : '') . '" name="date[\'+thisDay+\'][break]" maxlength="8" placeholder="' . $lang_allo_break_time[$lang] . '"></div><div class="col-md-2"><input type="text" class="form-control timepicker' . ($lang == 'bn' ? ' bangla' : '') . '" name="date[\'+thisDay+\'][reopen]" maxlength="8" placeholder="' . $lang_allo_open_again[$lang] . '"></div><div class="col-md-2"><input type="text" class="form-control timepicker' . ($lang == 'bn' ? ' bangla' : '') . '" name="date[\'+thisDay+\'][close]" maxlength="8" required="required" placeholder="' . $lang_allo_close_time[$lang] . '"></div><div class="col-md-2"><input type="number" class="form-control' . ($lang == 'bn' ? ' bangla' : '') . '" min="0" name="date[\'+thisDay+\'][tickets]" required="required" placeholder="' . $lang_allo_tickets[$lang] . '"></div></div></div><hr/>\';
            }
            
            $(".printDate").append(html);
            $(".timepicker").timepicker({});
        }
         
        $("#resetDate").on("click", function() {
            $(".printDate").html("");
            $("#dateForm").delay(3000).submit();
        });
        
        $("#resetDateClosed").on("click", function() {
            $("#closedDate").val("");
            $("#dateForm").delay(3000).submit();
        });
        
        $(function() {
            $(".noTime").val("");
        });
        
        </script>
     	
     	';
require_once 'footer.php';
?>
