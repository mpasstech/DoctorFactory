<html>
<head>
    <title>Appointment Tracker</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <script src="https://www.gstatic.com/firebasejs/6.2.4/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/6.2.4/firebase-messaging.js"></script>
    <!--link rel="manifest" href="<?php echo Router::url('/manifest.json')?>"-->

	<?php
        $thin_app_id = base64_decode($this->request->params['pass'][0]);
        $app_data = Custom::getThinAppData($thin_app_id);
      ?>
    <script>
        var show_sub_token_number = "<?php echo $app_data['show_sub_token_name_on_tracker']; ?>";
    </script>
    
    <?php foreach($mediaData AS $keyMedia1 => $media1){ ?>

            <?php if($media1['MediaMessage']['media_type'] == 'VIDEO'){ ?>

            <link rel="preload" as="video" href="<?php echo $media1['MediaMessage']['media_url']; ?>">
            <?php } ?>

    <?php } ?>


    <?php echo $this->Html->css(array('bootstrap.min.css',),array("media"=>'all','fullBase' => true)); ?>
    <style>
        body{
            background-color:#FFFFFF;
            text-transform: uppercase;
        }
        .table-row {

            display: flex;
            margin: 0.5em 0em 0em 0em;

        }
        .token-container {
            border: 1em solid #9feeeb;
            border-radius: 6em;
            width: 10em;
            height: 7em;
            padding: .5em 0em 0em 0em;
            background-color: #099b9b;
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
            margin-top: 1em;
            border: 0.5em solid #58eeed;
            border-left-color: rgb(88, 238, 237);
            border-left-style: solid;
            border-left-width: 0.5em;
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
            margin-left: -1.7em;

        }
        .patient-name {
            padding: unset;
            background-color: #58eeed;
            border-top-right-radius: 5em;
            width: 97%;
            height: 80%;
            margin-top: 1%;
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
        }
        h1, h2, h3 {
            margin-top: 5px;
        }
        .list-container {
            /*height: 42%;*/
            padding-bottom: 6px;
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

            border-bottom: .1em solid;

        }
        .green{
            background-color:#23af58 !important;
        }
        .token-main-container{
            height:30%;
        }
        .media-main-container{
            height: 63%;
        }
        .media_image {
            height: 100%;
            width: 100%;
        }
        .media_video {
            height: 100%;
            width: 100%;
        }
        .row {
            margin-right: unset;
            margin-left: unset;
        }
        video {
            object-fit: fill;
        }

        .header-title {
            border-bottom: .5em solid;
            background-color: #055bca;
        }
        .container-fluid{
            max-height:100%;
        }

        .tab{
            display:none;
            background-color: #FFF;
        }
        /*.tab1{
            display:block;
        }*/
        .show{
            display:block !important;
        }
        	
            .video_lbl{
            position: absolute;
            background: red;
            color: #fff;
            padding: 0PX 31px 0px 34px;
            transform: rotate(0deg);
            top: -14px;
            left: -4px;
            font-weight: 600;
            border-radius: 50px 50px 0px 0px;
            height: 22px;
        }
    </style>
</head>
<body>


<div class="container-fluid">
    <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8" style="padding: 0px; border-right: 1px solid;" >
        <div class="row media-main-container media-attach" style="height: 100%;">
            <?php foreach($mediaData AS $keyMedia => $media){ ?>
                <div class="row media-container media<?php echo $keyMedia; ?>" data-url="<?php echo $media['MediaMessage']['media_url']; ?>" id="media<?php echo $keyMedia; ?>" duration="<?php echo (isset($media['MediaMessage']['duration'])?$media['MediaMessage']['duration']:0)*60*1000; ?>" style="display:none;"  data-type="<?php echo $media['MediaMessage']['media_type']; ?>">
                    <?php if($media['MediaMessage']['media_type'] == 'IMAGE'){ ?>
                        <img src="<?php echo $media['MediaMessage']['media_url']; ?>" class="media_image">
                    <?php }else if($media['MediaMessage']['media_type'] == 'VIDEO'){ ?>
                        <video class="media_video" muted="muted" id="media1<?php echo $keyMedia; ?>" preload="metadata" controls="true">
                            <source src="<?php echo $media['MediaMessage']['media_url']; ?>" type="video/mp4">
                            <source src="<?php echo $media['MediaMessage']['media_url']; ?>" type="video/ogg">
                            <source src="<?php echo $media['MediaMessage']['media_url']; ?>" type="video/webm">
                            Your browser does not support the video tag.
                        </video>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" style="padding: 0px;">
        <div class="token-main-container">
            <div class="row header-title">
                <div class="footertext">
                    <div class="powered">
                        Powered by <img class="logo-mengage" src="https://mpasscheckin.com/doctor/img/old_tracker_logo.png">
                    </div>
                </div>
            </div>
            <div class="row" id="updated_content">

            </div>
        </div>
    </div>

</div>


<!-- Scripts -->
<script src="<?php echo Router::url('/js/jquery.js')?>"></script>
<script src="<?php echo Router::url('/js/bootstrap.min.js')?>"></script>
<script src="https://www.youtube.com/player_api"></script>

<script>




    var refreshSec = <?php echo $thinappData['tracker_media_refresh_sec']*1000; ?>;
    var show_patient_name = "<?php echo $thinappData['tracker_media_show_patient_name']; ?>";
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
                        data:{t:'<?php echo $thinappID; ?>',token:currentToken,type:"MEDIA"},
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
        //console.log('Message received. ', payload);

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
    var dataAudioPreload = [];
    var dataForSpeech = {};
    var oldDataForSpeech = [];
    var videoAttr = "";
    var isPlaying = false;
    var audioElement = "";
    var totalTab = 0;
    var currentTab = 1;
    var showFirst = "YES";
    var breakArray = {};

    function setContent(data){
        totalTab = 0;
        dataForSpeech = {};
        var htmlForDoc = "";
        if(Object.keys(data).length > 0)
        {

            var indexCompare = 0;
            data.reverse();
            var docLength = data.length;

            data.forEach(function(item, index){
                var docNum = index+1;


                var room = (item.room_number != "")?item.room_number:"";
                var roomToShow = (item.room_number != "")?"(Room-"+item.room_number+")":"";
                var docName = item.doctor_name;
                var docID = item.doctor_id;

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

                if(!dataForSpeech[totalTab]){
                    dataForSpeech[totalTab] = [];
                }

                htmlForDoc += '<div class="row green">'+
                    '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">'+
                    '<div class="row text-center green">'+
                    '<h1>'+
                    docName+

                    '</h1>'+
                    '<span class="room_number_span">'+roomToShow+'</span>'+
                    '</div>'+
                    '</div>'+
                    '</div>'+
                    '<div class="row">';

                    if(typeof breakArray[docID] !== "undefined")
                    {
                        var breakChunk = breakArray[docID];
                        htmlForDoc += '<div class="overlay_box_div" >';
                        if(breakChunk.flag != 'CUSTOM'){
                            htmlForDoc += '<h2 class="emergency">EMERGENCY</h2>';
                        }


                        htmlForDoc += '<h3 class="emer_patient_name">'+breakChunk.patient_name+'</h3>'+
                        '</div>';

                        htmlForDoc += '</div>'+
                            '</div>';
                        return;
                    }



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

                htmlForDoc += '<div class="current_token_div list-container '+patientName+item.current_token+' list-container-2 col-xs-12 col-sm-12 col-md-12 col-lg-12">';
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
                        queueNum = item.queue_number;
                        htmlClass="";
                    }
                    
                }
                //item.current_token = queueNum1;

				var video_icon ='';
                if(item.consulting_type =='VIDEO'){
                    video_icon = '<div class="video_lbl">Video</div>';
                }

                htmlForDoc += '<div class="rows table-row text-center">'+
                    '<div class="token-container col-xs-2 col-sm-2 col-md-2 col-lg-2">'+video_icon+
                    '<h1 class="'+htmlClass1+'">'+queueNum1+'</h1>'+
                    '</div>'+
                    '<div class="detail-container col-xs-10 col-sm-10 col-md-10 col-lg-10">'+
                    '<div class="patient-name col-xs-12 col-sm-12 col-md-12 col-lg-12"><h2>'+pName1+'</h2></div>'+
                    //'<div class="doctor-name col-xs-12 col-sm-12 col-md-12 col-lg-12"><h3>'+docName+'</h3></div>'+
                    '</div>'+
                    '</div>';



                htmlForDoc += '</div>'+
                    '</div>';


                    htmlForDoc += '<div class="upcoming_lbl"><span>Next</span></div><div class="list-container  all-list-container list-container-1 col-xs-12 col-sm-12 col-md-12 col-lg-12">';


                if((item.upcoming) !== undefined)
                {
                    dataAudioPreload = [];
                    (item.upcoming).forEach(function(item1, index1) {
                        if(index1 < 5){

                        var patientName2 = item1.patient_name;
                        patientName2 = patientName2.replace('B/O', '');
                        patientName2 = patientName2.replace('Baba', '');
                        patientName2 = patientName2.replace('DR.(MRS)', '');
                        patientName2 = patientName2.replace('DR.(MS)', '');
                        patientName2 = patientName2.replace('Dr.', '');
                        patientName2 = patientName2.replace('MR.', '');
                        patientName2 = patientName2.replace('MS.', '');
                        patientName2 = patientName2.replace('Mrs.', '');
                        patientName2 = patientName2.replace('Mr.', '');
                        patientName2 = patientName2.replace('Mr', '');
                        patientName2 = patientName2.replace('Miss.', '');
                        patientName2 = patientName2.replace('Master', '');
                        patientName2 = patientName2.replace('Baby', '');
                        patientName2 = patientName2.replace('.', '');
                        patientName2 = patientName2.trim();
                        item1.patient_name = patientName2;

                        var pName = (show_patient_name == "Y") ? item1.patient_name.substr(0, 12) : "";
                        var queueNum = item1.queue_number;
                        var htmlClass = "";
                        if (item1.patient_queue_type == 'REPORT_CHECKIN') {
                            queueNum = 'REPORT';
                            htmlClass = "report";
                        } else if (item1.patient_queue_type == 'LATE_CHECKIN') {
                            queueNum = 'NOT';
                            htmlClass = "late";
                        } else if (item1.patient_queue_type == 'EARLY_CHECKIN' || item1.patient_queue_type == 'EMERGENCY_CHECKIN' || item1.sub_token == 'YES') {
                            queueNum = 'EMERGENCY';
                            htmlClass = "emergency";
                            if(item1.sub_token == 'YES' && show_sub_token_number=='YES'){
                                queueNum = item1.queue_number;
                                htmlClass="";
                            }
                        }

						 var video_icon ='';
                            if(item1.consulting_type =='VIDEO'){
                                video_icon = '<div class="video_lbl">Video</div>';
                            }
                            
                        htmlForDoc += '<div class="rows table-row text-center">' +
                            '<div class="token-container col-xs-2 col-sm-2 col-md-2 col-lg-2">' +video_icon+
                            '<h1 class="' + htmlClass + '">' + queueNum + '</h1>' +
                            '</div>' +
                            '<div class="detail-container col-xs-10 col-sm-10 col-md-10 col-lg-10">' +
                            '<div class="patient-name col-xs-12 col-sm-12 col-md-12 col-lg-12"><h2>' + pName + '</h2></div>' +
                            //'<div class="doctor-name col-xs-12 col-sm-12 col-md-12 col-lg-12"><h3>'+docName+'</h3></div>'+
                            '</div>' +
                            '</div>';


                        var patientName1 = item1.patient_name;
                        patientName1 = patientName1.replace('B/O', '');
                        patientName1 = patientName1.replace('Baba', '');
                        patientName1 = patientName1.replace('DR.(MRS)', '');
                        patientName1 = patientName1.replace('DR.(MS)', '');
                        patientName1 = patientName1.replace('Dr.', '');
                        patientName1 = patientName1.replace('MR.', '');
                        patientName1 = patientName1.replace('MS.', '');
                        patientName1 = patientName1.replace('Mrs.', '');
                        patientName1 = patientName1.replace('Mr.', '');
                        patientName1 = patientName1.replace('Mr', '');
                        patientName1 = patientName1.replace('Miss.', '');
                        patientName1 = patientName1.replace('Master', '');
                        patientName1 = patientName1.replace('Baby', '');
                        patientName1 = patientName1.replace('.', '');
                        patientName1 = patientName1.trim();
                        patientName1 = patientName1.toLowerCase().split(' ')[0];
                        //console.log(patientName);
                        dataAudioPreload.push({
                            token: item1.queue_number,
                            name: patientName1,
                            room_number: room,
                            token_announce: queueNum
                        });
                    }

                    });
                    <?php if($thinappData['tune_tracker_media'] == ""){ ?>
                    preloadAudio();
                    <?php } ?>

                }
                else
                {
                    htmlForDoc += '<div class="rows text-center">'+
                        '<h2>Not Available</h2>'+
                        '</div>';
                }





                //console.log(item);
                if(item.play_status == 'ACTIVE')
                {
                    dataForSpeech[totalTab].push({token:item.current_token,name:patientName,room_number:room,token_announce:queueNum1});
                }


                    htmlForDoc += '</div></div>';


            });



        }

        $("#updated_content").html(htmlForDoc);
    }

    var speechData = [];

    function getTrackerData(){

        $.ajax({
            url: baseurl+"tracker/load_tracker_opd_media_two_column",
            type:'POST',
            data:{t:'<?php echo $thinappID; ?>',doctor_id_string:'<?php echo $doctorIDs; ?>'},
            success: function(result){
                var data = JSON.parse(result);
                if(data.status == 1)
                {
                    breakArray = data.break_array;
                    setContent(data.data);

                }
                else
                {
                    breakArray = {};
                    setContent({});
                }
                speechData = data.speech_data;
                //console.log(data);

            },error:function () {

            }
        });
    }



    var setedInterval = setInterval(function(){
            getTrackerData();
        },refreshSec);

    function play_voice_tune(){
        setInterval(function(){
            if(!indexSpeech[currentTab])
            {
                indexSpeech[currentTab] = 0;

            }

            var dataForSpeech1 = dataForSpeech[currentTab];

            if(typeof dataForSpeech1 === "undefined"){return;}
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
            //console.log(dataForSpeech,currentTab);
            if(typeof tokenToAnounce !== "undefined")
            {
                //console.log(tokenToAnounce);
                tokenToAnounce = dataForSpeech1[indexSpeech1].token;// +' '+dataForSpeech[indexSpeech1].name;
                var tokenToAnounceMod = dataForSpeech1[indexSpeech1].token_announce;
                tokenToCheck = dataForSpeech1[indexSpeech1].token+dataForSpeech1[indexSpeech1].name;;

                if(oldDataForSpeech.includes(tokenToCheck) !== true) {
                    oldDataForSpeech.push(tokenToCheck);

                    var textToAnounce = getVoiceString(tokenToAnounceMod, dataForSpeech1[indexSpeech1].name, dataForSpeech1[indexSpeech1].room_number);

                    if(typeof audioElement.muted !== "undefined")
                    {
                        audioElement.muted = true;
                        audioElement.pause();
                        audioElement.currentTime = 0;
                    }
                    var pNameTmp = dataForSpeech1[indexSpeech1].name.split(" ");
                    //console.log('here1');
                    blink('.'+pNameTmp+tokenToAnounce);
                    audioElement = document.createElement('audio');
                    /*audioElement.setAttribute('src', 'http://ivrapi.indiantts.co.in/tts?type=indiantts&text='+textToAnounce+'&api_key=2d108780-0b86-11e6-b056-07d516fb06e1&user_id=80&action=play&audio.tts');*/
                    audioElement.setAttribute('src', textToAnounce);
                    audioElement.play({
                        onplay: function() {
                            //console.log('Yay, playing');
                            if(isPlaying == true)
                            {
                                audioElement.pause();
                                audioElement.currentTime = 0;
                            }
                        },
                        onerror: function(errorCode, description) {
                            console.log(errorCode,description);
                            if(typeof videoAttr.muted !== "undefined")
                            {
                                videoAttr.muted = false;
                            }
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


                    var audioUrl = "<?php echo Router::url('/tracker_tunes/'.$thinappData['tune_tracker_media']); ?>";

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
                            if(isPlaying == true)
                            {
                                audioElement.pause();
                                audioElement.currentTime = 0;
                            }
                        },
                        onerror: function(errorCode, description) {
                           // console.log(errorCode,description);
                            if(typeof videoAttr.muted !== "undefined")
                            {
                                videoAttr.muted = false;
                            }
                        }
                    });


                }
            }

        },4000);
    }




    var indexSpeech = [];
    $(document).ready(function(){

        $('.html5-video-player').height($(document).height());


        getTrackerData();

        <?php if($thinappData['tune_tracker_media'] != ""){ ?>
        play_tune();
        <?php } else{ ?>
        play_voice_tune();
        <?php } ?>

        var indexSpeech = 0;
        setInterval(function(){

            var messageToAnounce = speechData[indexSpeech];
            speechData[indexSpeech] = "";
            if(speechData.length - 1 == indexSpeech)
            {
                indexSpeech = 0;
            }
            else
            {
                indexSpeech++;
            }


            if(typeof messageToAnounce !== "undefined" && messageToAnounce !== "" && isPlaying == false)
            {
                isPlaying = true;
                if(typeof videoAttr.muted !== "undefined")
                {
                    videoAttr.muted = true;
                }
                if(typeof audioElement.muted !== "undefined")
                {
                    audioElement.muted = true;
                }

                var textToAnounce = messageToAnounce;
                var audioElement1 = document.createElement('audio');
                var audioUrl = 'http://ivrapi.indiantts.co.in/tts?type=indiantts&text='+textToAnounce+'&api_key=2d108780-0b86-11e6-b056-07d516fb06e1&user_id=80&action=play&audio.tts';
                if(textToAnounce == "Bell")
                {
                    audioUrl = "https://mengage.s3.amazonaws.com/26_06_2019_11_46_50_0.06565928975403257.mp3";
                }
                //console.log(audioUrl);

                audioElement1.setAttribute('src', audioUrl);
                audioElement1.play({
                    onplay: function() {
                        //console.log('Yay, playing');
                    },
                    onerror: function(errorCode, description) {
                        //console.log(errorCode,description);
                        isPlaying = false;
                        if(typeof videoAttr.muted !== "undefined")
                        {
                            videoAttr.muted = false;
                        }
                        if(typeof audioElement.muted !== "undefined")
                        {
                            audioElement.muted = false;
                        }
                    }
                });
                audioElement1.onended = function() {
                    isPlaying = false;
                    if(typeof videoAttr.muted !== "undefined")
                    {
                        videoAttr.muted = false;
                    }
                    if(typeof audioElement.muted !== "undefined")
                    {
                        audioElement.muted = false;
                    }
                };

            }

        },4000);

        showNext();
    });


    var mediaCount = <?php echo count($mediaData); ?>;

    var currKey = 0;

    function showImage(){
        $(".media"+currKey).show();
        currKey++;
        setTimeout(function(){
            showNext();
        },25000);
    }

    function showVideo(){
        console.log("media1"+currKey);
        $(".media"+currKey).show();

        videoAttr = document.getElementById("media1"+currKey);
        //console.log(videoAttr);
        currKey++;
        var promise = videoAttr.play();

        if (promise !== undefined) {
            promise.then( function() {

                videoAttr.onplay = function() {
                    //console.log('Yay, playing');
                },
                    videoAttr.onerror = function(errorCode, description) {
                        console.log(errorCode, description);
                        showNext();
                    }
                videoAttr.onended = function() {
                    //console.log("The video has ended");
                    showNext();
                };




            }).catch(error => {
                console.log(error);
            showNext();
        });
        }


        /*setTimeout(function(){
         showNext();
         },5000);*/

    }

    var setedIntervalYoutube ="";

    function showYoutube(duration){
        console.log(duration);
        $(".media"+currKey).show();
        if((typeof YT !== "undefined") && YT && YT.Player){
            console.log("here1");
            clearInterval(setedIntervalYoutube);
            var vedioKey = $(".media"+currKey).attr("data-url");
            var player = new YT.Player("media"+currKey, {
                width: '100%',
                height: '100%',
                videoId: vedioKey,
                events: {
                    onReady: function (event) { event.target.playVideo(); },
                    onStateChange: function (event) { if(event.data === 0) { currKey++; player.destroy(); showNext(); } }
                }
            });
            if(duration > 0)
            {
                setTimeout(function(){currKey++; player.destroy(); showNext();},duration);
            }

        }
    }


    function showNext(){
        if (mediaCount <= currKey) {
            currKey = 0;
        }
        var mediaType = $(".media" + currKey).attr("data-type");
        $(".media-container").hide();
        if (mediaType == 'VIDEO') {
            showVideo();
        }
        else if (mediaType == 'IMAGE') {
            showImage();
        }
        else {
            var duration = $(".media" + currKey).attr("duration");
            setedIntervalYoutube = setInterval(function () {
                showYoutube(duration)
            }, 2000);
        }
    }



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
        //console.log(selector);
        $(selector).fadeOut(400).fadeIn(400);
        var blinkInterval = setInterval(() => {
                $(selector).fadeOut(400).fadeIn(400);
            clearInterval(blinkInterval);
        },1200);

    }

    function getVoiceString(tokenNumber, patientName, roomNumber){

        var audioUrl = baseurl+"voice_anouncement/"+tokenNumber+".wav";
        return audioUrl;
    }

</script>





<style>

    body{
        overflow: hidden;
    }

    .current_token_div{
        padding-bottom: 6%;
    }
    .upcoming_lbl{
        display: block;
        border-top: 1px solid;
        text-align: center;

    }
    .upcoming_lbl span{
        background: blue;
        color: #fff;
        padding: 1rem;
        text-align: center;
        position: relative;
        font-size: 2rem;
       top:-16px;

    }
    .current_token_div .token-container,
    .current_token_div .detail-container{
        border-color: #000;
        background: #fff;
        color: #000;
    }
    .current_token_div .patient-name{
        background: #000;
        color: #fff;
    }
    .room_number_span{
        font-size: 2rem;
        display: block;
        text-align: center;
    }
    .footertext img {
        height: 90%;
    }
    .footertext {
        width: 100%;
        height: 60px;
        position: relative;
        top: 0;
        left: 0;
        text-align: center;
        font-size: 20px;
        font-weight: bold;
    }
    .powered {
        width: 260px;
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
        margin-top: 14px !important;
    }
    .emergency {
        font-size: 1.2em !important;
        margin-top: 16px !important;
    }
    .late {
        font-size: 2em !important;
        margin-top: 12px !important;
    }
    .text-center > h2 {

        margin-top: 1em;

    }

    .overlay_box_div {
        top: 0;
        left: 0;
        height: 7.8em;
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