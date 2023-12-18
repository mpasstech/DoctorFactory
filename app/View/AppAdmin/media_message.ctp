<?php
$login = $this->Session->read('Auth.User.User');
$doctor_list = $this->AppAdmin->getHospitalDoctorList($login['thinapp_id']);

?>
<?php  echo $this->Html->css(array('dataTableBundle.css')); ?>
<?php  echo $this->Html->script(array('dataTableBundle.js','jquery.maskedinput-1.2.2-co.min.js','comman.js')); ?>

<?php
$login = $this->Session->read('Auth.User');
?>

<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">
                <!--box 1 -->
                <!--box 1 -->
                <div class="middle-block">
                    <h3 class="screen_title">Media Message</h3>
                    <button type="button" class="add_speech btn btn-success btn-lg">Add Media</button>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">


                        <div class="Social-login-box payment_bx">

                            <?php echo $this->element('message'); ?>



                            <div class="form-group row">
                                <div class="col-sm-12">


                                    <div class="table table-responsive">
                                        <table id="example" class="table table-striped table-bordered" style="width:100%">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Type</th>
                                                <th>Media</th>
                                                <th>Sort Order</th>
                                                <th>Options</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            <?php $counter = 1; foreach($mediaData AS $item){ ?>

                                                <tr>
                                                    <td><?php echo $counter; ?>.</td>
                                                    <td><?php echo $item['MediaMessage']['media_type']; ?></td>
                                                    <td>

                                                    <?php if($item['MediaMessage']['media_type'] == 'IMAGE'){ ?>
                                                        <img src="<?php echo $item['MediaMessage']['media_url']; ?>" class="media_image">
                                                    <?php }else if($item['MediaMessage']['media_type'] == 'VIDEO'){ ?>
                                                        <video class="media_video" autoload="false" controls>
                                                            <source src="<?php echo $item['MediaMessage']['media_url']; ?>" type="video/mp4">
                                                            <source src="<?php echo $item['MediaMessage']['media_url']; ?>" type="video/ogg">
                                                            <source src="<?php echo $item['MediaMessage']['media_url']; ?>" type="video/webm">
                                                            Your browser does not support the video tag.
                                                        </video>
                                                    <?php }else{ ?>
                                                        <iframe width="150" height="100" src="https://www.youtube.com/embed/<?php echo $item['MediaMessage']['media_url']; ?>" frameborder="0" allow="accelerometer; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                                    <?php } ?>

                                                    </td>
                                                    <td>
                                                        <input type="number" value="<?php echo $item['MediaMessage']['sort_order']; ?>" data-id="<?php echo $item['MediaMessage']['id']; ?>" class="sort_input">
                                                    </td>
                                                    <td>
                                                            <button type="button" from="live" id="updateMediaStatus" class="updateMediaStatus btn btn-primary btn-xs <?php echo $item['MediaMessage']['status']; ?>" data-status="<?php echo $item['MediaMessage']['status']; ?>" data-id="<?php echo $item['MediaMessage']['id']; ?>" ><?php echo $item['MediaMessage']['status']; ?></button>
                                                            <button type="button" from="live" id="updateMediaMessage" class="updateMediaMessage btn btn-primary btn-xs" data-message="<?php echo $item['MediaMessage']['media_url']; ?>" data-id="<?php echo $item['MediaMessage']['id']; ?>" >Edit</button>
                                                    </td>
                                                </tr>
                                                <?php $counter++; } ?>
                                            </tbody>
                                        </table>

                                    </div>


                                </div>
                            </div>
                            <div class="clear"></div>




                        </div>
                    </div>
                </div>
                <!-- box 1 -->
            </div>
            <!--box 2 -->
        </div>
    </div>
</div>



<div class="modal fade" id="editModal" role="dialog">

    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Media</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <form id="editForm" method="POST">

                        <div class="form-group local_file_container">

                            <div class="col-sm-6">
                                <label>&nbsp;</label>
                                <select name="local_file_type" id="fileTypeHolder" class="form-control cnt">
                                    <option value="IMAGE">Image</option>
                                    <option value="VIDEO">Video</option>
                                    <option value="YOUTUBE">Youtube</option>
                                </select>
                            </div>
                            <div class="col-sm-6 duration-holder" style="display:none;">
                                <label style="color:red;">Leave blank to play full length video.</label>
                                <input type="number" name="duration" value="" id="durationHolder" placeholder="Duration in minutes" class="form-control cnt">
                            </div>
                        </div>

                        <div class="form-group server_file_container">
                            <div class="col-sm-12 media-holder">
                                <input type="file" name="media" id="mediaHolder" accept="image/*,video/*" class="form-control cnt" required>
                            </div>
                            <div class="col-sm-12 youtube-video-holder" style="display:none;">
                                <input type="text" name="youtube-video" id="youtubeVideo" placeholder="Youtube video ID" class="form-control cnt">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="hidden" name="id" id="IDholder">
                                <button type="submit" id="editBtn" name="submitForm" class="form-control btn btn-primary" >Save</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
            <div class="modal-footer">

            </div>

        </div>
    </div>

</div>

<style>
    #example_length {
        width: 32%;
        text-align: right;
    }
    .modal-footer {

        border-top: none;

    }
    .media_image {
        width: 150px;
    }
    .media_video {
        width: 150px;
    }
    .sort_input {
        width: 60px;
    }

</style>

<script>
    var itemIndex = <?php echo $counter; ?>;


    $(document).ready(function () {


        $('#fileTypeHolder').change(function(){
            var fileTypeHolder = $(this).val();
            if(fileTypeHolder == 'IMAGE' || fileTypeHolder == 'VIDEO')
            {
                $(".media-holder").show();
                $(".youtube-video-holder").hide();
                $("#mediaHolder").prop('required',true);
                $("#youtubeVideo").removeAttr('required');
                $("#youtubeVideo").val('');
                $(".duration-holder").hide();
                $("#durationHolder").val('');
            }
            else
            {
                $(".media-holder").hide();
                $(".youtube-video-holder").show();
                $("#youtubeVideo").prop('required',true);
                $("#mediaHolder").removeAttr('required');
                $("#mediaHolder").val('');
                $(".duration-holder").show();
                $("#durationHolder").val('');
            }
        });



        $('#example').DataTable({
            dom: 'Blfrtip',
            lengthMenu: [
                [ 10, 25, 50, 100, 150, 200, -1 ],
                [ '10 rows', '25 rows', '50 rows', '100 rows', '150 rows', '200 rows', 'Show all' ]
            ],
            "language": {
                "emptyTable": "No Data Found Related To Search"
            },
            buttons: [
                {
                    extend: 'copy',
                    header: true,
                    footer: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6]
                    }

                },
                {
                    extend: 'csv',
                    header: true,
                    footer: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6]
                    }

                },
                {
                    extend: 'excel',
                    header: true,
                    footer: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6]
                    }

                },
                {
                    extend: 'pdf',
                    header: true,
                    footer: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6]
                    }

                },
                {
                    extend: 'print',
                    header: true,
                    footer: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6]
                    }

                }
            ]
        });

        $(document).on("click",".updateMediaStatus",function(){


                var ID = $(this).attr("data-id");
                var status = $(this).attr("data-status");
                $.ajax({
                    url: baseurl+'/app_admin/update_media_message_status',
                    data:{ID:ID,status:status},
                    type:'POST',
                    success: function(result){
                        var result = JSON.parse(result);
                        if(result.status == 1)
                        {
                            location.reload();
                        }
                        else
                        {
                            alert(result.message);
                        }
                    }
                });


        });

        $(document).on("click",".add_speech",function(){
            $('#editForm')[0].reset();

            $(".media-holder").show();
            $(".youtube-video-holder").hide();
            $("#mediaHolder").prop('required',true);
            $("#youtubeVideo").removeAttr('required');
            $("#youtubeVideo").val('');
            $(".duration-holder").hide();
            $("#durationHolder").val('');

            $("#editModal").modal('show');
        });

        $(document).on("click",".updateMediaMessage",function(){

            $('#editForm')[0].reset();
            var ID = $(this).attr("data-id");
            $("#IDholder").val(ID);


            $(".media-holder").show();
            $(".youtube-video-holder").hide();
            $("#mediaHolder").prop('required',true);
            $("#youtubeVideo").removeAttr('required');
            $("#youtubeVideo").val('');
            $(".duration-holder").hide();
            $("#durationHolder").val('');


            $("#editModal").modal('show');
        });

        $("form#editForm").submit(function(e){
            e.preventDefault();

                var formData = new FormData(this);

                $.ajax({
                    url: baseurl+'/app_admin/update_media_message',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend:function(){
                        $("#editBtn").button('loading').html('<i class="fa fa-spinner fa-pulse fa-2x fa-fw">');
                    },
                    success: function(result){
                        $("#editBtn").button('reset');
                        var result = JSON.parse(result);
                        if(result.status == 1)
                        {

                            $('#editForm')[0].reset();
                            $("#editModal").modal('hide');
                            window.location.reload();
                        }
                        else
                        {
                            alert(result.message);
                        }
                    }
                });
        });

        $(document).on("blur",".sort_input",function(){
            var ID = $(this).attr("data-id");
            var sortOrder = $(this).val();

            $.ajax({
                url: baseurl+'/app_admin/update_media_message_sort_order',
                data:{ID:ID,sortOrder:sortOrder},
                type:'POST',
                success: function(result){
                    var result = JSON.parse(result);
                    if(result.status != 1)
                    {
                        alert(result.message);
                    }
                }
            });

        });
    });
</script>