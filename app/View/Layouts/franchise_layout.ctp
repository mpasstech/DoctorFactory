<html>
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

<?php echo $this->element('franchise_header'); ?>
<?php echo $this->fetch('content'); ?>
<?php //echo $this->element('app_admin_footer'); ?>
<script type="text/javascript">
    $(function(){

    });
</script>

</body>
</html>
