<?php
$login = $this->Session->read('Auth.User');
?>
<div class="Inner-banner">
    <div class="container">
        <div class="row">
            <!--  SLIDER IMAGE -->
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Edit Sell Item</h2> </div>
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
                    <!--left sidebar-->

                    <?php echo $this->element('app_admin_leftsidebar'); ?>
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">





                        <div class="progress-bar channel_tap">

                            <a id="v_app_channel_list" href="<?php echo Router::url('/app_admin/sell'); ?>"><i class="fa fa-list"></i> Sell Item List</a>
                            <a id="v_add_channel" class='active' href="<?php echo Router::url('/app_admin/add_sell_item'); ?>" ><i class="fa fa-globe"></i> Add Sell Item</a>
                            <a id="v_add_channel" href="<?php echo Router::url('/app_admin/permit_sell'); ?>" ><i class="fa fa-globe"></i> Permit Sell Item</a>

                        </div>
                        <style>
                            .channel_tap a{ width:33% !important; }
                        </style>




                        <div class="Social-login-box payment_bx">

                            <?php echo $this->element('message'); ?>

                            <?php echo $this->Form->create('SellItem',array('type'=>'file','method'=>'post','class'=>'form-horizontal')); ?>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Item Name</label>
                                    <?php echo $this->Form->input('item_name',array('type'=>'text','placeholder'=>'Item Name','label'=>false,'class'=>'form-control cnt','required'=>true)); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Description</label>
                                    <?php echo $this->Form->textarea('description',array('placeholder'=>'Description','label'=>false,'class'=>'form-control cnt','required'=>true)); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Price</label>
                                    <?php echo $this->Form->input('price',array('type'=>'text','placeholder'=>'Price','label'=>false,'class'=>'form-control cnt','required'=>true)); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Negotiable</label>
                                    <?php echo $this->Form->radio('negotiable',array('YES'=>'Yes','NO'=>'No',),array('label'=>false,'required'=>true,'legend'=>false )); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Show My Phone Number</label>
                                    <?php echo $this->Form->radio('show_my_phone_number',array('YES'=>'Yes','NO'=>'No',),array('label'=>false,'required'=>true,'legend'=>false )); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Item Category</label>
                                    <?php echo $this->Form->input('sell_item_category_id',array('type'=>'select','options'=>$sellItemCategory,'empty'=>'Select category','label'=>false,'class'=>'form-control cnt','required'=>true)); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Location</label>
                                    <?php echo $this->Form->input('pick_up_location',array('type'=>'text','placeholder'=>'Location','label'=>false,'id'=>'address','class'=>'form-control cnt','required'=>true)); ?>

                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button type="button" id="selectAddressFromMap"  class='Btn-typ5'>Select Location From Map(Optional)</button>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Latitude</label>
                                    <?php echo $this->Form->input('latitude',array('type'=>'text','placeholder'=>'Latitude','label'=>false,'id'=>'latitude','class'=>'form-control cnt','required'=>true,'readonly'=>'readonly')); ?>

                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Longitude</label>
                                    <?php echo $this->Form->input('longitude',array('type'=>'text','placeholder'=>'Longitude','label'=>false,'id'=>'longitude','class'=>'form-control cnt','required'=>true,'readonly'=>'readonly')); ?>

                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Enable Chat</label>
                                    <?php echo $this->Form->radio('enable_chat',array('YES'=>'Yes','NO'=>'No',),array('label'=>false,'required'=>true,'legend'=>false )); ?>
                                </div>
                            </div>

                     <!--       <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Share On</label>
                                    <?php /*echo $this->Form->input('share_on',array('type'=>'select','options'=>array('SELL_FACTORY'=>'Sell Factory','CHANNEL'=>'Channel'),'empty'=>'Please Select','label'=>false,'id'=>'share_on','class'=>'form-control cnt','required'=>true)); */?>
                                </div>
                            </div>
                            
                            <div class="form-group" id="channelContainer">
                                <div class="col-sm-12">
                                    <label>Chanel For Share</label>
                                    <?php /*echo $this->Form->input('Channel_id',array('type'=>'select','options'=>$channels,'empty'=>'Please Select Channel','id'=>'ChannelId','label'=>false,'class'=>'form-control cnt')); */?>
                                </div>
                            </div>-->

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Show On Mbroadcast</label>
                                    <?php echo $this->Form->radio('show_on_mbroadcast',array('YES'=>'Yes','NO'=>'No',),array('label'=>false,'required'=>true,'legend'=>false )); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-3 pull-right">
                                    <?php echo $this->Form->submit('Save',array('class'=>'Btn-typ5','id'=>'addEvent')); ?>
                                </div>
                            </div>

                            <?php echo $this->Form->end(); ?>

                        </div>
                    </div>
                </div>
                <!-- box 1 -->
            </div>
            <!--box 2 -->
        </div>
    </div>
</div>


<div class="modal fade" id="modalForMap" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Select Address From Map</h4>
            </div>
            <div class="modal-body">
                <div id="map" style="width: 100%; height: 400px;"></div>
            </div>
        </div>
    </div>
</div>
<script>

    $(document).ready(function () {
        $('#share_on').change(function(){
            var shareOn = $(this).val();
            if(shareOn == 'CHANNEL')
            {
             $("#channelContainer").show();
            }
            else
            {
                $("#channelContainer").hide();
                $("#ChannelId").val('');
            }
        });
    });
    
</script>


<script>
    function initialize() {

        var input = document.getElementById('address');
        var autocomplete = new google.maps.places.Autocomplete(input);

        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            var place = autocomplete.getPlace();
            var lat = place.geometry.location.lat();
            var lng = place.geometry.location.lng();
            $('#latitude').val(lat);
            $('#longitude').val(lng);

            var geocoder;
            geocoder = new google.maps.Geocoder();
            var latlng = new google.maps.LatLng(lat, lng);
            geocoder.geocode(
                {'latLng': latlng},
                function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[0]) {
                            var add= results[0].formatted_address ;
                            var  value=add.split(",");
                            count=value.length;
                            city=value[count-3];
                            $("#address").val(city);
                        }
                        else  {
                            alert("address not found");
                        }
                    }
                    else {
                        alert("Geocoder failed due to: " + status);
                    }
                }
            );

        });

    }
    google.maps.event.addDomListener(window, 'load', initialize);

    $(document).ready(function () {
        $(document).on('change','#address',function () {
            $('#latitude').val('');
            $('#longitude').val('');
        });
    });
</script>

<script>
    var  map = '';
    $(document).ready(function () {
        $(document).on('click','#selectAddressFromMap',function () {
            $("#modalForMap").modal('show');
            setTimeout(function () {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var pos = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };
                    map = new google.maps.Map(document.getElementById('map'), {
                        center: pos,
                        zoom: 15
                    });
                    var marker = new google.maps.Marker({
                        position: pos,
                        map: map,
                    });
                    google.maps.event.addListener(map, "click", function (event) {
                        var latitude = event.latLng.lat();
                        var longitude = event.latLng.lng();
                        $("#latitude").val(latitude);
                        $("#longitude").val(longitude);
                        marker.setMap(null);
                        marker = new google.maps.Marker({
                            position: event.latLng,
                            map: map,
                        });
                        map.setCenter(event.latLng);
                        var geocoder = new google.maps.Geocoder();
                        geocoder.geocode({'latLng':event.latLng}, function(results, status) {
                            if (status == google.maps.GeocoderStatus.OK) {



                                var add= results[0].formatted_address ;
                                var  value=add.split(",");
                                count=value.length;
                                city=value[count-3];
                                $("#address").val(city);


                            }
                            else
                            {
                                alert('Could not get address');
                            }
                        });
                    });

                }, function() {
                    alert('can not get your position');
                });

            },1000)
        });
    });
</script>

<style>
    .bootstrap-tagsinput > input{
        width: 100%!important;
       /* padding-top: 5px !important; */
    }
    #channelContainer{
        display: none;
    }
</style>