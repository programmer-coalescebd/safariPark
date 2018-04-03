<?php
/**
 * Created by PhpStorm.
 * User: ahstanin
 * Date: 3/31/16
 * Time: 4:13 PM
 */

(strpos($_SERVER["REQUEST_URI"], "view") !== false) ? exit('Direct access not allowed') : '';
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

        <div class="col-sm-12">

            <div class="wrapper-page">
                <div class="m-t-40 card-box" style="text-align: center">

                    <form class="form-login" action="" method="post">
                        <h2 class="form-login-heading">Password Reset</h2>
                        <div class="login-wrap">


                            <?php
                            if (isset($_GET["for"]) && !empty($_GET["for"])) {
                                $url_key = $_GET["for"];
                                $check_key = $DB->sql("SELECT * FROM `user` WHERE `key` = '" . $url_key . "'", 0, 0, 0);

                                if (count($check_key) > 0) {

                                    $user_data = $check_key["0"];

                                    if (isset($_POST["password"]) && !empty($_POST["password"])) {
                                        $password_1 = $_POST["password"];
                                        $password_2 = $_POST["password_again"];

                                        if ($password_1 == $password_2) {
                                            $password = sha1($password_1);

                                            $DB->sql("UPDATE `user` SET `key` = '" . $key . "', last_seen = '" . $time . "', password = '" . $password . "' WHERE `key` = '" . $url_key . "'", 0, 0, 0);

                                            ?>

                                            <div class="registration">
                                                <div style="font-size: 100px; color: limegreen"><i
                                                            class="fa fa-check-circle"></i></div>
                                                Your password has been updated, you can now login to your
                                                app using
                                                new password. We suggest, you should change your password once a month.
                                                <br>
                                                Once again thank you very much.
                                            </div>

                                        <?php } else { ?>

                                            <div class="alert alert-danger"><b>ERROR!</b> Your password didn't matched.
                                            </div>

                                            <input type="text" class="form-control"
                                                   placeholder="<?php echo $user_data["email"]; ?>"
                                                   readonly>
                                            <br>
                                            <input type="password" name="password" class="form-control"
                                                   placeholder="New Password"
                                                   autofocus>
                                            <br>
                                            <input type="password" name="password_again" class="form-control"
                                                   placeholder="Confirm Password">
                                            <br>
                                            <button class="btn btn-primary btn-block" type="submit"><i
                                                        class="fa fa-lock"></i> Change Password
                                            </button>
                                            <hr>

                                            <div class="registration">
                                                For your security, please use a strong password. We suggest to not use
                                                any
                                                dictionary
                                                word or phone numbers. You can use mixed case and special characters for
                                                your
                                                password.
                                            </div>

                                        <?php }
                                    } else { ?>
                                        <input type="text" class="form-control"
                                               placeholder="<?php echo $user_data["email"]; ?>"
                                               readonly>
                                        <br>
                                        <input type="password" name="password" class="form-control"
                                               placeholder="New Password"
                                               autofocus>
                                        <br>
                                        <input type="password" name="password_again" class="form-control"
                                               placeholder="Confirm Password">
                                        <br>
                                        <button class="btn btn-primary btn-block" type="submit"><i
                                                    class="fa fa-lock"></i> Change Password
                                        </button>
                                        <hr>

                                        <div class="registration">
                                            For your security, please use a strong password. We suggest to not use any
                                            dictionary
                                            word or phone numbers. You can use mixed case and special characters for
                                            your
                                            password.
                                        </div>
                                    <?php } ?>

                                <?php } else { ?>

                                    <div class="registration">
                                        <div style="font-size: 100px; color: darkred"><i class="fa fa-hand-paper-o"></i>
                                        </div>
                                        The reset key expired or invalid. Please try to reset your password again.
                                    </div>

                                <?php } ?>

                            <?php } else { ?>

                                <div class="registration">
                                    <div style="font-size: 100px; color: darkred"><i class="fa fa-hand-paper-o"></i>
                                    </div>
                                    Your link missing reset key. Please check again your email address again. If you
                                    encounter
                                    same problem. Please feel free to contact our support team.
                                </div>

                            <?php } ?>
                        </div>
                    </form>
                </div>
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
