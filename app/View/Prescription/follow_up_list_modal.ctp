<div class="modal fade" id="followUpListModal" tabindex="-1" role="dialog" >
    <div class="modal-dialog modal-md" style="width: 700px;" >
        <div class="modal-content">

            <div class="modal-body">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Patient Follow Up List</h4>
                </div>

                <div class="raw action_box">
                    <form>
                    <div class="col-md-5">
                        <label>Search</label>
                        <input type="text" placeholder="Patient name, mobile" id="search" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label>From Date</label>
                        <input type="text" id="from_date" value="<?php echo date('d-m-Y');?>" class="from_date form-control"></div>
                    <div class="col-md-2">
                        <label>To Date</label>
                        <input type="text" id="to_date" value="<?php echo date('d-m-Y');?>" class="to_date form-control">
                    </div>

                    <div class="col-md-3">
                        <label style="width: 100%; display: block;">&nbsp;</label>
                        <button type="button" class="btn btn-xs btn-success follow_search_btn" style="height: 30px;"><i class="fa fa-search"></i> Search</button>
                        <button type="reset" class="btn btn-xs btn-warning follow_reset_btn" style="height: 30px;"><i class="fa fa-refresh"></i> Reset</button>
                    </div>
                    </form>

                </div>
                <div class="raw table_raw">
                    <div class="table_box">
                        <table class="table table-fixed" >
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Mobile</th>
                                <th>Date</th>
                                <th>Reminder Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody class="append_container">
                            <?php echo $data; ?>
                            </tbody>
                        </table>
                        <a href="javascript:void(0)" data-offset = "0" class="follow_load_more">Load More</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(function () {

            $('.from_date, .to_date').datepicker({
                format: 'dd-mm-yyyy',
                setDate: new Date(),
                autoclose:true
            });

            $(document).off('click','.follow_search_btn, .follow_load_more .follow_reset_btn');
            $(document).on('click','.follow_search_btn, .follow_load_more, .follow_reset_btn',function(){
                var obj = $(this);
                setTimeout(function () {
                    load_list(obj);
                },4);
            });

            function load_list(obj){
                var $btn = $(obj);

                if(!$($btn).hasClass('follow_load_more')){
                    $(".follow_load_more").attr('data-offset',0);
                }
                $.ajax({
                    url: "<?php echo Router::url('/prescription/follow_up_list_template',true);?>",
                    type:'POST',
                    data:{offset:$(".follow_load_more").attr('data-offset'),name:$("#search").val(),from_date:$("#from_date").val(),to_date:$("#to_date").val()},
                    beforeSend:function(){
                        $btn.button('loading');
                    },
                    success: function(response){
                        $btn.button('reset');
                        if($($btn).hasClass('follow_load_more')){
                            $(".append_container").append(response);
                        }else{
                            $(".append_container").html(response);
                        }


                    },error:function () {
                        $btn.button('reset');
                        flash("Error",'Something went wrong on server.', "danger",'center');
                    }
                });
            }

            $(document).off('click','.send_alert_btn');
            $(document).on('click','.send_alert_btn',function(){
                var $btn = $(this);
                $.ajax({
                    url: "<?php echo Router::url('/prescription/send_follow_up_alert',true);?>",
                    type:'POST',
                    data:{fi:$(this).attr('data-id')},
                    beforeSend:function(){
                        $btn.button('loading');
                    },
                    success: function(response){
                        $btn.button('reset');
                        response = JSON.parse(response);
                        if(response.status == 1){
                            flash("Follow Up",response.message, "success",'center');
                        }else{
                            flash("Warning",response.message, "warning",'center');
                        }
                    },error:function () {
                        $btn.button('reset');
                        flash("Error",'Something went wrong on server.', "danger",'center');
                    }
                });
            });



        });
    </script>
    <style>


        .follow_reset_btn, .follow_search_btn{
            width: 48%;
        }
        #followUpListModal .modal-content{
            float: left;
        }
        .follow_load_more{
            text-align: center;
            width: 100%;
            display: block;
        }
        .table_raw{
            float: left;
            width: 100%;
            display: block;
            margin-bottom: 9px;
        }
        .table_box{
            height: 450px;
            overflow-y: scroll;
            float: left;
            display: block;
            width: 100%;
            background: #fff;
        }
        tbody { height: 250px; overflow-y: scroll;}
        .action_box div[class^='col-md-']{
            padding: 2px !important;
        }

    </style>
</div>
