<?php $dataToShow = $this->request->data;?>
<div class="Inner-banner">
    <div class="container">
        <div class="row">

            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Edit Contest Question</h2> </div>
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

                            <?php echo $this->Form->create('Contest',array('type'=>'file','method'=>'post','class'=>'form-horizontal')); ?>

                            <div class="row" id="questionContainer">

                                <?php if(empty($dataToShow)){ ?>

                                    <div class="row questionRow">
                                        <button type="button" style="float: right" id="removeQuestion"  class="btn btn-primary btn-xs dontRemove" ><i class="fa fa-times fa-2x"></i></button>
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <label>Question</label>
                                                <?php echo $this->Form->input('question.0',array('type'=>'text','placeholder'=>'Question','label'=>false,'class'=>'form-control cnt','required'=>true)); ?>
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <div class="col-sm-3">
                                                <label>Answers A</label>
                                                <?php echo $this->Form->input('answer_a.0',array('type'=>'text','placeholder'=>'Answer A','label'=>false,'class'=>'form-control cnt','required'=>true)); ?>
                                            </div>
                                            <div class="col-sm-3">
                                                <label>Answers B</label>
                                                <?php echo $this->Form->input('answer_b.0',array('type'=>'text','placeholder'=>'Answer B','label'=>false,'class'=>'form-control cnt','required'=>true)); ?>
                                            </div>
                                            <div class="col-sm-3">
                                                <label>Answers C</label>
                                                <?php echo $this->Form->input('answer_c.0',array('type'=>'text','placeholder'=>'Answer C','label'=>false,'class'=>'form-control cnt','required'=>true)); ?>
                                            </div>
                                            <div class="col-sm-3">
                                                <label>Answers D</label>
                                                <?php echo $this->Form->input('answer_d.0',array('type'=>'text','placeholder'=>'Answer D','label'=>false,'class'=>'form-control cnt','required'=>true)); ?>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Correct Answer</label>
                                            <?php echo $this->Form->radio('correct_answer.0', array('A' => 'A', 'B' => 'B', 'C' => 'C', 'D' => 'D'), array('default' => 'A', 'legend' => false, 'label' => false, 'style'=>'margin:12px')); ?>
                                        </div>

                                    </div>

                                <?php }else{ ?>
                                    <?php
                                    $k = 0;
                                    foreach ($dataToShow AS $key => $val){ ?>
                                        <div class="row questionRow">
                                        <button type="button" style="float: right" id="removeQuestion"  class="btn btn-primary btn-xs <?php echo ($k == 0)?'dontRemove':''; ?>" ><i class="fa fa-times fa-2x"></i></button>
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <label>Question</label>
                                                <?php echo $this->Form->input('question.'.$key,array('value'=>$val['ContestMultipleChoiceQuestion']['question'],'type'=>'text','placeholder'=>'Question','label'=>false,'class'=>'form-control cnt','required'=>true)); ?>
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <div class="col-sm-3">
                                                <label>Answers A</label>
                                                <?php echo $this->Form->input('answer_a.'.$key,array('value'=>$val['ContestMultipleChoiceQuestion']['option_a'],'type'=>'text','placeholder'=>'Answer A','label'=>false,'class'=>'form-control cnt','required'=>true)); ?>
                                            </div>
                                            <div class="col-sm-3">
                                                <label>Answers B</label>
                                                <?php echo $this->Form->input('answer_b.'.$key,array('value'=>$val['ContestMultipleChoiceQuestion']['option_b'],'type'=>'text','placeholder'=>'Answer B','label'=>false,'class'=>'form-control cnt','required'=>true)); ?>
                                            </div>
                                            <div class="col-sm-3">
                                                <label>Answers C</label>
                                                <?php echo $this->Form->input('answer_c.'.$key,array('value'=>$val['ContestMultipleChoiceQuestion']['option_c'],'type'=>'text','placeholder'=>'Answer C','label'=>false,'class'=>'form-control cnt','required'=>true)); ?>
                                            </div>
                                            <div class="col-sm-3">
                                                <label>Answers D</label>
                                                <?php echo $this->Form->input('answer_d.'.$key,array('value'=>$val['ContestMultipleChoiceQuestion']['option_d'],'type'=>'text','placeholder'=>'Answer D','label'=>false,'class'=>'form-control cnt','required'=>true)); ?>
                                                <?php echo $this->Form->input('id.'.$key,array('value'=>$val['ContestMultipleChoiceQuestion']['id'],'type'=>'hidden','label'=>false,'required'=>true)); ?>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Correct Answer</label>
                                            <?php echo $this->Form->radio('correct_answer.'.$key, array('A' => 'A', 'B' => 'B', 'C' => 'C', 'D' => 'D'), array('default' => $val['ContestMultipleChoiceQuestion']['answer'], 'legend' => false, 'label' => false, 'style'=>'margin:12px')); ?>
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
                                    <?php echo $this->Form->submit('Update Question',array('class'=>'Btn-typ5','id'=>'addContest')); ?>
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
        var htmlToInsert = '<div class="row questionRow"><button type="button" style="float: right" id="removeQuestion" class="btn btn-primary btn-xs" ><i class="fa fa-times fa-2x"></i></button><div class="form-group"><div class="col-sm-12"><label>Question</label><div class="input text"><input name="data[question]['+lengthRow+']" placeholder="Question" class="form-control cnt" required="required" id="question0" type="text"></div></div></div><div class="form-group"><div class="col-sm-3"><label>Answers A</label><div class="input text"><input name="data[answer_a]['+lengthRow+']" placeholder="Answer A" class="form-control cnt" required="required" id="answer_a0" type="text"></div></div><div class="col-sm-3"><label>Answers B</label><div class="input text"><input name="data[answer_b]['+lengthRow+']" placeholder="Answer B" class="form-control cnt" required="required" id="answer_b0" type="text"></div></div><div class="col-sm-3"><label>Answers C</label><div class="input text"><input name="data[answer_c]['+lengthRow+']" placeholder="Answer C" class="form-control cnt" required="required" id="answer_c0" type="text"></div></div><div class="col-sm-3"><label>Answers D</label><div class="input text"><input name="data[answer_d]['+lengthRow+']" placeholder="Answer D" class="form-control cnt" required="required" id="answer_d0" type="text"></div></div></div><div class="form-group"><label>Correct Answer</label><input name="data[correct_answer]['+lengthRow+']" id="correct_answer0A" style="margin:12px" value="A" checked="checked" type="radio">A<input name="data[correct_answer]['+lengthRow+']" id="correct_answer0B" style="margin:12px" value="B" type="radio">B<input name="data[correct_answer]['+lengthRow+']" id="correct_answer0C" style="margin:12px" value="C" type="radio">C<input name="data[correct_answer]['+lengthRow+']" id="correct_answer0D" style="margin:12px" value="D" type="radio">D</div></div>';
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