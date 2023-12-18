<?php
/**
 *
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
$cakeVersion = __d('cake_dev', 'CakePHP %s', Configure::version())
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" /> 
	<meta name="description" content="mEngage is unique mobile universal channels app to ease way to Send or share messages to mass communities for desired actionable response">
		<meta name="keywords" content="mEngage, bulk sms, transaction sms, promotional sms">
<!--        <meta http-equiv="refresh" content="10">-->
	<?php //echo $this->Html->charset(); ?>
	<title><?php echo SITE_TITLE; ?></title>
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet">
	<?php
		echo $this->Html->meta('icon');
		//echo $this->Html->css(array('cake.generic'));
		echo $this->Html->css(array('bootstrap.min','font-awesome.min','main','responsive','jquery.fancybox','intlTelInput'));
		echo $this->Html->script(array('jquery-1.10.2.min','bootstrap','wow.min','jquery.scrollbar.min','jquery.fancybox','intlTelInput','jquery_002'));
		
	?>
<script src="https://cdn.ckeditor.com/4.4.3/standard/ckeditor.js" type="text/javascript"></script>
	
</head>
<body class="homepage">
    
<style>
body {background: #fff;}
.active{ background:#fff;}
.rigth-top-box{float:left;}
.panel-body {padding: 5px 10px;}
</style>
    
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-71756185-1', 'auto');
  ga('send', 'pageview');

</script>

<script type="text/javascript">
    adroll_adv_id = "XFYLOSQQBFBGFF6KZ7GTJP";
    adroll_pix_id = "GCGSFJAO6NDDTBHSY4RFW2";
    /* OPTIONAL: provide email to improve user identification */
    /* adroll_email = "username@example.com"; */
    (function () {
        var _onload = function(){
            if (document.readyState && !/loaded|complete/.test(document.readyState)){setTimeout(_onload, 10);return}
            if (!window.__adroll_loaded){__adroll_loaded=true;setTimeout(_onload, 50);return}
            var scr = document.createElement("script");
            var host = (("https:" == document.location.protocol) ? "https://s.adroll.com" : "http://a.adroll.com");
            scr.setAttribute('async', 'true');
            scr.type = "text/javascript";
            scr.src = host + "/j/roundtrip.js";
            ((document.getElementsByTagName('head') || [null])[0] ||
                document.getElementsByTagName('script')[0].parentNode).appendChild(scr);
        };
        if (window.addEventListener) {window.addEventListener('load', _onload, false);}
        else {window.attachEvent('onload', _onload)}
    }());
</script>

<div class="top-line"></div>





<?php echo $this->Html->script(array('jquery.form')); ?>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 header">
    <div class="container">
        <div class="row">


                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 Dashboard-logo pull-left">

                    <?php if(!empty(AuthComponent::user('id'))){?>
                        <a href="<?php echo SITE_URL.'users/dashboard';?>"><?php echo $this->Html->image('logo.png',array('alt'=>'mEngage')); ?></a>
                    <?php }else{?>
                        <?php echo $this->Html->image('logo.png',array('alt'=>'mEngage')); ?>
                    <?php }?>

                </div>

                <div class="right-text pull-right">

                </div>

        </div>
    </div>

</div>



<?php /* if($this->params['controller'] == 'homes' || $this->params['controller'] == 'blogs' || $this->params['controller'] == 'inquiry' || $this->params['controller'] == 'pages'): ?>
			<?php echo $this->element('home_header'); ?>
		<?php else:  ?>
			<?php echo $this->element('header'); ?>
		<?php endif; */ ?>
		
			<?php echo $this->Session->flash(); ?>
			<?php echo $this->fetch('content'); ?>
		
		<?php //echo $this->element('footer'); ?>
	
	<?php //echo $this->element('sql_dump'); ?>
</body>
<?php
if($this->Session->check("Message.popup_flash"))
{
    $flash_msg = $this->Session->read("Message.popup_flash.message");
    $flash_type = $this->Session->read("Message.popup_flash.params.class");$auto_close = $this->Session->read("Message.popup_flash.params.auto_close");
    echo $this->element("popup_flash", array("message" => $flash_msg, "type" => $flash_type, "auto_close" => $auto_close));
}
?>
<script>

function readURL(input) {
          if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                var image = new Image();
                image.src = e.target.result;
                
                image.onload = function () 
                {
                    var height = this.height;
                    var width = this.width;
                    $('#update_image').attr('src', e.target.result)
                    return true;
                };
        
             };

                reader.readAsDataURL(input.files[0]);
            }
        } 	
</script>
</html>
