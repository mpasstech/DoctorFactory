<?php
	$dataReceived = $_GET;
	$dataToShow = json_encode($dataReceived);
?>
<script>
	var jsonObj = JSON.stringify(<?php echo $dataToShow; ?>);
	alert(jsonObj);
</script>