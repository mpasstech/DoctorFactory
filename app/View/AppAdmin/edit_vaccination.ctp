<?php
$login = $this->Session->read('Auth.User');
$vac_time = $this->AppAdmin->getVaccinationDurationOption();
$vac_type = $this->AppAdmin->getVaccinationTypeOption();


?>


<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">
                <!--box 1 -->
                <!--box 1 -->
                <h3 class="screen_title">Edit Vaccination</h3>
                <div class="middle-block">

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 custom_form_box">

                        <?php echo $this->element('message'); ?>
                        <?php echo $this->element('app_admin_inner_tab_vaccination'); ?>


                        <?php echo $this->Form->create('AppMasterVaccination',array('type'=>'file','method'=>'post','class'=>'form-horizontal','id'=>'sub_frm')); ?>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                               <div class="form-group">
                                    <div class="col-sm-5">
                                        <label>Vaccination Full Name (ex. Oral Polio Vaccine) </label>
                                        <?php echo $this->Form->input('full_name',array('readonly'=>'readonly','type'=>'text','placeholder'=>'','label'=>false,'id'=>'mobile','class'=>'form-control vac_name')); ?>
                                    </div>
                                   <div class="col-sm-3">
                                       <label>Sub Title (ex. Hep-B,OPV)</label>
                                       <?php echo $this->Form->input('vac_name',array('readonly'=>'readonly','type'=>'text','placeholder'=>'','label'=>false,'id'=>'mobile','class'=>'form-control cnt')); ?>
                                   </div>
                                   <div class="col-sm-2">
                                       <label>Dose Type</label>
                                       <?php $options = array('Dose' => 'Dose', 'Booster' => 'Booster');
                                            $value = @explode(" ",$this->request->data['AppMasterVaccination']['vac_dose_name']);
                                             $vac_doce_type = @$value[0];
                                             $vac_number = @$value[1];

                                       ?>
                                       <?php echo $this->Form->input('dose_label',array('value'=>$vac_doce_type,'type'=>'select','label'=>false,'options'=>$options,'class'=>'form-control dose_label')); ?>
                                   </div>

                                   <div class="col-sm-2">
                                       <label>Dose Number</label>
                                       <?php for($i=1;$i<=20;$i++) $dose_cnt[$i]=$i;?>
                                       <?php echo $this->Form->input('dose_number',array('value'=>$vac_number,'type'=>'select','label'=>false,'options'=>$dose_cnt,'class'=>'form-control dose_number')); ?>
                                   </div>

                                </div>


                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <label>Vaccination Remark</label>
                                        <?php echo $this->Form->input('remark',array('type'=>'textarea','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>
                                    </div>
                                </div>


                                    <div class="form-group">
                                        <div class="col-sm-3">
                                            <label>Type</label>
                                            <?php echo $this->Form->input('vac_type',array('type'=>'select','label'=>false,'options'=>$vac_type,'class'=>'form-control cnt')); ?>
                                        </div>
                                        <div class="col-sm-3">
                                            <label>Status</label>
                                            <?php echo $this->Form->input('status',array('type'=>'select','label'=>false,'options'=>array('ACTIVE'=>'Active','INACTIVE'=>'Inactive'),'class'=>'form-control cnt')); ?>
                                        </div>
                                        <?php $enum_option = array('YES'=>'YES','NO'=>'NO'); ?>

                                        <div class="col-sm-3">
                                            <label>For Male</label>
                                            <?php echo $this->Form->input('visible_for_male',array('type'=>'select','label'=>false,'options'=>$enum_option,'class'=>'form-control')); ?>
                                        </div>
                                        <div class="col-sm-3">
                                            <label>For Female</label>
                                            <?php echo $this->Form->input('visible_for_female',array('type'=>'select','label'=>false,'options'=>$enum_option,'class'=>'form-control')); ?>
                                        </div>

                                    </div>


                                </div>


                                <div class="form-group">

                                    <div class="col-sm-5 border_div">
                                        <div class="radio_lbl_vac">


                                            <?php
                                            $options = array('BIRTH' => 'On birth', 'WEEK' => 'On Week', 'OTHER_DAY' => 'On Year & Month');
                                            $attributes = array('legend' => "When to given this vaccination.",'class'=>'radio-inline','div'=>'label');
                                            echo $this->Form->radio('vac_radio', $options, $attributes);

                                            ?>


                                        </div>

                                        </div>
                                    <div class="col-sm-6 border_div">

                                        <div class="col-sm-4 week_div">
                                            <?php for($i=1;$i<=20;$i++) $counter[$i]=$i;?>
                                            <?php echo $this->Form->input('week',array('type'=>'select','label'=>"Week",'options'=>$counter,'class'=>'form-control week')); ?>
                                        </div>

                                        <div class="col-sm-4 year_div">
                                            <?php for($i=0;$i<=20;$i++) $year[$i]=$i;?>
                                            <?php echo $this->Form->input('year',array('type'=>'select','label'=>"Year",'options'=>$year,'class'=>'form-control year')); ?>
                                        </div>

                                        <div class="col-sm-4 month_div">
                                            <?php for($i=0;$i<=12;$i++) $month[$i]=$i;?>
                                            <?php echo $this->Form->input('month',array('type'=>'select','label'=>"Month",'options'=>$month,'class'=>'form-control month')); ?>
                                        </div>
                                        <div class="col-sm-12">
                                            <?php echo $this->Form->error('vac_radio');?>
                                        </div>
                                     </div>
                                    <div class="show_vac_label"></div>


                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <div class="col-sm-4">
                                            <?php echo $this->Form->submit('Save',array('class'=>'Btn-typ5','id'=>'signup')); ?>
                                        </div>

                                    </div>
                                </div>





                            </div>

                        </div>
                    <?php echo $this->Form->end(); ?>
                </div>
                <!-- box 1 -->


            </div>
            <!--box 2 -->

        </div>
    </div>
</div>




<script>
    $(document).ready(function(){

        $(".channel_tap a").removeClass('active');
        $("#v_app_channel_list").addClass('active');


        function showVacLabel(){

            var vac_name = $(".vac_name").val();
            var vac_type = $(".radio_lbl_vac [type=radio]:checked").val();
            var year =   $('.year').val();
            var month =   $('.month').val();
            var week =   $('.week').val();
            var str ="";

            if(vac_type=="BIRTH"){
                str =vac_name+" scheduled on "+vac_type;
                $(".week_div, .year_div, .month_div").hide();
            }else if(vac_type=="WEEK"){
                week = (week > 1)?week+" weeks":week+" week";
                str =vac_name+" scheduled on "+week;
                $(".year_div, .month_div").hide();
                $(".week_div").show();

            }else{
                if(year > 0 && month > 0){
                    year = (year > 1)?year+" years":year+" year";
                    month = (month > 1)?month+" months":month+" month";
                    str = vac_name+" scheduled on "+ year + " "+ month;
                }else{
                    if(year > 0){
                        year = (year > 1)?year+" years":year+" year";
                        str = vac_name+" scheduled on "+ year;
                    }else{
                        month = (month > 1)?month+" months":month+" month";
                        str = vac_name+" scheduled on "+ month;
                    }
                }

                $(".year_div, .month_div").show();
                $(".week_div").hide();

            }


            $(".show_vac_label").html(str);
        }
        var var_time  = "<?php echo @$this->request->data['AppMasterVaccination']['vac_time'];?>";
        if(var_time){
            var split_array = var_time.split(" ");
            if(var_time=="BIRTH"){
                $(".radio_lbl_vac [value='BIRTH']").attr('checked',true);
            }else if(split_array[1]=="WEEK" || split_array[1]=="WEEKS"){
                $(".radio_lbl_vac [value='WEEK']").attr('checked',true);
                $(".week").val(split_array[0]);
            }else{
                $(".radio_lbl_vac [value='OTHER_DAY']").attr('checked',true);
                if(split_array[1]=="MONTH" || split_array[1]=="MONTHS"){
                    year = parseInt(split_array[0]/12);
                    month = parseInt(split_array[0]%12);
                    $(".year").val(year);
                    $(".month").val(month);
                }else{
                    $(".year").val(split_array[0]);
                    $(".month").val(0);
                }

            }
            showVacLabel();

        }

        var is_image = $(".image_box").val();
        if(is_image != ""){
            $('.channle_img_box').css('background-image', "url('"+is_image+"')");
        }

        $(document).on('change','.year, .month, .radio_lbl_vac [type=radio]',function () {
            showVacLabel();
        })

        $(document).on('submit','#sub_frm',function () {

            var vac_type = $(".radio_lbl_vac [type=radio]:checked").val();
            var year =   $('.year').val();
            var month =   $('.month').val();
            var week =   $('.week').val();
            if(vac_type=="WEEK" && week ==0 ){
                $.alert("Please select week number.");
                return false;
            }else if(vac_type=="OTHER_DAY" && year ==0 && month ==0 ){
                $.alert("Please select year or month number.");
                return false;
            }
        })
        showVacLabel();
    });
</script>






