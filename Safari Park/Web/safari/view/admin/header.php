<?php
/**
 * Created by PhpStorm.
 * User: ahstanin
 * Date: 8/7/17
 * Time: 9:45 PM
 */

function full_name()
{
    $firstName = $_SESSION['admin_data']['first_name'];
    $lastName = $_SESSION['admin_data']['last_name'];
    return $firstName . ' ' . $lastName;
}

$userKey = $_SESSION['admin_data']['unique_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title><?php echo $lang_app_name[$lang] . ' | ' . $page_title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta content="Custom admin panel" name="description"/>
    <meta content="Akram Hasan Sharkar" name="author"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>


    <link rel="shortcut icon" href="/assets/admin/images/favicon.ico">

    <!--Additional Headers -->
    <?php echo $additional_header; ?>


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

<div id="page-wrapper">

    <!-- Top Bar Start -->
    <div class="topbar">

        <!-- LOGO -->
        <div class="topbar-left">
            <div class="">
                <a href="/admin" class="logo">
                    <img src="/assets/admin/images/logo.png" alt="logo" style="width: 55px; height: 55px"
                         class="logo-lg"/>
                    <img src="/assets/admin/images/logo.png" alt="logo" style="width: 55px; height: 55px"
                         class="logo-sm hidden"/>
                </a>
            </div>
        </div>

        <!-- Top navbar -->
        <div class="navbar navbar-default" role="navigation">
            <div class="container">
                <div class="">

                    <!-- Mobile menu button -->
                    <div class="pull-left">
                        <button type="button" class="button-menu-mobile visible-xs visible-sm">
                            <i class="fa fa-bars"></i>
                        </button>
                        <span class="clearfix"></span>
                    </div>

                    <!-- Top nav Right menu -->
                    <ul class="nav navbar-nav navbar-right top-navbar-items-right pull-right">
                        <li class="dropdown top-menu-item-xs">
                            <a href="" class="dropdown-toggle menu-right-item profile" data-toggle="dropdown"
                               aria-expanded="true"><img src="/assets/admin/images/users/no-user-image.png"
                                                         alt="user-img"
                                                         class="img-circle"> </a>
                            <ul class="dropdown-menu">
                                <li><a href="/admin/editEmployee/<?php echo $userKey; ?>"><i
                                                class="ti-lock m-r-10"></i>
                                        <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_dash_account[$lang] . '</span>' : $lang_dash_account[$lang]); ?>
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li><a href="/admin/logout"><i class="ti-power-off m-r-10"></i>
                                        <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_sign_out[$lang] . '</span>' : $lang_sign_out[$lang]); ?>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div> <!-- end container -->
        </div> <!-- end navbar -->
    </div>
    <!-- Top Bar End -->


    <!-- Page content start -->
    <div class="page-contentbar">

        <!--left navigation start-->
        <aside class="sidebar-navigation">
            <div class="scrollbar-wrapper">
                <div>
                    <button type="button" class="button-menu-mobile btn-mobile-view visible-xs visible-sm">
                        <i class="mdi mdi-close"></i>
                    </button>


                    <!-- Left Menu Start -->
                    <ul class="metisMenu nav" id="side-menu">
                        <li><a href="/admin"><i class="ti-home"></i>
                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_dash_menu[$lang] . '</span>' : $lang_dash_menu[$lang]); ?>
                            </a>
                        </li>

                        <li><a href="/admin/pages"><i class="ti-files"></i>
                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_page_menu[$lang] . '</span>' : $lang_page_menu[$lang]); ?>
                            </a>
                        </li>

                        <li><a href="/admin/slider"><i class="ti-gallery"></i>
                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_slider_menu[$lang] . '</span>' : $lang_slider_menu[$lang]); ?>
                            </a>
                        </li>

                        <li><a href="/admin/entry"><i class="ti-envelope"></i>
                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_entry_menu[$lang] . '</span>' : $lang_ticket_entry_menu[$lang]); ?>
                            </a>
                        </li>

                        <li><a href="/admin/safari"><i class="ti-direction"></i>
                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_safari_menu[$lang] . '</span>' : $lang_ticket_safari_menu[$lang]); ?>
                            </a>
                        </li>


                        <li><a href="/admin/allocation"><i class="ti-calendar"></i>
                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_allo_menu[$lang] . '</span>' : $lang_ticket_allo_menu[$lang]); ?>
                            </a>
                        </li>

                        <li><a href="/admin/hours"><i class="ti-alarm-clock"></i>
                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_hour_menu[$lang] . '</span>' : $lang_ticket_hour_menu[$lang]); ?>
                            </a>
                        </li>


                        <li><a href="/admin/tickets"><i class="ti-ticket"></i>
                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_menu[$lang] . '</span>' : $lang_ticket_menu[$lang]); ?>
                            </a>
                        </li>


                        <li><a href="/admin/users"><i class="ti-user"></i>
                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_user_menu[$lang] . '</span>' : $lang_user_menu[$lang]); ?>
                            </a>
                        </li>


                        <li><a href="/admin/employee"><i class="ti-id-badge"></i>
                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_admin_menu[$lang] . '</span>' : $lang_admin_menu[$lang]); ?>
                            </a>
                        </li>

                        <li><a href="/admin/settings"><i class="ti-settings"></i>
                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_settings_menu[$lang] . '</span>' : $lang_settings_menu[$lang]); ?>
                            </a>
                        </li>
                    </ul>
                </div>
            </div><!--Scrollbar wrapper-->
        </aside>
        <!--left navigation end-->

        <!-- START PAGE CONTENT -->
        <div id="page-right-content" style="min-height:unset">