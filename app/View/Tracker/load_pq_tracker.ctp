<?php if(!empty($tracker_data)){ ?>
    <style>
        .table{
            width: 100%;
            font-size: 3.5rem;
            text-align: left;
            padding: 2.5rem 0.5rem;
            color: rebeccapurple;
        }

        .table th{
            font-size: 2.8rem;
            font-weight: 600;
        }


        .doctor_box{
            height: auto !important;
        }

        .header_box_ul li{
            list-style: none;
            float: left;
            width: 50%;
            text-align: center;
        }
        .header_box_ul{
            margin: 0;
            padding: 0;
        }

        .current_box{
            padding: 0 !important;
        }
        .heading_h1{
            position: relative;
            float: left;
            width: 27%;
            font-size: 2.5rem;
            margin: 11px;
            padding: 0;
            color: #fff;
        }
        .header_box_ul h1{
            font-size: 3.5rem;
        }
    </style>

        <div  class="main_div">
            <div  class="header_section">
                <div class="doctor_box">
                    <div class="powered_by">
                        <span>Powered by </span>
                        <img class="logo-mengage" src="https://mengage.in/doctor/images/logo.png">
                    </div>
                    <h3 class="heading_h1">Payment Queue</h3>
                    <div class="current_box">
                        <ul class="header_box_ul">
                            <li><h1>Patient Name <br> <?php echo $tracker_data[0]['appointment_patient_name']; ?></h1></li>
                            <li><h1>Current Token <br> <?php echo $tracker_data[0]['payment_sequence_number']; ?></h1></li>
                        </ul>


                    </div>
                </div>
            </div>
            <div class="list_section">
                <table class="table">
                    <?php if(count($tracker_data) > 1){  ?>
                        <tr>
                            <th>Patient Name</th>
                            <th>Doctor Name</th>
                            <th>Token</th>
                        </tr>
                    <?php }  ?>
                    <?php  foreach ($tracker_data as $key => $data){ if( $key > 0 ){ ?>
                        <tr>
                            <td><?php echo $data['appointment_patient_name']; ?></td>
                            <td><?php echo $data['doctor_name']; ?></td>
                            <td><?php echo $data['payment_sequence_number']; ?></td>
                        </tr>
                    <?php }} ?>
                </table>
            </div>
        </div>
<?php }else{  ?>
    <h1 style="text-align: center;"> There is no appointment available </h1>
<?php } ?>

