<?php
/**
 * Created by PhpStorm.
 * User: ahstanin
 * Date: 8/7/17
 * Time: 9:45 PM
 */

$additional_header = '';

$page_title = $lang_settings_menu[$lang];

require_once 'header.php';

if (isset($_POST["advance_ticket"])) {

    foreach ($_POST as $key => $value) {
        $DB->sql("UPDATE core SET `value` = '" . $value . "' WHERE `key` = '" . $key . "'", 0, 0, 0);
    }


    if ($lang == 'bn') {
        $message = '<div class="alert alert-success alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <span class="bangla"><strong>সফল!</strong> সেটিংস আপডেট করা হয়েছে</span>
                    </div>';
    } else {
        $message = '<div class="alert alert-success alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>Success!</strong> Settings has been updated.
                    </div>';
    }


} else {
    $message = '';
}


$coreValues = $DB->sql("SELECT * FROM `core` ORDER BY id ASC", 0, 0, 0);


?>

<?php echo $message; ?>

    <div class="container">
        <div class="row">
            <div class="col-sm-12 parentTab" style="margin-bottom: 20px">
                <h4 class="m-t-0 header-title">
                    <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_settings_menu[$lang] . '</span>' : $lang_settings_menu[$lang]); ?>
                </h4>
            </div>
        </div>


        <form action="" method="post">


            <?php
            foreach ($coreValues as $core) {
                if ($core["key"] != 'content_version') {
                    echo '<div class="row">
                                    <div class="col-sm-12 parentTab" style="margin-bottom: 20px">';

                    echo '<div class="form-group">
                            <label class="col-md-3 control-label ' . ($lang == 'bn' ? 'bangla' : '') . '" style="text-align: left">';

                    if ($core["key"] == 'advance_ticket') {
                        echo $lang_settings_advance[$lang];
                    } elseif ($core["key"] == 'ticket_valid') {
                        echo $lang_settings_valid[$lang];
                    } elseif ($core["key"] == 'purchase_vat') {
                        echo $lang_settings_vat[$lang];
                    } elseif ($core["key"] == 'ticket_limit') {
                        echo $lang_settings_limit[$lang];
                    }

                    echo '</label>
                            <div class="col-md-9">
                                <input type="number" ' . ($core["key"] == 'purchase_vat' ? 'step=".01"' : '') . ' class="form-control" name="' . $core["key"] . '" value="' . $core["value"] . '">
                            </div>
                            </div>';

                    echo '</div>
                            </div>';
                }
            }
            ?>


            <div class="row" style="padding-bottom: 20px">
                <div class="col-sm-12" style="text-align: right">
                    <button type="submit" class="btn btn-primary btn-bordered" style="margin: 0 20px">
                        <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_edit_new[$lang] . '</span>' : $lang_edit_new[$lang]); ?>
                    </button>
                </div>
            </div>
        </form>

    </div>
<?php

$additional_footer = '';

if (isset($childJS)) {
    $additional_footer .= $childJS;
}

require_once 'footer.php';
?>