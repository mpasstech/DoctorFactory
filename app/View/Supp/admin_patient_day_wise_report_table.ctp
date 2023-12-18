<h4>Active Users : <?php echo $data['active_users']; ?></h4>
<div class="table-responsive">
    <?php

    if(!empty($data['list'])){

        $data = $data['list'];

        ?>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th class="day_class_header" style="width: 10px;">Visit Time </th>
            <?php  $date_array= array(); foreach ($data as $key => $list){ ?>
            <th class="day_class_header"><?php echo $key; $date_array[] = $key; $row_count = count($list) ?></th>
            <?php } ?>

        </tr>
        </thead>
        <tbody>
        <?php for ($row=1; $row<= ($row_count);$row++){ ?>
        <tr>
            <td class="day_class"><?php

                echo ($row==5)?$row.' & More':$row;

            ?></td>
            <?php foreach ($date_array as $key => $date){ ?>
                <td><?php echo $data[$date][$row]; ?></td>
            <?php } ?>
        </tr>
        <?php } ?>
        </tbody>
    </table>
    <?php } else { ?>
        <H3>No record available</H3>
    <?php }  ?>
</div>
<style>
    .day_class_header, .day_class{

        background: rgba(112, 236, 185, 0.3)

    } .day_class{
        font-size: 16px;
        font-weight: 600;

    }
</style>