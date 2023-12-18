<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="UTF-8">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="#000000">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <meta name="mobile-web-app-capable" content="yes">
    <meta http-equiv="cache-control" content="no-store"/>
    <meta http-equiv="expires" content="0"/>
    <meta http-equiv="pragma" content="no-cache"/>
    <script>
    var baseurl = '<?php echo Router::url('/',true); ?>';

</script>
<?php  echo $this->Html->script(array('jquery.js','bootstrap4.min.js','jquery-confirm.min.js','sweetalert2.min.js','bootstrap-datepicker.min.js')); ?>
<?php  echo $this->Html->css(array('bootstrap4.min.css','font-awesome.min.css','jquery-confirm.min.css','sweetalert2.min.css','bootstrap-datepicker.min.css')); ?>
</head>
<body>
<div class="" style="width: 95%;margin: 0 auto;">
    <div class="row main_row_box">
    <?php if(!empty($pharmacist)){ $show_list ='YES'; ?>

        <div class="col-12">
            <h3 style="text-align: center;padding: 10px;">
                <img style="width: 50px; height: 50px;" class="logo" src="<?php echo $app_data['logo']; ?>" alt="logo">
                Proforma Invoice List
            </h3>
        </div>
        <div style="margin: 20px 0px; " class="col-12">
            <div class="row">
                <div class="col-2">
                    <label>From Date</label>
                    <input type="text" id="fd" class="form-control datetime" value="<?php echo date('d/m/Y')?>" >
                </div>

                <div class="col-2">
                    <label>To Date</label>
                    <input type="text" id="td" class="form-control datetime" value="<?php echo date('d/m/Y')?>" >
                </div>



                <div class="col-2">
                    <label>Invoice Creation</label>
                    <select id="invoice_status" class="form-control">
                        <option value="">All</option>
                        <option value="-1">Pending</option>
                        <option value="1">Created</option>
                    </select>
                </div>

                <div class="col-2">
                    <label>Payment Status</label>
                    <select id="payment_status" class="form-control">
                        <option value="">All</option>
                        <option value="NONE">Pending</option>
                        <option value="SUCCESS">Success</option>
                    </select>
                </div>

                <div class="col-2">
                    <label>Patient Response</label>
                    <select id="patient_response" class="form-control">
                        <option value="">All</option>
                        <option value="NONE">No Action</option>
                        <option value="ACCEPT">Accepted</option>
                        <option value="DECLINE">Declined</option>
                    </select>
                </div>


                <div class="col-2">
                    <label>Delivery Status</label>
                    <select id="delivery_status" class="form-control">
                        <option value="">All</option>
                        <option value="PENDING">Pending</option>
                        <option value="DELIVERD">Deliverd</option>
                    </select>
                </div>

            </div>
        </div>
        <div class="col-12" >
            <div class="row" id="status_list">

            </div>
        </div>



    <?php }else{ $show_list ='NO'; ?>
        <div class="col-12">
            <h3 style="text-align: center;padding: 10px;">
                <img style="width: 50px; height: 50px;" class="logo" src="<?php echo $app_data['logo']; ?>" alt="logo">
                Pharmacist Login Panel
            </h3>
        </div>
        <div class="col-12">
            <div class="box_container">
                <?php echo $this->Form->create('User',array('method'=>'post','id'=>'login_form','class'=>'contact-form')); ?>

                <div class="form-group">
                    <div class="col-sm-12">
                        <img style="display: none;" class="loading_mob" src="<?php echo Router::url('/img/9.gif',true);?>">
                        <?php echo $this->Form->input('mobile',array("autocomplete"=>"off",'id'=>'mobile','type'=>'number','placeholder'=>'Enter mobile number','label'=>false,'class'=>'form-control cnt','required'=>'required')); ?>
                    </div>
                </div>

                <div class="form-group margin_bottem">
                    <div class="col-sm-12">
                        <?php echo $this->Form->input('password',array('placeholder'=>'Password','label'=>false,'class'=>'form-control cnt','required'=>'required')); ?>
                    </div>
                </div>
                <div class="mob-no-box">
                    <div class="col-12">
                        <div class="form-group">
                            <?php echo $this->Form->submit('Login',array('class'=>'btn btn-success','id'=>'login_btn')); ?>
                        </div>
                    </div>
                </div>

                <?php echo $this->Form->end(); ?>

                <div class="clearfix"></div>

                <?php echo $this->element('message'); ?>


            </div>


        </div>
    <?php } ?>
    </div>
</div>

<style>

    .container{
        width: 100% !important;

    }
    #warningMessage{
        text-align: center;
        padding: 5px;
        color: red;
        border-radius: 5px;
    }
    .main_row_box{
        border: 1px solid #e0e0e0;
        margin: 15px;
        padding: 15px;
    }
    .submit{
        text-align: center;
    }
   .box_container{
       float: left;
       width: 48%;
       margin: 2px 25%;
       position: relative;
       display: block;
   }
    .header{display: none;}
    .login_radio{
        margin:0px !important;
    }

    .forgot_loading_mob{
        float: right;
        position: absolute;
        right: -2px;
        top: 11px;
    }
    .login_box_header{
        text-align: center;
    }
</style>
<script>
    $(document).ready(function (){

        $(document).on("change","#payment_status, #patient_response, #delivery_status, #invoice_status", function(e) {
            load_list();
        });

        $(document).on("change, input","#payment_status, #patient_response, #deliver_status, #fd, #td", function(e) {
            load_list();
        });



        $('.datetime').datepicker({
            setDate: new Date(),
            autoclose: true,
            format: 'dd/mm/yyyy'
        }).on('changeDate', load_list);


        var show_list ="<?php echo $show_list; ?>";
        if(show_list=='YES'){
            load_list();

            setInterval(function () {
                load_list();
            },10000);

        }

        function load_list() {

            var t = "<?php echo $thin_app_id; ?>";
            var ps = $("#payment_status").val();
            var pat_res = $("#patient_response").val();
            var d_staus = $("#delivery_status").val();
            var i_staus = $("#invoice_status").val();
            var fd = $("#fd").val();
            var td = $("#td").val();
            var data =  {is:i_staus,t:t,ps:ps,pat_res:pat_res,d_staus:d_staus,fd:fd,td:td};
            $.ajax({
                type:'POST',
                url: baseurl+"homes/load_proforma_invoice",
                data:data,
                beforeSend:function(){
                    var src = baseurl+'/images/doctor_web_loader.gif';
                    var htm = 'Loading List...';
                },
                success:function(data){
                        $("#status_list").html(data);
                },
                error: function(data){

                    //alert("Sorry something went wrong on server.");
                }
            });
        }

    });
</script>


</body>
</html>
