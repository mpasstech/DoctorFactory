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
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <?php echo $this->element('app_admin_inner_tab_cms'); ?>


                        <div class="Social-login-box payment_bx">

                            <?php echo $this->element('message'); ?>
                       <?php echo $this->element('message'); ?>
                            <div class="form-group row">
                                <div class="col-sm-12">

                                    <div class="table-responsive">
                                        <?php if(!empty($channel)){ ?>
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>#</th>

                                                <th>Dashboard Icon</th>
                                                <th>Title</th>
                                                <th>Page Load Type</th>
                                                <th>Status</th>
                                                <th>Action</th>


                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach ($channel as $key => $list){?>
                                                <tr>
                                                    <td><?php echo $key+1; ?></td>
                                                    <td><img src="<?php echo $list['CmsPage']['dashboard_icon_url']; ?>" style="width:40px;height: 40px;" ></td>
                                                    <td><?php echo $list['CmsPage']['title']; ?></td>
                                                    <td><?php echo $list['CmsPage']['request_load_type']; ?></td>
                                                    <td><?php echo ucfirst($list['CmsPage']['status']); ?></td>
                                                    <td>

                                                        <?php
                                                            if($list['CmsPage']['page_category_id']==0){
                                                                $list['CmsPage']['page_category_id'] = $this->AppAdmin->update_page_type_id($list['CmsPage']['id'],$list['CmsPage']['dashboard_icon_url']);
                                                            }
                                                        ?>


                                                        <div class="action_icon">
                                                            <a class="btn btn-xs btn-info" href="<?php echo Router::url('/cms/manage/',true).base64_encode($list['CmsPage']['page_category_id'])."/".base64_encode($list['CmsPage']['id']); ?>"><i class="fa fa-edit"></i> Edit</a>
                                                        </div>
                                                        <div class="action_icon">
                                                            <?php $url = ($list['CmsPage']['request_load_type']=='CONTENT')?Router::url('/cms/view_cms/',true).base64_encode($list['CmsPage']['id']):$list['CmsPage']['url']; ?>
                                                            <a class="btn btn-xs btn-warning" target="_blank" href="<?php echo $url; ?>"><i class="fa fa-edit"></i> View</a>
                                                        </div>
                                                        <div class="action_icon">
                                                            <?php $url = ($list['CmsPage']['request_load_type']=='CONTENT')?Router::url('/cms/delete_cms/',true).base64_encode($list['CmsPage']['id']):$list['CmsPage']['url']; ?>
                                                            <a class="btn btn-xs btn-danger delete_cms" data-id="<?php echo base64_encode($list['CmsPage']['id']); ?>" href="javascript:void(0);"><i class="fa fa-trash"></i> Delete</a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                        </table>
                                        <?php echo $this->element('paginator'); ?>
                                    </div>

                                    <?php }else{ ?>
                                        <div class="no_data">
                                            <h2>You have no cms pages</h2>
                                        </div>
                                    <?php } ?>

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





<script>
    $(document).ready(function() {


        $(".channel_tap a").removeClass('active');
        $("#v_app_cms_list").addClass('active');

        $(document).on('click', '.delete_cms', function (e) {

            if (confirm("Are you sure you want to delete this cms?")) {
                var btn = $(this);
                var tr = $(this).closest('tr');
                var id = $(this).attr("data-id");
                $.ajax({
                    url: baseurl + "/cms/delete_cms/" + id,
                    type: 'POST',
                    beforeSend: function () {
                        btn.button('loading').val('Wait..');
                    },
                    success: function (res) {
                        btn.button('reset');
                        var response = JSON.parse(res);
                        if (response.status == 1) {
                            $(tr).slideUp(300);
                        } else {
                            $.alert(response.message);
                        }

                    },
                    error: function () {
                        btn.button('reset');
                        $.alert("Sorry something went wrong on server.");
                    }
                })
            }

        });
    });


</script>






