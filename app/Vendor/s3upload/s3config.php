<?php

	//Bucket Name
	$bucket_name = LETOUT_S3_BUCKET;
	
	//include the S3 class
	if (!class_exists('S3'))require_once('S3.php');
	
	
	//instantiate the s3 class
	$s3 = new S3(LETOUT_S3_ACCESSS_KEY, LETOUT_S3_SECRET_KEY);
	
	//this is used to create a bucket in amazon S3
	$s3->putBucket($bucket_name, S3::ACL_PUBLIC_READ);

?>