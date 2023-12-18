<div class="modal fade"  style="width:750px;margin:2% auto; overflow: hidden;" id="get_ipd_bed_status">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <?php echo $this->Form->create('AppointmentCustomerStaffService',array( 'class'=>'form','id'=>'formPaySearch')); ?>

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="text-align: center;font-weight: bold;">IPD Bed Status</h4>
            </div>
            <div class="modal-body" style="overflow: scroll;height: 500px !important;">

                <table class="bed_stat_table" border="1px solid black">

                    <?php
                    $f = 0;
                    $category = "";
                    foreach($dataToShow AS $value){ $f++;
                        if($category != $value['hospital_service_categories']['id'])
                        {
                            $category = $value['hospital_service_categories']['id'];
                            echo "<tr><th colspan='10' class='ward_name'>".$value['hospital_service_categories']['name']."</th></tr>";
                            $f = 0;
                        }
                        ?>

                        <?php if ($f == 10){ echo "<tr>"; }?>

                        <td class='bed_name <?php echo $value['hospital_ipd']['admit_status'] == 'ADMIT'?'booked':'free'; ?>'>
                            <?php echo $value['medical_products']['name']; ?>
                        </td>

                        <?php if ($f == 10){ echo "</tr>"; $f = 0; }?>

                    <?php } ?>

                </table>

            </div>
        </div>
    </div>
    <style>
        .bed_stat_table {
            width: 100%;
        }
        .ward_name {
            text-align: center;
            color: lightslategrey;
            background: blanchedalmond;
        }
        .bed_name {
            text-align: center;
        }
        .booked {

            background: red;
            color: white;

        }
        .free {

            background: limegreen;
            color: white;

        }
        .modal-header {
            background-color: #03a9f5;
            color: #FFFFFF;
        }

    </style>
</div>
