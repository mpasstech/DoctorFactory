<!DOCTYPE html>
<html>
<head>


 <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="#7f0b00">


    <meta http-equiv="cache-control" content="no-store"/>
    <meta http-equiv="expires" content="0"/>
    <meta http-equiv="pragma" content="no-cache"/>
    <script src="../js/jquery.js" ?>"></script>
    <link rel="stylesheet" href="css/index.css" />

    <script src="js/imask.js"></script>
    <script>
        $( document ).ready(function() {
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
    $("#prev").removeClass("disabled");
    if (child >= length) {
      $(this).addClass("disabled");
      $('#submit').removeClass("disabled");
    }
    if (child <= length) {
      child++;
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




});
    </script>
</head>


<body>
<h3>
    <img src="css/doctor.png">
    Mahendra Saini
</h3>
<div id="svg_wrap"></div>
<h1>OPD Form Filling</h1>
<section>
  <p>Personal information</p>
  <label>Patient Name</label>
  <input type="text" class="patient_name" />

    <label>Gender</label>
  <select class="gender" >

    <option>Select</option>
     <option value="MALE">Male</option>
     <option value="FEMALE">Female</option>
  </select>
  
</section>

<section>
  <p>Age Infromation</p>
    <label>Patient Age</label>
  <input type="text" class="age" placeholder="Age" />
    <label>Patient DOB</label>
    <input data-format="00-00-0000" type="text"  placeholder="DD-MM-YYYY">
</section>



<section>
  <p>Address</p>
    <label>Address</label>
    <input type="text"  />
    <label>City</label>
    <input type="text" />


</section>

<section>
  <p>Contact information</p>

    <label>Patient Mobile</label>
    <input type="text" />

    <label>Alt Mobile</label>
    <input type="text" />

    <label>E-mail Address</label>
    <input type="text" />




</section>




  <div class="button" id="prev">&larr; Previous</div>
<div class="button" id="next">Next &rarr;</div>
<div class="button" id="submit">Submit</div>


</body>
</html>


