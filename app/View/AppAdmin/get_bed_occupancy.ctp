<?php  echo $this->Html->css(array('dataTableBundle.css')); ?>
<?php  echo $this->Html->script(array('dataTableBundle.js','jquery.maskedinput-1.2.2-co.min.js','comman.js')); ?>
<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">
                <!--box 1 -->
                <!--box 1 -->

                <?php $searchBY = isset($this->request->data['Search']['search_by'])?$this->request->data['Search']['search_by']:''; ?>

                <div class="middle-block">
                    <!-- Heading -->
                    <h3 class="screen_title"> Bed Occupancy</h3>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 custom_form_box">

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                            <?php echo $this->Form->create('Search',array('type'=>'get','url'=>array('controller'=>'app_admin','action' => 'search_get_bed_occupancy'))); ?>
                            <div class="form-group">
                                <div class="col-sm-2">

                                    <?php array_unshift($catList, array("HOSPITAL"=>"Hospital")); echo $this->Form->input('search_by', array('type' => 'select','options'=>$catList,'label' => 'Search By Ward',"empty"=>"Search By Ward", 'class' => 'form-control')); ?>
                                </div>

                                <div class="col-sm-2">
                                    <?php echo $this->Form->input('search_type', array('type' => 'select','options'=>array("DAY"=>"Day","MONTH"=>"Month"),'label' => 'Search By Day/Date', 'class' => 'form-control')); ?>
                                </div>

                                <div class="col-sm-2">
                                    <?php echo $this->Form->input('start_date', array('type' => 'text', 'placeholder' => 'Start Date', 'label' => 'Start Date', 'class' => 'date form-control', "data-date-format"=>"dd/mm/yyyy")); ?>
                                </div>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->input('end_date', array('type' => 'text', 'placeholder' => 'End Date', 'label' => 'End Date', 'class' => 'date form-control', "data-date-format"=>"dd/mm/yyyy")); ?>
                                </div>

                                <div class="col-sm-2">
                                    <?php echo $this->Form->label('&nbsp;'); ?>
                                    <div class="input text">
                                        <button type="submit" class='btn btn-info'>Search</button>
                                        <a class="btn btn-warning" href="<?php echo $this->Html->url(array('controller'=>'app_admin','action'=>'get_bed_occupancy')) ?>">Reset</a>

                                    </div>


                                </div>

                            </div>

                            <?php echo $this->Form->end(); ?>

                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                            <div class="table table-responsive">
                                <table id="example" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Date/Month</th>
                                        <th>Total Admission(Avg.)</th>
                                        <th>Total Available Bed</th>
                                        <th>Bed Occupancy Rate</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if($searchBY != 'HOSPITAL'){ ?>
                                        <?php  $n=1; foreach($dataToCalculate AS $date => $list){
                                            foreach($list AS $catID => $chunk){
                                                ?>
                                                <tr>
                                                    <td><?php echo $n; ?></td>
                                                    <td><?php echo $chunk['name']; ?></td>
                                                    <td><?php echo ($type == 'DAY')?date('d/m/Y',strtotime($date)):date('m/Y',strtotime($date)); ?></td>
                                                    <td><?php echo $chkTot = $chunk['total']; ?></td>
                                                    <td><?php  echo $catTot = $total[$catID]; ?></td>
                                                    <td><?php
                                                        $rate = ($chkTot/$catTot)*100;
                                                        echo round($rate,2);
                                                        ?></td>
                                                </tr>
                                                <?php
                                                $n++; }
                                        } ?>
                                    <?php }else{ ?>
                                        <?php
                                            $n=1;
                                            $dataToShowArr = array();
                                            foreach($dataToCalculate AS $date => $list){
                                                $chkTot = $catTot = 0;
                                                foreach($list AS $catID => $chunk){
                                                    $chkTot = $chkTot+$chunk['total'];
                                                    $catTot = $catTot+$total[$catID];
                                                    $dataToShowArr[$n] = array('date' => $date,'name' => 'Hospital','chkTot'=>$chkTot,'catTot'=>$catTot);
                                                }
                                                $n++;} ?>
                                        <?php $i = 1; foreach($dataToShowArr AS $list){ ?>
                                            <tr>
                                                <td><?php echo $i; ?></td>
                                                <td><?php echo $list['name']; ?></td>
                                                <td><?php echo ($type == 'DAY')?date('d/m/Y',strtotime($list['date'])):date('m/Y',strtotime($list['date'])); ?></td>
                                                <td><?php echo $list['chkTot']; ?></td>
                                                <td><?php  echo $list['catTot']; ?></td>
                                                <td><?php
                                                    $rate = ($list['chkTot']/$list['catTot'])*100;
                                                    echo round($rate,2);
                                                    ?></td>
                                            </tr>
                                        <?php $i++; } ?>
                                    <?php } ?>

                                    </tbody>
                                </table>
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
    $(".date").datepicker();
    $(document).ready(function () {


        $(document).on('change', '#p_type_id', function (e) {
            $('#p_type_name').val($('#p_type_id option:selected').text());
        });

        $('#example').DataTable({
            dom: 'Blfrtip',
            lengthMenu: [
                [10, 25, 50, 100, 150, 200, -1],
                ['10 rows', '25 rows', '50 rows', '100 rows', '150 rows', '200 rows', 'Show all']
            ],
            "language": {
                "emptyTable": "No Data Found Related To Search"
            },
            buttons: [
                {
                    extend: 'copy',
                    header: true,
                    footer: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    }

                },
                {
                    extend: 'csv',
                    header: true,
                    footer: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    }

                },
                {
                    extend: 'excel',
                    header: true,
                    footer: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    }

                },
                {
                    extend: 'pdf',
                    header: true,
                    footer: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    }

                },
                {
                    extend: 'print',
                    header: true,
                    footer: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    }

                }
            ]
        });

    });
</script>
