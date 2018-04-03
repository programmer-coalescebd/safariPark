<?php
/**
 * Created by PhpStorm.
 * User: ahstanin
 * Date: 8/11/17
 * Time: 3:05 PM
 */


if (isset($_POST["ticketName"])) {
    $ticketName = $_POST["ticketName"];
    $ticketPosition = $_POST["ticketPosition"];
    $ticketPrice = $_POST["ticketPrice"];
    $ticketCode = $_POST["ticketCode"];
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
        $unique_id = $encryption->unique_id();
        $user_id = $admin_data["user_id"];


        $sql = "INSERT INTO `ticket` (
                  `ticket_id`,
                  `unique_id`,
                  `ticket_name`,
                  `ticket_code`,
                  `ticket_category`,
                  `ticket_details`,
                  `ticket_position`,
                  `ticket_price`,
                  `requirement`,
                  `status`,
                  `created_by`,
                  `created_from`,
                  `created_at`,
                  `modified_by`
                )
              VALUES (
                  NULL,
                  '" . $unique_id . "',
                  '" . addslashes(trim($ticketName)) . "',
                  '" . addslashes(trim($ticketCode)) . "',
                  'safariTicket',
                  '" . addslashes(trim($ticketDetail)) . "',
                  '" . $ticketPosition . "',
                  '" . $ticketPrice . "',
                  '0',
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
                        <span class="bangla"><strong>সফল!</strong> নতুন টিকেট যোগ করা হয়েছে</span>
                    </div>';
        } else {
            $message = '<div class="alert alert-success alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>Success!</strong> New ticket has been added.
                    </div>';
        }

        //echo '<meta http-equiv="refresh" content="0;url=' . APP_DOM . 'admin/safari" />';

    }


    $message = '';
} else {
    $message = '';
}


$additional_header = '<link href="/assets/admin/plugins/summernote/summernote.css" rel="stylesheet" />';
$page_title = $lang_add_new_safari[$lang];
require_once 'header.php';

?>


<div class="container">

    <?php echo $message; ?>
    <div class="row">
        <div class="col-sm-12 parentTab" style="margin-bottom: 20px">
            <h4 class="m-t-0 header-title"><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_add_new_safari[$lang] . '</span>' : $lang_add_new_safari[$lang]); ?></h4>

            <button class="btn btn-icon btn-success addNew" onclick="location.href='/admin/safari';">
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
                                   value="<?php echo(isset($ticketName) ? $ticketName : ''); ?>">
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
                                   value="<?php echo(isset($ticketPosition) ? $ticketPosition : '0'); ?>">
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
                                   value="<?php echo(isset($ticketPrice) ? $ticketPrice : '0'); ?>">
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-md-3 control-label" style="text-align: left">
                            <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_code[$lang] . '</span>' : $lang_ticket_code[$lang]); ?>
                        </label>

                        <div class="col-md-9">
                            <input type="text" class="form-control<?php echo($lang == 'bn' ? ' bangla' : ''); ?>"
                                   placeholder="<?php echo $lang_ticket_code[$lang]; ?>"
                                   name="ticketCode"
                                   value="<?php echo(isset($ticketCode) ? $ticketCode : ''); ?>">
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
                            <?php echo(isset($ticketDetail) ? $ticketDetail : '<h4>নতুন পৃষ্ঠা যোগ করুন</h4>
                            <ul>
                                <li>
                                    আপনি ছবি যোগ করতে পারেন
                                </li>
                                <li>
                                    আপনি টেবিল এবং তালিকা যোগ করতে পারেন
                                </li>
                            </ul>'); ?>
                        </textarea>
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


</div>


<?php
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
