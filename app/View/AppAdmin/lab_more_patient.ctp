<div class="modal fade" id="lab_more_patient" role="dialog" keyboard="true">

    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="color:#FFF;">More Patient</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form method="post" id="search_form">
                        <div class="form-group" >
                            <div class="col-md-4">
                                <div class="input text">
                                    <label for="SearchSearch">Search Name/Mobile</label>
                                    <input name="search" value="<?php echo $search; ?>" placeholder="" required="required" class="form-control" type="text" id="SearchInput">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input text">
                                    <label style="display: block;">&nbsp;</label>
                                    <input class="btn btn-info" style="width:90%;height: 41px !important;" id="search_btn" type="submit" value="Search">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input text">
                                    <label style="display: block;">&nbsp;</label>
                                    <button class="btn btn-warning" style="width:90%;height: 41px !important;" id="add_new_patient_btn" type="button" >Add Patient</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row">
                    <div class="table-responsive">

                        <table id="example_tab" class="table">
                            <tr>
                                <th>#</th>
                                <th>Patient Name</th>
                                <th>Mobile</th>
                                <th>Gender</th>
                                <th>UHID</th>
                                <th>Email</th>
                                <th>Options</th>
                            </tr>
                            <?php foreach($dataToSend AS $key => $list){ ?>
                                <tr>
                                    <td><?php echo $key+1; ?></td>
                                    <td><?php echo $list['AppointmentCustomer']['first_name']; ?></td>
                                    <td><?php echo $list['AppointmentCustomer']['mobile']; ?></td>
                                    <td><?php echo $list['AppointmentCustomer']['gender']; ?></td>
                                    <td><?php echo $list['AppointmentCustomer']['uhid']; ?></td>
                                    <td><?php echo $list['AppointmentCustomer']['email']; ?></td>
                                    <td>
                                        <?php if(isset($list['DriveFolder']['id'])){ ?>
                                            <a class="btn btn-xs btn-info add_new_file" href="javascript:void(0);" data-id="<?php echo $list['DriveFolder']['id']; ?>"><i class="fa fa-upload"></i> Upload</a>
                                            <button type="button" class="btn btn-xs btn-info files_btn" data='<?php echo json_encode($list['file_list']); ?>'>Files</button>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </table>
                </div>
            </div>
        </div>
    </div>
        <script>
            $(document).ready(function(){
                /*$('#example_tab').DataTable({
                    "language": {
                        "emptyTable": "No Data Found Related To Search"
                    }
                });*/
            });
        </script>
</div>