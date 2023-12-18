<div class="Inner-banner">
    <div class="container">
        <div class="row">
            <!--  SLIDER IMAGE -->
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Dashboard</h2> </div>
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
                    <?php echo $this->element('admin_leftsidebar'); ?>
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">



                        <div class="Social-login-box">



                            <div class="form-group">
                                <div class="col-sm-12">
                                    <h3 class="ticket_status">Dashboard</h3>
                                    <ul class="dashboard_icon_li">
                                        <li>
                                            <a href="javascript:void(0)">
                                                <div class="content_div">
                                                    <div class="dash_img dash_text">
                                                        <?php echo $totalUsers; ?>
                                                    </div>
                                                    Total Users
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0)">
                                                <div class="content_div">
                                                    <div class="dash_img dash_text">
                                                        <?php echo $todayUser; ?>
                                                    </div>
                                                    Today's Users
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0)">
                                                <div class="content_div">
                                                    <div class="dash_img dash_text">
                                                        <?php echo $weekUser; ?>
                                                    </div>
                                                    Week's Users
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0)">
                                                <div class="content_div">
                                                    <div class="dash_img dash_text">
                                                        <?php echo $monthUser; ?>
                                                    </div>
                                                    Month's User
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label>Count Users By Date</label>
                                                <input type="text" name="date" id="hasDate" placeholder="Select date" readonly="readonly">
                                            </div>
                                    </div>

                                    <ul class="dashboard_icon_li" id="dateSearchContainer">

                                     </ul>

                                </div>

                            </div>
                            <div class="clear"></div>



                        </div>





                    </div>



                </div>
                <!-- box 1 -->


            </div>
            <!--box 2 -->


        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
            $('#hasDate').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });





        $(document).on('change','#hasDate',function(e){
            var dateVal = $(this).val();
            $.ajax({
                url: baseurl+'/admin/admin/index',
                data:{dateVal:dateVal},
                type:'POST',
                success: function(result){
                    $('#dateSearchContainer').html(result);
                }
            });
        });






    });
</script>