<?php
/**
 * Created by PhpStorm.
 * User: ahstanin
 * Date: 8/14/17
 * Time: 11:52 AM
 */


$entryDB = $DB->sql("SELECT `value` FROM allocation WHERE ticket_category = 'entryTicket'", 0, 0, 0);
if (isset($entryDB[0]['value']) && $entryDB[0]['value'] != '') {
    $entryTime = json_decode($entryDB[0]['value']);
} else {
    $addHours = true;
}

if (isset($_POST['scheduleName'])) {
    $scheduleName = $_POST["scheduleName"];
    $dateSelect = $_POST["dateSelect"];
    $timeSelect = $_POST["timeSelect"];
    $duration = $_POST["duration"];
    $tickets = $_POST["tickets"];
    $publish = $_POST["publish"];


    if (1 === preg_match('~[0-9]~', $dateSelect)) {
        $dateSelect = str_replace("-", "/", $dateSelect);
    }

    $checkExist = $DB->sql("SELECT * FROM shift WHERE ticket_category = 'entryTicket' AND shift_date = '" . $dateSelect . "' AND shift_time = '" . $timeSelect . "'", 0, 0, 0);

    if ($scheduleName == '' || $dateSelect == '' || $timeSelect == '' || $duration == '' || $tickets == '') {
        if ($lang == 'bn') {
            $message = '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <span class="bangla"><strong>সতর্কতা!</strong> সকল তথ্য প্রয়োজন</span>
                    </div>';
        } else {
            $message = '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>Warning!</strong> All fields are required.
                    </div>';
        }
    } elseif (count($checkExist) > 0) {
        if ($lang == 'bn') {
            $message = '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <span class="bangla"><strong>সতর্কতা!</strong> সময় ইতিমধ্যে বিদ্যমান</span>
                    </div>';
        } else {
            $message = '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>Warning!</strong> Time already exist.
                    </div>';
        }
    } else {

        $unique_id = $encryption->unique_id();
        $user_id = $admin_data["user_id"];

        $sql = "INSERT INTO `shift` (
                  `shift_id`,
                  `unique_id`,
                  `shift_name`,
                  `ticket_category`,
                  `shift_date`,
                  `shift_time`,
                  `duration`,
                  `tickets`,
                  `status`,
                  `created_by`,
                  `created_from`,
                  `created_at`,
                  `modified_by`
                )
              VALUES (
                  NULL,
                  '" . $unique_id . "',
                  '" . addslashes(trim($scheduleName)) . "',
                  'entryTicket',
                  '" . $dateSelect . "',
                  '" . $timeSelect . "',
                  '" . $duration . "',
                  '" . $tickets . "',
                  '" . $publish . "',
                  '" . $user_id . "',
                  '" . $ip . "',
                  '" . $time . "',
                  '0'
              );";


        $DB->sql($sql, 0, 0, 0);

        if ($lang == 'bn') {
            $message = '<div class="alert alert-success alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <span class="bangla"><strong>সফল!</strong> সময় যোগ করা হয়েছে</span>
                    </div>';
        } else {
            $message = '<div class="alert alert-success alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>Success!</strong> Time has been added.
                    </div>';
        }

        //echo '<meta http-equiv="refresh" content="0;url=' . APP_DOM . 'admin/hours/entryHours" />';

    }

} elseif (isset($_GET['job'])) {
    $dateVal = trim($_GET['job']);

    if (1 === preg_match('~[0-9]~', $dateVal)) {
        $dateVal = str_replace("-", "/", $dateVal);
        if (isset($entryTime->date->$dateVal)) {

            $openTime = $entryTime->date->$dateVal->open;
            $breakTime = $entryTime->date->$dateVal->break;
            $reopenTime = $entryTime->date->$dateVal->reopen;
            $closeTime = $entryTime->date->$dateVal->close;
            $ticketNum = $entryTime->date->$dateVal->tickets;


            $checkTime = $DB->sql("SELECT * FROM shift WHERE ticket_category = 'entryTicket' AND shift_date = '" . $dateVal . "'", 0, 0, 0);

            if (count($checkTime) > 0) {
                $output = array();

                $hasTicket = 0;

                foreach ($checkTime as $times) {
                    $dbStartTime = strtotime($times["shift_time"]);
                    $dbEndTime = strtotime('+ ' . $times["duration"] . ' minutes', $dbStartTime);

                    if (!empty($openTime) && !empty($closeTime)) {
                        $startPoint = strtotime($openTime);
                        $endPoint = strtotime($closeTime);

                        if (!empty($breakTime) && !empty($reopenTime)) {
                            $breakPoint = strtotime($breakTime);
                            $breakEnd = strtotime($reopenTime);
                        }

                        for ($i = $startPoint; $i < $endPoint; $i += 900) {

                            if (isset($breakPoint)) {
                                if ($i < $breakPoint || $i >= $breakEnd) {
                                    $output["time"][] = date("h:i A", $i);

                                    if ($i == $dbStartTime || $i < $dbEndTime) {
                                        $output["unset"][] = date("h:i A", $i);
                                    }
                                }
                            } else {
                                $output["time"][] = date("h:i A", $i);

                                if ($i == $dbStartTime || $i < $dbEndTime) {
                                    $output["unset"][] = date("h:i A", $i);
                                }
                            }
                        }

                        $hasTicket += $times["tickets"];
                    }
                }


                $output["times"] = array_unique(array_diff($output["time"], $output["unset"]));
                $output["tickets"] = $ticketNum;
                $output["available"] = $ticketNum - $hasTicket;
                unset($output["time"]);
                unset($output["unset"]);

                echo json_encode($output);

            } else {

                if (!empty($openTime) && !empty($closeTime)) {
                    $startPoint = strtotime($openTime);
                    $endPoint = strtotime($closeTime);

                    if (!empty($breakTime) && !empty($reopenTime)) {
                        $breakPoint = strtotime($breakTime);
                        $breakEnd = strtotime($reopenTime);
                    }

                    $output = array();
                    for ($i = $startPoint; $i < $endPoint; $i += 900) {

                        if (isset($breakPoint)) {
                            if ($i < $breakPoint || $i >= $breakEnd) {
                                $output["times"][] = date("h:i A", $i);
                            }
                        } else {
                            $output["times"][] = date("h:i A", $i);
                        }

                    }

                    $output["tickets"] = $ticketNum;
                    $output["available"] = $ticketNum;

                    echo json_encode($output);
                } else {
                    echo '{"status":"nothing"}';
                }
            }

        } else {
            echo '{"status":"error"}';
        }

    } else {
        if (isset($entryTime->$dateVal)) {
            $openTime = $entryTime->$dateVal->open;
            $breakTime = $entryTime->$dateVal->break;
            $reopenTime = $entryTime->$dateVal->reopen;
            $closeTime = $entryTime->$dateVal->close;
            $ticketNum = $entryTime->$dateVal->tickets;


            $checkTime = $DB->sql("SELECT * FROM shift WHERE ticket_category = 'entryTicket' AND shift_date = '" . $dateVal . "'", 0, 0, 0);

            if (count($checkTime) > 0) {
                $output = array();

                $hasTicket = 0;

                foreach ($checkTime as $times) {
                    $dbStartTime = strtotime($times["shift_time"]);
                    $dbEndTime = strtotime('+ ' . $times["duration"] . ' minutes', $dbStartTime);

                    if (!empty($openTime) && !empty($closeTime)) {
                        $startPoint = strtotime($openTime);
                        $endPoint = strtotime($closeTime);

                        if (!empty($breakTime) && !empty($reopenTime)) {
                            $breakPoint = strtotime($breakTime);
                            $breakEnd = strtotime($reopenTime);
                        }

                        for ($i = $startPoint; $i < $endPoint; $i += 900) {

                            if (isset($breakPoint)) {
                                if ($i < $breakPoint || $i >= $breakEnd) {
                                    $output["time"][] = date("h:i A", $i);

                                    if ($i == $dbStartTime || $i < $dbEndTime) {
                                        $output["unset"][] = date("h:i A", $i);
                                    }
                                }
                            } else {
                                $output["time"][] = date("h:i A", $i);

                                if ($i == $dbStartTime || $i < $dbEndTime) {
                                    $output["unset"][] = date("h:i A", $i);
                                }
                            }
                        }

                        $hasTicket += $times["tickets"];
                    }
                }


                $output["times"] = array_unique(array_diff($output["time"], $output["unset"]));
                $output["tickets"] = $ticketNum;
                $output["available"] = $ticketNum - $hasTicket;
                unset($output["time"]);
                unset($output["unset"]);

                echo json_encode($output);

            } else {

                if (!empty($openTime) && !empty($closeTime)) {
                    $startPoint = strtotime($openTime);
                    $endPoint = strtotime($closeTime);

                    if (!empty($breakTime) && !empty($reopenTime)) {
                        $breakPoint = strtotime($breakTime);
                        $breakEnd = strtotime($reopenTime);
                    }

                    $output = array();
                    for ($i = $startPoint; $i < $endPoint; $i += 900) {

                        if (isset($breakPoint)) {
                            if ($i < $breakPoint || $i >= $breakEnd) {
                                $output["times"][] = date("h:i A", $i);
                            }
                        } else {
                            $output["times"][] = date("h:i A", $i);
                        }

                    }

                    $output["tickets"] = $ticketNum;
                    $output["available"] = $ticketNum;

                    echo json_encode($output);
                } else {
                    echo '{"status":"nothing"}';
                }
            }

        } else {
            echo '{"status":"error"}';
        }
    }


    exit;
} else {
    $message = '';
}


$additional_header = '
        <link href="/assets/admin/plugins/timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
		<link href="/assets/admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
		<link href="/assets/admin/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">';

$page_title = $lang_entry_hours_list[$lang];

require_once 'header.php';


?>


<div class="container">

    <div id="sysMessage"><?php echo $message; ?></div>

    <div class="row">
        <div class="col-sm-12 parentTab" style="margin-bottom: 20px">
            <h4 class="m-t-0 header-title"><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_entry_hours_list[$lang] . '</span>' : $lang_entry_hours_list[$lang]); ?></h4>

            <button class="btn btn-icon btn-success addNew" onclick="location.href='/admin/hours/entryHours';">
                <i class="fa fa-chevron-left"></i>
                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_go_back[$lang] . '</span>' : $lang_go_back[$lang]); ?>
            </button>
        </div>
    </div>

    <?php

    if (isset($addHours)) { ?>

        <div class="container">
            <div class="row">
                <div class="col-sm-12" style="text-align: center; margin-top: 100px; margin-bottom: 100px">
                    <i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size: 200px"></i>
                    <br>
                    <br>
                    <h2><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_add_allo[$lang] . '</span>' : $lang_add_allo[$lang]); ?></h2>
                </div>
            </div>
        </div>


    <?php } else { ?>


        <form action="" method="post">

            <div class="row">
                <div class="form-horizontal">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-3 control-label" style="text-align: left">
                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_schedule_name[$lang] . '</span>' : $lang_schedule_name[$lang]); ?>
                            </label>
                            <div class="col-md-9">
                                <input type="text" class="form-control<?php echo($lang == 'bn' ? ' bangla' : ''); ?>"
                                       placeholder="<?php echo $lang_schedule_name[$lang]; ?>"
                                       name="scheduleName"
                                       value="<?php echo(isset($scheduleName) ? $scheduleName : ''); ?>">
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-md-3 control-label" style="text-align: left">
                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_schedule_service[$lang] . '</span>' : $lang_schedule_service[$lang]); ?>
                            </label>
                            <div class="col-md-9">

                                <select id="dateSelect"
                                        class="form-control<?php echo($lang == 'bn' ? ' bangla' : ''); ?>"
                                        name="dateSelect">
                                    <option value=""><?php echo $lang_schedule_select[$lang]; ?></option>
                                    <?php
                                    if (isset($entryTime)) {
                                        foreach ($entryTime as $key => $day) {

                                            if ($key == 'date') {

                                                foreach ($day as $date => $hours) {
                                                    echo '<option value="' . str_replace("/", "-", $date) . '">' . $date . '</option>';
                                                }

                                            } elseif ($key == 'closed') {

                                            } else {
                                                echo '<option value="' . $key . '">' . $lang_days[$lang][$key] . '</option>';
                                            }

                                        }
                                    }
                                    ?>
                                </select>

                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-md-12" style="text-align: center">
                                <h4 id="totalNum" style="margin-bottom: 5px; margin-top: 5px"></h4>
                            </div>
                        </div>


                    </div>
                </div>

                <div class="form-horizontal">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-3 control-label" style="text-align: left">
                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_schedule_start[$lang] . '</span>' : $lang_schedule_start[$lang]); ?>
                            </label>
                            <div class="col-md-9">
                                <select id="timeSelect"
                                        class="form-control<?php echo($lang == 'bn' ? ' bangla' : ''); ?>"
                                        name="timeSelect">
                                    <option value=""><?php echo $lang_schedule_start_select[$lang]; ?></option>
                                </select>
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-md-3 control-label" style="text-align: left">
                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_schedule_duration[$lang] . '</span>' : $lang_schedule_duration[$lang]); ?>
                            </label>

                            <div class="col-md-9">
                                <select class="form-control<?php echo($lang == 'bn' ? ' bangla' : ''); ?>"
                                        name="duration">
                                    <option value=""><?php echo $lang_schedule_duration_select[$lang]; ?></option>
                                    <option value="15">00:15</option>
                                    <option value="30">00:30</option>
                                    <option value="45">00:45</option>
                                    <option value="60">01:00</option>
                                    <option value="75">01:15</option>
                                    <option value="90">01:30</option>
                                    <option value="105">01:45</option>
                                    <option value="120">02:00</option>
                                    <option value="135">02:15</option>
                                    <option value="150">02:30</option>
                                    <option value="165">02:45</option>
                                    <option value="180">03:00</option>
                                    <option value="195">03:15</option>
                                    <option value="210">03:30</option>
                                    <option value="225">03:45</option>
                                    <option value="240">04:00</option>
                                </select>
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-md-3 control-label" style="text-align: left">
                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_allo_tickets[$lang] . '</span>' : $lang_allo_tickets[$lang]); ?>
                            </label>
                            <div class="col-md-9">
                                <input type="number" id="ticketNum"
                                       class="form-control<?php echo($lang == 'bn' ? ' bangla' : ''); ?>"
                                       placeholder="<?php echo $lang_allo_tickets[$lang]; ?>"
                                       name="tickets"
                                       min="0"
                                       value="">
                            </div>
                        </div>

                    </div>
                </div>
            </div>


            <div class="row" style="padding-bottom: 20px">
                <div class="col-sm-12" style="text-align: right">
                    <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_menu_page_publish[$lang] . '</span>' : $lang_menu_page_publish[$lang]); ?>

                    <div class="radio radio-success in-line">
                        <input type="radio" name="publish" id="radio14" value="1" checked="">
                        <label for="radio14">
                            <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_confirm_yes[$lang] . '</span>' : $lang_confirm_yes[$lang]); ?>
                        </label>
                    </div>

                    <div class="radio radio-danger in-line">
                        <input type="radio" name="publish" id="radio16" value="0">
                        <label for="radio16">
                            <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_confirm_no[$lang] . '</span>' : $lang_confirm_no[$lang]); ?>
                        </label>
                    </div>


                    <button type="submit" class="btn btn-success btn-bordered" style="margin: 0 20px">
                        <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_add_new[$lang] . '</span>' : $lang_add_new[$lang]); ?>
                    </button>

                </div>
            </div>

        </form>


    <?php } ?>


</div>


<?php


$additional_footer = '
<script src="/assets/admin/plugins/moment/moment.js"></script>
     	<script src="/assets/admin/plugins/timepicker/bootstrap-timepicker.js"></script>
     	<script src="/assets/admin/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
     	<script src="/assets/admin/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
     	
     	<script>
     	jQuery(".timepicker").timepicker({
            defaultTIme: false
        });
     	
     	$("#dateSelect").change(function() {
            var selectVal = $(this).val();
            
            $.getJSON( "/admin/addEntryHour/" + selectVal, function( data ) {
                if(data.times){
                    var selectMenu = $("#timeSelect");
                    var options = "";
                    $.each( data.times, function( key, value ) {
                        options+= \'<option value="\'+value+\'">\'+value+\'</option>\';
                    });
                    selectMenu.html(options);
                    
                    ' . ($lang == 'bn' ? 'var hmessage = \'<span class="bangla">মোট টিকিট : \'+data.tickets+\' , গ্রহণসাধ্য : \'+data.available+\'</span>\';' : 'var hmessage = "Total Tickets : "+data.tickets+" , Available : "+data.available;') . '
                    
                    $("#totalNum").html(hmessage);
                    $("#ticketNum").attr("max", data.available);
                    
                    
                }
  
            });
            
        });
     	
        </script>
     	
     	';
require_once 'footer.php';
?>
