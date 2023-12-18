



    <style>
        @import url(https://fonts.googleapis.com/css?family=Roboto:400,300,600,400italic);
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            -webkit-font-smoothing: antialiased;
            -moz-font-smoothing: antialiased;
            -o-font-smoothing: antialiased;
            font-smoothing: antialiased;
            text-rendering: optimizeLegibility;
        }

        body {
            font-family: "Roboto", Helvetica, Arial, sans-serif;
            font-weight: 100;
            font-size: 12px;
            line-height: 30px;
            color: #777;
            background: #4CAF50;
        }

        .container {
            max-width: 400px;
            width: 100%;
            margin: 0 auto;
            position: relative;
        }

        #contact input[type="text"],
        #contact input[type="email"],
        #contact input[type="tel"],
        #contact input[type="url"],
        #contact input[type="number"],
        #contact textarea,
        #contact button[type="submit"] {
            font: 400 12px/16px "Roboto", Helvetica, Arial, sans-serif;
        }

        #contact {
            background: #F9F9F9;
            padding: 25px;
            margin: 5px 0;
            box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
        }

        #contact h3 {
            display: block;
            font-size: 30px;
            font-weight: 300;
            margin-bottom: 10px;
        }

        #contact h4 {
            margin: 5px 0 15px;
            display: block;
            font-size: 13px;
            font-weight: 400;
        }

        fieldset {
            border: medium none !important;
            margin: 0 0 10px;
            min-width: 100%;
            padding: 0;
            width: 100%;
        }

        #contact input[type="text"],
        #contact input[type="email"],
        #contact input[type="tel"],
        #contact input[type="url"],
        #contact input[type="number"],
        #contact textarea {
            width: 100%;
            border: 1px solid #ccc;
            background: #FFF;
            margin: 0 0 5px;
            padding: 10px;
        }

        #contact input[type="text"]:hover,
        #contact input[type="email"]:hover,
        #contact input[type="tel"]:hover,
        #contact input[type="url"]:hover,
        #contact input[type="number"]:hover,
        #contact textarea:hover {
            -webkit-transition: border-color 0.3s ease-in-out;
            -moz-transition: border-color 0.3s ease-in-out;
            transition: border-color 0.3s ease-in-out;
            border: 1px solid #aaa;
        }

        #contact textarea {
            height: 100px;
            max-width: 100%;
            resize: none;
        }

        #contact button[type="submit"] {
            cursor: pointer;
            width: 100%;
            border: none;
            background: #4CAF50;
            color: #FFF;
            margin: 0 0 5px;
            padding: 10px;
            font-size: 15px;
        }

        #contact button[type="submit"]:hover {
            background: #43A047;
            -webkit-transition: background 0.3s ease-in-out;
            -moz-transition: background 0.3s ease-in-out;
            transition: background-color 0.3s ease-in-out;
        }

        #contact button[type="submit"]:active {
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.5);
        }

        .copyright {
            text-align: center;
        }

        #contact input:focus,
        #contact textarea:focus {
            outline: 0;
            border: 1px solid #aaa;
        }

        ::-webkit-input-placeholder {
            color: #888;
        }

        :-moz-placeholder {
            color: #888;
        }

        ::-moz-placeholder {
            color: #888;
        }

        :-ms-input-placeholder {
            color: #888;
        }
        .radio-inline input{
            margin-top: 8px !important;
        }
        .app_logo{
            width: 80px;
            height: 80px;
            float: right;
            border-radius: 50px;
            border: 1px solid rgb(111, 230, 111);
            margin-top: -20px;
        }
        label{
            margin-bottom: 0px !important;
        }
        input{
            margin: 0px !important;
        }
        .merge_field{
            float: left !important;

        }
        .mobile{
            width: 65%;
            min-width: 65% !important;;
            margin-right: 4px;
        }
        .discount{
            width: 33%;
            min-width: 33% !important;
        }
    </style>
    <div  class="login-page" style="display: block">

        <div class="container">
            <form id="contact" action="" method="post">
                <h3>
                    <?php echo $consent_data['app_name']; ?>
                    <img class="app_logo" src="<?php echo $consent_data['app_logo']; ?>">
                </h3>
                <h4>Form to Register Your Lab/Pharmacy</h4>
                <fieldset>
                    <label>Enter Your Name</label>
                    <input placeholder="" type="text" id="name" value="<?php echo $consent_data['name']; ?>" tabindex="1" required  oninvalid="this.setCustomValidity('Enter Your Name Here')"
                           oninput="setCustomValidity('')" >
                </fieldset>

                <fieldset class="merge_field mobile">
                    <label>Your Mobile Number</label>
                    <input placeholder="" id="mobile" value="<?php echo $consent_data['mobile']; ?>"  type="tel" tabindex="3" required oninvalid="this.setCustomValidity('Enter Your Mobile Number Here')"
                           oninput="setCustomValidity('')">
                </fieldset>

                <fieldset  class="merge_field discount">
                    <label>Discount (%)</label>
                    <input placeholder="" tabindex="1" id="discount" min="0" max="100" value="<?php echo $consent_data['discount']; ?>" type="number" required oninvalid="this.setCustomValidity('Enter Valid Discount Percentage')"
                           oninput="setCustomValidity('')">
                </fieldset>

                <fieldset>
                    <label>Enter Lab/Pharmacy Name</label>
                    <input placeholder="" type="text" id="lab_name" tabindex="1" value="<?php echo $consent_data['lab_name']; ?>" required oninvalid="this.setCustomValidity('Enter Your Lab/Pharmacy Name Here')"
                           oninput="setCustomValidity('')" >
                </fieldset>
                <fieldset>
                    <label>Select Your Address</label>
                    <input placeholder="" id="address" type="text" tabindex="1" value="<?php echo $consent_data['address']; ?>" required oninvalid="this.setCustomValidity('Select Your Address Here')"
                           oninput="setCustomValidity('')">
                    <input type="hidden" id="lat" value="<?php echo $consent_data['latitude']; ?>" >
                    <input type="hidden" id="lng" value="<?php echo $consent_data['longitude']; ?>" >
                </fieldset>

                <fieldset>
                    <label>Chose Your Role Type</label>
                    <label class="radio-inline"><input type="radio" name="role_type" value="LAB"  name="optradio">Lab</label>
                    <label class="radio-inline"><input type="radio" name="role_type" value="PHARMACY"  name="optradio">Pharmacy</label>
                </fieldset>

                <fieldset class="request_status">
                    <h4 style="color:<?php if($consent_data['request_status']=='APPROVED'){echo 'green';}else if($consent_data['request_status']=='REJECTED'){echo 'red';} ?>"><label>Request Status :</label> <?php if($consent_data['is_editable'] == 'YES'){echo ucfirst(strtolower($consent_data['request_status']));}else{ echo "Pending for Approve";} ?></h4>
                </fieldset>
                <?php if($consent_data['is_editable'] == 'YES'){ ?>
                <fieldset class="submit_fieldset">
                    <button type="submit" class="sub_btn" id="contact-submit">Submit</button>

                </fieldset>
                <?php } ?>


                <p class="copyright">Powered by <a href="http://www.mengage.in" target="_blank" title="Menage">MEngage</a></p>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            var checked = "<?php echo $consent_data['role_type']; ?>";
            $("input[name=role_type][value='"+checked+"']").prop("checked",true);


            $(document).on('submit','form',function (e) {
                e.preventDefault();

                var address = $('#address').val();
                var lat = $('#lat').val();
                var lng = $('#lng').val();
                var geocoder = new google.maps.Geocoder();
                geocoder.geocode( {"address": address}, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK && lat!='' && lng !='')
                    {
                        updateDetail();
                    }
                    else
                    {
                        swal(
                            'Location',
                            'Please select location from list',
                            'error'
                        );
                        $('#lat, #lng').val('');


                    }
                });



            });



            function updateDetail(){

                var role_type = $('input[name=role_type]:checked').val();
                var address = $('#address').val();
                var lab_name = $('#lab_name').val();
                var discount = $('#discount').val();
                var name = $('#name').val();
                var lat = $('#lat').val();
                var lng = $('#lng').val();
                var request_mobile = $('#mobile').val();
                var thisButton = $("#contact-submit");
                var thin_app_id = "<?php echo $consent_data['thinapp_id'];?>";
                var lab_user_id = "<?php echo $consent_data['id'];?>";
                var mobile = "<?php echo $consent_data['mobile'];?>";
                var doctor_id = "<?php echo $consent_data['doctor_id'];?>";
                var otp = "<?php echo $consent_data['verification_code'];?>";
                var param = {
                    thin_app_id:thin_app_id,
                    app_key:"MB",
                    mobile:mobile,
                    request_mobile:request_mobile,
                    user_id:0,
                    lab_user_id:lab_user_id,
                    role_type:role_type,
                    address:address,
                    lab_name:lab_name,
                    latitude:lat,
                    longitude:lng,
                    discount:discount,
                    name:name,
                    action_type:"UPDATE",
                    doctor_id:doctor_id
                };

                var submit_text = "Submit";
                $.ajax({
                    url: "<?php echo SITE_PATH;?>services/lab_update_user_detail",
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    data:JSON.stringify(param),
                    type:'POST',
                    beforeSend:function () {
                        $(thisButton).button('loading').text('Please wait..');
                    },
                    success: function(result){
                        result = JSON.parse(result);
                        if (result.status == 1) {
                            swal({
                                type: 'success',
                                title: result.message,
                                showCancelButton: false,
                                showConfirmButton: false
                            });


                            var inter = setInterval(function () {
                                $(".submit_fieldset").hide();
                                $(".request_status").show();
                                swal.close();
                                clearInterval(inter);
                            },1000);


                        } else {
                            $(thisButton).button('reset').text(submit_text);
                            swal(
                                'Oops...',
                                result.message,
                                'error'
                            );
                        }
                    },
                    error:function () {
                        $(thisButton).button('reset').text(submit_text);
                        swal(
                            'Oops...',
                            'Something went wrong. Please try again',
                            'error'
                        );
                    }
                });

            }



            $("#address").geocomplete().bind("geocode:result", function(event, result){
                    $("#lat").val(result.geometry.location.lat());
                    $("#lng").val(result.geometry.location.lng());
                });

        });
    </script>