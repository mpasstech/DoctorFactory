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
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Tiger Reports</h2> </div>
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

                            <div class="form-group">
                                <div class="row">
                                    <div class="form-group">

                                        <div class="col-sm-3">
                                            <?php echo $this->Form->input('dwn_cnt_from', array('type' => 'text',  'label' => 'Download Count <= ', 'value'=>'25','class' => 'form-control dwn_cnt_from')); ?>
                                        </div>
                                        <div class="col-sm-3">
                                            <?php echo $this->Form->input('dwn_cnt_to', array('type' => 'text',  'label' => 'Download Count <= ', 'value'=>'99','class' => 'form-control dwn_cnt_to')); ?>
                                        </div>
                                        <div class="col-sm-3">
                                            <?php echo $this->Form->input('avg_cnt', array('type' => 'text',  'label' => 'Per Day Average = ', 'value'=>'2', 'class' => 'form-control avg_cnt')); ?>
                                        </div>
                                        <div class="col-sm-2">
                                            <?php echo $this->Form->label('Action'); ?>
                                            <input type="button" class ='btn btn-info' id='show_btn' value="Show Report" >
                                        </div>

                                    </div>

                                </div>
                            </div>

                            <div class="form-group">

                                <div class="row row_master">
                                    <a style="float: right;" href="javascript:void(0);" class="tiger_report_download"><i alt="Screen Shot of Chart" class="fa fa-photo"></i> Download Image</a>
                                    <h3 style="text-align: center;">Tiger Potential Report</h3>
                                    <img  style="display: none;" src="<?php echo Router::url('/img/ajaxloader.gif',true); ?>"  class="ajax-loader">
                                    <div  class="col-sm-12 bar_chart_div"  style="height: 100%;">
                                    </div>

                                </div>

                                <div class="row row_master2" style="display: none;">
                                    <div class="row" style="margin: 0px !important;">
                                    <div class="form-group">
                                        <div class="col-sm-3">
                                            <?php echo $this->Form->input('from_date', array('type' => 'text', 'placeholder' => 'dd-mm-yyyy', 'label' => 'From Date', 'value'=>date('d-m-Y'),'class' => 'form-control datetime from_date')); ?>
                                        </div>
                                        <div class="col-sm-3">
                                            <?php echo $this->Form->input('to_date', array('type' => 'text', 'placeholder' => 'dd-mm-yyyy', 'label' => 'To Date', 'value'=>date('d-m-Y'), 'class' => 'form-control datetime to_date')); ?>
                                        </div>
                                        <div class="col-sm-2">
                                            <?php echo $this->Form->label('Action'); ?>
                                            <input type="button" class ='btn btn-info' id="load_user_btn" value="Show Graph" >
                                        </div>

                                    </div>
                                    </div>


                                    <a style="float: right;" href="javascript:void(0);" class="user_download_graph_btn"><i alt="Screen Shot of Chart" class="fa fa-photo"></i> Download Image</a>
                                    <h3 style='text-align: center'>App Downlaod Graph</h3>
                                    <img  style="display: none;" src="<?php echo Router::url('/img/ajaxloader.gif',true); ?>"  class="ajax-loader2">
                                    <div  class="col-sm-12 sub_module_chart_div"  style="height: 100%;">
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
    .row_master {
        height: 450px;
        position: relative;
        background-color: #fff;
        padding: 3px 0px;
        border: 1px solid #f8f8f8;
        margin: 30px 0px;
        overflow-y: scroll;
    }

    .row_master2, .row_master3 {
        height: 530px;
        position: relative;
        background-color: #efefef;
        border: 2px solid #a9a4a4;
        padding: 3px 0px;
        border-top: none;
        margin: 30px 0px;
        overflow-y; scroll;
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


        $(document).on('click','.tiger_report_download',function () {
            html2canvas($(".row_master"), {
                onrendered: function(canvas) {
                    var myImage = canvas.toDataURL("image/jpeg");
                    var tmp_link = $("<a>").attr("href", myImage).attr("download", "tiger_report.jpeg").hide().appendTo("body");
                    tmp_link[0].click(); tmp_link.remove();
                }
            });
        })

        $(document).on('click','.user_download_graph_btn',function () {
            html2canvas($(".sub_module_chart_div"), {
                onrendered: function(canvas) {
                    var myImage = canvas.toDataURL("image/jpeg");
                    var tmp_link = $("<a>").attr("href", myImage).attr("download", "sub_module_graph.jpeg").hide().appendTo("body");
                    tmp_link[0].click(); tmp_link.remove();
                }
            });
        })


        function load_tiger_report(){
                var dcf = $(".dwn_cnt_from").val();
                var dct = $(".dwn_cnt_to").val();
                var ac = $(".avg_cnt").val();
                $.ajax({
                    type:'POST',
                    url: baseurl+"/admin/supp/load_tiger_potential_report",
                    data:{dcf:dcf,dct:dct,ac:ac},
                    beforeSend:function(){
                        $(".ajax-loader").show();
                    },
                    success:function(data){
                           $(".bar_chart_div").html(data);
                           $(".ajax-loader").hide();
                           $(".row_master2").slideUp(500);
                    },
                    error: function(data){
                        $(".ajax-loader").show();
                        $(".row_master2").slideUp(500);
                        $(".file_error").html("Sorry something went wrong on server.");
                    }
                });

        }


        function load_user_download_graph(){
            var fd = $(".from_date").val();
            var td = $(".to_date").val();
            var tid = $(".row_master2").attr('app-id');
            $.ajax({
                type:'POST',
                url: baseurl+"/admin/supp/load_user_download_graph",
                data:{tid:tid,fd:fd,td:td},
                beforeSend:function(){
                    $(".ajax-loader, .ajax-loader2").show();
                },
                success:function(data){

                    $(".sub_module_chart_div").html(data);
                    $(".ajax-loader, .ajax-loader2").hide();
                    $(".row_master2").slideDown(500);
                },
                error: function(data){
                    $(".ajax-loader, .ajax-loader2").show();
                    $(".file_error").html("Sorry something went wrong on server.");
                }
            });

        }


        $(document).on("click",".app_user_btn",function () {
            $(".row_master2").attr('app-id',$(this).attr('app-id'));
            load_user_download_graph();
        })

        $(document).on("click","#load_user_btn",function () {
            load_user_download_graph();
        })




        $(document).on('click','#show_btn',function(e){
            load_tiger_report();
        })


        $('.datetime').datepicker({
            setDate: new Date(),
            autoclose: true,
            format: 'dd-mm-yyyy'
        });

        load_tiger_report();

    });
</script>
