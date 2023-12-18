<div class="row search_row appointment_search_bar">
    <div class="col-xs-12 col-xs-offset-0 main_div">
        <?php echo $this->Form->create('Search',array('type'=>'post','url'=>array('controller'=>'app_admin','action' => 'search_view_app_schedule'),'admin'=>true)); ?>

        <div class="input-group col-xs-12">


            <button data-ref ="<?php echo Router::url('/app_admin/appointment',true); ?>" class="btn btn-default back_btn search_btn" type="button"><span class="fa fa-arrow-left"></span></button>
           <!-- <div class="input-group-btn search-panel status_panel">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    <span id="search_type_btn">Status</span> <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="#s">Staff</a></li>
                    <li><a href="#c">Customer</a></li>
                </ul>
            </div>-->
            <input type="hidden" name="lt" value="<?php echo @$this->request->query['lt']; ?>" id="list_type">
            <input type="text" class="form-control large_search_box"  value="<?php echo @$this->request->query['n']; ?>" name="name" placeholder="Search By Name">
            <input type="text" class="form-control large_search_box"  value="<?php echo @$this->request->query['m']; ?>" name="mobile" placeholder="Search By Mobile">
            <input type="text" class="form-control large_search_box"  value="<?php echo @$this->request->query['t']; ?>" name="title" placeholder="Search By Title">
            <input type="text" class="form-control large_search_box datepicker"  value="<?php echo @$this->request->query['d']; ?>" name="date" placeholder="Search By Date">
            <button class="btn btn-default search_btn" type="Submit"><span class="glyphicon glyphicon-search"></span></button>
            <button class="btn btn-default reset_btn search_btn" type="button" data-ref ="<?php echo Router::url('/app_admin/view_app_schedule',true) ?>"><span class="glyphicon glyphicon-refresh"></span></button>
              <a class="btn btn-info" style="margin: 0px 5px;" href="<?php echo Router::url('/app_admin/list_view_appointment',true)?>"><i class="fa fa-bars"></i> List View</a>



        </div>
        <?php echo $this->Form->end(); ?>


    </div>
</div>
<style>
    .large_search_box{ width: 20% !important; }
    input.large_search_box {
        border: 1px solid #ccc !important;
        -moz-border-radius: 10px !important;
        -webkit-border-radius: 10px !important;
        border-radius: 10px !important;
        -moz-box-shadow: 2px 2px 3px #666 !important;
        -webkit-box-shadow: 2px 2px 3px #666 !important;
        box-shadow: 2px 2px 3px #666 !important;
        padding: 4px 7px !important;
        outline: 0 !important;
        -webkit-appearance: none !important;
    }
    input.large_search_box:focus {
        border-color: #339933;
    }
</style>
<script>
    $(document).ready(function(){
        /*serach box script start*/
      

        var concept = $('#list_type').val();
        if(concept!=""){
            if(concept=='c'){
                $('#search_type_btn').text("Customer");
            }else{
                $('#search_type_btn').text("Staff");
            }
        }else{
            $('#search_type_btn').text("Staff");
        }
        $('.search-panel .dropdown-menu').find('a').click(function(e) {
            e.preventDefault();
            var param = $(this).attr("href").replace("#","");
            var concept = $(this).text();
            $('.search-panel span#search_type_btn').text(concept);
            $('#list_type').val(param);
        });
        /*serach box script end*/

        $(document).on("click", ".reset_btn", function(){
            window.location.href = $(this).attr("data-ref");
        });


        $(document).on("click", ".back_btn", function(){
            window.location.href = $(this).attr("data-ref");
        });

    });
</script>