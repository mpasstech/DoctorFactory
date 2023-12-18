<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>:: Welcome to mEngage ::</title>
    <?php echo $this->element('css'); ?>
    <?php echo $this->element('js'); ?>
    <script> var baseurl = '<?php echo Router::url('/',true); ?>';
        var img_loader = '<?php echo Router::url('/img/ajaxloader.gif',true); ?>';

    </script>
</head>


<!--/head-->
<body class="homepage">
<?php echo $this->element('support_header'); ?>
<?php echo $this->fetch('content'); ?>
<?php echo $this->element('admin_footer'); ?>

</body>
</html>
