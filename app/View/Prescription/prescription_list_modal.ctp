<div class="modal fade" id="prescriptionListModal" tabindex="-1" role="dialog" >
    <div class="modal-dialog modal-md" >
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Prescription List</h4>
            </div>
            <div class="modal-body">

                <div class="row" style="overflow-y: auto;height: 500px; padding-right: 5px;">
                    <table class="table">
                        <?php if(!empty($data)){ foreach($data as $key => $list){
                            $type = ($list['is_medical_certificate']=='YES')?'Certificate':'Prescription';

                            ?>
                            <tr style="border-bottom: 1px solid #e5e5e5;">
                                <td style="padding: 5px 0px; border: none;">
                                    <img class="prescription_img vew_last_prescription" src="<?php echo $list['file_path']; ?>" />
                                    <label><?php echo   $type.' - '.$list['file_name']; ?></label>
                                    <p><i class="fa fa-calendar"></i> <?php echo date('d/m/Y h:i A',strtotime($list['created'])); ?></p>
                                    <span><?php echo "Size : ".ceil($list['file_size']*1024)." KB"; ?></span>
                                    <a style="float: right; margin: 0px 5px;" href="javascript:void(0)" src="<?php echo $list['file_path']; ?>" class="vew_last_prescription" ><i class="fa fa-eye"></i> View</a>
                                    <a id="<?php echo "html_".$list['file_id']; ?>" style="float: right; margin: 0px 5px;" href="javascript:void(0)" class="prescription_img_prescription"><i class="fa fa-print"></i> Print</a>
                                    <?php if(!empty($list['prescription_html'])){ ?>
                                            <script> $("<?php echo '#html_'.$list['file_id']; ?>").data('key','<?php echo base64_encode(json_encode($list['prescription_html'])); ?>'); </script>
                                    <?php } ?>


                                    <a style="float: right; margin: 0px 5px;" href="javascript:void(0)" data-dfi="<?php echo base64_encode($list['file_id']); ?>" class="delete_prescription_btn" ><i class="fa fa-trash"></i> Delete</a>


                                </td>
                            </tr>
                        <?php }}else{ ?>
                            <tr><td style="text-align: center;font-size: 20px;">No prescription found</td></tr>
                        <?php } ?>
                    </table>
                </div>
            </div>

        </div>
    </div>
    <style>
        .prescription_img{
            width: 100px;
            height: 90px;
            float: left;
            margin: 2px 6px;
            border: 1px solid #dbdbdb;
            padding: 0px;
            cursor: pointer;
        }
    </style>
    <script type="text/javascript">
        $(function () {
            $(document).off('click','.delete_prescription_btn');
            $(document).on('click','.delete_prescription_btn',function(){
                var $btn = $(this);
                var fi = $(this).attr('data-dfi');
                var tr = $(this).closest('tr');
                var jc = $.confirm({
                    title: "Delete Prescription",
                    content: 'Are you sure you want to delete this prescription?',
                    type: 'red',
                    buttons: {
                        ok: {
                            text: "Yes",
                            btnClass: 'btn-primary',
                            keys: ['enter'],
                            name:"ok",
                            action: function(e){
                                $.ajax({
                                    type: 'POST',
                                    url: "<?php echo Router::url('/prescription/delete_prescription',true);?>",
                                    data: {fi:fi},
                                    beforeSend: function () {
                                        $($btn).button({loadingText: '<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i> Delete'}).button('loading');

                                    },
                                    success: function (response) {
                                        $btn.button('reset');
                                        response = JSON.parse(response);
                                        if(response.status == 1){
                                            flash("Prescription",response.message, "success",'center');
                                            $(tr).remove();
                                            jc.close();
                                        }else{
                                            flash("Warning",response.message, "warning",'center');
                                        }

                                    },
                                    error: function (data) {
                                        $btn.button('reset');
                                        flash("Error",'Something went wrong on server.', "danger",'center');
                                    }
                                });
                                return false;
                            }
                        },
                        cancel: function(){

                        }
                    }
                });
            });

            $(document).off('click',".prescription_img_prescription");
            $(document).on('click',".prescription_img_prescription",function () {

                var data_i = $(this).attr('id');
                if($("#"+data_i).data('key')){
                    var html = JSON.parse(atob($("#"+data_i).data('key')));
                    var Pagelink = "about:blank";
                    var pwa = window.open(Pagelink, "_new");
                    pwa.document.open();
                    pwa.document.write(HtmltoPrint(html));
                    pwa.document.close();

                }else{
                      var src = $(this).closest("tr").find(".prescription_img").attr('src');
                     PrintImage(src);
                }
            });





            function HtmltoPrint(html) {
                return "<html><head><style> @media print {@page {size: A4;margin: 0;}</style><scri" + "pt> var background = background; function step1(){\n" +
                    "setTimeout('step2()', 10);}\n" +
                    "function step2(){ /*document.getElementById('printPrescriptionBox').style.background = 'none';*/  window.print();window.close();  }\n" +
                    "</scri" + "pt></head><body onload='step1()'>\n" + html
                    "</body></html>";
            }


            function ImagetoPrint(source) {
                return "<html><head><style> @media print {@page {size: A4;margin: 0;}</style><scri" + "pt>function step1(){\n" +
                    "setTimeout('step2()', 10);}\n" +
                    "function step2(){window.print();window.close()}\n" +
                    "</scri" + "pt></head><body onload='step1()'>\n" +
                    "<img src='" + source + "' /></body></html>";
            }

            function PrintImage(source) {
                var Pagelink = "about:blank";
                var pwa = window.open(Pagelink, "_new");
                pwa.document.open();
                pwa.document.write(ImagetoPrint(source));
                pwa.document.close();
            }


        });
    </script>

</div>
