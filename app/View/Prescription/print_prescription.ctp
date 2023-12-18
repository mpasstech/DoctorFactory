<?php
$left = $tb_left = '0%';
$top = $tb_top= '0%';
$width = $tb_width= '60%';
$height = '8%';
$fontSize = $tb_text_font_size= $tb_label_font_size= $tb_heading_font_size= '13px';
$header_rotate = "0";
$barcode = 'NO';
$background_img = "";
$header_box_border = $tag_box_border = "2px solid";

if (!empty($prescription_setting)) {
    $data = $prescription_setting;
    $left = $data['left'];
    $top = $data['top'];
    $width = $data['width'];
    $height = $data['height'];
    $fontSize = $data['font_size'];
    $barcode = $data['barcode'];
    $tb_left = $data['tb_left'];
    $tb_top = $data['tb_top'];
    $tb_width = $data['tb_width'];
    $header_rotate = $data['header_rotate'];

    $tb_text_font_size = !empty($data['tb_text_font_size'])?$data['tb_text_font_size']:$tb_text_font_size;
    $tb_label_font_size = !empty($data['tb_label_font_size'])?$data['tb_label_font_size']:$tb_label_font_size;
    $tb_heading_font_size = !empty($data['tb_heading_font_size'])?$data['tb_heading_font_size']:$tb_heading_font_size;
    $header_box_border = !empty($data['header_box_border'])?$data['header_box_border']:$header_box_border;
    $tag_box_border = !empty($data['tag_box_border'])?$data['tag_box_border']:$tag_box_border;
    $prescription = $data['prescription'];
    if(!empty($prescription)){
        $img = file_get_contents($prescription);
        $background_img = "data:image/png;base64,".base64_encode($img);
        $background_img = "background-image:url('".$background_img."');";
    }

    if(!empty($data['fields']))
    {
        $fields = json_decode($data['fields'],true);
        uasort($fields, function($item1, $item2){
            if ($item1['order'] == $item2['order']) return 0;
            return $item1['order'] < $item2['order'] ? -1 : 1;
        });
    }
}


?>
<style media="print">

    @page {
        size: A4;
        margin: 0;
    }
    @media print {
        html, body {
            width: 210mm;
            /*height: 297mm;*/
        }

        #tag_box .background_color {
            background-color: #0000001f !important;
            -webkit-print-color-adjust: exact;

        }
        #tag_box td, #tag_box th{
            text-align: left;
        }

        #label_box li{
            float: left;
            list-style: none;
            font-weight: 600;
            padding: 5px 3px;
            margin-right: 8px;
            min-width: 10%;
        }



    }
</style>
<div id="printPrescriptionBox" style="float: left; width:210mm; height:297mm; background-size: cover !important; <?php echo $background_img; ?>;display:block;" >
    <div  id="printThisPrescription">
        <?php if($data['barcode'] =='YES' && $data['barcode_on_prescription'] =="YES"){ ?>
            <div><img id="barcode"/></div>
        <?php } ?>
        <?php if($data['field_layout_type'] == "ORDER_LIST"){ ?>
            <div class="child" id="label_box"  style=" position: relative; float:left; transform: rotate(<?php echo $header_rotate.'deg'; ?>); border:<?php echo $header_box_border; ?>; left: <?php echo $left; ?>; font-size: <?php echo $fontSize; ?>; top: <?php echo $top; ?>; width: <?php echo $width; ?>; height: auto;" >
                <?php $keys = 1; $size = 1; $totalSize = sizeof($fields); foreach ($fields AS $key => $field){
                    $custom_condition = isset($field['custom_field'])?true:false;
                    ?>
                    <?php if( $field['status'] == 'ACTIVE'){ ?>

                        <?php
                        $label = $field['label'];
                        if($field['column']=="age"){
                            $value = $this->AppAdmin->getAgeStringFromDob($data[$field['column']],false,true);
                            if(empty($value)){
                                $value = $data[$field['column']];
                            }
                        }else if($field['column']=="country_id"){
                            $value = "";
                            if(!empty($data[$field['column']])){
                                $name = $this->AppAdmin->get_localization_by_id($data[$field['column']],'COUNTRY');
                                if(!empty($name)){
                                    $value = $name;
                                }
                            }

                        }else if($field['column']=="state_id"){
                            $value = "";
                            if(!empty($data[$field['column']])){
                                $name = $this->AppAdmin->get_localization_by_id($data[$field['column']],'STATE');
                                if(!empty($name)){
                                    $value = $name;
                                }
                            }

                        }else if($field['column']=="city_id"){
                            $value = $data['city_name'];
                            if(!empty($data[$field['column']])){
                                $name = $this->AppAdmin->get_localization_by_id($data[$field['column']],'CITY');
                                if(!empty($name)){
                                    $value = $name;
                                }
                            }

                        }else if($field['column']=="parents_name"){
                            $label = (!empty($data['relation_prefix']) && !empty($data[$field['column']]))?$data['relation_prefix']:"Parents Name";
                            $value = @$data[$field['column']];
                        }else if($field['column']=="gender" || $field['column']=="marital_status"){
                            $value = @ucfirst(strtolower($data[$field['column']]));
                        }else{
                            $value = @$data[$field['column']];
                        }
                        ?>

                        <?php if(($field['column']=="conceive_date" || $field['column']=="expected_date")){ ?>
                            <?php if(($data['gender']=="FEMALE")){ ?>
                                <li>
                                    <span><?php echo ucfirst($label); ?>:</span>
                                    <span ><?php echo ($value != "" && $value != '00-00-0000')?$value:''; ?></span>
                                </li>

                            <?php } ?>
                        <?php }else{ ?>

                            <?php $value=  ($value == '00-00-0000' || empty($value))?'':$value; ?>

                            <li>
                                <span><?php echo ucfirst($label); ?>:</span>
                                <span ><?php echo $value; ?></span>
                            </li>

                        <?php } ?>

                    <?php } ?>
                    <?php $size++; } ?>
            </div>
        <?php }else{ ?>
            <table class="child" id="label_box"  style="position:relative;transform: rotate(<?php echo $header_rotate.'deg'; ?>); border:<?php echo $header_box_border; ?>; left: <?php echo $left; ?>; font-size: <?php echo $fontSize; ?>; top: <?php echo $top; ?>; width: <?php echo $width; ?>; height: <?php echo $height; ?>;" >
                <?php $keys = 1; $size = 1; $totalSize = sizeof($fields); foreach ($fields AS $key => $field){
                    $custom_condition = isset($field['custom_field'])?true:false;
                    ?>
                    <?php if( $field['status'] == 'ACTIVE'){ ?>
                        <?php if($keys == 1){ ?> <tr> <?php } ?>
                        <?php
                        $label = $field['label'];
                        if($field['column']=="age"){
                            $value = $this->AppAdmin->getAgeStringFromDob($data[$field['column']]);
                            if(empty($value)){
                                $value = $data[$field['column']];
                            }
                        }else if($field['column']=="country_id"){
                            $value = "";
                            if(!empty($data[$field['column']])){
                                $name = $this->AppAdmin->get_localization_by_id($data[$field['column']],'COUNTRY');
                                if(!empty($name)){
                                    $value = $name;
                                }
                            }

                        }else if($field['column']=="state_id"){
                            $value = "";
                            if(!empty($data[$field['column']])){
                                $name = $this->AppAdmin->get_localization_by_id($data[$field['column']],'STATE');
                                if(!empty($name)){
                                    $value = $name;
                                }
                            }

                        }else if($field['column']=="city_id"){
                            $value = $data['city_name'];
                            if(!empty($data[$field['column']])){
                                $name = $this->AppAdmin->get_localization_by_id($data[$field['column']],'CITY');
                                if(!empty($name)){
                                    $value = $name;
                                }
                            }

                        }else if($field['column']=="parents_name"){
                            $label = (!empty($data['relation_prefix']) && !empty($data[$field['column']]))?$data['relation_prefix']:"Parents Name";
                            $value = @$data[$field['column']];
                        }else{
                            $value = @$data[$field['column']];
                        }
                        ?>

                        <?php if(($field['column']=="conceive_date" || $field['column']=="expected_date")){ ?>
                            <?php if(($data['gender']=="FEMALE")){ ?>
                                <th align="left" width="16.666%"><?php echo ucfirst($label); ?>: </th>
                                <th align="left" width="16.666%"><?php echo ($value != "" && $value != '00-00-0000')?$value:''; ?></th>
                                <?php if($keys == 3 || $size == $totalSize){ ?> </tr> <?php $keys = 0; }  $keys++; ?>
                            <?php } ?>
                        <?php }else{ ?>
                            <?php $value=  ($value == '00-00-0000' || empty($value))?'':$value; ?>
                            <th align="left" width="16.666%"><?php echo ucfirst($label); ?>: </th>
                            <th align="left" width="16.666%"><?php echo ucfirst($value); ?></th>
                            <?php if($keys == 3 || $size == $totalSize){ ?> </tr> <?php $keys = 0; }  $keys++; ?>
                        <?php } ?>


                    <?php } ?>
                    <?php $size++; } ?>
            </table>
        <?php } ?>
    </div>
    <table class="tag_box" id="tag_box"   style="position:relative;border:<?php echo $tag_box_border; ?>; left: <?php echo $tb_left; ?>; font-size: <?php echo $tb_text_font_size; ?>; top: <?php echo $tb_top; ?>; width: <?php echo $tb_width; ?>; height: auto;" >

    </table>
</div>
<script>
    $(function () {



        var max_th_count = $("#label_box tr:first-child th").length;
        var last_row_th = $("#label_box tr:last-child th").length;
        if(last_row_th < max_th_count){
            for(var i=0; i < (max_th_count-last_row_th); i++){
                $("#label_box tr:last-child").append("<th></th>");
            }
        }


        function update_print_prescription(){

            var string ="";
            $("#prescription_body table[class^='category_']").each(function (index, value) {
                var h4 = $(this).closest('.cat_box').find("h4:first-child").clone();
                var table = $(this).clone();
                string += "<tr style='background: #dbdbdb6e;-webkit-print-color-adjust: exact;' class='background_color' ><td class='tag_heading_td'>"+$(h4).html()+"</td></tr>";
                string += "<tr><td>"+$('<div>').append(table).html()+"</td></tr>";
            });

            $("#tag_box").html(string);
            $("#tag_box th").css('font-size','<?php echo $tb_label_font_size; ?>');
            $("#tag_box td").css('font-size','<?php echo $tb_text_font_size; ?>');
            $("#tag_box .tag_heading_td").css('font-size','<?php echo $tb_heading_font_size; ?>');
            $("#tag_box td, #tag_box th").css('padding','2px 4px');

        }





    })
</script>





