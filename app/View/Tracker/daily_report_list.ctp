<html>
<head id ="row_content" class="row_content" >
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <meta name="mobile-web-app-capable" content="yes">
    <meta http-equiv="cache-control" content="no-store"/>
    <meta http-equiv="expires" content="0"/>
    <meta http-equiv="pragma" content="no-cache"/>

   <meta name="apple-mobile-web-app-status-bar" content="#0952EE" />
    <meta name="theme-color" content="#0952EE" />
    <?php echo $this->Html->script(array('jquery-3.5.1.min.js','bootstrap4.min.js','dataTableBundle.js')); ?>
    <?php echo $this->Html->css(array( 'bootstrap4.min.css','font-awesome.min.css','dataTableBundle.css'),array("media"=>'all')); ?>
    <?php $reportTitle = "Token Report For (".date('d-M-Y',strtotime($date)).")"; ?>
    <title><?php echo $reportTitle; ?></title>
    <style>
        .table-responsive{
            padding: 0px 6px;
        }
        .dt-buttons{
            margin-bottom: 15px;
        }


        @media only screen and (max-width: 600px)
        {
            #example td, #example th {
                word-wrap: break-word;
                padding: 4px 2px;
                font-size: 0.7rem;
            }

            .total_tr h6{
                text-align: center;
                padding: 5px 0px;
            }

        }


    </style>
</head>

<body>
<div class="col-md-12 col-sm-12">
    <div class="row">

        <table style="width: 100%;">
            <tr>
                <td style="width: 10%;"><img style="width: 70px;height: 70px;" src="<?php echo $app_data['logo']; ?>"></td>
                <td><h2 style="text-align: center;width: 100%;"><?php echo $app_data['name']; ?></h2></td>
            </tr>
            <tr>
                <td colspan="2">
                    <h5 style="padding: 15px 0px; text-align: center; width: 100%;"> <?php echo $reportTitle; ?>)</h5>
                </td>
            </tr>
            <tr class="total_tr" style="border-top: 3px solid;">
                <td style="width: 50%;" colspan="2">
                    <h6 style="width: 50%;float: left;">Total Booked : <span class="total_booked"></span></h6>
                    <h6 style="width: 50%;float: left;">Total Refund : <span class="total_refund"></span></h6>
                </td>

            </tr>
        </table>




        <?php if(!empty($dataList)){ ?>
            <div class="table-responsive">
                <table id="example" class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th style="width:70px;">
                            Name
                        </th>
                        <th>
                            Mobile
                        </th>

                        <th style="width: 200px;">Date & Time</th>
                        <th>Token</th>
                        <th>C. Fee</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($dataList as $key => $list){
                        ?>
                        <tr>
                            <td><?php echo $key+1; ?></td>
                            <td>
                               <?php
                                    if($list['appointment_patient_name']!=$list['mobile']){

                                        echo $list['appointment_patient_name'];
                                    }

                               ?>
                            </td>
                            <td>
                                <?php
                                    echo substr($list['mobile'],3,10)

                                ?>
                            </td>
                            <td>
                                <?php echo date('d-m-Y H:i',strtotime($list['appointment_datetime'])); ?>
                            </td>
                            <td><?php echo $list['queue_number']; ?></td>
                            <td><?php

                                echo (int)$list['amount'];
                                $status = ($list['status']=='CANCELED')?'Refunded':'Booked';

                            ?></td>
                            <td class="<?php echo $status ?>"><?php echo $status ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php }else{ ?>
            <h3>No Record Found</h3>
        <?php } ?>
    </div>

</div>
<script>
    $(document).ready(function(){

        var col = [0,1,2,3,4,5,6];
        $('#example').DataTable({
            dom: 'Blfrtip',
            lengthMenu: [
                [ -1 ],
                [ 'Show all' ]
            ],
            "language": {
                "emptyTable": "No Data Found Related To Search"
            },
            searching: false,
            paging: false,
            info: false,
            buttons: [
                {
                    extend: 'copy',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns:col
                    }

                },
                {
                    extend: 'csv',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: col
                    }

                },
                {
                    extend: 'excel',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns:col
                    }

                },
                {
                    extend: 'pdf',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: col
                    }

                },
                {
                    extend: 'print',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: col
                    }

                }
            ]
        });


        $("head > title").text('Daily Report');

        $(".total_booked").html($(".Booked").length);
        $(".total_refund").html($(".Refunded").length);

    });

</script>

</body>
</html>


