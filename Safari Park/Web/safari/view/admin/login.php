<?php
/**
 * Created by PhpStorm.
 * User: ahstanin
 * Date: 8/7/17
 * Time: 7:04 PM
 */
ob_start("sanitize_output");

if (isset($_GET["for"]) && $_GET["for"] == 'sms' || isset($_GET["for"]) && $_GET["for"] == 'email') {

    if (isset($_SESSION["login_key"])) {
        if ($_GET["for"] == 'sms') {

            $login_check = $DB->sql("SELECT user_id, status, state, username, `key`, first_name, last_name, 2_step, phone FROM `user` WHERE `key` = '" . $_SESSION["login_key"] . "'", 0, 0, 0);

            if (count($login_check) > 0) {
                $user_data = $login_check["0"];
                if ($user_data["status"] == '4' && $user_data["state"] == '2') {

                    $_2_step = true;
                    $pin_code = $encryption->random_number();
                    $_SESSION["login_key"] = $user_data["key"];
                    $_SESSION["pin_code"] = $pin_code;
                    $last_digit = substr($user_data["phone"], -4);
                    $sms->send($user_data["phone"], 'Your ' . APP_NAME . ' login pin code is : ' . $pin_code);
                    $message = '<div class="alert alert-info"><b>Info!</b> SMS sent to ***-***-<b>' . $last_digit . '</b></div>';

                } else {
                    $message = '<div class="alert alert-danger"><b>Warning!</b> Access restricted.</div>';
                }

            }


        } elseif ($_GET["for"] == 'email') {

            $login_check = $DB->sql("SELECT user_id, status, state, username, `key`, first_name, last_name, 2_step, phone, email FROM `user` WHERE `key` = '" . $_SESSION["login_key"] . "'", 0, 0, 0);

            if (count($login_check) > 0) {
                $user_data = $login_check["0"];
                if ($user_data["status"] == '4' && $user_data["state"] == '2') {

                    $_2_step = true;
                    $pin_code = $encryption->random_number();
                    $_SESSION["login_key"] = $user_data["key"];
                    $_SESSION["pin_code"] = $pin_code;
                    $last_digit = explode("@", $user_data["email"])["1"];

                    //Mail Function


                    $message = '<div class="alert alert-info"><b>Info!</b> Email sent to *******@<b>' . $last_digit . '</b></div>';

                } else {
                    $message = '<div class="alert alert-danger"><b>Warning!</b> Access restricted.</div>';
                }

            }

        }
    }

}


if (isset($_POST["username"])) {
    $_token = $_POST["_token"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $remember = (isset($_POST["remember"]) ? $_POST["remember"] : '');

    if ($encryption->dec_token($_token, $ip) == false) {

        if ($lang == 'bn') {
            $message = '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <span class="bangla"><strong>সতর্কতা!</strong> নিরাপত্তা টোকেন মেলেনি</span>
                    </div>';
        } else {
            $message = '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>Warning!</strong> Security token mismatch.
                    </div>';
        }

    } elseif ($username == '') {

        if ($lang == 'bn') {
            $message = '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <span class="bangla"><strong>সতর্কতা!</strong> ইমেল ফাঁকা হতে পারে না</span>
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

    } elseif ($password == '') {

        if ($lang == 'bn') {
            $message = '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <span class="bangla"><strong>সতর্কতা!</strong> পাসওয়ার্ড ফাঁকা হতে পারে না</span>
                    </div>';
        } else {
            $message = '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>Warning!</strong> Password cannot be empty.
                    </div>';
        }

    } else {

        $password = sha1($password);
        $login_check = $DB->sql("SELECT user_id, unique_id, email, `key`, first_name, phone, 2_step, verified, status, state FROM `user` WHERE email = '" . $username . "' AND password = '" . $password . "' OR username = '" . $username . "' AND password = '" . $password . "'", 0, 0, 0);

        if (count($login_check) > 0) {
            $user_data = $login_check["0"];
            if ($user_data["status"] == '4' && $user_data["state"] == '2' || $user_data["status"] == '5' && $user_data["state"] == '2') {

                if ($user_data["2_step"] == '1' && $user_data["verified"] == '1') {
                    $_2_step = true;
                    $pin_code = $encryption->random_number();
                    $_SESSION["login_key"] = $user_data["key"];
                    $_SESSION["pin_code"] = $pin_code;
                    $last_digit = substr($user_data["phone"], -4);
                    $sms->send($user_data["phone"], 'Your ' . APP_NAME . ' login pin code is : ' . $pin_code);
                    $message = '<div class="alert alert-info"><b>Info!</b> SMS sent to ***-***-<b>' . $last_digit . '</b></div>';
                } else {
                    $DB->sql("UPDATE `user` SET token = '" . $token . "' WHERE user_id = '" . $user_data["user_id"] . "'", 0, 0, 0);

                    $_SESSION["admin_data"] = $user_data;
                    if (isset($remember) && $remember == 1) {
                        setcookie('admin_cookie_login', $encryption->encrypt($token), time() + (86400 * 30), "/");
                    }

                    if ($lang == 'bn') {
                        $message = '<div class="alert alert-success alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <span class="bangla"><strong>সফল!</strong> লগইন সফল, দয়া করে অপেক্ষা করুন</span>
                    </div>';
                    } else {
                        $message = '<div class="alert alert-success alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>Success!</strong> Login successful, please wait.
                    </div>';
                    }

                    echo '<meta http-equiv="refresh" content="0;url=' . APP_DOM . 'admin/" />';
                }

            } else {

                if ($lang == 'bn') {
                    $message = '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <span class="bangla"><strong>সতর্কতা!</strong> সংরক্ষিত এলাকা</span>
                    </div>';
                } else {
                    $message = '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>Warning!</strong> Access restricted.
                    </div>';
                }
            }

        } else {

            if ($lang == 'bn') {
                $message = '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <span class="bangla"><strong>সতর্কতা!</strong> ব্যবহারকারী অ্যাকাউন্ট পাওয়া যায় নি</span>
                    </div>';
            } else {
                $message = '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>Warning!</strong> User account not found.
                    </div>';
            }
        }


    }
} elseif (isset($_POST["resetUser"])) {
    $_token = $_POST["_token"];
    $reset_email = $_POST["resetUser"];

    if ($encryption->dec_token($_token, $ip) == false) {

        if ($lang == 'bn') {
            $message = '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <span class="bangla"><strong>সতর্কতা!</strong> নিরাপত্তা টোকেন মেলেনি</span>
                    </div>';
        } else {
            $message = '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>Warning!</strong> Security token mismatch.
                    </div>';
        }

    } elseif ($reset_email == '') {
        if ($lang == 'bn') {
            $message = '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <span class="bangla"><strong>সতর্কতা!</strong> ইমেল ফাঁকা হতে পারে না</span>
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
    } else {


        $login_check = $DB->sql("SELECT * FROM `user` WHERE email = '" . $reset_email . "'", 0, 0, 0);

        if (count($login_check) > 0) {
            $user_data = $login_check["0"];
            if ($user_data["status"] == '4' && $user_data["state"] == '2' || $user_data["status"] == '5' && $user_data["state"] == '2') {
                $first_name = $user_data["first_name"];
                $email = $user_data["email"];

                $DB->sql("UPDATE `user` SET `key` = '" . $key . "', last_seen = '" . $time . "' WHERE `user_id` = '" . $user_data["user_id"] . "'", 0, 0, 0);

                $_input = array();
                $_input[] = $first_name; //Heading
                $_input[] = "Seems like you have forgotten your password. Use a common password with mixed combination for security or save your password somewhere safe. Never share your password with anyone else nor use your cellphone number as password. A strong password insure security of your account and our system.<br/> - Thanks"; //Message
                $_input[] = "Reset Password"; //Button text
                $_input[] = (APP_DOM . "reset/" . $key); //Button link

                $_output = $email_template->execute(DIR_VIEW . "email_template", $_input);

                $mail->addAddress($email, $first_name);
                $mail->isHTML(true);
                $mail->Subject = 'Password Reset';
                $mail->Body = $_output;
                $mail->send();


                if ($lang == 'bn') {
                    $message = '<div class="alert alert-success alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <span class="bangla"><strong>সফল!</strong> দয়া করে আপনার ইমেল চেক করুন</span>
                    </div>';
                } else {
                    $message = '<div class="alert alert-success alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>Success!</strong> Password reset link sent to your email.
                    </div>';
                }
            } else {

                if ($lang == 'bn') {
                    $message = '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <span class="bangla"><strong>সতর্কতা!</strong> সংরক্ষিত এলাকা</span>
                    </div>';
                } else {
                    $message = '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>Warning!</strong> Access restricted.
                    </div>';
                }
            }

        } else {
            if ($lang == 'bn') {
                $message = '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <span class="bangla"><strong>সতর্কতা!</strong> ব্যবহারকারী অ্যাকাউন্ট পাওয়া যায় নি</span>
                    </div>';
            } else {
                $message = '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>Warning!</strong> User account not found.
                    </div>';
            }
        }
    }


} elseif (isset($_POST["pincode"])) {
    $_token = $_POST["_token"];
    $pincode = $_POST["pincode"];
    $last_digit = $_POST["last_digit"];

    if ($encryption->dec_token($_token, $ip) == false) {
        $_2_step = true;
        $message = '<div class="alert alert-danger"><b>Warning!</b> Security token mismatch.</div>';
    } elseif ($pincode == '') {
        $_2_step = true;
        $message = '<div class="alert alert-danger"><b>Warning!</b> Pin code cannot be empty.</div>';
    } elseif ($pincode != $_SESSION["pin_code"]) {
        $_2_step = true;
        $message = '<div class="alert alert-danger"><b>Warning!</b> Invalid pin code.</div>';
    } else {

        $login_check = $DB->sql("SELECT user_id, status, state, email, `key`, first_name, last_name, 2_step, phone FROM `user` WHERE `key` = '" . $_SESSION["login_key"] . "'", 0, 0, 0);

        if (count($login_check) > 0) {
            $user_data = $login_check["0"];
            if ($user_data["status"] == '4' && $user_data["state"] == '2') {

                $DB->sql("UPDATE `user` SET `key` = '" . $key . "' WHERE `key` = '" . $_SESSION["login_key"] . "'", 0, 0, 0);
                $login_check = $DB->sql("SELECT user_id, status, state, email, `key`, first_name, last_name, 2_step, phone FROM `user` WHERE `key` = '" . $key . "'", 0, 0, 0);
                $user_data = $login_check["0"];

                $_SESSION["admin_data"] = $user_data;
                setcookie('admin_cookie_login', $encryption->encrypt($user_data["username"]), time() + (86400 * 30), "/");
                $message = '<div class="alert alert-success"><b>Success!</b> Login successful, please wait.</div>';
                $additional_header = '<meta http-equiv="refresh" content="0;url=' . APP_DOM . 'admin/index" />';

            } else {
                $message = '<div class="alert alert-danger"><b>Warning!</b> Access restricted.</div>';
            }

        } else {
            $message = '<div class="alert alert-danger"><b>Warning!</b> User account not found.</div>';
        }

    }

} else {
    $message = (isset($message) ? $message : '');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title><?php echo $lang_login_title[$lang]; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta content="Custom admin panel" name="description"/>
    <meta content="Akram Hasan Sharkar" name="author"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>

    <link rel="shortcut icon" href="/assets/admin/images/favicon.ico">

    <!-- Bootstrap core CSS -->
    <link href="/assets/admin/css/bootstrap.min.css" rel="stylesheet">
    <!-- MetisMenu CSS -->
    <link href="/assets/admin/css/metisMenu.min.css" rel="stylesheet">
    <!-- Icons CSS -->
    <link href="/assets/admin/css/icons.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="/assets/admin/css/style.css" rel="stylesheet">

    <link href="/assets/stylesheet.css" rel="stylesheet">

    <style>
        .bangla {
            font-family: 'SolaimanLipi' !important;
        }
    </style>

</head>


<body>
<!-- HOME -->
<section>
    <div class="container">

        <div class="row">
            <div class="col-sm-12">

                <div class="wrapper-page">

                    <?php echo $message; ?>

                    <div class="m-t-40 card-box">
                        <div class="text-center">
                            <h2 class="text-uppercase m-t-0 m-b-30">
                                <span><img src="/assets/admin/images/logo.png" alt="Safari Park" width="150"></span>
                            </h2>
                            <!--<h4 class="text-uppercase font-bold m-b-0">Sign In</h4>-->
                        </div>
                        <div class="account-content">
                            <form class="form-horizontal" action="" method="post">

                                <div class="form-group m-b-20">
                                    <div class="col-xs-12">
                                        <label for="emailaddress"><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_email_address[$lang] . '</span>' : $lang_email_address[$lang]); ?></label>
                                        <input class="form-control" type="text" name="username" id="emailaddress"
                                               autocomplete="off"
                                               required=""
                                               placeholder="user@example.com">
                                    </div>
                                </div>

                                <div class="form-group m-b-20">
                                    <div class="col-xs-12">
                                        <a href="#" data-toggle="modal" data-target="#myModal"
                                           class="text-muted pull-right font-14">
                                            <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_forget_password[$lang] . '</span>' : $lang_forget_password[$lang]); ?>
                                        </a>
                                        <label for="password"><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_password[$lang] . '</span>' : $lang_password[$lang]); ?></label>
                                        <input class="form-control<?php echo($lang == 'bn' ? ' bangla' : ''); ?>"
                                               type="password"
                                               required=""
                                               id="password"
                                               name="password"
                                               placeholder="<?php echo $lang_password[$lang]; ?>">
                                    </div>
                                </div>

                                <div class="form-group m-b-30">
                                    <div class="col-xs-12">
                                        <div class="checkbox checkbox-primary">
                                            <input id="checkbox5" value="1" name="remember" type="checkbox">
                                            <label for="checkbox5">
                                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_remember_me[$lang] . '</span>' : $lang_remember_me[$lang]); ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" name="_token"
                                       value="<?php echo $encryption->enc_token($ip, 5); ?>">

                                <div class="form-group account-btn text-center m-t-10">
                                    <div class="col-xs-12">
                                        <button class="btn btn-lg btn-primary btn-block<?php echo($lang == 'bn' ? ' bangla' : ''); ?>"
                                                type="submit">
                                            <?php echo $lang_sign_in[$lang]; ?>
                                        </button>
                                    </div>
                                </div>

                            </form>


                            <div id="myModal" class="modal fade" tabindex="-1" role="dialog"
                                 aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="" class="form-horizontal" method="post">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-hidden="true">×
                                                </button>
                                                <h4 class="modal-title"
                                                    id="myModalLabel"><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_forget_password[$lang] . '</span>' : $lang_forget_password[$lang]); ?></h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group m-b-20">
                                                    <div class="col-xs-12">
                                                        <label for="resetUser"><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_email_address[$lang] . '</span>' : $lang_email_address[$lang]); ?></label>
                                                        <input class="form-control" type="text" name="resetUser"
                                                               id="resetUser"
                                                               autocomplete="off"
                                                               required=""
                                                               placeholder="user@example.com">
                                                    </div>
                                                </div>

                                                <input type="hidden" name="_token"
                                                       value="<?php echo $encryption->enc_token($ip, 5); ?>">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default " data-dismiss="modal">
                                                    <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_close_modal[$lang] . '</span>' : $lang_close_modal[$lang]); ?>
                                                </button>
                                                <button type="submit" class="btn btn-primary ">
                                                    <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_reset_pass[$lang] . '</span>' : $lang_reset_pass[$lang]); ?>
                                                </button>
                                            </div>
                                        </form>
                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                            </div>


                            <div class="clearfix"></div>

                        </div>
                    </div>
                    <!-- end card-box-->


                    <div class="row m-t-50">
                        <div class="col-sm-12 text-center">
                            <p class="text-muted">
                                <?php echo($lang == 'bn' ? '<a class="text-dark" href="/index.php?lang=en">English</a> | <span class="bangla">বাংলা</span>' : 'English | <a class="text-dark" href="/index.php?lang=bn"><span class="bangla">বাংলা</span></a>'); ?>
                            </p>
                        </div>
                    </div>

                </div>
                <!-- end wrapper -->

            </div>
        </div>
    </div>
</section>
<!-- END HOME -->


<!-- js placed at the end of the document so the pages load faster -->
<script src="/assets/admin/js/jquery-2.1.4.min.js"></script>
<script src="/assets/admin/js/bootstrap.min.js"></script>
<script src="/assets/admin/js/metisMenu.min.js"></script>
<script src="/assets/admin/js/jquery.slimscroll.min.js"></script>

<!-- Sweet-Alert  -->
<script src="/assets/admin/plugins/sweet-alert2/sweetalert2.min.js"></script>


<!-- App Js -->
<script src="/assets/admin/js/jquery.app.js"></script>

</body>
</html>
