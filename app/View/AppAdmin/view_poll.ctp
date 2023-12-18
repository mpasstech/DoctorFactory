<?php
$login = $this->Session->read('Auth.User');
?>



<div class="Inner-banner">
    <div class="container">
        <div class="row">
            <!--  SLIDER IMAGE -->
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>View Poll</h2> </div>
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
                <!--box 1 -->
                <div class="middle-block">
                    <!-- Heading -->
                    <?php echo $this->element('app_admin_leftsidebar'); ?>

                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">

                        <div class="Social-login-box">

                            <div class="form-group row">
                                <div class="col-sm-4 p_count_div">
                                    <label>Question Type  </label>
                                    <p><?php echo $question["ActionType"]['name'];?></p>
                                </div>
                                <div class="col-sm-4 p_count_div">
                                    <label>Total Participates  </label>
                                    <p><?php echo $question["ActionQuestion"]['participates_count'];?></p>
                                </div>

                                <div class="col-sm-4 p_count_div">
                                    <label>Total Response  </label>
                                    <p><?php echo $question["ActionQuestion"]['response_count'];?></p>
                                </div>

                            </div>

                            <div class="form-group row">
                                <div class="col-sm-12 question_div" >
                                 <!--   <label>Your Question  </label>-->
                                    <p ><?php echo $question["ActionQuestion"]['question_text'];?></p>
                                </div>
                                </div>

                            <div class="form-group row">
                                    <p>Options </p>
                                    <?php foreach ($question["QuestionChoice"] as $key => $val){ ?>
                                        <div class="col-sm-4">
                                            <div class=" option_lbl">
                                            <div class="opt_cnt"><?php echo $key+1; ?></div>
                                            <?php echo $val['choices']; ?>
                                            </div>
                                        </div>
                                    <?php } ?>


                            </div>



                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Graphical Result </label>
                                    <p>
                                        <?php
                                        $pollData = $this->Custom->getPollChart($id);
                                        $responseCount = $pollData['responseCount'];
                                        $actionChoice = $pollData['actionChoice'];
                                        $questionVal = $pollData['questionVal'];
                                        $actionType = $pollData['actionType'];
                                        $thinApp = $pollData['thinApp'];
                                        $per = '';

                                        if(in_array($actionType['name'],array('DROPDOWN','YES/NO','RATING','SCALING','RANKING','MULTIPLE CHOICES'))) {




                                        if($actionType['name'] == 'RANKING'){ $per = '(%)'; }
                                        ?>

                                    <div id="piechart" align='left' class="poll_chart"></div>

                                    <div id="piechartBar" align='right'class="poll_chart" ></div>

                                    <script type="text/javascript">

                                        $(document).ready(function () {


                                            google.charts.load('current', {'packages': ['corechart','bar']});
                                            google.charts.setOnLoadCallback(drawChart);
                                            function drawChart() {

                                                var data = google.visualization.arrayToDataTable([
                                                    ['option', 'option'],
                                                    <?php
                                                    foreach($actionChoice as $key => $value)
                                                    {
                                                    ?>
                                                    ['<?php echo $value?>', <?php echo (isset($responseCount[$key]))?$responseCount[$key]:0; ?>],
                                                    <?php } ?>
                                                ]);

                                                var options = {
                                                    title: '<?php echo $questionVal['question_text']."(".$thinApp['name'].")"; ?>',
                                                    width: 300


                                                };

                                                var chart = new google.visualization.PieChart(document.getElementById('piechart'));
                                                chart.draw(data, options);



                                            }

                                            // google.charts.load('current', {'packages':['bar']});

                                            google.charts.setOnLoadCallback(drawStuff);

                                            function drawStuff() {
                                                var data = new google.visualization.arrayToDataTable([
                                                    ['OPTIONS', 'Total Votes'],
                                                    <?php
                                                    foreach($actionChoice as $key => $value)
                                                    {
                                                    ?>
                                                    ['<?php echo $value?>', <?php echo (isset($responseCount[$key]))?$responseCount[$key]:0; ?>],
                                                    <?php } ?>
                                                ]);

                                                var optionsBar = {
                                                    width: 300,
                                                    legend: { position: 'none' },
                                                    series: {
                                                        0: {axis: 'votes'},
                                                    },
                                                    axes: {
                                                        y: {
                                                            votes: {label: 'VOTES<?php echo $per; ?>'},
                                                        }
                                                    },
                                                };



                                                var chart = new google.charts.Bar(document.getElementById('piechartBar'));
                                                chart.draw(data, google.charts.Bar.convertOptions(optionsBar));
                                            };

                                        });

                                    </script>


                                    <?php
                                    }

                                    else
                                    { ?>

                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th colspan="3">
                                                    <?php echo $pollData['questionVal']['question_text'];?>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th>
                                                    #
                                                </th>
                                                <th>
                                                    From
                                                </th>
                                                <th>
                                                    Result
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>


                                            <?php
                                            $n = 1;
                                            foreach ($pollData['actionChoice'] as $mobile => $answer)
                                            { ?>

                                                <tr>
                                                    <td><?php echo $n++; ?></td>
                                                    <td><?php echo substr($mobile, 0, 7) . str_repeat("*", strlen($mobile)-7); ?></td>
                                                    <td>
                                                        <?php
                                                        foreach ($answer as $ans)
                                                        {
                                                            echo $ans."<br>";
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>

                                            <?php } ?>
                                            </tbody>
                                        </table>

                                    <?php     }


                                    ?>

                                    </p>
                                </div>

                                <div class="clear"></div>
                            </div>





                        </div>





                </div>






                    </div>




                </div>
                <!-- box 1 -->


            </div>
            <!--box 2 -->

        </div>
    </div>
</div>



<div class="modal fade" id="myModal" role="dialog">

    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <?php echo $this->Form->create('form',array( 'class'=>'form','id'=>'form')); ?>

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Post Message</h4>
            </div>
            <div class="modal-body">

                    <div class="form-group">
                        <?php echo $this->Form->input('reply', array('type'=>'textarea','label'=>false,'required'=>false,'placeholder'=>'Reply here','class'=>'form-control ','id'=>'reply','style'=>(array('box-shadow'=>'0px 0px 0px','border-radius'=>'0px','border'=>'0px none')))); ?>
                        <div class="show_err"></div>
                    </div>

                <div class="form-group">
                    <?php echo $this->Form->input('channel_list', array('type'=>'select','label'=>false,'required'=>false,'options'=>$this->Custom->getChannelList($login['User']['thinapp_id']),'class'=>'form-control ','id'=>'reply','style'=>(array('box-shadow'=>'0px 0px 0px','border-radius'=>'0px','border'=>'0px none')))); ?>
                    <div class="show_err"></div>
                </div>

            </div>
            <div class="modal-footer">
                <?php echo $this->Form->submit('Post Message',array('class'=>'Btn-typ3','id'=>'signup')); ?>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>

</div>





<script>
    $(document).ready(function(){


        $(".channel_tap a").removeClass('active');
        $("#v_app_channel_list").addClass('active');

    $(document).on('click','.post_message',function(e){
        $("#reply").val('');
        $(".show_err").html('');;
        $("#myModal").modal('show').attr("mob",$(this).attr('data-num'));

    })

    });
</script>






