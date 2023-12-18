<?php
//pr($dataToShow);
//pr($parentData);
//die;
?>



<html>
	<head>
		<?php echo $this->Html->script(array('jquery.js','loader.js','bootstrap.min.js',
	'comman.js')); ?>
	
	<?php
echo $this->Html->css(array(
       'bootstrap.min.css',
       ),array("media"=>'all'));
?>
	
		<script>
					google.charts.load('current', {packages:["orgchart"]});
					google.charts.setOnLoadCallback(drawChart);

					  function drawChart() {
						var data = new google.visualization.DataTable();
						data.addColumn('string', 'From');
						data.addColumn('string', 'To');
						data.addColumn('string', 'ToolTip');

						// For each orgchart box, provide the name, manager, and tooltip to show.
						data.addRows([
						
					<?php foreach($dataToShow as $key => $dataToShowArr){ 
						  if($key == 0)
						  {?>
							[{v:'<?php echo $parentData['mobile']; ?>', f:'<?php echo $parentData['username']; ?><div style="background-color:red; color:white; font-style:bold;"><?php echo $parentData['refreal_points']; ?></div>'},'', ''],  
					<?php } ?>
							
							<?php
							
								if($dataToShowArr['status'] == 'DENIED')
								{
									$tag = '<p style="font-style:bold;">'.$dataToShowArr['reffered_mobile'].'</p>';
									$tag .= '<p><div style="background-color:red; color:white; font-style:bold;">'.$dataToShowArr['status'].'</div></p>';
									if($dataToShowArr['is_reffered_already'] == 'YES')
									{
										$tag .= '<p><div style="background-color:red; color:white; font-style:bold;">ALREADY REFERRED</div></p>';
									}
									$tag .='<p><button class="btn btn-primary btn-xs" row-id="'.$dataToShowArr['id'].'" id="rerefer">&nbsp;Re Refer&nbsp;</button></p>';
									$tag .='<p><button class="btn btn-primary btn-xs" row-id="'.$dataToShowArr['id'].'" id="getDetail">&nbsp;Detail&nbsp;</button></p>';
								}
								else if($dataToShowArr['status'] == 'NEW')
								{
									$tag = '<p style="font-style:bold;">'.$dataToShowArr['reffered_mobile'].'</p>';
									$tag .= '<p><div style="background-color:orange; color:white; font-style:bold;">'.$dataToShowArr['status'].'</div></p>';
									if($dataToShowArr['is_reffered_already'] == 'YES')
									{
										$tag .= '<p><div style="background-color:red; color:white; font-style:bold;">ALREADY REFERRED</div></p>';
									}
									$tag .='<p><button class="btn btn-primary btn-xs" row-id="'.$dataToShowArr['id'].'" id="rerefer">&nbsp;Re Refer&nbsp;</button></p>';
									$tag .='<p><button class="btn btn-primary btn-xs" row-id="'.$dataToShowArr['id'].'" id="getDetail">&nbsp;Detail&nbsp;</button></p>';
								}
								else if($dataToShowArr['status'] == 'CONTACTED')
								{
									$tag = '<p style="font-style:bold;">'.$dataToShowArr['reffered_mobile'].'</p>';
									$tag .= '<p><div style="background-color:gray; color:white; font-style:bold;">'.$dataToShowArr['status'].'</div></p>';
									if($dataToShowArr['is_reffered_already'] == 'YES')
									{
										$tag .= '<p><div style="background-color:red; color:white; font-style:bold;">ALREADY REFERRED</div></p>';
									}
									$tag .='<p><button class="btn btn-primary btn-xs" row-id="'.$dataToShowArr['id'].'" id="rerefer">&nbsp;Re Refer&nbsp;</button></p>';
									$tag .='<p><button class="btn btn-primary btn-xs" row-id="'.$dataToShowArr['id'].'" id="getDetail">&nbsp;Detail&nbsp;</button></p>';
								}
								else if($dataToShowArr['status'] == 'REREFERRED')
								{
									$tag = '<p style="font-style:bold;">'.$dataToShowArr['reffered_mobile'].'</p>';
									$tag .= '<p><div style="background-color:orange; color:white; font-style:bold;">RE-REFERRED</div></p>';
									if($dataToShowArr['is_reffered_already'] == 'YES')
									{
										$tag .= '<p><div style="background-color:red; color:white; font-style:bold;">ALREADY REFERRED</div></p>';
									}
									$tag .='<p><button class="btn btn-primary btn-xs"  row-id="'.$dataToShowArr['id'].'" id="rerefer">&nbsp;Re Refer&nbsp;</button></p>';
									$tag .='<p><button class="btn btn-primary btn-xs"  row-id="'.$dataToShowArr['id'].'" id="getDetail">&nbsp;Detail&nbsp;</button></p>';
								}
								else if($dataToShowArr['status'] == 'CONVERTED')
								{
									$tag = '<p style="font-style:bold;">'.$dataToShowArr['reffered_mobile'].'</p>';
									$tag .= '<p><div style="background-color:blue; color:white; font-style:bold;">'.$dataToShowArr['status'].'</div></p>';
									if($dataToShowArr['is_reffered_already'] == 'YES')
									{
										$tag .= '<p><div style="background-color:red; color:white; font-style:bold;">ALREADY REFERRED</div></p>';
									}
									$tag .='<p><button class="btn btn-primary btn-xs"  row-id="'.$dataToShowArr['id'].'" id="getDetail">&nbsp;Detail&nbsp;</button></p>';
								}
								else
								{
									$tag = '<p style="font-style:bold;">'.$dataToShowArr['reffered_mobile'].'</p>';
									$tag .= '<p><div style="background-color:green; color:white; font-style:bold;">'.$dataToShowArr['status'].'</div></p>';
									if($dataToShowArr['is_reffered_already'] == 'YES')
									{
										$tag .= '<p><div style="background-color:red; color:white; font-style:bold;">ALREADY REFERRED</div></p>';
									}
									$tag .='<p><button class="btn btn-primary btn-xs" row-id="'.$dataToShowArr['id'].'" id="getDetail">&nbsp;Detail&nbsp;</button></p>';
								}
							
							?>
							
					[{v:'<?php echo $dataToShowArr['reffered_mobile']; ?>', f:'<?php echo $dataToShowArr['reffered_name'].$tag; ?>'},'<?php echo $dataToShowArr['mobile']; ?>', ''],
										
						<?php } ?>
						  
						]);

						// Create the chart.
						 //data.setRowProperty(0, 'style', 'background: #2a6496');
						 //data.setRowProperty(0, 'style', 'color: #FFFFFF');
						var chart = new google.visualization.OrgChart(document.getElementById('chart_div'));
						// Draw the chart, setting the allowHtml option to true for the tooltips.
						chart.draw(data, {allowHtml:true,nodeClass: 'myNodeClass'});
					  }
		</script>
	</head>
	<body>
	
	<?php if(empty($dataToShowArr)){?>
	<div class="errorMessage">
		<p>You haven't referred anyone yet!</p>
	</div>					
	<?php } ?>

	
		<div id="chart_div"></div>
		
		
		
<div class="modal fade" id="detailModal" role="dialog">

    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">View Detail</h4>
            </div>
            <div class="modal-body">

                <p id="detailContainer"></p>

            </div>
        </div>
    </div>

</div>		
		
		
		
		<style>
		.modal-header{
			background-color:#2b669a;
			color:#FFFFFF;
		}
		.close{
			color:#FFFFFF;
		}

		.errorMessage {

			font-size: small;
			padding: 10px;
			text-align: center;
			color: grey;
			background-color: #FFFFFF;

		}
		.myNodeClass {
			text-align: center;
			vertical-align: middle;
			font-family: arial,helvetica;
			cursor: default;
			-moz-border-radius: 5px;
			-webkit-border-radius: 5px;
			-webkit-box-shadow: rgba(0, 0, 0, 0.5) 3px 3px 3px;
			-moz-box-shadow: rgba(0, 0, 0, 0.5) 3px 3px 3px;
			background-color: #b0d7ee;
			background: -webkit-gradient(linear, left top, left bottom, from(#b0d7ff), to(#b0d7ee));
		}
		
		.google-visualization-orgchart-nodesel{
			border: none !important;
		}

        .modal-open .modal-dialog{
        width:335px;
        min-width:335px;
        }
		</style>
		
		<script>		
			$(document).on("click","#rerefer",function(){
				var rowID = $(this).attr('row-id');
				var thisButton = $(this);
				 var baseurl = '<?php echo Router::url('/',true); ?>';
				$.ajax({
                    url: baseurl+'/folder/rerefer',
                    data:{rowID:rowID},
                    type:'POST',
                    beforeSend:function(){
                        $(thisButton).button('loading').html('Wait...');
                    },
                    success: function(result){
						$(thisButton).button('reset');
                        location.reload(true)
                    }
                });
			});
			
			
			$(document).on("click","#getDetail",function(){
				var rowID = $(this).attr('row-id');
				var thisButton = $(this);
				 var baseurl = '<?php echo Router::url('/',true); ?>';
				$.ajax({
                    url: baseurl+'/folder/get_refer_detail',
                    data:{rowID:rowID},
                    type:'POST',
                    beforeSend:function(){
                        $(thisButton).button('loading').html('Wait...');
                    },
                    success: function(result){
						$(thisButton).button('reset');
						if(result == ''){
							result = "No details found!";
						}
                        $("#detailContainer").html(result);
						$("#detailModal").modal('show');
                    }
                });
			});
			
		</script>
		
	</body>
</html>