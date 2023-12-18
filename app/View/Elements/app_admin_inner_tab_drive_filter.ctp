<?php
$category_list = $this->AppAdmin->getFileCategory();
$login = $this->Session->read('Auth.User');
?>


<div class="row seach_box">
    <?php echo $this->Form->create('Search',array('type'=>'get','url'=>array('controller'=>'app_admin','action' => 'search_drive'))); ?>
    <div class="form-group">


        <?php if($folder_type=='SHARED_FILE'){ ?>

        <div class="col-sm-2">
            <?php echo $this->Form->input('category', array('type' => 'select','options'=>$category_list,'label' => 'Record Category', 'class' => 'form-control','required'=>'required')); ?>
        </div>
        <?php } ?>

        <?php if($folder_type=='SHARED_FOLDER' || $folder_type=='PERSONAL'){ ?>
            <div class="col-sm-2">
                <?php echo $this->Form->input('int_folder', array('type' => 'select','options'=>array('NO'=>'No','YES'=>'Yes'),'label' => 'Instruction Folder', 'class' => 'form-control')); ?>
            </div>

            <div class="col-md-2">
                <?php echo $this->Form->input('name', array('type'=>'text','placeholder' => '', 'label' => 'Patient Name', 'class' => 'form-control')); ?>
            </div>
            <div class="col-md-2">
                <?php echo $this->Form->input('mobile', array('type'=>'text','placeholder' => '', 'label' => 'Patient Mobile', 'class' => 'form-control')); ?>
            </div>
            <?php if($login['USER_ROLE'] == 'ADMIN'){
                $doctor_list =$this->AppAdmin->getHospitalDoctorList($login['User']['thinapp_id']);
            ?>
                <div class="col-md-2">
                    <?php echo $this->Form->input('doctor_id', array('type'=>'select','options' => $doctor_list,"empty"=>"All Doctors", 'label' => 'Doctor', 'class' => 'form-control')); ?>
                </div>
            <?php } ?>


        <?php } ?>


       <!-- <div class="col-md-2">
            <?php /*echo $this->Form->input('from_date', array('type'=>'text','placeholder' => '', 'label' => 'From date', 'class' => 'form-control from_date')); */?>
        </div>
        <div class="col-md-2">
            <?php /*echo $this->Form->input('to_date', array( 'type'=>'text','placeholder' => '', 'label' => 'To date', 'class' => 'form-control to_date')); */?>
        </div>
-->



        <div class="col-sm-4 action_btn" >
            <div class="input text">
                <label style="display: block;">&nbsp;</label>

                <?php echo $this->Form->input('ft', array('type'=>'hidden')); ?>

                <?php echo $this->Form->submit('Search',array('div'=>false,'class'=>'btn btn-info','id'=>'search')); ?>
                <a class="btn btn-warning resteButton" href="<?php echo $this->Html->url(array('controller'=>'app_admin','action'=>'drive')) ?>">Reset</a>
				    <a class="btn btn-danger" href="<?php echo $this->Html->url(array('controller'=>'app_admin','action'=>'prescription_list')) ?>">Prescription List</a>

            </div>

        </div>

    </div>
    <?php echo $this->Form->end(); ?>

</div>
<script>
    $(document).ready(function(){




        /*serach box script start*/
        var concept = $('#search_param').val();
        if(concept!=""){
            var concept = "#"+concept;
            var text = $(".op_menu [href="+concept+"]").text();
            $('#search_concept').text(text);

        }
        $('.search-panel .dropdown-menu').find('a').click(function(e) {
            e.preventDefault();
            var param = $(this).attr("href").replace("#","");
            var concept = $(this).text();
            $('.search-panel span#search_concept').text(concept);
            $('.input-group #search_param').val(param);
        });


        $(document).on("click", ".reset_btn", function(){
            window.location.href = $(this).attr("data-ref");
        });

        $(".from_date, .to_date").datepicker({clearBtn:true,format: 'dd/mm/yyyy',autoclose:true,orientation: "bottom auto",endDate: new Date()});

    });
</script>
<style>
    .seach_box{
        background: #f3f3f3;
    }
</style>

