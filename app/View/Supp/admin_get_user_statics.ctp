<?php
$login = $this->Session->read('Auth.User');
?>

<div class="Inner-banner">
    <div class="container">
        <div class="row">
            <!--  SLIDER IMAGE -->
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>User Statics</h2> </div>
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
                    <?php echo $this->element('support_admin_leftsidebar'); ?>
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">


                        <div class="Social-login-box payment_bx">

                            <?php echo $this->element('message'); ?>
                            <div class="form-group row">
							
								<div class="row">
								
								
									<div class="col-sm-4">
										<div class="panel panel-primary">
											<div class="panel-heading">
												<div class="row">
													<div class="col-xs-3">
														<i class="fa fa-users fa-5x"></i>
													</div>
													<div class="col-xs-9 text-right">
														<div class="huge"><?php echo $todayUserCount[0]["total_users"]?></div>
														<div>Today's Users</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									
									
									<div class="col-sm-4">
										<div class="panel panel-primary">
											<div class="panel-heading">
												<div class="row">
													<div class="col-xs-3">
														<i class="fa fa-users fa-5x"></i>
													</div>
													<div class="col-xs-9 text-right">
														<div class="huge"><?php echo $weekUserCount[0]["total_users"]?></div>
														<div>This Week's Users</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									
									
									<div class="col-sm-4">
										<div class="panel panel-primary">
											<div class="panel-heading">
												<div class="row">
													<div class="col-xs-3">
														<i class="fa fa-users fa-5x"></i>
													</div>
													<div class="col-xs-9 text-right">
														<div class="huge"><?php echo $monthUserCount[0]["total_users"]?></div>
														<div>This Month's Users</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									
									
								</div>
								
								
								<div class="row">
								
								
									<div class="col-sm-4">
										<div class="panel panel-danger">
											<div class="panel-heading">
												<div class="row">
													<div class="col-xs-3">
														<i class="fa fa-users fa-5x"></i>
													</div>
													<div class="col-xs-9 text-right">
														<div class="huge"><?php echo $todayUniqueUserCount[0]["total_users"]?></div>
														<div>Today's Unique Users</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									
									
									<div class="col-sm-4">
										<div class="panel panel-danger">
											<div class="panel-heading">
												<div class="row">
													<div class="col-xs-3">
														<i class="fa fa-users fa-5x"></i>
													</div>
													<div class="col-xs-9 text-right">
														<div class="huge"><?php echo $weekUniqueUserCount[0]["total_users"]?></div>
														<div>This Week's Unique Users</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									
									
									<div class="col-sm-4">
										<div class="panel panel-danger">
											<div class="panel-heading">
												<div class="row">
													<div class="col-xs-3">
														<i class="fa fa-users fa-5x"></i>
													</div>
													<div class="col-xs-9 text-right">
														<div class="huge"><?php echo $monthUniqueUserCount[0]["total_users"]?></div>
														<div>This Month's Unique Users</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									
									
								</div>
								
								
								<div class="row">
								
									<form id="searchForm">
									
										<div class="col-sm-3">
											<div class="form-group">
												<div class="col-sm-12">
													<label>Select Thinapp</label>
													<select class="form-control cnt" id="searchByThinapp">
														<option value="">Please select</option>
														<?php foreach($thinapps as $id => $name){ ?>
														<option value="<?php echo $id; ?>" <?php echo ($thinappID == $id)?"selected":'';?> ><?php echo $name; ?></option>
														<?php } ?>
													</select>
												</div>
											</div>
										</div>
										
										<div class="col-sm-3">
											<div class="form-group">
												<div class="col-sm-12">
													<label>Search Start Date</label>
													<input type="text" value="<?php echo $startDate; ?>" class="form-control cnt" required = "required" id="searchByDate"> 
												</div>
											</div>
										</div>
										
										<div class="col-sm-3">
											<div class="form-group">
												<div class="col-sm-12">
													<label>Search End Date</label>
													<input type="text" value="<?php echo $endDate; ?>" class="form-control cnt" required = "required" id="searchByEndDate"> 
												</div>
											</div>
										</div>
										
										<div class="col-sm-3">
											<div class="form-group">
												<div class="col-sm-12">
													<button class="searchBtn btn btn-info form-control" type="submit" >Search</button>
												</div>
											</div>	
										</div>
										
									</form>
									
								</div>
								
								
								
								
										
										
								<div class="row">
									<div class="col-sm-offset-2 col-sm-4">
										<div class="panel panel-warning">
											<div class="panel-heading">
												<div class="row">
													<div class="col-xs-3">
														<i class="fa fa-users fa-5x"></i>
													</div>
													<div class="col-xs-9 text-right">
														<div class="huge"><?php echo $userCount[0]["total_users"]?></div>
														<div>Total Users</div>
													</div>
												</div>
											</div>
											<a href="<?php echo $this->Html->url(array('controller' => 'supp','action' =>'user_statics_details'),true)."/".$startDate."_".$endDate."/".$thinappID; ?>">
												<div class="panel-footer">
													<span class="pull-left">View Details</span>
													<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
													<div class="clearfix"></div>
												</div>
											</a>
										</div>
									</div>
									<div class=" col-sm-4">
										<div class="panel panel-warning">
											<div class="panel-heading">
												<div class="row">
													<div class="col-xs-3">
														<i class="fa fa-users fa-5x"></i>
													</div>
													<div class="col-xs-9 text-right">
														<div class="huge"><?php echo $uniqueUserCount[0]["unique_users"]?></div>
														<div>Unique Users</div>
													</div>
												</div>
											</div>
											<a href="<?php echo $this->Html->url(array('controller' => 'supp','action' =>'user_statics_details'),true)."/".$startDate."_".$endDate."/unique"."/".$thinappID; ?>">
												<div class="panel-footer">
													<span class="pull-left">View Details</span>
													<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
													<div class="clearfix"></div>
												</div>
											</a>
										</div>
									</div>
									
								</div>
									
								</div>
                            </div>
                        </div>
                            
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
				var thinappVal = $("#searchByThinapp").val();
				window.location.href = "<?php echo $this->Html->url(array('controller' => 'supp','action' =>'get_user_statics'),true); ?>/"+dateVal+"_"+dateEndVal+"/"+thinappVal;	
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


