<section class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">

                <div class="middle-block">


                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                           <div class="form-group row">
                                <div class="col-sm-12">

                                    <?php if(isset($response['message'])){ ?>
                                        <div class="message" id="successMessage"><?php echo $response['message']; ?></div>


                                    <?php  if($response['showGraph'] == 1){
                                    $pollData = $this->Custom->getPollChart($actionQuestionsID);
                                    $responseCount = $pollData['responseCount'];
                                    $actionChoice = $pollData['actionChoice'];
                                    $questionVal = $pollData['questionVal'];
                                    $actionType = $pollData['actionType'];
                                    $thinApp = $pollData['thinApp'];
                                    $per = '';

                                    if(in_array($actionType['name'],array('DROPDOWN','YES/NO','RATING','SCALING','RANKING','MULTIPLE CHOICES'))) {
                                    if($actionType['name'] == 'RANKING'){ $per = '(%)'; }
                                    ?>

                                    <div class="col-sm-12">
                                        <div id="piechart"></div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div id="piechartBar"align='center' ></div>
                                    </div>
                                        <script type="text/javascript">

                                            $(document).ready(function () {


                                                google.charts.load('current', {'packages': ['corechart','bar']});
                                                google.charts.setOnLoadCallback(drawChart);
                                                function drawChart() {

                                                    var data = google.visualization.arrayToDataTable([
                                                        ["option", "option"],
                                                        <?php
                                                        foreach($actionChoice as $key => $value)
                                                        {
                                                        ?>
                                                        ["<?php echo $value?>", <?php echo (isset($responseCount[$key]))?$responseCount[$key]:0; ?>],
                                                        <?php } ?>
                                                    ]);

                                                    var options = {
                                                        title: "<?php echo $questionVal['question_text']."(".$thinApp['name'].")"; ?>",


                                                    };

                                                    var chart = new google.visualization.PieChart(document.getElementById('piechart'));
                                                    chart.draw(data, options);



                                                }

                                                // google.charts.load('current', {'packages':['bar']});

                                                google.charts.setOnLoadCallback(drawStuff);

                                                function drawStuff() {
                                                    var data = new google.visualization.arrayToDataTable([
                                                        ["OPTIONS", "Total Votes"],
                                                        <?php
                                                        foreach($actionChoice as $key => $value)
                                                        {
                                                        ?>
                                                        ["<?php echo $value?>", <?php echo (isset($responseCount[$key]))?$responseCount[$key]:0; ?>],
                                                        <?php } ?>
                                                    ]);

                                                    var optionsBar = {
                                                      //  width: 300,
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
                                                        <?php echo implode(", <br>",$answer); ?>
                                                    </td>
                                                </tr>

                                            <?php } ?>
                                            </tbody>
                                        </table>

                                   <?php     }

                                    } ?>

                                    <?php } ?>



                                    <?php  if(isset($question) && !empty($question) && ($response['status'] == 0)){  ?>
                                        <div class="table-responsive">
                                                 <form name="submitResponse" method="post" id="submitResponse">

                                        <?php if($question['ActionType']['name'] == 'SHORT ANSWER'){ ?>

                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <th colspan="2">
                                                        <?php echo $question['Thinapp']['name']; ?>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th>Poll: </th>
                                                    <th><?php echo $question['ActionQuestion']['question_text']; ?></th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                <tr>
                                                    <td>Answer</td>
                                                    <td>
                                                        <input type="text" name="response[<?php echo $question['QuestionChoice'][0]['id']; ?>]" placeholder="<?php echo $question['QuestionChoice'][0]['choices']; ?>" <?php echo ($question['ActionQuestion']['is_mandatory'] == 'YES')?'required':''; ?> >
                                                        <?php echo ($question['ActionQuestion']['is_mandatory'] == 'YES')?'*':''; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <input type="submit" name="submitResponse" class="Btn-typ3" value="Submit" id="submitID">
                                                    </td>
                                                </tr>

                                                </tbody>
                                            </table>

                                        <?php } ?>

                                        <?php if($question['ActionType']['name'] == 'LONG ANSWER'){ ?>

                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <th colspan="2">
                                                        <?php echo $question['Thinapp']['name']; ?>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th>Poll: </th>
                                                    <th><?php echo $question['ActionQuestion']['question_text']; ?></th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                <tr>
                                                    <td>Answer</td>
                                                    <td>
                                                        <textarea name="response[<?php echo $question['QuestionChoice'][0]['id']; ?>]" <?php echo ($question['ActionQuestion']['is_mandatory'] == 'YES')?'required':''; ?> placeholder="<?php echo $question['QuestionChoice'][0]['choices']; ?>" ></textarea>
                                                        <?php echo ($question['ActionQuestion']['is_mandatory'] == 'YES')?'*':''; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <input type="submit" name="submitResponse" class="Btn-typ3" value="Submit" id="submitID">
                                                    </td>
                                                </tr>

                                                </tbody>
                                            </table>

                                        <?php } ?>

                                        <?php if($question['ActionType']['name'] == 'DROPDOWN'){ ?>

                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <th colspan="2">
                                                        <?php echo $question['Thinapp']['name']; ?>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th>Poll: </th>
                                                    <th><?php echo $question['ActionQuestion']['question_text']; ?></th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                <tr>
                                                    <td>Answer</td>
                                                    <td>

                                                        <select name="response" <?php echo ($question['ActionQuestion']['is_mandatory'] == 'YES')?'required':''; ?>>
                                                            <option value="">Select</option>
                                                            <?php foreach($question['QuestionChoice'] as $que){ ?>
                                                                <option value="<?php echo $que['id']; ?>,<?php echo $que['choices']; ?>"><?php echo $que['choices']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <?php echo ($question['ActionQuestion']['is_mandatory'] == 'YES')?'*':''; ?>

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <input type="submit" name="submitResponse" class="Btn-typ3" value="Submit" id="submitID">
                                                    </td>
                                                </tr>

                                                </tbody>
                                            </table>

                                        <?php } ?>

                                        <?php if($question['ActionType']['name'] == 'YES/NO'){ ?>

                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <th colspan="2">
                                                        <?php echo $question['Thinapp']['name']; ?>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th>Poll: </th>
                                                    <th><?php echo $question['ActionQuestion']['question_text']; ?></th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                <tr>
                                                    <td>Answer</td>
                                                    <td>


                                                        <?php
                                                        $n = 1;
                                                        foreach($question['QuestionChoice'] as $que){ ?>
                                                            <input type="radio" name="response" <?php if($n == 1){?> checked="checked"<?php } ?>  value="<?php echo $que['id']; ?>,<?php echo $que['choices']; ?>"><?php echo $que['choices']; ?>&nbsp;&nbsp;
                                                        <?php } ?>
                                                        <?php echo ($question['ActionQuestion']['is_mandatory'] == 'YES')?'*':''; ?>


                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <input type="submit" name="submitResponse" class="Btn-typ3" value="Submit" id="submitID">
                                                    </td>
                                                </tr>

                                                </tbody>
                                            </table>

                                        <?php } ?>

                                        <?php if($question['ActionType']['name'] == 'DATE'){ ?>

                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <th colspan="2">
                                                        <?php echo $question['Thinapp']['name']; ?>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th>Poll: </th>
                                                    <th><?php echo $question['ActionQuestion']['question_text']; ?></th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                <tr>
                                                    <td>Answer</td>
                                                    <td>
                                                        <input type="text" <?php echo ($question['ActionQuestion']['is_mandatory'] == 'YES')?'required':''; ?> name="response[<?php echo $question['QuestionChoice'][0]['id']; ?>]" placeholder="<?php echo $question['QuestionChoice'][0]['choices']; ?>" id="datePick" data-date-format="yyyy-mm-dd" readonly >
                                                        <?php echo ($question['ActionQuestion']['is_mandatory'] == 'YES')?'*':''; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <input type="submit" name="submitResponse" class="Btn-typ3" value="Submit" id="submitID">
                                                    </td>
                                                </tr>

                                                </tbody>
                                            </table>

                                            <script>
                                                $(document).ready(function () {
                                                    $('#datePick').datepicker();
                                                });
                                            </script>

                                        <?php } ?>

                                        <?php if($question['ActionType']['name'] == 'RATING'){ ?>

                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <th colspan="2">
                                                        <?php echo $question['Thinapp']['name']; ?>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th>Poll: </th>
                                                    <th><?php echo $question['ActionQuestion']['question_text']; ?></th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                <tr>
                                                    <td>Answer</td>
                                                    <td>
                                                        <fieldset class="rating">
                                                            <?php
                                                            $html = '';
                                                            foreach($question['QuestionChoice'] as $que){
                                                                $html ='<input type="radio" id="star'.$que["choices"].'" name="response" value="'.$que["id"].','.$que["choices"].'" /><label class = "full" for="star'.$que["choices"].'" title="rating"></label>'.$html;
                                                            }
                                                            echo $html;
                                                            ?>
                                                        </fieldset>
                                                        

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <input type="submit" name="submitResponse" class="Btn-typ3" value="Submit" id="submitID">
                                                    </td>
                                                </tr>

                                                </tbody>
                                            </table>
                                            <style>

                                                fieldset, label { margin: 0; padding: 0; }
                                                /****** Style Star Rating Widget *****/

                                                .rating {
                                                    border: none;
                                                    float: left;
                                                }

                                                .rating > input { display: none; }
                                                .rating > label:before {
                                                    margin: 5px;
                                                    font-size: 1.25em;
                                                    font-family: FontAwesome;
                                                    display: inline-block;
                                                    content: "\f005";
                                                }

                                                .rating > .half:before {
                                                    content: "\f089";
                                                    position: absolute;
                                                }

                                                .rating > label {
                                                    color: #ddd;
                                                    float: right;
                                                }

                                                /***** CSS Magic to Highlight Stars on Hover *****/

                                                .rating > input:checked ~ label, /* show gold star when clicked */
                                                .rating:not(:checked) > label:hover, /* hover current star */
                                                .rating:not(:checked) > label:hover ~ label  { color: #FFD700;  } /* hover previous stars in list */

                                                .rating > input:checked + label:hover, /* hover current star when changing rating */
                                                .rating > input:checked ~ label:hover,
                                                .rating > label:hover ~ input:checked ~ label, /* lighten current selection */
                                                .rating > input:checked ~ label:hover ~ label { color: #FFED85;  }
                                            </style>
                                        <?php } ?>

                                        <?php if($question['ActionType']['name'] == 'MULTIPLE INPUTS'){ ?>

                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <th colspan="2">
                                                        <?php echo $question['Thinapp']['name']; ?>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th>Poll: </th>
                                                    <th>
                                                        <?php echo $question['ActionQuestion']['question_text']; ?>
                                                        
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                <?php
                                                $num = 1;
                                                foreach($question['QuestionChoice'] as $que){ ?>
                                                    <tr>
                                                        <td>Answer <?php echo $num++;?></td>
                                                        <td>
                                                            <input type="text" <?php echo ($question['ActionQuestion']['is_mandatory'] == 'YES')?'required':''; ?> name="response[<?php echo $que['id']; ?>]" placeholder="<?php echo $que['choices']; ?>">
                                                            <?php echo ($question['ActionQuestion']['is_mandatory'] == 'YES')?'*':''; ?>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                                <tr>
                                                    <td colspan="2">
                                                        <input type="submit" name="submitResponse" class="Btn-typ3" value="Submit" id="submitID">
                                                    </td>
                                                </tr>

                                                </tbody>
                                            </table>

                                        <?php } ?>

                                        <?php if($question['ActionType']['name'] == 'MULTIPLE CHOICES'){ ?>

                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <th colspan="2">
                                                        <?php echo $question['Thinapp']['name']; ?>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th>Poll: </th>
                                                    <th><?php echo $question['ActionQuestion']['question_text']; ?></th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                <?php
                                                $num = 1;
                                                foreach($question['QuestionChoice'] as $que){ ?>
                                                    <tr>
                                                        <td>Answer <?php echo $num++;?></td>
                                                        <td>
                                                            <?php echo $que['choices']; ?> <input type="checkbox" name="response[<?php echo $que['id']; ?>]" value="<?php echo $que['choices']; ?>">
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                                <tr>
                                                    <td colspan="2">
                                                        <input type="submit" name="submitResponse" class="Btn-typ3" value="Submit" id="submitID">
                                                    </td>
                                                </tr>

                                                </tbody>
                                            </table>

                                        <?php } ?>

                                        <?php if($question['ActionType']['name'] == 'SCALING'){ ?>

                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <th colspan="2">
                                                        <?php echo $question['Thinapp']['name']; ?>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th>Poll: </th>
                                                    <th><?php echo $question['ActionQuestion']['question_text']; ?></th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                <tr>
                                                    <td>Answer</td>
                                                    <td>


                                                        <?php
                                                        $n = 1;
                                                        foreach($question['QuestionChoice'] as $que){ ?>
                                                            <input type="radio" <?php echo ($n == 1)?'checked="checked"':''; ?> name="response" value="<?php echo $que['id']; ?>,<?php echo $que['choices']; ?>"><?php echo $que['choices']; ?>&nbsp;&nbsp;
                                                        <?php } ?>


                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <input type="submit" name="submitResponse" class="Btn-typ3" value="Submit" id="submitID">
                                                    </td>
                                                </tr>

                                                </tbody>
                                            </table>

                                        <?php } ?>

                                        <?php if($question['ActionType']['name'] == 'RANKING'){ ?>

                                            <table class="table" id="tableRanking">
                                                <thead>
                                                <tr>
                                                    <th colspan="2">
                                                        <?php echo $question['Thinapp']['name']; ?>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th>Poll: </th>
                                                    <th><?php echo $question['ActionQuestion']['question_text']; ?></th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                <?php
                                                $n = sizeof($question['QuestionChoice']);
                                                foreach($question['QuestionChoice'] as $que){ ?>
                                                    <tr id="<?php echo $n; ?>">
                                                        <td colspan="2">
                                                            <input type="hidden" name="response[<?php echo $que['id']; ?>]" value="<?php echo $n; ?>"> <?php echo $que['choices']; ?>
                                                        </td>
                                                    </tr>
                                                    <?php $n--; } ?>
                                                </tbody>
                                                <tfoot>
                                                <tr>
                                                    <th colspan="2">
                                                        <input type="submit" name="submitResponse" class="Btn-typ3" value="Submit" id="submitID">
                                                    </th>
                                                </tr>
                                                </tfoot>

                                            </table>

                                            <script type="text/javascript">
                                                $(document).ready(function() {
                                                    $("#tableRanking").tableDnD({
                                                        onDrop: function(table, row) {
                                                            var rows = table.tBodies[0].rows;
                                                            for (var i=0; i<rows.length; i++) {
                                                                var j = i+ + 1;
                                                                $(rows[i]).find('input[type="hidden"]').val(rows.length-i);
                                                            }

                                                        },
                                                    });
                                                });
                                            </script>

                                        <?php } ?>

                                    </form>
                                        </div>
                                    <?php } ?>

                                </div>
                            </div>
                            <div class="clear"></div>




                    </div>



                </div>



            </div>



        </div>
    </div>
</section>