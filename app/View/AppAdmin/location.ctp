<?php
$login = $this->Session->read('Auth.User');
?>

<div class="Inner-banner">
    <div class="container">
        <div class="row">
            <!--  SLIDER IMAGE -->
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Location</h2> </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 pull-right create-btn-box">
            </div>
        </div>
    </div>
</div>

<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">
                <!--box 1 -->
                <!--box 1 -->
                <div class="middle-block">
                    <!-- Heading -->
                    <?php echo $this->element('app_admin_leftsidebar'); ?>
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 custom_form_box">

                        <?php echo $this->element('message'); ?>


                        <div class="form-group row">
                            <div class="col-sm-12">

                                <div class="table table-responsive">
                                    <?php if(!empty($list)){ ?>
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>City</th>
                                            <th>Address</th>
                                        
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($list as $key => $list){ ?>
                                            <tr>
                                                <td><?php echo $key+1; ?></td>
                                                <td><?php echo $list['AppAddressLocation']['city']; ?></td>
                                                <td><?php echo $list['AppAddressLocation']['address']; ?></td>

                                                <td>
                                                    <div class="action_icon">
                                                        <?php
                                                        echo $this->Html->link('', 'javascript:void(0);',
                                                            array('class' => 'fa fa-edit edit_loc', 'data-id'=>base64_encode($list['AppAddressLocation']['id']),'data-city'=>$list['AppAddressLocation']['city'],'data-gaddress'=>$list['AppAddressLocation']['gaddress'],'data-address'=>$list['AppAddressLocation']['address'],'data-latitude'=>$list['AppAddressLocation']['latitude'],'data-longitude'=>$list['AppAddressLocation']['longitude'],'data-location_link'=>$list['AppAddressLocation']['location_link'], 'title' => 'View Channel'));
                                                        ?>
                                                    </div>
                                                    <div class="action_icon">
                                                        <?php
                                                        echo $this->Html->link('','javascript:void(0);',
                                                            array('class' => 'fa fa-trash delete_address', 'data-id'=>base64_encode($list['AppAddressLocation']['id']), 'title' => 'Delete location'));
                                                        ?>
                                                    </div>

                                                </td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>

                                </div>
                                <?php }else{ ?>
                                    <div class="no_data">
                                        <h2>You have not add location.</h2>
                                    </div>
                                <?php } ?>
                            <a href="javascript:void(0);" class="clear_btn">Clear Form</a>
                            </div>

                        </div>

                        <div class="form-group row add_edit_form" >
                            <?php echo $this->Form->create('AppAddressLocation',array('method'=>'post','class'=>'form-horizontal','id'=>'sub_frm')); ?>
                            <?php echo $this->Form->input('flag',array('type'=>'hidden','id'=>'flag','class'=>'form-control cnt')); ?>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <div class="col-sm-6">
                                        <label>Enter Location</label>
                                        <?php echo $this->Form->input('city',array('type'=>'text','placeholder'=>'Enter location','label'=>false,'id'=>'city','class'=>'form-control cnt')); ?>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Select Location</label>
                                        <?php echo $this->Form->input('gaddress',array('type'=>'text','label'=>false,'id'=>'gaddress','class'=>'form-control cnt')); ?>
                                    </div>
                                </div>


                                <div class="form-group" style="display: none;">
                                    <div class="col-sm-6">
                                        <label>Select latitude</label>
                                        <?php echo $this->Form->input('latitude',array('type'=>'hidden','placeholder'=>'','label'=>false,'id'=>'latitude','class'=>'form-control cnt')); ?>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Select latitude</label>
                                        <?php echo $this->Form->input('longitude',array('type'=>'hidden','placeholder'=>'','label'=>false,'id'=>'longitude','class'=>'form-control cnt')); ?>
                                    </div>
                                </div>



                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <label>Your address</label>
                                        <?php echo $this->Form->input('address',array('type'=>'textarea','placeholder'=>'Channel description','label'=>false,'id'=>'address','class'=>'form-control cnt')); ?>                                </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <label>Address Location Link</label>
                                        <?php echo $this->Form->input('location_link',array('type'=>'text','placeholder'=>'Enter address location','label'=>false,'id'=>'location_link','class'=>'form-control cnt')); ?>
                                    </div>
                                </div>



                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <div class="col-sm-4">
                                            <?php echo $this->Form->submit('Save',array('class'=>'Btn-typ5','id'=>'signup')); ?>
                                        </div>

                                    </div>
                                </div>
                                </div>

                        </div>
                        <?php echo $this->Form->end(); ?>
                        </div>



                        <div class="clear"></div>



                    </div>






                </div>
                <!-- box 1 -->


            </div>
            <!--box 2 -->

        </div>
    </div>
</div>





<script>
    $(document).ready(function(){
        $(".channel_tap a").removeClass('active');
        $("#v_app_channel_list").addClass('active');


        CKEDITOR.replace( 'address' );
        CKEDITOR.env.isCompatible = true;

        $(document).on('click','.edit_loc',function(e){
            $("#city").val($(this).attr('data-city'));
            $("#flag").val($(this).attr('data-id'));
            CKEDITOR.instances['address'].setData($(this).attr('data-address'))
            $("#gaddress").val($(this).attr('data-gaddress'));
            $("#latitude").val($(this).attr('data-latitude'));
            $("#longitude").val($(this).attr('data-longitude'));
            $("#location_link").val($(this).attr('data-location_link'));
        });

        $(document).on('click','.clear_btn',function(e){
            var emp = "";
            $("#city").val(emp);
            $("#flag").val(emp);
            $("#address").val(emp);
            $("#gaddress").val(emp);
            $("#latitude").val(emp);
            $("#longitude").val(emp);
            $("#location_link").val(emp);
        });


        $(document).on('click','.delete_address',function(e){
            var rowID = $(this).attr('data-id');
            var curObj = $(this);
            var thisButton = $(this);
            $.ajax({
                url: baseurl+'/app_admin/delete_location',
                data:{data_id:rowID},
                type:'POST',
                beforeSend:function(){

                    $(thisButton).button('loading').html("").removeClass("fa fa-trash").addClass("fa fa-spinner fa-pulse");
                },
                success: function(result){

                    $(thisButton).button('reset');
                    var result = JSON.parse(result);
                    if(result.status == 1){
                       $(curObj).closest("tr").fadeOut('slow');
                    }else{
                        alert('Sorry, Location couldd not delete!');
                    }
                },
                error:function (error) {
                    $(thisButton).button('reset');
                    alert("Somthing went wrong.")
                }
            });
        });


    });
</script>


<script>
    function initialize() {
        var input = document.getElementById('gaddress');
        var autocomplete = new google.maps.places.Autocomplete(input);
        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            var place = autocomplete.getPlace();
            $('#latitude').val(place.geometry.location.lat());
            $('#longitude').val(place.geometry.location.lng());
            CKEDITOR.instances['address'].setData(input.value)

        });

    }
    google.maps.event.addDomListener(window, 'load', initialize);

   /* $(document).ready(function () {
        $(document).on('change','#address',function () {
            $('#latitude').val('');
            $('#longitude').val('');
        });
    });*/

</script>







