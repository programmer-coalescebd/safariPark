<?php
/**
 * Created by PhpStorm.
 * User: ahstanin
 * Date: 8/7/17
 * Time: 9:45 PM
 */
?>
<div class="footer">
    <div class="pull-right hidden-xs">
        <?php echo($lang == 'bn' ? '<a class="text-dark" href="/index.php?lang=en">English</a> | <span class="bangla">বাংলা</span>' : 'English | <a class="text-dark" href="/index.php?lang=bn"><span class="bangla">বাংলা</span></a>'); ?>
    </div>
    <div>
        <strong><?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_app_name[$lang] . '</span>' : $lang_app_name[$lang]); ?></strong> - <?php echo($lang == 'bn' ? '<span class="bangla">' . $lang_app_copyright[$lang] . '</span>' : $lang_app_copyright[$lang]); ?> &copy; <?php echo date("Y"); ?>
    </div>
</div> <!-- end footer -->

</div>
<!-- End #page-right-content -->


</div>
<!-- end .page-contentbar -->
</div>
<!-- End #page-wrapper -->


<!-- js placed at the end of the document so the pages load faster -->
<script src="/assets/admin/js/jquery-2.1.4.min.js"></script>
<script src="/assets/admin/js/bootstrap.min.js"></script>
<script src="/assets/admin/js/metisMenu.min.js"></script>
<script src="/assets/admin/js/jquery.slimscroll.min.js"></script>

<?php echo $additional_footer; ?>

<!-- App Js -->
<script src="/assets/admin/js/jquery.app.js"></script>

</body>
</html>
