<div class="row">
    <table style="width: 100%;" class="lable_table">
        <tr>
            <td><i style="background:gray;" class="fa fa-eyedropper"></i> Pending </td>
            <td><i style="background:green;" class="fa fa-eyedropper"></i> Given </td>
            <td><i style="background:black;" class="fa fa-eyedropper"></i> Canceled </td>
            <td><i style="background:red;" class="fa fa-eyedropper"></i> Missed </td>
            <td><i style="background:yellow;" class="fa fa-eyedropper"></i> Upcoming </td>
        </tr>
    </table>
</div>
<div class="accordion" id="dateWise">

    <?php foreach ($data['data']['vac_list'] as $key => $value){ $heading = "heading_$key"; $collapse = "collapse_$key";  ?>
        <div class="card">
            <div class="card-header" id="<?php echo $heading; ?>">
                <h5 class="mb-0">
                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#<?php echo $collapse; ?>" aria-expanded="true" aria-controls="collapseOne">
                        <?php echo $value['title']; ?>
                    </button>
                </h5>
            </div>

            <div id="<?php echo $collapse; ?>" class="collapse <?php echo ($key==0)?"show":''; ?>" aria-labelledby="<?php echo $heading; ?>" data-parent="#dateWise">
                <ul class="card-body date_wise_ul" >
                    <?php foreach ($value['list'] as $childKey => $vaccination){  ?>

                        <li data-ci="<?php echo base64_encode($child_id); ?>" data-id="<?php echo base64_encode($vaccination['id']); ?>" data-status="<?php echo $vaccination['status']; ?>" data-type="<?php echo $vaccination['vac_type']; ?>">
                            <table>
                                <tr>
                                    <td class="first_td"><i style="background:<?php echo $vaccination['label']; ?>" class="fa fa-eyedropper"></i> </td>
                                    <td class="second_td">
                                        <h6><?php echo $vaccination['dose_name']?></h6>
                                        <span><?php echo $vaccination['due_date']?></span>

                                    </td>
                                    <td class="third_td">
                                        <span>Due-<?php echo $vaccination['label_vac_date']?></span>
                                        <span>Give-<?php echo $vaccination['label_given_date']?></span>
                                    </td>


                                </tr>
                            </table>
                        </li>

                    <?php }  ?>
                </ul>
            </div>

        </div>

    <?php } ?>
</div>
<style>
    #dateWise .card-header{
        padding: 0.2rem;
    }
    .date_wise_ul{
        margin: 0;
        padding: 0;
    }

    #dateWise .card-header button{
        text-decoration: none;
        font-weight: 600;
        outline: none;
    }

    .date_wise_ul li{
        border-bottom: 1px solid #b9abab;
        padding: 5px 2px;
        list-style: none;
        width: 100%;
        float: left;
    }

    .first_td{
        width: 15%;
    }
    .first_td i{
        background: RED;
        font-size: 1.3rem;
        border-radius: 27px;
        padding: 10px;
        color: #fff;
    }
    .second_td{
        width: 55%;
    }
    .third_td{
        width: 30%;
    }

    .third_td span{
        font-size: 0.7rem;
        display: block;
        width: 100%;
        float: right;
    }


    .age_title{
        font-size: 0.7rem;
        display: block;
    }
</style>