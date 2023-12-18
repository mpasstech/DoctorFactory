<div class="Inner-banner">
    <div class="container">
        <div class="row">

            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Doc CMS Subcategories</h2> </div>
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

                   <?php echo $this->element('support_admin_leftsidebar'); ?>
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                        <div class="progress-bar channel_tap">
                            <a href="<?php echo Router::url('/admin/supp/list_cms_doc_sub_category'); ?>"  class="active" ><i class="fa fa-list"></i> Subcategory List</a>
                            <a href="<?php echo Router::url('/admin/supp/add_cms_doc_sub_category'); ?>" ><i class="fa fa-list"></i> Add Subcategory</a>
                        </div>


                        <div class="Social-login-box payment_bx">
                            <?php echo $this->element('message'); ?>
                            <div class="form-group row">
                                <div class="col-sm-12">
                            <div class="table-responsive">
                                <?php if(!empty($data)){ ?>
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Subcategory Name</th>
                                        <th>Category</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
									$num = 1;
									foreach ($data as $key => $value){
                                       
                                        ?>
                                        <tr>
                                            <td><?php echo $num++; ?></td>
                                            <td><?php echo $value['CmsDocHealthTipSubCategory']['sub_category_name']; ?></td>
                                            <td><?php echo $value['CmsDocHealthTipSubCategory']['category']; ?></td>
                                            <td>
												<button type="button" id="changeSellStatus" class="btn btn-primary btn-xs"  sell-id="<?php echo $value['CmsDocHealthTipSubCategory']['id']; ?>" ><?php echo $value['CmsDocHealthTipSubCategory']['status']; ?></button>
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
                                <h2>No Subcategories..!</h2>
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

<script>
    $(document).ready(function(){
                $(document).on('click','#changeSellStatus',function(e){
                    var sellID = $(this).attr('sell-id');
                    var thisButton = $(this);
                    $.ajax({
                        url: baseurl+'/admin/supp/change_doc_cms_subcategory_status',
                        data:{sellID:sellID},
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
	});			
</script>				