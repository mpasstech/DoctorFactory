<?php

$login = $this->Session->read('Auth.User');

?>

<div class="header">
    <div class="top-bar"> </div>

    <div class="navbar navbar-inverse" >
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
                <a class="navbar-brand wow bounceInRight " href="<?php echo Router::url('/',true); ?>"><img class="logo" src="<?php echo Router::url('/images/logo.jpg',true); ?>" alt="logo"></a>
            </div>



            <div class="collapse navbar-collapse navbar-right wow bounceInLeft dropdown">
                <div class="top-info">

                    <?php if(!empty($login)){ ?>
                        <span class="u_name" > <?php echo "Hi.. ".$login['username'];?></span>
                        <a class="dashboard" href="<?php echo Router::url('/admin/supp/thinapp_list',true); ?>"><i class="fa fa-home"></i> App List</a>

                        <a class="logout_link" href="<?php echo Router::url('/admin/supp/logout')?>" > <i class="fa-power-off fa"></i>&nbsp;Logout</a>
                    <?php } ?>

                </div>

            </div>



        </div>

    </div>
</div>


<style>


 .top-info{
        display: block !important;
    }
    
    .center_title{
        width: 50%;
        float: left;
        text-align: center;
        font-size: 30px;
        color: rgba(0, 0, 0, 0.49);
    }
    .center_title img{
        width: 65px;
        height: 65px;

        border-radius: 57px;
        margin: 2px;
    }
    .u_name{
        font-size: 17px;
        /* height: 31px; */
        background: rgba(38, 216, 76, 0.92);
        color: #fff;
        padding: 9px 9px !important;
        margin: 0 auto !important;
        float: left;
        border-radius: 15px 0px 0px 15px;
        border-right: 1px solid #fff;
    }
    .dashboard{
        font-size: 17px;
        /* height: 31px; */
        background: #04a6f0;
        color: #fff;
        padding: 9px 9px !important;
        margin: 0 auto !important;
        float: left;

        border-right: 1px solid #fff;
    }
    .logout_link{
        font-size: 17px;
        /* height: 31px; */
        background: #04a6f0;
        color: #fff;
        padding: 9px 9px !important;
        margin: 0 auto;
        float: left;
        border-radius: 0px 15px 15px 0px;
    }

</style>



