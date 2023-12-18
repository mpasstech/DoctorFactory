<div class="row search_row">
    <div class="col-xs-12 col-xs-offset-0">
        <?php echo $this->Form->create('Search',array('type'=>'post','url'=>array('controller'=>'app_admin','action' => 'search_channel'),'admin'=>true)); ?>

        <div class="input-group">
            <div class="input-group-btn search-panel">
                <button type="button" class="btn btn-default dropdown-toggle search_btn" data-toggle="dropdown">
                    <span id="search_concept">Filter by</span> <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="#">All</a></li>
                    <li><a href="#DEFAULT">Default</a></li>
                    <li><a href="#PUBLIC">Public</a></li>
                    <li><a href="#PRIVATE">Private</a></li>
                </ul>
            </div>
            <input type="hidden" name="type" value="<?php echo @$this->request->query['t']; ?>" id="search_param">
            <input type="text" class="form-control large_search_box"  value="<?php echo @$this->request->query['n']; ?>" name="name" placeholder="Search channel">
                <span class="input-group-btn">
                    <button class="btn btn-default search_btn" type="Submit"><span class="glyphicon glyphicon-search"></span></button>
                </span>
        </div>
        <?php echo $this->Form->end(); ?>


    </div>
</div>
<script>
    $(document).ready(function(){
        /*serach box script start*/
        var concept = $('#search_param').val();
        if(concept!=""){
            $('#search_concept').text(concept);
        }
        $('.search-panel .dropdown-menu').find('a').click(function(e) {
            e.preventDefault();
            var param = $(this).attr("href").replace("#","");
            var concept = $(this).text();
            $('.search-panel span#search_concept').text(concept);
            $('.input-group #search_param').val(param);
        });
        /*serach box script end*/
    });
</script>