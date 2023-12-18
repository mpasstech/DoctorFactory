<?php
$login = $this->Session->read('Auth.User');
?>




<?php  echo $this->Html->css(array('dataTableBundle.css')); ?>
<?php  echo $this->Html->script(array('dataTableBundle.js')); ?>



<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">

                <div class="middle-block">
                    <h3 class="screen_title">Blog List</h3>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 custom_form_box">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 hospital_search_box">
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <label class="add_more_lbl"><a href="javascript:void(0);" class="send_blog_icon btn btn-success" ><i class="fa fa-plus"></i> Add New Post</a> </label>
                                <div class="table table-responsive">
                        <table id="example" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th style="width:15% !important;">Title</th>
                                <th style="width:45% !important;">Message</th>
                                <th>Type</th>

                                <th style="width:9% !important;">Created</th>
                                <th style="width:8% !important;">Option</th>
                            </tr>
                            </thead>
                            <tbody>


                            </tbody>

                        </table>

                    </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        var total = "<?php echo $total_record; ?>";


        $('#example').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "data": function(){
                    $('#example').DataTable().ajax.url(
                        baseurl + "app_admin/load_blog_post/"+total+"/?"+window.location.search.substring(1)
                    );
                }
            },
            "language": {
                processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only">Loading...</span> '
            },
            dom: 'Blfrtip',
            lengthMenu: [
                [ 10, 25, 50, 100, 150, 200, -1 ],
                [ '10 rows', '25 rows', '50 rows', '100 rows', '150 rows', '200 rows', 'Show all' ]
            ],
            buttons: []

        });






        $(document).on('click','.delete_btn',function(){

            var data_id = $(this).attr('data-id');
            var jc = $.confirm({
                title: "Delete Post",
                content: 'Are you sure you want to delete this post?',
                type: 'red',
                buttons: {
                    ok: {
                        text: "Yes",
                        btnClass: 'btn-primary',
                        keys: ['enter'],
                        name:"ok",
                        action: function(e){
                           $.ajax({
                                url: "<?php echo Router::url('/app_admin/delete_blog_post',true);?>",
                                type:'POST',
                                data:{data_id:data_id},
                               beforeSend:function(){
                                   jc.buttons.ok.setText("Deleting...");
                               },
                                success: function(result){
                                    var data = JSON.parse(result);
                                    jc.buttons.ok.setText("Yes");
                                    if(data.status == 1){
                                        jc.close();
                                        window.location.reload();
                                    }else{
                                        $.alert(data.message);
                                    }
                                },error:function () {
                                   $.alert('Sorry, post could not delete');
                                }
                            });

                            return false;
                        }
                    },
                    cancel: function(){

                    }
                }
            });



        });




        $(document).on('click','.send_blog_icon, .edit_btn',function(){

            var data_id = $(this).attr('data-id');
            var data ={};
            if(data_id){
                data ={data_id:data_id};
            }
            $.ajax({
                url: "<?php echo Router::url('/app_admin/send_blog_post',true);?>",
                type:'POST',
                data:data,
                success: function(result){
                    var html = $(result).filter('#send_blog_modal');
                    $(html).modal('show');
                },error:function () {

                }
            });
        });

    });
</script>
<style>

    #example_processing{
       position: fixed;
        background: transparent !important;
    }

    .add_more_lbl {
        width: 10%;
        position: absolute;
        margin: 0px 70%;
        display: block;
        z-index: 10;
    }
    #example_length{
        width: auto;
    }
    .list_image_container{

        float: left;
    }
    .message_container{
        float: left;

    }
    .list_image_container img{
        border:1px solid #413c3c61;
    }
    .message_padding{

    }
    td p{
        margin: 0px;
        float: left;
        width: 33%;
        color: #28a4c9;
    }
    .type_lbl{
        width: 100%;
    }

    table.dataTable tbody th, table.dataTable tbody td {
        padding: 3px 4px;
    }
    td a{
        margin: 2px 0px;
    }
</style>





