<div class="modal fade"   id="loadChildDetail" tabindex="-1"  aria-labelledby="loadChildDetail" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" >Edit Child/Patient</h5>
                <button type="button"  class="close VacDetailClose" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding: 0.5rem;" >
                <div  style="width: 100%;display: block;float: left;">


                    <div class="form-group image_container_child">

                        <?php echo $this->Form->create('AppointmentStaff',array('type'=>'file','method'=>'post','class'=>'form-horizontal','id'=>'sub_frm')); ?>
                        <?php echo $this->Form->input('file',array("capture"=>"capture","accept"=>"image/x-png,image/gif,image/jpeg",'type'=>'file','label'=>false,'class'=>'form-control hidden_file_browse','style'=>'display:none;')) ?>
                        <?php echo $this->Form->end(); ?>


                        <?php
                            $default =Router::url('/img/profile.png',true);
                            $child_image =empty($child_data['image'])?$default:$child_data['image'];

                        ?>
                        <img id="child_image" src="<?php echo $child_image; ?>" />
                        <input type="hidden" id="vac_image" class="vac_image_input" value="<?php echo $child_data['image']; ?>">
                        <label style="display: <?php echo ($user_role !='USER')?'block':'none'; ?>" ><a href="javascript:void(0);" class="imageDeleteButton"><i class="fa fa-trash"></i> Remove</a></label>
                    </div>


                   <div class="form-group">
                       <label>Name</label>
                       <input type="text" value="<?php echo $child_data['child_name']; ?>" class="form-control" id="child_name">
                   </div>

                    <div class="form-group">
                        <label>Gender</label>
                        <select id="child_gender" class="form-control">
                            <option value="MALE" <?php echo ($child_data['gender']=='MALE')?'selected':'';?> >Male</option>
                            <option value="FEMALE" <?php echo ($child_data['gender']=='FEMALE')?'selected':'';?>>Female</option>
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
                                <option <?php echo ($child_data['blood_group']==$val)?'selected':'';?> value="<?php echo $val?>"><?php echo $val?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Special Remark</label>
                        <input type="text" value="<?php echo $child_data['special_remark']; ?>" class="form-control" id="special_remark">
                    </div>

                    <div class="form-group">
                        <label>Patient Address</label>
                        <input type="text" value="<?php echo $child_data['patient_address']; ?>" class="form-control" id="patient_address">
                    </div>

                    <div class="form-group">
                        <label>Patient Profession</label>
                        <input type="text" value="<?php echo $child_data['patient_profession']; ?>" class="form-control" id="patient_profession">
                    </div>

                    <div class="form-group">
                        <label>Mobile Number</label>
                        <input type="number" value="<?php echo substr($child_data['mobile'],-10); ?>" class="form-control" id="child_mobile">
                    </div>

                    <div class="form-group">
                        <label>Parents Mobile</label>
                        <input type="number" value="<?php echo substr($child_data['parents_mobile'],-10); ?>" class="form-control" id="parents_mobile">
                    </div>

                    <div class="form-group">
                        <label>Date of Birth</label>
                        <input type="text" <?php echo ($child_data['dob_editable']=='NO')?'disabled':''; ?> value="<?php echo date('d-m-Y',strtotime($child_data['dob'])); ?>" class="form-control" id="patient_dob">
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn-warning btn VacDetailClose" data-dismiss="modal">Cancel</button>
                 <button type="button" data-ci="<?php echo base64_encode($child_id); ?>" class="btn-info btn update_child_btn">Update</button>
            </div>
        </div>
    </div>
    <style>

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

    <script type="text/javascript">

    </script>


</div>



