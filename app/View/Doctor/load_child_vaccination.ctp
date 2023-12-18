<div class="modal fade"   id="load_vaccination_modal" data-ci="<?php echo base64_encode($child_id); ?>" tabindex="-1" role="dialog" aria-labelledby="load_vaccination_modal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" ><?php echo $child_data['child_name']; ?><span class="age_title"><?php echo $age; ?></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="min-height: 500px;">
                <img class="modal_loader_image" src="<?php echo Router::url('/img/loader.gif',true); ?>">


                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="date_wise-tab" data-toggle="tab" href="#date_wise" role="tab" aria-controls="date_wise" aria-selected="true">Date<br>Wise</a>
                    </li>
                    <?php if($user_role!='USER'){ ?>
                    <li class="nav-item">
                        <a class="nav-link" id="add_growth-tab" data-toggle="tab" href="#add_growth" role="tab" aria-controls="add_growth" aria-selected="false">Add<br>Growth</a>
                    </li>
                    <?php } ?>

                    <li class="nav-item">
                        <a class="nav-link" id="record-tab" data-toggle="tab" href="#record" role="tab" aria-controls="record" aria-selected="false">Medical<br>Record</a>
                    </li>

                    <li class="nav-item" style="display: none;">
                        <a class="nav-link" id="special_add-tab" data-toggle="tab" href="#special_add" role="tab" aria-controls="special_add" aria-selected="false">Special<br>Adolescent</a>
                    </li>


                    <li class="nav-item" style="display: none;">
                        <a class="nav-link" id="vaccine_wise-tab" data-toggle="tab" href="#vaccine_wise" role="tab" aria-controls="vaccine_wise" aria-selected="false">Vaccination<br>Wise</a>
                    </li>

                </ul>

                <div class="tab-content" id="myTabContent">

                    <div class="tab-pane fade show active" id="date_wise" role="tabpanel" aria-labelledby="date_wise-tab">

                    </div>

                    <div class="tab-pane fade" id="add_growth" role="tabpanel" aria-labelledby="add_growth-tab">


                         <form class="growth_form_container">
                             <div class="form-group">
                                 <label>Weight (In Kilograms)</label>
                                 <input type="number" id="child_weight" class="form-control">
                             </div>
                             <div class="form-group">
                                 <label>Height (In Centimeters)</label>
                                 <input type="number" id="child_height" class="form-control">
                             </div>

                             <div class="form-group">
                                 <label>Head Circumference (In Centimeters)</label>
                                 <input type="number" id="child_head_circumference" class="form-control">
                             </div>

                             <div class="form-group">
                                 <label>Select Date</label>
                                 <input id="growth_date_input" type="text" class="form-control">
                             </div>

                             <div class="form-group">
                                 <button type="reset" class="btn-success btn" >Reset</button>
                                 <button type="button" data-ci="<?php echo base64_encode($child_id); ?>" class="btn-info btn add_growth_btn" >Add Growth</button>
                             </div>

                         </form>



                    </div>

                    <div class="tab-pane fade" id="record"  data-fi="<?php echo $child_data['folder_id']; ?>" data-m="<?php echo $child_data['mobile']; ?>" role="tabpanel" aria-labelledby="record-tab">
                        <div style="max-height: 500px;overflow-y: auto;" class="record_list_container">
                            <div  class="child_medical_doc_list">

                            </div>
                            <div class="load_more" id="load_more_record" style="display: none !important;"><button>Load More</button></div>
                        </div>
                        <div class="child_medical_actio_button_div">
                            <button type="button" class="btn btn-success float_btn add_new_file"><i class="fa fa-plus"></i> </button>
                            <button type="button" class="btn btn-info refresh_btn"><i class="fa fa-refresh"></i> </button>
                        </div>

                    </div>

                    <div class="tab-pane fade" id="special_add" role="tabpanel" aria-labelledby="special_add-tab">Four</div>

                    <div class="tab-pane fade" id="vaccine_wise" role="tabpanel" aria-labelledby="vaccine_wise-tab">Five</div>
                </div>


            </div>


        </div>
    </div>

    <style>

 .datepicker td{
            padding: 8px !important;
        }


        #dateWise{
            overflow-y: auto;
            max-height: 550px;
        }
        .child_medical_doc_list{
            max-height: 500px;
            float: left;
            display: block;
        }
        .growth_form_container{
            padding: 0.6rem;
        }
        .modal_loader_image{
            display: block;
            position: absolute;
            top: 25%;
            z-index: 999;
            background: transparent;
            left: 44%;
            width: 10%;
            margin: 0 auto;
        }

        #load_vaccination_modal .modal-body{
            padding: 0.1rem;
        }
        .lable_table {
            margin: 0.5rem 0px;
        }
        #myTab{
            border-bottom: 2px solid #3333e0;

        }
        .lable_table i{
            background: gray;
            padding: 7px;
            border-radius: 70%;
            color: #fff;
        }
        .lable_table td{
            text-align: center;
            font-size: 0.8rem;
        }


        #load_vaccination_modal .nav-tabs > li > a{
            color: black;
            font-size: 0.7rem;
            padding: 3px 6px;
            text-align: center;
            text-transform: capitalize;
            min-width: 55px;
        }

        #load_vaccination_modal .nav-link.active {
            background-color: #3333e0;
            border-color: #3333e0;
            color: #fff !important;
        }

    </style>



</div>



