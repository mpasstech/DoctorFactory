<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8" />
    <title>Daily Token</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <meta name="mobile-web-app-capable" content="yes">
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="author" content="mengage">
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta http-equiv="cache-control" content="no-store"/>
    <meta http-equiv="expires" content="0"/>
    <meta http-equiv="pragma" content="no-cache"/>
    <link rel="stylesheet" href="<?php echo Router::url('/css/moq_css.css?'.date('his'),true)?>" />
 <link rel="icon" type="image/png" href="https://www.mpasscheckin.com/doctor/favicon.png">
    <?php echo $this->Html->script(array('jquery-3.5.1.min.js','bootstrap4.min.js','jquery-confirm.min.js')); ?>
    <?php echo $this->Html->css(array( 'bootstrap4.min.css','font-awesome.min.css','jquery-confirm.min.css'),array("media"=>'all')); ?>



    <style>




        #myTabContent label{
            display: block;
            width: 100%;
            text-align: left;
            font-weight: 600;
        }
        .login_box_dti {
            padding: 0.2rem 0.5rem;
            background: #fff;
            border: 1px solid #cfcfcf;
            float: left;
            width: 100%;
            display: block;
            margin: 1rem 0;
            border-radius: 5px;
        }

        .body_heading{
            text-align: center;
            width: 100%;
            display: block;
            font-size: 1.4rem;
        }
        .body_heading span{
            display: block;
            color: rgb(90 153 22);
            font-size: 1.7rem;
            margin-bottom: 1.5rem;
        }



        .login_box_dti .nav-link.active {
            color: #fff;
            background-color: rgb(124 124 124);
            border-color: rgb(124 124 124);

        }
        .login_box_dti .nav-link{
            font-size: 0.9rem;
            font-weight: 600;
        }
        .login_box_dti .tab-pane{
            padding: 1rem 0;
        }
        .button_l{
            border: navajowhite;
            padding: 0.4rem 2rem;
            margin: 0.8rem auto;
            display: block;
            background: rgb(93 84 255);
            color: #fff;
            border-radius: 3px;
            width: 100%;
        }
        #logo_image{
            width: 100px;
            height: 100px;
            margin: 0 auto;
            display: block;
        }
        .sub_lable{
            text-align: center;
            width: 100%;
            display: block;
            font-size: 1.1rem;
        }

        .tab_container_box{
            float: left;
            display: block;
            width: 100%;
            padding: 0.2rem;
            background: #f1eeeeb8;
        }
    </style>

</head>


<?php

$background = Router::url('/opd_form/css/backgound.png',true);
$background = "background: url('$background');";
if(empty($data)){
    $background ='overflow:hidden;background:none;';
}

$single_field = 'YES';

?>
<body style="<?php echo $background; ?>;">
<?php if(!empty($data)){ ?>
    <header>
        <h3 style="text-align: center;">
            mPass Token
        </h3>
    </header>
    <div class="container-fluid">
        <div class="login_box_dti">
            <img id="logo_image" src="<?php echo $data['logo']; ?>" alt="Logo Image" />
            <h1 class="body_heading" ><span><?php echo $data['name']; ?></span></h1>
            <p class="sub_lable">Daily token interface login panel </p>

            <div class="tab_container_box">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#otp-tab" role="tab" aria-controls="home" aria-selected="true">Login With OTP</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#password-tab" role="tab" aria-controls="profile" aria-selected="false">Login With Password</a>
                    </li>

                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="otp-tab" role="tabpanel" aria-labelledby="home-tab">

                        <label for="uname">Mobile Number</label>
                        <input autocomplete="off" type="number" placeholder="Enter 10 digit mobile number" id="mobile_otp" class="form-control" required>
                        <button class="button_l" type="button" id="sendOtp">Tap here to send OTP</button>
                        <label for="psw">OTP</label>
                        <input autocomplete="off" class="form-control" type="password" placeholder="Enter OTP" id="otp" required>
                        <button type="button" class="button_l" id="login_otp_btn">Login</button>

                    </div>
                    <div class="tab-pane fade" id="password-tab" role="tabpanel" aria-labelledby="profile-tab">

                        <label for="uname">Mobile Number</label>
                        <input autocomplete="off" type="number" placeholder="Enter 10 digit mobile number" id="mobile_password" class="form-control" required>
                        <label for="psw">Password</label>
                        <input autocomplete="off" class="form-control" type="password" placeholder="Enter Password" id="password" required>
                        <button type="button" class="button_l" id="login_passwrod_btn">Login</button>
                    </div>

                </div>
            </div>
        </div>
    </div>

<?php }else{ ?>
    <header>
        <h3 style="text-align: center;">
            Invalid Slug
        </h3>
    </header>
    <div class="container-fluid">
        <div class="login_box">
            <h1 style="font-size: 2rem;">Invalid Slug</h1>
            <h1 style="text-align: center;width: 100%;">Please enter valid Url</h1>

        </div>
    </div>

<?php } ?>
<div class="modal fade" id="counterSelectionModal" role="dialog" keyboard="true"></div>
</body>




<script>




    $(function () {

        var lastText = "";
        function showLoader(obj,flag,text) {
            if(flag){
                lastText = $(obj).text();
                $(obj).attr('disabled','disabled');
                $(obj).button('loading').html(text);
            }else{
                $(obj).removeAttr('disabled');
                $(obj).html(lastText);
            }
        }


        const UUIDGeneratorBrowser = () =>
            ([1e7] + -1e3 + -4e3 + -8e3 + -1e11).replace(/[018]/g, c =>
                (c ^ (132[0] & (15 >> (c / 4)))).toString(16)
            );
        console.log(UUIDGeneratorBrowser());



        var baseUrl = "<?php echo Router::url('/',true); ?>"
        var ti = "<?php echo $thin_app_id; ?>";
        $(document).on("click","#sendOtp",function(){
            var okBtn = $(this);
            var mobile = $("#mobile_otp").val();
            if(mobile.length==10){
                $.ajax({
                    url: baseUrl+"doctor/send_otp",
                    data:{mobile:mobile,t:ti},
                    type:'POST',
                    beforeSend:function () {
                        showLoader(okBtn,true,"Sending OTP...");
                    },
                    success: function(result){
                        showLoader(okBtn,false,"");
                        result = JSON.parse(result);
                        if (result.status == 1) {
                            row_id = result.row_id;
                        }else{
                            $.alert(result.message);
                        }
                    },error:function () {
                        showLoader(okBtn,false,"");
                    }
                });
            }else{
                $.alert("Please enter valid 10 digit mobile number");
                $("#mobile_otp").focus();
            }
        })

        $(document).on("click","#login_otp_btn",function(){
            login($(this),'otp');
        })


        function setCookie(cname, cvalue, exdays) {
            var d = new Date();
            d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
            var expires = "expires="+d.toUTCString();
            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
        }

        function delete_cookie(name) {
            document.cookie = name +'=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
        }


        function getCookie(cname) {
            var name = cname + "=";
            var ca = document.cookie.split(';');
            for(var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        }


        $(document).on("click","#login_passwrod_btn",function(){
            login($(this),'password');
        })

        var row_id = "";
        function login(okBtn,loginType){

            var mobile_otp = $("#mobile_otp").val();
            var mobile_password = $("#mobile_password").val();
            var password = $("#password").val();
            var otp = $("#otp").val();
            if(loginType=='password' && password=="" ){
                $.alert("Please enter valid password");
                $("#password").focus();
            }else if(loginType=='otp' && row_id=="" ){
                $.alert("Please tap on OTP button to send OTP");
            }else if(loginType=='otp' && otp=="" ){
                $.alert("Please enter OTP");
                $("#otp").focus();
            }else if(mobile_password.length != 10 && loginType=='password'){
                $.alert("Please enter valid 10 digit mobile number");
                $("#mobile_password").focus();
            }else if(mobile_otp.length!=10 && loginType=='otp'){
                $.alert("Please enter valid 10 digit mobile number");
                $("#mobile_otp").focus();
            }else{

                var mobile = (loginType=='password')?mobile_password:mobile_otp;
                $.ajax({
                    url: baseUrl+"homes/dti_login",
                    data:{ti:ti,mobile:mobile,login_with:loginType,password:password,otp:otp,row_id:row_id},
                    type:'POST',
                    beforeSend:function () {
                        showLoader(okBtn,true,"Login...");
                    },
                    success: function(result){
                        showLoader(okBtn,false,"");
                        result = JSON.parse(result);
                        if (result.status == 1) {
                            delete_cookie(result.data.token_name);
                            if(result.data.li !=""){
                                $("#counterSelectionModal").html(result.data.li);
                                $("#counterSelectionModal").modal('show');
                            }else{
                                setCookie(result.data.token_name,result.data.token,1000);
                                setCookie("_dti",result.data.token,1000);
                                window.location.href =result.data.dashboard;
                            }
                        }else{
                            $.alert(result.message);
                        }
                    },error:function () {
                        showLoader(okBtn,false,"");
                    }
                });
            }
        }


        function setLocalData(string,staff_id){
            var local_name ="moq_login"+staff_id;
            localStorage.setItem(local_name, string);
        }


		

    })
</script>
</html>


