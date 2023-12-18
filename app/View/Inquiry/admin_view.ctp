
<script language="javascript">
$(document).ready(function(){
    $('#select_all').on('click',function(){
        if(this.checked){
            $('.checkbox').each(function(){
                this.checked = true;
            });
        }else{
             $('.checkbox').each(function(){
                this.checked = false;
            });
        }
    });
    
    $('.checkbox').on('click',function(){
        if($('.checkbox:checked').length === $('.checkbox').length){
            $('#select_all').prop('checked',true);
        }else{
            $('#select_all').prop('checked',false);
        }
    });
});
</script>

<script>
$(document).ready(function() {
       $('.action-delete').click(function(){
	    var n = $(".checkbox:checked").length;
	    if(n == 0)
	    {
		alert("please select atleast one for delete.")
	    }
	    else{
		if(confirm('Are you Sure, want to delete Selected ?'))
		   $('#mainform').submit();
	    }
       });
});


</script>

<?php echo $this->Session->flash('done'); ?>
<?php echo $this->Session->flash('error');?>

    <!--/RIGHT-BOX-->
<div class="right-box">
    <!--/HEADING-->
<div class="top-box">
    <div class="tp-line">
    <p>View Inquiries </p>
    </div>
</div>
    <!--/ SEARCH BOX -->
    <div class="select-all-box">
    <!-- CHACK BOX & DELETE TEXT -->  
    <div class="chack-block">
        <p><input align="baseline" type="checkbox" id="select_all" value=""/></p>&nbsp; <p class="tex">Select All</p>
        <p class="del"><a href="" class="action-delete"> <i class="fa fa-trash-o"></i>&nbsp;Delete</a></p>
    </div>
    <!-- SEARCH BOX --> 
<div id="custom-search-input">
    <?php echo $this->Form->create('Inquiry',array('url'=>array('controller'=>'Inquiry','action' => 'view'),'admin'=>true)); ?>
    
  <div class="form-group" style="width:auto;display: inline-block;margin-right: 15px;">
    <label for="email" style="font-weight:normal;">Name</label>
    <input type="text" class="form-control" id="name" name="name" style="width:auto; float:none;">
  </div>
    
  <div class="form-group" style="width:auto;display: inline-block;margin-right: 15px;">
    <label for="email" style="font-weight:normal;">Email</label>
    <input type="email" class="form-control" id="email" name="email" style="width:auto; float:none;">
  </div>
    
 <?php echo $this->Form->submit('Search',array('class'=>'Add-btn3-user')); ?>       
 <?php echo $this->Form->end();?>
    </div>
    </div>
     <!--/ TABLE BOX -->
    <div class="table-box">
<table class="table table-striped">
    
    <tbody>
        <?php echo $this->Form->create('Inquiry',array('url'=>array('controller'=>'Inquiry','action' => 'delete'),'id'=>'mainform', 'admin'=>true)); ?>
      <tr class="HEader">
	<td>&nbsp;</td>
        <td>Name</td>
        <td>Email</td>
        <td>Message</td>
	<td>Action</td>
      </tr>
      <!--TABLE ROW 1 -->
      
    <tr>
          <?php
            if(isset($inquiries) && !empty($inquiries))
            {
               foreach($inquiries as $inquiry)
               {


            ?>
	<td><input type="checkbox"  class="checkbox" name="deleteall[]" value="<?php echo h($inquiry['Inquiry']['id']); ?>"/></td>
        <td><?php echo $inquiry['Inquiry']['name']?></td>
        <td><?php echo $inquiry['Inquiry']['email']; ?></td>
        <td><?php echo substr($inquiry['Inquiry']['message'],0,30)."..."; ?></td>
	<td>
           
                <?php echo $this->Html->link('', array('controller'=>'Inquiry','action'=>'view_inquiry/'.$inquiry['Inquiry']['id'],'admin'=>true), 
                                                 array('id' => 'eyebox','class'=>'fa fa-eye','title'=>'View'));?>
            &nbsp;&nbsp;&nbsp; 
            
            
                <?php echo $this->Html->link('', array('controller'=>'Inquiry','action'=>'sendIndividual/'.$inquiry['Inquiry']['id'],'admin'=>true),
                                                 array('class'=>'fa fa-pencil','title'=>'Reply Inquiry'));?>
            &nbsp; &nbsp;
            
            
                <?php echo $this->Html->link('', array('controller'=>'Inquiry','action'=>'delete/'.$inquiry['Inquiry']['id'],'admin'=>true), 
                        array('class'=>'fa fa-trash-o','onclick'=>'return confirm("Do you really want to delete this Inquiry")','title'=>'Delete')); ?>    
             
       </td>
    </tr>
    
    <?php
        }
     }else{
       ?>
    <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td> No Inquiry Found in this crietria...</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
    </tr>
     <?php }?> 
    </tbody>
  </table>
        
  <div class="pegination-block">
      <div class="pagi">
       <ul class="pagination">
    <?php
    echo $this->Paginator->prev('&laquo;', array('tag' => 'li', 'escape' => false), '<a href="#">&laquo;</a>', array('class' => 'prev disabled', 'tag' => 'li', 'escape' => false));
    $numbers = $this->Paginator->numbers(array('separator' => '', 'tag' => 'li', 'currentLink' => true, 'currentClass' => 'active', 'currentTag' => 'a'));
    $number = preg_replace("#\<li class=\"active\"\>([0-9]+)\<\/li\>#", "<li class=\"active\"><a href=''>$1</a></li>",$numbers);
    echo $number;
    echo $this->Paginator->next('&raquo;', array('tag' => 'li', 'escape' => false), '<a href="#">&raquo;</a>', array('class' => 'prev disabled', 'tag' => 'li', 'escape' => false));
    ?>
  </ul>
      </div>
      </div>
</div>      
</div>
    
 
                
				    