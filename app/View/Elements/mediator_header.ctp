    <?php

$login = $this->Session->read('Auth.User.SmartClinicMediator');

$action = $this->request->params['action'];



?>

<div class="header">
    <div class="top-bar"> </div>
    <div class="navbar navbar-inverse" >
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
                
            </div>
            <?php if(!empty($login)){ ?>
                <img  class="logo" src="<?php echo Router::url('/images/logo.png',true); ?>" alt="logo">

            <?php } ?>


            <div class="collapse navbar-collapse navbar-right wow bounceInLeft dropdown">
                <div class="top-info">

                    <?php if(!empty($login)){ ?>

                        <label class="dashboard "  >
                            <?php echo "Hi..". $login['name']; ?>
                        </label>
                        <a class="dashboard center_box" href="<?php echo Router::url('/mediator/dashboard',true); ?>"><i class="fa fa-home"></i> Dashboard</a>
                        <a class="dashboard center_box" href="<?php echo Router::url('/mediator/report',true); ?>"><i class="fa fa-bar-chart"></i> Report</a>
                        <a class="dashboard logout_link" href="<?php echo Router::url('/mediator/logout')?>" > <i class="fa-power-off fa"></i>&nbsp;Logout</a>
                    <?php } ?>

                </div>

            </div>



        </div>

    </div>
</div>
<style>
        .center_title{
            float: left;
            text-align: center;
            font-size: 30px;
            color: #fff;
        }
        .center_title img{
            width: 65px;
            height: 65px;

            border-radius: 57px;
            margin: 2px;
        }
        .dashboard{
            font-size: 15px;
            /* height: 31px; */
            background: #94c405;
            color: #fff;
            padding: 9px 9px !important;
            margin: 0 auto !important;
            float: left;
            border-radius: 15px 0px 0px 15px;
            border-right: 1px solid #fff;
        }
        .logout_link{
            border-radius: 0px 15px 15px 0px;
        }
        .center_box{
            border-radius: 0px;
        }

    </style>


