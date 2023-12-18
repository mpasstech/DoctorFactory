<div class="modal fade"   id="patientRecordModal" tabindex="-1" data-fi="<?php echo $folder_id; ?>" aria-labelledby="patientRecordModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" ><?php echo $name; ?></h5>
                <button type="button"  class="close VacDetailClose" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="medical_lis_container">
                    <div class="row blog-container patient_record_list"></div>
                    <div class="load_more" id="load_more_patient_record" style="display: none !important;"><button>Load More</button></div>
                </div>

                <button type="button" class="btn btn-success float_btn add_new_file"><i class="fa fa-plus"></i> </button>
                <button type="button" class="btn btn-info refresh_btn"><i class="fa fa-refresh"></i> </button>
            </div>

        </div>
    </div>
    <style>
        .medical_lis_container{
            height: 500px;
            overflow-y: auto;
        }
    </style>
</div>



