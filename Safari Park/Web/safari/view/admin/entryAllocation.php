<?php

if (isset($_POST['Sunday'])) {
    $value = json_encode($_POST);

    $sql = "UPDATE `allocation` SET `value` = '" . $value . "' WHERE ticket_category = 'entryTicket'";
    $DB->sql($sql, 0, 0, 0);

    if ($lang == 'bn') {
        $message = '<div class="alert alert-success alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <span class="bangla"><strong>সফল!</strong> তারিখ-সময় আপডেট করা হয়েছে</span>
                    </div>';
    } else {
        $message = '<div class="alert alert-success alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>Success!</strong> Date-Time has been updated.
                    </div>';
    }


} else {
    $message = '';
}

$entryDB = $DB->sql("SELECT `value` FROM allocation WHERE ticket_category = 'entryTicket'", 0, 0, 0);
if (isset($entryDB[0]['value']) && $entryDB[0]['value'] != '') {
    $entryTime = json_decode($entryDB[0]['value']);
}
?>



<?php echo $message; ?>
<div class="row">
    <div class="col-sm-12" style="margin-bottom: 20px">
        <h4 class="m-t-0 header-title"><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_allo_time[$lang] . '</span>' : $lang_allo_time[$lang]); ?></h4>
    </div>
</div>


<form action="" method="post" id="dateForm">

    <div class="row">
        <div class="form-horizontal">
            <?php
            foreach ($lang_days[$lang] as $key => $openDays) {

                echo '<div class="col-md-12">
                    <div class="form-group">
                        <label class="col-md-2 control-label" style="text-align: left">
                            ' . ($lang == 'bn' ? '<span class="bangla">' . $openDays . '</span>' : $openDays) . '
                        </label>
                        
                        <div class="col-md-2">
                            <input type="text" class="form-control timepicker' . ($lang == 'bn' ? ' bangla' : '') . (isset($entryTime->$key->open) && $entryTime->$key->open == '' ? ' noTime' : '') . '" name="' . $key . '[open]" ' . (isset($entryTime->$key) ? 'value="' . $entryTime->$key->open . '"' : '') . ' maxlength="8" required="required" placeholder="' . $lang_allo_open_time[$lang] . '">
                        </div>
                        
                        <div class="col-md-2">
                            <input type="text" class="form-control timepicker' . ($lang == 'bn' ? ' bangla' : '') . (isset($entryTime->$key->open) && $entryTime->$key->break == '' ? ' noTime' : '') . '" name="' . $key . '[break]" ' . (isset($entryTime->$key) ? 'value="' . $entryTime->$key->break . '"' : '') . ' maxlength="8" placeholder="' . $lang_allo_break_time[$lang] . '">
                        </div>
                        
                        <div class="col-md-2">
                            <input type="text" class="form-control timepicker' . ($lang == 'bn' ? ' bangla' : '') . (isset($entryTime->$key->open) && $entryTime->$key->reopen == '' ? ' noTime' : '') . '" name="' . $key . '[reopen]" ' . (isset($entryTime->$key) ? 'value="' . $entryTime->$key->reopen . '"' : '') . ' maxlength="8" placeholder="' . $lang_allo_open_again[$lang] . '">
                        </div>
                        
                        <div class="col-md-2">
                            <input type="text" class="form-control timepicker' . ($lang == 'bn' ? ' bangla' : '') . (isset($entryTime->$key->open) && $entryTime->$key->close == '' ? ' noTime' : '') . '" name="' . $key . '[close]" ' . (isset($entryTime->$key) ? 'value="' . $entryTime->$key->close . '"' : '') . ' maxlength="8" required="required" placeholder="' . $lang_allo_close_time[$lang] . '">
                        </div>
                        
                        <div class="col-md-2">
                            <input type="number" class="form-control' . ($lang == 'bn' ? ' bangla' : '') . '" min="0" required="required" name="' . $key . '[tickets]" ' . (isset($entryTime->$key) ? 'value="' . $entryTime->$key->tickets . '"' : '') . ' placeholder="' . $lang_allo_tickets[$lang] . '">
                        </div>
                    </div>
                </div>';
            }
            ?>
        </div>
    </div>


    <hr/>


    <div class="row">
        <div class="col-sm-12" style="margin-bottom: 20px">
            <h4 class="m-t-0 header-title"><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_allo_special[$lang] . '</span>' : $lang_allo_special[$lang]); ?></h4>
        </div>
    </div>


    <div class="row">
        <div class="form-horizontal">

            <div class="col-md-12">

                <div class="form-group">
                    <label class="col-md-2 control-label" style="text-align: left">
                        <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_allo_special_day[$lang] . '</span>' : $lang_allo_special_day[$lang]); ?>
                    </label>
                    <?php
                    if (isset($entryTime->date)) {
                        $dateArray = (array)$entryTime->date;
                        $dateStart = current(array_keys($dateArray));
                        $reverseDate = array_reverse($dateArray);
                        $dateEnd = current(array_keys($reverseDate));


                        echo '<div class="col-md-4">
                                <input class="form-control input-daterange-datepicker" type="text" value="' . $dateStart . ' - ' . $dateEnd . '">
                        </div>';

                        echo '<div class="col-md-2">';
                        echo '<button type="button" class="btn btn-icon btn-danger" id="resetDate">
	                            <i class="fa fa-eraser" aria-hidden="true"></i>
                                ' . ($lang == 'bn' ? '<span class="bangla">' . $lang_erase[$lang] . '</span>' : $lang_erase[$lang]) . '
                                </button>';
                        echo '</div>';
                    } else {
                        echo '<div class="col-md-4">
                                <input class="form-control input-daterange-datepicker" type="text" value="">
                        </div>';
                    }
                    ?>
                </div>
            </div>

        </div>
    </div>


    <div class="row">
        <div class="form-horizontal printDate">
            <?php
            if (isset($entryTime->date)) {
                foreach ($entryTime->date as $date => $values) {

                    echo '<div class="col-md-12">
                    <div class="form-group">
                        <label class="col-md-2 control-label" style="text-align: left">
                            ' . $date . '
                        </label>
                        
                        <div class="col-md-2">
                            <input type="text" class="form-control timepicker' . ($lang == 'bn' ? ' bangla' : '') . ($values->open == '' ? ' noTime' : '') . '" name="date[' . $date . '][open]"  value="' . $values->open . '" maxlength="8" required="required" placeholder="' . $lang_allo_open_time[$lang] . '">
                        </div>
                        
                        <div class="col-md-2">
                            <input type="text" class="form-control timepicker' . ($lang == 'bn' ? ' bangla' : '') . ($values->break == '' ? ' noTime' : '') . '" name="date[' . $date . '][break]" value="' . $values->break . '" maxlength="8" placeholder="' . $lang_allo_break_time[$lang] . '">
                        </div>
                        
                        <div class="col-md-2">
                            <input type="text" class="form-control timepicker' . ($lang == 'bn' ? ' bangla' : '') . ($values->reopen == '' ? ' noTime' : '') . '" name="date[' . $date . '][reopen]" value="' . $values->reopen . '" maxlength="8" placeholder="' . $lang_allo_open_again[$lang] . '">
                        </div>
                        
                        <div class="col-md-2">
                            <input type="text" class="form-control timepicker' . ($lang == 'bn' ? ' bangla' : '') . ($values->close == '' ? ' noTime' : '') . '" name="date[' . $date . '][close]" value="' . $values->close . '" maxlength="8" required="required" placeholder="' . $lang_allo_close_time[$lang] . '">
                        </div>
                        
                        <div class="col-md-2">
                            <input type="number" class="form-control' . ($lang == 'bn' ? ' bangla' : '') . '" min="0" required="required" name="date[' . $date . '][tickets]" value="' . $values->tickets . '" placeholder="' . $lang_allo_tickets[$lang] . '">
                        </div>
                    </div>
                </div>';
                }
            }
            ?>
        </div>
    </div>


    <!-- Closed -->
    <hr/>
    <div class="row">
        <div class="col-sm-12" style="margin-bottom: 20px">
            <h4 class="m-t-0 header-title"><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_allo_closed[$lang] . '</span>' : $lang_allo_closed[$lang]); ?></h4>
        </div>
    </div>

    <div class="row">
        <div class="form-horizontal">

            <div class="col-md-12">

                <div class="form-group">
                    <label class="col-md-2 control-label" style="text-align: left">
                        <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_allo_closed[$lang] . '</span>' : $lang_allo_closed[$lang]); ?>
                    </label>
                    <?php
                    if (isset($entryTime->closed) && $entryTime->closed != '') {
                        echo '<div class="col-md-4">
                                <input class="form-control input-daterange-datepicker-closed" name="closed" id="closedDate" type="text" value="' . $entryTime->closed . '">
                              </div>';

                        echo '<div class="col-md-2">';
                        echo '<button type="button" class="btn btn-icon btn-danger" id="resetDateClosed">
	                            <i class="fa fa-eraser" aria-hidden="true"></i>
                                ' . ($lang == 'bn' ? '<span class="bangla">' . $lang_erase[$lang] . '</span>' : $lang_erase[$lang]) . '
                                </button>';
                        echo '</div>';
                    } else {
                        echo '<div class="col-md-4">
                                <input class="form-control input-daterange-datepicker-closed" name="closed" id="closedDate" type="text" value="">
                              </div>';
                    }
                    ?>
                </div>
            </div>

        </div>
    </div>
    <!-- Closed -->


    <div class="row" style="padding-bottom: 20px">
        <div class="col-sm-12" style="text-align: right">
            <button type="submit" class="btn btn-primary btn-bordered" style="margin: 0 20px">
                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_edit_new[$lang] . '</span>' : $lang_edit_new[$lang]); ?>
            </button>
        </div>
    </div>


</form>