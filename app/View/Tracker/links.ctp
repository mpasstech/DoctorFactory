<?php
//pr($userAppointmentData);
//pr($otherTokenData);
//pr($showTracker);
//die;
?>
<html>
<head>
    <title>mPass Tracker</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link href="/doctor/home/icon/favicon.png" rel="shortcut icon">
    <!--link rel="manifest" href="manifest-add-homepage.json"-->
    <?php echo $this->Html->css(array('bootstrap.min.css','font-awesome.min.css','jquery-confirm.min.css'),array("media"=>'all','fullBase' => true)); ?>

</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 doctor_name">
            <h1 style="width: 100%;text-align: center;">mPass Live Tracker App List</h1>
            <a class="btn btn-info" href="https://www.mpasscheckin.com/doctor/tracker/live?t=OTAy"> Apollo Live </a>
            
            
        <table class="table table-striped" style="width: 100%;">
            <tr>
                <th>S.NO</th>
                <th>App Name</th>
                <th>Tracker Url</th>
                <th>DTI Login</th>
            </tr>
            <?php foreach ($list as $key=>$app){ ?>
                <tr>
                    <td><?php echo $key+1; ?></td>
                    <td><?php echo $app['name']; ?></td>
                    <td>
                        <a class="btn btn-success" href="<?php echo Router::url('/tracker/fortis?t=',true).base64_encode($app['id']); ?>"> Tracker Url </a>
                    </td>
                    <td>
                        <a class="btn btn-info" href="<?php echo Router::url('/dti/',true).$app['slug']; ?>"> DTI Login </a>
                    </td>
                </tr>
            <?php } ?>
        </table>

        </div>
    </div>
</div>

<script src="<?php echo Router::url('/js/jquery.js'); ?>"></script>
<script src="<?php echo Router::url('/js/bootstrap.min.js'); ?>"></script>
<script src="<?php echo Router::url('/js/jquery-confirm.min.js'); ?>"></script>



</body>
</html>