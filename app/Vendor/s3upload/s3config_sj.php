<?php

	//Bucket Name old
	/*$bucket_name = "sjchatimages";*/


	$bucket_name = "mbroacastmedia";

	//include the S3 class
	if (!class_exists('S3'))require_once('S3.php');
	
	
	//AWS access info old project
/*if (!defined('awsAccessKey')) define('awsAccessKey', 'AKIAJGE3O56JDQJR34PQ');
if (!defined('awsSecretKey')) define('awsSecretKey', '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');*/


if (!defined('awsAccessKey')) define('awsAccessKey', 'AKIAI4GH7NSGCGUI6QSA');
if (!defined('awsSecretKey')) define('awsSecretKey', '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');

//instantiate the class
$s3 = new S3(awsAccessKey, awsSecretKey);


	//this is used to create a bucket in amazon S3
	$s3->putBucket($bucket_name, S3::ACL_PUBLIC_READ);

?>