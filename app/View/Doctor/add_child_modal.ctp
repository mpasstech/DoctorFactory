<div class="modal fade"   id="loadChildModal" tabindex="-1"  aria-labelledby="loadChildModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" >Add Child/Patient</h5>
                <button type="button"  class="close VacDetailClose" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding: 0.5rem;" >
                <div  style="width: 100%;display: block;float: left;">


                    <div class="form-group image_container_child">

                        <?php echo $this->Form->create('AppointmentStaff',array('type'=>'file','method'=>'post','class'=>'form-horizontal','id'=>'sub_frm')); ?>
                        <?php echo $this->Form->input('file',array("accept"=>"image/x-png,image/gif,image/jpeg",'type'=>'file','label'=>false,'class'=>'form-control hidden_file_browse','style'=>'display:none;')) ?>
                        <?php echo $this->Form->end(); ?>


                        <?php
                            $default =Router::url('/img/profile.png',true);


                        ?>
                        <img id="child_image" src="<?php echo $default; ?>" />
                        <input type="hidden" id="vac_image" class="vac_image_input" >
                        <label style="display: <?php echo ($user_role !='USER')?'block':'none'; ?>" ><a href="javascript:void(0);" class="imageDeleteButton"><i class="fa fa-trash"></i> Remove</a></label>
                    </div>


                   <div class="form-group">
                       <label>Name</label>
                       <input type="text"  autocomplete="off" required class="form-control" id="child_name">
                   </div>

                    <div class="form-group">
                        <label>Gender</label>
                        <select id="child_gender" class="form-control">
                            <option value="MALE" >Male</option>
                            <option value="FEMALE" >Female</option>
                        </select>
                    </div>


                    <div class="form-group">
                        <label>Blood Group</label>
                        <select id="child_blood_group" class="form-control">
                            <option value="">N/A</option>
                            <?php
                                $group =array('A+','A-','B+','B-','O+','O-','AB+','AB-');
                                foreach ($group as $val){
                            ?>
                                <option value="<?php echo $val?>"><?php echo $val?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Special Remark</label>
                        <input type="text" autocomplete="off" class="form-control" id="special_remark">
                    </div>

                    <div class="form-group">
                        <label>Patient Address</label>
                        <input type="text"  autocomplete="off" class="form-control" id="patient_address">
                    </div>

                    <div class="form-group">
                        <label>Patient Profession</label>
                        <input type="text" autocomplete="off" class="form-control" id="patient_profession">
                    </div>

                    <div class="form-group">
                        <label>Mobile Number</label>
                        <input type="number" autocomplete="off" required class="form-control" id="child_mobile">
                    </div>

                    <div class="form-group">
                        <label>Parents Mobile</label>
                        <input type="number" autocomplete="off"  class="form-control" id="parents_mobile">
                    </div>

                    <div class="form-group">
                        <label>Date of Birth</label>
                        <input type="text"  autocomplete="off" required class="form-control" id="patient_dob">
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn-warning btn VacDetailClose" data-dismiss="modal">Cancel</button>
                 <button type="button"  class="btn-info btn add_child_btn">Save</button>
            </div>
        </div>
    </div>
    <style>

        .datepicker td{
            padding: 5px !important;
        }
        .image_container_child{
            float: left;
            display: block;
            text-align: center;
            width: 100%;
        }
        #child_image{
            width: 150px;
            height: 150px;
            margin: 0 auto;
            display: block;
            border-radius: 10px;
        }
    </style>




</div>



