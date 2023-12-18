<?php
$constant_path = explode(DIRECTORY_SEPARATOR,__FILE__);
$tot =count($constant_path);
unset($constant_path[$tot-2]);
unset($constant_path[$tot-1]);
include_once implode("/",$constant_path)."/constant.php";
include_once implode("/",$constant_path)."/webservice/Custom.php";

$patientID=$_GET['patient_id'];
$patientType=$_GET['patient_type'];
$doctorID=$_GET['user_id'];
$thinappID=$_GET['thinapp_id'];
$folderID=$_GET['folder_id'];

$data = @Custom::get_medical_certificate_data($thinappID,$patientID,$patientType,$doctorID);
$doctorData = $data['doctorData'];
$userData = $data['userData'];
$today = date("d/m/Y");
$tomorrow = date("d/m/Y", strtotime("+ 1 day"));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
	
	<title>Medical Certificate</title>
    <script>
        var BASE_URL = "<?php echo SITE_PATH;?>";
    </script>
	<link rel='stylesheet' type='text/css' href='style.css' />
	<link rel='stylesheet' type='text/css' href='bootstrap/css/bootstrap.min.css' />
	<link rel='stylesheet' type='text/css' href='bootstrap-datepicker/css/bootstrap-datepicker.min.css' />
	<script type='text/javascript' src='jquery.js'></script>
	<script type='text/javascript' src='magic.js'></script>
	<script type='text/javascript' src='bootstrap/js/bootstrap.min.js'></script>
	<script type='text/javascript' src='bootstrap-datepicker/js/bootstrap-datepicker.min.js'></script>
    <script type='text/javascript' src="<?php echo SITE_PATH.'js/html2canvas.min.js'; ?>"> </script>

</head>

<body id="capture">

<div class="main_container">

<div class="top-heading">MEDICAL CERTIFICATE</div>

	<div class="content-container">
		<div class="doctor-info-row">
			<div class="doctor-info">
				<p class="doctor-name"><span id="DOCTOR_NAME_TOP"><?php echo $doctorData['username']; ?></span><input type="text" id="DOCTOR_NAME_TOP_INPUT"></p>
				<p><?php echo $doctorData['address']; ?></p>
			</div>
            <?php if($doctorData['image'] != '' || !empty($doctorData['image']) || $doctorData['image'] != null ){ ?>
                <div class="doctor-logo">
                    <img src="<?php echo $doctorData['image']; ?>" class="doctor-logo-img">
                </div>
            <?php } ?>
		</div>
		
		<div class="certificate-text">
			<p>Signature of the application....................................<p>
			<p>I, <span id="DOCTOR_NAME"><?php echo $doctorData['username']; ?></span><input type="text" id="DOCTOR_NAME_INPUT"> after careful personal examination of the case hereby certified that <span id="PATIENT_NAME"><?php echo $userData['username']; ?></span><input type="text" id="PATIENT_NAME_INPUT"> whose signature is given above, is suffering from <span id="DISEASE">******DISEASE******</span><input type="text" id="DISEASE_INPUT"> that I consider that a period of absence from duty for <span id="DUTY_FOR">******DUTY FOR******</span><input type="text" id="DUTY_FOR_INPUT"> with effect from <span id="START_DATE"><?php echo $today; ?></span><input type="text" id="START_DATE_INPUT" class="datepicker"> to <span id="END_DATE"><?php echo $tomorrow;?></span><input type="text" id="END_DATE_INPUT" class="datepicker"> is absolutely necessary for the restoration of his/her health.</p>
		</div>
		
		<div class="certificate-footer">
			<div class="doctor-signature-container">
				<br><br><br><br>
				<p class="doctor-signature-text">Authorized Signatory<p>
			</div>
			<div class="location-container">
			
				<p>Location : <span id="LOCATION">******LOCATION******</span><input type="text" id="LOCATION_INPUT"></p>
				<p>Date : <span id="DATE"><?php echo $today; ?></span><input type="text" id="DATE_INPUT" class="datepicker"></p>
			</div>
		</div>
		
	</div>
		
	<div class="border-bottom"><div>
		
</div>
        <input type="hidden" id="patientID" value="<?php echo $userData['id']; ?>">
        <input type="hidden" id="doctorID" value="<?php echo $doctorData['id']; ?>">
        <input type="hidden" id="thinappID" value="<?php echo $thinappID; ?>">
        <input type="hidden" id="patientType" value="<?php echo $patientType; ?>">
        <input type="hidden" id="folderID" value="<?php echo $folderID; ?>">
</body>
</html>