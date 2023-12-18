<div class="progress-bar quest_tap">
    <?php $quest_type = base64_decode($this->request->query['qt']); ?>
    <?php $action = $this->request->params['action']?>
    <a id="v_app_quest_list"  href="<?php echo Router::url('/app_admin/quest?qt=').base64_encode($quest_type) ?>"><i class="fa fa-list"></i> <?php echo ucfirst($quest_type); ?> List</a>

    <a id="v_add_quest" href="<?php echo Router::url('/app_admin/add_quest?qt=').base64_encode($quest_type); ?>" ><i class="fa fa-television"></i> Add <?php echo ucfirst($quest_type); ?></a>

    <a id="v_permit_quest" href="<?php echo Router::url('/app_admin/permit_quest?qt=').base64_encode($quest_type); ?>" ><i class="fa fa-globe"></i> Permit <?php echo ucfirst($quest_type); ?></a>

</div>
<style>
    .quest_tap a{ width:33% !important; }
</style>

<script>
    $(document).ready(function(){


        $(".quest_tab a").removeClass('active');
        var action = '<?php echo $action; ?>';
        console.log(action);
        if(action=='quest'){
            $("#v_app_quest_list").addClass('active');
        }else if(action=='add_quest'){
            $("#v_add_quest").addClass('active');
        }else if(action=='permit_quest'){
            $("#v_permit_quest").addClass('active');
        }


    });
</script>