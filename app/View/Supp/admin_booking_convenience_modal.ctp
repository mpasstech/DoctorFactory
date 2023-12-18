
<div class="modal fade" id="booking_convenience_modal">
    <form id="booking_convenience_form">
        <input type="hidden" name="form_id" value="<?php echo base64_encode($thin_app_id); ?>">
        <input type="hidden" name="assoc_id" value="<?php echo base64_encode($thinappData['assoc_id']); ?>">
    <div class="modal-dialog" style="width: 1200px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Booking Convenience Setting Modal</h4>
            </div>
            <div class="modal-body" style="height: auto;">


                <div class="col-sm-12" style="padding:0px;">

                    <div class="form-group change_box">
                        <div class="col-sm-3">
                            <div class="input text">
                                <label class="small_text" for="booking_convenience_fee">Clinic Visit Convenience Fee </label>
                                <input name="booking_convenience_fee" placeholder="Booking Convenience Fee" class="form-control" type="text" value="<?php echo $thinappData['booking_convenience_fee']; ?>" id="booking_convenience_fee">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="input text">
                                <label class="small_text" for="booking_convenience_fee_video">Video Convenience Fee </label>
                                <input name="booking_convenience_fee_video" placeholder="Booking Convenience Fee" class="form-control" type="text" value="<?php echo $thinappData['booking_convenience_fee_video']; ?>" id="booking_convenience_fee_video">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="input text">
                                <label class="small_text" for="booking_convenience_fee_audio">Audio Convenience Fee </label>
                                <input name="booking_convenience_fee_audio" placeholder="Booking Convenience Fee" class="form-control" type="text" value="<?php echo $thinappData['booking_convenience_fee_audio']; ?>" id="booking_convenience_fee_audio">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="input text">
                                <label class="small_text" for="booking_convenience_fee_chat">Chat Convenience Fee </label>
                                <input name="booking_convenience_fee_chat" placeholder="Booking Convenience Fee" class="form-control" type="text" value="<?php echo $thinappData['booking_convenience_fee_chat']; ?>" id="booking_convenience_fee_chat">
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="input text">
                                <label class="small_text" for="booking_convenience_fee_chat">Emer. Convenience Fee </label>
                                <input name="booking_convenience_fee_emergency" placeholder="Emergency Convenience Fee" class="form-control" type="text" value="<?php echo $thinappData['booking_convenience_fee_emergency']; ?>" id="booking_convenience_fee_emergency">
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="input text">
                                <label class="small_text" for="booking_payment_getway_fee_percentage">Gateway Fee (%) <span class="amount_lbl getway_lbl">0</span></label>

                                <input name="booking_payment_getway_fee_percentage" placeholder="Booking Payment Getway Fee (%)" class="form-control" type="text" value="<?php echo $thinappData['booking_payment_getway_fee_percentage']; ?>" id="booking_payment_getway_fee_percentage">
                            </div>
                        </div>


                        <div class="col-sm-3">
                            <div class="input text">
                                <label class="small_text" for="booking_convenience_gst_percentage">GST(%) <span class="amount_lbl gst_lbl">0</span></label>
                                <input name="booking_convenience_gst_percentage" placeholder="GST(%)" class="form-control" type="text" value="<?php echo $thinappData['booking_convenience_gst_percentage']; ?>" id="booking_convenience_gst_percentage">
                            </div>
                        </div>


                        <div class="col-sm-3">
                            <div class="input text">
                                <label class="small_text" for="booking_doctor_share_percentage">Doctor Share (%) <span class="amount_lbl doctor_share_lbl"></span></label>
                                <input name="booking_doctor_share_percentage" placeholder="Booking Doctor Share (%)" class="form-control" type="text" value="<?php echo $thinappData['booking_doctor_share_percentage']; ?>" id="booking_doctor_share_percentage">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="input text">
                                <label class="small_text" for="booking_convenience_fee_restrict_ivr">Fee Restrict IVR</label>

                                <select name="booking_convenience_fee_restrict_ivr" id="booking_convenience_fee_restrict_ivr" class="form-control">
                                    <option <?php echo ($thinappData['booking_convenience_fee_restrict_ivr'] == 'NO')?'selected':''; ?> value="NO">NO</option>
                                    <option <?php echo ($thinappData['booking_convenience_fee_restrict_ivr'] == 'YES')?'selected':''; ?> value="YES">YES</option>
                                </select>

                            </div>
                        </div>

                    </div>




                    <div class="form-group change_box primary_box">

                        <div class="col-sm-5">
                            <div class="input text">
                                <label class="small_text" for="primery_owner_share_percentage">Primary Owner(%)</label>
                                <input name="primary_owner_share_percentage" placeholder="Primary Owner(%)" class="form-control" type="text" value="<?php echo $thinappData['primary_owner_share_percentage']; ?>" id="primary_owner_share_percentage">
                            </div>
                        </div>
                        <div class="col-sm-7">
                            <div class="input text">
                                <label class="small_text" for="booking_payment_getway_fee_percentage">Primary Owner <span class="amount_lbl po_lbl"></span></label>
                                <?php echo $this->Form->input('primary_owner_id', array('label'=>false,'type' => 'select','options'=>$mediatorArray, 'value'=>$thinappData['primary_owner_id'], 'empty'=>'Select Owner', 'class' => 'form-control select_search')); ?>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>

                    <div class="form-group change_box primary_box">

                        <div class="col-sm-5">
                            <div class="input text">
                                <label class="small_text" for="secondary_owner_share_percentage">Secondary Owner(%) </label>
                                <input name="secondary_owner_share_percentage" placeholder="Secondary Owner(%)" class="form-control" type="text" value="<?php echo $thinappData['secondary_owner_share_percentage']; ?>" id="secondary_owner_share_percentage">
                            </div>
                        </div>
                        <div class="col-sm-7">
                            <div class="input text">
                                <label class="small_text" for="secondary_owner_id">Secondary Owner <span class="amount_lbl so_lbl"></span></label>
                                <?php echo $this->Form->input('secondary_owner_id', array('label'=>false,'type' => 'select','options'=>$mediatorArray, 'value'=>$thinappData['secondary_owner_id'], 'empty'=>'Select Owner', 'class' => 'form-control select_search')); ?>
                            </div>
                        </div>

                        <div class="clear"></div>
                    </div>


                    <div class="form-group change_box secondary_box">


                        <div class="col-sm-5">
                            <div class="input text">
                                <label class="small_text" for="primary_mediator_share_percentage">Primary Mediator(%) </label>
                                <input name="primary_mediator_share_percentage" placeholder="Primary Mediator(%)" class="form-control" type="text" value="<?php echo $thinappData['primary_mediator_share_percentage']; ?>" id="primary_mediator_share_percentage">
                            </div>
                        </div>
                        <div class="col-sm-7">
                            <div class="input text">
                                <label class="small_text" for="primary_mediator_id">Primary Mediator <span class="amount_lbl pm_lbl"></span></label>
                                <?php echo $this->Form->input('primary_mediator_id', array('label'=>false,'type' => 'select','options'=>$mediatorArray, 'value'=>$thinappData['primary_mediator_id'], 'empty'=>'Select Owner', 'class' => 'form-control select_search')); ?>
                            </div>
                        </div>
                        <div class="clear"></div>




                    </div>


                    <div class="form-group change_box secondary_box">




                        <div class="col-sm-5">
                            <div class="input text">
                                <label class="small_text" for="secondary_mediator_share_percentage">Sec. Mediator(%)</label>
                                <input name="secondary_mediator_share_percentage" placeholder="Secondary Mediator" class="form-control" type="text" value="<?php echo $thinappData['secondary_mediator_share_percentage']; ?>" id="secondary_mediator_share_percentage">
                            </div>
                        </div>
                        <div class="col-sm-7">
                            <div class="input text">
                                <label class="small_text" for="secondary_mediator_id">Secondary Mediator <span class="amount_lbl sm_lbl"></span></label>
                                <?php echo $this->Form->input('secondary_mediator_id', array('label'=>false,'type' => 'select','options'=>$mediatorArray, 'value'=>$thinappData['secondary_mediator_id'], 'empty'=>'Select Mediator', 'class' => 'form-control select_search')); ?>
                            </div>
                        </div>
                        <div class="clear"></div>




                    </div>

                    <div class="form-group change_box">

                        <div class="col-sm-12">
                            <p class="menage_p">MEngage Share Fee is : <span class="mengage_share"></span></p>
                            <div class="clear"></div>
                        </div>

                    </div>






                </div>

                <div class="col-sm-6" style="padding: 0px;">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class="input text">
                                <label class="small_text" for="booking_convenience_fee_terms_condition">Booking Convenience Fee T&C</label>

                                <textarea   name="booking_convenience_fee_terms_condition" class="form-control" placeholder="Booking Convenience Fee Terms Avd Condition" id="booking_convenience_fee_terms_condition"><?php echo $thinappData['booking_convenience_fee_terms_condition']; ?></textarea>


                            </div>
                        </div>
                    </div>


                </div>


                <div class="col-sm-6" style="padding: 0px;">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class="input text">
                                <label class="small_text" for="booking_convenience_fee_terms_condition">Booking Convenience Fee T&C Online Consulting</label>

                                <textarea  name="booking_convenience_fee_online_consutlting_terms_condition" class="form-control" placeholder="Booking Convenience Fee Terms Avd Condition For Online Consultation" id="booking_convenience_fee_online_consutlting_terms_condition"><?php echo $thinappData['booking_convenience_fee_online_consutlting_terms_condition']; ?></textarea>


                            </div>
                        </div>
                    </div>


                </div>


            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" data-dismiss="modal" aria-label="Close" type="button" >Close</button>
                <button class="btn btn-warning" type="reset">Reset</button>
                <button  class="btn btn-success save_btn" type="button">Save</button>
            </div>
    </form>

    <script type="text/javascript">
        $(document).ready(function() {



            CKEDITOR.replace( "booking_convenience_fee_terms_condition",{
                toolbarGroups: [
                    { name: 'links', groups: [ 'links' ] },
                    { name: 'colors', groups: [ 'colors' ] },
                    {name: 'basicstyles', groups: ['basicstyles', 'cleanup']},
                    {name: 'styles', groups: ['styles']},
                    {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi', 'paragraph']}
                ],
                removeButtons:'Strike,Subscript,Superscript,BidiLtr,BidiRtl,Language,CopyFormatting,RemoveFormat',
                height:300,
                autoParagraph :false,
                enterMode : CKEDITOR.ENTER_BR,
                shiftEnterMode: CKEDITOR.ENTER_P
            } );


            CKEDITOR.replace( "booking_convenience_fee_online_consutlting_terms_condition",{
                toolbarGroups: [
                    { name: 'links', groups: [ 'links' ] },
                    { name: 'colors', groups: [ 'colors' ] },
                    {name: 'basicstyles', groups: ['basicstyles', 'cleanup']},
                    {name: 'styles', groups: ['styles']},
                    {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi', 'paragraph']}
                ],
                removeButtons:'Strike,Subscript,Superscript,BidiLtr,BidiRtl,Language,CopyFormatting,RemoveFormat',
                height:300,
                autoParagraph :false,
                enterMode : CKEDITOR.ENTER_BR,
                shiftEnterMode: CKEDITOR.ENTER_P
            } );




            function calc(value) {

                if(value){
                    var re = new RegExp('^-?\\d+(?:\.\\d{0,' + (2 || -1) + '})?');
                    return value.toString().match(re)[0];
                }
                return 0;
            }


            function setAmountLabel(){
                var base_price = 0;
                var con_fee = parseFloat($("#booking_convenience_fee").val());
                var payment_getway_per = parseFloat($("#booking_payment_getway_fee_percentage").val());
                var gst_per = parseFloat($("#booking_convenience_gst_percentage").val());
                var doctor_share_per = parseFloat($("#booking_doctor_share_percentage").val());
                var po_per = parseFloat($("#primary_owner_share_percentage").val());
                var so_per = parseFloat($("#secondary_owner_share_percentage").val());
                var pm_per = parseFloat($("#primary_mediator_share_percentage").val());
                var sm_per = parseFloat($("#secondary_mediator_share_percentage").val());

                var payment_getway_fee = calc((con_fee * payment_getway_per)/100);
                base_price = con_fee - payment_getway_fee;
                var gst_fee = calc((base_price * gst_per)/100);
                var base_price = parseFloat(base_price) - parseFloat(gst_fee);
                var doctor_fee = calc((base_price * doctor_share_per)/100);

                var po_fee = calc((base_price * po_per)/100);
                var so_fee = calc((base_price * so_per)/100);
                var pm_fee = calc((base_price * pm_per)/100);
                var sm_fee = calc((base_price * sm_per)/100);
                var mengage_share = calc(parseFloat(base_price) - ( parseFloat(doctor_fee) + parseFloat(po_fee) + parseFloat(so_fee) +parseFloat(pm_fee) +parseFloat(sm_fee) ));

                var fa = "<i class='fa fa-inr'></i> ";
                $(".getway_lbl").html(fa+payment_getway_fee);
                $(".gst_lbl").html(fa+gst_fee);
                $(".doctor_share_lbl").html(fa+doctor_fee);
                $(".po_lbl").html(fa+po_fee);
                $(".so_lbl").html(fa+so_fee);
                $(".pm_lbl").html(fa+pm_fee);
                $(".sm_lbl").html(fa+sm_fee);
                $(".mengage_share").html(fa+mengage_share);


            }



            setAmountLabel();




            $(document).off("input",'.change_box input[type="text"]');
            $(document).on("input",'.change_box input[type="text"]',function(e){
                setAmountLabel();
            });




            $('.select_search').select2();
            $(document).off("click",'.save_btn');
            $(document).on("click",'.save_btn',function(e){


                for (instance in CKEDITOR.instances) {
                    CKEDITOR.instances[instance].updateElement();
                }


                var thisButton = $(this);
                var baseurl = "<?php echo Router::url('/',true); ?>";
                $.ajax({
                    url: baseurl+'admin/supp/save_booking_convenience',
                    data:$("#booking_convenience_form").serialize(),
                    type:'POST',
                    beforeSend:function(){
                        $(thisButton).button('loading').html('<i class="fa fa-spinner fa-pulse">');
                    },
                    success: function(result){
                        $(thisButton).button('reset');
                        var response = JSON.parse(result);

                        if(response.status==1){
                            $("#booking_convenience_modal").modal('hide');
                        }else{
                            $.alert(response.message);
                        }
                    }
                });
            });




        });


    </script>


    <style>


        .mengage_share{
            font-size: 25px;
            color: green;
            font-weight: 600;
        }
        .menage_p{
            padding: 5px;
            width: 100%;
            font-size: 18px;
            clear: both;
            text-transform: uppercase;
            font-weight: 600;
        }

        .amount_lbl{
            color: green;
            float: right;
            font-size: 15px;
        }
        .primary_box{

           /* clear: both;*/
            width: 50%;
            float: left;
            padding: 0 6px;
        }
        .secondary_box{
            /*clear: both;*/
            padding: 0 6px;
            width: 50%;
            float: left;
        }

        .change_box label{
            width: 100%;
        }
        .primary_box div[class*="col-sm-"], .secondary_box div[class^="col-sm-"]{
            background: #e8e4e4;

            padding: 16px 6px;

            height: 105px;

            font-size: 13px;
        }


        .select2-container .select2-selection--single, .change_box .form-control{
            height:34px !important;
            width: 100% !important;
        }
        .select2{
            width: 100% !important;
        }
        .select2-container--default .select2-selection--single, .change_box .form-control{
            border: 1px solid #ccc !important;
            border-radius: 0px !important;
        }
    </style>



</div>


