<?php
$login = $this->Session->read('Auth.User');
//echo $date;
?>


<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">
                <!--box 1 -->
                <!--box 1 -->
                <div class="middle-block">
                    <!-- Heading -->

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                        <div class="Social-login-box payment_bx">

                            <?php echo $this->element('message'); ?>
                            <h3 class="screen_title">User Statics Reports</h3>
							<div class="row">
								
									<form id="searchForm">
										
										<div class="col-sm-4">
											<div class="form-group">
												<div class="col-sm-12">
													<label>Search Start Date</label>
													<input type="text" value="<?php echo $startDate; ?>" class="form-control cnt" required = "required" id="searchByDate"> 
												</div>
											</div>
										</div>
										
										<div class="col-sm-4">
											<div class="form-group">
												<div class="col-sm-12">
													<label>Search End Date</label>
													<input type="text" value="<?php echo $endDate; ?>" class="form-control cnt" required = "required" id="searchByEndDate"> 
												</div>
											</div>
										</div>
										
										<div class="col-sm-4">
											<div class="form-group">
												<div class="col-sm-12">
													<button class="searchBtn btn btn-info form-control" type="submit" >Search</button>
												</div>
											</div>	
										</div>
										
									</form>
									
								</div>
							
							
                            <div class="form-group row">
                                <div class="col-sm-12">

                            <div class="table-responsive">
                            <?php if(!empty($userData)){ ?>
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Username</th>
										<th>Mobile</th>
										<th>Active at</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($userData as $key => $list){?>
                                    <tr>
                                        <td><?php echo $key+1; ?></td>
                                        <td><?php echo $list['Owner']['username']; ?></td>
                                    	<td><?php echo $list['Owner']['mobile']; ?></td>
										<td><?php echo $list['AppUserStatic']['created']; ?></td>
                                    </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                                <?php echo $this->element('paginator'); ?>
                            </div>

                            <?php }else{ ?>
                                <div class="no_data">
                                    <h2>There is no user active on this date!</h2>
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


<style>
.searchBtn{ margin-top: 28px; }
</style>
<script>
			$("#searchForm").submit(function(event){
				event.preventDefault();
				var dateVal = $("#searchByDate").val();
				var dateEndVal = $("#searchByEndDate").val();
				window.location.href = "<?php echo $this->Html->url(array('controller' => 'app_admin','action' =>'user_statics_details'),true); ?>/"+dateVal+"_"+dateEndVal;	
			});
			
			$(document).ready(function(){
					
				var start = new Date();
				// set end date to max one year period:
				var end = new Date();

				$('#searchByDate').datepicker({
					endDate   : end,
					autoclose: true,
					format: 'yyyy-mm-dd',
					orientation: "bottom"
				// update "toDate" defaults whenever "fromDate" changes
				}).on('changeDate', function(){
					// set the "toDate" start to not be later than "fromDate" ends:
					$('#searchByEndDate').datepicker('setStartDate', new Date($(this).val()));
				}); 

				$('#searchByEndDate').datepicker({
					endDate   : end,
					autoclose: true,
					format: 'yyyy-mm-dd',
					orientation: "bottom"
				// update "fromDate" defaults whenever "toDate" changes
				}).on('changeDate', function(){
					// set the "fromDate" end to not be later than "toDate" starts:
					$('#searchByDate').datepicker('setEndDate', new Date($(this).val()));
				});	
					
			});
</script>






