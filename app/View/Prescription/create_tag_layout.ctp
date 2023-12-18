<li class="tag_box">
    <div class="btn-group">
        <?php
        $tag_name = $tag_data['tag_name'];
        if($master_medicine_name_step_id ==  $step_id){
            $type = !empty($tag_data['type'])?"(".$tag_data['type'].")":'';
            $tag_name = strtoupper($tag_data['tag_name'].' '.$type);
        }
        ?>
        <button type="button" class="tag_button_class" style = "<?php echo ($master_medicine_name_step_id ==  $step_id)?'font-size:12px;':''; ?>" data-ti="<?php echo base64_encode($tag_data['tag_id']); ?>" data-tn="<?php echo $tag_name;  echo  (!empty($tag_data['composition']))?"<span style='display: block;font-size: 7px;'>".strtoupper($tag_data['composition']).'</span>':''; ?>" >
            <?php echo $tag_name; if(!empty($tag_data['composition'])){ ?>
                <span class="company_lbl"><?php echo $tag_data['composition']; ?></span>
            <?php } ?>
        </button>
        <button type="button" class="dropdown-toggle" data-toggle="dropdown">
            <span class="caret"></span>
            <span class="sr-only">Toggle Dropdown</span>
        </button>
        <ul class="dropdown-menu" role="menu">
            <li><a href="javascript:void(0);" class="delete_tag" data-si="<?php echo base64_encode($step_id); ?>" data-ti="<?php echo base64_encode($tag_data['tag_id']); ?>" ><i class="fa fa-trash"></i> Delete</a></li>
            <li><a href="javascript:void(0);" class="edit_tag" data-si="<?php echo base64_encode($step_id); ?>" data-ti="<?php echo base64_encode($tag_data['tag_id']); ?>" data-tn="<?php echo $tag_data['tag_name']; ?>"><i class="fa fa-edit"></i> Edit</a></li>
        </ul>
    </div>
</li>