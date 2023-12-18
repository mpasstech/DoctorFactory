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
        }
        else
        {
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

function userCat(g_id)
{
     
    var group_id = g_id.value;
    var url1 ="<?php echo SITE_URL."admin/usermanager/view/"?>"+group_id;
    window.location=url1;
    
}

</script>

<?php echo $this->Session->flash('done'); ?>
<?php echo $this->Session->flash('error');?>
<!--/RIGHT-BOX-->
    <div class="right-box">
    <!--/HEADING-->
    <div class="top-box">
    <div class="tp-line">
    <p><img align="obsolete" src="<?php echo SITE_URL;?>img/admin_img/des-img2.png" alt=""/> Thin App Module </p>
    </div>
        
    <div class="button">
        <?php echo $this->Html->link('Add New', array('controller'=>'thinapps','action'=>'add/','admin'=>true),array('class'=>'Add-btn-user pull-right')); ?>
        
    </div>
    </div>
    
    <!--/ SEARCH BOX -->
<div class="select-all-box">
    <!-- CHACK BOX & DELETE TEXT -->  
<div class="chack-block">
    <p><input align="baseline" value="" type="checkbox" id="select_all"/></p>&nbsp; <p class="tex">Select All</p>
    <p class="del"><a href="" class="action-delete"> <i class="fa fa-trash-o"></i>&nbsp;Delete</a></p>
</div>
    <!-- SEARCH BOX --> 
    <div id="custom-search-input">
    <?php echo $this->Form->create('Thinapp',array('url'=>array('controller'=>'Thinapps','action' => 'view'),'admin'=>true)); ?>
      <?php
        $name = $this->Session->read('name');
        $user_id = $this->Session->read('user_id');
      ?>  
  <div class="form-group" style="width:auto;display: inline-block;margin-right: 15px;">
    <label for="email" style="font-weight:normal;">App Name</label>
    <input type="text" class="form-control" id="name" name="name" <?php echo $name!="" ? "value='$name'" : "";?> style="width:auto; float:none;">
  </div>
        
  <div class="form-group" style="width:auto;display: inline-block;margin-right: 15px;">
    <label for="email" style="font-weight:normal;">User Id</label>
    <input type="text" class="form-control" id="mobile" name="user_id" <?php echo $user_id!="" ? "value='$user_id'" : "";?> style="width:auto; float:none;">
  </div>
  <?php echo $this->Form->submit('Search',array('class'=>'Add-btn3-user')); ?>       
  <?php echo $this->Form->end();?>

    </div>
</div>
    
     <!--/ TABLE BOX -->
    <div class="table-box">
		
      <table class="table table-striped">
    
    <tbody>
        <?php echo $this->Form->create('Thinapps',array('url'=>array('controller'=>'Thinapps','action' => 'delete'),'id'=>'mainform', 'admin'=>true)); ?>
      <tr class="HEader">
	<td>&nbsp;</td>
        <td>App Name</td>
        <td>App ID</td>
        <td>Username</td>
        <td>Status</td>
        <td>Action</td>
      </tr>
      <!--TABLE ROW 1 -->
      
      <tr>
          <?php
            if(isset($users) && !empty($users))
            {
               foreach($users as $user)
               {
                
                
            ?>
	<td><input type="checkbox"  class="checkbox" name="deleteall[]" value="<?php echo h($user['Thinapp']['id']); ?>"/></td>
        <td><?php echo $user['Thinapp']['name']; ?></td>
        <td><?php echo $user['Thinapp']['app_id']; ?></td>
        <td><?php echo $user['Thinapp']['user_id']; ?></td>
        
        <td class="color1">
      <?php 
        if($user['Thinapp']['status']=='1'){
              // echo $this->Html->link("Active",array('controller'=>'Thinapps','action'=>'active/'.$user['Thinapp']['id'],'admin'=>true), array('class' => 'active'));
              echo "Active";
        }else{
             //  echo $this->Html->link("InActive",array('controller'=>'Thinapps','action'=>'active/'.$user['Thinapp']['id'],'admin'=>true),array('class' => "inactive"));
              echo "InActive";
        }
        ?>
        </td>
	
        <td>
           
                <?php echo $this->Html->link('', array('controller'=>'Thinapps','action'=>'view_user/'.$user['Thinapp']['id'],'admin'=>true), 
                                                 array('id' => 'eyebox','class'=>'fa fa-eye','title'=>'View'));?>
            &nbsp;&nbsp;&nbsp; 
            
            
                <?php echo $this->Html->link('', array('controller'=>'Thinapps','action'=>'edit/'.$user['Thinapp']['id'],'admin'=>true),
                                                 array('class'=>'fa fa-pencil','title'=>'Edit'));?>
            &nbsp; &nbsp;
            
            
                <?php echo $this->Html->link('', array('controller'=>'Thinapps','action'=>'delete/'.$user['Thinapp']['id'],'admin'=>true), 
                        array('class'=>'fa fa-trash-o','onclick'=>'return confirm("Do you really want to delete this User")','title'=>'Delete')); ?>    
             
       </td>
       </tr>
	<?php }
           }else{ ?>
           
       <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td> No User Found in this crietria...</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
           <td>&nbsp;</td>
       </tr>		
        <?php } ?>	
      
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
