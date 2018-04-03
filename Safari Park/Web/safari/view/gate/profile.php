<?php
/**
 * Created by PhpStorm.
 * User: ahstanin
 * Date: 8/12/17
 * Time: 1:15 PM
 */

$additional_header = '';
$page_title = $lang_ticket_edit[$lang];

require_once 'header.php';

if (isset($_POST["firstName"]) && isset($_GET["job"])) {
    $job = trim($_GET["job"]);
    $first_name = $_POST["firstName"];
    $email = $_POST["emailAddress"];
    $phone = $_POST["phoneNumber"];
    $new_pass = $_POST["password"];


    $email_check = $DB->sql("SELECT user_id FROM `user` WHERE email = '" . $email . "' AND unique_id != '" . $job . "' OR phone ='" . $phone . "' AND unique_id != '" . $job . "'", 0, 0, 0);

    if ($first_name == '') {
        if ($lang == 'bn') {
            $message = '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <span class="bangla"><strong>সতর্কতা!</strong> নাম ফাঁকা হতে পারে না</span>
                    </div>';
        } else {
            $message = '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>Warning!</strong> Name cannot be empty.
                    </div>';
        }
    } elseif ($phone == '') {
        if ($lang == 'bn') {
            $message = '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <span class="bangla"><strong>সতর্কতা!</strong> মোবাইল নম্বর ফাঁকা হতে পারে না</span>
                    </div>';
        } else {
            $message = '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>Warning!</strong> Mobile Number cannot be empty.
                    </div>';
        }
    } elseif ($email == '') {
        if ($lang == 'bn') {
            $message = '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <span class="bangla"><strong>সতর্কতা!</strong> ইমেইল ফাঁকা হতে পারে না</span>
                    </div>';
        } else {
            $message = '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>Warning!</strong> Email cannot be empty.
                    </div>';
        }
    } elseif (count($email_check) > 0) {
        if ($lang == 'bn') {
            $message = '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <span class="bangla"><strong>সতর্কতা!</strong> ইমেল বা মোবাইল নাম্বার ব্যবহার করা হচ্ছে।</span>
                    </div>';
        } else {
            $message = '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>Warning!</strong> Email or Mobile already in use.
                    </div>';
        }
    } else {


        //$user_id = $admin_data["user_id"];

        $sql = "UPDATE `user` SET
                `first_name` = '" . $first_name . "',
                " . ($new_pass != '' ? "`password` = '" . sha1($new_pass) . "'," : '') . "
                `phone` = '" . $phone . "',
                `email` = '" . $email . "',
                `token` = '" . $token . "'
                WHERE unique_id = '" . $job . "'
                ";


        $DB->sql($sql, 0, 0, 0);

        if ($lang == 'bn') {
            $message = '<div class="alert alert-success alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <span class="bangla"><strong>সফল!</strong> ব্যবহারকারী সম্পাদনা করা হয়েছে</span>
                    </div>';
        } else {
            $message = '<div class="alert alert-success alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>Success!</strong> User has been updated.
                    </div>';
        }

        echo '<meta http-equiv="refresh" content="0;url=/gate/" />';
    }

} else {
    $message = '';
}


if (isset($_GET["job"])) {
    $job = trim($_GET["job"]);
    if($job != $userKey) exit("Restricted");
    $users = $DB->sql("SELECT unique_id, email, first_name, phone, state, status FROM `user` WHERE unique_id = '" . $job . "' AND state != '0'", 0, 0, 0);
}

if (isset($users) && count($users) > 0) {
    $user = $users[0];
    ?>


    <div class="container">

        <?php echo $message; ?>

        <div class="row">
            <div class="col-sm-12 parentTab" style="margin-bottom: 20px">
                <h4 class="m-t-0 header-title"><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_user_edit[$lang] . '</span>' : $lang_user_edit[$lang]); ?></h4>

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
                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_user_name[$lang] . '</span>' : $lang_user_name[$lang]); ?>
                            </label>
                            <div class="col-md-9">
                                <input type="text" class="form-control<?php echo($lang == 'bn' ? ' bangla' : ''); ?>"
                                       placeholder="<?php echo $lang_user_name[$lang]; ?>"
                                       name="firstName"
                                       value="<?php echo $user["first_name"]; ?>">
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-md-3 control-label" style="text-align: left">
                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_user_phone[$lang] . '</span>' : $lang_user_phone[$lang]); ?>
                            </label>
                            <div class="col-md-9">
                                <input type="text" class="form-control<?php echo($lang == 'bn' ? ' bangla' : ''); ?>"
                                       placeholder="<?php echo $lang_user_phone[$lang]; ?>"
                                       name="phoneNumber"
                                       value="<?php echo $user["phone"]; ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-horizontal">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-3 control-label" style="text-align: left">
                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_user_email[$lang] . '</span>' : $lang_user_email[$lang]); ?>
                            </label>
                            <div class="col-md-9">
                                <input type="email" class="form-control<?php echo($lang == 'bn' ? ' bangla' : ''); ?>"
                                       placeholder="<?php echo $lang_user_email[$lang]; ?>"
                                       name="emailAddress"
                                       value="<?php echo $user["email"]; ?>">
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-md-3 control-label" style="text-align: left">
                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_user_password[$lang] . '</span>' : $lang_user_password[$lang]); ?>
                            </label>
                            <div class="col-md-9">
                                <input type="password"
                                       class="form-control<?php echo($lang == 'bn' ? ' bangla' : ''); ?>"
                                       placeholder="<?php echo $lang_user_password[$lang]; ?>"
                                       name="password"
                                       value="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr/>


            <div class="row" style="padding-bottom: 20px; text-align: right">
                <button type="submit" class="btn btn-primary btn-bordered" style="margin: 0 20px">
                    <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_edit_new[$lang] . '</span>' : $lang_edit_new[$lang]); ?>
                </button>
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

$additional_footer = '';
require_once 'footer.php';
?>

