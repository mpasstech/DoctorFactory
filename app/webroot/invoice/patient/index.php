<?php
$constant_path = explode(DIRECTORY_SEPARATOR,__FILE__);
$tot =count($constant_path);
unset($constant_path[$tot-3]);
unset($constant_path[$tot-2]);
unset($constant_path[$tot-1]);
include_once implode("/",$constant_path)."/constant.php";
include_once implode("/",$constant_path)."/webservice/Custom.php";
$q_data = $_REQUEST;
$thin_app_id = @$q_data['t'];
$patient_id = @$q_data['pi'];

$patient_type = @$q_data['ty'];
$doctor_id = @$q_data['di'];
$folder_id = @$q_data['fi'];


if($patient_type == "CU"){
    $patient_type = "CUSTOMER";
}else if($patient_type == "CH"){
    $patient_type = "CHILDREN";
}

$patient_data = @Custom::load_patient_invoice_content($thin_app_id,$patient_id,$patient_type,$doctor_id);
$address_data = @Custom::get_doctor_address_list_drp($doctor_id,$thin_app_id);
$address_array=array();
?>
<html>

<head>
    <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />

    <title>Editable Invoice</title>

    <link rel='stylesheet' type='text/css' href='css/style.css' />
    <link rel='stylesheet' type='text/css' href='css/print.css' media="print" />
    <script type='text/javascript' src='js/jquery-1.3.2.min.js'></script>
    <script type='text/javascript' src='js/example.js'></script>
    <script type='text/javascript' src="<?php echo SITE_PATH.'js/html2canvas.min.js'; ?>"> </script>

    <style>



        td, td div, td span, .subtotal, .total{text-align:right;}
        #addrow{float:left;}
        body{
            padding:1px 12px;

        }

        *{
            letter-spacing: 0.1rem;
            font-size: 8px;
        }

        #page-wrap{
            height: 100% !important;
        }

        .border_class textarea, .border_class input{
            padding: 0px 2px;
            border: 1px solid rgb(224, 219, 219) !important;
        }
    </style>
</head>

<body id="capture">
<?php $i=0; ?>
<form action="" method="post" >

    <div id="page-wrap">

        <textarea name="header" id="header">INVOICE</textarea>

        <div id="identity">


            <div class="left_div">

                <select name="address" id="address" >
                    <?php if(!empty($address_data)){
                        foreach ($address_data as $key => $list){

                            $address_array[$list['id']] = $list['address'];

                        ?>
                            <option value="<?php echo $list['id']; ?>"><?php echo $list['address']; ?> </option>
                    <?php }}else{ ?>
                    <?php } ?>
                </select>





            </div>


            <div id="logo_div">
                <!--<img id="image" src="<?php /*echo @$patient_data['logo']; */?>" alt="logo" />-->
                <div class="total-line"><?php echo @$patient_data['name']; ?></div>

            </div>

        </div>

        <div style="clear:both"></div>
        <br>
        <div id="customer">
            <div  class="total-line pat_name">Patient Name</div>
            <textarea id="customer-title" name="patient_name"><?php echo @$patient_data['patient_name']; ?></textarea>

            <table id="meta">
                <tr>
                    <td class="meta-head">Invoice #</td>
                    <td><div name="invoice_number" id="invoice_number"><?php echo @$patient_data['invoice_number']; ?></div></td>
                </tr>
                <tr>

                    <td class="meta-head">Date</td>
                    <td><div name="date" id="date"></div></td>
                </tr>
                <tr>
                    <td class="meta-head">Amount Due</td>
                    <td><div class="due" id="amount_due" >0.00</div></td>
                </tr>

            </table>

        </div>

        <table id="items">

            <tr>
                <th>Item</th>
                <th>Description</th>
                <th>Unit Cost</th>
                <th>Quantity</th>
                <th>Price</th>
            </tr>

            <tr class="item-row border_class">
                <td class="item-name"><div class="delete-wpr"><textarea name="item[]" ><?php echo @$patient_data['service_name']; ?></textarea><a class="delete" style="display: none;" href="javascript:;" title="Remove row">X</a></div></td>
                <td class="description"><textarea name="description[]"></textarea></td>
                <td><input type="number" value="<?php echo @$patient_data['service_amount']; ?>" class="cost" name="unit_cost[]" ></td>
                <td><input type="number" class="qty" name="quantity[]" value="1" ></td>
                <td ><span class="price" name="price[]"></span></td>
            </tr>





            <tr id="hiderow">
                <td colspan="5"><a id="addrow" href="javascript:;" title="Add a row">Add new item</a></td>
            </tr>

            <tr>
                <td colspan="2" class="blank"> </td>
                <td colspan="2" class="total-line">Subtotal</td>
                <td  class="total-value"><div id="subtotal">0.00</div></td>
            </tr>
            <tr>

                <td colspan="2" class="blank"> </td>
                <td colspan="2" class="total-line">Total</td>
                <td  class="total-value"><div id="total">0.00</div></td>
            </tr>
            <tr>
                <td colspan="2" class="blank"> </td>
                <td colspan="2" class="total-line">Amount Paid</td>

                <td   class="total-value border_class"><input type="number" id="paid" name="paid" value="<?php echo @$patient_data['service_amount']; ?>" /></td>
            </tr>
            <tr>
                <td colspan="2" class="blank"> </td>
                <td colspan="2" class="total-line balance">Balance Due</td>
                <td  class="total-value balance"><div class="due" id="due">0.00</div></td>
            </tr>

        </table>

        <div id="terms">
            <h5>Terms</h5>
            <textarea name="terms" id="terms"><?php echo !empty($patient_data['t_and_c'])?$patient_data['t_and_c']:"Terms and Condition."; ?></textarea>
        </div>

    </div>

</form>

</body>


<script type="text/javascript">
    $(document).ready(function () {


        $(document).on('keyup','[name="terms"]',function(event){

            var count = $(this).val().split(/\r*\n/).length;
            $(this).height(count * 20+"px");

        });


        $("form").submit(function(e){
            e.preventDefault();
            var base64='';

            $(".item-row, .total-value").removeClass("border_class");
            $(".delete, #addrow").hide();

            bind();
            html2canvas(document.querySelector("#capture")).then(canvas => {

                var base64 = canvas.toDataURL('image/png');;
                console.log(base64);
            var address_id = $("#address").val();
            $.ajax({
                url: "<?php echo SITE_PATH;?>services/tab_add_patient_invoice",
                data:{
                    form:$(this).serialize(),
                    amount_due:$("#amount_due").val(),
                    total:$("#total").text(),
                    subtotal:$("#subtotal").text(),
                    due:$("#due").text(),
                    invoice_number:$("#invoice_number").text(),
                    date:$("#date").text(),
                    thin_app_id:"<?php echo $thin_app_id; ?>",
                    patient_id:"<?php echo $patient_id; ?>",
                    patient_type:"<?php echo $patient_type; ?>",
                    folder_id:"<?php echo $folder_id; ?>",
                    base64:base64,
                    doctor_id:"<?php echo $doctor_id; ?>",
                    address_id:address_id,
                },
                type:'POST',
                beforeSend:function(){
                },
                success: function(result){
                    result = JSON.parse(result);
                    var img_array = base64.split(",");
                    if(result.status==1){
                        result.base64 = img_array[1];
                    }
                    alert(JSON.stringify(result));

                },error:function () {
                    alert('Server Error');
                }
            });

        });
        });

        window.addEventListener("hashchange", function () {
            if(location.hash =="#save"){

                $("form").trigger('submit');
            }
        });

        $(".cost").trigger('blur');

        var address_id = "<?php echo $patient_data['invoice_address_id']; ?>";
        if(address_id > 0){
            $("#address").val('<?php echo $patient_data['invoice_address_id']; ?>');

        }

    });

</script>
</html>