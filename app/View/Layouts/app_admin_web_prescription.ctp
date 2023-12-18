<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>:: Welcome to mEngage ::</title>
    <?php echo $this->element('webcss'); ?>
    <?php echo $this->element('webjs'); ?>
    <script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
    <script> var baseurl = '<?php echo Router::url('/',true); ?>';
        var img_loader = '<?php echo Router::url('/img/ajaxloader.gif',true); ?>';

    </script>
    <style>
    .example-modal .modal {
      position: relative;
      top: auto;
      bottom: auto;
      right: auto;
      left: auto;
      display: block;
      z-index: 1;
    }

    .example-modal .modal {
      background: transparent !important;
    }
  </style>
     <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>


<!--/head-->
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper"> 
<?php echo $this->element('app_admin_web_prescription_header'); ?>
<?php echo $this->element('app_admin_web_prescription_body'); ?>
<?php echo $this->fetch('content'); ?>
<?php echo $this->element('app_admin_web_prescription_footer'); ?>
<script type="text/javascript">
    $(function(){

        var flashMessage = setInterval(function () {
            $('#successMessage, #errorMessage, #infoMessage, #warningMessage').slideUp(500);
            clearInterval(flashMessage);
        },2000)

       // $(".screen_title").appendTo(".header .container");
        $(".screen_title").fadeIn(850);



    });
</script>
</div>
</body>
</html>
