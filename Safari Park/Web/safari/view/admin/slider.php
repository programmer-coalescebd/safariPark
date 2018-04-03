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

$page_title = $lang_slider_menu[$lang];

require_once 'header.php';

$slides = $DB->sql("SELECT slide_id, unique_id, slide_name, slide_image, slide_position, status FROM `slider`", 0, 0, 0);

?>

    <div class="container">

        <div class="row">
            <div class="col-sm-12 parentTab">
                <h4 class="m-t-0 header-title"><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_slider_list[$lang] . '</span>' : $lang_slider_list[$lang]); ?></h4>

                <button class="btn btn-icon btn-success addNew" onclick="location.href='/admin/addSlide';">
                    <i class="fa fa-file-text-o"></i>
                    <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_add_new[$lang] . '</span>' : $lang_add_new[$lang]); ?>
                </button>

                <div class="table-responsive m-b-20">

                    <table id="datatable" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_menu_slide_picture[$lang] . '</span>' : $lang_menu_slide_picture[$lang]); ?></th>
                            <th><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_menu_slide_name[$lang] . '</span>' : $lang_menu_slide_name[$lang]); ?></th>
                            <th><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_menu_slide_position[$lang] . '</span>' : $lang_menu_slide_position[$lang]); ?></th>
                            <th><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_menu_page_publish[$lang] . '</span>' : $lang_menu_page_publish[$lang]); ?></th>
                            <th><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_actions[$lang] . '</span>' : $lang_actions[$lang]); ?></th>
                        </tr>
                        </thead>


                        <tbody>


                        <?php
                        if (count($slides) > 0) {
                            foreach ($slides as $slide) {
                                echo '<tr>
                                        <td style="text-align: center">' . ($slide["slide_image"] != '' ? '<img src="/' . $slide["slide_image"] . '" width="100" />' : '<img src="/assets/no-image.png" width="100" />') . '</td>
                                        <td class="bangla">' . $slide["slide_name"] . '</td>
                                        <td>' . $slide["slide_position"] . '</td>
                                        <td class="bangla">' . ($slide["status"] == 1 ? '<i class="mdi mdi-check-circle" style="font-size: 20px; color: limegreen"></i> ' . $lang_confirm_yes[$lang] : '<i class="mdi mdi-minus-circle" style="font-size: 20px; color: orangered"></i> ' . $lang_confirm_no[$lang]) . '</td>
                                        <td>
                                            <button class="btn btn-icon btn-primary" style="padding:3px 6px" onclick="location.href=\'/admin/editSlide/' . $slide["unique_id"] . '\';"><i class="fa fa-pencil"></i></button>
                                            <button class="btn btn-icon btn-danger" style="padding:3px 6px" onclick="location.href=\'/admin/deleteSlide/' . $slide["unique_id"] . '\';"><i class="fa fa-trash"></i></button>
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
    <!-- end container -->

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

        <!-- init -->
        <script src="/assets/admin/pages/jquery.datatables.init.js"></script>';
require_once 'footer.php';
?>