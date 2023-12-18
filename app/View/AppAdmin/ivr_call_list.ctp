<?php
$login = $this->Session->read('Auth.User');
?>
<?php  echo $this->Html->css(array('dataTableBundle.css')); ?>
<?php  echo $this->Html->script(array('dataTableBundle.js','jquery.maskedinput-1.2.2-co.min.js','comman.js')); ?>
<style>
    .button_container{
        float: left;
        width: 100%;
        display: flow-root;
        text-align: right;
    }
</style>

<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">

                <div class="middle-block">
                    <!-- Heading -->
                    <h3 class="screen_title">Ivr Call List</h3>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 custom_form_box">

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 hospital_table_box">


                            <div class="row">
                                <a style="position: absolute;float: right;margin: -2% 48%;" class="btn btn-success" href="<?php echo Router::url('/app_admin/ivr_call_list/'.base64_encode($ivr_number)); ?>">Reset Page</a>
                                <?php if(!empty($callData['Calls'])){ ?>
                                    <div class="table-responsive">


                                        <table id="example" class="table">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Patient Mobile</th>
                                                <th>Date</th>
                                                <th>Start Time</th>
                                                <th>End Time</th>
                                                <th>status</th>
                                                <th>Duration(Sec)</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach ($callData['Calls'] as $key => $list){  ?>
                                                <tr>
                                                    <td><?php echo $key+1; ?></td>
                                                    <td><?php echo $list['From']; ?></td>
                                                    <td><?php echo $list['DateCreated']; ?></td>
                                                    <td><?php echo $list['StartTime']; ?></td>
                                                    <td><?php echo $list['EndTime']; ?></td>
                                                    <td><?php echo ($list['Status']=='completed')?'Completed':'In Completed'; ?></td>
                                                    <td><?php echo $list['Duration']; ?></td>

                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                        </table>
                                        <?php if(!empty($callData['Metadata'])){ ?>
                                            <div class="button_container">

                                                <?php if($callData['Metadata']['Total'] >=50){ ?>
                                                    <a href="<?php echo Router::url('/app_admin/ivr_call_list/'.base64_encode($ivr_number).'/'.base64_encode($callData['Metadata']['FirstPageUri']),true); ?>" class="btn btn-info" >First</a>
                                                    <a href="<?php echo Router::url('/app_admin/ivr_call_list/'.base64_encode($ivr_number).'/'.base64_encode($callData['Metadata']['PrevPageUri']),true); ?>" class="btn btn-info" >Prev</a>
                                                    <a href="<?php echo Router::url('/app_admin/ivr_call_list/'.base64_encode($ivr_number).'/'.base64_encode($callData['Metadata']['NextPageUri']),true); ?>" class="btn btn-info" >Next</a>
                                                <?php } ?>

                                            </div>

                                    <?php } ?>
                                    </div>
                                <?php }else{ ?>

                            </div>
                            <div class="no_data">
                                <h2>No Call Found</h2>
                            </div>
                            <?php } ?>
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

<style>
    .status_lbl{
        margin: 0px 5px;
    }
    #status{
        padding: 3px;
    }
</style>


<script>
    $(document).ready(function(){
        var table = $('#example').DataTable({
            dom: 'Blfrtip',
            "bPaginate": false,
            lengthMenu: [
                [-1 ],
                ['Show all' ]
            ],
            "language": {
                "emptyTable": "No Data Found Related To Search"
            },
            buttons: [
                {
                    extend: 'copy',
                    header: true,
                    title: '',
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6]
                    }

                },
                {
                    extend: 'csv',
                    header: true,

                    title: '',
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6]
                    }

                },
                {
                    extend: 'excel',
                    header: true,

                    title: '',
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6]
                    }

                },
                {
                    extend: 'pdf',
                    header: true,

                    title: '',
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6]
                    }

                },
                {
                    extend: 'print',
                    header: true,

                    title: '',
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6]
                    }

                }
            ]
        });


        var html ='<label class="status_lbl">Status <select id="status">' +
            '<option value="ALL">All</option>' +
            '<option value="Completed">Completed</option>' +
            '<option value="In completed">In Completed</option>' +
            '</select></label>';
        $("#example_filter").append(html);

        // Event listener to the two range filtering inputs to redraw on input
        $(document).on("change","#status",function() {
            table.draw();
        } );

        $.fn.dataTable.ext.search.push(
            function( settings, data, dataIndex ) {
                var status =$('#status').val();
                if(status=='ALL'){
                    return  true;
                }else if (status == data[5]){
                    return true;
                }
                return false;
            }
        );


    });



</script>





