<?php
/**
 * Created by PhpStorm.
 * User: ahstanin
 * Date: 8/10/17
 * Time: 11:29 AM
 */

$additional_header = '';
$page_title = $lang_delete_new_page[$lang];

require_once 'header.php';

if (isset($_POST["confirm"]) && isset($_GET["job"])) {
    $job = trim($_GET["job"]);

    $checkData = $DB->sql("SELECT page_id, menu_icon FROM `pages` WHERE unique_id = '" . $job . "'", 0, 0, 0);
    $checkParent = $DB->sql("SELECT COUNT(page_id) AS has FROM `pages` WHERE parent_id = '" . $checkData[0]['page_id'] . "'", 0, 0, 0);

    if ($checkParent[0]['has'] > 0) {
        if ($lang == 'bn') {
            $message = '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <span class="bangla"><strong>সতর্কতা!</strong> এই পাতাটি অন্য পাতার অভিভাবক</span>
                    </div>';
        } else {
            $message = '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>Warning!</strong> This is a parent page.
                    </div>';
        }
    } else {

        @unlink($checkData[0]['menu_icon']);
        $DB->sql("DELETE FROM `pages` WHERE unique_id = '" . $job . "'", 0, 0, 0);

        //ContentVersion
        $newVer = $contentVersion + 0.01;
        $DB->sql("UPDATE core SET `value` = '" . $newVer . "' WHERE `key` = 'content_version'", 0, 0, 0);

        if ($lang == 'bn') {
            $message = '<div class="alert alert-success alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <span class="bangla"><strong>সফল!</strong> পাতাটি অপসারণ করা হয়েছে</span>
                    </div>';
        } else {
            $message = '<div class="alert alert-success alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>Success!</strong> Page has been deleted.
                    </div>';
        }

        echo '<meta http-equiv="refresh" content="0;url=' . APP_DOM . 'admin/pages" />';

    }

} else {
    $message = '';
}

if (isset($_GET["job"])) {
    $job = trim($_GET["job"]);
    $pages = $DB->sql("SELECT * FROM `pages` WHERE unique_id = '" . $job . "'", 0, 0, 0);
}

function get_parent($parent_id)
{
    if ($parent_id == '0') {
        $output = '-';
    } else {
        $firstParent = $GLOBALS["DB"]->sql("SELECT page_id, parent_id, menu_name, status FROM pages WHERE page_id = '" . $parent_id . "'", 0, 0, 0);
        $output[] = $firstParent[0]['menu_name'];
        if ($firstParent[0]['parent_id'] != '0') {
            $secondParent = $GLOBALS["DB"]->sql("SELECT page_id, parent_id, menu_name, status FROM pages WHERE page_id = '" . $firstParent[0]['parent_id'] . "'", 0, 0, 0);
            $output[] = $secondParent[0]['menu_name'];
            if ($secondParent[0]['parent_id'] != '0') {
                $thirdParent = $GLOBALS["DB"]->sql("SELECT page_id, parent_id, menu_name, status FROM pages WHERE page_id = '" . $secondParent[0]['parent_id'] . "'", 0, 0, 0);
                $output[] = $thirdParent[0]['menu_name'];
            }
        }
    }

    if (is_array($output)) {
        $dump = array_reverse($output);
        $output = '';
        foreach ($dump as $var) {
            $output .= $var . ' => ';
        }

        $output = rtrim($output, ' => ');
    }

    return $output;
}

if (isset($pages) && count($pages) > 0) {
    $page = $pages[0];
    ?>


    <div class="container">

        <?php echo $message; ?>

        <div class="row">
            <div class="col-sm-12 parentTab" style="margin-bottom: 20px">
                <h4 class="m-t-0 header-title"><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_delete_new_page[$lang] . '</span>' : $lang_delete_new_page[$lang]); ?></h4>

                <button class="btn btn-icon btn-success addNew" onclick="window.history.go(-1); return false;">
                    <i class="fa fa-chevron-left"></i>
                    <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_go_back[$lang] . '</span>' : $lang_go_back[$lang]); ?>
                </button>
            </div>
        </div>


        <div class="row">
            <div class="form-horizontal">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-3 control-label" style="text-align: left">
                            <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_menu_page_id[$lang] . '</span>' : $lang_menu_page_id[$lang]); ?>
                        </label>
                        <div class="col-md-9">
                            <input type="text" class="form-control bangla" disabled
                                   value="<?php echo $page['page_id']; ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" style="text-align: left">
                            <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_menu_page_name[$lang] . '</span>' : $lang_menu_page_name[$lang]); ?>
                        </label>
                        <div class="col-md-9">
                            <input type="text" class="form-control bangla" disabled
                                   value="<?php echo $page['page_name']; ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" style="text-align: left">
                            <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_menu_menu_parent[$lang] . '</span>' : $lang_menu_menu_parent[$lang]); ?>
                        </label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" disabled
                                   value="<?php echo get_parent($page['parent_id']); ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" style="text-align: left">
                            <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_menu_menu_position[$lang] . '</span>' : $lang_menu_menu_position[$lang]); ?>
                        </label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" disabled
                                   value="<?php echo $page['menu_position']; ?>">
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-horizontal">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-3 control-label" style="text-align: left">
                            <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_menu_page_publish[$lang] . '</span>' : $lang_menu_page_publish[$lang]); ?>
                        </label>
                        <div class="col-md-9">
                            <input type="text" class="form-control <?php echo($lang == 'bn' ? ' bangla' : ''); ?>"
                                   disabled
                                   value="<?php echo($page['status'] == 1 ? $lang_confirm_yes[$lang] : $lang_confirm_no[$lang]); ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" style="text-align: left">
                            <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_menu_menu_name[$lang] . '</span>' : $lang_menu_menu_name[$lang]); ?>
                        </label>
                        <div class="col-md-9">
                            <input type="text" class="form-control bangla" disabled
                                   value="<?php echo $page['menu_name']; ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" style="text-align: left">
                            <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_menu_menu_icon[$lang] . '</span>' : $lang_menu_menu_icon[$lang]); ?>
                        </label>
                        <div class="col-md-9">
                            <?php echo($page["menu_icon"] != '' ? '<img src="/' . $page["menu_icon"] . '" width="38" />' : '<img src="/assets/no-image.png" width="38" />'); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" style="text-align: left">
                            <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_menu_menu_category[$lang] . '</span>' : $lang_menu_menu_category[$lang]); ?>
                        </label>

                        <div class="col-md-9">
                            <input type="text" class="form-control" disabled
                                   value="<?php echo $page['menu_category']; ?>">
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <hr/>

        <div class="row">
            <div class="col-sm-12" style="text-align: center; margin-top: 30px; margin-bottom: 50px">
                <h3><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_delete_confirm[$lang] . '</span>' : $lang_delete_confirm[$lang]); ?></h3>
                <form action="" method="post">
                    <input type="hidden" name="confirm" value="yes">
                    <button type="submit" class="btn btn-danger btn-bordered" style="margin: 0 20px">
                        <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_confirm_yes[$lang] . '</span>' : $lang_confirm_yes[$lang]); ?>
                    </button>


                    <button type="button" class="btn btn-success btn-bordered" onclick="location.href='/admin/pages';"
                            style="margin: 0 20px">
                        <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_confirm_no[$lang] . '</span>' : $lang_confirm_no[$lang]); ?>
                    </button>
                </form>
            </div>
        </div>


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
