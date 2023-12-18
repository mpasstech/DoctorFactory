<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Doctor Code List</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <meta name="mobile-web-app-capable" content="yes">
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="author" content="mengage">
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta http-equiv="cache-control" content="no-store"/>
    <meta http-equiv="expires" content="0"/>
    <meta http-equiv="pragma" content="no-cache"/>
    <?php echo $this->Html->script(array('jquery-3.5.1.min.js','popper.min.js','bootstrap4.min.js','loader.js','es6-promise.auto.min.js')); ?>
    <?php echo $this->Html->css(array( 'bootstrap4.min.css','font-awesome.min.css'),array("media"=>'all')); ?>


    <?php  echo $this->Html->css(array('dataTableBundle.css')); ?>
    <?php  echo $this->Html->script(array('dataTableBundle.js','jquery.maskedinput-1.2.2-co.min.js','comman.js')); ?>


</head>

<style>
    td, th{
        padding: 0.40rem !important;
    }
</style>


<body>

<?php if(!empty($app_data)){ ?>
<header style="background: #005aff;color: #fff;font-size: 2rem;font-weight: 600;">
    <table class="table" style="width: 100%;">
        <tr>
            <td><img style="width: 50px;height: 50px; border-radius: 50px;" src="<?php echo $app_data['logo']; ?>" </td>
            <td><?php echo $app_data['name']; ?></td>
        </tr>
    </table>
</header>
<div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <table id="list_table" class="table table-bordered">
                    <thead>
                    <tr>
                        <th>S.No</th>

                        <th>Doctor Name</th>
                        <th>Doctor Code</th>

                        <th>Call</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(!empty($list_data)) { foreach ($list_data as $key=>$data){ ?>
                        <tr>
                            <td><?php echo $key+1; ?></td>
                            <td><?php echo $data['doctor_name']; ?></td>
                            <td><?php echo $data['doctor_code']; ?></td>
                            <td>
                                <a href="tel:<?php echo $ivr_number; ?>"><i class="fa fa-phone"></i></a>


                            </td>

                        </tr>

                    <?php }}else{ ?>

                    <?php } ?>

                    </tbody>

                </table>
            </div>
        </div>
    </div>

<?php }else{ ?>
    <h3 style='text-align: center;width: 100%;'> Invalid Request</h3>
<?php } ?>


</body>

<script>
    $( document ).ready(function() {

        $(document).on("click","#logo_image",function(e){
            $("#token_booking_sec").show();
            $("#profile_sec, #token_list_sec").hide();
        });


        $('#list_table').DataTable({
            dom: 'Plfrtip',
            searching: true,
            paging: false,
            info: false,
            buttons: false

        });


    });
</script>
</html>


