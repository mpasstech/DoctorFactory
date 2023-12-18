<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
<head>
	<?php echo $this->Html->charset("utf-8"); 
        $this->Html->meta(array('name' => 'viewport', 'content' => 'width=device-width, initial-scale=1.0'));
        $this->Html->meta(array('name' => 'description', 'content' => ''));
        $this->Html->meta(array('name' => 'author', 'content' => ''));
        ?>
    
	<title>
		<?php echo SITE_TITLE."Login-Page"; ?>
	</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
	<?php
                echo $this->Html->css(array('admin_css/bootstrap.min','admin_css/font-awesome.min','admin_css/main','admin_css/responsive'));
                echo $this->Html->script(array('admin_js/jquery-1.js','admin_js/bootstrap.js','admin_js/wow.min.js'));
		echo $this->fetch('meta');
                
	?>
</head>
    
    

<body class="homepage">
<style>
body {overflow: hidden;}
.logo{width:100%;}
.form-control{background: transparent; width:100%;}
</style>
    
    <!--/header-->
<div class="top-line"></div>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 banner">
    <div class="container">
	<div class="row">
            <div class="middle-block">
            <div class="logo">
            <img src="<?php echo SITE_URL;?>img/admin_img/logo.png" alt="logo"/>
            </div>
            <div class="admin-acces">
            <img src="<?php echo SITE_URL;?>img/admin_img/logo1.png" class="loc" alt=""/>
            </div>
                
                <?php echo $this->Session->flash('success');?>
                <?php echo $this->Session->flash('error');?>
                <?php echo $this->Session->flash('info');?>
                <?php echo $this->fetch('content'); ?>

       </div>
     </div>
   </div>
</div>

<?php echo $this->Html->script(array('admin_js/jquery.js','admin_js/bootstrap.min.js','admin_js/main.js'));?>
   <!-- Script to Activate the Carousel -->
    
</body>
</html>
