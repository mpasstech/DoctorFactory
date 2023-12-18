<div class="Inner-banner">
    <div class="container">
        <div class="row">
            <!--  SLIDER IMAGE -->
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Print Barcode</h2> </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 pull-right create-btn-box">

            </div>
        </div>
    </div>
</div>

<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">
                <!--box 1 -->
                <div class="middle-block">
                    <!-- Heading -->

                    <?php echo $this->element('support_admin_leftsidebar'); ?>
                    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">

                        <div class="Social-login-box dashboard_box" style="padding: 15px; 20px;">

                            <?php echo $this->element('message'); ?>
                            <?php echo $this->Form->create('Barcode',array('type'=>'post','url'=>array('controller'=>'supp','action' => 'print_offline_barcode'),'admin'=>true)); ?>

                            <div class="form-group">
                                <div class="row">
                                    <div class="form-group">
                                        <div class="col-sm-3">
                                            <?php $type_array = $this->SupportAdmin->getAllThinappDropdwon(); ?>
                                            <?php echo $this->Form->input('thinapp_id',array('type'=>'select','label'=>"Select App",'options'=>$type_array,'class'=>'form-control thinapp_id')); ?>
                                        </div>

                                        <div class="col-sm-3">
                                            <?php $type_array = array('A'=>'Appointment'); ?>
                                            <?php echo $this->Form->input('module',array('type'=>'select','label'=>"Select Module",'options'=>$type_array,'class'=>'form-control')); ?>
                                        </div>


                                        <div class="col-sm-2">
                                            <?php echo $this->Form->input('page', array('type' => 'text', 'label' => 'Pages ', 'value'=>'10','class' => 'form-control total_page')); ?>
                                        </div>

                                        <div class="col-sm-2">
                                            <label style="display: block;">&nbsp;</label>
                                            <button class="btn btn-info" type="submit" > Print Barcode</button>
                                         </div>

                                    </div>

                                </div>
                            </div>
                            <?php echo $this->Form->end(); ?>

                            <div class="form-group char_group">

                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <?php if(!empty($last_record_array)){ ?>
                                        <div class="table-responsive">
                                            <table class="table table-responsive ">

                                                <thead>
                                                <tr >
                                                    <th>#</th>
                                                    <th>App Name</th>
                                                    <th>Total Pages</th>
                                                    <th>File Name</th>
                                                    <th>Date</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>

                                                <tbody>

                                               <?php foreach ($last_record_array as $key => $value){ ?>
                                                   <tr>
                                                   <td><?php echo $key+1; ?></td>
                                                   <td><?php echo $value['name']; ?></td>
                                                   <td><?php echo $value['total_pages']; ?></td>
                                                   <td><?php echo $value['bunch_id']; ?></td>
                                                   <td><?php echo date('d-m-Y', strtotime($value['created'])); ?></td>
                                                   <td> <a target="_blank" href="<?php echo Router::url('/offline-barcodes/'.$value['bunch_id'].".pdf",true); ?>" >Download</a></td>
                                                   </tr>
                                               <?php } ?>

                                                </tbody>
                                            </table>

                                        </div>
                                        <?php } ?>
                                        <div class="clear"></div>
                                    </div>
                                </div>




                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>


    .char_group h3{
        text-align: center;
        color: #5f3333;
    }

    .char_group a{
        cursor: pointer;
        position: absolute;
        right: 10px;;
    }

    .bar_chart_div{
        padding-top :10px;
    }
    .row_master, .row_master2, .row_master3 {
        height: 450px;
        position: relative;
        background-color: #efefef;
        border: 2px solid #a9a4a4;
        padding: 3px 0px;
        border-top: none;
        margin: 30px 0px;
    }


    .record_title {
        width: auto;
        position: absolute;
        left: 45%;
        top: 20%;
        font-size: 15px;
    }

    .ajax-loader, .ajax-loader2, .ajax-loader3 {
        width: 5%;
        position: absolute;
        left: 50%;
        top: 50%;
        margin-left: -32px; /* -1 * image width / 2 */
        margin-top: -32px; /* -1 * image height / 2 */
    }


</style>
<script type="text/javascript">
    $(function(){

        var file = '<?php echo @$filename;  ?>';

        if(file!=''){

            window.open(baseurl+'offline-barcodes/'+file,'_blank');
            window.location.href = baseurl+"admin/supp/print_offline_barcode";
        }



    });
</script>
