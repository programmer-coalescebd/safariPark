<?php
/**
 * Created by PhpStorm.
 * User: ahstanin
 * Date: 8/11/17
 * Time: 12:45 PM
 */

$additional_header = '';
$page_title = $lang_ticket_delete[$lang];

require_once 'header.php';

if (isset($_POST["confirm"]) && isset($_GET["job"])) {
    $job = trim($_GET["job"]);

    $jobID = $DB->sql("SELECT ticket_id FROM ticket WHERE unique_id = '" . $job . "'", 0, 0, 0);
    $jobID = (isset($jobID[0]["ticket_id"]) ? $jobID[0]["ticket_id"] : 0);
    $checkBefore = $DB->sql("SELECT order_id FROM orders WHERE ticket_data LIKE '%\"" . $jobID . "\"%'", 0, 0, 0);


    if (count($checkBefore) > 0) {

        if ($lang == 'bn') {
            $message = '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <span class="bangla"><strong>সতর্কতা!</strong> টিকেটটি ব্যবহার করা হয়েছে, আপনি এর পরিবর্তে এটি অপ্রকাশিত করতে পারেন।</span>
                    </div>';
        } else {
            $message = '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>Warning!</strong> Ticket has been using, you can unpublish this instead.
                    </div>';
        }

    } else {

        $DB->sql("DELETE FROM `ticket` WHERE unique_id = '" . $job . "'", 0, 0, 0);

        if ($lang == 'bn') {
            $message = '<div class="alert alert-success alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <span class="bangla"><strong>সফল!</strong> টিকেট অপসারণ করা হয়েছে</span>
                    </div>';
        } else {
            $message = '<div class="alert alert-success alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>Success!</strong> Ticket has been deleted.
                    </div>';
        }

        echo '<meta http-equiv="refresh" content="0;url=' . APP_DOM . 'admin/safari" />';
    }
} else {
    $message = '';
}

if (isset($_GET["job"])) {
    $job = trim($_GET["job"]);
    $tickets = $DB->sql("SELECT * FROM `ticket` WHERE unique_id = '" . $job . "'", 0, 0, 0);
}

if (isset($tickets) && count($tickets) > 0) {
    $ticket = $tickets[0];

    ?>


    <div class="container">

        <?php echo $message; ?>

        <div class="row">
            <div class="col-sm-12 parentTab" style="margin-bottom: 20px">
                <h4 class="m-t-0 header-title"><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_delete[$lang] . '</span>' : $lang_ticket_delete[$lang]); ?></h4>

                <button class="btn btn-icon btn-success addNew" onclick="window.history.go(-1); return false;">
                    <i class="fa fa-chevron-left"></i>
                    <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_go_back[$lang] . '</span>' : $lang_go_back[$lang]); ?>
                </button>
            </div>
        </div>


        <div class="row">
            <div class="form-horizontal">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-3 control-label" style="text-align: left">
                            <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_name[$lang] . '</span>' : $lang_ticket_name[$lang]); ?>
                        </label>
                        <div class="col-md-9">
                            <input type="text" class="form-control<?php echo($lang == 'bn' ? ' bangla' : ''); ?>"
                                   disabled
                                   value="<?php echo $ticket['ticket_name']; ?>">
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-horizontal">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-3 control-label" style="text-align: left">
                            <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_price[$lang] . '</span>' : $lang_ticket_price[$lang]); ?>
                        </label>
                        <div class="col-md-9">
                            <input type="number" class="form-control"
                                   disabled
                                   value="<?php echo $ticket['ticket_price']; ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr/>

        <div class="row">
            <div class="col-sm-12" style="text-align: center; margin-top: 30px; margin-bottom: 50px">
                <h3><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_delete_confirm[$lang] . '</span>' : $lang_delete_confirm[$lang]); ?></h3>
                <form action="" method="post">
                    <input type="hidden" name="confirm" value="yes">
                    <button type="submit" class="btn btn-danger btn-bordered" style="margin: 0 20px">
                        <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_confirm_yes[$lang] . '</span>' : $lang_confirm_yes[$lang]); ?>
                    </button>


                    <button type="button" class="btn btn-success btn-bordered" onclick="location.href='/admin/safari';"
                            style="margin: 0 20px">
                        <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_confirm_no[$lang] . '</span>' : $lang_confirm_no[$lang]); ?>
                    </button>
                </form>
            </div>
        </div>

    </div>


<?php } else { ?>

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
