<html>
<head>
    <title>Appointment Tracker</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <?php echo $this->Html->css(array('bootstrap.min.css',),array("media"=>'all','fullBase' => true)); ?>
    <style>
        .table-row {
            display: flex;
            margin: 1em;
        }
        .token-container {
            border: 1em solid #9feeeb;
            border-radius: 6em;
            width: 12em;
            height: 12em;
            padding: 2em;
            background-color: #099b9b;
            z-index: 1;
            color: #FFF;
            padding-left: unset;
            padding-right: unset;
        }
        .detail-container {
            height: 10em;
            margin-top: 1em;
         }
        .detail-container {
            height: 10em;
            margin-top: 1em;
            border: 0.5em solid #58eeed;
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
            height: 39%;
            margin-top: 2%;

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
            font-size: 4em;

        }
        h1, h2, h3 {
            margin-top: 10px;
        }
        .list-container {
            height: 100%;
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
    </style>
</head>
<body>


<div class="container-fluid">
    <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <div class="row text-center">
                <h1>
                    <img class="image preparing" src="<?php echo Router::url('/images/medicine_preparing.png'); ?>">
                    Medicine Preparing
                </h1>
            </div>
        </div>
        <div class="shadow col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <div class="row text-center">
                <h1>
                    <img class="image billing" src="<?php echo Router::url('/images/billing.png'); ?>">
                    Billing
                </h1>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="list-container list-container-1 col-xs-6 col-sm-6 col-md-6 col-lg-6">


            <!--div class="rows table-row text-center">
                <div class="token-container col-xs-2 col-sm-2 col-md-2 col-lg-2">
                    <h1>3</h1>
                </div>
                <div class="detail-container col-xs-10 col-sm-10 col-md-10 col-lg-10">
                    <div class="patient-name col-xs-12 col-sm-12 col-md-12 col-lg-12"><h2>Manish Kumar Jangid</h2></div>
                    <div class="doctor-name col-xs-12 col-sm-12 col-md-12 col-lg-12"><h3>Dr Arvind Gupta</h3></div>
                </div>
            </div-->


        </div>
        <div class="list-container list-container-2 col-xs-6 col-sm-6 col-md-6 col-lg-6">


            <!--div class="rows table-row text-center">
                <div class="token-container col-xs-2 col-sm-2 col-md-2 col-lg-2">
                    <h1>5</h1>
                </div>
                <div class="detail-container col-xs-10 col-sm-10 col-md-10 col-lg-10">
                    <div class="patient-name col-xs-12 col-sm-12 col-md-12 col-lg-12"><h2>Manish Kumar Jangid</h2></div>
                    <div class="doctor-name col-xs-12 col-sm-12 col-md-12 col-lg-12"><h3>Dr Arvind Gupta</h3></div>
                </div>
            </div>
            <div class="rows table-row text-center">
                <div class="token-container col-xs-2 col-sm-2 col-md-2 col-lg-2">
                    <h1>5</h1>
                </div>
                <div class="detail-container col-xs-10 col-sm-10 col-md-10 col-lg-10">
                    <div class="patient-name col-xs-12 col-sm-12 col-md-12 col-lg-12"><h2>Manish Kumar Jangid</h2></div>
                    <div class="doctor-name col-xs-12 col-sm-12 col-md-12 col-lg-12"><h3>Dr Arvind Gupta</h3></div>
                </div>
            </div>
            <div class="rows table-row text-center">
                <div class="token-container col-xs-2 col-sm-2 col-md-2 col-lg-2">
                    <h1>5</h1>
                </div>
                <div class="detail-container col-xs-10 col-sm-10 col-md-10 col-lg-10">
                    <div class="patient-name col-xs-12 col-sm-12 col-md-12 col-lg-12"><h2>Manish Kumar Jangid</h2></div>
                    <div class="doctor-name col-xs-12 col-sm-12 col-md-12 col-lg-12"><h3>Dr Arvind Gupta</h3></div>
                </div>
            </div>
            <div class="rows table-row text-center">
                <div class="token-container col-xs-2 col-sm-2 col-md-2 col-lg-2">
                    <h1>5</h1>
                </div>
                <div class="detail-container col-xs-10 col-sm-10 col-md-10 col-lg-10">
                    <div class="patient-name col-xs-12 col-sm-12 col-md-12 col-lg-12"><h2>Manish Kumar Jangid</h2></div>
                    <div class="doctor-name col-xs-12 col-sm-12 col-md-12 col-lg-12"><h3>Dr Arvind Gupta</h3></div>
                </div>
            </div>
            <div class="rows table-row text-center">
                <div class="token-container col-xs-2 col-sm-2 col-md-2 col-lg-2">
                    <h1>5</h1>
                </div>
                <div class="detail-container col-xs-10 col-sm-10 col-md-10 col-lg-10">
                    <div class="patient-name col-xs-12 col-sm-12 col-md-12 col-lg-12"><h2>Manish Kumar Jangid</h2></div>
                    <div class="doctor-name col-xs-12 col-sm-12 col-md-12 col-lg-12"><h3>Dr Arvind Gupta</h3></div>
                </div>
            </div>
            <div class="rows table-row text-center">
                <div class="token-container col-xs-2 col-sm-2 col-md-2 col-lg-2">
                    <h1>5</h1>
                </div>
                <div class="detail-container col-xs-10 col-sm-10 col-md-10 col-lg-10">
                    <div class="patient-name col-xs-12 col-sm-12 col-md-12 col-lg-12"><h2>Manish Kumar Jangid</h2></div>
                    <div class="doctor-name col-xs-12 col-sm-12 col-md-12 col-lg-12"><h3>Dr Arvind Gupta</h3></div>
                </div>
            </div-->



        </div>
    </div>
</div>


<!-- Scripts -->
<script src="<?php echo Router::url('/js/jquery.js')?>"></script>
<script src="<?php echo Router::url('/js/jquery.voicerss-tts.min.js')?>"></script>
<script src="<?php echo Router::url('/js/bootstrap.min.js')?>"></script>
<script>

    var dataPreparing = [];
    var dataBilling = [];
    var dataForSpeech = [];

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

    function setContent(dataPreparing,dataBilling){
        dataForSpeech = [];
        var htmlForContainer1 = "";
        var htmlForContainer2 = "";
        dataPreparing.forEach(function(item, index){

            htmlForContainer1 += '<div class="rows table-row text-center">'+
                '<div class="token-container col-xs-2 col-sm-2 col-md-2 col-lg-2">'+
                    '<h1>'+item.queue_number+'</h1>'+
                '</div>'+
                '<div class="detail-container col-xs-10 col-sm-10 col-md-10 col-lg-10">'+
                    '<div class="patient-name col-xs-12 col-sm-12 col-md-12 col-lg-12"><h2>'+item.patient_name+'</h2></div>'+
                    '<div class="doctor-name col-xs-12 col-sm-12 col-md-12 col-lg-12"><h3>'+item.doc_name+'</h3></div>'+
                '</div>'+
            '</div>';
        });
        dataBilling.forEach(function(item, index){

            htmlForContainer2 += '<div class="rows table-row text-center">'+
                    '<div class="token-container col-xs-2 col-sm-2 col-md-2 col-lg-2">'+
                        '<h1>'+item.queue_number+'</h1>'+
                    '</div>'+
                    '<div class="detail-container col-xs-10 col-sm-10 col-md-10 col-lg-10">'+
                        '<div class="patient-name col-xs-12 col-sm-12 col-md-12 col-lg-12"><h2>'+item.patient_name+'</h2></div>'+
                        '<div class="doctor-name col-xs-12 col-sm-12 col-md-12 col-lg-12"><h3>'+item.doc_name+'</h3></div>'+
                    '</div>'+
            '</div>';
            if(index < 4)
            {
                dataForSpeech.push(item.queue_number);
            }
        });
        $(".list-container-1").html(htmlForContainer1);
        $(".list-container-2").html(htmlForContainer2);
    }

    function setTrackerContent(data){
            dataPreparing = [];
            dataBilling = [];
            data.forEach(function(item, index){
                if(item.status == 'CHECKED-IN')
                {
                    dataPreparing.push(item);
                }
                else
                {
                    dataBilling.push(item);
                }
            });
            setContent(dataPreparing,dataBilling);
        }

    function getTrackerData(){
        var baseurl ="<?php echo Router::url('/',true);?>";
        $.ajax({
            url: baseurl+"tracker/load_lab_pharmacy_tracker_new",
            type:'POST',
            data:{t:'<?php echo $thinappID; ?>',l:'<?php echo $labUserId; ?>'},
            success: function(result){
                var data = JSON.parse(result);
                if(data.status == 1)
                {
                    setTrackerContent(data.data);
                }

            },error:function () {

            }
        });
    }

    $(document).ready(function(){
        getTrackerData();
    });

    setInterval(function(){
        getTrackerData();
    },25000);

    $(document).ready(function(){
        var indexSpeech = 0;
        setInterval(function(){
            var tokenToAnounce = dataForSpeech[indexSpeech];
            //console.log((dataBilling.length - 1), indexSpeech);
            if(indexSpeech == 2 || ((dataBilling.length - 1) == indexSpeech))
            {
                indexSpeech = 0;
            }
            else
            {
                indexSpeech++;
            }

            if(typeof tokenToAnounce !== "undefined")
            {
                //var textToAnounce = "token number"+tokenToAnounce+" please come for billing";
                var textToAnounce = "कृपया ध्यानदें, टोकन नंबर "+tokenToAnounce+" बिलिंग के लिए आएं";
                /* $.speech({
                    key: '95462391d77345a9b7445a718a7a2790',
                    src: textToAnounce,
                    hl: 'en-us',
                    r: 0,
                    c: 'mp3',
                    f: '44khz_16bit_stereo',
                    ssml: false
                }); */
                var audioElement = document.createElement('audio');
                audioElement.setAttribute('src', 'http://ivrapi.indiantts.co.in/tts?type=indiantts&text='+textToAnounce+'&api_key=2d108780-0b86-11e6-b056-07d516fb06e1&user_id=80&action=play');
                //audioElement.setAttribute('src', 'http://www.soundjay.com/misc/sounds/bell-ringing-01.mp3');
                audioElement.play({
                    onplay: function() {
                        console.log('Yay, playing');
                    },
                    onerror: function(errorCode, description) {
                        console.log(errorCode,description);
                        // https://html.spec.whatwg.org/multipage/embedded-content.html#error-codes
                    }
                });

            }

        },15000);
    });

</script>
</body>
</html>