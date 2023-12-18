<div class="modal fade" id="manageMedicineModal" role="dialog"  >
    <div class="modal-dialog modal-md" style="width: 35%;" >
        <div class="modal-content">
            <form id="manage_medicine_form">


            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $label; ?> Medicine</h4>
            </div>
            <div class="modal-body">
                <div class="row">

                    <div class="col-sm-12">
                        <?php echo $this->Form->input('tag_name', array('type'=>'text','value' => @$this->request->data['tag_name'], 'label' => 'Medicine Name', 'class' => 'form-control','id'=>'medicine_name_box','required'=>'required')); ?>
                        <?php $action = "ADD"; $tag_id = @base64_encode($this->request->data['tag_id']); if(!empty($this->request->data['tag_id'])){ $action = "UPDATE"; ?>
                            <?php echo $this->Form->input('ti', array('type'=>'hidden','value' => $tag_id)); ?>
                        <?php } ?>
                        <?php echo $this->Form->input('at', array('type'=>'hidden','value' => $action)); ?>
                        <?php echo $this->Form->input('si', array('type'=>'hidden','value' => base64_encode($step_id))); ?>
                    </div>

                    <div class="col-sm-4">
                        <?php echo $this->Form->input('tag_type', array('type'=>'text','value' => @$this->request->data['tag_type'], 'label' => 'Medicine Type', 'class' => 'form-control', 'id'=>'medicine_type')); ?>
                    </div>

                    <div class="col-sm-8">
                        <?php echo $this->Form->input('company_name', array('type'=>'text','value' => @$this->request->data['company_name'], 'label' => 'Company Name', 'class' => 'form-control', 'id'=>'company_name')); ?>
                    </div>

                    <div class="col-sm-12">
                        <?php echo $this->Form->input('composition', array('type'=>'text','value' => @$this->request->data['composition'], 'label' => 'Composition', 'class' => 'form-control','id'=>'composition')); ?>
                    </div>
                    <div class="col-sm-12">
                        <?php echo $this->Form->input('tag_notes', array('type'=>'text','value' => @$this->request->data['tag_notes'], 'label' => 'Medicine Notes', 'class' => 'form-control','id'=>'composition')); ?>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-xs" data-dismiss="modal"><i class="fa fa-close"></i> Cancel</button>
                <button type="reset" class="btn btn-warning btn-xs"><i class="fa fa-refresh"></i> Reset</button>
                <button type="submit" class="btn btn-success btn-xs save_medicine_btn"><i class="fa fa-save"></i> Save</button>
            </div>
            </form>
        </div>
    </div>



    <script type="text/javascript">
        $(function () {




            setTimeout(function () {
                $("#medicine_name_box").focus();
            },10);

            var company_list = "<?php echo base64_encode(json_encode($company_list)); ?>";
            company_list  = atob(company_list);
            var company_option = {
                data: JSON.parse(company_list),
                getValue: "company_name",
                list:{
                    onChooseEvent: function() {
                    },match: {
                        enabled: true
                    },maxNumberOfElements: 10,
                }
            };
            $("#company_name").easyAutocomplete(company_option);

            var medicine_type_list = "<?php echo base64_encode(json_encode($medicine_type_list)); ?>";
            medicine_type_list  = atob(medicine_type_list);
            var type_option = {
                data: JSON.parse(medicine_type_list),
                getValue: "type",
                list:{
                    onChooseEvent: function() {
                    },match: {
                        enabled: true
                    },maxNumberOfElements: 10,
                }
            };
            $("#medicine_type").easyAutocomplete(type_option);

            var composition_list = "<?php echo base64_encode(json_encode($composition_list)); ?>";
            composition_list  = atob(composition_list);
            var composition_option = {
                data: JSON.parse(composition_list),
                getValue: "composition",
                list:{
                    onChooseEvent: function() {
                    },match: {
                        enabled: true
                    },maxNumberOfElements: 10,
                }
            };
            $("#composition").easyAutocomplete(composition_option);


            $(document).off('submit','#manage_medicine_form');
            $(document).on('submit','#manage_medicine_form',function (e) {
                e.preventDefault();
                var $btn = $(this).find(".save_medicine_btn");
                var $form = $(this);
                var si = "<?php echo $step_id; ?>";

                $.ajax({
                    type:'POST',
                    url: baseUrl+"prescription/manage_medicine",
                    data:$(this).serialize(),
                    beforeSend:function(){
                        $btn.button('loading').html('Saving...');
                    },
                    success:function(response){
                        $btn.button('reset');
                        response = JSON.parse(response);
                        if(response.status == 1){
                            $("#manageMedicineModal").modal('hide');
                            var action  = "<?php echo $action; ?>";
                            if(action=="ADD"){
                                $("#tag_list_box_"+si).prepend(response.html);
                            }else{
                                var tag_id  = "<?php echo base64_decode($tag_id); ?>";

                                $("#li_tag_"+tag_id).replaceWith(response.html);
                            }


                        }else{
                            flash("Add Medicine",response.message, "warning",'center');
                        }
                    },
                    error: function(data){
                        $btn.button('reset');
                        $($form).trigger("reset");
                        flash("Add Patient","Sorry something went wrong on server.", "danger",'center');

                    }
                });
            });




        });
    </script>
    <style>

    </style>


</div>
