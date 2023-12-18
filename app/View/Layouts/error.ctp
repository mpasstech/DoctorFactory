<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>:: Welcome to mEngage ::</title>
	<link rel="canonical" href="http://www.mEngage.in" />
	<link rel="publisher" href="https://plus.google.com/114128856459589308795"/>

	<!-- this code user for meta tag-->
	<?php

	if(isset($this->params['url']['url'])){
		$url = $this->params['url']['url'];
		if($url=="features"){
			echo '<meta name="title" content="Android and iOS App Builder Features | mEngage">';
			echo '<meta name="description" content="mEngage, one of the best app builders has unique features like adding subscribers, creating channel, communicating via image/video, earning money and many more.">';
		}else if($url=="price"){
			echo '<meta name="title" content="mEngage App Builder Pricing Plans ">';
			echo '<meta name="description" content="mEngage pricing plans starting from 10000 INR & includes ready to-use themes, channel creation, publishing app on Play & App store, support & other great features.">';
		}else if($url=="usecase"){
			echo '<meta name="title" content="Use case- mEngage">';
			echo '<meta name="description" content="With wide range of mEngage features, you can create a custom app for iOS and Android, no matter what your niche: ecommerce, restaurant, small business and all the rest!">';
		}else if($url=="enquiry"){
			echo '<meta name="title" content="Custom Mobile App Development | mEngage">';
			echo '<meta name="description" content="mEngage expertise in end-to-end Custom Mobile Apps development by leveraging ready to-use templates and proven business processes. Enquire now!">';
		}else if($url=="support"){
			echo '<meta name="title" content="mEngage Support">';
			echo '<meta name="description" content="Read our FAQs, rise a ticket or request a call for any type of query, mEngage will be happy to help you.">';
		}else if($url=="earn"){
			echo '<meta name="title" content="Earn Money through mEngage">';
			echo '<meta name="description" content="Earn money through mEngage app builder by participating in the public channels. You can redeem your money in your paytm wallet. Participate Now!">';
		}else if($url=="blog"){
			echo '<meta name="title" content="mEngage Blog">';
			echo '<meta name="description" content="Welcome to the mEngage blog! Learn about our app builder with tutorials to create Interactive and Beautiful Apps.">';
		}else if($url=="contact"){
			echo '<meta name="title" content="Contact Us | mEngage">';
			echo '<meta name="description" content="Description: For any technical problems or other queries, please contact our customer support team. Call Now 0141-2294992.">';
		}

	}else{ ?>
		<meta name="title" content="Creative app builder for Consumer Engagement- mEngage">
		<meta name="description" content="Create visually build interactive mass consumer engagement apps using iOS and android app builder-mEngage without writing a single line of code. Create app now!">
	<?php } ?>

	<?php echo $this->element('css'); ?>
	<?php echo $this->element('js'); ?>
	<script> var baseurl = '<?php echo Router::url('/',true); ?>';</script>
</head>


<!--/head-->
<body class="homepage">

<?php echo $this->element('home_header'); ?>
<?php echo $this->fetch('content'); ?>
<?php echo $this->element('home_footer'); ?>

</body>
</html>
