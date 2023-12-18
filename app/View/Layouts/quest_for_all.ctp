<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>:: Welcome to mEngage ::</title>
    <?php echo $this->element('css'); ?>
    <?php echo $this->element('js'); ?>
    <script type="text/javascript"> var baseurl = '<?php echo Router::url('/',true); ?>';</script>
</head>


<!--/head-->
<body class="homepage">


<div class="header">
</div>

<?php echo $this->fetch('content'); ?>


<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 fotter">
    <div class="container">
        <div class="row">
        </div>
     </div>
</div>
<style>
    .fotter{ background: none !important; }
</style>

</body>
</html>
