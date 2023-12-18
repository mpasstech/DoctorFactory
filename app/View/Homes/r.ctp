<html>
<head id ="row_content" class="row_content" >
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <meta name="mobile-web-app-capable" content="yes">
    <meta http-equiv="cache-control" content="no-store"/>
    <meta http-equiv="expires" content="0"/>
    <meta http-equiv="pragma" content="no-cache"/>
    <title>Linked Expired</title>
    <?php echo $this->Html->script(array('jquery-3.5.1.min.js','bootstrap4.min.js')); ?>
    <?php echo $this->Html->css(array( 'bootstrap4.min.css','font-awesome.min.css'),array("media"=>'all')); ?>
    <style>

    .top_header{
        padding: 20% 3%;
    }

    .top_header .fa{
        font-size: 8rem;
        display: block;
        color:yellow;
    }
    </style>
</head>
<body>

<div class="container">
    <div class="row">
            <div class="col-12 top_header"  style="text-align: center;">
                <i class="fa fa-exclamation-triangle "></i>
                <h2>Link Expired</h2>
                <h5>This link has already been expired</h5>
            </div>
    </div>
    
</div>

</body>

</html>


