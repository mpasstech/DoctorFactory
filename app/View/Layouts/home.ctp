<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    

    <title>mPass</title>
       
       
    <link rel="canonical" href="http://www.mpasscheckin.com" />
    <link rel="publisher" href="https://plus.google.com/114128856459589308795"/>

   <!-- this code user for meta tag-->


    <?php echo $this->element('css'); ?>
    <?php echo $this->element('js'); ?>
    <script type="text/javascript">
        var baseurl = '<?php echo Router::url('/',true); ?>';
        var img_loader = '<?php echo Router::url('/img/ajaxloader.gif',true); ?>';
     
    </script>


   


</head>


<!--/head-->
<body class="homepage">

<?php echo $this->element('home_header'); ?>
<?php echo $this->fetch('content'); ?>
<?php echo $this->element('home_footer'); ?>
<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-90203409-1', 'auto');
    ga('send', 'pageview');

</script>
</body>
</html>
