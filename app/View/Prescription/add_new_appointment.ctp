<div class="modal fade" id="addNewAppointment" tabindex="-1"  role="dialog" >


    <?php   $address_list = $this->AppAdmin->custom_dropdown_list($doctor['id'],$doctor['thinapp_id'],'ADDRESS');
            $service_list = $this->AppAdmin->custom_dropdown_list($doctor['id'],$doctor['thinapp_id'],'SERVICE');

    ?>
    <div class="modal-dialog modal-md">
        <form id="booking_frm">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Book Appointment</h4>
                </div>

                <div class="modal-body">

                    <div class="row">
                        <div class="form-group">

                            <div class="col-sm-6">
                                <label style="width: 100%;">Mobile   <i style="float: right; display: none;" class="loading_mobile fa fa-spinner fa-pulse fa-fw"></i></label>
                                <input type="text" id="modal_mobile" data-masked-input="9999999999"  maxlength="10"  minlength="10" name="m_pat_mobile" class="form-control">
                            </div>


                            <div class="col-sm-6">
                                <label style="width: 100%;">Name   <i style="float: right; display: none;" class="loading_name fa fa-spinner fa-pulse fa-fw"></i></label>

                                <input type="text" id="m_pat_name" name="m_pat_name" maxlength="20" minlength="3"   class="form-control">
                            </div>



                            <div class="col-sm-6">
                                <label>E-mail</label>
                                <input type="text"  name="m_email" class="form-control" placeholder="">
                            </div>

                            <div class="col-sm-3">
                                <label>Age</label>
                                <input type="text" name="m_age" data-masked-input="999"  maxlength="3" minlength="1"  class="form-control">
                            </div>

                            <div class="col-sm-3">
                                <label>DOB</label>
                                <input type="text" name="m_dob" data-masked-input="99-99-9999" placeholder="DD-MM-YYYY"  maxlength="10" class="form-control">
                            </div>

                            <div class="col-sm-3">
                                <label>Gender</label>
                                <select name="m_gender" class="form-control" required="required">
                                    <option value="">Select Gender</option>
                                    <option value="MALE">Male</option>
                                    <option value="FEMALE">Female</option>
                                </select>

                            </div>





                            <div class="col-sm-3">

                                <label>Blood Group</label>
                                <select name="m_blood_group" class="form-control">
                                    <option value="N/A">N/A</option>
                                    <option value="O+">O+</option>
                                    <option value="A+">A+</option>
                                    <option value="B+">B+</option>
                                    <option value="AB+">AB+</option>
                                    <option value="O-">O-</option>
                                    <option value="A-">A-</option>
                                    <option value="B-">B-</option>
                                    <option value="AB-">AB-</option>
                                </select>

                            </div>



                            <div class="col-sm-6">
                                <label>Marital Status</label>
                                <select name="m_marital_staus" class="form-control">
                                    <option value="">Select Status</option>
                                    <option value="MARRIED">Married</option>
                                    <option value="UNMARRIED">Unmerried</option>
                                </select>

                            </div>









                            <div class="col-sm-6">
                                <label>Parent Name</label>
                                <input type="text"  name="m_par_name" class="form-control" placeholder="">
                            </div>

                            <div class="col-sm-6">
                                <label>Parent Mobile</label>
                                <input type="text"   name="m_par_mobile" data-masked-input="9999999999"  maxlength="10" minlength="10" class="form-control" placeholder="">

                            </div>
                            <div class="col-sm-6">
                                <label>Refer By Name</label>
                                <input type="text"  name="m_ref_name" class="form-control" placeholder="">
                            </div>

                            <div class="col-sm-6">
                                <label>Refer By Mobile</label>
                                <input type="text"  name="m_ref_mobile" data-masked-input="9999999999"  maxlength="10" minlength="10" class="form-control" placeholder="">
                            </div>

                            <div class="col-sm-12">
                                <label>Patient Address</label>
                                <input type="text"  name="m_address" class="form-control" placeholder="">
                            </div>


                            <div class="col-sm-6">
                                <label>Reason Of Appointment</label>
                                <input type="text"  name="m_roa" class="form-control" placeholder="">
                            </div>

                            <div class="col-sm-6">
                                <label>Remark</label>
                                <input type="text"  name="m_remark" class="form-control" placeholder="">
                            </div>

                            <div class="col-sm-6">
                                <label>Select Appointment Address</label>
                                <?php echo $this->Form->input('m_address_id',array('id'=>'m_address_id','type'=>'select','label'=>false,'options'=>$address_list,'class'=>'form-control')); ?>
                            </div>
                            <div class="col-sm-6">
                                <label>Select Service</label>
                                <?php echo $this->Form->input('m_service_id',array('id'=>'m_service_id','type'=>'select','label'=>false,'options'=>$service_list,'class'=>'form-control')); ?>
                            </div>



                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <input type="hidden" name="m_pi" />
                    <input type="hidden" name="m_pt" />
                    <input type="hidden" name="m_di" value="<?php echo base64_encode($doctor['id']); ?>" />
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Cancel</button>
                    <button type="reset" class="btn btn-warning"><i class="fa fa-refresh"></i> Reset</button>
                    <button type="submit" class="btn btn-success m_save"><i class="fa fa-save"></i> Save</button>

                </div>
            </div>
        </form>
    </div>
    <script>
        $(function () {
            var patient = "<?php echo base64_encode(json_encode($all_patient)); ?>";
            patient  = atob(patient);
            var mobile_option = {
                data: JSON.parse(patient),
                getValue: "mobile",
                list:{
                    onChooseEvent: function() {
                        var index = $("#modal_mobile").getSelectedItemIndex();
                        var data = $("#modal_mobile").getSelectedItemData();
                        load_detail(data,'loading_mobile');
                    },match: {
                        enabled: true
                    },maxNumberOfElements: 10,
                },
                template: {
                    type: "description",
                    fields: {
                        description: "patient_name"
                    }
                }
            };
            $("#modal_mobile").easyAutocomplete(mobile_option);
            var name_option = {
                data: JSON.parse(patient),
                getValue: "patient_name",
                list:{
                    onChooseEvent: function() {
                        var index = $("#m_pat_name").getSelectedItemIndex();
                        var data = $("#m_pat_name").getSelectedItemData();
                        load_detail(data,'loading_name');
                    },match: {
                        enabled: true
                    },maxNumberOfElements: 10,
                },
                template: {
                    type: "description",
                    fields: {
                        description: "mobile"
                    }
                }
            };
            $("#m_pat_name").easyAutocomplete(name_option);

            function load_detail(data,loader){
                var pi = btoa(data.patient_id);
                var pt = data.patient_type;
                $.ajax({
                    type:'POST',
                    url: baseUrl+"prescription/get_patient_detail",
                    data:{pi:pi,pt:pt},
                    beforeSend:function(){
                        $("."+loader).fadeIn();
                    },
                    success:function(response){
                        response = JSON.parse(response);
                        $("."+loader).fadeOut();
                        if(response.status == 1){

                            response = response.data.detail;
                            $("[name='m_pat_name']").val(response.patient_name);
                            $("[name='m_pat_mobile']").val(response.mobile);
                            $("[name='m_email']").val(response.email);
                            $("[name='m_age']").val(response.age);
                            $("[name='m_gender']").val(response.gender);
                            $("[name='m_dob']").val(response.dob);
                            $("[name='m_marital_staus']").val(response.marital_status);
                            var blood_group = (response.blood_group =="")?"N/A":response.blood_group;
                            $("[name='m_blood_group']").val(blood_group);
                            $("[name='m_par_name']").val(response.parents_name);
                            $("[name='m_par_mobile']").val(response.parents_mobile);
                            $("[name='m_address']").val(response.address);
                            $("[name='m_pi']").val(pi);
                            $("[name='m_pt']").val(pt);
                        }
                    },
                    error: function(data){
                        $btn.button('reset');
                        flash("Patient Detail","Sorry something went wrong on server.", "danger",'center');
                    }
                });
            }




            $(document).off('submit','#booking_frm');
            $(document).on('submit','#booking_frm',function (e) {
                e.preventDefault();
                var $btn = $(".m_save");
                $.ajax({
                    type:'POST',
                    url: baseUrl+"prescription/book_new_appointment",
                    data:$('#booking_frm').serialize(),
                    beforeSend:function(){
                        $btn.button('loading').html('Saving...');
                    },
                    success:function(response){
                        $btn.button('reset');
                        response = JSON.parse(response);
                        if(response.status == 1){
                            flash("Add Patient",response.message, "success",'center');
                            $("#addNewAppointment").modal('hide');
                            $( ".patient_list_ul" ).prepend( response.data.li );
                            $( ".patient_list_ul li a:first" ).trigger( 'click' );
                        }else{
                            flash("Add Patient",response.message, "warning",'center');
                        }
                    },
                    error: function(data){
                        $btn.button('reset');
                        flash("Add Patient","Sorry something went wrong on server.", "danger",'center');

                    }
                });
            });


        })
    </script>
</div>