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
    <style>
        body{
            background-color:#FFFFFF;
            text-transform: uppercase;
        }
        .table-row {
            display: flex;
            margin: .2em 1em 0em 2em;
        }
        
        .appBookingClass .token-container{
            border: 1em solid #139c09;
            background-color: #2ade09;
        }

        .appBookingClass .detail-container{
            border: 0.5em solid #139c09;
            background-color: #2ade09;
        }
        
        
         .appBookingClass .patient-name{
            background-color: #2ade09;
        }
        
        
        .token-container {
            border: 1em solid #9feeeb;
            border-radius: 6em;
            width: 10em;
            height: 6em;
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
            margin-top: .5em;
            border: 0.5em solid #58eeed;
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
            background-color: #58eeed;
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
            height: 38%;
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

    </style>
</head>
<body>


<div class="container-fluid">
    <div class="row header-title">
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <div class="row text-center">
                <h1>
                    Upcoming
                </h1>
            </div>
        </div>
        <div class="shadow col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <div class="row text-center">
                <h1>
                    Current
                </h1>
            </div>
        </div>
    </div>
    <div class="row" id="updated_content">
    </div>
</div>


<!-- Scripts -->
<script src="<?php echo Router::url('/js/jquery.js')?>"></script>

<script src="<?php echo Router::url('/js/bootstrap.min.js')?>"></script>

<script>
    var refreshSec = <?php echo $thinappData1['tracker_new_refresh_sec']*1000; ?>;
    var show_patient_name = "<?php echo $thinappData1['tracker_new_show_patient_name']; ?>";
    var show_sub_token_number = "<?php echo $thinappData1['show_sub_token_name_on_tracker']; ?>";

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
                        data:{t:'<?php echo $thinappID; ?>',token:currentToken,type:"NEW"},
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

    var dataForSpeech = [];
    var dataAudioPreload = [];
    var oldDataForSpeech = [];
    var breakArray = {};

    function setContent(data){
        dataAudioPreload = [];
        dataForSpeech = [];
        var htmlForDoc = "";
        if(Object.keys(data).length > 0)
        {
            if(data.length == 2)
            {
                var indexCompare = 1;
                var height = "38%";

            }
            else
            {
                var indexCompare = 5;
                var height = "100%";

            }
            //console.log("index compare",indexCompare);
            data.reverse();
            data.forEach(function(item, index){
                var room = (item.room_number != "")?item.room_number:"";
                var roomToShow = (item.room_number != "")?"(Room-"+item.room_number+")":"";
                var docName = item.doctor_name;
                var docID = item.doctor_id;

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



                htmlForDoc += '<div class="list-container  all-list-container list-container-1 col-xs-6 col-sm-6 col-md-6 col-lg-6">';

                //console.log(item.upcoming);
                if((item.upcoming) !== undefined)
                {
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
                            var appBookingClass = "";
                            console.log("left "+item1.appointment_booked_from);
                            if(item1.appointment_booked_from == 'APP' || item1.appointment_booked_from == 'DOCTOR_PAGE'){
                                 appBookingClass = "appBookingClass";
                            }
                            


                            htmlForDoc += '<div class="rows table-row text-center '+appBookingClass+'">'+
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


                        dataAudioPreload.push({token:item1.queue_number,name:patientName1,room_number:room,token_announce:queueNum});
                    });
                    preloadAudio();
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


                var queueNum1 = item.current_token;
                var htmlClass1 = "";
                if(item.patient_queue_type == 'REPORT_CHECKIN'){
                    queueNum1 = 'REPORT';
                    htmlClass1 = 'report';
                } else if(item.patient_queue_type == 'LATE_CHECKIN'){
                    queueNum1 = 'NOT';
                    htmlClass1 = 'late';
                }else if(item.patient_queue_type == 'EARLY_CHECKIN' || item.patient_queue_type == 'EMERGENCY_CHECKIN' || item.sub_token == 'YES'){
                    queueNum1 = 'EMERGENCY';
                    htmlClass1 = 'emergency';
                    if(item.sub_token == 'YES' && show_sub_token_number=='YES'){
                        queueNum = item.queue_number;
                        htmlClass="";
                    }
                    
                }
                //item.current_token = queueNum1;
                
                var appBookingClass1 = "";
                console.log(item.appointment_booked_from);
                if(item.appointment_booked_from == 'APP' || item.appointment_booked_from == 'DOCTOR_PAGE'){
                    appBookingClass1 = "appBookingClass";
                }
                

                htmlForDoc += '</div>'+
                    '<div class="list-container '+item.current_token+patientName+' list-container-2 col-xs-6 col-sm-6 col-md-6 col-lg-6">';
                var pName1 = (show_patient_name == "Y")?item.current_patient.substr(0, 12):"";
                htmlForDoc += '<div class="rows table-row text-center '+appBookingClass1+'">'+
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
                    dataForSpeech.push({token:item.current_token,name:patientName,room_number:room,token_announce:queueNum1});
                }


            });

            $("#updated_content").html(htmlForDoc);
            $(".list-container").css("height",height);

        }
        else
        {
            $("#updated_content").html(htmlForDoc);
        }




    }

    var speechData = [];

    function getTrackerData(){
        var baseurl ="<?php echo Router::url('/',true);?>";
        $.ajax({
            url: baseurl+"tracker/load_tracker_opd_new",
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
                    breakArray = data.break_array;
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
        getTrackerData();
    },refreshSec);

    function play_voice_tune(){
        setInterval(function(){

            var tokenToAnounce = dataForSpeech[indexSpeech];
            //console.log((dataBilling.length - 1), indexSpeech);
            var indexSpeech1 = indexSpeech;
            if(indexSpeech == 2 || ((dataForSpeech.length - 1) == indexSpeech))
            {
                indexSpeech = 0;
            }
            else
            {
                indexSpeech++;
            }

            if(typeof tokenToAnounce !== "undefined")
            {
                tokenToAnounce = dataForSpeech[indexSpeech1].token;// +' '+dataForSpeech[indexSpeech1].name;
                var tokenToAnounceMod = dataForSpeech[indexSpeech1].token_announce;

                if(oldDataForSpeech.includes(tokenToAnounce) !== true) {
                    oldDataForSpeech.push(tokenToAnounce);


                    /* var subText = (dataForSpeech[indexSpeech1].room_number != "")?" room number "+dataForSpeech[indexSpeech1].room_number+" में":" आगे";
                    var textToAnounce = encodeURIComponent("कृपया ध्यानदें, टोकन नंबर "+tokenToAnounce+ subText +" आएं"); */
                    //console.log(textToAnounce);
                    var textToAnounce = getVoiceString(tokenToAnounceMod, dataForSpeech[indexSpeech1].name, dataForSpeech[indexSpeech1].room_number);

                    if(typeof audioElement.muted !== "undefined")
                    {
                        audioElement.muted = true;
                        audioElement.pause();
                        audioElement.currentTime = 0;
                    }
                    var pNameTmp = dataForSpeech[indexSpeech1].name.split(" ");
                    blink('.'+dataForSpeech[indexSpeech1].token+pNameTmp);

                    audioElement = document.createElement('audio');
                    /*audioElement.setAttribute('src', 'http://ivrapi.indiantts.co.in/tts?type=indiantts&text='+textToAnounce+'&api_key=2d108780-0b86-11e6-b056-07d516fb06e1&user_id=80&action=play&audio.tts');*/
                    audioElement.setAttribute('src', textToAnounce);
                    audioElement.play({
                        onplay: function() {
                            console.log('Yay, playing');
                        },
                        onerror: function(errorCode, description) {
                            console.log(errorCode,description);
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

            var tokenToAnounce = dataForSpeech[indexSpeech];
            //console.log((dataBilling.length - 1), indexSpeech);
            var indexSpeech1 = indexSpeech;
            if(indexSpeech == 2 || ((dataForSpeech.length - 1) == indexSpeech))
            {
                indexSpeech = 0;
            }
            else
            {
                indexSpeech++;
            }

            if(typeof tokenToAnounce !== "undefined")
            {
                tokenToAnounce = dataForSpeech[indexSpeech1].token;// +' '+dataForSpeech[indexSpeech1].name;

                if(oldDataForSpeech.includes(tokenToAnounce) !== true) {
                    oldDataForSpeech.push(tokenToAnounce);


                    var audioUrl = "<?php echo Router::url('/tracker_tunes/'.$thinappData1['tune_tracker_new']); ?>";

                    if(typeof audioElement.muted !== "undefined")
                    {
                        audioElement.muted = true;
                        audioElement.pause();
                        audioElement.currentTime = 0;
                    }
                    var pNameTmp = dataForSpeech[indexSpeech1].name.split(" ");
                    blink('.'+dataForSpeech[indexSpeech1].token+pNameTmp[0]);
                    audioElement = document.createElement('audio');
                    audioElement.setAttribute('src', audioUrl);
                    audioElement.play({
                        onplay: function() {
                            console.log('Yay, playing');
                        },
                        onerror: function(errorCode, description) {
                            console.log(errorCode,description);
                        }
                    });


                }
            }

        },4000);
    }

    var indexSpeech = 0;

    $(document).ready(function(){

        <?php if($thinappData1['tune_tracker_new'] != ""){ ?>
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
                        console.log('Yay, playing');
                        if(typeof audioElement.muted !== "undefined")
                        {
                            audioElement.muted = true;
                        }
                    },
                    onerror: function(errorCode, description) {
                        console.log(errorCode,description);
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

    function blink(selector){
        //console.log(selector);
        $(selector).fadeOut(400).fadeIn(400);
        var blinkInterval = setInterval(() => {
                $(selector).fadeOut(400).fadeIn(400);
        clearInterval(blinkInterval);
    },1200);

    }


    /*function getVoiceString(tokenNumber, patientName, roomNumber){
        <?php if($thinappData1['tracker_voice'] != ''){ ?>
            var text = "<?php echo $thinappData1['tracker_voice']; ?>";
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
</script>

<div class="footertext">
    <div class="powered">
        Powered by <img class="logo-mengage" src="https://mpasscheckin.com/doctor/img/old_tracker_logo.png">
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