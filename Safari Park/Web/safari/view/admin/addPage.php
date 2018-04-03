<?php
/**
 * Created by PhpStorm.
 * User: ahstanin
 * Date: 8/7/17
 * Time: 9:45 PM
 */

define('THUMBNAIL_IMAGE_MAX_WIDTH', 64);
define('THUMBNAIL_IMAGE_MAX_HEIGHT', 64);

function generate_image_thumbnail($source_image_path, $thumbnail_image_path)
{
    list($source_image_width, $source_image_height, $source_image_type) = getimagesize($source_image_path);
    switch ($source_image_type) {
        case IMAGETYPE_GIF:
            $source_gd_image = imagecreatefromgif($source_image_path);
            break;
        case IMAGETYPE_JPEG:
            $source_gd_image = imagecreatefromjpeg($source_image_path);
            break;
        case IMAGETYPE_PNG:
            $source_gd_image = imagecreatefrompng($source_image_path);
            break;
    }
    if ($source_gd_image === false) {
        return false;
    }
    $source_aspect_ratio = $source_image_width / $source_image_height;
    $thumbnail_aspect_ratio = THUMBNAIL_IMAGE_MAX_WIDTH / THUMBNAIL_IMAGE_MAX_HEIGHT;
    if ($source_image_width <= THUMBNAIL_IMAGE_MAX_WIDTH && $source_image_height <= THUMBNAIL_IMAGE_MAX_HEIGHT) {
        $thumbnail_image_width = $source_image_width;
        $thumbnail_image_height = $source_image_height;
    } elseif ($thumbnail_aspect_ratio > $source_aspect_ratio) {
        $thumbnail_image_width = (int)(THUMBNAIL_IMAGE_MAX_HEIGHT * $source_aspect_ratio);
        $thumbnail_image_height = THUMBNAIL_IMAGE_MAX_HEIGHT;
    } else {
        $thumbnail_image_width = THUMBNAIL_IMAGE_MAX_WIDTH;
        $thumbnail_image_height = (int)(THUMBNAIL_IMAGE_MAX_WIDTH / $source_aspect_ratio);
    }

    $thumbnail_gd_image = imagecreatetruecolor($thumbnail_image_width, $thumbnail_image_height);
    imagealphablending($thumbnail_gd_image, false);
    $transparency = imagecolorallocatealpha($thumbnail_gd_image, 0, 0, 0, 127);
    imagefill($thumbnail_gd_image, 0, 0, $transparency);
    imagesavealpha($thumbnail_gd_image, true);

    imagecopyresampled($thumbnail_gd_image, $source_gd_image, 0, 0, 0, 0, $thumbnail_image_width, $thumbnail_image_height, $source_image_width, $source_image_height);


    $img_disp = imagecreatetruecolor(THUMBNAIL_IMAGE_MAX_WIDTH, THUMBNAIL_IMAGE_MAX_WIDTH);
    imagealphablending($img_disp, false);
    $transparency = imagecolorallocatealpha($img_disp, 0, 0, 0, 127);
    imagefill($img_disp, 0, 0, $transparency);
    imagesavealpha($img_disp, true);

    imagecopy($img_disp, $thumbnail_gd_image, (imagesx($img_disp) / 2) - (imagesx($thumbnail_gd_image) / 2), (imagesy($img_disp) / 2) - (imagesy($thumbnail_gd_image) / 2), 0, 0, imagesx($thumbnail_gd_image), imagesy($thumbnail_gd_image));

    imagepng($img_disp, $thumbnail_image_path, 9);
    imagedestroy($source_gd_image);
    imagedestroy($thumbnail_gd_image);
    imagedestroy($img_disp);
    return true;
}


if (isset($_POST["pageName"])) {
    $pageName = $_POST["pageName"];
    $menuName = $_POST["menuName"];
    $pageParent = $_POST["pageParent"];
    $gridMenu = $_POST["gridMenu"];
    $menuPosition = $_POST["menuPosition"];
    $menuCategory = $_POST["menuCategory"];
    $pageContent = $_POST["pageContent"];
    $publish = $_POST["publish"];

    $picture = (isset($_FILES["menuIcon"]) ? $_FILES["menuIcon"] : '');


    if ($pageName == '') {
        if ($lang == 'bn') {
            $message = '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <span class="bangla"><strong>সতর্কতা!</strong> পাতার শিরোনাম ফাঁকা হতে পারে না</span>
                    </div>';
        } else {
            $message = '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>Warning!</strong> Page Name cannot be empty.
                    </div>';
        }
    } elseif ($menuName == '') {
        if ($lang == 'bn') {
            $message = '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <span class="bangla"><strong>সতর্কতা!</strong> মেনুর নাম ফাঁকা হতে পারে না</span>
                    </div>';
        } else {
            $message = '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>Warning!</strong> Menu Name cannot be empty.
                    </div>';
        }
    } else {

        if (isset($picture["name"]) && $picture["name"] != '') {
            $file_name = $encryption->random_key();
            $ext = pathinfo($picture["name"], PATHINFO_EXTENSION);
            $uploaded = "content/" . $file_name . "." . $ext;
            move_uploaded_file($picture['tmp_name'], $uploaded);
            generate_image_thumbnail($uploaded, $uploaded);
            $picture = $uploaded;
        } else {
            $picture = '';
        }

        $unique_id = $encryption->unique_id();
        $base64 = ($picture != '' ? 'data:image/png;base64,' . base64_encode(file_get_contents($picture)) : '');
        $user_id = $admin_data["user_id"];


        if ($menuCategory == 'entryTicket' || $menuCategory == 'safariTicket') {
            $check = $DB->sql("SELECT
(SELECT COUNT(page_id) FROM `pages` WHERE menu_category = 'entryTicket') AS entry,
(SELECT COUNT(page_id) FROM `pages` WHERE menu_category = 'safariTicket') AS safari", 0, 0, 0);


            if ($menuCategory == 'entryTicket' && $check[0]["entry"] > 0) {
                if ($lang == 'bn') {
                    $message = '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <span class="bangla"><strong>সতর্কতা!</strong> প্রবেশ টিকেট মেনু নকল হতে পারে না</span>
                    </div>';
                } else {
                    $message = '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>Warning!</strong> Entry Ticket Menu cannot be duplicate.
                    </div>';
                }
                @unlink($picture);
            } elseif ($menuCategory == 'safariTicket' && $check[0]["safari"] > 0) {
                if ($lang == 'bn') {
                    $message = '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <span class="bangla"><strong>সতর্কতা!</strong> সাফারি টিকেট মেনু নকল হতে পারে না</span>
                    </div>';
                } else {
                    $message = '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>Warning!</strong> Safari Ticket Menu cannot be duplicate.
                    </div>';
                }
                @unlink($picture);
            } else {
                $executeSQL = true;
            }
        } else {
            $executeSQL = true;
        }

        if (isset($executeSQL) && $executeSQL == true) {
            ///MySQL Insert here
            $sql = "INSERT INTO `pages` (
                  `page_id`,
                  `unique_id`,
                  `parent_id`,
                  `grid_menu`,
                  `page_name`,
                  `menu_name`,
                  `menu_icon`,
                  `icon_base64`,
                  `menu_position`,
                  `menu_category`,
                  `page_content`,
                  `status`,
                  `created_by`,
                  `created_from`,
                  `created_at`,
                  `modified_by`
                )
              VALUES (
                  NULL,
                  '" . $unique_id . "',
                  '" . $pageParent . "',
                  '" . $gridMenu . "',
                  '" . addslashes(trim($pageName)) . "',
                  '" . addslashes(trim($menuName)) . "',
                  '" . $picture . "',
                  '" . $base64 . "',
                  '" . $menuPosition . "',
                  '" . $menuCategory . "',
                  '" . addslashes(trim($pageContent)) . "',
                  '" . $publish . "',
                  '" . $user_id . "',
                  '" . $ip . "',
                  '" . $time . "',
                  '0'
              );";


            $DB->sql($sql, 0, 0, 0);


            //ContentVersion
            $newVer = $contentVersion + 0.01;
            $DB->sql("UPDATE core SET `value` = '" . $newVer . "' WHERE `key` = 'content_version'", 0, 0, 0);


            if ($lang == 'bn') {
                $message = '<div class="alert alert-success alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <span class="bangla"><strong>সফল!</strong> নতুন পৃষ্ঠা যোগ করা হয়েছে</span>
                    </div>';
            } else {
                $message = '<div class="alert alert-success alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>Success!</strong> New page has been added.
                    </div>';
            }

            //echo '<meta http-equiv="refresh" content="0;url=' . APP_DOM . 'admin/pages" />';
        }


    }


} else {
    $message = '';
}


$additional_header = '<link href="/assets/admin/plugins/summernote/summernote.css" rel="stylesheet" />';
$page_title = $lang_add_new_page[$lang];

require_once 'header.php';


$pages = $DB->sql("SELECT page_id, parent_id, menu_name, status FROM `pages` WHERE status = '1' AND parent_id = '0' AND menu_category != 'entryTicket' AND menu_category != 'safariTicket'", 0, 0, 0);

?>

    <div class="container">

        <?php echo $message; ?>
        <div class="row">
            <div class="col-sm-12 parentTab" style="margin-bottom: 20px">
                <h4 class="m-t-0 header-title"><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_add_new_page[$lang] . '</span>' : $lang_add_new_page[$lang]); ?></h4>

                <button class="btn btn-icon btn-success addNew" onclick="location.href='/admin/pages';">
                    <i class="fa fa-chevron-left"></i>
                    <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_go_back[$lang] . '</span>' : $lang_go_back[$lang]); ?>
                </button>
            </div>
        </div>

        <form action="" method="post" enctype="multipart/form-data">

            <div class="row">
                <div class="form-horizontal">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-3 control-label" style="text-align: left">
                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_menu_page_name[$lang] . '</span>' : $lang_menu_page_name[$lang]); ?>
                            </label>
                            <div class="col-md-9">
                                <input type="text" class="form-control<?php echo($lang == 'bn' ? ' bangla' : ''); ?>"
                                       placeholder="<?php echo $lang_menu_page_name[$lang]; ?>"
                                       name="pageName"
                                       value="<?php echo(isset($pageName) ? $pageName : ''); ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label" style="text-align: left">
                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_menu_menu_parent[$lang] . '</span>' : $lang_menu_menu_parent[$lang]); ?>
                            </label>
                            <div class="col-md-9">
                                <select class="form-control<?php echo($lang == 'bn' ? ' bangla' : ''); ?>"
                                        name="pageParent">
                                    <option value="0"><?php echo $lang_menu_menu_parent_select[$lang]; ?></option>
                                    <!-- PHP LOOP -->

                                    <?php
                                    if (count($pages) > 0) {
                                        foreach ($pages as $page) {
                                            echo '<option value="' . $page['page_id'] . '">' . $page['menu_name'] . '</option>';

                                            $firstChild = $DB->sql("SELECT page_id, parent_id, menu_name, status FROM pages WHERE parent_id = '" . $page["page_id"] . "' ORDER BY page_id ASC", 0, 0, 0);

                                            if (count($firstChild) > 0) {
                                                foreach ($firstChild as $first) {
                                                    echo '<option value="' . $first['page_id'] . '">-' . $first['menu_name'] . '</option>';

                                                    $secondChild = $DB->sql("SELECT page_id, parent_id, menu_name, status FROM pages WHERE parent_id = '" . $first["page_id"] . "' ORDER BY page_id ASC", 0, 0, 0);

                                                    if (count($secondChild) > 0) {
                                                        foreach ($secondChild as $second) {
                                                            echo '<option value="' . $second['page_id'] . '">--' . $second['menu_name'] . '</option>';
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label" style="text-align: left">
                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_menu_menu_position[$lang] . '</span>' : $lang_menu_menu_position[$lang]); ?>
                            </label>
                            <div class="col-md-9">
                                <input type="number" class="form-control<?php echo($lang == 'bn' ? ' bangla' : ''); ?>"
                                       placeholder="<?php echo $lang_menu_menu_position[$lang]; ?>"
                                       min="0" max="24"
                                       name="menuPosition"
                                       value="0">
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-md-3 control-label" style="text-align: left">
                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_grid_menu[$lang] . '</span>' : $lang_grid_menu[$lang]); ?>
                            </label>

                            <div class="col-md-9">
                                <select class="form-control<?php echo($lang == 'bn' ? ' bangla' : ''); ?>"
                                        name="gridMenu">
                                    <option value="1" selected><?php echo $lang_confirm_yes[$lang]; ?></option>
                                    <option value="0"><?php echo $lang_confirm_no[$lang]; ?></option>
                                </select>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="form-horizontal">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-3 control-label" style="text-align: left">
                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_menu_menu_name[$lang] . '</span>' : $lang_menu_menu_name[$lang]); ?>
                            </label>
                            <div class="col-md-9">
                                <input type="text" class="form-control<?php echo($lang == 'bn' ? ' bangla' : ''); ?>"
                                       placeholder="<?php echo $lang_menu_menu_name[$lang]; ?>"
                                       name="menuName"
                                       value="<?php echo(isset($menuName) ? $menuName : ''); ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label" style="text-align: left">
                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_menu_menu_icon[$lang] . '</span>' : $lang_menu_menu_icon[$lang]); ?>
                            </label>
                            <div class="col-md-9">
                                <input type="file" name="menuIcon" accept="image/png"
                                       class="form-control<?php echo($lang == 'bn' ? ' bangla' : ''); ?>" value="">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label" style="text-align: left">
                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_menu_menu_category[$lang] . '</span>' : $lang_menu_menu_category[$lang]); ?>
                            </label>

                            <div class="col-md-9">
                                <select class="form-control<?php echo($lang == 'bn' ? ' bangla' : ''); ?>"
                                        name="menuCategory">
                                    <option value="page"><?php echo $lang_menu_page[$lang]; ?></option>
                                    <option value="entryTicket"><?php echo $lang_ticket_entry_menu[$lang]; ?></option>
                                    <option value="safariTicket"><?php echo $lang_ticket_safari_menu[$lang]; ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <hr/>


            <div class="row">
                <div class="col-sm-12">
                    <div class="p-20 m-b-20">
                        <h4 class="m-b-30 m-t-0 header-title">
                            <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_menu_page_detail[$lang] . '</span>' : $lang_menu_page_detail[$lang]); ?>
                        </h4>
                        <textarea name="pageContent" class="summernote">
                            <?php echo(isset($pageContent) ? $pageContent : '<h4>নতুন পৃষ্ঠা যোগ করুন</h4>
                            <ul>
                                <li>
                                    আপনি ছবি যোগ করতে পারেন
                                </li>
                                <li>
                                    আপনি টেবিল এবং তালিকা যোগ করতে পারেন
                                </li>
                            </ul>'); ?>
                        </textarea>
                    </div>
                </div>
            </div>
            <!-- end row -->


            <div class="row" style="padding-bottom: 20px">
                <div class="col-sm-12" style="text-align: right">
                    <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_menu_page_publish[$lang] . '</span>' : $lang_menu_page_publish[$lang]); ?>

                    <div class="radio radio-success in-line">
                        <input type="radio" name="publish" id="radio14" value="1" checked="">
                        <label for="radio14">
                            <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_confirm_yes[$lang] . '</span>' : $lang_confirm_yes[$lang]); ?>
                        </label>
                    </div>

                    <div class="radio radio-danger in-line">
                        <input type="radio" name="publish" id="radio16" value="0">
                        <label for="radio16">
                            <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_confirm_no[$lang] . '</span>' : $lang_confirm_no[$lang]); ?>
                        </label>
                    </div>


                    <button type="submit" class="btn btn-success btn-bordered" style="margin: 0 20px">
                        <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_add_new[$lang] . '</span>' : $lang_add_new[$lang]); ?>
                    </button>

                </div>
            </div>

        </form>


    </div>
    <!-- end container -->

<?php
$additional_footer = ($lang == 'bn' ? '<script src="/assets/admin/plugins/summernote/summernote.min.bd.js"></script>' : '<script src="/assets/admin/plugins/summernote/summernote.min.js"></script>');

$additional_footer .= '
<script type="text/javascript">
            $(document).ready(function() {
                $(".summernote").summernote({
                    height: 350,                 // set editor height
                    minHeight: null,             // set minimum height of editor
                    maxHeight: null,             // set maximum height of editor
                    focus: false                 // set focus to editable area after initializing summernote
                });
            });
        </script>';
require_once 'footer.php';
?>