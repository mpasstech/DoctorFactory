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
                    <h3 class="screen_title">Speech Message</h3>
                    <button type="button" class="add_speech btn btn-success btn-lg">Add Speech</button>
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
                                                <th>Message</th>
                                                <th>Options</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            <?php $counter = 1; foreach($speechData AS $item){ ?>

                                                <tr>
                                                    <td><?php echo $counter; ?>.</td>
                                                    <td><?php echo ucwords( $item['SpeechMessage']['message']); ?></td>
                                                    <td>
                                                        <?php if($item['SpeechMessage']['thinapp_id'] != 0){ ?>
                                                            <button type="button" id="updateSpeechStatus" class="updateSpeechStatus btn btn-primary btn-xs <?php echo $item['SpeechMessage']['status']; ?>" data-status="<?php echo $item['SpeechMessage']['status']; ?>" data-id="<?php echo $item['SpeechMessage']['id']; ?>"  ><?php echo $item['SpeechMessage']['status']; ?></button>
                                                            <button type="button" id="updateSpeechMessage" class="updateSpeechMessage btn btn-primary btn-xs" data-message="<?php echo $item['SpeechMessage']['message']; ?>" data-id="<?php echo $item['SpeechMessage']['id']; ?>"  >Edit</button>
                                                        <?php } ?>
                                                        <?php if($item['SpeechMessage']['status'] == 'ACTIVE'){ ?>
                                                            <button type="button" class="play_speech btn btn-success btn-xs" data-id="<?php echo $item['SpeechMessage']['id']; ?>"  >Play</button>
                                                        <?php } ?>
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
                <h4 class="modal-title">Speech</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <form id="editForm" method="POST">

                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="text" maxlength="50" name="message" id="messageHolder"  placeholder="Message*" class="form-control cnt" required>
                                <input type="hidden" name="id" id="IDholder">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12">
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



</style>

<script>
    $(document).ready(function () {

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

        $(document).on("click",".updateSpeechStatus",function(){

                var ID = $(this).attr("data-id");
                var status = $(this).attr("data-status");
                $.ajax({
                    url: baseurl+'/app_admin/update_speech_message_status',
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

        $(document).on("click",".play_speech",function(){

            var ID = $(this).attr("data-id");
            $.ajax({
                url: baseurl+'/app_admin/update_speech_message_play_status',
                data:{ID:ID},
                type:'POST',
                success: function(result){
                    var result = JSON.parse(result);
                    if(result.status == 1)
                    {
                        //location.reload();
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
            $("#editModal").modal('show');
        });
        $(document).on("click",".updateSpeechMessage",function(){
            $('#editForm')[0].reset();
            var ID = $(this).attr("data-id");
            var message = $(this).attr("data-message");
            $("#messageHolder").val(message);
            $("#IDholder").val(ID);
            $("#editModal").modal('show');
        });
        $("#editForm").submit(function(e){
            e.preventDefault();
            var dataTosend = $(this).serialize();

            $.ajax({
                url: baseurl+'/app_admin/update_speech_message',
                data:dataTosend,
                type:'POST',
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
    });
</script>