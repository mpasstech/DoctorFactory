<SCRIPT language="javascript">
jQuery(document).ready(function() {
 
    jQuery("#selectall").click(function () {
          $('.case').attr('checked', this.checked);
    });
    jQuery(".case").click(function(){
 
        if(jQuery(".case").length == jQuery(".case:checked").length) {
	    $("#selectall").attr("checked", "checked");
        } else {
            jQuery("#selectall").removeAttr("checked");
        }
 
    });
});
</SCRIPT>

<script>
jQuery(document).ready(function() {
       jQuery('.action-delete').click(function(){
	    var n = jQuery(".case:checked").length;
	    if(n == 0)
	    {
		alert("please select atleast one for delete.")
	    }
	    else{
		if(confirm('Are you Sure, want to delete Selected ?'))
		   jQuery('#mainform').submit();
	    }
       });
});



</script>
<?php echo $this->Session->flash('done'); ?>
<?php echo $this->Session->flash('error'); ?>

<div class="right-box">
    
 <div class="top-box">
    <div class="tp-line">
    <p>View Templates </p>
    </div>
 </div>
    
        <!--/ TABLE BOX -->
    <div class="table-box">
<table class="table table-striped">
    
    <tbody>
      <tr class="HEader">
	<td>S No.</td>
        <td>Subject</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>Action</td>
      </tr>
      <!--TABLE ROW 1 -->
      <tr>
         <?php
            if(isset($templates) && !empty($templates))
            {
                   $i=1;
                   foreach($templates as $template)
                   {
           ?>
	<td><?php echo $i; ?></td>
        <td><?php echo $template['EmailTemplate']['subject'];?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <?php echo $this->Html->link('', array('controller'=>'Emailtemplates','action'=>'edit/'.$template['EmailTemplate']['id'],'admin'=>true),
                                           array('class'=>'fa fa-pencil','title'=>'Edit'));?>
      </td>
      </tr>
     <?php
        $i++;
           }
        } ?>
    </tbody>
  </table>
</div>
</div>
