<?php

    $login = $this->Session->read('Auth.User');

    $is_payment = $this->AppAdmin->getLeadPaymentStatus($login['User']['org_unique_url']);
    $lead = $this->AppAdmin->getLeadData($login['User']['org_unique_url']);

    $path = Router::url('/img/profile.png',true);
    /*if(isset($lead['app_logo']) && !empty($lead['app_logo'])){
        $path1 = WWW_ROOT . 'uploads' . DS . 'app' . DS . $lead['app_logo'];
        if(file_exists($path1)){
            $path = Router::url('/uploads/app/',true).$lead['app_logo'];
        }
    }*/


?>
<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12"  >

    <?php

    $login = $this->Session->read('Auth.User');

    ?>

        <div class="Myaccount-left">
            <div class="Myaccount-links">
                <p><a href="<?php echo Router::url('/app_admin/profile'); ?>" ><i class="fa-pencil fa"> </i>&nbsp; Edit Profile</a></p>
                <p><a href="<?php echo Router::url('/app_admin/password'); ?>"><i class="fa-key fa"> </i>&nbsp; Change Password</a></p>
                <p><a href="javascript:void(0);" title="Mobile number"><i class="fa-phone fa"></i> &nbsp; <?php echo $login['User']['mobile']; ?> </a></p>
                <p><a href="javascript:void(0);" title="App Url"><i class="fa-link fa"></i> &nbsp; <?php echo $login['User']['org_unique_url']; ?> </a></p>
                <p><a href="<?php echo Router::url('/app_admin/logout');?>" title="Logout"><i class="fa-lock fa"></i> &nbsp; Logout</a></p>
            </div>
        </div>
    

</div>