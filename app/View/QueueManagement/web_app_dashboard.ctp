<div class="row tab_btn_div" style="display:none;">



    <div class="col-12 banner_div_sec">
        <div class="row doctor_banner_img_sec">
            <div class="col-3">
                <?php if(!empty($doctor_data['profile_photo'])){ ?>
                    <img src="<?php echo $doctor_data['profile_photo']; ?>"  />
                <?php }else{ ?>
                    <img src="<?php echo Router::url("/images/channel-icon.png",true); ?>" />
                <?php } ?>
            </div>
            <div class="col-9" style="padding-right: 0px;">
                <h3><?php echo mb_strimwidth($doctor_data['app_name'], 0, 25, '...'); ?></h3>
                <?php if($doctor_data['app_name']!=$doctor_data['name']){ ?>
                    <h4><?php echo mb_strimwidth($doctor_data['name'], 0, 25, '...'); ?></h4>
                <?php } ?>
                <span id="top_sub_title">Dashboard</span>
            </div>
        </div>
    </div>


    <?php if(!empty($doctor_data['website_url'])){
        $urlStr =$doctor_data['website_url'];
        $parsed = parse_url($urlStr);
        if (empty($parsed['scheme'])) {
            $urlStr = 'http://' . ltrim($urlStr, '/');
        }
        ?>
        <div class="icon-div-btn col-4" id="dash_about_hospital">
            <button data-id="about_hospital" onclick="window.location.href ='<?php echo $urlStr; ?>'" ><i class="fa fa-building" aria-hidden="true"></i>About Hospital</button>
        </div>
    <?php } ?>


    <?php
    $doctor_list = $this->AppAdmin->get_all_doctor_list($thin_app_id, $doctor_id);
    if(!empty($doctor_list)){
        $ids = array_column($doctor_list,'id');
        $ids = implode(",",$ids);
        ?>
        <div data-id="<?php echo $ids; ?>" class="icon-div-btn col-4" id="dash_other_doctors" >
            <button class=" tab-blog" data-id="other_doctors" data-title=""><i class="fa fa-Users" aria-hidden="true"></i>Other Doctors</button>
        </div>
    <?php } ?>



    <?php if(!empty($doctor_data['ivr_number'])){ ?>
        <div class="icon-div-btn col-4" style="display: none;">
            <a href="tel:<?php echo $doctor_data['ivr_number']; ?>" data-id="virtual_receptionist" ><i class="fa fa-phone" aria-hidden="true"></i>Virtual Receptionist</a>
        </div>
    <?php }else if(!empty($doctor_data['doctor_code'])){ ?>
        <div class="icon-div-btn col-4" style="display: none;">
            <a href="tel:‎+911141171845" data-code="<?php echo $doctor_data['doctor_code']; ?>" class="virtual_reception_btn" data-id="virtual_receptionist" ><i class="fa fa-phone" aria-hidden="true"></i>Virtual Receptionist</a>
        </div>
    <?php } ?>


    <div class="icon-div-btn col-4" id="chat_boat_div" >
        <button onclick="" data-id="chatboat" ><i class="fa fa-comment" aria-hidden="true"></i>Book Token <br> Via Chatbot</button>
    </div>



    <div class="icon-div-btn col-4" id="chat_boat_div" style="display: none;" >
        <button onclick="" data-id="chatboat" ><i class="fa fa-comment-o" aria-hidden="true"></i>Book Token <br> Via Chatbot</button>
    </div>


</div>
<div class="main_container container-fluid contain" data-ti = "<?php echo base64_encode($doctor_data['thin_app_id']); ?>" data-di = "<?php echo $doctor_id; ?>" data-online="<?php echo $doctor_data['is_online_consulting']; ?>" data-offline="<?php echo $doctor_data['is_offline_consulting']; ?>" data-chat="<?php echo $doctor_data['is_chat_consulting']; ?>" data-audio="<?php echo $doctor_data['is_audio_consulting']; ?>" >

    <?php if(!empty($doctor_list)){  ?>
        <div class="tab-content" id="other_doctors" style="display: none;">
            <div class="row">
                <?php foreach ($doctor_list as $key =>$val){ ?>
                    <div class="col-12 list_container_div" >
                        <img src="<?php echo !empty($val['profile_photo'])?$val['profile_photo']:$doctor_data['logo']; ?>" class="other_doctor_img" />
                        <label>
                            <?php echo $val['name'];?>
                            <span class="span_department"><?php echo $val['department_name'];?></span>
                            <a target="_blank" href="<?php echo 'https://www.mengage.in/doctor/queue_management/index/'.$val['id'].'/?t='.base64_encode($val['id']).'&wa=y&back=no'; ?>"> Book Token</a>

                        </label>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php }  ?>

</div>
<div class="bottom_box">
    <table class="table_bottom">
        <tr>
            <td class="tracker_row">
            </td>
        </tr>
        <tr>
            <td>
                <?php

                $ban_list  = $this->AppAdmin->getAddBanner();
                $img_path =$ban_list[0]['path'];
                $ban_list=array();
                $ban_list[] ='Select install Webapp or Weblink on Dashboard or Home Page';
                $ban_list[]= 'No download hassle';
                $ban_list[]= 'No memory consumption -Still remain connected on single click';
                $ban_list[]= 'Pre-book token in future any time in fewer clicks';
                $ban_list[]= 'Track your token live on single click';

                ?>
                <?php if($show_add_banner && !empty($ban_list)){ ?>
                    <div class="row add_container">
                        <div id="home_advertisement" class="carousel slide" data-ride="carousel" >

                            <ol class="carousel-indicators">
                                <?php foreach ($ban_list as $key => $banner) {   ?>
                                    <li data-target="#carouselExampleIndicators" data-slide-to="<?php echo $key; ?>" class="<?php echo ($key==0)?'active':''; ?>""></li>

                                <?php } ?>
                            </ol>


                            <div class="carousel-inner">
                                <?php foreach ($ban_list as $key => $banner) {   ?>
                                    <div class="carousel-item <?php echo ($key==0)?'active':''; ?>">
                                        <table>
                                            <tr>
                                                <td style="width: 20%; padding: 0.3rem;">
                                                    <img src="<?php echo $img_path; ?>" class="d-block" alt="...">
                                                </td>
                                                <td style="width: 80%;padding: 0.2rem;">
                                                    <h6><?php echo $banner; ?></h6>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                <?php } ?>
                            </div>
                            <a class="carousel-control-prev" href="#carouselExampleInterval" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#carouselExampleInterval" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                    </div>
                <?php } ?>
            </td>
        </tr>
    </table>
</div>



<style>
    #track_token{
        float: left;
        width: 100%;
        display: block;
        overflow-y: scroll;
        height:calc(100vh - 40px);

    }
    #trackerIframe{
        width: 100%;
        border-top: 3px solid #e3e3e3 !important;
        display: block;
        float: left;
        position: relative;
        border: none;
    }
</style>

<script>

    Dropzone.autoDiscover = false;

    $(function () {

        var urlParams = new URLSearchParams(window.location.search);
        var url = $("#trackerIframe").attr('data-src');
        if(urlParams.get('pm')){
            var pm = urlParams.get('pm');
            url = url+"&pm="+pm;
        }
        $("#trackerIframe").attr('src', url);


        setTimeout(function () {

            var totalHeight = $("body").height();
            var headerHeight = $("header").height();
            var dashboardHeight = $("#dashboard_box").height();
            totalHeight = totalHeight-(headerHeight+dashboardHeight);
            $("#trackerIframe").css('height', parseInt(totalHeight));
        },100);










        function getQueryParams(){
            try{
                url = window.location.href;
                query_str = url.substr(url.indexOf('?')+1, url.length-1);
                r_params = query_str.split('&');
                params = {}
                for( i in r_params){
                    param = r_params[i].split('=');
                    params[ param[0] ] = param[1];
                }
                return params;
            }
            catch(e){
                return {};
            }
        }



        var slider_interval = 4500;
        $('.carousel').carousel({
            interval: slider_interval
        });

        $(document).off('click',".inner_label_header a");
        $(document).on('click',".inner_label_header a",function() {
            $($(this).attr("data-hide")).hide();
            $($(this).attr("data-target")).show();
        });


        $(document).off('click',"#addressSlider li");
        $(document).on('click',"#addressSlider li",function() {
            $(this).closest("ul").find("li").removeClass("selected_address");
            $(this).addClass("selected_address");
            $(this).closest("ul").hide();
            $("#date_slider").show();
        });
        history.pushState(null, null, location.href);
        window.onpopstate = function () {
            history.go(1);
        };

        $("#row_content").data('key',"<?php echo base64_encode(json_encode(($doctor_data))); ?>");
        $("#body").data('key',<?php echo json_encode(($success)); ?>);
        if($("#body").data('key')){
            var appointment = $("#body").data('key');
            if(appointment.id > 0){
                swal({
                    type:appointment.dialog_type,
                    title: appointment.title,
                    html: "<ul class='success_ul'>"+appointment.message+"</ul>",
                    showCancelButton: false,
                    confirmButtonText: 'Ok',
                    customClass:"success-box",
                    showConfirmButton: true
                }).then(function (result) {
                    var apk_url = "<?php echo $doctor_data['apk_url']; ?>";

                    if (apk_url.search('http://') == -1){
                        apk_url = 'https://'+apk_url;
                    }
                    var ua = navigator.userAgent.toLowerCase();
                    var isAndroid = ua.indexOf("android") > -1;
                    var isAndroid = true;
                    if(isAndroid && app_category!='TEMPLE' && apk_url !='' && appointment.dialog_type=='success'){
                        swal({
                            type:'info',
                            title: 'Token Confirmed',
                            html: "<div class='download_box'><p>The booking confirmation message has been sent to you on whatsapp or mobile sms in case you don't have whatsapp account.</p><p>Please <a href='"+apk_url+"' target='_blank'><i class='fa fa-android'></i> Download</a> doctor app from playstore to track medical records, live tracking of your token, notifications and better experience. </p><div id='diloag_add_box'></div></div>",
                            showCancelButton: false,
                            confirmButtonText: 'Thankyou',
                            customClass:"success-box",
                            showConfirmButton: true
                        }).then(function (result) {
                            window.close();
                            var url = baseUrl+'doctor/index/?t='+(appointment.doctor_id);
                            window.location.replace(url);
                            window.location = baseUrl+'doctor/index/?t='+(appointment.doctor_id);
                        });
                        if($(".add_container").length > 0){
                            setTimeout(function () {
                                var string = $(".add_container").html();
                                string = string.replace("home_advertisement", "dialog_advertisement");
                                $("#diloag_add_box").html($(string));
                                $('#dialog_advertisement').carousel({
                                    interval: slider_interval
                                });
                            },100);
                        }
                    }else{
                        var url = baseUrl+'doctor/index/?t='+(appointment.doctor_id);
                        window.location.replace(url);
                        window.location = baseUrl+'doctor/index/?t='+(appointment.doctor_id);
                    }
                });
            }
        }

        $(document).off('hover',".read_more_about");
        $(document).on('hover',".read_more_about",function() {
            $('.available-tool').hide();
            var e = $(this);
            e.off('hover');
            e.popover({
                content: e.data('poload'),
                html: true,
                placement: 'right',
            }).popover('show');

        });


        $(document).off('click',".back_button");
        $(document).on('click',".back_button",function() {
            window.location.href = $(this).attr('data-href');
        });

        if(is_desktop()){
            $("body").addClass("desktop_layout");
        }

        function isSafari(){
            var ua = navigator.userAgent.toLowerCase();
            if (ua.indexOf('safari') != -1) {
                if (ua.indexOf('chrome') > -1) {
                    return false;
                } else {
                    return true;
                }
            }
        }

        if(isSafari()){
            $("[data-id='android_app']").closest(".icon-div-btn").hide();
        }

        function is_desktop(){
            if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
                return false;
            }return true;
        }



    });
</script>


</body>
</html>