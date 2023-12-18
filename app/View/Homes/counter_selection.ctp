    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Select Counter</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <ul>
                    <?php foreach ($list as $service_name => $counterList){ ?>
                        <li class="service_name_li">
                            <label class="service_label"><?php echo $service_name; ?></label>
                            <ul class="sub_list">
                                <?php foreach ($counterList as $key => $counter){ ?>
                                    <li class="counter_name_li">
                                        <label>
                                            <h6><?php echo $counter['name']; ?></h6>
                                            <input data-status="<?php echo $counter['counter_status']; ?>" class="counter_radio" id="<?php echo base64_encode($counter['id']); ?>" type="radio" name="counterRadio" value="<?php echo base64_encode($counter['id']); ?>"/>
                                            <span class="counter_status"><b>Status :-</b> <?php echo ucfirst(strtolower($counter['counter_status'])); ?></span>
                                            <?php if(!empty($counter['recepiton_name'])){ ?>
                                                <span class="counter_status"><b>Last access by :-</b> <?php echo $counter['recepiton_name']; ?></span>
                                            <?php } ?>


                                        </label>

                                    </li>
                                <?php } ?>
                            </ul>
                        </li>
                    <?php } ?>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-secondary counterSelectedBtn"  data-si="<?php echo $login_staff_id; ?>" data-ti="<?php echo $thin_app_id; ?>" >Open Counter</button>
            </div>
        </div>
    </div>
    <style>
        #counterSelectionModal ul{
            padding: 0;
            margin: 0;
        }

        #counterSelectionModal .modal-body{
            height: 450px;
            overflow-y: auto;
        }
        #counterSelectionModal ul li{
            list-style: none;
            width: 100%;
        }
        .counter_status{
            display: block;
            font-size: 0.7rem;
        }
        .service_name_li .service_label {
            display: inline-block;
            margin-bottom: 0.5rem;
            width: 100%;
            padding: 0.2rem;
            background: #f3f3f3 !important;
            font-size: 1rem;
            font-weight: 600;
        }

        .counter_radio{
            float: right;
            display: block;
            width: 20px;
            height: 20px;
        }
        .counter_name_li{
            padding: 0.5rem;
            border-bottom: 1px solid #e5e5e5;
        }

        .counter_name_li label{
            width: 100%;
        }

    </style>
    <script type="text/javascript">
        $(function(){

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

            function setCookie(cname, cvalue, exdays) {
                var d = new Date();
                d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
                var expires = "expires="+d.toUTCString();
                document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
            }

            function delete_cookie(name) {
                document.cookie = name +'=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
            }

            $(document).off("click",".counterSelectedBtn");
            $(document).on("click",".counterSelectedBtn",function(){
                var okBtn = $(this);
                var ti = $(this).attr('data-ti');
                var si = $(this).attr('data-si');
                var id =  $(".counter_radio:checked").val();
                var status =  $(".counter_radio:checked").attr('data-status');

                if(id){
                    $.ajax({
                        url: "<?php echo Router::url('/homes/updateCounterStatus',true); ?>",
                        data:{si:si,ti:ti,id:id,status:"OPEN"},
                        type:'POST',
                        beforeSend:function () {
                            showLoader(okBtn,true,"Opening...");
                        },
                        success: function(result){
                            showLoader(okBtn,false,"");
                            result = JSON.parse(result);
                            if (result.status == 1) {
                                delete_cookie(result.data.token_name);
                                setCookie(result.data.token_name,result.data.token,1000);
                                setCookie("_dti",result.data.token,1000);
                                window.location.href = result.data.dashboard;
                            }else{
                                $.alert(result.message);
                            }
                        },error:function () {
                            showLoader(okBtn,false,"");
                        }
                    });
                }else{
                    $.alert("Please select counter");
                }
            })
        })
    </script>
