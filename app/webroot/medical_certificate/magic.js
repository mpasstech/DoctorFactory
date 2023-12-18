$(document).ready(function(){
	$(document).on("click","span",function(e){
		var spanID = $(this).attr('id');
		var inputID = spanID+"_INPUT";
		var spanValue = $(this).text();
		$("#"+inputID).val(spanValue).show().focus();
		$(this).hide();
	});
	
	$(document).on("blur","input:not(.datepicker)",function(e){
		var inputID = $(this).attr('id');
		var spanID = inputID.replace("_INPUT", '');;
		var inputValue = $(this).val();
		$("#"+spanID).text(inputValue).show();
		$(this).hide();
	});
	
	window.addEventListener("hashchange", saveAll);

    $('.datepicker').datepicker({
        format: 'dd/mm/yyyy',
		autoclose:true
    });

    $('.datepicker').on('changeDate', function(e) {
        var inputID = $(this).attr('id');
        var spanID = inputID.replace("_INPUT", '');;
        var inputValue = $(this).val();
        $("#"+spanID).text(inputValue).show();
        $(this).hide();
    });
	
});

function saveAll(){
	var hashVal = location.hash;
	if(hashVal == '#save')
	{

        html2canvas(document.querySelector("#capture")).then(function(canvas) {

             	var imageBase64 = canvas.toDataURL('image/png');
        		console.log(imageBase64);

				var dateVal = $("#DATE").text();
				var locationVal = $("#LOCATION").text();
				var endDateVal = $("#END_DATE").text();
				var startDateVal = $("#START_DATE").text();
				var dutyForVal = $("#DUTY_FOR").text();
				var dieaseVal = $("#DISEASE").text();
				var patientNameVal = $("#PATIENT_NAME").text();
				var doctorNameVal = $("#DOCTOR_NAME").text();
				var doctorNameTopVal = $("#DOCTOR_NAME_TOP").text();
				var patientID = $("#patientID").val();
				var doctorID = $("#doctorID").val();
				var thinappID = $("#thinappID").val();
				var patientType = $("#patientType").val();
	            var folderID = $("#folderID").val();
				var dataTosend={
					dateVal:dateVal,
					locationVal:locationVal,
					endDateVal:endDateVal,
					startDateVal:startDateVal,
					dutyForVal:dutyForVal,
					diseaseVal:dieaseVal,
					patientNameVal:patientNameVal,
					doctorNameVal:doctorNameVal,
					doctorNameTopVal:doctorNameTopVal,
					patientID:patientID,
					doctorID:doctorID,
					thinappID:thinappID,
					patientType:patientType,
                    folderID:folderID,
                    imageBase64:imageBase64
				};

				$.ajax({
					url: BASE_URL+'/services/save_medical_certificate',
					data:dataTosend,
					type:'POST',
					success: function(result){
						var result = JSON.parse(result);
						if(result.status == 1)
						{
							alert(result.message);
						}
						else
						{
							alert(result.message);
						}
					}
				});


    	});
	}
}