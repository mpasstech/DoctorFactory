<?php
$date = date('d-m-Y');
$time = date('H:i');
$message ="Doctor Factory<br>-----------------------------<br>Token:15, Time:-$time<br>Date:-$date<br><br>------------------------<br>Happy journey";
?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="author" content="QuToT">
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />

    <script type='application/javascript'>
        function printDiv() {
            var divContents = document.getElementById("printDiv").innerHTML;
            var a = window.open('', '', 'height=auto, width=auto');
            a.document.write('<html>');
            a.document.write('<body style="">');
            a.document.write(divContents);
            a.document.write('</body></html>');
            a.document.close();
            a.print();
        }


    </script>

</head>
<body>
<div id="printDiv">
    <?php echo $message; ?>
</div>
<br>
<br>
<input type="button" value="Print" onclick="printDiv()">
</body>
</html>


