<div class="modal fade" id="loadCertificateModal" tabindex="-1" role="dialog">
    <div id="modal_width_content" class="modal-dialog modal-md" style="padding: 0px;">

        <div class="modal-content" id="print_container" style="border: none;">
            <?php
            $font_size = "13px";
            $paper_size = "A4/4";
            if (!empty($_COOKIE["invoice_font_size"])) {
                $font_size = $_COOKIE["invoice_font_size"];
            }if (!empty($_COOKIE["print_size"])) {
                $paper_size = $_COOKIE["print_size"];
            }
            $login = $this->Session->read('Auth.User');
            ?>

            <div id="receipt_modal_body" class="modal-body" style="padding:0px;margin:0px;">
                <div class="inner_container" id="certificate_inner_container" style="padding: 0px 5px; width: 100%; float: left; background: #fff;">
                    <table id="main_table" style="width: 100%;">
                        <tr style="border-bottom: 1px solid;">
                            <td style="width: 40%; text-align: left;">
                                <label style="display:block; width: 100%;font-size: 20px;"><?php echo $data['name']; ?></label>
                                <span class="education" style="font-size: 13px;"><?php echo $data['sub_title']; ?></span>
                            </td>
                            <td style="width: 60%; text-align: right;">
                                <h2 id="clinic_name_title"></h2>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align: center;">
                                <h3 style="text-decoration: underline;">MEDICAL CERTIFICATE</h3>
                                <h3>TO WHOMSOEVER IT MAY CONCERN</h3>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="font-size: 18px;font-family: Serif;line-height: 20px;font-weight: 300;">
                                This to certify that <span id="pat_name_lbl"></span> has been under my treatment for <input style="width: 14%;border: none; border-bottom:1px solid; outline: none;" onkeypress="this.style.width = ((this.value.length + 1) * 8) + 'px';" type="text" id="disease" style="width: 14%;border: none; border-bottom:1px solid; outline: none;" class="fever_input edit_input"> from <input type="text" id="start_treatment" style="width: 14%;border: none; border-bottom:1px solid; outline: none;" class="admit_date_from edit_input"> to <input style="width: 14%;border: none; border-bottom:1px solid; outline: none;" id="end_treatment" type="text" class="admit_date_to edit_input">.
                                <br>
                                <br>
                                Doctor is advised complete rest from <input type="text" id="start_date" style="width: 14%;border: none; border-bottom:1px solid; outline: none;" class="admit_date_from edit_input"> to <input id="end_date" style="width: 14%;border: none; border-bottom:1px solid; outline: none;" type="text" class="admit_date_to edit_input"> which is a absolutely essential for the restoration of this health.
                                <br>
                                <br>
                            </td>
                        </tr>
                        <tr><td colspan="2">Place :<input  id="location" style="border: none; border-bottom:1px solid; outline: none; width: 14%;" type="text" class="edit_input"></td></tr>
                        <tr><td colspan="2">Date :<input style="width: 14%;border: none; border-bottom:1px solid; outline: none;" id="date" type="text" class="admit_date_to edit_input"></td></tr>
                        <tr><td colspan="2" style="text-align: right"><label><?php echo $data['name']; ?></label></td></tr>

                    </table>
                </div>

            </div>
        </div>
        <div class="footer_box">

            <div class="option">
                <?php $login = $this->Session->read('Auth.User.User'); $address_list = $this->AppAdmin->get_address_list($login['thinapp_id']); ?>
                <select id="address_drp">
                    <?php if(!empty($address_list)){ foreach($address_list as $key =>$address){ ?>
                        <option data-clinic="<?php echo $address['clinic_name']; ?>" value="<?php echo $address['id']; ?>"><?php echo $address['clinic_name'].' - '.$address['address']; ?></option>
                    <?php }} ?>
                </select>

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
                        <button class="btn btn-info" style="padding: 2px 14px;" id="print_btn"><i class="fa fa-print"></i> Print</button>
                        <button class="btn btn-success" style="padding: 2px 14px;" id="save_certificate"><i class="fa fa-save"></i> Save & Print</button>
                        <button class="btn btn-warning" style="padding: 2px 14px;" id="close_receipt_btn"><i class="fa fa-close"></i> Close</button>
                    </li>

                </div>



            </div>
        </div>
    </div>
    <style>
        .admit_date_from, .admit_date_to{
            width: 15%;

        }
        .edit_input, #pat_name_lbl{
            font-size: 17px;
            font-weight: 600;
            border: none;
            border-bottom: 1px solid;
            outline: none;

            padding: 0px 5px;
        }
        #pat_name_lbl{
            border-bottom: 0px solid;
        }

        .fever_input{

            min-width: 20%;
        }
        .option #address_drp{
            padding: 3px 2px;
            font-weight: 300;
            width: 97%;
            border: none;
            border-bottom: 1px solid;
            margin-bottom: 7px;
        }

        .footer_box{
            width: 100%;
            float: left;
            background: #fff;
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

    </style>




    <script>
        $(function () {

            var lastCss = $("style[data-id='style_tag']").html();

            $('.admit_date_from, .admit_date_to').datepicker({
                format: 'dd/M/yyyy',
                setDate: new Date(),
                autoclose:true
            }).on('changeDate', function(e) {

            });
            var patient_data = $("#patient_detail_object").data('key');
            patient_data = JSON.parse(patient_data);
            $("#loadCertificateModal #pat_name_lbl").html(patient_data.general_info.name) ;






            $("#font_size").val("<?php echo $font_size; ?>");
            $("#prescription_size").val("<?php echo $paper_size; ?>");

            function addPageCss(){
                checkCookie();
                var size = $("#prescription_size").val();
                var font_size = parseInt($("#font_size").val());
                var update_font  = font_size - 13;
                $( "#main_table" ).find("*").each(function(index,object){
                    var original_value = $(this).css('font-size');
                    if(!$(this).attr('data-size')){
                        $(this).attr('data-size',original_value);
                    }
                    if($(this).prev().prop('nodeName') != "TD" && $(this).prev().prop('nodeName') !="TR" ){
                        $(this).css('font-size',parseInt($(this).attr('data-size'))+update_font);
                    }
                });


            }


            function checkCookie() {
                var size = getCookie("print_size");
                var width = "210mm";
                var margin="15px";
                if(size=="A4/4"){
                    width = "450px";
                }
                $("#modal_width_content").width(width);

                $("style[data-id='style_tag']").remove();
                var style_2 = " <style data-id ='style_tag' media='print'>@media print {#print_container{width: "+width+";margin: 0 auto; margin: 0 auto;} @page {size: A4; margin: "+margin+"; -webkit-print-color-adjust: exact;color-adjust: exact;}}</style>";
                $("#printInvoiceModal").append(style_2);

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
                $("#loadCertificateModal").modal('hide');
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

            $(document).off("change","#loadCertificateModal #address_drp");
            $(document).on("change","#loadCertificateModal #address_drp",function(){
                $("#clinic_name_title").html($("#loadCertificateModal #address_drp").children("option:selected").attr('data-clinic'));
            });


            $(document).off('click',"#save_certificate");
            $(document).on('click',"#save_certificate",function(e){
                var $btn = $(this);
                $($btn).button({loadingText: '<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i> Saving..'}).button('loading');
               setTimeout(function () {
                   var patient_name = patient_data.general_info.name;
                   var patient_id = btoa(patient_data.general_info.patient_id);
                   var folder_id = btoa(patient_data.general_info.folder_id);
                   var patient_type = patient_data.general_info.patient_type;
                   var address_id = btoa($("#loadCertificateModal #address_drp").val());
                   var date  = $("#date").val();
                   var location  = $("#location").val();
                   var start_date  = $("#start_date").val();
                   var end_date  = $("#end_date").val();
                   var start_treatment  = $("#start_treatment").val();
                   var end_treatment  = $("#end_treatment").val();
                   var disease  = $("#disease").val();
                   var clinic_name  = $("#clinic_name_title").text();
                   html2canvas(document.querySelector("#certificate_inner_container"),{'useCORS':true}).then(function(canvas) {
                       var baseEncodeImage = canvas.toDataURL("image/png");
                       $('#receipt_modal_body').makeCssInline();
                       var certificate_html =  $("#receipt_modal_body").html();
                       $.ajax({
                           type:'POST',
                           url: baseUrl+"prescription/save_certificate",
                           data:{ch:certificate_html,base64:baseEncodeImage,pn:patient_name,pi:patient_id,fi:folder_id,pt:patient_type,ai:address_id,d:date,l:location,sd:start_date,ed:end_date,st:start_treatment,et:end_treatment,disease:disease,cn:clinic_name},
                           beforeSend:function(){
                           },
                           success:function(data){
                               data = JSON.parse(data);
                               $btn.button('reset');
                               if(data.status == 1){

                                   flash("Certificate Save",data.message, "success",'center');
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
                                   $("#loadCertificateModal").modal('hide');
                               }else{
                                   flash("Error",data.message, "danger",'center');
                               }
                           },
                           error: function(data){
                               $btn.button('reset');
                               alert("Sorry something went wrong on server.");
                           }
                       });
                   });
               },20);


            });

            $("#loadCertificateModal #address_drp").trigger("change");



            addPageCss();

        })
    </script>
</div>





