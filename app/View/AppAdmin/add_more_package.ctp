<?php echo $this->Html->script(array('magicsuggest-min.js')); ?>
<?php echo $this->Html->css(array('magicsuggest-min.css')); ?>
<label class="min_head">New Package</label>
<div class="form-group">

    <div class="item_div row">
        <div class="input number col-md-2 package_input">
            <label for="opdCharge">Package</label>
            <input type="text" name="packageID" required="true" autocomplete="off" class="form-control magicsuggest" placeholder="Package" required="true">
        </div>
        <div class="input number col-md-2">
            <label for="opdCharge">Discount</label>
            <input name="package_discount" class="form-control" min="0" value="0" placeholder="Discount" type="number">
        </div>
        <div class="input number col-md-2">
            <label for="opdCharge">Discount Type</label>
            <select name="package_discount_type" required="true" class="form-control">
                <option value="PERCENTAGE">Percentage</option>
                <option value="AMOUNT">Amount</option>
            </select>
        </div>
        <div class="input number col-md-2">
            <label for="opdCharge">Price</label>
            <input type="text" readonly="readonly" required="true" name="package_price" class="form-control" value="0" placeholder="Price">
        </div>
        <div class="input number col-md-2">
            <label for="opdCharge">Amount</label>
            <input type="text" readonly="readonly" required="true" name="package_amount" class="form-control" value="0" placeholder="Amount">
            <input type="hidden" name="add_package" value="1">
        </div>
        <div class="input number col-md-2">
            <label for="opdCharge">&nbsp;</label>
            <button type="submit" class="btn btn-md btn-info form-control add_more_package_button">Add</button>
        </div>

    </div>
</div>




<script>
    $(function() {
        var ms = $('.magicsuggest').magicSuggest({
            allowFreeEntries:false,
            allowDuplicates:false,
            data:<?php echo json_encode($tag,true); ?>,
            maxDropHeight: 345,
            maxSelection: 1
        });
        $(ms).on('selectionchange', function(e,m){
            $('[name="package_price"]').val('');
            $('[name="package_amount"]').val('');
            $('[name="package_discount"]').val(0);
            $('[name="package_discount_type"]').val('PERCENTAGE');
            $('[name="package_price"]').attr('readonly','readonly');

            var IdArr = this.getSelection();
            if(IdArr[0])
            {
                $('[name="package_price"]').val(IdArr[0].price);
                $('[name="package_amount"]').val(IdArr[0].price);
                if(IdArr[0].is_price_editable)
                {
                    $('[name="package_price"]').removeAttr('readonly');
                    $('[name="package_price"]').attr('type','number');
                }
                else
                {
                    $('[name="package_price"]').attr('readonly','readonly');
                    $('[name="package_price"]').attr('type','text');
                }
                console.log(IdArr);
            }
        });

        $('[name="package_discount"],[name="package_discount_type"]').on('input',function(){
            var price = $('[name="package_price"]').val();
            var package_discount = $('[name="package_discount"]').val();
            var package_discount_type = $('[name="package_discount_type"]').val();

            if((package_discount_type == 'PERCENTAGE') && (package_discount > 100))
            {
                $(ms).trigger('selectionchange');
                alert('Discount could not be greater then 100%!');
            }
            else if((parseFloat(price,2) < parseFloat(package_discount,2)) && (package_discount_type == 'AMOUNT'))
            {
                $(ms).trigger('selectionchange');
                alert('Discount could not be greater then product price!');
            }
            else
            {

                if(!(package_discount > 0))
                {
                    package_discount = 0;
                    //$('[name="package_discount"]').val(0);
                }

                if(package_discount_type == 'AMOUNT')
                {
                    var totalPrice = parseFloat(price,2) - parseFloat(package_discount,2);
                    $('[name="package_amount"]').val(totalPrice);
                }
                else
                {
                    var totalPrice = parseFloat(price,2) - ((parseFloat(package_discount,2)/100)*parseFloat(price,2));
                    $('[name="package_amount"]').val(totalPrice);
                }
            }

        });

    });
</script>