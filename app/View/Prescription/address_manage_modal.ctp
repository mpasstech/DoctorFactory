<div class="modal fade" id="addressManageModal" role="dialog" style="z-index: 9999;">

    <?php
    echo $this->Html->script(array( 'comman.js'),array('fullBase' => true));
    $country = $this->AppAdmin->getAllCountryList();

    ?>

    <div class="modal-dialog modal-sm">
        <form id="address_manage_form">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?php echo empty($data)?'Add Address':'Edit Address'; ?></h4>
                    <input type="hidden" name="at" value="<?php echo empty($data)?'ADD':'UPDATE'; ?>">
                    <?php if(!empty($data)){ ?>
                        <input type="hidden" name="ai" value="<?php echo base64_encode($data['id']); ?>">
                    <?php } ?>

                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group">

                            <div class="col-sm-12">
                                <label style="width: 100%;">Clinic Name </label>
                                <input type="text"  autocomplete="off"  value="<?php echo @$data['clinic_name']; ?>" autocomplete="off" name="clinic_name" maxlength="150" oninvalid = 'this.setCustomValidity("Please enter  clinic name")' oninput = 'setCustomValidity("")' required="required" class="form-control">
                            </div>


                            <div class="col-sm-12">
                                <label style="width: 100%;">Address </label>
                                <input type="textarea" row="3" value="<?php echo @$data['address']; ?>"  autocomplete="off" name="address" maxlength="150" oninvalid = 'this.setCustomValidity("Please enter  address")' oninput = 'setCustomValidity("")' required="required" class="form-control">
                            </div>

                            <div class="col-sm-12">
                                <?php echo $this->Form->input('country_name', array('autocomplete'=>"off",'value'=>@$data['country_name'],'type'=>'text','placeholder' => '', 'label' => 'Country', 'class' => 'form-control country')); ?>
                                <input type="hidden"  value="<?php echo @$data['country_id']; ?>"  name="country_id">

                            </div>

                            <div class="col-sm-12">
                                <?php echo $this->Form->input('state_name', array('autocomplete'=>"off",'value'=>@$data['state_name'],'type'=>'text','placeholder' => '', 'label' => 'State', 'class' => 'form-control state')); ?>
                                <input type="hidden"  value="<?php echo @$data['state_id']; ?>"  name="state_id">
                            </div>

                            <div class="col-sm-12">
                                <?php echo $this->Form->input('city_name', array('autocomplete'=>"off",'value'=>@$data['city_name'],'type'=>'text','placeholder' => '', 'label' => 'City', 'class' => 'form-control city','oninvalid'=>'this.setCustomValidity("Please enter  city")','oninput'=>'setCustomValidity("")','required'=>'required')); ?>
                                <input type="hidden"  value="<?php echo @$data['city_id']; ?>"  name="city_id">
                            </div>

                            <div class="col-sm-12">
                                <label style="width: 100%;">Pincode</label>
                                <input type="text" autocomplete="off" value="<?php echo @$data['pincode']; ?>"  autocomplete="off" maxlength="15" minlength="1" name="pincode" class="form-control">
                            </div>

                            <input type="hidden"  value="<?php echo @$data['latitude']; ?>"  name="latitude">
                            <input type="hidden"  value="<?php echo @$data['longitude']; ?>"  name="longitude">



                        </div>
                    </div>
               </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-xs" data-dismiss="modal"><i class="fa fa-close"></i> Cancel</button>
                    <button type="reset" class="btn btn-warning btn-xs"><i class="fa fa-refresh"></i> Reset</button>
                    <button type="submit" class="btn btn-success btn-xs save_address_btn"><i class="fa fa-save"></i> Save</button>
                </div>
            </div>
        </form>
    </div>

    <script type="text/javascript">
        $(function () {



            $("input[type='text'], input[type='textarea']").attr('autocomplete','off');

            var country = "<?php echo base64_encode(json_encode($country)); ?>";
            country  = atob(country);
            var country_option = {
                data: JSON.parse(country),
                getValue: "label",
                list:{
                    onChooseEvent: function() {
                        var data = $(".country").getSelectedItemData();
                        $("input[name='country_id']").val(data.value);
                        $("input[name='data[state_name]'], input[name='data[city_name]'], input[name='city_id'], input[name='state_id']").val('');

                        if(data.value){
                            load_state(data.value)
                        }



                    },match: {
                        enabled: true
                    },maxNumberOfElements: 10,
                }
            };
            $(".country").easyAutocomplete(country_option);


            if($("input[name='country_id']").val()!=''){
                load_state($("input[name='country_id']").val());
            }

            if($("input[name='state_id']").val()!=''){
                load_city($("input[name='state_id']").val());
            }
            function load_state(country_id){
                $.ajax({
                    type:'POST',
                    url: "<?php echo Router::url('/',true);?>app_admin/get_state_list_json",
                    data:{country_id:country_id},
                    beforeSend:function(){

                    },
                    success:function(data){
                        var state_option = {
                            data: JSON.parse(data),
                            getValue: "name",
                            list:{
                                onChooseEvent: function() {
                                    var data = $(".state").getSelectedItemData();
                                    $("input[name='state_id']").val(data.id);
                                    $("input[name='data[city_name]'], input[name='city_id']").val('');
                                    if(data.id){
                                        load_city(data.id);
                                    }
                                },match: {
                                    enabled: true
                                },maxNumberOfElements: 10,
                            }
                        };
                        $(".state").easyAutocomplete(state_option);
                    },
                    error: function(data){

                    }
                });
            }
            function load_city(state_id){
                $.ajax({
                    type:'POST',
                    url: "<?php echo Router::url('/',true);?>app_admin/get_city_list_json",
                    data:{state_id:state_id},
                    beforeSend:function(){
                    },
                    success:function(data){
                        var state_option = {
                            data: JSON.parse(data),
                            getValue: "name",
                            list:{
                                onChooseEvent: function() {
                                    var data = $(".city").getSelectedItemData();
                                    $("input[name='city_id']").val(data.id);
                                },match: {
                                    enabled: true
                                },maxNumberOfElements: 10,
                            }
                        };
                        $(".city").easyAutocomplete(state_option);
                    },
                    error: function(data){

                    }
                });
            }

            $(document).off('submit','#address_manage_form');
            $(document).on('submit','#address_manage_form',function(e){
                e.preventDefault();
                var $btn = $(this).find('.save_address_btn');
                $.ajax({
                    url: "<?php echo Router::url('/prescription/manage_address',true);?>",
                    type:'POST',
                    data:$(this).serialize(),
                    beforeSend:function(){
                        $btn.button('loading').text('Saving..');
                    },
                    success: function(response){
                        $btn.button('reset');
                        response = JSON.parse(response);
                        if(response.status == 1){
                            flash("Doctor",response.message, "success",'center');
                            $("#addressListModal, #addressManageModal").modal('hide');
                        }else{
                            flash("Warning",response.message, "warning",'center');
                        }
                    },error:function () {
                        $btn.button('reset');
                        flash("Error",'Something went wrong on server.', "danger",'center');
                    }
                });
            });




            <?php if(empty($data)){ ?>
                if ("geolocation" in navigator){
                    navigator.geolocation.getCurrentPosition(function(position){
                        $("input[name='latitude']").val(position.coords.latitude);
                        $("input[name='longitude']").val(position.coords.longitude);
                    });
                }else{
                    flash("Warning","Allow browser to locate location.", "warning",'center');
                }
            <?php } ?>



            function flash(title,message,type,position){
                $.alert(message, {
                    autoClose: true,
                    closeTime: 3000,
                    withTime: false,
                    type: type,
                    position: [position, [-0.42, 0]],
                    title: title,
                    icon: false ,
                    close: true,
                    speed: 'normal',
                    isOnly: true,
                    minTop: 10,
                    animation: false,
                    animShow: 'fadeIn',
                    animHide: 'fadeOut',
                    onShow: function () {
                    },
                    onClose: function () {
                    }
                });
            }

        });
    </script>


</div>
