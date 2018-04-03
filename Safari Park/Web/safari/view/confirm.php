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

                    <form class="form-login" action="">
                        <h2 class="form-login-heading">Email Confirmed</h2>
                        <div class="login-wrap">


                            <?php
                            if (isset($_GET["for"]) && !empty($_GET["for"])) {
                                $url_key = $_GET["for"];
                                $check_key = $DB->sql("SELECT * FROM `user` WHERE `key` = '" . $url_key . "'", 0, 0, 0);

                                if (count($check_key) > 0) {
                                    $DB->sql("UPDATE `user` SET `key` = '" . $key . "', last_seen = '" . $time . "', status = '1' WHERE `key` = '" . $url_key . "'", 0, 0, 0);
                                    $user_data = $check_key["0"];
                                    $first_name = $user_data["first_name"];
                                    $email = $user_data["email"];
                                    $user_id = $user_data["user_id"];

                                    /*

                                    $_input = array();
                                    $_input[] = 'আপনার নতুন অ্যাকাউন্ট প্রায় সমাপ্ত...'; //Heading
                                    $_input[] = "আপনার ইমেল ঠিকানাটি নিশ্চিত করতে হবে। সাইন আপ প্রক্রিয়া সম্পন্ন করতে, নীচের লিঙ্কে ক্লিক করুন।"; //Message
                                    $_input[] = "ইমেইল নিশ্চিত করুন"; //Button text
                                    $_input[] = (APP_DOM . "confirm/" . $key); //Button link

                                    $_output = $email_template->execute(DIR_VIEW . "email_template", $_input);

                                    $mail->addAddress($email, $first_name);
                                    $mail->isHTML(true);
                                    $mail->Subject = 'Email Confirmation';
                                    $mail->Body = $_output;
                                    $mail->send();

                                    */
                                    ?>

                                    <div class="registration">
                                        <div style="font-size: 100px; color: limegreen"><i
                                                    class="fa fa-check-circle"></i></div>
                                        You successfully confirmed your email address. Now you can login from your
                                        cellphone app using your credentials.
                                        <br>
                                        Once again thank you very much.
                                    </div>

                                <?php } else { ?>

                                    <div class="registration">
                                        <div style="font-size: 100px; color: darkred"><i class="fa fa-hand-paper-o"></i>
                                        </div>
                                        The confirmation key expired or invalid. Please try to confirm your email again.
                                    </div>

                                <?php } ?>

                            <?php } else { ?>

                                <div class="registration">
                                    <div style="font-size: 100px; color: darkred"><i class="fa fa-hand-paper-o"></i>
                                    </div>
                                    Your link missing confirmation key. Please check again your email address again. If
                                    you
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
