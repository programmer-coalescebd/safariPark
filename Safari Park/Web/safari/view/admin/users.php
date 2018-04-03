<?php
/**
 * Created by PhpStorm.
 * User: ahstanin
 * Date: 8/7/17
 * Time: 9:45 PM
 */

$additional_header = '<link href="/assets/admin/plugins/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/admin/plugins/datatables/buttons.bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/admin/plugins/datatables/responsive.bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/admin/plugins/datatables/scroller.bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/admin/plugins/datatables/dataTables.colVis.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/admin/plugins/datatables/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/admin/plugins/datatables/fixedColumns.dataTables.min.css" rel="stylesheet" type="text/css"/>';

$page_title = $lang_user_menu[$lang];

require_once 'header.php';


if (isset($_POST["searchBy"])) {
    $searchBy = $_POST["searchBy"];
    if ($searchBy == 'mobile') {
        $addSQL = 'AND phone LIKE "%' . $_POST["mobile"] . '%"';
    } elseif ($searchBy == 'name') {
        $addSQL = 'AND first_name LIKE "%' . $_POST["name"] . '%"';
    } elseif ($searchBy == 'email') {
        $addSQL = 'AND email LIKE "%' . $_POST["email"] . '%"';
    }

} else {
    $addSQL = '';
}


$users = $DB->sql("SELECT user_id, unique_id, email, phone, first_name, last_seen, platform, status FROM `user` WHERE state = '0' " . $addSQL, 0, 1000, 0);

?>


    <div class="container">


        <div class="row">
            <h4 class="m-t-0 header-title"><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_user_menu[$lang] . '</span>' : $lang_user_menu[$lang]); ?></h4>

            <div class="col-md-12">
                <div class="panel panel-default panel-fill">
                    <div class="panel-body">
                        <form action="" method="post">
                            <div class="col-sm-12">
                                <div class="col-sm-3">
                                    <select id="searchBy"
                                            class="form-control<?php echo($lang == 'bn' ? ' bangla' : ''); ?>"
                                            name="searchBy">
                                        <option value="mobile" <?php echo(isset($searchBy) && $searchBy == 'mobile' ? 'selected' : ''); ?>><?php echo $lang_search_mobile[$lang]; ?></option>
                                        <option value="name" <?php echo(isset($searchBy) && $searchBy == 'name' ? 'selected' : ''); ?>><?php echo $lang_search_name[$lang]; ?></option>
                                        <option value="email" <?php echo(isset($searchBy) && $searchBy == 'email' ? 'selected' : ''); ?>><?php echo $lang_email_address[$lang]; ?></option>
                                    </select>
                                </div>
                                <div class="col-sm-8">
                                    <div id="mobileDIV" class="searchBy">
                                        <input type="text"
                                               class="form-control<?php echo($lang == 'bn' ? ' bangla' : ''); ?>"
                                               name="mobile"
                                               placeholder="<?php echo $lang_search_mobile[$lang]; ?>" <?php echo(isset($_POST["mobile"]) ? 'value="' . $_POST["mobile"] . '"' : ''); ?>>
                                    </div>

                                    <div id="nameDIV" style="display: none" class="searchBy">
                                        <input type="text"
                                               class="form-control<?php echo($lang == 'bn' ? ' bangla' : ''); ?>"
                                               name="name"
                                               placeholder="<?php echo $lang_search_name[$lang]; ?>" <?php echo(isset($_POST["name"]) ? 'value="' . $_POST["name"] . '"' : ''); ?>>
                                    </div>

                                    <div id="emailDIV" style="display: none" class="searchBy">
                                        <input type="text"
                                               class="form-control<?php echo($lang == 'bn' ? ' bangla' : ''); ?>"
                                               name="email"
                                               placeholder="<?php echo $lang_email_address[$lang]; ?>" <?php echo(isset($_POST["email"]) ? 'value="' . $_POST["email"] . '"' : ''); ?>>
                                    </div>
                                </div>
                                <div class="col-sm-1">
                                    <button type="submit"
                                            class="btn btn-primary<?php echo($lang == 'bn' ? ' bangla' : ''); ?>">
                                        <?php echo $lang_search[$lang]; ?>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-sm-12 parentTab">
                <div class="table-responsive m-b-20">

                    <table id="datatable" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_user_name[$lang] . '</span>' : $lang_user_name[$lang]); ?></th>
                            <th><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_user_email[$lang] . '</span>' : $lang_user_email[$lang]); ?></th>
                            <th><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_user_mobile[$lang] . '</span>' : $lang_user_mobile[$lang]); ?></th>
                            <th><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_user_device[$lang] . '</span>' : $lang_user_device[$lang]); ?></th>
                            <th><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_user_last[$lang] . '</span>' : $lang_user_last[$lang]); ?></th>
                            <th><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_actions[$lang] . '</span>' : $lang_actions[$lang]); ?></th>
                        </tr>
                        </thead>


                        <tbody>

                        <?php
                        if (count($users) > 0) {

                            foreach ($users as $user) {
                                echo '<tr>
                                        <td>' . $user["first_name"] . '</td>
                                        <td>' . $user["email"] . '</td>
                                        <td>' . $user["phone"] . '</td>
                                        <td>' . $user["platform"] . '</td>
                                        <td>' . date("d/m/Y  h:i A", $user["last_seen"]) . '</td>
                                        <td>
                                            <button class="btn btn-icon btn-primary" style="padding:3px 6px" onclick="location.href=\'/admin/editUser/' . $user["unique_id"] . '\';"><i class="fa fa-pencil"></i></button>
                                            <button class="btn btn-icon btn-danger" style="padding:3px 6px" onclick="location.href=\'/admin/deleteUser/' . $user["unique_id"] . '\';"><i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>';
                            }

                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>


<?php
if ($lang == 'bn') {
    $additional_footer = '<script src="/assets/admin/plugins/datatables/jquery.dataTables.min.bn.js"></script>';
} else {
    $additional_footer = '<script src="/assets/admin/plugins/datatables/jquery.dataTables.min.js"></script>';
}

$additional_footer .= '
        <script src="/assets/admin/plugins/datatables/dataTables.bootstrap.js"></script>
        <script src="/assets/admin/plugins/datatables/dataTables.buttons.min.js"></script>
        <script src="/assets/admin/plugins/datatables/buttons.bootstrap.min.js"></script>
        <script src="/assets/admin/plugins/datatables/jszip.min.js"></script>
        <script src="/assets/admin/plugins/datatables/pdfmake.min.js"></script>
        <script src="/assets/admin/plugins/datatables/vfs_fonts.js"></script>
        <script src="/assets/admin/plugins/datatables/buttons.html5.min.js"></script>
        <script src="/assets/admin/plugins/datatables/buttons.print.min.js"></script>
        <script src="/assets/admin/plugins/datatables/dataTables.keyTable.min.js"></script>
        <script src="/assets/admin/plugins/datatables/dataTables.responsive.min.js"></script>
        <script src="/assets/admin/plugins/datatables/responsive.bootstrap.min.js"></script>
        <script src="/assets/admin/plugins/datatables/dataTables.scroller.min.js"></script>
        <script src="/assets/admin/plugins/datatables/dataTables.colVis.js"></script>
        <script src="/assets/admin/plugins/datatables/dataTables.fixedColumns.min.js"></script>

        
        <script src="/assets/admin/pages/jquery.datatables.init.js"></script>
        
        <script>
        var selectedVal = $("#searchBy").val();
        $(".searchBy").css("display","none");
        $("#"+selectedVal+"DIV").css("display","block");
        
        $("#searchBy").change(function() {
            var selectVal = $(this).val();
            $(".searchBy").css("display","none");
            $("#"+selectVal+"DIV").css("display","block");
        });
        </script>
        
        ';

if (isset($childJS)) {
    $additional_footer .= $childJS;
}

require_once 'footer.php';
?>