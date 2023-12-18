<?php echo $this->Html->script(array('jquery.form')); ?>
<header> 
  <!-- <div class="logo"><img src="images/logo.png" alt="" /> </div> -->
  <div class="inner-top-header">
    <div class="warrper">
      <div class="logo"><a href="<?php echo SITE_URL; ?>" ><?php echo $this->Html->image('logo.png',array('alt'=>'Letout')); ?> </a></div>
      <?php if(isset($loggedIn) && !empty($loggedIn)){ ?>
      <nav class="clearfix"> <i class="nav_icon" id="menu"><?php echo $this->Html->image('menu_icon.png',array('alt'=>'Letout')); ?></i>
        <ul class="clearfix inner-menu" id="clickmenu">
            <li><?php echo $this->Html->link("Welcome   ".$userdata['User']['username'],array('controller'=>'users','action'=>'dashboard'),array('class'=>'introlink')); ?></li>
            <li><a href="<?php echo SITE_URL."users/dashboard"; ?>" class="introlink"><!--<img src="<?php //echo $userdata['User']['image']; ?>">--><i class="fa fa-user"></i></a></li>
            <li class="dropdown"><a href="javascript:void(0)" class="" id="Submenu"><i class="fa fa-cog"></i></a>
                <div class="hdr_dropdown" id="SubmenuBtm">
                    <div class="user-dash">
                        <ul>
                            <li> <span>Credit: </span>
                              <p>500</p>
                              <div class="drop_right"><button class="drop_link">Add Credit</button></div>
                              <!--<div class="drop_right"><a href="javascript:void(0)" class="drop_link">Add Credit</a></div>-->
                            </li>
                            <li> <span>Token: </span>
                              <p><span id="app_key_show1">**************</span><span id="app_key_show2" style="display: none;"><?php echo $userdata['User']['app_key']; ?></span></p>
                              <!--<p><span id="app_key_show1">********</span></p>-->
                              <div class="drop_right">
                                  <input type="button" value="Show" id="show_app_key" class="show-app-key">
                              </div>
                              <!--<div>
                                  <input type="button" value="Show" id="show_app_key" class="show-app-key">
                                  <button class="drop_link" id="genearte_new_token">Generate New Token</button>
                              </div>-->
                            </li>
                          
                        </ul>
                        <div class="hdr_update_link">
                          <a href="<?php echo SITE_URL."users/settings"; ?>" class="introlink">Update Profile</a>
                          <!--<a href="<?php echo SITE_URL."users/update_image"; ?>" class="introlink">Update Profile Image</a>-->
                        </div>
                    </div>
                </div>
            </li>
            <li><a href="<?php echo SITE_URL."users/logout"; ?>" class="introlink">Logout</i></a></li>
        </ul>
      </nav>
      <?php }else{ ?>
      <div class="login-frms">
        <?php echo $this->Form->create('User',array('contoller'=>'users','action'=>'signin'),array('inputDefaults'=>array('required'=>false))); ?>
        <?php echo $this->Form->input('mobile_number',array('class'=>'headr-login-inpt','label'=>false,'required'=>false,'placeholder'=>'Mobile')); ?>
        <?php echo $this->Form->input('password_1',array('type'=>'password','class'=>'headr-login-inpt','label'=>false,'required'=>false,'placeholder'=>'Password')); ?>
        <?php echo $this->Form->submit('Login',array('type'=>'button','id'=>'login_submit')); ?> <?php echo $this->Form->end(); ?>
      </div>
      <?php } ?>
      <div class="clear"></div>
    </div>
  </div>
</header>
<script>
    $(document).ready(function(){
        $('#UserPassword1').keyup(function(e){
            if(e.keyCode == 13)
            {
                $('#login_submit').trigger("click");
            }
        });
        $('#login_submit').click(function(){
            $("#UserSigninForm").ajaxForm({
                dataType: "json",
                success: function(resp){
                    if(resp.status == 1){
                        window.location.href = '<?php echo SITE_URL."users/dashboard" ?>';
                    }else{
                        alert(resp.message);
                        return false;
                    }
                }
            }).submit();
        });
        $("#Submenu").click(function(){
            $("#SubmenuBtm").slideToggle()
        });
        $('#show_app_key').click(function(){
            if($(this).val()=='Show'){
                $('#show_app_key').val('Hide');//change the button label to be 'Show'
                $('#app_key_show1').hide();
                $('#app_key_show2').show();
            }else{
                $('#show_app_key').val('Show');//change the button label to be 'Hide'
                $('#app_key_show1').show();
                $('#app_key_show2').hide();
            }
            
        });
        
        $('#genearte_new_token').click(function(){
            
            $.ajax({
                'url':  "<?php echo SITE_URL; ?>users/genearte_new_token",
                'data':  {},
                'type' : 'get',
                'dataType' : 'html',
                'success': function(resp){
                    //console.log(resp);
                    if(resp != ''){
                        $('#app_key_show1').hide();
                        $('#app_key_show2').html(resp);
                        $('#app_key_show2').show();
                        $('#show_app_key').val('Hide');//change the button label to be 'Hide'
                    }else{
                        alert('Something went wrong! Please try again later.');
                        return false;
                    }
                }
            });
        });
    });
</script> 