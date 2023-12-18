<?php

require_once('s3config.php');

class S3upload
{
	public function upload($filename = null,$tmp_filename = null, $filetype = null){
		
		//upload file formats
		$valid_file_formats = array("jpg", "png", "gif", "bmp","jpeg","PNG","JPG","JPEG","GIF","BMP");
		/*
		$filename = $_FILES['uploadfile']['name'];
		$tmp_filename = $_FILES['uploadfile']['tmp_name'];
		$filetype = strtolower($_FILES['uploadfile']['type']);
		*/
		//get file extenstion to verify the format
		$extension = substr(strrchr($file_name,'.'),1);
		
		if(in_array($extension,$valid_file_formats))
		{
			//include config.php
			include_once "config.php";
			//set content type in headers inorder to display image in browser
			$header = array('Content-Type' => $filetype);
			//change filename
			$new_file_name = "w3_".time().".".$extension;
			if($s3->putObject(S3::inputFile($tmp_filename), $bucket_name , $new_file_name, S3::ACL_PUBLIC_READ, array(), $header) )
			{
				$img = "http://".$bucket_name.".s3.amazonaws.com/".$new_file_name;
				echo "1-".$img;
			} else {
				echo "2-Upload Failed";
			}
		} else {
			echo "3-Not a Valid Format";
		}
	}
}
?>
