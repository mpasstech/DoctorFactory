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
                        <a class="dashboard center_box" href="<?php echo Router::url('/franchise/dashboard',true); ?>"><i class="fa fa-home"></i> Dashboard</a>
                        <a class="dashboard center_box" href="<?php echo Router::url('/franchise/report',true); ?>"><i class="fa fa-bar-chart"></i> Report</a>
                        <a class="dashboard center_box password_btn" href="javascript:void(0);"><i class="fa fa-key"></i> Change Password</a>
                        <a class="dashboard logout_link" href="<?php echo Router::url('/franchise/logout')?>" > <i class="fa-power-off fa"></i>&nbsp;Logout</a>
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

    <script type="text/javascript">
        $(function(){

            $(document).on('click','.password_btn',function(e){
                var dialog = $.confirm({
                    title: 'Change Password',
                    content: '<div class="form-group">' +
                    '<label>New Password</label><input type="password" class="form-control"  id="new_password" />' +
                    '<label>Confirm Password</label><input type="password" class="form-control"  id="confirm_password" />' +
                    '</div>',
                    buttons: {
                        formSubmit: {
                            text: 'Save',
                            btnClass: 'btn-green password_save_btn',
                            action: function () {
                                var $btn = $(".password_save_btn");
                                var new_pass = this.$content.find('#new_password').val();
                                var confirm_pass = this.$content.find('#confirm_password').val();
                                if(new_pass && confirm_pass){
                                    if(confirm_pass == new_pass){
                                        $.ajax({
                                            type: 'POST',
                                            url: baseurl + "franchise/update_password",
                                            data: {n:new_pass,c:confirm_pass},
                                            beforeSend: function () {
                                                $btn.button({loadingText: 'Changing...'}).button('loading');
                                            },
                                            success: function (data) {
                                                $btn.button('reset');
                                                data = JSON.parse(data);
                                                if(data.status==1){
                                                    $.alert(data.message);
                                                    dialog.close();
                                                }else{
                                                    $.alert(data.message);
                                                }
                                            },
                                            error: function (data) {
                                                $btn.button('reset');
                                                $.alert("Sorry something went wrong on server.");
                                            }
                                        });
                                    }else{
                                        $.alert("New password and confirm password does not match");
                                    }
                                }else{
                                    var label = 'confirm password';
                                    if(new_pass==''){
                                        label = 'new password';
                                    }
                                    $.alert("Please enter "+label);
                                }
                                return false;
                            }
                        },
                        cancel: function () {
                            //close
                        },
                    },
                    onContentReady: function () {


                    }
                });





            });

        })
    </script>


