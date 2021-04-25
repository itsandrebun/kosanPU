<!-- Bootstrap core JavaScript-->
<script src="<?=(isset($inside_folder) ? '../../assets/vendor/jquery/jquery.min.js' : '../assets/vendor/jquery/jquery.min.js');?>"></script>
<script src="<?=(isset($inside_folder) ? '../../assets/vendor/bootstrap/js/bootstrap.bundle.min.js' : '../assets/vendor/bootstrap/js/bootstrap.bundle.min.js');?>"></script>

<!-- Core plugin JavaScript-->
<script src="<?=(isset($inside_folder) ? '../../assets/vendor/jquery-easing/jquery.easing.min.js' : '../assets/vendor/jquery-easing/jquery.easing.min.js');?>"></script>

<!-- Custom scripts for all pages-->
<script src="<?=(isset($inside_folder) ? '../../assets/js/sb-admin-2.min.js' : '../assets/js/sb-admin-2.min.js');?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
<?php if(isset($_SESSION['user'])):?>
<script>
    var fullUrl = "<?= $actual_link;?>";
    var tenant_id = null;
    var total_unread_notification = "<?= $total_unread_notifications;?>";
</script>
<script src="<?=(isset($inside_folder) ? '../../assets/js/notification.js' : '../assets/js/notification.js');?>"></script>
<?php endif;?>
<script>
    $(function() {
        $.fn.datepicker.defaults.format = "yyyy-mm-dd";
        $('.datepicker').datepicker();
    });
</script>
<!-- Page level plugins -->
<script src="<?=(isset($inside_folder) ? '../../assets/vendor/chart.js/Chart.min.js' : '../assets/vendor/chart.js/Chart.min.js');?>"></script>

<!-- Page level custom scripts -->
<script src="<?=(isset($inside_folder) ? '../../assets/js/demo/chart-area-demo.js' : '../assets/js/demo/chart-area-demo.js');?>"></script>
<script src="<?=(isset($inside_folder) ? '../../assets/js/demo/chart-pie-demo.js' : '../assets/js/demo/chart-pie-demo.js');?>"></script>