<?php
/**
 * Created by PhpStorm.
 * User: ahstanin
 * Date: 8/12/17
 * Time: 2:26 PM
 */

$additional_header = '<link href="/assets/admin/plugins/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/admin/plugins/datatables/buttons.bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/admin/plugins/datatables/responsive.bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/admin/plugins/datatables/scroller.bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/admin/plugins/datatables/dataTables.colVis.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/admin/plugins/datatables/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/admin/plugins/datatables/fixedColumns.dataTables.min.css" rel="stylesheet" type="text/css"/>';

$page_title = $lang_ticket_hour_menu[$lang];

require_once 'header.php';


echo '<div class="container">';

echo '<div class="row allocationBar"><div class="col-lg-12">';
echo '<ul class="nav nav-tabs tabs-bordered">
        <li class="' . (isset($_GET["job"]) && $_GET["job"] == 'entryHours' ? 'active' : '') . (!isset($_GET["job"]) ? 'active' : '') . '">
            <a href="/admin/hours/entryHours">
                <i class="ti-envelope"></i>
                ' . ($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_entry_menu[$lang] . '</span>' : $lang_ticket_entry_menu[$lang]) . '
            </a>
        </li>
        <li class="' . (isset($_GET["job"]) && $_GET["job"] == 'safariHours' ? 'active' : '') . '">
            <a href="/admin/hours/safariHours">
                <i class="ti-direction"></i>
                ' . ($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_safari_menu[$lang] . '</span>' : $lang_ticket_safari_menu[$lang]) . '
            </a>
        </li>
</ul>';

echo '</div></div>';

if (isset($_GET["job"])) {
    $job = trim($_GET["job"]);
    if ($job == 'entryHours') {
        require_once 'entryHours.php';
    } elseif ($job == 'safariHours') {
        require_once 'safariHours.php';
    } else {
        require_once 'entryHours.php';
    }
} else {
    require_once 'entryHours.php';
}


echo '</div>';

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
