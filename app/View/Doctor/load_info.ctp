<div class="modal" id="load_info" tabindex="-1" >
    <style>
        #load_info .bootstrap-tagsinput .tag, .dialog_tag_box .bootstrap-tagsinput .tag {
            margin: 0px;
            color: #fff;
            background: red;
            padding: 2px 5px;
            border-radius: 3px;
            line-height: 1.8rem;
            font-size: 0.8rem;
            text-align: center;
        }

        .dialog_tag_box{
            float: left;
            width: 100%;
            display: block;
            border-top: 1px solid #d6d6d6;
            margin-top: 30px;
            padding-top: 10px;
        }

        #load_info label {
            display: block;
            width: 100%;
            font-weight: 600;
        }
        #load_info .bootstrap-tagsinput  {
            width: 100%;
        }

        .swal2-modal .swal2-title{
            font-size: 1.5rem !important;
        }
        .tag_ul{
            margin: 0;
            padding: 0;
            width: 100%;
            display: block;
            float: left;
        }
        .tag_ul li{
            list-style: none;
            float: left;
            display: block;
            border: 1px solid;
            margin: 2px;
            padding: 4px 8px;
            border-radius: 20px;
            color: #1751f5;
            font-size: 0.7rem;
        }

        .swal2-modal{
            padding: 6px 10px !important;

        }

        .swal2-modal .swal2-styled{
            padding: 4px 10px !important;
            font-size: 0.9rem !important;
        }
        .tag_ul li.selected{
            color: #fff;
            background: #1751f5;
        }

        .input_label{
            margin: 13px 0px;
            display: block;
            float: left;
            width: 100%;
            font-size: 0.9rem;
            font-weight: 600;
        }

    </style>
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Patient Health History</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding: 0px;">
                <div class="row">
                    <div class="col-12 form_box" style="padding: 0; margin: 0px;">
                        <h5 style="text-align: center;"><?php echo $data['first_name']; ?></h5>
                        <div class="row box" data-id="flags">
                            <div class="col-12">
                                <label>Flags</label>
                                <input   class="form-control" value="<?php echo $data['flag']; ?>"  type="text"  id="flags" />
                            </div>
                        </div>
                        <div class="row box" data-id="allergy">
                            <div class="col-12">
                                <label>Allergy</label>
                                <input  class="form-control" value="<?php echo $data['allergy']; ?>" type="text"  id="allergy" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <label>Disease/Operation History</label>
                                <textarea  class="form-control" rows="5" placeholder="If any disease or operation has happened to this patient and family member, then please mention here"  id="history" ><?php echo $data['medical_history']; ?></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <label>Height (Feet)</label>
                                <input  class="form-control" value="<?php echo $data['height']; ?>" type="number"  id="height" />
                            </div>

                            <div class="col-6">
                                <label>Weight (kg)</label>
                                <input  class="form-control" value="<?php echo $data['weight']; ?>" type="number"  id="weight" />
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-6">
                                <label>BP (mm Hg)</label>
                                <input  data-masked-input="99-999" placeholder="00-000" class="form-control" value="<?php echo $data['bp_systolic']; ?>" type="text"  id="bp_systolic" />
                            </div>

                            <div class="col-6">
                                <label>Temperature (°C)</label>
                                <input  class="form-control" value="<?php echo $data['temperature']; ?>" type="number"  id="temperature" />
                            </div>


                        </div>
                        <div class="row">
                            <div class="col-12">
                                <label>Oxygen (mm Hg)</label>
                                <input  class="form-control" value="<?php echo $data['o_saturation']; ?>" type="number"  id="o_saturation" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="save_btn">Save </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>


