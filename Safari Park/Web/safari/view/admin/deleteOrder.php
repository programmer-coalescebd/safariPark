<?php
/**
 * Created by PhpStorm.
 * User: ahstanin
 * Date: 8/11/17
 * Time: 12:45 PM
 */

$additional_header = '';
$page_title = $lang_delete_new[$lang];

require_once 'header.php';

if (isset($_POST["confirm"]) && isset($_GET["job"])) {
    $job = trim($_GET["job"]);


    $DB->sql("DELETE FROM `orders` WHERE order_id = '" . $job . "'", 0, 0, 0);

    if ($lang == 'bn') {
        $message = '<div class="alert alert-success alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <span class="bangla"><strong>সফল!</strong> অর্ডার অপসারণ হয়েছে</span>
                    </div>';
    } else {
        $message = '<div class="alert alert-success alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>Success!</strong> Order has been deleted.
                    </div>';
    }

    echo '<meta http-equiv="refresh" content="0;url=' . APP_DOM . 'admin/tickets" />';


} else {
    $message = '';
}

if (isset($_GET["job"])) {
    $job = trim($_GET["job"]);
    $orders = $DB->sql("SELECT * FROM `orders` WHERE order_id = '" . $job . "'", 0, 0, 0);
}

if (isset($orders) && count($orders) > 0) {
    $order = $orders[0];

    ?>


    <div class="container">

        <?php echo $message; ?>

        <div class="row">
            <div class="col-sm-12 parentTab" style="margin-bottom: 20px">
                <h4 class="m-t-0 header-title"><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_delete_new[$lang] . '</span>' : $lang_delete_new[$lang]); ?></h4>

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
                        <label class="col-md-4 control-label" style="text-align: left">
                            <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_invoice[$lang] . '</span>' : $lang_ticket_invoice[$lang]); ?>
                        </label>
                        <div class="col-md-8">
                            <input type="text" class="form-control<?php echo($lang == 'bn' ? ' bangla' : ''); ?>"
                                   disabled
                                   value="<?php echo $order['invoice_id']; ?>">
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-horizontal">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-4 control-label" style="text-align: left">
                            <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_ticket_price[$lang] . '</span>' : $lang_ticket_price[$lang]); ?>
                        </label>
                        <div class="col-md-8">
                            <input type="text" class="form-control"
                                   disabled
                                   value="<?php echo number_format($order['ticket_amount'], 2); ?> BDT">
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


                    <button type="button" class="btn btn-success btn-bordered"
                            onclick="window.history.go(-1); return false;"
                            style="margin: 0 20px">
                        <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_confirm_no[$lang] . '</span>' : $lang_confirm_no[$lang]); ?>
                    </button>
                </form>
            </div>
        </div>

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

$additional_footer = '';
require_once 'footer.php';

?>
