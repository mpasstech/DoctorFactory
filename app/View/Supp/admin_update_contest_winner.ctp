<?php 
	$dropArr = array();
	if($contestType == 'TEXT')
	{
		foreach($contestAnswerData AS $data){
			
			$dropArr[$data['User']['id']."-".$data['Thinapp']['id']."-".$data['ContestTextResponse']['mobile']."-".$data['ContestTextResponse']['id']] = $data['ContestTextResponse']['mobile']." (".$data['User']['username'].")";
		}
	}
	else
	{
		foreach($contestAnswerData AS $data){
			
			$dropArr[$data['User']['id']."-".$data['Thinapp']['id']."-".$data['ContestMultipleChoiceAnswer']['mobile']."-0"] = $data['ContestMultipleChoiceAnswer']['mobile']." (".$data['User']['username'].")";
		}
	}
 ?>

<div class="Inner-banner">
    <div class="container">
        <div class="row">

            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Update Winner</h2> </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 pull-right create-btn-box">
            </div>
        </div>
    </div>
</div>


<section class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">

                <div class="middle-block">

                   <?php echo $this->element('support_admin_leftsidebar'); ?>
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                        <div class="progress-bar channel_tap">
                            <a href="<?php echo Router::url('/admin/supp/list_contest'); ?>"   ><i class="fa fa-list"></i> Contest List</a>
                            <a href="<?php echo Router::url('/admin/supp/add_contest'); ?>" class="active" ><i class="fa fa-list"></i> Add Contest</a>
                        </div>


                        <div class="Social-login-box payment_bx">

                            <?php echo $this->element('message'); ?>

                            <?php echo $this->Form->create('ContestWinner',array('type'=>'file','method'=>'post','class'=>'form-horizontal')); ?>

						<div class="row" id="questionContainer">
						
						<?php if(empty($contestWinners)){ ?>
						
							<div class="row questionRow">
                                    <button type="button" style="float: right" id="removeQuestion"  class="btn btn-primary btn-xs dontRemove" ><i class="fa fa-times fa-2x"></i></button>
                                    
									<div class="form-group">
										<div class="col-sm-12">
											<label>Title</label>
											<?php echo $this->Form->input('title.0',array('type'=>'text','placeholder'=>'Enter title','label'=>false,'class'=>'form-control cnt','required'=>true)); ?>
										</div>
									</div>

									<div class="form-group">
										<div class="col-sm-12">
											<label>Winning Title</label>
											<?php echo $this->Form->input('winning_title.0',array('type'=>'text','placeholder'=>'Enter winning title','label'=>false,'class'=>'form-control cnt','required'=>true)); ?>
										</div>
									</div>

									<div class="form-group">
										<div class="col-sm-12">
											<label>User</label>
											<?php echo $this->Form->input('user_data.0',array('type'=>'select','empty'=>'Please select','options'=>$dropArr,'label'=>false,'class'=>'form-control cnt','required'=>true)); ?>
										</div>
									</div>

							</div>	
						<?php }else{ ?>
						
						<?php
                            $k = 0;
                            foreach ($contestWinners AS $key => $val){ ?>
							
							<div class="row questionRow">
                                        <button type="button" style="float: right" id="removeQuestion"  class="btn btn-primary btn-xs <?php echo ($k == 0)?'dontRemove':''; ?>" ><i class="fa fa-times fa-2x"></i></button>
                                    
									<div class="form-group">
										<div class="col-sm-12">
											<label>Title</label>
											<?php echo $this->Form->input('title.'.$key,array('value'=>$val['ContestWinner']['title'],'type'=>'text','placeholder'=>'Enter title','label'=>false,'class'=>'form-control cnt','required'=>true)); ?>
										</div>
									</div>

									<div class="form-group">
										<div class="col-sm-12">
											<label>Winning Title</label>
											<?php echo $this->Form->input('winning_title.'.$key,array('value'=>$val['ContestWinner']['winning_title'],'type'=>'text','placeholder'=>'Enter winning title','label'=>false,'class'=>'form-control cnt','required'=>true)); ?>
											<?php echo $this->Form->input('id.'.$key,array('value'=>$val['ContestWinner']['id'],'type'=>'hidden','label'=>false,'required'=>true)); ?>
										</div>
									</div>

									<div class="form-group">
										<div class="col-sm-12">
											<label>User</label>
											<?php echo $this->Form->input('user_data.'.$key,array('value'=>$val[0]['contest_winner'],'type'=>'select','empty'=>'Please select','options'=>$dropArr,'label'=>false,'class'=>'form-control cnt','required'=>true)); ?>
										</div>
									</div>

							</div>		
						<?php $k++; } ?>
                                <?php } ?>			
							
						</div>	
									<div class="form-group">
										<div class="col-sm-3 pull-left">
											<?php echo $this->Form->button('Add More',array('type'=>'button','class'=>'Btn-typ5','id'=>'addMore')); ?>
										</div>
									</div>
									<div class="form-group">
										<div class="col-sm-3 pull-right">
											<?php echo $this->Form->submit('Update Winner',array('class'=>'Btn-typ5','id'=>'addContest')); ?>
										</div>
									</div>
							
							<?php echo $this->Form->end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<style>
    .row.questionRow {
        border-bottom: 1px solid;
        margin-top: 40px;
        margin-bottom: 20px;
    }
</style>
<script>
    $(document).on('click','#addMore',function () {
        var lengthRow = $('.questionRow').length;
        var htmlToInsert = '<div class="row questionRow"><button type="button" style="float: right" id="removeQuestion" class="btn btn-primary btn-xs"><i class="fa fa-times fa-2x"></i></button><div class="form-group"><div class="col-sm-12"><label>Title</label><div class="input text"><input name="data[title]['+lengthRow+']" placeholder="Enter title" class="form-control cnt" required="required" id="title0" type="text"></div></div></div><div class="form-group"><div class="col-sm-12"><label>Winning Title</label><div class="input text"><input name="data[winning_title]['+lengthRow+']" placeholder="Enter winning title" class="form-control cnt" required="required" id="winning_title0" type="text"></div></div></div><div class="form-group"><div class="col-sm-12"><label>User</label><div class="input select"><select name="data[user_data]['+lengthRow+']" class="form-control cnt" required="required" id="user_data0"><option value="">Please select</option><?php foreach($dropArr AS $key=>$val){ ?><option value="<?php echo $key; ?>"><?php echo $val; ?></option><?php } ?></select></div></div></div></div>';
        $("#questionContainer").append(htmlToInsert);
    });

    $(document).on('click','#removeQuestion',function () {
        if($( this ).hasClass( "dontRemove" ))
        {
            return;
        }
        $(this).parent().remove();
    });
</script>