<?php
/**
 * Created by PhpStorm.
 * User: ahstanin
 * Date: 8/28/17
 * Time: 6:34 PM
 */
$additional_header = '';
$page_title = $lang_add_new_emp[$lang];

if (isset($_POST["userName"])) {
    $userName = $_POST["userName"];
    $firstName = $_POST["firstName"];
    $phoneNumber = $_POST["phoneNumber"];
    $emailAddress = $_POST["emailAddress"];
    $password = $_POST["password"];
    $status = $_POST["status"];


    $email_check = $DB->sql("SELECT user_id FROM `user` WHERE username = '" . $userName . "' OR email = '" . $emailAddress . "' OR phone ='" . $phoneNumber . "'", 0, 0, 0);

    if ($userName == '' || $firstName == '' || $phoneNumber == '' || $emailAddress == '' || $password == '') {
        if ($lang == 'bn') {
            $message = '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <span class="bangla"><strong>সতর্কতা!</strong> সমস্ত তথ্য বাধ্যতামূলক</span>
                    </div>';
        } else {
            $message = '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>Warning!</strong> All fields are necessary.
                    </div>';
        }
    } elseif (count($email_check) > 0) {
        if ($lang == 'bn') {
            $message = '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <span class="bangla"><strong>সতর্কতা!</strong> ব্যবহারকারীর নাম, ইমেল বা মোবাইল নাম্বার ব্যবহার করা হচ্ছে।</span>
                    </div>';
        } else {
            $message = '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>Warning!</strong> Username, Email or Mobile already in use.
                    </div>';
        }
    } else {

        if ($status == 3) {
            $state = 1;
        } else {
            $state = 2;
        }

        $unique_id = $encryption->random_key();
        $user_id = $admin_data["user_id"];

        $sql = "INSERT INTO `user` (
                                  `user_id`,
                                  `unique_id`,
                                  `username`,
                                  `password`,
                                  `email`,
                                  `phone`,
                                  `first_name`,
                                  `last_name`,
                                  `date_of_birth`,
                                  `picture`,
                                  `register_ip`,
                                  `register_time`,
                                  `last_seen`,
                                  `notification`,
                                  `key`,
                                  `token`,
                                  `deviceToken`,
                                  `platform`,
                                  `provider`,
                                  `social_id`,
                                  `social_url`,
                                  `state`,
                                  `status`,
                                  `verified`,
                                  `2_step`,
                                  `modify_by`
                                )
                              VALUES (
                                  NULL,
                                  '" . $unique_id . "',
                                  '" . $userName . "',
                                  '" . sha1($password) . "',
                                  '" . $emailAddress . "',
                                  '" . $phoneNumber . "',
                                  '" . $firstName . "',
                                  '',
                                  '',
                                  '',
                                  '" . $ip . "',
                                  '" . $time . "',
                                  '" . $time . "',
                                  '1',
                                  '" . $key . "',
                                  '',
                                  '',
                                  '',
                                  '',
                                  '',
                                  '',
                                  '" . $state . "',
                                  '" . $status . "',
                                  '0',
                                  '0',
                                  '" . $user_id . "'
                              );";

        $DB->sql($sql, 0, 0, 0);

        if ($lang == 'bn') {
            $message = '<div class="alert alert-success alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <span class="bangla"><strong>সফল!</strong> কর্মচারী যোগ করা হয়েছে</span>
                    </div>';
        } else {
            $message = '<div class="alert alert-success alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>Success!</strong> Employee has been added.
                    </div>';
        }

        //echo '<meta http-equiv="refresh" content="0;url=/admin/employee" />';
    }


} else {
    $message = '';
}


require_once 'header.php';
?>

<div class="container">

    <?php echo $message; ?>

    <div class="row">
        <div class="col-sm-12 parentTab" style="margin-bottom: 20px">
            <h4 class="m-t-0 header-title"><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_add_new_emp[$lang] . '</span>' : $lang_add_new_emp[$lang]); ?></h4>

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
                                   value="<?php echo(isset($_POST["firstName"]) ? $_POST["firstName"] : ''); ?>">
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-md-3 control-label" style="text-align: left">
                            <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_user_phone[$lang] . '</span>' : $lang_user_phone[$lang]); ?>
                        </label>
                        <div class="col-md-9">
                            <input type="number" class="form-control<?php echo($lang == 'bn' ? ' bangla' : ''); ?>"
                                   placeholder="<?php echo $lang_user_phone[$lang]; ?>"
                                   name="phoneNumber"
                                   minlength="11"
                                   value="<?php echo(isset($_POST["phoneNumber"]) ? $_POST["phoneNumber"] : ''); ?>">
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-md-3 control-label" style="text-align: left">
                            <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_user_status[$lang] . '</span>' : $lang_user_status[$lang]); ?>
                        </label>
                        <div class="col-md-9">

                            <select class="form-control<?php echo($lang == 'bn' ? ' bangla' : ''); ?>"
                                    name="status">
                                <option value="3" <?php echo(isset($_POST["status"]) && $_POST["status"] == 3 ? 'selected="selected"' : ''); ?>><?php echo $lang_emp_employ[$lang]; ?></option>
                                <option value="5" <?php echo(isset($_POST["status"]) && $_POST["status"] == 5 ? 'selected="selected"' : ''); ?>><?php echo $lang_emp_admin[$lang]; ?></option>
                                <option value="4" <?php echo(isset($_POST["status"]) && $_POST["status"] == 4 ? 'selected="selected"' : ''); ?>><?php echo $lang_emp_super[$lang]; ?></option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-horizontal">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-3 control-label" style="text-align: left">
                            <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_user_username[$lang] . '</span>' : $lang_user_username[$lang]); ?>
                        </label>
                        <div class="col-md-9">
                            <input type="text" class="form-control<?php echo($lang == 'bn' ? ' bangla' : ''); ?>"
                                   placeholder="<?php echo $lang_user_username[$lang]; ?>"
                                   name="userName"
                                   value="<?php echo(isset($_POST["userName"]) ? $_POST["userName"] : ''); ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" style="text-align: left">
                            <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_user_email[$lang] . '</span>' : $lang_user_email[$lang]); ?>
                        </label>
                        <div class="col-md-9">
                            <input type="email" class="form-control<?php echo($lang == 'bn' ? ' bangla' : ''); ?>"
                                   placeholder="<?php echo $lang_user_email[$lang]; ?>"
                                   name="emailAddress"
                                   value="<?php echo(isset($_POST["emailAddress"]) ? $_POST["emailAddress"] : ''); ?>">
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
$additional_footer = '';
require_once 'footer.php';
?>
