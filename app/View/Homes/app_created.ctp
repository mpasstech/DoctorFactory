<html>
<head id ="row_content" class="row_content" >
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <meta name="mobile-web-app-capable" content="yes">


    <meta http-equiv="cache-control" content="no-store"/>
    <meta http-equiv="expires" content="0"/>
    <meta http-equiv="pragma" content="no-cache"/>

    <?php echo $this->Html->script(array('jquery-3.5.1.min.js','bootstrap4.min.js','popper.min.js','sweetalert2.min.js','jquery.maskedinput-1.2.2-co.min.js','jquery-confirm.min.js','moment.js', 'timepicker.min.js')); ?>
    <?php echo $this->Html->css(array( 'bootstrap4.min.css','font-awesome.min.css','sweetalert2.min.css','jquery-confirm.min.css','timepicker.min.css'),array("media"=>'all')); ?>

    <style>

        .action_btn{
            padding: 4px 3px;
            font-size: 12px;
            float: left;
            display: inline-block;
            margin: 2px;
        }
        .wizard {
            margin: 20px auto;
            background: #fff;
        }

        .wizard .nav-tabs {
            position: relative;
            margin: 40px auto;
            margin-bottom: 0;
            border-bottom-color: #e0e0e0;
        }

        .wizard > div.wizard-inner {
            position: relative;
        }

        .connecting-line {
            height: 2px;
            background: #e0e0e0;
            position: absolute;
            width: 80%;
            margin: 0 auto;
            left: 0;
            right: 0;
            top: 50%;
            z-index: 1;
        }

        .wizard .nav-tabs > li.active > a, .wizard .nav-tabs > li.active > a:hover, .wizard .nav-tabs > li.active > a:focus {
            color: #555555;
            cursor: default;
            border: 0;
            border-bottom-color: transparent;
        }

        span.round-tab {
            width: 70px;
            height: 70px;
            line-height: 70px;
            display: inline-block;
            border-radius: 100px;
            background: #fff;
            border: 2px solid #e0e0e0;
            z-index: 2;
            position: absolute;
            left: 0;
            text-align: center;
            font-size: 25px;
        }
        span.round-tab i{
            color:#555555;
        }
        .wizard li.active span.round-tab {
            background: #fff;
            border: 2px solid #5bc0de;

        }
        .wizard li.active span.round-tab i{
            color: #5bc0de;
        }

        span.round-tab:hover {
            color: #333;
            border: 2px solid #333;
        }

        .wizard .nav-tabs > li {
            width: 25%;
        }

        .wizard li:after {
            content: " ";
            position: absolute;
            left: 46%;
            opacity: 0;
            margin: 0 auto;
            bottom: 0px;
            border: 5px solid transparent;
            border-bottom-color: #5bc0de;
            transition: 0.1s ease-in-out;
        }

        .wizard li.active:after {
            content: " ";
            position: absolute;
            left: 46%;
            opacity: 1;
            margin: 0 auto;
            bottom: 0px;
            border: 10px solid transparent;
            border-bottom-color: #5bc0de;
        }

        .wizard .nav-tabs > li a {
            width: 70px;
            height: 70px;
            margin: 20px auto;
            border-radius: 100%;
            padding: 0;
        }

        .wizard .nav-tabs > li a:hover {
            background: transparent;
        }

        .wizard .tab-pane {
            position: relative;
            padding-top: 10px;
        }

        .wizard h3 {
            margin-top: 0;
        }

        section{
            width: 100%;
        }


        .container {
            padding: 5% 3%;
        }
        @media( max-width : 585px ) {

            .wizard {
                width: 90%;
                height: auto !important;
            }

            span.round-tab {
                font-size: 16px;
                width: 50px;
                height: 50px;
                line-height: 50px;
            }

            .wizard .nav-tabs > li a {
                width: 50px;
                height: 50px;
                line-height: 50px;
            }

            .wizard li.active:after {
                content: " ";
                position: absolute;
                left: 35%;
            }
        }

        .button-box{
            width: 100%;
            display: block;
            float: left;
        }

        .button-box li{
            float: left;
            width: auto;
            margin: 5px;
            list-style: none;
        }

        .tab-pane .col-sm-12{
            height: 90px;
        }


        .table td, .table th {
            padding: .1rem .3rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
            font-size: 0.9rem;
        }

        header{
            background: green;
            color: #fff;
            text-align: center;
        }

        .main_heading{
            border-bottom: 1px solid #dadada;
            padding-bottom: 4px;
        }
    </style>

</head>
<body>
<header>
    <h1>QuTot</h1>
</header>
<div class="container">


    <div class="row">

        <div class="col-12" style="text-align: center;">
            <h3 class="main_heading"><?php echo $app_data[0]['app_name']; ?></h3>

        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <h6>Now you can get started and allow users to book token</h6>
            <h5 style="text-align: center;width: 100%;">Details</h5>

            <div class="table-responsive">
                <form method="post">
                    <table id="data_table" class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Counter</th>
                            <!-- <th>Name</th>-->

                            <th style="width: 10%;">Password</th>
                            <th style="width: 40%;">Link</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $mobile_array = array(); foreach ($app_data as $key => $data){

                            $mobile = substr($data['mobile'], -10);
                            $mobile_array[$data['staff_type']][]=$mobile;


                            ?>
                            <tr class="inner_tr" data-name="<?php echo $data['doctor_name']; ?>" data-id="<?php echo $data['id']; ?>" data-m="<?php echo $mobile; ?>" data-type="<?php echo $data['staff_type']; ?>">
                                <td><?php echo $key+1; ?></td>
                                <!--<td><?php /*echo ($data['staff_type']=='DOCTOR')?'Executive':'Assistant'; */?>  </td>-->
                                <td><?php echo $data['doctor_name']; ?>  </td>
                                <td><?php echo $mobile; ?>  </td>
                                <td>
                                    <?php if($data['staff_type']=='DOCTOR'){ ?>
                                        <a  class="action_btn btn btn-xs btn-success" target="_blank" href="<?php echo $this->AppAdmin->short_url(Router::url('/doctor/index/'.$data['thinapp_id'].'/?t='.base64_encode($data['id']),true),$data['thinapp_id'] );?>">Web App</a>
                                    <?php } ?>
                                    <a  class="action_btn btn btn-xs btn-info" target="_blank" href="<?php echo Router::url('/homes/mq_form/'.base64_encode($data['thinapp_id']).'/'.base64_encode($data['id']),true)?>">MoQ Link</a>
                                </td>
                            </tr>
                        <?php } ?>

                        </tbody>
                    </table>
                </form>
            </div>


        </div>
    </div>
    <br>
    <div class="row">

        <div class="col-12">
            <h5>More Option</h5>
            <div class="row">
                <div class="col-sm-12" style="text-align: center;">
                    <a target="_blank" href="<?php echo Router::url('/qutot/qr-code-generate.php?t='.base64_encode($thin_app_id),true)?>" class="btn btn-success"><i class="fa fa-qrcode"></i> QR Code </a>
                    <a target="_blank" href="<?php echo Router::url('/homes/qutot_user_booking/'.base64_encode($thin_app_id),true)?>" class="btn btn-success"><i class="fa fa-list"></i> User Token Booking </a>
                </div>
            </div>
        </div>
    </div>


</div>


<script>
    $(document).ready(function () {
        var baseurl = "<?php echo Router::url('/',true); ?>";



        var content='';



    });
</script>
</body>

</html>


