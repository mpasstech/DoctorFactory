<?php echo $this->Html->script('ckeditor/ckeditor');?>

<article class="module width_full">
            <header><h3>Add New Advertisement</h3></header>
<!-- end page-heading -->
  <table border="0" width="100%" cellpadding="0" cellspacing="0" id="content-table">
	<tr>
	<td id="tbl-border-left"></td>
	<td>
	<div id="content-table-inner">
         <div id="table-content">
	      <table border="0" width="100%" cellpadding="0" cellspacing="0">
		 <tr valign="top">
		    <td>
                   <?php require_once 'generic_add.ctp'; ?>
			 </td>
			  <td>

		  
			</td>
			</tr>
			<tr>

			<td>
			  <?php echo $this->Html->image('blank.gif',array( 'width'=>"695px" ,'height'=>"1px"));?>
			</td>
			<td></td>
			</tr>
			</table>
			<div class="clear"></div>
                      
		 </div>
		</td>
		<td id="tbl-border-right"></td>
	</tr>
	<tr>
		<th class="sized bottomleft"></th>
		<td id="tbl-border-bottom">&nbsp;</td>
		<th class="sized bottomright"></th>
	</tr>
	</table>
	<div class="clear">&nbsp;</div>

</div>
</div>

