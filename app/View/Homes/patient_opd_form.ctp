<html>
<head>




    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <meta name="mobile-web-app-capable" content="yes">

    <script src="<?php echo Router::url('/js/jquery.js',true)?>" ?>"></script>
    <link rel="stylesheet" href="<?php echo Router::url('/opd_form/css/index.css?'.date('Ymdhi'),true)?>" />
    <script src="<?php echo Router::url('/opd_form/js/imask.js',true)?>"></script>

</head>


<?php

$background = Router::url('/opd_form/css/backgound.png',true);
$background = "background: url('$background');";
 if(empty($data)){
     $background ='overflow:hidden;background:none;';
 }

 $single_field = 'YES';

?>

<body style="<?php echo $background; ?>">

<?php if(!empty($data)){ $single_field = $data['patient_single_field_form'];     ?>
<h3>
    <img src="<?php echo !empty($data['profile_photo'])?$data['profile_photo']:$data['logo']; ?>" alt="Doctor Image" />
    <?php echo $data['doctor_name']; ?>
</h3>

<?php if($single_field=='NO'){ ?>
        <div id="svg_wrap"></div>
<?php }else{ ?>
    <br>
<?php } ?>



<h1>OPD Form Filling</h1>
<div class="section_box">


        <?php

        $dob = '';
        $dob_val = $data['dob'];
        if(!empty($dob_val) && $dob_val !='0000-00-00'){
            $dob = date('d-m-Y',strtotime($dob_val));
        }
        $display_css='';
        $children_id =  $data['children_id'];
        if(!empty($children_id)){
            $display_css ='display:none;';
        }


        ?>

        <?php if($single_field=='NO'){ ?>
        <section>
        <p>Personal information</p>
        <label>Patient Name</label>
        <input type="text" maxlength="35" id="patient_name" value="<?php echo ($data['patient_name']!=$data['patient_mobile'])?$data['patient_name']:'';  ?>" />
        <label>Gender</label>
        <select id="gender" >
            <option>Select</option>
            <option <?php echo ($data['gender']=='MALE')?'selected':'' ?> value="MALE">Male</option>
            <option <?php echo ($data['gender']=='FEMALE')?'selected':'' ?> value="FEMALE">Female</option>
        </select>

    </section>
        <section>
            <p>Age Information</p>

            <label>Patient DOB</label>


            <input id="dob" value="<?php echo $dob; ?>" data-format="00-00-0000" type="text"  placeholder="DD-MM-YYYY">

            <label style="<?php echo $display_css; ?>" >Patient Age</label>
            <input style="<?php echo $display_css; ?>" id="age" value="<?php echo $data['age']?>" type="text" class="age" placeholder="Age" />

        </section>
        <section>
            <p>Address</p>
            <label>Address</label>
            <input  id="address" value="<?php echo $data['address']?>" type="text"  />
            <label>City</label>
            <input id="city_name" maxlength="15" value="<?php echo $data['city_name']?>" type="text" />


        </section>
        <section>

            <p>Contact information</p>

            <label>Patient Mobile</label>
            <input id="patient_mobile" data-format="0000000000" placeholder="9999999999" disabled="disabled" value="<?php echo substr($data['patient_mobile'], -10); ?>" type="number" />
            <label>Parents/Friend Mobile</label>
            <input id="parents_mobile" data-format="0000000000" placeholder="9999999999" value="<?php echo substr($data['parents_mobile'], -10); ?>" type="text" />
            <label>E-mail Address</label>
            <input id="email" maxlength="35" value="<?php echo $data['email']; ?>" type="email" />

        </section>
        <div class="button" id="prev">&larr; Previous</div>
        <div  class="button" id="next">Next &rarr;</div>
        <div class="button" id="submit">Submit</div>
     <?php } else{ ?>

                <section>
                <p>Personal information</p>
                <label>Patient Name</label>
                <input type="text" maxlength="35" id="patient_name" value="<?php echo ($data['patient_name']!=$data['patient_mobile'])?$data['patient_name']:'';  ?>" />
                <label style="display: none;">Gender</label>
                <select id="gender" style="display: none;" >
                    <option>Select</option>
                    <option <?php echo ($data['gender']=='MALE')?'selected':'' ?> value="MALE">Male</option>
                    <option <?php echo ($data['gender']=='FEMALE')?'selected':'' ?> value="FEMALE">Female</option>
                </select>

            </section>
                <input id="dob" value="<?php echo $dob; ?>" data-format="00-00-0000" type="hidden"  placeholder="DD-MM-YYYY">
                <input style="<?php echo $display_css; ?>" id="age" value="<?php echo $data['age']?>" type="hidden" class="age" placeholder="Age" />
                <input  id="address" value="<?php echo $data['address']?>" type="hidden"  />
                <input id="city_name" maxlength="15" value="<?php echo $data['city_name']?>" type="hidden" />
                <input id="patient_mobile" data-format="0000000000" placeholder="9999999999" disabled="disabled" value="<?php echo substr($data['patient_mobile'], -10); ?>" type="hidden" />
                <input id="parents_mobile" data-format="0000000000" placeholder="9999999999" value="<?php echo substr($data['parents_mobile'], -10); ?>" type="hidden" />
                <input id="email" maxlength="35" value="<?php echo $data['email']; ?>" type="hidden" />
                <div class="button" id="submit">Submit</div>

     <?php } ?>


</div>


<?php }else{ ?>
    <img class="time_expired" src="<?php echo Router::url('/opd_form/css/time_expired.png',true); ?>" />
    <h2 class="expire_message"> Your request has been expired. Please try agin next time when booking appoinment.</h2>
    <p>Thankyou</p>

<?php } ?>
</body>

<style>


    body{
        background-repeat: no-repeat !important;
        background-size: cover !important;
    }
    .expire_message{
        padding: 15px 6px;
        text-align: center;
        font-size: 1.2rem;
    }
    .war_message{
        color: red;
        font-size: 1rem;
    }
    .time_expired{
        width: 40%;
        margin: 10% auto;
        display: block;
    }
    .req_input{
        border-color: red !important;
    }
    .section_box{
        width: 100%;
        display: block;
        float: left;
    }
    #patient_mobile{
        background: #e8e8e8;
    }
    section{
        min-height: 35%;
    }
    .thankyou{
        margin: 20px auto;
        display: block;
        width: 100px;
        height: 100px;
    }
</style>
<script>
    $( document ).ready(function() {
        var single_field = "<?php echo $single_field; ?>";
        if(single_field=='NO'){
            var base_color = "rgb(230,230,230)";
            var active_color = "rgb(237, 40, 70)";
            var child = 1;
            var length = $("section").length - 1;
            $("#prev").addClass("disabled");
            $("#submit").addClass("disabled");

            $("section").not("section:nth-of-type(1)").hide();
            $("section").not("section:nth-of-type(1)").css('transform','translateX(100px)');

            var svgWidth = length * 200 + 24;
            $("#svg_wrap").html(
                '<svg version="1.1" id="svg_form_time" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 ' +
                svgWidth +
                ' 24" xml:space="preserve"></svg>'
            );

            function makeSVG(tag, attrs) {
                var el = document.createElementNS("http://www.w3.org/2000/svg", tag);
                for (var k in attrs) el.setAttribute(k, attrs[k]);
                return el;
            }

            for (i = 0; i < length; i++) {
                var positionX = 12 + i * 200;
                var rect = makeSVG("rect", { x: positionX, y: 9, width: 200, height: 6 });
                document.getElementById("svg_form_time").appendChild(rect);
                // <g><rect x="12" y="9" width="200" height="6"></rect></g>'
                var circle = makeSVG("circle", {
                    cx: positionX,
                    cy: 12,
                    r: 12,
                    width: positionX,
                    height: 6
                });
                document.getElementById("svg_form_time").appendChild(circle);
            }

            var circle = makeSVG("circle", {
                cx: positionX + 200,
                cy: 12,
                r: 12,
                width: positionX,
                height: 6
            });
            document.getElementById("svg_form_time").appendChild(circle);

            $('#svg_form_time rect').css('fill',base_color);
            $('#svg_form_time circle').css('fill',base_color);
            $("circle:nth-of-type(1)").css("fill", active_color);


            $(".button").click(function () {
                $("#svg_form_time rect").css("fill", active_color);
                $("#svg_form_time circle").css("fill", active_color);
                var id = $(this).attr("id");
                if (id == "next") {
                    var obj = $("#patient_name");
                    if($(obj).val()!=''){
                        $(obj).removeClass('req_input');
                        $("#prev").removeClass("disabled");
                        if (child >= length) {
                            $(this).addClass("disabled");
                            $('#submit').removeClass("disabled");
                        }
                        if (child <= length) {
                            child++;
                        }
                    }else{
                        $(obj).addClass('req_input');
                    }
                } else if (id == "prev") {
                    $("#next").removeClass("disabled");
                    $('#submit').addClass("disabled");
                    if (child <= 2) {
                        $(this).addClass("disabled");
                    }
                    if (child > 1) {
                        child--;
                    }
                }
                var circle_child = child + 1;
                $("#svg_form_time rect:nth-of-type(n + " + child + ")").css(
                    "fill",
                    base_color
                );
                $("#svg_form_time circle:nth-of-type(n + " + circle_child + ")").css(
                    "fill",
                    base_color
                );
                var currentSection = $("section:nth-of-type(" + child + ")");
                currentSection.fadeIn();
                currentSection.css('transform','translateX(0)');
                currentSection.prevAll('section').css('transform','translateX(-100px)');
                currentSection.nextAll('section').css('transform','translateX(100px)');
                $('section').not(currentSection).hide();
            });

            var inputElements = document.querySelectorAll("input[data-format]");
            inputElements.forEach(input => {
                let m = new IMask(input, {
                    mask: input.getAttribute("data-format")
                });
            });
            function diff_years(dt2, dt1)
            {

                var diff =(dt2.getTime() - dt1.getTime()) / 1000;
                diff /= (60 * 60 * 24);
                return Math.abs(Math.round(diff/365.25));

            }
            $(document).on("input","#dob",function(e){

                $("#age").val('');
                var parts =$("#dob").val().split('-');
                if(parts.length==3 && parts[2].length==4){
                    var dt1 = new Date(parts[2], parts[1] - 1, parts[1]);
                    var dt2 = new Date();
                    $("#age").val(diff_years(dt2, dt1));
                }

            });
        }


        $(document).on("click","#submit",function(e){


            var $btn =  $(this);
            var data = {
                    patient_name:$('#patient_name').val(),
                    gender:$('#gender').val(),
                    dob:$('#dob').val(),
                    age:$('#age').val(),
                    patient_mobile:$('#patient_mobile').val(),
                    address:$('#address').val(),
                    city_name:$('#city_name').val(),
                    parents_mobile:$('#parents_mobile').val(),
                    email:$('#email').val(),
                    ai:"<?php echo $appointment_id; ?>"
            };


            $.ajax({
                type:'POST',
                url: "<?php echo Router::url('/homes/update_opd_form',true); ?>",
                data:data,
                beforeSend:function(){
                    $($btn).prop('disabled',true).text('Saving..');
                },
                success:function(response){
                    $($btn).prop('disabled',false).text('Submit');
                    var response = JSON.parse(response);
                    console.log(response.status);
                    if(response.status == 1){
                        var imageUrl ="<?php echo Router::url('/opd_form/css/thankyou.png',true); ?>";
                        var html ="<section><h4><img class='thankyou' src="+imageUrl+" > "+response.message+"</h4></section>";
                        $(".section_box").html(html);
                    }else if(response.status == 2){
                            alert(response.message);
                            $("#patient_name").addClass('req_input');
                            var currentSection = $("section:nth-of-type(" + 1 + ")");
                            currentSection.fadeIn();
                            currentSection.css('transform','translateX(0)');
                            currentSection.prevAll('section').css('transform','translateX(-100px)');
                            currentSection.nextAll('section').css('transform','translateX(100px)');
                            $('section').not(currentSection).hide();
                            $("#prev").addClass("disabled");
                            if(single_field=='NO'){
                                $("#submit").addClass("disabled");
                            }
                            $("#next").removeClass("disabled");

                    }else{
                        alert(response.message);
                    }

                },
                error: function(data){
                    $($btn).prop('disabled',false).text('Submit');
                    $(".file_error").html("Sorry something went wrong on server.");
                }
            });

        });

    });
</script>
</html>


