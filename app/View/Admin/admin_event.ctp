<div class="Inner-banner">
    <div class="container">
        <div class="row">

            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Event List</h2> </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 pull-right create-btn-box">
            </div>
        </div>
    </div>
</div>
<section class="Home-section-2">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">
                <div class="middle-block">
                    <?php echo $this->element('admin_leftsidebar'); ?>
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                        <?php echo $this->element('admin_inner_tab_event'); ?>
                        <div class="Social-login-box payment_bx">
                            <?php echo $this->Form->create('Search',array('type'=>'get','url'=>array('controller'=>'admin','action' => 'search_event'),'admin'=>true)); ?>
                            <div class="form-group">
                                <div class="col-sm-4">
                                    <?php echo $this->Form->input('title', array('type' => 'text', 'placeholder' => 'Insert title', 'label' => 'Search by title', 'class' => 'form-control')); ?>
                                </div>
                                <div class="col-sm-4">
                                    <?php echo $this->Form->input('category', array('type' => 'select', 'empty' => 'Please select', 'options'=>$eventCategory, 'label' => 'Search by  category', 'class' => 'form-control')); ?>
                                </div>
                                <div class="col-sm-4">
                                    <?php echo $this->Form->input('date', array('type' => 'text','placeholder' => 'Select date','label' => 'Search by date', 'class' => 'form-control','readonly'=>'readonly')); ?>
                                </div>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->label('&nbsp;'); ?>
                                    <?php echo $this->Form->submit('Search',array('class'=>'Btn-typ3','id'=>'date')); ?>
                                </div>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->label('&nbsp;'); ?>
                                    <div class="submit">
                                        <a href="<?php echo $this->Html->url(array('controller'=>'admin','action'=>'event')) ?>"><button type="button" class="Btn-typ3" >Reset</button></a>
                                    </div>
                                </div>
                            </div>
                            <div class="clear"></div>



                            <?php echo $this->Form->end(); ?>
                            <?php echo $this->element('message'); ?>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <div class="table-responsive">
                                        <?php if(!empty($event)){ ?>
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Title</th>
                                                <th>Start at</th>
                                                <th>End at</th>
                                                <th>Category</th>
                                                <th>Status</th>
                                                <th>Publish</th>
                                                <th>Options</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $login = $this->Session->read('Auth.User');
                                            $num = 1;
                                            foreach ($event as $key => $value){
                                                ?>
                                                <tr>
                                                    <td><?php echo $num++; ?></td>
                                                    <td><?php echo $value['Event']['title']; ?></td>
                                                    <td><?php echo date("d-M-Y H:i:s",strtotime($value['Event']['start_datetime'])); ?></td>
                                                    <td><?php echo date("d-M-Y H:i:s",strtotime($value['Event']['end_datetime'])); ?></td>
                                                    <td><?php echo $value['EventCategory']['title']; ?></td>
                                                    <td>
                                                        <button type="button" id="changeEventStatus" class="btn btn-primary btn-xs"  event-id="<?php echo $value['Event']['id']; ?>" ><?php echo $value['Event']['status']; ?></button>
                                                    </td>
                                                    <td>
                                                        <button type="button" id="publishEvent" class="btn btn-primary btn-xs"  event-id="<?php echo $value['Event']['id']; ?>" ><?php echo $value['Event']['publish_status']; ?></button>
                                                    </td>
                                                    <td>
                                                        <div class="action_icon" style="display:flex;">
                                                            <button type="button" id="getOptionEvent" class="btn btn-primary btn-xs"  event-id="<?php echo $value['Event']['id']; ?>" >OPTIONS</button>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="action_icon" style="display:flex;">
                                                            <button type="button" id="viewEvent" class="btn btn-primary btn-xs"  event-id="<?php echo $value['Event']['id']; ?>" ><i class="fa fa-eye fa-2x"></i></button>
                                                            &nbsp;
                                                            <button type="button" id="viewEventResponse" class="btn btn-primary btn-xs"  event-id="<?php echo $value['Event']['id']; ?>" ><i class="fa fa-cog fa-2x"></i></button>
                                                            &nbsp;
                                                            <a href="<?php echo $this->Html->url(array('controller'=>'admin','action'=>'edit_event',base64_encode($value['Event']['id']))) ?>" >
                                                                <button type="button" id="editEvent" class="btn btn-primary btn-xs" ><i class="fa fa-edit fa-2x"></i></button>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                        </table>
                                        <?php echo $this->element('paginator'); ?>
                                    </div>
                                </div>
                            </div>
                        <?php }else{ ?>
                            <div class="no_data">
                                <h2>No Event..!</h2>
                            </div>
                        <?php } ?>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



<div class="modal fade" id="myModalView" role="dialog">

    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">View Event</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table" id="viewEventTable">
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <div id="map" style="width: 100%; height: 200px;"></div>
            </div>
        </div>
    </div>

</div>


<div class="modal fade" id="myModalViewResponse" role="dialog">

    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Event Response</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table" id="viewEventResponseTable">
                    </table>
                </div>
            </div>
            <!--div class="modal-footer">
                <a href="<?php echo $this->Html->url(array('controller'=>'admin','action'=>'media_event',base64_encode($value['Event']['id']))) ?>" id="mediaDetail" >
                    <button type="button" class="btn btn-primary btn-xs" >View Detailed Response</button>
                </a>
            </div-->

        </div>
    </div>

</div>




<div class="modal fade" id="modalViewEventOption" role="dialog">

    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Event Options</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th>MEDIA</th>
                            <th>ORGANIZER</th>
                            <th>SPEAKER</th>
                            <th>AGENDA</th>
                            <th>SHOW</th>
                            <th>TICKET</th>
                        </tr>
                        <tr>
                            <th>
                                <a href="" id="mediaLink" >
                                    <button type="button" class="btn btn-primary btn-xs" >MEDIA</button>
                                </a>
                            </th>
                            <th>
                                <a href="" id="organizerLink" >
                                    <button type="button" class="btn btn-primary btn-xs" >ORGANIZER</button>
                                </a>
                            </th>
                            <th>
                                <a href="" id="speakerLink" >
                                    <button type="button" class="btn btn-primary btn-xs" >SPEAKER</button>
                                </a>
                            </th>
                            <th>
                                <a href="" id="agendaLink" >
                                    <button type="button" class="btn btn-primary btn-xs" >AGENDA</button>
                                </a>
                            </th>
                            <th>
                                <a href="" id="showLink" >
                                    <button type="button" class="btn btn-primary btn-xs" >SHOW</button>
                                </a>
                            </th>
                            <th>
                                <a href="" id="ticketLink" >
                                    <button type="button" class="btn btn-primary btn-xs" >TICKET</button>
                                </a>
                            </th>
                        </tr>

                        <tr>
                            <th></th>
                            <th><button type="button" class="btn btn-primary btn-xs allow" row-id="" field-id="enable_organizer" >ORGANIZER</button></th>
                            <th><button type="button" class="btn btn-primary btn-xs allow" row-id="" field-id="enable_speaker" >SPEAKER</button></th>
                            <th><button type="button" class="btn btn-primary btn-xs allow" row-id="" field-id="enable_agenda" >AGENDA</button></th>
                            <th><button type="button" class="btn btn-primary btn-xs allow" row-id="" field-id="enable_show" >SHOW</button></th>
                            <th><button type="button" class="btn btn-primary btn-xs allow" row-id="" field-id="enable_ticket" >TICKET</button></th>
                        </tr>


                    </table>
                </div>
            </div>

        </div>
    </div>

</div>
<script>
    $(document).ready(function(){
        $(document).on('click','#changeEventStatus',function(e){
            var eventID = $(this).attr('event-id');
            var thisButton = $(this);
            $.ajax({
                url: baseurl+'/admin/admin/change_event_status',
                data:{eventID:eventID},
                type:'POST',
                beforeSend:function(){
                    $(thisButton).button('loading').html('<i class="fa fa-spinner fa-pulse fa-2x fa-fw">');
                },
                success: function(result){
                    $(thisButton).button('reset');
                    var result = JSON.parse(result);
                    if(result.status == 1)
                    {
                        $(thisButton).text(result.text);
                    }
                    else
                    {
                        alert('Sorry, Could not change status!');
                    }
                }
            });
        });
        $(document).on('click','#publishEvent',function(e){
            var eventID = $(this).attr('event-id');
            var thisButton = $(this);
            $.ajax({
                url: baseurl+'/admin/admin/change_event_publish_status',
                data:{eventID:eventID},
                type:'POST',
                beforeSend:function(){
                    $(thisButton).button('loading').html('<i class="fa fa-spinner fa-pulse fa-2x fa-fw">');
                },
                success: function(result){
                    $(thisButton).button('reset');
                    var result = JSON.parse(result);
                    if(result.status == 1)
                    {
                        $(thisButton).text(result.text);
                    }
                    else if(result.status == 0)
                    {
                        $(thisButton).text(result.text);
                        alert('Already published!');
                    }
                    else
                    {
                        $(thisButton).text(result.text);
                        alert('Event is not active!');
                    }
                }
            });
        });
        var map = '';
        $(document).on('click','#viewEvent',function () {
            var eventID = $(this).attr('event-id');
            var thisButton = $(this);
            $.ajax({
                url: baseurl+'/admin/admin/view_event',
                data:{eventID:eventID},
                type:'POST',
                beforeSend:function(){
                    $(thisButton).button('loading').html('<i class="fa fa-spinner fa-pulse fa-2x fa-fw">');
                },
                success: function(result){
                    $(thisButton).button('reset');
                    var result = JSON.parse(result);
                    if(result.status == 1)
                    {
                        $("#viewEventTable").html(result.html);
                        $("#myModalView").modal('show');

                        if(map == '')
                        {
                            map = new google.maps.Map(document.getElementById('map'), {
                                center: new google.maps.LatLng(parseFloat(result.lat), parseFloat(result.lng)),
                                zoom: 15
                            });
                            var marker = new google.maps.Marker({
                                position: new google.maps.LatLng(parseFloat(result.lat), parseFloat(result.lng)),
                                map: map,
                            });
                            map.setCenter(marker.getPosition());
                        }
                        else
                        {
                            var marker = new google.maps.Marker({
                                position: new google.maps.LatLng(parseFloat(result.lat), parseFloat(result.lng)),
                                map: map,
                            });
                            map.setCenter(marker.getPosition());
                        }
                    }
                    else
                    {
                        alert('Sorry, Could not find event!');
                    }
                }
            });
        });
        $(document).on('click','#viewEventResponse',function () {
            var eventID = $(this).attr('event-id');
            var thisButton = $(this);
            $.ajax({
                url: baseurl+'/admin/admin/view_event_result',
                data:{eventID:eventID},
                type:'POST',
                beforeSend:function(){
                    $(thisButton).button('loading').html('<i class="fa fa-spinner fa-pulse fa-2x fa-fw">');
                },
                success: function(result){
                    $(thisButton).button('reset');
                    var result = JSON.parse(result);
                    if(result.status == 1)
                    {
                        $("#viewEventResponseTable").html(result.html);
                        $("#myModalViewResponse").modal('show');
                        var link = baseurl+'admin/admin/view_event_result_detail/'+btoa(eventID);
                     //   $("#mediaDetail").attr('href',link);
                    }
                    else
                    {
                        alert('Sorry, Could not find any response!');
                    }
                }
            });
        });
        $("#SearchDate").datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd'
        });
        $(document).on('click','#getOptionEvent',function () {
            var eventID = $(this).attr('event-id');
            var thisButton = $(this);
            $.ajax({
                url: baseurl+'/admin/admin/get_event_allow_detail',
                data:{eventID:eventID},
                type:'POST',
                beforeSend:function(){
                    $(thisButton).button('loading').html('<i class="fa fa-spinner fa-pulse fa-2x fa-fw">');
                },
                success: function(result){
                    $(thisButton).button('reset');
                    var result = JSON.parse(result);
                    if(result.status == 1)
                    {
                        $("[field-id='enable_agenda']").text(result.data.enable_agenda);
                        $("[field-id='enable_organizer']").text(result.data.enable_organizer);
                        $("[field-id='enable_show']").text(result.data.enable_show);
                        $("[field-id='enable_speaker']").text(result.data.enable_speaker);
                        $("[field-id='enable_ticket']").text(result.data.enable_ticket);
                        var mediaLinkHref ='<?php echo $this->Html->url(array('controller'=>'admin','action'=>'media_event')); ?>/'+btoa(eventID);
                        var organizerLinkHref ='<?php echo $this->Html->url(array('controller'=>'admin','action'=>'organizer_event')); ?>/'+btoa(eventID);
                        var speakerLinkHref ='<?php echo $this->Html->url(array('controller'=>'admin','action'=>'speaker_event')); ?>/'+btoa(eventID);
                        var agendaLinkHref ='<?php echo $this->Html->url(array('controller'=>'admin','action'=>'agenda_event')); ?>/'+btoa(eventID);
                        var ticketLinkHref ='<?php echo $this->Html->url(array('controller'=>'admin','action'=>'ticket_event')); ?>/'+btoa(eventID);
                        var showLinkHref ='<?php echo $this->Html->url(array('controller'=>'admin','action'=>'show_event')); ?>/'+btoa(eventID);
                        $("#mediaLink").attr('href',mediaLinkHref);
                        $("#organizerLink").attr('href',organizerLinkHref);
                        $("#speakerLink").attr('href',speakerLinkHref);
                        $("#agendaLink").attr('href',agendaLinkHref);
                        $("#ticketLink").attr('href',ticketLinkHref);
                        $("#showLink").attr('href',showLinkHref);
                        $('.allow').attr('row-id',eventID);
                        $("#modalViewEventOption").modal('show');
                    }
                    else
                    {
                        alert('Sorry, Could not find any response!');
                    }
                }
            });
        });
        $(document).on('click','.allow',function(e){
            var eventID = $(this).attr('row-id');
            var field = $(this).attr('field-id');
            var thisButton = $(this);
            $.ajax({
                url: baseurl+'/admin/admin/change_event_allow',
                data:{eventID:eventID,field:field},
                type:'POST',
                beforeSend:function(){
                    $(thisButton).button('loading').html('<i class="fa fa-spinner fa-pulse fa-2x fa-fw">');
                },
                success: function(result){
                    $(thisButton).button('reset');
                    var result = JSON.parse(result);
                    if(result.status == 1)
                    {
                        $(thisButton).text(result.text);
                    }
                    else
                    {
                        alert('Sorry, Could not update!');
                    }
                }
            });
        });
    });
</script>