<?php (strpos($_SERVER["REQUEST_URI"], "view") !== false) ? exit('Direct access not allowed') : ''; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head><title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <style type="text/css">
        .ReadMsgBody {
            width: 100%;
            background-color: #ffffff;
        }

        .ExternalClass {
            width: 100%;
            background-color: #ffffff;
        }

        .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {
            line-height: 100%;
        }

        html {
            width: 100%;
        }

        body {
            -webkit-text-size-adjust: none;
            -ms-text-size-adjust: none;
            margin: 0;
            padding: 0;
        }

        table {
            border-spacing: 0;
            border-collapse: collapse;
            table-layout: fixed;
            margin: 0 auto;
        }

        table table table {
            table-layout: auto;
        }

        img {
            display: block !important;
        }

        table td {
            border-collapse: collapse;
        }

        .yshortcuts a {
            border-bottom: none !important;
        }

        a {
            color: #21b6ae;
            text-decoration: none;
        }

        .textbutton a {
            font-family: 'open sans', arial, sans-serif !important;
            color: #ffffff !important;
        }

        .text-link a {
            color: #FFFFFF !important;
        }

        @media only screen and (max-width: 640px) {
            body {
                width: auto !important;
            }

            table[class="table600"] {
                width: 450px !important;
            }

            table[class="table-inner"] {
                width: 90% !important;
            }

            table[class="table3-3"] {
                width: 100% !important;
                text-align: center !important;
            }
        }

        @media only screen and (max-width: 479px) {
            body {
                width: auto !important;
            }

            table[class="table600"] {
                width: 290px !important;
            }

            table[class="table-inner"] {
                width: 82% !important;
            }

            table[class="table3-3"] {
                width: 100% !important;
                text-align: center !important;
            }
        }
    </style>
</head>
<body>
<div class="parentOfBg">
</div>
<table data-module="Notification 1" data-bgcolor="Notificatioon 1" width="100%" border="0" align="center"
       cellpadding="0" cellspacing="0" bgcolor="#379732">
    <tr>
        <td data-bg="Notification 1" align="center" background="{DOMAIN}images/bg.jpg"
            style="background-size:cover; background-position:top;">
            <table class="table600" width="530" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                    <td height="50">
                    </td>
                </tr>

                <tr>
                    <td align="center">
                        <table data-bgcolor="Content BG" align="center" bgcolor="#f5f5f5"
                               style="border-top:5px solid #379732;" width="100%" border="0" cellspacing="0"
                               cellpadding="0">
                            <tr>
                                <td height="40">
                                </td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <table align="center" class="table-inner" width="480" border="0" cellspacing="0"
                                           cellpadding="0">
                                        <!--img-->
                                        <tr>
                                            <td align="center" style="line-height: 0px;">
                                                <a href="{DOMAIN}" style="border:none"><img mc:edit="img"
                                                                                            style="display:block; line-height:0px; font-size:0px; border:0px;"
                                                                                            src="{DOMAIN}images/Logo.png"
                                                                                            alt="logo" width="100"></a>
                                            </td>
                                        </tr>
                                        <!--end img-->
                                        <tr>
                                            <td height="15">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td height="15">
                                            </td>
                                        </tr>
                                        <!-- title -->
                                        <tr>
                                            <td data-link-style="text-decoration:none; color:#21b6ae;"
                                                data-link-color="Content Link" data-color="Tite" data-size="Title"
                                                mc:edit="title" align="center"
                                                style="font-family: 'Open Sans', Arial, sans-serif; font-size:30px; color:#3b3b3b; font-weight: bold; letter-spacing:3px;">
                                                {HEADING}
                                            </td>
                                        </tr>
                                        <!-- end title -->
                                        <tr>
                                            <td height="20">
                                            </td>
                                        </tr>
                                        <!-- content -->
                                        <tr>
                                            <td data-link-style="text-decoration:none; color:#21b6ae;"
                                                data-link-color="Content Link" data-color="Content" data-size="Content"
                                                mc:edit="content"
                                                style="font-family: 'Open Sans', Arial, sans-serif; font-size:13px; color:#7f8c8d; line-height:30px; text-align: center">
                                                {MESSAGE}
                                            </td>
                                        </tr>
                                        <!-- end content -->
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td height="40">
                                </td>
                            </tr>
                            <!-- button -->
                            <tr>
                                <td align="center">
                                    <table data-bgcolor="Main Color" align="center"
                                           style="border-top: 2px solid #379732;" bgcolor="#FFFFFF" border="0"
                                           cellspacing="0" cellpadding="0" style="box-shadow: 0px 1px 0px #d4d2d2;">
                                        <tr>
                                            <td data-color="Button" data-size="Button" mc:edit="button" height="55"
                                                align="center"
                                                style="font-family: 'Open Sans', Arial, sans-serif; font-size:16px; color:#7f8c8d; line-height:30px; padding-left:25px;padding-right: 25px; border: lightgray 1px solid; border-top: 3px solid #379732;">
                                                <a href="{BUTTON_LINK}" style="color:#292b36;" data-color="Button Link">
                                                    {BUTTON_TEXT}</a>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <!-- end button -->
                            <tr>
                                <td height="35">
                                </td>
                            </tr>
                            <tr>
                                <td align="center" style="line-height: 0px;">
                                    <img style="display:block; line-height:0px; font-size:0px; border:0px;"
                                         src="{DOMAIN}images/img1.png" width="40" height="16" alt="logo">
                                </td>
                            </tr>
                            <!-- option -->
                            <tr>
                                <td align="center" bgcolor="#379732">
                                    <table align="center" class="table-inner" width="290" border="0" cellspacing="0"
                                           cellpadding="0">
                                        <tr>
                                            <td height="15">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-link" align="center">
                                                <!-- left -->
                                                <table class="table3-3" width="80" border="0" align="left"
                                                       cellpadding="0" cellspacing="0">
                                                    <tr>
                                                        <td data-link-style="text-decoration:none; color:#FFFFFF;"
                                                            data-link-color="Link" mc:edit="Email option-1"
                                                            align="center"
                                                            style="font-family: 'Open Sans', Arial, sans-serif; font-size:13px; color:#7f8c8d; line-height:30px;">
                                                            <a href="https://www.facebook.com/#"
                                                               style="text-decoration:none; color:#FFFFFF;"
                                                               data-color="Link">
                                                                Facebook</a>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <!-- end left -->
                                                <!--Space-->
                                                <table width="1" height="15" border="0" cellpadding="0" cellspacing="0"
                                                       align="left">
                                                    <tr>
                                                        <td height="15"
                                                            style="font-size: 0;line-height: 0;border-collapse: collapse;">
                                                            <p style="padding-left: 24px;">
                                                                &nbsp;</p>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <!--End Space-->
                                                <!-- middle -->
                                                <table class="table3-3" width="80" border="0" align="left"
                                                       cellpadding="0" cellspacing="0">
                                                    <tr>
                                                        <td data-link-style="text-decoration:none; color:#FFFFFF;"
                                                            data-link-color="Link" mc:edit="Email option-2"
                                                            align="center"
                                                            style="font-family: 'Open Sans', Arial, sans-serif; font-size:13px; color:#7f8c8d; line-height:30px;">
                                                        </td>
                                                    </tr>
                                                </table>
                                                <!-- end middle -->
                                                <!--Space-->
                                                <table width="1" height="15" border="0" cellpadding="0" cellspacing="0"
                                                       align="left">
                                                    <tr>
                                                        <td height="15"
                                                            style="font-size: 0;line-height: 0;border-collapse: collapse;">
                                                            <p style="padding-left: 24px;">
                                                                &nbsp;</p>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <!--End Space-->
                                                <!-- right -->
                                                <table class="table3-3" width="80" border="0" align="right"
                                                       cellpadding="0" cellspacing="0">
                                                    <tr>
                                                        <td data-link-style="text-decoration:none; color:#FFFFFF;"
                                                            data-link-color="Link" mc:edit="link" align="center"
                                                            style="font-family: 'Open Sans', Arial, sans-serif; font-size:13px; color:#7f8c8d; line-height:30px;">
                                                            <a href="{DOMAIN}" style="color:#FFFFFF;"
                                                               data-color="Option Link">
                                                                About us</a>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <!-- end right -->
                                            </td>
                                        </tr>
                                        <tr>
                                            <td height="15">
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <!-- end option -->
                        </table>
                    </td>
                </tr>
                <!-- end profile-img -->
                <tr>
                    <td height="30">
                    </td>
                </tr>

                <tr>
                    <td height="30">
                    </td>
                </tr>
                <!-- copyright
                <tr>
                    <td data-link-style="text-decoration:none; color:#21b6ae;" data-link-color="Copyright Link"
                        data-color="Copyright" data-size="Copyright" mc:edit="copyright" align="center"
                        style="font-family: 'Open Sans', Arial, sans-serif; font-size: 13px; color: rgb(41, 43, 54); line-height: 30px;">
                        Application developed by <a href="https://twitter.com/akhaurabd"
                                                    style="text-decoration: none; color: rgb(60, 65, 68);"
                                                    data-color="Copyright Link">
                            Akhaura Info Foundation</a>
                    </td>
                </tr>
                <!-- end copyright -->
                <tr>
                    <td height="40">
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>