<style>
.message {
    width: 100% !important;
}
</style>
<?php echo $this->Html->script(array('canvasjs.min.js','html2canvas.js'));?>

<div class="Inner-banner">
    <div class="container">
        <div class="row">
            <!--  SLIDER IMAGE -->
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Visitor Report</h2> </div>
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
                            <p style="text-align: right;"><a  style="text-decoration: underline;" target="_blank" href="<?php echo Router::url('/cron_jobs/update_user_log_stats.php',true); ?>" class="cron_btn">Click To Update Stats</a>
                            </p>

                            <div class="form-group">
                                <div class="row">

                                    <div class="form-group">
                                        <div class="col-sm-2">
                                            <?php $type_array = array('1'=>'Patient','5'=>'Doctor') ?>
                                            <?php echo $this->Form->input('rf',array('type'=>'select','label'=>"Report For",'options'=>$type_array,'class'=>'form-control report_for')); ?>
                                        </div>
                                        <div class="col-sm-2">
                                            <?php $type_array = array('DAILY'=>'Daily','WEEK'=>'Weekly', 'MONTH'=>'Monthly','YEAR'=>'Yearly') ?>
                                            <?php echo $this->Form->input('day_type',array('type'=>'select','label'=>"Select App",'options'=>$type_array,'class'=>'form-control day_type')); ?>
                                        </div>
                                        <div class="col-sm-2">
                                            <?php echo $this->Form->input('download_count', array('type' => 'text', 'label' => 'Download >= ', 'value'=>'10','class' => 'form-control download_cnt')); ?>
                                        </div>
                                        <div class="col-sm-2">
                                            <?php echo $this->Form->input('from_date', array('type' => 'text', 'placeholder' => 'dd-mm-yyyy', 'label' => 'From Date', 'value'=>date('d-m-Y'),'class' => 'form-control datetime from_date')); ?>
                                        </div>
                                        <div class="col-sm-2">
                                            <?php echo $this->Form->input('to_date', array('type' => 'text', 'placeholder' => 'dd-mm-yyyy', 'label' => 'To Date', 'value'=>date('d-m-Y'), 'class' => 'form-control datetime to_date')); ?>
                                        </div>
                                        <div class="col-sm-2">
                                            <?php echo $this->Form->label('&nbsp;'); ?>
                                            <input type="button" class ='btn btn-info' id='show_btn' value="Genrate Graph" >
                                        </div>


                                    </div>

                                </div>
                            </div>

                            <div class="form-group char_group">

                                <div class="row row_master">

                                    <a href="javascript:void(0);" class="scr_sht_module_chart"><i alt="Screen Shot of Chart" class="fa fa-photo"></i> Download Image</a>
                                    <h3>Visitor Report</h3>
                                    <img  style="display: none;" src="<?php echo Router::url('/img/ajaxloader.gif',true); ?>"  class="ajax-loader">
                                    <div class="col-sm-12 bar_chart_div"  style="height: 100%;">
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
        float: right;
        position: relative;
        right: 10px;;
    }

    .bar_chart_div{
        padding-top :10px;
    }
    .row_master, .row_master2, .row_master3 {

    }

    .table-responsive{
        overflow-x: scroll;
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


        $(document).on('click','.scr_sht_module_chart',function () {
            html2canvas($(".row_master"), {
                onrendered: function(canvas) {
                    var myImage = canvas.toDataURL("image/jpeg");
                    var tmp_link = $("<a>").attr("href", myImage).attr("download", "functionality_graph.jpeg").hide().appendTo("body");
                    tmp_link[0].click(); tmp_link.remove();
                }
            });
        })

        $(document).on('click','.scr_sht_sub_chart',function () {
            html2canvas($(".row_master2"), {
                onrendered: function(canvas) {
                    var myImage = canvas.toDataURL("image/jpeg");
                    var tmp_link = $("<a>").attr("href", myImage).attr("download", "sub_module_graph.jpeg").hide().appendTo("body");
                    tmp_link[0].click(); tmp_link.remove();
                }
            });
        })

        $(document).on('click','.scr_sht_user_chart',function () {
            html2canvas($(".row_master3"), {
                onrendered: function(canvas) {
                    var myImage = canvas.toDataURL("image/jpeg");
                    var tmp_link = $("<a>").attr("href", myImage).attr("download", "users_graph.jpeg").hide().appendTo("body");
                    tmp_link[0].click(); tmp_link.remove();
                }
            });
        })

        function load_bar_chart(){
                 var dt = $(".day_type").val();
                 var rf = $(".report_for").val();
                var dc = $(".download_cnt").val();
                var fd = $(".from_date").val();
                var td = $(".to_date").val();
                $.ajax({
                    type:'POST',
                    url: baseurl+"/admin/supp/patient_day_wise_report_table",
                    data:{dt:dt,fd:fd,td:td,dc:dc,rf:rf},
                    beforeSend:function(){
                        $(".ajax-loader").show();
                    },
                    success:function(data){
                           $(".bar_chart_div").html(data);
                            $(".ajax-loader").hide();


                    },
                    error: function(data){
                        $(".ajax-loader").show();

                        $(".file_error").html("Sorry something went wrong on server.");
                    }
                });

        }




        $(document).on('click','#show_btn',function(e){
            load_bar_chart();
        })


        $('.datetime').datepicker({
            setDate: new Date(),
            autoclose: true,
            format: 'dd-mm-yyyy'
        });

        load_bar_chart();

    });
</script>
