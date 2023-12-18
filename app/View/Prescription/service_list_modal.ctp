<div class="modal fade" id="serviceListModal" role="dialog" tabindex="-1" >

    <div class="modal-dialog modal-md" >
        <div class="modal-content">

            <div class="modal-body">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <a class="add_new_category btn btn-warning btn-xs" href="javascript:void(0);">Add Category</a>
                    <h4 class="modal-title">Service List</h4>
                </div>
                <div class="container_list">
                    <table class="table table-bordered">

                <?php if(!empty($data['data']['list'])){ foreach($data['data']['list'] as $key => $list){ ?>

                        <tr class="header active" data-t="Category" data-n ="<?php echo $list['category_name']; ?>" data-ci="<?php echo base64_encode($list['category_id']); ?>">
                            <td colspan="3">
                                <span class="navigation_icon"><i class="fa fa-angle-down"></i></span>
                                <?php echo $list['category_name']; ?>
                                <span class="icon_span_corner">
                                <i class="fa fa-trash delete_category"></i>
                                <i class="fa fa-pencil edit_category"></i>
                                <i class="fa fa-plus add_service" ></i>
                                </span>
                            </td>
                        </tr>
                        <?php foreach($list['service_list'] as $s_key => $service){ if(!empty($service)){ ?>
                            <tr style="display: table-row;" data-p="<?php echo $service['price']; ?>" data-n ="<?php echo $service['service_name']; ?>" data-t="Service" data-si="<?php echo base64_encode($service['service_id']); ?>" data-ci="<?php echo base64_encode($list['category_id']); ?>">
                                <td width="82%"><?php echo $service['service_name']; ?></td>
                                <td width="5%" style="text-align: right;"><?php echo $service['price']; ?></td>
                                <td width="13%" style="text-align: right;">
                                    <a  class="service_update_btn" data-si="<?php echo base64_encode($service['service_id']);?>"  title = "Edit this service" href="javascript:void(0);"><i class="fa fa-edit fa-1x fa-fw"></i></a>
                                    <a  class="service_delete_btn" data-si="<?php echo base64_encode($service['service_id']);?>" title="Delete this service" href="javascript:void(0);"><i class="fa fa-trash fa-1x fa-fw"></i></a>
                                </td>

                            </tr>
                        <?php }} ?>

                <?php }}else{ ?>
                    <tr><td colspan="3">No service list found</td></tr>
                <?php } ?>

                    </table>
                </div>

            </div>


        </div>
    </div>
    <script type="text/javascript">
        $(function () {

            $('button').button({loadingText: '<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>'});

            var ua = navigator.userAgent,
                event = (ua.match(/iPad/i)) ? "touchstart" : "click";
            if ($('.table').length > 0) {
                $('.table .header').off(event);
                $('.table .header').on(event, function() {
                    var obj = $(this);
                    $(this).toggleClass("active", "").nextUntil('.header').css('display', function(i, v) {
                        var add_class = this.style.display === 'table-row' ? 'fa-angle-up' : 'fa-angle-down';
                        $(obj).find('.navigation_icon i').removeClass('fa-angle-down').removeClass('fa-angle-up');
                        $(obj).find('.navigation_icon i').addClass(add_class);

                        return this.style.display === 'table-row' ? 'none' : 'table-row';
                    });
                });
            }


            $(document).off('click','.add_new_category');
            $(document).on("click",".add_new_category",function(e){
                $(this).closest('.header').trigger('click');
                $("#service_manage_form").trigger('reset');
                var type = $(this).closest('tr').attr('data-t');
                $('.price_box_div').hide();
                $("#serviceManageModal .manage_service_header").html("Add Category");
                $("#serviceManageModal .label_name").html("Enter category name");
                $("#serviceManageModal").modal('show').attr({'data-ci':'0','data-si':'0','data-af':'CATEGORY','data-at':'ADD'});
            });

            $(document).off('click','.edit_category');
            $(document).on("click",".edit_category",function(e){
                $(this).closest('.header').trigger('click');
                $("#service_manage_form").trigger('reset');
                var type = $(this).closest('tr').attr('data-t');
                var name = $(this).closest('tr').attr('data-n');
                var raw = $(this).closest('tr');
                $('.price_box_div').hide();
                $("#serviceManageModal .manage_service_header").html("Edit Category");
                $("#serviceManageModal .label_name").html("Enter "+type+" name");
                $("#serviceManageModal input[name='name']").val(name);
                $("#serviceManageModal").modal('show').attr({'data-ci':$(raw).attr('data-ci'),'data-si':'0','data-af':'CATEGORY','data-at':'UPDATE'});
            });


            $(document).off('click','.add_service');
            $(document).on("click",".add_service",function(e){
                $(this).closest('.header').trigger('click');
                var raw = $(this).closest('tr');
                $("#service_manage_form").trigger('reset');
                $('.price_box_div').show();
                $("#serviceManageModal .manage_service_header").html("Add Service");
                $("#serviceManageModal .label_name").html("Enter service name");
                $("#serviceManageModal input[name='name']").val('');
                $("#serviceManageModal").modal('show').attr({'data-ci':$(raw).attr('data-ci'),'data-si':'0','data-af':'SERVICE','data-at':'ADD'});
            });


            $(document).off('click','.container_list .service_update_btn');
            $(document).on("click",".container_list .service_update_btn",function(){
                $(this).closest('.header').trigger('click');
                $("#service_manage_form").trigger('reset');
                var raw = $(this).closest('tr');
                var type = $(this).closest('tr').attr('data-t');
                var name = $(this).closest('tr').attr('data-n');
                var price = $(this).closest('tr').attr('data-p');
                $('.price_box_div').show();
                $("#serviceManageModal .manage_service_header").html("Edit Service");
                $("#serviceManageModal .label_name").html(type+" name");
                $("#serviceManageModal input[name='name']").val(name);
                $("#serviceManageModal input[name='price']").val(price);
                $("#serviceManageModal").modal('show').attr({'data-ci':$(raw).attr('data-ci'),'data-si':$(raw).attr('data-si'),'data-af':'SERVICE','data-at':'UPDATE'});
            });

            $(document).off('submit','#service_manage_form');
            $(document).on('submit','#service_manage_form',function(e){
                e.preventDefault();
                $(this).append("<input type='hidden' name='ci' value='"+$("#serviceManageModal").attr('data-ci')+"'>");
                $(this).append("<input type='hidden' name='si' value='"+$("#serviceManageModal").attr('data-si')+"'>");
                $(this).append("<input type='hidden' name='af' value='"+$("#serviceManageModal").attr('data-af')+"'>");
                $(this).append("<input type='hidden' name='at' value='"+$("#serviceManageModal").attr('data-at')+"'>");
                var $btn = $(this).find('.save_service_btn');
                $.ajax({
                    url: "<?php echo Router::url('/prescription/manage_category_and_service',true);?>",
                    type:'POST',
                    data:$(this).serialize(),
                    beforeSend:function(){
                        $btn.button('loading').text('Saving..');
                    },
                    success: function(response){
                        $btn.button('reset');
                        response = JSON.parse(response);
                        if(response.status == 1){
                            flash("Success",response.message, "success",'center');
                            $("#serviceManageModal").modal('hide');
                            var html = $('.container_list', $(response.data.update));
                            $("#serviceListModal .container_list").html(html);
                        }else{
                            flash("Warning",response.message, "warning",'center');
                        }
                    },error:function () {
                        $btn.button('reset');
                        flash("Error",'Something went wrong on server.', "danger",'center');
                    }
                });
            });



            $(document).off('click','.delete_category');
            $(document).on("click",".delete_category",function(){
                var $btn = $(this);
                var tr = $(this).closest('tr');
                var ci = $(tr).attr('data-ci');
                var jc = $.confirm({
                    title: "Delete Category",
                    content: 'Are you sure you want to delete this category?',
                    type: 'red',
                    buttons: {
                        ok: {
                            text: "Yes",
                            btnClass: 'btn-primary',
                            keys: ['enter'],
                            name:"ok",
                            action: function(e){
                                $.ajax({
                                    type: 'POST',
                                    url: "<?php echo Router::url('/prescription/manage_category_and_service',true);?>",
                                    data: {at:'DELETE',af: 'CATEGORY',ci:ci},
                                    beforeSend: function () {

                                    },
                                    success: function (response) {
                                        $btn.button('reset');
                                        response = JSON.parse(response);
                                        if(response.status == 1){
                                            flash("Success",response.message, "success",'center');
                                            $("#serviceManageModal").modal('hide');
                                            var html = $('.container_list', $(response.data.update));
                                            $("#serviceListModal .container_list").html(html);

                                            jc.close();
                                        }else{
                                            flash("Warning",response.message, "warning",'center');
                                        }

                                    },
                                    error: function (data) {
                                        $btn.button('reset');
                                        flash("Error",'Something went wrong on server.', "danger",'center');
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

            $(document).off('click','.service_delete_btn');
            $(document).on("click",".service_delete_btn",function(){
                var $btn = $(this);
                var tr = $(this).closest('tr');
                var si = $(tr).attr('data-si');
                var jc = $.confirm({
                    title: "Delete Service",
                    content: 'Are you sure you want to delete this service?',
                    type: 'red',
                    buttons: {
                        ok: {
                            text: "Yes",
                            btnClass: 'btn-primary',
                            keys: ['enter'],
                            name:"ok",
                            action: function(e){
                                $.ajax({
                                    type: 'POST',
                                    url: "<?php echo Router::url('/prescription/manage_category_and_service',true);?>",
                                    data: {at:'DELETE',af: 'SERVICE',si:si},
                                    beforeSend: function () {

                                    },
                                    success: function (response) {
                                        $btn.button('reset');
                                        response = JSON.parse(response);
                                        if(response.status == 1){
                                            flash("Success",response.message, "success",'center');
                                            $("#serviceManageModal").modal('hide');
                                            var html = $('.container_list', $(response.data.update));
                                            $("#serviceListModal .container_list").html(html);

                                            jc.close();
                                        }else{
                                            flash("Warning",response.message, "warning",'center');
                                        }

                                    },
                                    error: function (data) {
                                        $btn.button('reset');
                                        flash("Error",'Something went wrong on server.', "danger",'center');
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

        });
    </script>
    <style>

        #serviceListModal .modal-md{
            width: 35%;
        }
        #serviceListModal .modal-body{
           padding: 0px;
        }
        .add_new_category{
            float: right;
            border-radius: 12px;
            margin: 2px 15px;
        }
        .container_list {
            width: 100%;
        }

        .navigation_icon{
            font-size: 14px;
        }
        .icon_span_corner{
            float: right;
            font-size: 20px;
            display: block;
            width: 20%;
            position: absolute;
            right: 0px;
            margin-top: -25px;
            z-index: 99;
            font-weight: 300;
        }

        .icon_span_corner i{
            z-index: 999999 !important;
            margin: 0 4px;
        }

        .table tr.header {
            font-weight: bold;
            background-color: #fff;
            cursor: pointer;
            -webkit-user-select: none;
            /* Chrome all / Safari all */
            -moz-user-select: none;
            /* Firefox all */
            -ms-user-select: none;
            /* IE 10+ */
            user-select: none;
            /* Likely future */

        }

        .table tr:not(.header) {
            display: none;
        }

        #serviceListModal .modal-header{
            background-color:#074097d4;
            color: #fff;
        }

        #serviceListModal .header td{
            background-color:#1a57b2d4;
            color: #fff;
        }

        #serviceListModal td{
            border: none;
            border-bottom: 1px solid #d7cdcdd9;
        }

        .table .header td:after {

            position: relative;
            top: 1px;
            font-family: 'Glyphicons Halflings';
            font-style: normal;
            font-weight: 400;
            line-height: 1;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            float: left;
            color: #fff;
            text-align: center;
            padding: 3px;
            transition: transform .25s linear;
            -webkit-transition: -webkit-transform .25s linear;
        }


    </style>
</div>
