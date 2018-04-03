<?php
/**
 * Created by PhpStorm.
 * User: ahstanin
 * Date: 8/12/17
 * Time: 1:15 PM
 */

$additional_header = '<link href="/assets/admin/plugins/summernote/summernote.css" rel="stylesheet" />';
$page_title = $lang_ticket_edit[$lang];

require_once 'header.php';

if (isset($_POST["ticketName"]) && isset($_GET["job"])) {
    $job = trim($_GET["job"]);
    $ticketName = $_POST["ticketName"];
    $ticketPosition = $_POST["ticketPosition"];
    $ticketPrice = $_POST["ticketPrice"];
    $requirement = $_POST["requirement"];
    $ticketDetail = $_POST["ticketDetail"];
    $publish = $_POST["publish"];

    if ($ticketName == '') {
        if ($lang == 'bn') {
            $message = '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <span class="bangla"><strong>সতর্কতা!</strong> টিকেটের নাম ফাঁকা হতে পারে না</span>
                    </div>';
        } else {
            $message = '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>Warning!</strong> Ticket\'s Name cannot be empty.
                    </div>';
        }
    } else {

        $user_id = $admin_data["user_id"];

        $sql = "UPDATE `ticket`  SET 
                  `ticket_name` = '" . addslashes(trim($ticketName)) . "',
                  `ticket_details` = '" . addslashes(trim($ticketDetail)) . "',
                  `ticket_position` = '" . $ticketPosition . "',
                  `ticket_price` = '" . $ticketPrice . "',
                  `requirement` = '" . $requirement . "',
                  `status` = '" . $publish . "',
                  `modified_by` = '" . $user_id . "' WHERE unique_id = '" . $job . "'";


        $DB->sql($sql, 0, 0, 0);

        if ($lang == 'bn') {
            $message = '<div class="alert alert-success alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <span class="bangla"><strong>সফল!</strong> টিকেট সম্পাদনা করা হয়েছে</span>
                    </div>';
        } else {
            $message = '<div class="alert alert-success alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>Success!</strong> Ticket has been updated.
                    </div>';
        }

        //echo '<meta http-equiv="refresh" content="0;url=' . APP_DOM . 'admin/entry" />';
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
                <h4 class="m-t-0 header-title"><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_edit[$lang] . '</span>' : $lang_ticket_edit[$lang]); ?></h4>

                <button class="btn btn-icon btn-success addNew" onclick="window.history.go(-1); return false;">
                    <i class="fa fa-chevron-left"></i>
                    <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_go_back[$lang] . '</span>' : $lang_go_back[$lang]); ?>
                </button>
            </div>
        </div>


        <form action="" method="post">

            <div class="row">
                <div class="form-horizontal">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-3 control-label" style="text-align: left">
                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_name[$lang] . '</span>' : $lang_ticket_name[$lang]); ?>
                            </label>
                            <div class="col-md-9">
                                <input type="text" class="form-control<?php echo($lang == 'bn' ? ' bangla' : ''); ?>"
                                       placeholder="<?php echo $lang_ticket_name[$lang]; ?>"
                                       name="ticketName"
                                       value="<?php echo $ticket["ticket_name"]; ?>">
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-md-3 control-label" style="text-align: left">
                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_position[$lang] . '</span>' : $lang_ticket_position[$lang]); ?>
                            </label>
                            <div class="col-md-9">
                                <input type="number" class="form-control<?php echo($lang == 'bn' ? ' bangla' : ''); ?>"
                                       placeholder="<?php echo $lang_ticket_position[$lang]; ?>"
                                       name="ticketPosition"
                                       min="0" max="24"
                                       value="<?php echo $ticket["ticket_position"]; ?>">
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
                                <input type="number" class="form-control<?php echo($lang == 'bn' ? ' bangla' : ''); ?>"
                                       placeholder="<?php echo $lang_ticket_price[$lang]; ?>"
                                       name="ticketPrice"
                                       min="0" max="1000"
                                       value="<?php echo $ticket["ticket_price"]; ?>">
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-md-3 control-label" style="text-align: left">
                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_guardian[$lang] . '</span>' : $lang_ticket_guardian[$lang]); ?>
                            </label>

                            <div class="col-md-9">
                                <select class="form-control<?php echo($lang == 'bn' ? ' bangla' : ''); ?>"
                                        name="requirement">
                                    <option value="0" <?php echo($ticket["requirement"] == 0 ? 'selected' : ''); ?>><?php echo $lang_confirm_no[$lang]; ?></option>
                                    <option value="1" <?php echo($ticket["requirement"] == 1 ? 'selected' : ''); ?>><?php echo $lang_confirm_yes[$lang]; ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr/>


            <div class="row">
                <div class="col-sm-12">
                    <div class="p-20 m-b-20">
                        <h4 class="m-b-30 m-t-0 header-title">
                            <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_detail[$lang] . '</span>' : $lang_ticket_detail[$lang]); ?>
                        </h4>
                        <textarea name="ticketDetail" class="summernote">
                            <?php echo $ticket["ticket_details"]; ?>
                        </textarea>
                    </div>
                </div>
            </div>


            <div class="row" style="padding-bottom: 20px">
                <div class="col-sm-12" style="text-align: right">
                    <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_menu_page_publish[$lang] . '</span>' : $lang_menu_page_publish[$lang]); ?>

                    <div class="radio radio-success in-line">
                        <input type="radio" name="publish" id="radio14"
                               value="1" <?php echo($ticket["status"] == 1 ? 'checked' : ''); ?>>
                        <label for="radio14">
                            <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_confirm_yes[$lang] . '</span>' : $lang_confirm_yes[$lang]); ?>
                        </label>
                    </div>

                    <div class="radio radio-danger in-line">
                        <input type="radio" name="publish" id="radio16"
                               value="0" <?php echo($ticket["status"] == 0 ? 'checked' : ''); ?>>
                        <label for="radio16">
                            <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_confirm_no[$lang] . '</span>' : $lang_confirm_no[$lang]); ?>
                        </label>
                    </div>


                    <button type="submit" class="btn btn-primary btn-bordered" style="margin: 0 20px">
                        <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_edit_new[$lang] . '</span>' : $lang_edit_new[$lang]); ?>
                    </button>

                </div>
            </div>

        </form>


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

$additional_footer = ($lang == 'bn' ? '<script src="/assets/admin/plugins/summernote/summernote.min.bd.js"></script>' : '<script src="/assets/admin/plugins/summernote/summernote.min.js"></script>');

$additional_footer .= '
<script type="text/javascript">
            $(document).ready(function() {
                $(".summernote").summernote({
                    height: 350,                 // set editor height
                    minHeight: null,             // set minimum height of editor
                    maxHeight: null,             // set maximum height of editor
                    focus: false                 // set focus to editable area after initializing summernote
                });
            });
        </script>';
require_once 'footer.php';
?>

