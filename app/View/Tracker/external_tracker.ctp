<html>
<head>
    <title>Appointment Tracker</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <script src="https://www.gstatic.com/firebasejs/6.2.4/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/6.2.4/firebase-messaging.js"></script>
    <!--link rel="manifest" href="<?php echo Router::url('/manifest.json')?>"-->
    <?php echo $this->Html->css(array('bootstrap.min.css',),array("media"=>'all','fullBase' => true)); ?>
    <?php echo $this->Html->css(array('bootstrap-multiselect.css'),array("media"=>'all','fullBase' => true)); ?>


    <style>
        .patient-name > h2{
            font-size: 30px;
            font-weight: bold;
        }
        .glyphicon.glyphicon-info-sign {
            z-index: 99999999999999;
        }
        body{
            background-color:#FFFFFF;
            text-transform: uppercase;
        }
        .table-row {
            display: flex;
            margin: .2em 1em 0em 2em;
        }
        .token-container {
            border: 1em solid #ccbf66;
            border-radius: 6em;
            width: 10em;
            height: 6em;
            padding: .5em 0em 0em 0em;
            background-color: #a73e04;
            z-index: 1;
            color: #FFF;
            padding-left: unset;
            padding-right: unset;
        }
        /*.table-row:last-of-type {
            margin-bottom: 1em;
        } */
        .detail-container {
            height: 5em;
            margin-top: .5em;
            border: 0.5em solid #ccbf66;
            border-left-color: rgb(88, 238, 237);
            border-left-style: solid;
            border-left-width: 0.5em;
            border-left-color: rgb(88, 238, 237);
            border-left-style: solid;
            border-left-width: 0.5em;
            border-left-color: currentcolor;
            border-left-style: solid;
            border-left-width: 0.5em;
            border-top-right-radius: 5em;
            border-bottom-right-radius: 5em;
            border-left: none;
            padding: unset !important;
            margin-left: -2.7em;
        }
        .patient-name {
            padding: unset;
            background-color: #ccbf66;
            border-top-right-radius: 5em;
            width: 97%;
            height: 80%;
            margin-top: .4em;
            border-bottom-right-radius: 5em;
        }
        .doctor-name {
            padding: unset;
            background-color: #27e1dc;
            border-bottom-right-radius: 5em;
            height: 42%;
            width: 97%;
        }
        .token-container > h1 {
            font-weight: bold;
            font-size: 3em;
            margin-top: 0px;
        }
        h1, h2, h3 {
            margin-top: 6px;
        }
        .list-container {
            height: 20.5%;
        }
        .list-container-2 {

            background: #f9f7f8;

        }
        .list-container-1 {
            background: #FFFFFF;
        }
        .row.text-center {

            background-color: #055bca;
            color: #FFF;

        }
        .shadow {

            box-shadow: 0 0px 0px 0 rgba(0, 0, 0, 0.2), 0 0px 51px 0 rgb(0, 0, 0);

        }
        .image {

            width: 1.5em;

        }
        .all-list-container{overflow: hidden;}
        .header-title {

            border-bottom: .5em solid;

        }
        .green{
            background-color:#23af58 !important;
        }
        .glyphicon.glyphicon-info-sign {

            line-height: 0;
            float: right;
            margin-right: 21px;
            padding-top: 12px;
            margin-left: -21px;
            font-size: small;

        }
        .submit {
            text-align: center;
        }
        .tab{
            display:none;
        }
        /*.tab1{
            display:block;
        }*/
        .show{
            display:block !important;
        }

        #updated_content img {
            height: 100%;
        }
        #updated_content {
            width: 100%;
            height: 100%;
            text-align: center;
        }

        .rows.text-center > h2 {
            padding-top: 20px;
        }

    </style>
</head>
<body>


<div class="container-fluid">
    <div class="row header-title">

        <div class="shadow col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <div class="row text-center">
                <h1>
                    Current
                    <span class="glyphicon glyphicon-info-sign"></span>
                </h1>
                <span></span>
            </div>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <div class="row text-center">
                <h1>
                    Next

                </h1>

            </div>
        </div>
    </div>
    <div class="row" id="updated_content">
        <div class="empty_img">
            <img src="<?php echo Router::url('/images/get_well.gif')?>">
        </div>
    </div>
</div>

<div class="modal fade" id="myModalForm" role="dialog">
    <div class="modal-dialog modal-md">

        <div class="modal-content">

            <form method="post" id="doctorForm">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Doctor/Department</h4>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <label for="doctor_ids">Doctor <input type="radio" id="doctorSelect" name="type" value="doctor" ></label>
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <label for="department_ids">Department <input type="radio" id="departmentSelect" name="type" value="department" ></label>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <select name="doctor_id" class="select_doctor_multi multiselect-ui  form-control" multiple>
                                    <?php
                                    //$newDocID = json_decode($trackerDocData['tracker_new_doctor_id']);
                                    //$newDocID = is_array($newDocID)?$newDocID:array();
                                    ?>
                                    <?php foreach ($doctor_list as $key => $doctor){ ?>
                                        <option value="<?php echo $doctor['id']; ?>" <?php //echo (in_array($doctor['id'],$newDocID))?'selected=""SELECTED':''; ?> ><?php echo $doctor['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <select name="category_id" id="catID" class="form-control" >
                                    <option value="">Select</option>
                                    <?php foreach ($categoryData as $category){ ?>
                                        <option value="<?php echo $category['id']; ?>" doctor-ids="<?php echo $category['doctor_ids']; ?>" ><?php echo $category['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer payment">
                    <div class="submit">
                        <input class="btn btn-info btn-xl" value="Submit" type="submit">
                    </div>
                </div>

            </form>

        </div>
    </div>
</div>

<div class="image-overlay">
    <div class="image-overlay-image">
        <img src="<?php echo Router::url('/images/token_1.jpg')?>">
    </div>
    <div class="image-overlay-scroll-text">
        Call <?php echo $ivrMobile; ?> to book appointment.
    </div>
</div>

<style>
    .image-overlay {
        position: fixed;
        padding: 0;
        margin: 0;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background:#FFF;
        display: none;
        z-index: 9999999999999;
    }
    .image-overlay-image{
        width: 100%;
        height: 90%;
    }
    .image-overlay-image img {
        width: 100%;
        height: 100%;
    }
    .image-overlay-scroll-text {
        width: 100%;
        height: 20%;
        font-size: 4em;
        font-weight: bolder;
        color: #FFF;
        background:#008000;
        text-align: center;
    }
</style>

<!-- Scripts -->
<script src="<?php echo Router::url('/js/jquery.js')?>"></script>
<script src="<?php echo Router::url('/js/bootstrap-multiselect.js')?>"></script>

<script src="<?php echo Router::url('/js/bootstrap.min.js')?>"></script>

<script>
    var refreshSec = <?php echo $thinappData['tracker_multiple_refresh_sec']*1000; ?>;
    var show_patient_name = "<?php echo $thinappData['tracker_multiple_show_patient_name']; ?>";
    var show_sub_token_number = "<?php echo $thinappData['show_sub_token_name_on_tracker']; ?>";

    var doctorIDs = '<?php echo $doctorIDs; ?>';
    var showFirst = "YES";

    var savedCommaDocIDS = localStorage.getItem('commaDocIDS');
    if(savedCommaDocIDS !== null)
    {
        doctorIDs = savedCommaDocIDS.replace(/^"(.*)"$/, '$1');
    }

    $(document).ready(function(){


        var type = localStorage.getItem('type');

        if(type == 'doctor')
        {
            $("#doctorSelect").prop("checked", true);
            var docIDs = localStorage.getItem('doctor_ids');
            if(docIDs !== null)
            {
                docIDs = JSON.parse(docIDs);
                $('.multiselect-ui').val(docIDs);
            }
        }
        else if(type == 'department')
        {
            $("#departmentSelect").prop("checked", true);
            var catIDs = localStorage.getItem('category_ids');
            if(catIDs !== null)
            {
                catIDs = JSON.parse(catIDs);
                $('[name="category_id"]').val(catIDs[0]);
            }
        }


        $('.multiselect-ui').multiselect();



        $(document).on("click",".glyphicon",function(){
            $("#myModalForm").modal("show");
        });

        $(document).on("submit","#doctorForm",function(e){
            e.preventDefault();
            var data = $( this ).serializeArray();
            var typeSave = "";
            var commaDocIDS = "";
            var doctor_ids = [];
            var category_ids = [];
            data.forEach(function(data,index){
                if(data.name == 'type')
                {
                    localStorage.setItem('type', data.value);
                    typeSave = data.value;
                }
                else if(data.name == 'doctor_id')
                {
                    doctor_ids.push(data.value);
                }
                else if(data.name == 'category_id')
                {
                    category_ids.push(data.value);
                }
            });

            if(typeSave == "doctor")
            {
                commaDocIDS = doctor_ids.join();
            }
            else
            {

                commaDocIDS = $("#catID option:selected").attr("doctor-ids");
            }

            localStorage.setItem('commaDocIDS', JSON.stringify(commaDocIDS));
            localStorage.setItem('doctor_ids', JSON.stringify(doctor_ids));
            localStorage.setItem('category_ids', JSON.stringify(category_ids));
            location.reload();
        });


    });
</script>

<script>
    var baseurl ="<?php echo Router::url('/',true);?>";
    var firebaseConfig = {
        apiKey: "AIzaSyAjVkayDn99JcIgbFcSdbfl3B3jxHWCtSQ",
        authDomain: "mbroadcast-b50a6.firebaseapp.com",
        databaseURL: "https://mbroadcast-b50a6.firebaseio.com",
        projectId: "mbroadcast-b50a6",
        storageBucket: "mbroadcast-b50a6.appspot.com",
        messagingSenderId: "670774162115",
        appId: "1:670774162115:web:7cae6926392fce92"
    };

    firebase.initializeApp(firebaseConfig);
    const messaging = firebase.messaging();
    messaging.usePublicVapidKey("BDGGM5BTpjHhS6iHQzo_Gk2IwKJz4FJvqZhLpsBVZsbH4VzLxpHbGMcVKXBbS3jStIV3UUOuKdHAdbW5ssK2Rog");


    navigator.serviceWorker.register("<?php echo Router::url('/firebase-messaging-sw.js')?>")
        .then((registration) => {
        messaging.useServiceWorker(registration);

    Notification.requestPermission().then( function(permission){

        if (permission === 'granted') {
            messaging.getToken().then( function(currentToken){
                if (currentToken) {


                    //console.log(currentToken);



                    $.ajax({
                        url: baseurl+"tracker/save_tracker_token_data",
                        type:'POST',
                        data:{t:'<?php echo $thinappID; ?>',token:currentToken,type:"MULTIPLE",doctor_ids:doctorIDs},
                        success: function(result){
                            //console.log(result);
                        },error:function () {

                        }
                    });




                } else {
                    console.log('No Instance ID token available. Request permission to generate one.');
                }
            }).catch((err) => {
                console.log('An error occurred while retrieving token. ', err);
        });


        } else {
            console.log('Unable to get permission to notify.');
        }
    });

    });
    var payload;
    var intervalCleared = false;
    messaging.onMessage(function(payload){
        //('Message received. ', payload);

        if(intervalCleared == false)
        {
            clearInterval(setedInterval);
            intervalCleared = true;
            setInterval(function(){ getTrackerData(); },300000);
        }


        if(payload.data['gcm.notification.break_array'])
        {
            breakArray = JSON.parse(payload.data['gcm.notification.break_array']);
        }
        else
        {
            breakArray = {};
        }

        if(payload.data['gcm.notification.speech_data'])
        {
            speechData = JSON.parse(payload.data['gcm.notification.speech_data']);
        }

        if(payload.data['gcm.notification.status'])
        {
            setContent(JSON.parse(payload.data['gcm.notification.data']));
        }
        else
        {
            setContent({});
        }

    });


</script>

<script>
    var totalTab = 0;

    var audioElement = "";

    function toggleFullScreen() {
        elem = document.body;
        if ((document.fullScreenElement !== undefined && document.fullScreenElement === null) || (document.msFullscreenElement !== undefined && document.msFullscreenElement === null) || (document.mozFullScreen !== undefined && !document.mozFullScreen) || (document.webkitIsFullScreen !== undefined && !document.webkitIsFullScreen)) {
            if (elem.requestFullScreen) {
                elem.requestFullScreen();
            } else if (elem.mozRequestFullScreen) {
                elem.mozRequestFullScreen();
            } else if (elem.webkitRequestFullScreen) {
                elem.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
            } else if (elem.msRequestFullscreen) {
                elem.msRequestFullscreen();
            }
        } else {
            if (document.cancelFullScreen) {
                document.cancelFullScreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.webkitCancelFullScreen) {
                document.webkitCancelFullScreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            }
        }
    }

    $(document).dblclick(function(){
        toggleFullScreen();
    });



    var breakArray = {};
    var dataForSpeech = {};
    var dataAudioPreload = [];

    var oldDataForSpeech = [];

    function setContent(data){
        totalTab = 0;
        dataForSpeech = {};
        var htmlForDoc = "";
        if(Object.keys(data).length > 0)
        {
            //console.log(data.length);
            if(data.length > 1)
            {
                var indexCompare = 0;
                var height = "20.5%";

            }
            else
            {
                var indexCompare = 5;
                var height = "100%";

            }

            data.reverse();
            var docLength = data.length;

                data.forEach(function(item, index){
                    var docNum = index+1;


                    var room = (item.room_number != "")?item.room_number:"";
                    var roomToShow = (item.room_number != "")?"(Room-"+item.room_number+")":"";
                    var docName = item.doctor_name;
                    var docID = item.doctor_id;

                    if(docNum == 1 || docNum % 3 === 1)
                    {
                        totalTab++;
                        if(showFirst == "YES" && currentTab == 1){
                            showFirst = "NO";
                            htmlForDoc += '<div class="show tab tab'+totalTab+'">';
                        }
                        else if(totalTab == currentTab)
                        {
                            htmlForDoc += '<div class="show tab tab'+totalTab+'">';
                        }
                        else
                        {
                            htmlForDoc += '<div class="tab tab'+totalTab+'">';
                        }


                    }
                    if(!dataForSpeech[totalTab]){
                        dataForSpeech[totalTab] = [];
                    }

                    htmlForDoc += '<div class="row green">'+
                        '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">'+
                        '<div class="row text-center green">'+
                        '<h1>'+
                        docName+roomToShow+
                        '</h1>'+
                        '</div>'+
                        '</div>'+
                        '</div>'+
                        '<div class="row">';

                    if(typeof breakArray[docID] !== "undefined")
                    {
                        var breakChunk = breakArray[docID];
                        htmlForDoc += '<div class="overlay_box_div list-container" >';
                        if(breakChunk.flag != 'CUSTOM'){
                            htmlForDoc += '<h2 class="emergency">EMERGENCY</h2>';
                        }


                        htmlForDoc += '<h3 class="emer_patient_name">'+breakChunk.patient_name+'</h3>'+
                            '</div>';

                        htmlForDoc += '</div>'+
                            '</div>';
                        return;
                    }


                    htmlForDoc += '<div class="list-container  all-list-container list-container-1 col-xs-12 col-sm-12 col-md-12 col-lg-6">';


                    var patientName4 = item.current_patient;
                    patientName4 = patientName4.replace('B/O','');
                    patientName4 = patientName4.replace('Baba','');
                    patientName4 = patientName4.replace('DR.(MRS)','');
                    patientName4 = patientName4.replace('DR.(MS)','');
                    patientName4 = patientName4.replace('Dr.','');
                    patientName4 = patientName4.replace('MR.','');
                    patientName4 = patientName4.replace('MS.','');
                    patientName4 = patientName4.replace('Mrs.','');
                    patientName4 = patientName4.replace('Mr.','');
                    patientName4 = patientName4.replace('Mr','');
                    patientName4 = patientName4.replace('Miss.','');
                    patientName4 = patientName4.replace('Master','');
                    patientName4 = patientName4.replace('Baby','');
                    patientName4 = patientName4.replace('.','');
                    patientName4 = patientName4.trim();
                    item.current_patient = patientName4;






                    var patientName = item.current_patient;
                    patientName = patientName.replace('B/O','');
                    patientName = patientName.replace('Baba','');
                    patientName = patientName.replace('DR.(MRS)','');
                    patientName = patientName.replace('DR.(MS)','');
                    patientName = patientName.replace('Dr.','');
                    patientName = patientName.replace('MR.','');
                    patientName = patientName.replace('MS.','');
                    patientName = patientName.replace('Mrs.','');
                    patientName = patientName.replace('Mr.','');
                    patientName = patientName.replace('Mr','');
                    patientName = patientName.replace('Miss.','');
                    patientName = patientName.replace('Master','');
                    patientName = patientName.replace('Baby','');
                    patientName = patientName.replace('.','');
                    patientName = patientName.trim();
                    patientName = patientName.toLowerCase().split(' ')[0];
                    //console.log(patientName);

                    htmlForDoc += '</div>'+
                        '<div class="list-container '+patientName+item.current_token+' list-container-2 col-xs-12 col-sm-12 col-md-12 col-lg-6">';
                    var pName1 = (show_patient_name == "Y")?item.current_patient.substr(0, 12):"";

                    var queueNum1 = item.current_token;
                    var htmlClass1 ="";
                    if(item.patient_queue_type == 'REPORT_CHECKIN'){
                        queueNum1 = 'REPORT';
                        htmlClass1="report";
                    } else if(item.patient_queue_type == 'LATE_CHECKIN'){
                        queueNum1 = 'NOT';
                        htmlClass1 ="late";
                    }else if(item.patient_queue_type == 'EARLY_CHECKIN' || item.patient_queue_type == 'EMERGENCY_CHECKIN' || item.sub_token == 'YES'){
                        queueNum1 = 'EMERGENCY';
                        htmlClass1 ="emergency";
                        if(item.sub_token == 'YES' && show_sub_token_number=='YES'){
                            queueNum = item1.queue_number;
                            htmlClass="";
                        }
                    }
                    //item.current_token = queueNum1;


                    htmlForDoc += '<div class="rows table-row text-center">'+
                        '<div class="token-container col-xs-2 col-sm-2 col-md-2 col-lg-2">'+
                        '<h1 class="'+htmlClass1+'">'+queueNum1+'</h1>'+
                        '</div>'+
                        '<div class="detail-container col-xs-10 col-sm-10 col-md-10 col-lg-10">'+
                        '<div class="patient-name col-xs-12 col-sm-12 col-md-12 col-lg-12"><h2>'+pName1+'</h2></div>'+
                        //'<div class="doctor-name col-xs-12 col-sm-12 col-md-12 col-lg-12"><h3>'+docName+'</h3></div>'+
                        '</div>'+
                        '</div>';

                    htmlForDoc += '</div>'+
                        '</div>';
                    //console.log(item);
                    if(item.play_status == 'ACTIVE')
                    {
                        dataForSpeech[totalTab].push({token:item.current_token,name:patientName,room_number:room,token_announce:queueNum1});
                    }







                    //console.log(item.upcoming);
                    if((item.upcoming) !== undefined)
                    {
                        dataAudioPreload = [];
                        (item.upcoming).forEach(function(item1, index1){

                            var patientName2 = item1.patient_name;
                            patientName2 = patientName2.replace('B/O','');
                            patientName2 = patientName2.replace('Baba','');
                            patientName2 = patientName2.replace('DR.(MRS)','');
                            patientName2 = patientName2.replace('DR.(MS)','');
                            patientName2 = patientName2.replace('Dr.','');
                            patientName2 = patientName2.replace('MR.','');
                            patientName2 = patientName2.replace('MS.','');
                            patientName2 = patientName2.replace('Mrs.','');
                            patientName2 = patientName2.replace('Mr.','');
                            patientName2 = patientName2.replace('Mr','');
                            patientName2 = patientName2.replace('Miss.','');
                            patientName2 = patientName2.replace('Master','');
                            patientName2 = patientName2.replace('Baby','');
                            patientName2 = patientName2.replace('.','');
                            patientName2 = patientName2.trim();
                            item1.patient_name = patientName2;


                            if(index1 > indexCompare){
                                return false;
                            }
                            else
                            {

                                var pName = (show_patient_name == "Y")?item1.patient_name.substr(0, 12):"";


                                var queueNum = item1.queue_number;
                                var htmlClass="";
                                if(item1.patient_queue_type == 'REPORT_CHECKIN'){
                                    queueNum = 'REPORT';
                                    htmlClass="report";
                                } else if(item1.patient_queue_type == 'LATE_CHECKIN'){
                                    queueNum = 'NOT';
                                    htmlClass="late";
                                }else if(item1.patient_queue_type == 'EARLY_CHECKIN' || item1.patient_queue_type == 'EMERGENCY_CHECKIN' || item1.sub_token == 'YES'){
                                    queueNum = 'EMERGENCY';
                                    htmlClass="emergency";
                                    if(item1.sub_token == 'YES' && show_sub_token_number=='YES'){
                                        queueNum = item1.queue_number;
                                        htmlClass="";
                                    }
                                }
                                //item1.queue_number = queueNum;


                                htmlForDoc += '<div class="rows table-row text-center">'+
                                    '<div class="token-container col-xs-2 col-sm-2 col-md-2 col-lg-2">'+
                                    '<h1 class="'+htmlClass+'">'+queueNum+'</h1>'+
                                    '</div>'+
                                    '<div class="detail-container col-xs-10 col-sm-10 col-md-10 col-lg-10">'+
                                    '<div class="patient-name col-xs-12 col-sm-12 col-md-12 col-lg-12"><h2>'+pName+'</h2></div>'+
                                    //'<div class="doctor-name col-xs-12 col-sm-12 col-md-12 col-lg-12"><h3>'+docName+'</h3></div>'+
                                    '</div>'+
                                    '</div>';
                            }




                            var patientName1 = item1.patient_name;
                            patientName1 = patientName1.replace('B/O','');
                            patientName1 = patientName1.replace('Baba','');
                            patientName1 = patientName1.replace('DR.(MRS)','');
                            patientName1 = patientName1.replace('DR.(MS)','');
                            patientName1 = patientName1.replace('Dr.','');
                            patientName1 = patientName1.replace('MR.','');
                            patientName1 = patientName1.replace('MS.','');
                            patientName1 = patientName1.replace('Mrs.','');
                            patientName1 = patientName1.replace('Mr.','');
                            patientName1 = patientName1.replace('Mr','');
                            patientName1 = patientName1.replace('Miss.','');
                            patientName1 = patientName1.replace('Master','');
                            patientName1 = patientName1.replace('Baby','');
                            patientName1 = patientName1.replace('.','');
                            patientName1 = patientName1.trim();
                            patientName1 = patientName1.toLowerCase().split(' ')[0];
                            //console.log(patientName);
                            dataAudioPreload.push({token:item1.queue_number,name:patientName1,room_number:room,token_announce:queueNum});

                        });
                        <?php if($thinappData['tune_tracker_multiple'] == ""){ ?>
                        preloadAudio();
                        <?php } ?>

                    }
                    else
                    {
                        htmlForDoc += '<div class="rows text-center">'+
                            '<h2>Not Available</h2>'+
                            '</div>';
                    }


                    if(docNum == docLength || docNum % 3 === 0)
                    {
                        htmlForDoc += '</div>';
                    }


                });





            $("#updated_content").html(htmlForDoc);
            $(".list-container").css("height",height);


            /* if(indexCompare == 5)
            {
                 $(".token-container").css("height","8em");
                 $(".token-container > h1").css("font-size","4.5em");
                 $(".detail-container").css("height","7em");
                 $(".patient-name > h2").css("font-size","3.5em");
                 $(".patient-name").css("margin-top","0.6em");
            }
            else
            {

                $(".token-container").css("height","6em");
                $(".token-container > h1").css("font-size","3em");
                $(".detail-container").css("height","5em");
                $(".patient-name > h2").css("font-size","25px");
                $(".patient-name").css("margin-top",".4em");

            } */


        }
        else if(!($.isEmptyObject(breakArray)))
        {

                    if(Object.keys(breakArray).length > 1)
                    {
                        var indexCompare = 0;
                        var height = "20.5%";

                    }
                    else
                    {
                        var indexCompare = 5;
                        var height = "100%";

                    }


                    var docLength = Object.keys(breakArray).length;

            Object.keys(breakArray).forEach(function(itemIndex, index){
                        var docNum = index+1;

                        var item = breakArray[itemIndex];

                        var room = (item.room_number != "")?item.room_number:"";
                        var roomToShow = (item.room_number != "")?"(Room-"+item.room_number+")":"";
                        var docName = item.doctor_name;

                        if(docNum == 1 || docNum % 3 === 1)
                        {
                            totalTab++;
                            if(showFirst == "YES" && currentTab == 1){
                                showFirst = "NO";
                                htmlForDoc += '<div class="show tab tab'+totalTab+'">';
                            }
                            else if(totalTab == currentTab)
                            {
                                htmlForDoc += '<div class="show tab tab'+totalTab+'">';
                            }
                            else
                            {
                                htmlForDoc += '<div class="tab tab'+totalTab+'">';
                            }


                        }

                        htmlForDoc += '<div class="row green">'+
                            '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">'+
                            '<div class="row text-center green">'+
                            '<h1>'+
                            docName+roomToShow+
                            '</h1>'+
                            '</div>'+
                            '</div>'+
                            '</div>'+
                            '<div class="row">';



                        var breakChunk = item;
                        htmlForDoc += '<div class="overlay_box_div list-container" >';
                        if(breakChunk.flag != 'CUSTOM'){
                            htmlForDoc += '<h2 class="emergency">EMERGENCY</h2>';
                        }


                        htmlForDoc += '<h3 class="emer_patient_name">'+breakChunk.patient_name+'</h3>'+
                            '</div>';

                        htmlForDoc += '</div>'+
                            '</div>';


                    });

            $("#updated_content").html(htmlForDoc);
            $(".list-container").css("height",height);
        }
        else
        {
            var html = '<div class="empty_img"><img src="<?php echo Router::url('/images/get_well.gif')?>"></div>';
            $("#updated_content").html(html);
        }


    }




    var speechData = [];

    function getTrackerData(){
        var baseurl ="<?php echo Router::url('/',true);?>";
        $.ajax({
            url: baseurl+"tracker/load_tracker_opd_multiple",
            type:'POST',
            data:{t:'<?php echo $thinappID; ?>',doctor_id_string:doctorIDs},
            success: function(result){
                var data = JSON.parse(result);
                if(typeof data.break_array !== "undefined")
                {
                    breakArray = data.break_array;
                }
                else
                {
                    breakArray = {};
                }
                if(data.status == 1)
                {

                    setContent(data.data);
                }
                else
                {

                    setContent({});
                }
                speechData = data.speech_data;
                //console.log(data);

            },error:function () {

            }
        });
    }

    $(document).ready(function(){
        getTrackerData();
    });

    var setedInterval = setInterval(function(){
       // getTrackerData();
    },refreshSec);

    function play_voice_tune(){
        setInterval(function(){
            //console.log("dataForSpeech",dataForSpeech);
            //console.log(currentTab);

            if(!indexSpeech[currentTab])
            {
                indexSpeech[currentTab] = 0;
            }

            var dataForSpeech1 = dataForSpeech[currentTab];
            var tokenToAnounce = dataForSpeech1[indexSpeech[currentTab]];
            var tokenToCheck = "";
            //console.log((dataBilling.length - 1), indexSpeech);
            var indexSpeech1 = indexSpeech[currentTab];
            if(indexSpeech[currentTab] == 3 || ((dataForSpeech1.length - 1) == indexSpeech[currentTab]))
            {
                indexSpeech[currentTab] = 0;
            }
            else
            {
                indexSpeech[currentTab]++;
            }

            if(typeof tokenToAnounce !== "undefined")
            {
                tokenToAnounce = dataForSpeech1[indexSpeech1].token;// +' '+dataForSpeech[indexSpeech1].name;
                var tokenToAnounceMod = dataForSpeech1[indexSpeech1].token_announce;
                tokenToCheck = dataForSpeech1[indexSpeech1].token+dataForSpeech1[indexSpeech1].name;;

                if(oldDataForSpeech.includes(tokenToCheck) !== true) {
                    oldDataForSpeech.push(tokenToCheck);


                    // कृपया ध्यान दें अगला टोकन नंबर 7 अजय शर्मा है
                    //console.log(dataForSpeech1[indexSpeech1]);

                    //var subText = (dataForSpeech1[indexSpeech1].room_number != "")?" room number "+dataForSpeech1[indexSpeech1].room_number+" में":" आगे";

                    //var textToAnounce = encodeURIComponent("कृपया ध्यानदें, अगला टोकन नंबर "+tokenToAnounce+","+dataForSpeech1[indexSpeech1].name+" है");
                   // var textToAnounce = encodeURIComponent("टोकन नंबर "+tokenToAnounce+","+dataForSpeech1[indexSpeech1].name);
                    //console.log(textToAnounce);

                    var textToAnounce = getVoiceString(tokenToAnounceMod, dataForSpeech1[indexSpeech1].name, dataForSpeech1[indexSpeech1].room_number);

                    if(typeof audioElement.muted !== "undefined")
                    {
                        audioElement.muted = true;
                        audioElement.pause();
                        audioElement.currentTime = 0;
                    }
                    var pNameTmp = dataForSpeech1[indexSpeech1].name.split(" ");
                    blink('.'+pNameTmp+tokenToAnounce);
                    audioElement = document.createElement('audio');
                    /*audioElement.setAttribute('src', 'http://ivrapi.indiantts.co.in/tts?type=indiantts&text='+textToAnounce+'&api_key=2d108780-0b86-11e6-b056-07d516fb06e1&user_id=80&action=play&audio.tts'); */
                    audioElement.setAttribute('src', textToAnounce);
                    audioElement.play({
                        onplay: function() {
                            //console.log('Yay, playing');

                        },
                        onerror: function(errorCode, description) {
                            //console.log(errorCode,description);
                        }
                    });


                }
            }

        },9000);
    }


    function preloadAudio(){
        dataAudioPreload.forEach(function(item, index){

            var textToAnounce = getVoiceString(item.token_announce, item.name, item.room_number);
            /*var aud = new Audio('http://ivrapi.indiantts.co.in/tts?type=indiantts&text='+textToAnounce+'&api_key=2d108780-0b86-11e6-b056-07d516fb06e1&user_id=80&action=play&audio.tts');*/
            var aud = new Audio(textToAnounce);
            aud.preload = 'auto';
        });


    }


    function play_tune(){
        setInterval(function(){
            //console.log("dataForSpeech",dataForSpeech);
            //console.log(currentTab);

            if(!indexSpeech[currentTab])
            {
                indexSpeech[currentTab] = 0;
            }

            var dataForSpeech1 = dataForSpeech[currentTab];
            var tokenToAnounce = dataForSpeech1[indexSpeech[currentTab]];
            var tokenToCheck = "";
            //console.log((dataBilling.length - 1), indexSpeech);
            var indexSpeech1 = indexSpeech[currentTab];
            if(indexSpeech[currentTab] == 3 || ((dataForSpeech1.length - 1) == indexSpeech[currentTab]))
            {
                indexSpeech[currentTab] = 0;
            }
            else
            {
                indexSpeech[currentTab]++;
            }

            if(typeof tokenToAnounce !== "undefined")
            {
                tokenToAnounce = dataForSpeech1[indexSpeech1].token;// +' '+dataForSpeech[indexSpeech1].name;
                tokenToCheck = dataForSpeech1[indexSpeech1].token+dataForSpeech1[indexSpeech1].name;;

                if(oldDataForSpeech.includes(tokenToCheck) !== true) {
                    oldDataForSpeech.push(tokenToCheck);


                    var audioUrl = "<?php echo Router::url('/tracker_tunes/'.$thinappData['tune_tracker_multiple']); ?>";

                    if(typeof audioElement.muted !== "undefined")
                    {
                        audioElement.muted = true;
                        audioElement.pause();
                        audioElement.currentTime = 0;
                    }
                    var pNameTmp = dataForSpeech1[indexSpeech1].name.split(" ");
                    blink('.'+pNameTmp+tokenToAnounce);
                    audioElement = document.createElement('audio');
                    audioElement.setAttribute('src', audioUrl);
                    audioElement.play({
                        onplay: function() {
                            //console.log('Yay, playing');
                        },
                        onerror: function(errorCode, description) {
                            //console.log(errorCode,description);
                        }
                    });


                }
            }

        },4000);
    }

    var indexSpeech = [];
    $(document).ready(function(){


        <?php if($thinappData['tune_tracker_multiple'] != ""){ ?>
        play_tune();
        <?php } else{ ?>
        play_voice_tune();
        <?php } ?>
    });

    $(document).ready(function(){
        var indexSpeech = 0;
        setInterval(function(){
            var messageToAnounce = speechData[indexSpeech];
            speechData[indexSpeech] = "";
            //console.log((dataBilling.length - 1), indexSpeech);
            if(speechData.length - 1 == indexSpeech)
            {
                indexSpeech = 0;
            }
            else
            {
                indexSpeech++;
            }


            if(typeof messageToAnounce !== "undefined" && messageToAnounce !== "")
            {
                //console.log(messageToAnounce);
                var textToAnounce = messageToAnounce;
                var audioElement1 = document.createElement('audio');
                var audioUrl = 'http://ivrapi.indiantts.co.in/tts?type=indiantts&text='+textToAnounce+'&api_key=2d108780-0b86-11e6-b056-07d516fb06e1&user_id=80&action=play&audio.tts';
                if(textToAnounce == "Bell")
                {
                    audioUrl = "https://mengage.s3.amazonaws.com/26_06_2019_11_46_50_0.06565928975403257.mp3";
                }
                audioElement1.setAttribute('src', audioUrl);
                if(typeof audioElement.muted !== "undefined")
                {
                    audioElement.muted = true;
                }
                audioElement1.play({
                    onplay: function() {
                        //console.log('Yay, playing');
                        if(typeof audioElement.muted !== "undefined")
                        {
                            audioElement.muted = true;
                        }
                    },
                    onerror: function(errorCode, description) {
                        //console.log(errorCode,description);
                        if(typeof audioElement.muted !== "undefined")
                        {
                            audioElement.muted = false;
                        }
                    },
                    onended: function() {
                        if(typeof audioElement.muted !== "undefined")
                        {
                            audioElement.muted = false;
                        }
                    }
                });


            }

        },4000);
    });

    var currentTab = 1;

    function changeTab(){
        currentTab++;
        //console.log("currentTab",currentTab);
        //console.log("totalTab",totalTab);
        if(currentTab <= totalTab)
        {
            //console.log("yes");
            //$(".tab"+(currentTab-1)).hide();
            //$(".tab"+currentTab).show();
            var cls = ".tab"+currentTab;
            $(".show:not("+cls+")").removeClass("show");
            //console.log(cls);
            $(cls).addClass("show");

        }
        else
        {
            //console.log("no");
            currentTab = 1;
            //$(".tab"+totalTab).hide();
            //$(".tab"+currentTab).show();
            var cls = ".tab"+currentTab;
            $(".show:not("+cls+")").removeClass("show");
            //console.log(cls);
            $(cls).addClass("show");
        }

    }

    setInterval(function(){ changeTab(); },15000);

    function blink(selector){
        $(selector).fadeOut(400).fadeIn(400);
        var blinkInterval = setInterval(() => {
            $(selector).fadeOut(400).fadeIn(400);
            clearInterval(blinkInterval);
        },1200);

    }

    /*function getVoiceString(tokenNumber, patientName, roomNumber){
        <?php if($thinappData['tracker_voice'] != ''){ ?>
        var text = "<?php echo $thinappData['tracker_voice']; ?>";
        <?php }else{ ?>
        var text = "token number [TOKEN], [NAME], room number [ROOM]";
        if(roomNumber == '')
        {
            text = text.replace(', room number [ROOM]','');
        }
        <?php } ?>
        tokenNumber = tokenNumber.toLowerCase();
        text = text.replace('[TOKEN]',tokenNumber);
        text = text.replace('[NAME]',patientName);
        text = text.replace('[ROOM]',roomNumber);

        return encodeURIComponent(text);
    }*/

    function getVoiceString(tokenNumber, patientName, roomNumber){
        var audioUrl = baseurl+"voice_anouncement/"+tokenNumber+".wav";
        return audioUrl;
    }

    setTimeout(function(){ location.reload();  },(5*60*1000));

    function displayImageOverly(){
        $(".image-overlay").show();
        setTimeout(function(){ $(".image-overlay").hide(); },(15*1000));
    }

    <?php if($ivrMobile != ''){ ?>
    setInterval(function(){ displayImageOverly();  },(60*1000));
    <?php }?>

</script>

<div class="footertext">
    <div class="powered">
        Powered by <img class="logo-mengage" src="https://mengage.in/doctor/images/logo.png">
    </div>
</div>


<style>
    .footertext img {
        height: 90%;
    }
    .footertext {
        width: 100%;
        height: 60px;
        position: fixed;
        top: 0;
        left: 0;
        text-align: center;
        font-size: 20px;
        font-weight: bold;
    }
    .powered {
        width: 250px;
        margin: 0 auto;
        left: 0;
        right: 0;
        position: relative;
        background-color: #FFF;
        border-radius: 8px;
        top: 2px;
        overflow: hidden;
    }
    .report {
        font-size: 1.6em !important;
        margin-top: 10px !important;
    }
    .emergency {
        font-size: 1.2em !important;
        margin-top: 12px !important;
    }
    .late {
        font-size: 2em !important;
        margin-top: 5px !important;
    }
</style>

<style>

    .overlay_box_div {
        top: 0;
        left: 0;
        height: 100%;
        background: #fff;
        width: 100%;
    }
    .overlay_box_div .emergency {
        color: red;
        margin-top: 3%;
        font-size: 14rem;
        width: 100%;
        text-align: center;
        opacity: 1;
    }
    .overlay_box_div .emer_patient_name {
        text-align: center;
        width: 100%;
        font-size: 6rem;
    }

    .break_doctor{
        text-align: center;
        font-size: 2.5rem;
        background: blue;
        color: #fff;
        margin: 0 auto;
        padding: 5px 13px;
        border-radius: 0px 0px 30px 30px;
        width: 40%;
        font-weight: 600;
    }
    .overlay_box_div .emer_patient_name {

        text-align: center;
        width: 100%;
        font-size: 5rem;
        margin-top: .2em;
        font-weight: bolder;

    }
</style>

</body>
</html>