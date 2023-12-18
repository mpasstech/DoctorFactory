<?php
$login = $this->Session->read('Auth.User');
?>
<div class="Inner-banner">
    <div class="container">
        <div class="row">
            <!--  SLIDER IMAGE -->
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Add Event</h2> </div>
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

                    <?php echo $this->element('admin_leftsidebar'); ?>
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                        <?php echo $this->element('admin_inner_tab_event'); ?>


                        <div class="Social-login-box payment_bx">

                            <?php echo $this->element('message'); ?>

                            <?php echo $this->Form->create('Event',array('type'=>'file','method'=>'post','class'=>'form-horizontal')); ?>


                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Event Title</label>
                                    <?php echo $this->Form->input('title',array('type'=>'text','placeholder'=>'Event Title','label'=>false,'id'=>'title','class'=>'form-control cnt','required'=>true)); ?>

                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Event Description</label>
                                    <?php echo $this->Form->input('description',array('type'=>'textarea','placeholder'=>'Event description','label'=>false,'id'=>'description','class'=>'form-control cnt','required'=>true)); ?>                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Address</label>
                                    <?php echo $this->Form->input('address',array('type'=>'text','placeholder'=>'Address','label'=>false,'id'=>'address','class'=>'form-control cnt','required'=>true)); ?>

                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button type="button" id="selectAddressFromMap"  class='Btn-typ5'>Select Address From Map(Optional)</button>
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
                                    <label>Venue</label>
                                    <?php echo $this->Form->input('venue',array('type'=>'text','placeholder'=>'Venue','label'=>false,'id'=>'venue','class'=>'form-control cnt','required'=>true)); ?>

                                </div>
                            </div>


                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Tags (comma separated)</label>
                                    <?php echo $this->Form->input('tags',array('type'=>'text','placeholder'=>'','label'=>false,'id'=>'tags','class'=>'form-control cnt','required'=>true)); ?>
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Contact Phone</label>
                                    <?php echo $this->Form->input('contact_phone',array('type'=>'text','placeholder'=>'Contact phone','label'=>false,'id'=>'contact_phone','class'=>'form-control cnt')); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Start Date</label>
                                    <?php echo $this->Form->input('start_date',array('type'=>'text','placeholder'=>'Start Date','label'=>false,'id'=>'start_date','class'=>'form-control cnt','required'=>true)); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Start Time</label>
                                    <?php echo $this->Form->input('start_time',array('type'=>'text','placeholder'=>'Start Time','label'=>false,'id'=>'start_time','class'=>'form-control cnt','required'=>true)); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>End Date</label>
                                    <?php echo $this->Form->input('end_date',array('type'=>'text','placeholder'=>'End Date','label'=>false,'id'=>'end_date','class'=>'form-control cnt','required'=>true)); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>End Time</label>
                                    <?php echo $this->Form->input('end_time',array('type'=>'text','placeholder'=>'End Time','label'=>false,'id'=>'end_time','class'=>'form-control cnt','required'=>true)); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Event Category</label>
                                    <?php echo $this->Form->input('event_category_id',array('type'=>'select','options'=>$eventCategory,'empty'=>'Select event category','label'=>false,'id'=>'event_category_id','class'=>'form-control cnt','required'=>true)); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Share On</label>
                                    <?php echo $this->Form->input('share_on',array('type'=>'select','options'=>array('EVENT_FACTORY'=>'Event Factory','CHANNEL'=>'Channel'),'empty'=>'Please Select','label'=>false,'id'=>'share_on','class'=>'form-control cnt','required'=>true)); ?>
                                </div>
                            </div>
                            
                            <div class="form-group" id="channelContainer">
                                <div class="col-sm-12">
                                    <label>Chanel For Share</label>
                                    <?php echo $this->Form->input('Channel_id',array('type'=>'select','options'=>$channels,'empty'=>'Please Select Channel','id'=>'ChannelId','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Show On Mbroadcast</label>
                                    <?php echo $this->Form->input('show_on_mbroadcast',array('type'=>'select','options'=>array('YES'=>'Yes','NO'=>'No'),'empty'=>'Please Select','label'=>false,'id'=>'show_on_mbroadcast','class'=>'form-control cnt','required'=>true)); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-3 pull-right">
                                    <?php echo $this->Form->submit('Add Event',array('class'=>'Btn-typ5','id'=>'addEvent')); ?>
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
    function initialize() {

        var input = document.getElementById('address');
        var autocomplete = new google.maps.places.Autocomplete(input);

        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            var place = autocomplete.getPlace();
           $('#latitude').val(place.geometry.location.lat());
            $('#longitude').val(place.geometry.location.lng());

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
    $(document).ready(function(){
        var startSDate = new Date();
        $('#start_date').datepicker({
            startDate: new Date(),
            autoclose: true,
            format: 'yyyy-mm-dd'
        })
            .on('changeDate', function(selected){
                startSDate = new Date(selected.date.valueOf());
                startSDate.setDate(startSDate.getDate(new Date(selected.date.valueOf())));
                $('#end_date').datepicker('setStartDate', startSDate).datepicker('setMinDate', startSDate);
                $('#end_date').val('');
            });
        $('#end_date')
            .datepicker({
                startDate: startSDate,
                minDate: startSDate,
                autoclose: true,
                format: 'yyyy-mm-dd'
            });
    });

    $(document).ready(function () {
        $('#end_time').timepicker({'timeFormat': 'H:i:s'});
        $('#start_time').timepicker({'timeFormat': 'H:i:s'});
        $('#end_time').keydown(function () { return false; });
        $('#start_time').keydown(function () { return false; });
    });

    var input = $('#tags').tagsinput({
        maxTags: 10,
        maxChars: 15,
        trimValue: true,
        allowDuplicates: false
    });
    $('.bootstrap-tagsinput').addClass('form-control cnt');
    
    
    
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
                                    if(results[0])
                                    {
                                        $("#address").val(results[0].formatted_address);
                                    }
                                    else
                                    {
                                        alert('Could not get address');
                                    }
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