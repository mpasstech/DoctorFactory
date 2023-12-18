
<div class="modal fade" id="franchise_fee_detail_modal">

    <div class="modal-dialog" style="width: 650px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="text-align: center;"><?php echo "<b>".$final_array['data']['app_name']."</b> (".$final_array['data']['label'].")"; ?></h4>
            </div>
            <div class="modal-body" style="height: auto;">

                <?php $inr = "<i class ='fa fa-inr'></i> "; ?>
                <div class="col-sm-6" style="padding:0px;">

                    <h3>Franchise Fee Share List </h3>
                    <?php if(!empty($final_array['list'])){ ?>
                        <div class="table-responsive">

                                <table id="assocate_table" class="table" style="margin-bottom:0;">
                                    <thead>
                                    <tr>
                                        <th style="width: 5%;">#</th>
                                        <th style="width: 65%; text-align: left;">Name</th>
                                        <th style="width: 30%; text-align: right;">Total Fee</th>
                                    </tr>


                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td colspan="3">
                                            <table id="assocate_table" class="table">

                                                <?php $total_pay=0;  foreach ($final_array['list'] as $label => $data){ ?>
                                                <thead>
                                                <tr>
                                                    <th colspan="3"><?php echo $label; ?></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php  foreach ($data as $key => $list){ ?>
                                                    <tr>
                                                        <td><?php echo $key+1; ?></td>
                                                        <td><?php echo $list['name'] ?></td>
                                                        <td style="text-align: right;"><?php $total_pay += $list['fee']; echo $inr.$list['fee']; ?></td>
                                                    </tr>
                                                <?php } ?>
                                                </tbody>

                                                <?php } ?>

                                            </table>
                                        </td>
                                    </tr>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th></th>
                                        <th style="text-align: right;">Franchise Payable Amount</th>
                                        <th style="text-align: right; font-weight: 600;"><?php echo $inr.$total_pay; ?></th>
                                    </tr>
                                    </tfoot>
                                </table>

                        </div>
                    <?php }else{ ?>
                        <h2 class="no_data"> No share list found  </h2>

                    <?php } ?>


                </div>

                <div class="col-sm-6" style="padding:0px;">

                    <h3 style="text-align: right;">Other Share List </h3>
                    <ul class="other_share_ul">
                        <li>
                            <label>Total MEngage Share :</label>
                            <label><?php echo $inr.$final_array['data']['booking_mengage_share_fee']; ?></label>
                        </li>

                        <li>
                            <label>Total GST :</label>
                            <label><?php echo $inr.$final_array['data']['booking_convenience_gst_fee']; ?></label>
                        </li>

                        <li>
                            <label>Payment Getway Fee :</label>
                            <label><?php echo $inr.$final_array['data']['booking_payment_getway_fee']; ?></label>
                        </li>


                        <li>
                            <label>Is Settled :</label>
                            <label><?php echo $final_array['data']['is_settled']; ?></label>
                        </li>

                        <?php if($final_array['data']['is_settled']=="YES"){ ?>
                        <li>
                            <label>Settled Date:</label>
                            <label><?php echo date('d/m/Y',strtotime($final_array['data']['settled_date'])); ?></label>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            <div class="modal-footer" style="clear: both;">
                <button class="btn btn-danger" data-dismiss="modal" aria-label="Close" type="button" >Close</button>
                <?php if($final_array['data']['is_settled']=="NO"){ ?>
                    <button  class="btn btn-success settle_btn" type="button">Settle</button>
                <?php } ?>

            </div>

    <script type="text/javascript">
        $(document).ready(function() {




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
            $(document).off("click",'.settle_btn');
            $(document).on("click",'.settle_btn',function(e){
                if(confirm("Are you sure, you want to settle?")){
                    var thisButton = $(this);
                    var thin_app_id = "<?php echo base64_encode($thin_app_id); ?>";
                    var month = "<?php echo ($month); ?>";
                    var year = "<?php echo ($year); ?>";
                    var baseurl = "<?php echo Router::url('/',true); ?>";
                    $.ajax({
                        url: baseurl+'admin/supp/settle_franchise_fee',
                        data:{t:thin_app_id,m:month,y:year},
                        type:'POST',
                        beforeSend:function(){
                            $(thisButton).button('loading').html('Updating...');
                        },
                        success: function(result){
                            $(thisButton).button('reset');
                            var response = JSON.parse(result);
                            if(response.status==1){
                                $("#franchise_fee_detail_modal").modal('hide');
                            }else{
                                $.alert(response.message);
                            }
                        }
                    });
                }

            });




        });


    </script>


    <style>


        .modal-body{
            height: auto;
            clear: both;
            float: left;
            width: 100%;
            padding: 0px 10px;
        }
        .other_share_ul li{
            list-style: none;
            border-bottom: 1px solid #cac8c8;
            padding: 2px 0px;

        }
        .other_share_ul li label:first-child{
            width: 60%;
        }
        .other_share_ul li label:last-child{
            text-align: right !important;
            width: 38%;
        }
    </style>



</div>


