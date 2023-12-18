<?php
$login = $this->Session->read('Auth.User');
$ward_list =$this->AppAdmin->getHospitalWardList($login['User']['thinapp_id']);
?>
<div class="modal fade" id="update_ward_modal" role="dialog" keyboard="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" style="text-align: center;font-weight: bold;">Update Ward/Bed</h4>
                </div>
                <div class="modal-body">


                    <?php echo $this->Form->create('HospitalIpd',array("id"=>"update_ward_form",'class'=>'form-horizontal')); ?>
                        <div class="col-sm-12">
                            <label>Admission Ward/Room &nbsp; <a href="javascript:void(0);" class="get_bed_status">Bed Status </a></label>
                            <input type="hidden" name="ipd_id" value="<?php echo $ipd_id; ?>">
                            <?php echo $this->Form->input('',array('name'=>"hospital_service_category_id",'required'=>'required','empty'=>array('0'=>'Select Ward/Room'),'type'=>'select','label'=>false,'options'=>$ward_list,'class'=>'form-control ward_drp cnt')); ?>
                        </div>


                        <div class="col-sm-12">
                            <label>Select Room/Bad <i class="fa fa-spinner fa-spin service_spin" style="display:none;"></i></label>
                            <?php echo $this->Form->input('',array('name'=>"hospital_service_id",'required'=>'required' ,'empty'=>array('0'=>'Select Room/Bad'),'type'=>'select','label'=>false,'options'=>array(),'class'=>'form-control service_drp')); ?>
                        </div>

                        <div class="col-sm-12">
                            <label>Date And Time</label>
                            <?php echo $this->Form->input('',array('name'=>"admit_date",'required'=>'required','value'=>date('d/m/Y h:i A'),'type'=>'text','label'=>false,'class'=>'form-control admit_date')); ?>
                        </div>
                        <div class="col-sm-12" style="text-align:center;">
                            <button type="submit" class="btn btn-info">Submit</button>
                        </div>
                    </form>



                </div>
                <div class="modal-footer" style="border:none;"></div>
            </div>
        </div>
    <style>
        .modal-header {

            background-color: #03A9F5;
            color: #FFF;

        }
    </style>
    <script>
        $(".admit_date").datetimepicker({format: 'DD/MM/YYYY hh:mm A',defaultDate:new Date()});
        $(document).off('change','.ward_drp');
        $(document).on('change','.ward_drp',function(e){
            var ward_id =$(this).val();
            if(ward_id){
                $.ajax({
                    type:'POST',
                    url: baseurl+"app_admin/get_hospital_service_list",
                    data:{ward_id:ward_id},
                    beforeSend:function(){
                        $('.service_spin').show();
                        $('.service_drp').attr('disabled',true).html('');
                    },
                    success:function(data){
                        $('.service_spin').hide();
                        $('.service_drp').attr('disabled',false).html(data);
                    },
                    error: function(data){
                    }
                });
            }
        });
        $(document).off('submit',"#update_ward_form");
        $(document).on('submit',"#update_ward_form",function(e){
            e.preventDefault();
            var dataToSend = $(this).serialize();
            var $btn = $(this).find("button[type='submit']");
            $.ajax({
                type: 'POST',
                url: baseurl + "app_admin/update_ward",
                data: dataToSend,
                beforeSend: function () {
                    $btn.button('loading').html('Wait..')
                },
                success: function (data) {
                    $btn.button('reset');
                    data= JSON.parse(data);
                    if(data.status == 1)
                    {
                        if($(".ward_name_span").length > 0){
                            $(".ward_name_span").html(data.name);
                        }else{
                            if(parentTr){
                                $(parentTr).find(".ward_name").text(data.name);
                            }
                        }
                        $("#update_ward_modal").modal("hide");
                    }
                    else
                    {
                        alert(data.message);
                    }

                },
                error: function (data) {
                    $btn.button('reset');
                    alert("Sorry something went wrong on server.");
                }
            });
        });
    </script>
</div>
