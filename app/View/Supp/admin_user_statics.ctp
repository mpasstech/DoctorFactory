<?php
$login = $this->Session->read('Auth.User');
//echo $date;
?>
<style>
    .numContainer{text-align: center;}
    .totalTextContainer{text-align: center; font-weight: bold;}
    .totalStaticsContainer{border: 1px solid black; border-radius: 3px; margin-right: 2px; margin-top: 75px; }
	.submitBtnStats{margin-top: 36px;}
	#dayContainer{ display: <?php echo (isset($intervalType) && ($intervalType == 'day'))?"block":"none"; ?>;}
	#yearContainer{ display: <?php echo (isset($intervalType) && ($intervalType == 'year'))?"block":"none"; ?>;}
	#monthContainer{ display: <?php echo (isset($intervalType) && ($intervalType == 'month'))?"block":"none"; ?>;}
	#weekContainer{ display: <?php echo (isset($intervalType) && ($intervalType == 'week'))?"block":"none"; ?>;}
</style>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="http://cdn.datatables.net/responsive/1.0.2/css/dataTables.responsive.css"/>
    

<link rel="stylesheet" href="http://cdn.datatables.net/plug-ins/a5734b29083/integration/bootstrap/3/dataTables.bootstrap.css"/>
<link rel="stylesheet" href="http://cdn.datatables.net/responsive/1.0.2/css/dataTables.responsive.css"/>
    

<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
<script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.3/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="//cdn.datatables.net/responsive/1.0.2/js/dataTables.responsive.js"></script>
<script type="text/javascript" language="javascript" src="//cdn.datatables.net/plug-ins/a5734b29083/integration/bootstrap/3/dataTables.bootstrap.js"></script>

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
                            <div class="row">
                                <div class="col-md-3 col-md-offset-4">
                                    <h3>Total Download Status</h3>
                                </div>
                                <div class="clear"></div>
                                <div class="col-md-2 col-md-offset-2 totalStaticsContainer">
                                    <div class="numContainer"><?php echo $totalDownloads; ?></div>
                                    <div class="totalTextContainer">Total Downloads</div>
                                </div>
                                <div class="col-md-2 totalStaticsContainer">
                                    <div class="numContainer"><?php echo $totalActive; ?></div>
                                    <div class="totalTextContainer">Total Active</div>
                                </div>
                                <div class="col-md-5">
                                    <div id="myfirstchart" style="height: 250px;"></div>
                                </div>
                            </div>
							<div class="row">
									<div class="col-md-4 col-md-offset-4">
										<h3>All Total/Active Download</h3>
									</div>
									<div class="clear"></div>
								
								<?php echo $this->Form->create('searchAllApp',array('type'=>'file','method'=>'post')); ?>
									<div class="form-group col-md-5">
										<label for="thinapp">Select Thinapp:</label>
										<?php echo $this->Form->input('thinapp',array('type'=>'select','empty'=>'Please select','label'=>false,'options'=>$thinappList,'class'=>'form-control','id'=>'thinapp')); ?>
									</div>
									<div class="form-group col-md-5">
										<label for="selectDate">Select Date:</label>
										<?php echo $this->Form->input('date',array('type'=>'text','placeholder'=>'Select Date','id'=>'selectDate','label'=>false,'class'=>'form-control')); ?>
									</div>
									<div class="form-group col-md-2">
										<button type="submit" class="btn btn-info submitBtnStats">Submit</button>
									</div>
								<?php echo $this->Form->end(); ?>	
									<div class="clear"></div>
								<table id="example" class="display" width="100%" cellspacing="0">
									<thead>
										<tr>
											<th>#</th>
											<th>App Name</th>
											<th>Total Users</th>
											<th>Active Users</th>
											<th>Active(%)</th>
										</tr>
									</thead>
									<tfoot>
										<tr>
											<th>#</th>
											<th>App Name</th>
											<th>Total Users</th>
											<th>Active Users</th>
											<th>Active(%)</th>
										</tr>
									</tfoot>
									<tbody>
									<?php
										$n = 1;
										foreach($allAppsTotal AS $thinappID => $appsTotal){
									?>
										<tr>
											<td><?php echo $n++; ?></td>
											<td><?php echo isset($thinappList[$thinappID])?$thinappList[$thinappID]:''; ?></td>
											<td><?php echo $total = isset($appsTotal)?$appsTotal:0; ?></td>
											<td><?php echo $active = isset($allAppsTotalInactive[$thinappID])?($total-$allAppsTotalInactive[$thinappID]):$total; ?></td>
											<td><?php echo round( ($active/$total) * 100); ?>%</td>
										</tr>
									<?php } ?>	
									</tbody>
								</table>
								<div class="clear"></div>
							</div>
                            <div class="clear"></div>
							<div class="row">
									<div class="col-md-4 col-md-offset-4">
										<h3>Appwise Total/Active Download</h3>
									</div>
									<div class="clear"></div>
									<div>
										<div class="col-md-3">
											<button type="button" class="btn btn-info" id="day">Day</button>
										</div>
										<div class="col-md-3">
											<button type="button" class="btn btn-info" id="week">Week</button>
										</div>
										<div class="col-md-3">
											<button type="button" class="btn btn-info" id="month">Month</button>
										</div>
										<div class="col-md-3">
											<button type="button" class="btn btn-info" id="year">Year</button>
										</div>
									</div>
									<div class="clear"></div>
									<div id="dayContainer">
										<form method="POST" id="dateForm">
											<div class="form-group col-md-5">
												<label for="selectDate">Start Date:</label>
												<input type="text" readonly="readonly" value="<?php echo isset($startDate)?$startDate:""; ?>" placeholder="Select Start Date" class="form-control" id="startDate" name="startDate" required="required">
											</div>
											<div class="form-group col-md-5">
												<label for="selectDate">End Date:</label>
												<input type="text" readonly="readonly" value="<?php echo isset($endDate)?$endDate:""; ?>" placeholder="Select End Date" class="form-control" id="endDate" name="endDate" required="required">
												<input type="hidden" name="intervalType" value="day">
											</div>
											<div class="form-group col-md-2">
												<button type="submit" class="btn btn-info submitBtnStats">Submit</button>
											</div>
										</form>	
									</div>
									<div class="clear"></div>
									<div id="weekContainer">
										<form method="POST" id="weekForm">
											<div class="form-group col-md-5">
												<label for="selectDate">Start Date:</label>
												<input type="text" readonly="readonly" value="<?php echo isset($startWeek)?$startWeek:""; ?>" placeholder="Select Start Date" class="form-control" id="startWeek" name="startWeek" required="required">
											</div>
											<div class="form-group col-md-5">
												<label for="selectDate">End Date:</label>
												<input type="text" readonly="readonly" value="<?php echo isset($endWeek)?$endWeek:""; ?>" placeholder="Select End Date" class="form-control" id="endWeek" name="endWeek" required="required">
												<input type="hidden" name="intervalType" value="week">
											</div>
											<div class="form-group col-md-2">
												<button type="submit" class="btn btn-info submitBtnStats">Submit</button>
											</div>
										</form>	
									</div>
									<div class="clear"></div>
									<div id="monthContainer">
										<form method="POST" id="monthForm">
											<div class="form-group col-md-5">
												<label for="selectDate">Start Month:</label>
												<input type="text" readonly="readonly" value="<?php echo isset($startMonth)?$startMonth:""; ?>" placeholder="Select Start Month" class="form-control" id="startMonth" name="startMonth" required="required">
											</div>
											<div class="form-group col-md-5">
												<label for="selectDate">End Month:</label>
												<input type="text" readonly="readonly" value="<?php echo isset($endMonth)?$endMonth:""; ?>" placeholder="Select End Month" class="form-control" id="endMonth" name="endMonth" required="required">
												<input type="hidden" name="intervalType" value="month">
											</div>
											<div class="form-group col-md-2">
												<button type="submit" class="btn btn-info submitBtnStats">Submit</button>
											</div>
										</form>
									</div>
									<div class="clear"></div>
									<div id="yearContainer">
										<form method="POST" id="yearForm">
											<div class="form-group col-md-5">
												<label for="selectDate">Start Year:</label>
												<input type="text" readonly="readonly" value="<?php echo isset($startMonth)?$startMonth:""; ?>" placeholder="Select Start Year" class="form-control" id="startYear" name="startYear" required="required">
											</div>
											<div class="form-group col-md-5">
												<label for="selectDate">End Year:</label>
												<input type="text" readonly="readonly" value="<?php echo isset($endYear)?$endYear:""; ?>" placeholder="Select End Year" class="form-control" id="endYear" name="endYear" required="required">
												<input type="hidden" name="intervalType" value="year">
											</div>
											<div class="form-group col-md-2">
												<button type="submit" class="btn btn-info submitBtnStats">Submit</button>
											</div>
										</form>
									</div>
									<div class="clear"></div>
									
									<?php if( isset($intervalType) && ($intervalType == 'day') ){ ?>
										<table id="dayTable" class="display" width="100%" cellspacing="0">
											<thead>
												<tr>
													<th>#</th>
													<th>App Name</th>
													<?php foreach($interval as $date){ ?>
													<th><?php echo $date; ?></th>
													<?php } ?>
												</tr>
											</thead>
											<tfoot>
												<tr>
													<th>#</th>
													<th>App Name</th>
													<?php foreach($interval as $date){ ?>
													<th><?php echo $date; ?></th>
													<?php } ?>
												</tr>
											</tfoot>
											<tbody>
											<?php
												$n = 1;
												foreach($dataToSend AS $allAppsData){ ?>
											<tr>
													<td><?php echo $n++; ?></td>
											<?php foreach($allAppsData AS $appsData){ ?>
													<td><?php echo $appsData; ?></td>
											<?php } ?>
											</tr>
											<?php } ?>	
											</tbody>
										</table>
										<div class="clear"></div>
									<?php } ?>
									
									<?php if( isset($intervalType) && ($intervalType == 'week') ){ ?>
										<table id="dayTable" class="display" width="100%" cellspacing="0">
											<thead>
												<tr>
													<th>#</th>
													<th>App Name</th>
													<?php foreach($interval as $date){ ?>
													<th><?php echo $date; ?></th>
													<?php } ?>
												</tr>
											</thead>
											<tfoot>
												<tr>
													<th>#</th>
													<th>App Name</th>
													<?php foreach($interval as $date){ ?>
													<th><?php echo $date; ?></th>
													<?php } ?>
												</tr>
											</tfoot>
											<tbody>
											<?php
												$n = 1;
												foreach($dataToSend AS $allAppsData){ ?>
											<tr>
													<td><?php echo $n++; ?></td>
											<?php foreach($allAppsData AS $appsData){ ?>
													<td><?php echo $appsData; ?></td>
											<?php } ?>
											</tr>
											<?php } ?>	
											</tbody>
										</table>
										<div class="clear"></div>
									<?php } ?>
									
									<?php if( isset($intervalType) && ($intervalType == 'month') ){ ?>
										<table id="dayTable" class="display" width="100%" cellspacing="0">
											<thead>
												<tr>
													<th>#</th>
													<th>App Name</th>
													<?php foreach($interval as $date){ ?>
													<th><?php echo $date; ?></th>
													<?php } ?>
												</tr>
											</thead>
											<tfoot>
												<tr>
													<th>#</th>
													<th>App Name</th>
													<?php foreach($interval as $date){ ?>
													<th><?php echo $date; ?></th>
													<?php } ?>
												</tr>
											</tfoot>
											<tbody>
											<?php
												$n = 1;
												foreach($dataToSend AS $allAppsData){ ?>
											<tr>
													<td><?php echo $n++; ?></td>
											<?php foreach($allAppsData AS $appsData){ ?>
													<td><?php echo $appsData; ?></td>
											<?php } ?>
											</tr>
											<?php } ?>	
											</tbody>
										</table>
										<div class="clear"></div>
									<?php } ?>
									
									<?php if( isset($intervalType) && ($intervalType == 'year') ){ ?>
										<table id="dayTable" class="display" width="100%" cellspacing="0">
											<thead>
												<tr>
													<th>#</th>
													<th>App Name</th>
													<?php foreach($interval as $date){ ?>
													<th><?php echo $date; ?></th>
													<?php } ?>
												</tr>
											</thead>
											<tfoot>
												<tr>
													<th>#</th>
													<th>App Name</th>
													<?php foreach($interval as $date){ ?>
													<th><?php echo $date; ?></th>
													<?php } ?>
												</tr>
											</tfoot>
											<tbody>
											<?php
												$n = 1;
												foreach($dataToSend AS $allAppsData){ ?>
											<tr>
													<td><?php echo $n++; ?></td>
											<?php foreach($allAppsData AS $appsData){ ?>
													<td><?php echo $appsData; ?></td>
											<?php } ?>
											</tr>
											<?php } ?>	
											</tbody>
										</table>
										<div class="clear"></div>
									<?php } ?>
									
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
<script>
    Morris.Bar({
        element: 'myfirstchart',
        data: [
            { y: '', a: <?php echo $totalDownloads; ?>, b: <?php echo $totalActive; ?> }
        ],
        xkey: 'y',
        ykeys: ['a', 'b'],
        labels: ['Total Downloads', 'Total Active']
    });
	
	$(document).ready(function() {
		$('#example').DataTable({responsive: true});
		$('#dayTable').DataTable({responsive: true});
	} );
	
	$('#selectDate').datepicker({
		format: 'yyyy-mm-dd',
		endDate: 'today'
	});
	$(document).ready(function(){
		$(document).on("click","#day",function(){
			$("#yearContainer").hide();
			$("#monthContainer").hide();
			$("#weekContainer").hide();
			$("#dayContainer").show();
		});
		$(document).on("click","#week",function(){
			$("#yearContainer").hide();
			$("#monthContainer").hide();
			$("#weekContainer").show();
			$("#dayContainer").hide();
		});
		$(document).on("click","#month",function(){
			$("#yearContainer").hide();
			$("#monthContainer").show();
			$("#weekContainer").hide();
			$("#dayContainer").hide();
		});
		$(document).on("click","#year",function(){
			$("#yearContainer").show();
			$("#monthContainer").hide();
			$("#weekContainer").hide();
			$("#dayContainer").hide();
		});
	});
	
		$(document).ready(function(){
			$("#startDate").datepicker({
				autoclose: true,
				format: 'yyyy-mm-dd',
				endDate: 'today'
			}).on('changeDate', function (selected) {
				var minDate = new Date(selected.date.valueOf());
				$('#endDate').datepicker('setStartDate', minDate);
			});

			$("#endDate").datepicker({
				autoclose: true,
				format: 'yyyy-mm-dd',
				endDate: 'today'
			}).on('changeDate', function (selected) {
					var maxDate = new Date(selected.date.valueOf());
					$('#startDate').datepicker('setEndDate', maxDate);
			});
		});
		
		$(document).ready(function(){
			$("#startWeek").datepicker({
				autoclose: true,
				format: 'yyyy-mm-dd',
				endDate: 'today',
				daysOfWeekDisabled: [1,2,3,4,5,6]
			}).on('changeDate', function (selected) {
				var minDate = new Date(selected.date.valueOf());
				minDate.setDate(minDate.getDate()+1);
				$('#endWeek').datepicker('setStartDate', minDate);
			});

			$("#endWeek").datepicker({
				autoclose: true,
				format: 'yyyy-mm-dd',
				endDate: 'today',
				daysOfWeekDisabled: [0,1,2,3,4,5]
			}).on('changeDate', function (selected) {
					var maxDate = new Date(selected.date.valueOf());
					maxDate.setDate(maxDate.getDate()-1);
					$('#startWeek').datepicker('setEndDate', maxDate);
			});
		});
		
		$(document).ready(function(){
			
			$("#startMonth").datepicker({
				autoclose: true,
				format: "yyyy-mm",
				viewMode: "months", 
				minViewMode: "months",
				endDate: 'today'
			}).on('changeDate', function (selected) {
				var minDate = new Date(selected.date.valueOf());
				minDate.setDate(minDate.getDate()+30);
				$('#endMonth').datepicker('setStartDate', minDate);
			});

			$("#endMonth").datepicker({
				autoclose: true,
				format: "yyyy-mm",
				viewMode: "months", 
				minViewMode: "months",
				endDate: 'today'
			}).on('changeDate', function (selected) {
					var maxDate = new Date(selected.date.valueOf());
					maxDate.setDate(maxDate.getDate()-30);
					$('#startMonth').datepicker('setEndDate', maxDate);
			});
			
		});
		
		$(document).ready(function(){
			
			$("#startYear").datepicker({
				autoclose: true,
				format: "yyyy",
				viewMode: "years", 
				minViewMode: "years",
				endDate: 'today'
			}).on('changeDate', function (selected) {
				var minDate = new Date(selected.date.valueOf());
				minDate.setDate(minDate.getDate()+30);
				$('#endYear').datepicker('setStartDate', minDate);
			});

			$("#endYear").datepicker({
				autoclose: true,
				format: "yyyy",
				viewMode: "years", 
				minViewMode: "years",
				endDate: 'today'
			}).on('changeDate', function (selected) {
					var maxDate = new Date(selected.date.valueOf());
					maxDate.setDate(maxDate.getDate()-30);
					$('#startYear').datepicker('setEndDate', maxDate);
			});
			
		});
</script>