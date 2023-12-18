<html>
	<head>
		<?php echo $this->Html->script(array('loader.js')); ?>
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
					<?php foreach($driveShare as $key => $driveShareArr){ 
						  if($key == 0)
						  {?>
							[{v:'<?php echo $driveShareArr['DriveShare']['share_from_mobile']; ?>', f:'<?php echo $driveShareArr['DriveShare']['share_from_mobile']; ?><div style="color:red; font-style:italic">Owner</div>'},
						   '', 'Owner'],  
					<?php	} ?>
					
					['<?php echo $driveShareArr['DriveShare']['share_with_mobile']; ?>', '<?php echo $driveShareArr['DriveShare']['share_from_mobile']; ?>', ''],
						<?php } ?>
						  
						]);

						// Create the chart.
						 //data.setRowProperty(0, 'style', 'background: #2a6496');
						 //data.setRowProperty(0, 'style', 'color: #FFFFFF');
						var chart = new google.visualization.OrgChart(document.getElementById('chart_div'));
						// Draw the chart, setting the allowHtml option to true for the tooltips.
						chart.draw(data, {allowHtml:true});
					  }
		</script>
	</head>
	<body>
		<div id="chart_div"></div>
	</body>
</html>