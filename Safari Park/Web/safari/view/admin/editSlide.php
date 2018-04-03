<?php
/**
 * Created by PhpStorm.
 * User: ahstanin
 * Date: 8/11/17
 * Time: 11:41 AM
 */
$additional_header = '
<style>
#image-cropper {
    width: 400px;
    height: 500px;
    margin: 0 auto;
}

.cropit-preview {
    width: 400px;
    height: 400px;
    z-index: 99;
}
.cropit-image-input {
    position: absolute;
    margin-left: auto;
    margin-right: auto;
    left: 0;
    right: 0;
    height: 40px;
    width: 140px;
    top: 0;
    opacity: 0;
}
.fake-file {
    position: relative;
    margin-top: 10px;
}                      
</style>
';

$page_title = $lang_edit_new_slide[$lang];

require_once 'header.php';

define('THUMBNAIL_IMAGE_MAX_WIDTH', 800);
define('THUMBNAIL_IMAGE_MAX_HEIGHT', 800);

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
    imagecopyresampled($thumbnail_gd_image, $source_gd_image, 0, 0, 0, 0, $thumbnail_image_width, $thumbnail_image_height, $source_image_width, $source_image_height);

    $img_disp = imagecreatetruecolor(THUMBNAIL_IMAGE_MAX_WIDTH, THUMBNAIL_IMAGE_MAX_WIDTH);
    $backcolor = imagecolorallocate($img_disp, 0, 0, 0);
    imagefill($img_disp, 0, 0, $backcolor);

    imagecopy($img_disp, $thumbnail_gd_image, (imagesx($img_disp) / 2) - (imagesx($thumbnail_gd_image) / 2), (imagesy($img_disp) / 2) - (imagesy($thumbnail_gd_image) / 2), 0, 0, imagesx($thumbnail_gd_image), imagesy($thumbnail_gd_image));

    imagejpeg($img_disp, $thumbnail_image_path, 75);
    imagedestroy($source_gd_image);
    imagedestroy($thumbnail_gd_image);
    imagedestroy($img_disp);
    return true;
}

function base64_to_jpeg($base64_string, $output_file)
{
    $ifp = fopen($output_file, "wb");

    $data = explode(',', $base64_string);

    fwrite($ifp, base64_decode($data[1]));
    fclose($ifp);

    return $output_file;
}


if (isset($_POST["slideName"]) && isset($_GET["job"])) {
    $job = trim($_GET["job"]);

    $slideName = $_POST['slideName'];
    $slidePosition = $_POST['slidePosition'];
    $publish = $_POST['publish'];
    $imageContent = $_POST['imageContent'];

    $picture = (isset($_FILES["slideImage"]) ? $_FILES["slideImage"] : '');


    if ($slideName == '') {
        if ($lang == 'bn') {
            $message = '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <span class="bangla"><strong>সতর্কতা!</strong> স্লাইডের নাম ফাঁকা হতে পারে না</span>
                    </div>';
        } else {
            $message = '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>Warning!</strong> Slide Name cannot be empty.
                    </div>';
        }
    } else {

        $old_picture = $DB->sql("SELECT slide_image FROM `slider` WHERE unique_id = '" . $job . "'", 0, 0, 0);

        if (isset($picture["name"]) && $picture["name"] != '') {

            if ($old_picture["0"]["slide_image"] != '') {
                @unlink($old_picture["0"]["slide_image"]);
            }

            $file_name = $encryption->random_key();
            $imgLocation = "content/" . $encryption->random_key() . ".jpg";
            base64_to_jpeg($imageContent, $imgLocation);
            $uploaded = "content/" . $encryption->random_key() . ".jpg";
            generate_image_thumbnail($imgLocation, $uploaded);
            $picture = $uploaded;

            @unlink($imgLocation);

        } else {
            $picture = $old_picture[0]['slide_image'];
        }

        $base64 = ($picture != '' ? 'data:image/jpg;base64,' . base64_encode(file_get_contents($picture)) : '');
        $user_id = $admin_data["user_id"];


        $sql = "UPDATE `slider`  SET 
                  `slide_name` = '" . addslashes(trim($slideName)) . "',
                  `slide_image` = '" . $picture . "',
                  `image_base64` = '" . $base64 . "',
                  `slide_position` = '" . $slidePosition . "',
                  `status` = '" . $publish . "',
                  `modified_by` = '" . $user_id . "' WHERE unique_id = '" . $job . "'";


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
                        <span class="bangla"><strong>সফল!</strong> স্লাইড সম্পাদনা করা হয়েছে</span>
                    </div>';
        } else {
            $message = '<div class="alert alert-success alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>Success!</strong> Slide has been updated.
                    </div>';
        }

        //echo '<meta http-equiv="refresh" content="0;url=' . APP_DOM . 'admin/slider" />';

    }


} else {
    $message = '';
}

if (isset($_GET["job"])) {
    $job = trim($_GET["job"]);
    $slides = $DB->sql("SELECT * FROM `slider` WHERE unique_id = '" . $job . "'", 0, 0, 0);
}

if (isset($slides) && count($slides) > 0) {
    $slide = $slides[0];
    ?>


    <div class="container">

        <?php echo $message; ?>

        <div class="row">
            <div class="col-sm-12 parentTab" style="margin-bottom: 20px">
                <h4 class="m-t-0 header-title"><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_edit_new_slide[$lang] . '</span>' : $lang_edit_new_slide[$lang]); ?></h4>

                <button class="btn btn-icon btn-success addNew" onclick="window.history.go(-1); return false;">
                    <i class="fa fa-chevron-left"></i>
                    <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_go_back[$lang] . '</span>' : $lang_go_back[$lang]); ?>
                </button>
            </div>
        </div>


        <form action="" method="post" enctype="multipart/form-data" id="imageUpload">

            <div class="row">
                <div class="form-horizontal">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-3 control-label" style="text-align: left">
                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_menu_slide_name[$lang] . '</span>' : $lang_menu_slide_name[$lang]); ?>
                            </label>
                            <div class="col-md-9">
                                <input type="text" class="form-control<?php echo($lang == 'bn' ? ' bangla' : ''); ?>"
                                       placeholder="<?php echo $lang_menu_slide_name[$lang]; ?>"
                                       name="slideName"
                                       value="<?php echo $slide['slide_name']; ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-horizontal">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-3 control-label" style="text-align: left">
                                <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_menu_slide_position[$lang] . '</span>' : $lang_menu_slide_position[$lang]); ?>
                            </label>
                            <div class="col-md-9">
                                <input type="number" class="form-control<?php echo($lang == 'bn' ? ' bangla' : ''); ?>"
                                       placeholder="<?php echo $lang_menu_slide_position[$lang]; ?>"
                                       name="slidePosition"
                                       min="0" max="24"
                                       value="<?php echo $slide['slide_position']; ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr/>


            <div class="row">
                <div class="col-sm-12" style="text-align: center; background: whitesmoke;">

                    <div id="image-cropper">
                        <!-- This is where the preview image is displayed -->
                        <div class="cropit-preview"></div>
                        <!-- This is where user selects new image -->
                        <div class="btn btn-info fake-file">
                            <span class="fa fa-picture-o"></span>
                            <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_menu_slide_select[$lang] . '</span>' : $lang_menu_slide_select[$lang]); ?>
                            <input type="file" name="slideImage" class="cropit-image-input" accept="image/*"/>
                        </div>
                        <!-- The cropit- classes above are needed
                             so cropit can identify these elements -->
                    </div>

                </div>
            </div>
            <!-- end row -->


            <div class="row" style="padding-bottom: 20px">
                <div class="col-sm-12" style="text-align: right">
                    <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_menu_page_publish[$lang] . '</span>' : $lang_menu_page_publish[$lang]); ?>

                    <div class="radio radio-success in-line">
                        <input type="radio" name="publish"
                               id="radio14" <?php echo($slide['status'] == 1 ? 'checked' : ''); ?> value="1">
                        <label for="radio14">
                            <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_confirm_yes[$lang] . '</span>' : $lang_confirm_yes[$lang]); ?>
                        </label>
                    </div>

                    <div class="radio radio-danger in-line">
                        <input type="radio" name="publish"
                               id="radio16" <?php echo($slide['status'] == 0 ? 'checked' : ''); ?> value="0">
                        <label for="radio16">
                            <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_confirm_no[$lang] . '</span>' : $lang_confirm_no[$lang]); ?>
                        </label>
                    </div>

                    <textarea name="imageContent" id="imageContent" style="display: none"></textarea>

                    <button type="button" class="btn btn-success btn-bordered nextButton" style="margin: 0 20px">
                        <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_edit_new[$lang] . '</span>' : $lang_edit_new[$lang]); ?>
                    </button>

                </div>
            </div>

        </form>

    </div>


<?php } else { ?>

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

$additional_footer = '<script src="/assets/bower_components/cropit/dist/jquery.cropit.js"></script>

<script>
    var cropper = $("#image-cropper");
    cropper.cropit({
    exportZoom: 2,
    onImageError: function (e) {
        alert(e.message);
    }
    });
    
    ' . (isset($slides[0]['slide_image']) ? 'cropper.cropit("imageSrc", "/' . $slides[0]['slide_image'] . '");' : '') . '
    
    
    $(".nextButton").click(function () {
        var imageData = $("#image-cropper").cropit("export");
        $("#imageContent").val(imageData);
        $("#imageUpload").delay(2000).submit();
    });
</script>

';
require_once 'footer.php';

?>
