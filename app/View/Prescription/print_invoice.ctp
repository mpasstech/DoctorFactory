<div class="modal fade" id="printInvoiceModal" tabindex="-1" role="dialog">

    <div  class="modal-dialog modal-md" style="width: 60%; padding: 0px;">


        <div class="modal-content" >
            <div id="all_container">
                <div id="print_container" style="border: none;">
                    <?php
                    $font_size = "13px";
                    $paper_size = "A4/4";
                    if (!empty($_COOKIE["invoice_font_size"])) {
                        $font_size = $_COOKIE["invoice_font_size"];
                    }if (!empty($_COOKIE["print_size"])) {
                        $paper_size = $_COOKIE["print_size"];
                    }
                    $login = $this->Session->read('Auth.User');
                    $data = @$invoiceData[0];
                    if (isset($data['billing_date'])) {
                        $receiptID = $this->AppAdmin->get_receipt_id_by_order_id($data['order_id']);
                    } else {
                        $receiptID = "-";
                    }

                    ?>
                    <div id="receipt_modal_body" class="modal-body" style="padding:0px;margin:0px;">
                        <div class="inner_container" style="padding: 0px 5px; width: 100%; float: left; background: #fff;">
                            <table id="main_table" style="width: 100%;">
                                <tr>
                                    <td>
                                        <label style="font-weight: 600; display:block; width: 100%; text-align: center;font-size: 18px; color:#105D96;">
                                            <?php
                                            $clinic_name = !empty($data['clinic_name'])?$data['clinic_name']:$data['app_name'];
                                            echo $data['doctor_name']." (".$clinic_name.")";
                                            ?>
                                        </label>
                                    </td>
                                </tr>
                                <tr >
                                    <td>
                                        <label style="display:block; width: 100%; font-size: 15px; text-align: center;">
                                            <?php echo $data['address']; ?>
                                        </label>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="text-align: center; padding: 4px 2px !important;">
                                        <label class="title_box" style="border: 1px solid; -webkit-print-color-adjust: exact;font-size: 15px; background-color: #0267FF;color:#fff;padding: 3px 30px; font-weight: 500;">Payment Receipt</label>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <table style="width: 100%;">
                                            <tr>
                                                <td style="text-align: left;">
                                                    <label style="width:auto;"> Receipt No : <?php echo $receiptID; ?></label>
                                                </td>

                                                <td style="float: right;">
                                                    <label style="width:auto; text-align: right;"> Date : <?php echo date('D, d M Y',strtotime($data['billing_date'])); ?></label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: left;">
                                                    <label style="width:auto;">Name : <?php echo $data['patient_name']; ?></label>
                                                </td>
                                                <td style="float: right;">
                                                    <label style="width:auto; text-align: right;"> UHID : <?php echo $data['uhid']; ?></label>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <table id="item_table" style="width: 100%;border:1px solid;">
                                            <tr style="border-bottom:1px solid;">
                                                <th style="width: 2%; text-align: left;">
                                                    <label style="margin: 0px; font-weight: 600">S.N</label>
                                                </th>

                                                <th style="width: 88%; text-align: left;">
                                                    <label style="margin: 0px; font-weight: 600">Particulars</label>
                                                </th>
                                                <th style="width: 10%;">
                                                    <label style="margin: 0px; font-weight: 600">Charges</label>
                                                </th>
                                            </tr>

                                            <?php $total =0; $due_paid_amount = 0; $counter = 1; foreach($invoiceData as $key => $value){ $due_paid_amount = $value['due_paid_amount']; ?>
                                                <tr>
                                                    <td><label style="font-weight: 500;"><?php echo $counter++; ?></label></td>
                                                    <td><label style="font-weight: 500;"><?php echo $value['service_name']; ?></label></td>
                                                    <td ><label style="font-weight: 500;"><?php echo $value['service_paid_amount']; $total +=$value['service_paid_amount']; ?> </label></td>
                                                </tr>
                                            <?php } if(!empty($due_paid_amount)){ ?>

                                            <tr>
                                                <td><label style="font-weight: 500;"><?php echo $counter; ?></label></td>
                                                <td><label style="font-weight: 500;">Due Amount </label></td>
                                                <td><label style="font-weight: 500;"><?php echo sprintf("%.2f",$due_paid_amount); $total +=$due_paid_amount; ?></label> </td>
                                            </tr>
                                            <?php } ?>

                                            <tr style="border-top: 1px solid #dcdcdc">
                                                <td></td>
                                                <td  style="padding:0px 10px !important;"><label style="font-weight: 500;">Total </label></td>
                                                <td  style="padding:0px 10px !important;"><label style="font-weight: 500;"><?php echo sprintf("%.2f",$total); ?></label> </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>


                                <tr>
                                    <td>
                                        <table style="width: 100%;">
                                            <tr>
                                                <td>
                                                    <label style=" font-weight: 500;">Paid Balance</label>
                                                    <label style=" font-weight: 500;"><?php echo $data['total_paid']; ?>/-</label>
                                                </td>
                                                <td style="text-align: center;">
                                                    <?php if(!empty($data['total_due_amount'])){ ?>
                                                        <label style=" font-weight: 500;">Due Amount</label>
                                                        <label style=" font-weight: 500;"><?php echo $data['total_due_amount']; ?>/-</label><br>
                                                    <?php } ?>
                                                </td>
                                                <td style="text-align: right;">
                                                    <label style=" font-weight: 500;">Payment Mode</label>
                                                    <label style=" font-weight: 500;">CASH</label><br>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="3">
                                                    <label style="display: block; width: 100%;font-size: 9px; font-weight: 500;">Treatment given on <?php echo date('D, d M Y',strtotime($data['billing_date'])); ?></label>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td  colspan="3" style="text-align: right;">
                                                    <label style="text-align: right;">Authorized Sign</label>
                                                </td>
                                            </tr>


                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>

                    </div>
                </div>
                <div class="footer_box">
                    <div class="option">

                        <div class="option_div">

                            <li>
                                <b>Font Size</b><br>
                                <select id="font_size">
                                    <option value="8px">8</option>
                                    <option value="9px">9</option>
                                    <option value="10px">10</option>
                                    <option value="11px">11</option>
                                    <option value="12px">12</option>
                                    <option value="13px">13</option>
                                    <option value="14px">14</option>
                                    <option value="15px">15</option>
                                    <option value="16px">16</option>
                                    <option value="17px">17</option>
                                    <option value="18px">18</option>
                                    <option value="19px">19</option>
                                    <option value="20px">20</option>
                                    <option value="21px">21</option>
                                    <option value="22px">22</option>
                                    <option value="23px">23</option>
                                    <option value="24px">24</option>
                                    <option value="25px">25</option>

                                </select>
                            </li>
                            <li>
                                <b>Paper Size</b><br>
                                <select id="prescription_size">
                                    <option value="A4">A4</option>
                                    <option value="A4/4">A4/4</option>
                                </select>
                            </li>
                            <li class="button_li">
                                <button class="btn btn-success" style="padding: 2px 14px;" id="print_btn"><i class="fa fa-print"></i> Print</button>
                                <button class="btn btn-warning" style="padding: 2px 14px;" id="close_receipt_btn"><i class="fa fa-close"></i> Close</button>
                            </li>

                        </div>



                    </div>
                </div>
            </div>

        </div>

    </div>
    <style>


        #all_container td,#all_container td{
                padding: 2px 2px;

        }
        .inner_container{
            min-height: 460px;
        }
        #item_table td, #item_table th{
            padding: 0px 10px !important;
        }
        #all_container{
            width: 100%;
            margin: 0 auto;
            float: left;
            padding: 9px;
            background: #fff;
            border-radius: 30px;
            border: 2px solid #0267FF;
        }
        #printInvoiceModal{
            font-family: Montserrat !important;

        }

        .footer_box{
            width: 100%;
            float: left;
            background: #afacac;
            border-radius: 0px 0px 25px 25px;
        }
        .option{
            width: 100%;
            float: left;
            text-align: center;
            display: block;
            padding: 10px 0px;
            position: relative;
            border-top: 1px dashed #aca9a9db;
            background-color: #fcfcfcdb;
        }
        .option_div li{
            list-style: none;
            float: left;
            width: 33%;
        }
        .option_div{
            width: 100%;
            float: left;
            font-size:14px !important;
        }

        .button_li button{
            padding: 2px 16px;
            margin: 2px 0px;
        }

        .option_div select{
            font-size:20px !important;
        }

        #main_table{
            margin: 0 auto;
        }



    </style>




    <script>
        $(function () {

            var lastCss = $("style[data-id='style_tag']").html();

            $("#font_size").val("<?php echo $font_size; ?>");
            $("#prescription_size").val("<?php echo $paper_size; ?>");

            function addPageCss(){
                checkCookie();
                var size = $("#prescription_size").val();
                var font_size = parseInt($("#font_size").val());
                var update_font  = font_size - 13;
                console.log(update_font);

                $( "#main_table" ).find("td, label, span").each(function(index,object){
                    var original_value = $(this).css('font-size');
                    if(!$(this).attr('data-size')){
                        $(this).attr('data-size',original_value);
                    }
                    console.log($(this).prop('nodeName'));
                    if($(this).prop('nodeName') != "TH" && $(this).prop('nodeName') != "TBODY" && $(this).prop('nodeName') != "TABLE" && $(this).prop('nodeName') != "TD" && $(this).prop('nodeName') !="TR" ){
                        $(this).css('font-size',parseInt($(this).attr('data-size'))+update_font);
                       // $(this).css('font-size',font_size);
                    }
                });


                var margin = "15px";
                var width = "210mm;";
                $("#main_table").width("680px");
                if(size=="A4/4"){
                    width = "450px";
                    $("#main_table").width(width);
                }

                $("style[data-id='style_tag']").remove();

                var style_2 = " <style data-id ='style_tag' media='print'>@media print {.title_box{-webkit-print-color-adjust: exact; !important;} #main_table{width: "+width+";margin: 0 auto;} @page {size: A4; margin: "+margin+"; -webkit-print-color-adjust: exact;color-adjust: exact;}}</style>";
                $("#printInvoiceModal").append(style_2);
            }


            function checkCookie() {
                var user = getCookie("print_size");
                if (user != "") {
                    $("#prescription_size").val(user);
                    if (user == 'A4') {
                        $("#modal_width_content").css('width', '650px');
                    }
                    else {
                        $("#modal_width_content").css('width', '450px');
                    }
                } else {
                    $("#prescription_size").val('A4');
                    if (user != "" && user != null) {
                        setCookie("print_size", 'A4', 365);
                    }
                    $("#modal_width_content").css('width', '650px');
                }
            }

            function setCookie(cname, cvalue, exdays) {
                var d = new Date();
                d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
                var expires = "expires="+d.toUTCString();
                document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
            }



            function getCookie(cname) {
                var name = cname + "=";
                var ca = document.cookie.split(';');
                for(var i = 0; i < ca.length; i++) {
                    var c = ca[i];
                    while (c.charAt(0) == ' ') {
                        c = c.substring(1);
                    }
                    if (c.indexOf(name) == 0) {
                        return c.substring(name.length, c.length);
                    }
                }
                return "";
            }




            $(document).off('click',"#print_btn");
            $(document).on('click',"#print_btn",function(e){
                e.preventDefault();
                e.stopPropagation();
                $('#print_container').printThis({              // show the iframe for debugging
                    importCSS: true,            // import page CSS
                    importStyle: true,         // import style tags
                    pageTitle: "",              // add title to print page
                    removeInline: false,        // remove all inline styles from print elements
                    printDelay: 333,            // variable print delay; depending on complexity a higher value may be necessary
                    header: null,               // prefix to html
                    footer: null,               // postfix to html
                    base: false ,               // preserve the BASE tag, or accept a string for the URL
                    formValues: true,           // preserve input/form values
                    canvas: false,              // copy canvas elements (experimental)
                    doctypeString: ".",       // enter a different doctype for older markup
                    removeScripts: false,       // remove script tags from print content
                    copyTagClasses: false       // copy classes from the html & body tag
                });
            });

            $(document).off('click',"#close_receipt_btn");
            $(document).on('click',"#close_receipt_btn",function(e){
               $("#printInvoiceModal").modal('hide');
            });

            $(document).off("change","#prescription_size");
            $(document).on("change","#prescription_size",function(){
                var value = $(this).val();
                setCookie("print_size", value, 365);
                addPageCss();
            });

            $(document).off("change","#font_size");
            $(document).on("change","#font_size",function(){
                var value = $(this).val();
                setCookie("invoice_font_size", value, 365);
                addPageCss();
            });

            addPageCss();

        })
    </script>
</div>





