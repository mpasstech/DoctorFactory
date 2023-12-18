<html>
<head>
    <title>Appointment Tracker</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <?php echo $this->Html->script(array('jquery.js')); ?>
    <?php error_reporting(0); echo $this->Html->css(array('bootstrap.min.css',),array("media"=>'all','fullBase' => true)); ?>

    <style>

        p{
            font-size: 20px;
            text-align: center;
        }

        h1, h2{
            text-align: center;
        }

        h3{
            font-size: 26px;
        }


        ol li label{
            font-size: 15px;
        }
        ol li{
            list-style: none;
        }
        .number_td{
            width: 100%;
            background: #2a6ee4;
            color: #fff;
            text-align: center;
            padding: 2px 6px;
            border-radius: 20px;
            font-size: 15px;
        }
        .question{
            padding: 2px 4px;
        }
        .top_header{
            background: #0302e4;
            padding: 8px 60px 8px 0px;
            margin: 0;
            text-align: right;
            color: #fff;
        }
        .corona{
            height: 75px;
            width: 75px;
            margin: 0px 38%;
            padding-top: 5px;
            position: relative;

        }

        #loader {
            border: 5px solid #f3f3f3;
            border-radius: 50%;
            border-top: 5px solid green;
            width: 70px;
            height: 70px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            100% {
                transform: rotate(360deg);
            }
        }

        .center {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            margin: auto;
            z-index:9999999;
        }


        .ans_ul{
            list-style: none;
        }
        .ans_ul li:before {
            content: '✓';
            margin: 0px 5px;
            position: absolute;
            left: 50px;
        }


        .center {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            margin: auto;
            z-index:9999999;
        }

        .p_subtitle{
            text-align: left;
            font-size: 18px;
        }

        .label_title{
            font-size: 20px;
        }

        .p_body{

            text-align: left;
            font-size: 17px;
            font-weight: 500;

        }

        .report_lbl{
            text-align: center;
            font-size: 18px;
            text-transform: uppercase;
            font-weight: 600;
            color: #fff;
            margin: 5px auto;
            border: 1px solid;
            /* width: 5px; */
            display: block;
            width: 76%;
            padding: 7px 15px;
            border-radius: 41px;
            background: blue;



        }

        .content_ol {
            list-style: none;
            counter-reset: item;
        }
        .content_ol li {
            counter-increment: item;
            margin-bottom: 25px;
        }

        .content_ol li:before {
            left: 15px;
            content: counter(item);
            background: green;
            border-radius: 100%;
            color: white;
            /* width: 1.2em; */
            text-align: center;
            display: inline-block;
            position: absolute;
            float: left;
            padding: 5px 10px;
            font-size: 15px;
        }
        .button_container{
            width: 100%;
            display: block;
            margin: 0 auto;
            position: fixed;
            z-index: 999;
            background: #fff;
        }


        .top_margin_class{
            margin-top: 16%;
            height: 99%;
            overflow: scroll;
        }
        #postYourAdd{
            margin: 6px auto;
            width: 67%;
            text-align: center;
            align-self: center;
            display: block;
            background: #3c9c09;
            color: #fff;
            border: none;
            padding: 5px 15px;
            font-size: 16px;
            border-radius: 28px;
            outline: none;
            font-weight: 500;
            text-transform:uppercase;


        }
    </style>
    <script>
        document.onreadystatechange = function() {
            if (document.readyState !== "complete") {
                document.querySelector("body").style.visibility = "hidden";
                document.querySelector("#loader").style.visibility = "visible";
            } else {
                document.querySelector("#loader").style.display = "none";
                document.querySelector("body").style.visibility = "visible";
            }
        };


        $(document).on('click','#postYourAdd',function () {

            if($(this).attr('data-action')=='IFRAME'){
                $("#iframe").show();
                $("#iframe").attr("src", $("#iframe").attr('data-url') );

                $(this).attr("data-action", "ANSWER_BOX");
                $("#ans_box").hide();
                $(this).text("Show Patient Answer");
            }else{
                $("#ans_box").show();
                $("#iframe").hide();
                $(this).attr("data-action", "IFRAME");
                $(this).text("Show Consent Status");

            }
        });


    </script>
</head>
<body>
<div id="loader" class="center"></div>


<?php $class = 0; if(!empty($consent_link)){ $class = "top_margin_class"; ?>
    <p class="button_container"><button id="postYourAdd"  data-action="IFRAME" >Show Consent Status</button></p>
    <iframe id="iframe" class="<?php echo $class; ?>" scrolling="yes"  data-url="<?php echo $consent_link; ?>"  width="100%" height="99%" style=" background:#ffffff;height: 99%;width: 100%; display: none; border: none;"></iframe>

<?php } ?>
<div id="ans_box" class="<?php echo $class; ?>" style="float: left;width: 100%;display: block;"  >
    <img class="corona" src="data:image/png;base64,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">
    <div class="container-fluid">

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 5px 10px 10px 10px;">
            <?php if(!empty($content)){ ?>
                <table style="width: 100%;">
                    <?php foreach ($content as $key => $data){ if(isset($data['question'])){ if(count($data['ans']) > 0){  ?>
                        <tr>
                            <td  style="text-align: left; width: 5%;" valign="top"><label class="number_td"><?php echo $key+1; ?></label></td>
                            <td style="text-align: left; width: 95%;" valign="top">
                                <label class="question"><?php echo $data['question']; ?></label>
                                <?php if(!empty($data['sub_title'])){
                                    echo $data['sub_title'];
                                } ?>
                                <?php if(!empty($data['option'])){ ?>
                                    <label style="display: block;">Option:</label>
                                    <ul>
                                        <?php foreach ($data['option'] as $option){  ?>
                                            <li><?php echo $option; ?></li>
                                        <?php } ?>
                                    </ul>
                                <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <label>Answer:</label>
                                <ul class="ans_ul">
                                    <?php foreach ($data['ans'] as $answer){  ?>
                                        <li style="color: green;font-weight: 600;"><?php echo $answer; ?></li>
                                    <?php } ?>
                                </ul></td>
                        </tr>
                    <?php }}else if(isset($data['result'])){ ?>
                        <tr style="border-top:1px solid green;"><td colspan="2">
                                <span class="report_lbl">Patient Suggestion</span>

                                <img  style="margin: 0 auto;display: block;" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAJsAAAC2CAYAAADOU/9kAAAABGdBTUEAALGPC/xhBQAAADhlWElmTU0AKgAAAAgAAYdpAAQAAAABAAAAGgAAAAAAAqACAAQAAAABAAAAm6ADAAQAAAABAAAAtgAAAADn/OYMAAA7B0lEQVR4Ae19B3hcx3Xu2b6L3itBohHsBHunWERSjVS1ZEtWVBJHoiVb/uy45fkpcl5sOd/Li+O4yfXZSVwU+clqVi+kJZMiJZKiWESxgr0ARG+Luu//52KB3cXuYjuwSxx+w13svXfuzJn/njlt5oqM0zgHYsQBXYzuM9ZuY0SD8lAyUDpQzqL0ovgiPQ6kopSilKFMQ7kGZTbKWygvo9SgHEa5iNKHMk5XMAcIsFko/4TyPkojShdKE8rrKJtQXB8+A/6ehPIAytMoJ1DsKA4fhQC7hPIKykMoE1HG6QrjAKVSFcoTKASYL7B04thvUe4eKD/H52kUX+f7+51SkuD8B5QSlHG6AjiQjD5uRgkVNP4AFcixHtx7F8onUAj6cUpADnBgK1CeRAkEFNE+h9PrN1FSUMYpgThgQl/WoBxAiTaIgqmfU/j3UdJRxikBOGBBH25BoUUYDBBidS4B9z2UJJRximMOmNH2G1Auo8QKPKHch1PqN1BcLV/8OU7xwgG6KRag1KCEAoBYX3MM7VyGckURBykRqBCd+DHK3DjpDJ3J9PHtRWmNkzaPNxMcoGf/UZRYS6dw70d3zN+iJMoDj674p3jvKNtfjUIrL96UbqdVyhDXBZSEp3h3NHLA6LTNjtORWoF2r0Oxxmn7r5hmM9bJwWpBCXdKG83rX0L7F6EkPMWzZKOudhcKP+OZ5qPxi1H48CQ0xavOxoekHOVxlIiAjU6vaeUl8o3PfFLmT6+UXQePSm9fTDKFGL+lb5CWKZ2+CUvxCjYbRuRWlDsiNTJZ6anyz1+4T27bsFzys9Ll+JmLcvxszPR2ovogypFI9Wcs1hOv0yilwYZIMTTFZpV7b7pa1i6eLe0ddslOT5NNqxeJzcroV0yoDHeZgsIoSMJSPIKNM14OCiMGYZPJaJD5MybL5tuvV9Omw+EQs9kky6qnyS1rl4Rdf4AV0MlbhZIb4PlxeVo8go1PP6VAfiQ4npeVIV++9xZJT7FJfz+NUlGfuVlpcv/NG2TxLN4q6sQHaAJKcdTvNIo3iEewMYVoKkrYbU9Jssmnb1gti2ZWSVd3r8swEHQ6qZpUJI8++CllMLgcjNbXAlTMsFvCUjwaCNTXaBjQZRAyGQ0GmTejQr65+dNiNOgFs+cw0ul0UpCdJQtmVElDc4syGDjNRomY1bsThVZpQlI8go3Zrp9GCWt+y8X0+e3P/xWkV7H09Pp2cRBauZmpsmrhLCktypezly5LXWNzNMDARryPsgulPxo3GO064xVsdObSgguJUpNt8tAd18sn1q/A9EmB4p/6Ic0sJrPMnlIq1y5fIBMLc6W+qUVqG1sgESMm6VgRgUbpNnKj/Dd5TB6NV7BRspWEwlGbxSy3AWRfuf826ev3LdE863YgItYPeZNis8B6rZTrr1okMysmShtcJecvN0pfX9jCiBUQbNtRmH6UcBSPYGN2x0aUimBHw2wyyppF1fL45+8RK9wbTuszmHooyQgsi8kkMyomybUr58vsyWVK0p2+WBdMVZ7nUrK9h7INhetTE47iEWyMHqxBmRnMaNAgWAh/2r9+5TOSg2hBT/iSCJKxH8aFQaaUTpB1S6olMy1FPjp+Wjq7uoNpmvNcitkdKO+gjIPNyZVR/qTrg0Bb6dmOJHj8szLSVBTA9ZhBr5eZkyfJv3/1QZlYlCvdPYFPn671+PpO0FktFpk3tUJKi/Nkz6Hj0trONc/DiRZuMqZiG87v6nFTzfgHpRrBFhJah99tbP0Sj5KNDtAslNucrFRgqpwoP//mI/LZ268TI6IC7x/Qwox6AG0qJM+/f/0BmVI2Qbrd/GnOGsL/5PSqhwultDhf8rMzZPuHh7waH0W5WfLV+2+Xz921SRqbW6Xm3CWhAQIiOrei/AXF1emHPxOD4hFsVKQpmgi2FA5DGqzLz35qk9y8ejFCTUaZP22ykhof15zF9wr531+6X2ZWlnodfF4fKSLgOK0SUL29/bJzP5Nw3YkB/w3L5mLanSO9kIgHj52SxpY2nkR/ymsonEojZuKirjFD8ZhDRbDVotBqY+aHkmQT83Oku7cXyrsDU5pJ/v4zt8td162S/JxMyUhNijrQ2A5SPwCUlpwkN65ZItv2fiS7PzqmHRj4H7OocCqlNMtMTVF63sAhJoE2oYRt1g7UN+Y+wg75jFKP2nHfV5z3plXZ0WUfWIgJJwWnNJ1BJpcWS0qSJeI6mvO+vj4daMnEghy5fcMKYaDflQyQfMwyYciCPr7unsEZ8zLOC8ucdb3PWPwer2CjtUbd5hyZyiTHMxcvK4nBvzVyYCrrU74x5y+x+nQ4+pUBsKR6KkJdk91ua4H7JQspTL3w8dVDZ2tq5XOjiH05P/A9IT/iFWzUaS6iKOnGcNORk+eCctJGezTpBC7EFL5x5UKhNCPRkMmAzpafnS6d9h65eLnBqa/REj2Joh4efCYkxSvYOBjUqp9G6e7BVERjoLGlQzCeY4I4ldO9sQgpStMRaSDZrGapmFAgORnp0tDSKmcu1El7p3Kp8cH5GCWh08LHyNBwKIImSoPdKH+hn+tcXb0cAeCMxrFj86isEUi3q+bNUJ2jQcBplQbMWUQbTgJsBCWIZushlIS0QtlBUjyDje2nBfdrFEdHZ5ds3b1PDLqx0yUl3eBongpDxYSHoBAukaXVU+CW6ZVDJ85gncMFNF0F3T/EJyVbQtPYGZnQ2Mw56C2UnZ1dXfLGjn1ysb5R5aeFVl1kryLYCLLplSVy3Yr5ch30t4kFeYhwdMqej487U5VO4K67UOoje/exV5u7XT722hdIiziddmBcb+qw23V0qq6aP0t0KiGSbpBAqojmOTpYnymyYek8FZulsbDvSI088dTLCGl18MZ05D6JMg42cmOMEx1Vl1DmwWdVvv/oSaltaIJybpYkm02SoJSPNuB0mNoNep2cRuLlc1velX//3Qty/LTycrDdv0V5AyVhnbnomyLGGROBTOjE1Sh/RGFWCNwNeqmuKpefPvqwFOZlqsgCf4810Uiwd/XIi2/vlMd/8Qfn1Olsxp/w5R9ROI0mPMW7zuYcIE6lO1B+5fyBOWcnsMh4GwLiZiOxOHp0Bpbnb17c6gk0GjcMuXFx8hVBiQI2DhYD2d9HOco/SMwr2/r+fqwxIBZjT5RqPUhnOnL6nMpz82jBB/ibD4j3XCSPkxPhz0QCG02BMyj/hqLMAsYe9xw6JsfOXIJVGPuuEmxtnbA8kd/mkVDJrJWdKAm7kgp9G0axH4FhTYjoDzTvXkEZlG6NzW3y8rZdcIfE3tnLBIELCElt2/ORZydpeRJsCR0x8Ox0ooGN/aMutM/Z0Xa7XV546z05V1sPwMXOHtLDArV3d8vuA0fl8KlzzuY4P/nDFSXV2PFEBBsRZWbnSJQupy5ekv/60xaEsmJnKDAQfwEAf+7P78ESdktDpyP6GAqn/CuKEg1sRNNMlBWuo8jldr9/6c/y5937lf/N9Vg0vjMVvQMB9hff2aX2efO4Bx3pqSjKReNxLKH/ZMcThSjN1qH8BKXYs1OILsj+o6exb0eFFOflRG2jP06fff298has4H/9z2ecWR2uzSHPs1A4lX6M0otyRVCigC0Zo/VZlB+ieN2chVEE7texc98RtcNkaVEeABdZpz2B1gOgvbnjQ/mnnz0pF+oafIGIUm0RCiUcp9So7OeAescUJQLYOGBfQPkOitUfdwk4Zsdu33tIppdPlNIJeWrBsb9rAj3m9Km9seMDefSHv5Hz0NdGILZ7Gco0lJMoZ1ASmuIdbJQQd6H8C0rAfWlF1sVHJ07L+iVzsUbBGoHYKW0Shxw8cUr+6SdPysnzl/B3QESdeTLKHBSuP6CUi6y4RYVjhQIeoLHSYJd2sO1zUX6OQikRMDH1hw7fSZhK52BhcbgbNXP6pE74yrYP5Ok3tgXcDpcTi/Cdhg3FIfW4hARcPFujmRiUL6PkoQRNXPZ37DSSFyOREgLBxlVSdcg2CYOYzvt3KNeGUceYvjRewUbLcyHKplC5S+lmR+xUxbVCrcR5HSoxmgyua0CdR4L9nI8L7kOhlEs4ilewpWAkPoViCXVEjHqD5OVkYH1p+FEFLjhOwuKWOVVlUpBDr0ZYtApXb0AZdEyHVdsYujgewUZ05KCsDYePFmyZxY1g+rDGM3xyIDnSIDOwec3dG1erJXth1Mm+LUapDKOOMXlpPBoIlGaMEHwGJSSxxFXqC7FP7mbsPjmQIILP8IjhKavZIhUlhWp6/hCp32EQHb0HUGgsJAzFI9icmwFeHeoo5Gdnyv966G4pK8mPmJ+NbSHgUrED+dxp5Qp4B+FeCWQbVS/94IyzB2W3qtbLCfH4UzyCjdEC7mBEZTpo4nsPHtv8KewkNC8q22dpy/essgjrQ2dVlaoUI276HAIxK4Q7UY5O5mcIDR7pkngF263oWFAWG7efnzulTB7/wr1y7YoFoUqckfipjhNwDMZXlBQoUJdg+d6R843S0hxwVIpGMoH2LkrC7K8b+4xCNRxh/xe4Vg9r05SSJRPnrZav3rNW1lWmSyv22Yg2EXDc2CY5KUkqF66Rwr750ndotzQdfFs6L50Uh//Nowk2kvNT+yvO/49HsFF5vjAi3+HVt2QVSOa0FZIxdYmY0/PkqTM9MiW3R3IR5MJefVEnWi9nW/vlqaMOadYlS071WsmYsljazx6Wy3tfl84Lx6W/2+sShG5c2obCz4SheAMbx4+RA2ZMeCWD2SbWoirJnr5MkifNElNyujiw4KWvt1sOIwn7V7DvvlStw/ZaEBtRlBtYJiqtgMqbSCQ6gCCUSY+t7dEOvckiqRVzJa9qjizNFUluPiFb3nxNDhw4IG1trc4+MRTBAGtCgS3edDZ6TL+NchPKIJnNZlm8eKl89rMPyRe/8qjYS5dJvSlbTFh34EDKD2cjopTYOovt0GxID5+Vjb1SowQ23qsXdX8IkP3XEcRhIUVdfcf9mEKXTi6SzTeulNtvvEE2btS6s3//h9KFbSSQQXIUK+dfwlR8BFUlDMWTU5cZHhyVe8l9KuAlJRMBsIflpZdekz/96WX54he/JlctWSAPr58lOTYjcsvc0rEV4Lox8P99wiG7auGij+KjVovZ8bmTDqm3o61E3wAx2pCM1frrZpVISWYypFm7pKWlAXCbZMmSZeqs1NS06Zs3P/R3ly41bW5sbJwE0LnU4Kwp/j7jBWxkNrNvv46iT05Ols2bH5atW7fJd7/7fTVI3OKgs7MdSnmPzJiQLQ9cPQs7GumGTZWsqBH23S8+xhoBSLlIr/AjsDogTLeeh6MMSUOugKYg5TYMU4syZVFFoTaV47deJAVMnlwly5dDv8zIlNbWlqTm5ubVDQ2Xf2w2J/+5paXz39rbuxcePXqUDu24pXgBG31rt6NUcf+1efMWyKOPPiZ5eXkAWCf8ZQioDyhg/OCUtWH2JLll0WS1qbPn6BBwx+GF+L8AHF+J4DrFeZ4bzN+sl1PzYWhcL54avqkN25iNzaRvXlApGckWpI8TftQdEVuF1Tp//kKZNm26+vvAgf1y8OBBzKj6SSaT+QvYgu6l/PwJP2tsbL0K58cl6MY82LZv3277wQ9+emNqaupDHJj09HRItYfwmYnV5t5dGAQc966956ppMr+8YNgGzgQFh/kvF0WeqcG2VpHiAiquw/T5PKbPSx2UYmyxRpw+rWjTsqoCWTVtwiDQBo8DTeXlFVJVVaVUhDNnTsvRo0ekvb1d6XF9fb05BoPpHpPJ9nR7e9ePmpraFzz11FNRVAScLYvcpws7IldpJGrC02uore1cMW3arOfMZsNvIcEmUKrNnDlL1q1brwbA/30ckpOSJJ+7plry05OGrTcg4Hqgv/2/E/Cewu5zne781+v9KKWjHdPnO3DK7PDQBwlsZpeU5abLJyBtzUhHojRzJW5pn52dDcBVKh2uqalJTp8+iSm1FZJXr7a878KO6H19PQjU6//GZDI9fc01N/0DdLpS13rG8vcxBzYMgg5Pc1FTU+f3kpIMr/b1da1/443XlV5DXe366zdiMDIwWECKH+JY6nQOmV6cLQ9ePRu6GV9g6z7ABFwjnAu/POSQc9DfXCWRn6q9HmLVRzE1v4DpE7hxI943HVvk3zCvVCYX4oUc7utI1bk8x4I0JRo9OTm5aq1pbW2tEHR6FwuDa1CpNmDjnInYqekber3ldy0tXbecOXOGBtSYpjEFNjDc2NDQ+Yn+fsMWm832OSjOSefO1cmePYxH43UuKamycuUqZQQEwlUCgBJnHfS3WxdXQn9zt05ZBwF3Au4tAo76m8u4BnILdQ6vodX5JwDtHFyxrqAlvE34YX5pnlxbXTZMwrrehBY2gUYJRyLQWlqa0Ae20p1oVPT0dGMDcuNSuEl+kp6e+82xLuXGDNhaWlqy8YR+z2TSP4khr6JlSbCcPXsa5YzamHnixIlSVoYB8wIa96EY+ot1WDBt3bNyuiyo8K2/bcdU+vpZ5KUNH9ehyrx84+ldAOm7uP4vmEJdp2MCjVSclSK3AexpNm5M6PxVO+b5fxre7AfXh/q5vb1NuUa0R8LzTM2wAOAo9fMAyC/p9dYftbR0LB1+5tj4ZdTBBubr8UTO0emsz5vNlocxTejJQBJ1ldOnkaYDRye3TqisrFTSbaQpdDhraQXa5HMboL9lJA+TLgQMw1fP1GiO2GAMBkLnRIvmU/MMgRFYydgZfP2sSTK3NN+rZHVtK8+3WhEBsWorEtlvFs2ccT3T/TuMBwIOmoLheki6H7a2dt2KusZcdGhUwbZli8PY1NS60WRK+hOmgmWaNBt68jGryMWLMBlBNA5KSkqwo2TwPNSEiUOmQn/bDP8bM0A4sK5EwDG68DvEMS/BogzE/8bpswlYeOk0Fn4CcJ7TJ31qM4qz5Mb55UpKu97P13fwYfAlHdzQkEAKhNgf+hhB86DLfaehofXemhqHhtpAKojBOaMGNjDHVF3d9jdms/V3/f26Yrt9eECainZzMwKaIG5bmpUFQ0xpWeqnoP4jtmgRroOUuW0x/W/D9Tf6yA42IMIAwHFq9Ke/OafP9+G43XpuuPuEg5+XliS3LqyENZwccPo5rVLng0DDANIq4H7yOs14cFTZbEn/Iz294178NmYANypgAwPMjY3tX4Kz8kfYmTG5F0Fy74RBH3grMZVkWmsjTSne69F+5WDQ7XDPymmysLJwmP+NzLAD4G9DmL5yZmT97TSMgWdrsEoLwHTV4elTS8KrKFdOnSDLphTD8BgObG/tZB8Jlu5uTp0wLExmvNLS5O1Uv79RDYE6Um6xmL7S2mq/C/0OHLF+aw7vYMzBxpBLc3P734GR34Gib2BQOhDC+DmniUBO93OOQ7JSoL+tr5YCxCY99/ugxGoG9p87KfIBEmzNXjhEicdzXj0DdweiBa5TLidnStCK/HS5dVGl2vGSbQ+UOjo64MiFRxiUhBRzSCh8Y6uCIzq8ISQrIBm/2NbWPQ2AC76S4G454tleWDniNSGfQImWk1P4sNFo/jYUWl0gij4tUBLfjpyfXzg4xYTaCG3gqb9lyeZ18L956G/OETk/oL+dx7i7gonHGcwnEN+A9ep6jG2i9MxMscimeZVSnpc+DMz+2s22tbQ0o0ABBNHVk5KSgm9BoFVdqf1HCYf2zMTU/PXmZnupy6FR+RozsO3a5TAhxHInPN//DIkWENAIxlWrVstjjz0mX/vaV6W6utpniCoY7nFQOWVdPWOi3L6E+hvQ40IEFPW3Q9TfjmnTpKv+RiA+i5BUG6Sb2/SJ6yxcuYUQ2XpkdfQEuUsSpXx9/WX41zQ9laE5ZoQQwKES/XHw392JKu5DNCI31HoicV1MwAZmGUpKmtdAon0fbzo2BTp1soNw7iJAPV9mzJgFpgc25QbCGA4g9be7V0yTxZML8T4p97rJGDvGmOGnl+Gspf+NgGuFwfc6ps+PAERcPkiEA0E6ITtVbocBor3sI3CQEPx0c1y6dEk5c5mjl5ubq3xuNBpCJfYT1wNvukdgZq05c8YxapGGqIMNndWBiZUWi/mnYFhaoKY8mcsH2oBRpknPwlc9RpY0/e1h6G9F0N/oanAlgqcF4Hr+JNKFMG1Siu0DyF6F8eAq6XgNBzXVapLrqktlRkkutsN3By/P8UcEGzN1z549pz7p2C0sLFLZIKw7HGKIC2pIBiIzX0lN7Rg1/S3qYEPIJd1u7/uBxWItdTprg2EcMh1wOodd8yOFy3jXe2tj6JApyC/bDMDRvaIQPnAS70q6AK8M/W8M2DMkRd+aK9gIBcZeZ0/MRfyzTAXN1YVB/MdQVV1dHfyK52lJKqk2YUIJrNGRow6B3IZS02jUL4Af7976+s6iQK6J9DlRBRuAAV+F+YsWi219Z6dmYQXTAUoSRBWUfkVjSnMJhPeUe96fgKNUWTsDb86DVOr2yBUn4Jh2dgAS7V8+dMgBSDjX6ZP18QEoQGTiNmR0ZMPSpesjeNJBqp1VoTleW1xcjDJBtS34urxfwZkBG1rfC//40tFw+EYNbBgAQ31983J4/r9ix95lwZJT6tDPpEUNNIcl9T2CI5JEsJih2H9qWZVUT8zxaTA0Q6J5To6aT80ka6aXyKLKgqCnT2c/KPVrao7LuXNnVf+Ki0swjRaGJCWddXp+kncI+6WDfw9kZ3eXeh6P9t9RA9uFCxcy0bF/hp/HFoiLw7OjGp70SrIxVMVplKCla4D16XQu2rnnxSH97ZBJuRly3+oZ8PjbvOpvrlMnb0H5xZBUVWGm3LywQktDD+HenEJpgcIHCWu0XlmglZUViJhkRxRsbJo2O+jW4gFb39DQkB5Cc0O+JCpgo+M2KSnzM2azbSET/oIlDWg6lV5z9OjHTKVBFbTWsOP3/g9k795d0G3O4jeCLjJdoCSlz2xuWZ7ctXwqpOnwBEfPflAiZqVYAbRKKYEV2hui1ch7cQqtqTmh9DVKtcrKKhWU5z0iSawPuqkBD+x9yIWriGTdI9UVfFR7hBrRGV19fXcZ/P1fcoZdRrjE7TCBRv5evHgOT/rHYH6PApTGdOb19ysp0NzcBIW6FoMyGe6RFPW7W0Uh/EHdLAmp21y/UFPbIs/uOo7p1fuUzXOZurS0sghT6ISgfWquzaNr49ixo3LixHH1c3l5OVKpytFv17Mi950hMfg758L63tDc7DiZnq6DRhp9ioxYcGknnlCr0dj/iMWSlBuMP41VaEDTwdd0QQ4fPogphPqZNpXm5OSrCILNxrUvGtXX12Jx74dInW5UAWvqcuEW1kwl/5PLpiAtiC4Md3cIj1PWMCOlNCcdTmFIIMRB+Vso96ZUY+r3oUMfyfnz55T1yQdowgSsU1Ax1SghDq3Fgwtnb0cZ+xQLiqhkg/TRX77cPA0Nv9tbFoe/DmlPsQ6Mb1YSjedy8AiyiopKSK9kSC++UpGOz/PIc6tRTlBauceOHZGpU2coCadBwd+d/B8jaFhHaU6a3Lt6ulx6frfUtnSqeKfzSkrZTOwyvnFeuVTBbcL4aqiQoL5GkB05clitFCsrK5Pp02cMJlCaIGl5jhbrHA58Z5vIK7V0caAhDohef1YxVRPowrMgVNfAPXUiIyNDC1s4K4zCZ6g88toU5sGnpub8G3xqDzI3LVhihGDfvr3S2HgZQDOo9OgZM6rBbEiOgTUHGij1SD1qwAAdUlPq9u3b5b333lcDok23wd7Z/XwlufAThdrF5g5p6ux2AxvPtsJ6Lc5KUuGpcO5JkDAWSsBRwiUnpyipRqcuQVZUVCSbNt0sGzZcowwHTdq5t5eBfwL+3Y+OyFu7D8rE/By5dvEcKcxK9ws4+vAQOtyBGeTBrKyUfe61Rv6viIENDNe1tnZP6e/v3Y5JJjOYKZQAop5WW3tRPvpon2Iy04mqqxcMSDT3J9oJOE6j3/rWN+XXv/515DkzhmrMzMyUhx9+RC1hZHDeNXxFsHITwrfxIpH/eOVtqcFbm3PSUuWvrlkhG5fN9ytxeS28BT06Xd9DdnvSk3l5OiRNRY8iprMdOyZmna73DuhqQQGNXSPQGCo6fx5BxwEqLp6IUI1vxZ8Mr6u7jK0XXnZekrCfSJuXLVvehCW+X1nJzo4SLJQWOw8ekyfffFfO4d2mZriJGJdNscEZ7jzRxyclMqZShGgMm3S61qhHFSIGtvz81lQkGHwy2JAUpRSZRl2NFianDgbf8/OLB6fO4bwiGx2YbhtwTcAb7A2vJo5+4VSr+Rg1rVIBDXzbc6RGfv/Wdjlx4ZKa6lOhS65fOFsWT5+s+DpSF6kLIl69CrHrKqzAj+oO5REB25YtW4wGg3kJdKtpA3nwI/XR7TifsMuXa9X0wO+5ufkqK5ffvRN/16l9MZiCcyUQFHjVX6c0Y8r4wZoz8rs3t8vh0+c11QPRluuhq924fL6k4oH1zb8hjlHdQWp+Oh7ydfn5+VFNQYoI2CorV5uQ3n0rQksjSe6hXg58I54I0KamBjobUYwwDHL9MorXkNnp6RkqYD2s0gT8getJc3JyFF/oLjmCt9P87s1tcgCbRJNM+G3Dgtlyy1WLsI9IErZ3cNdz/bGERgf8mRuwtUMRABr0GPqr2/VYJMCmy87uzEHfrnGuF3C9wUjfOY0yFbqjg9arDnpaMiyyVD9T6FCNyWAq03ASnahaMLeNDxctz5oLtfIkps49h2tU1wm+1XOmy22rF0t2ekpQQGMFTPuC9V/V12dYiBBWarT4GTbYdu3aZUSe1FUwo4tgiQbVTgKNAGtraxmQZA5l3muBd/9V8VqIf5UZ4f/M+D/KbSeYEp+ZkSZnYRT991vvyrsHj0Jr1fTdFTOr5I61S6UoO0MZWsH2mNMteG6AbbHOaEzOC/b6QM8PG2xFRfNNSD2+hU9fKMSOUqpx6ysuW0tNTQ9IseW9uC6Ba0kTnaivTSgukqb2LnnqrR3y9r5Dmv8MvFs4rUI+uXaZTMrPDmq9gyfPqMpAf1uONKRSjEmksxzU7UJDyEBLOb8nJXUV4nM1422hEK974okfy5e//GV55plnBla885n1T9TbqOMx5yvRifvQmRD/fW77HnljzwE6YjETiFRXlspdVy+TyuL8sIBG/lFvQ6JqHpb/LYFzmfsWR5zCDVfh+r61mM6yg83u0KZQPQLuF+TZZ5+BgdCEnSTfdnNYjtxbnRQUFDBHSxkZI58fn2cYLcmy/fBpOd15avD9DdMmFSugTSstDmnq9MYJGl0w9K7u69P9EceRJhpZCkuyoSlYvOK4iXpXKETAMX+LG97RpM/JyUZaDddJjizZeA6D9LTSEt39caqhRfaeuihteAM0iZLs0+uXSfXkUgAtEF6py0b8r7ubOnf/PL3eUXnwoCPiPreQwcYp9PLlrhJgZHkoqUTOnmP/WCT0dSuwcasohqlQt/Owz0+eQrAylOPcYsrnyXF8QAc91mBJEj3imHRnFOEVk5uWzJOZE0uCnAVGZgJj01g8nmYyWZbl5rZnjXxFcGeEDDbcxmgy9a3F+oIMZ5A8uFtrlpTTXULJxh18aMYHQwxcM306UcmANRjmJHgj9CbJTzbJppklMqs4RxqbWqWhoRkPKvP9Itd7PugIHWLZpUTcwRsy2LC+kWJ2YyBSyBcrKJ1cwUV/TzD1kcnIMEloI8FksYkexkEOVtnfOCVdVufaxdKBzUj6e5AmT2d4K0J9nUHxzdd48HcuisEYzILBMAOfEd2UJiSwoRE6gyEDZqBjWahWqNZhbZdsAo4g4zsBNAdj4I8q1ycksvtDjyk0JxPvSZiSIasmpYoJL+kztJ8XYyfTsBh96Vd8wyaAmFZHVj/8AY3HOEthKrUlJVlWYhdQbhsVMQoJbLg7YqF9axDeCHkKdfYAu4Crze8INi76cE6rzuP+PjXJmNjuj+yMVLlxVqGsKwWfkKLONE19T4cYIN0MPTCsYEEyMtXZaQfoOiIi4bTwlWM1xiSi+kmoYENaiuMGf0AI5BifRDosmaNFsDGVpr1d2x07kOt5DnW9goIit+k40GvH+nmUXEvKc2X9lDxJNgNUTsGFA4buFtHbG0QHpZ7nkZd2exdAh/WGYRJnFwB4CiaNuVg4HbHwVUhgq6vrKkbAIKwplFKJTEpLS8fLM7TAO1OGsATQJ3BUxgMvGiS6P3Tq5Rvabj+DBxLiixUW6ITcTEnHmgjnCzq0jiGPDQuBDD3NousFuMADsoVTKgFHp284xAcfUyk2hzasw8KYiIWvQgKb1epYaTJZs8Lf6MWhtoSaOnW64g23CHjvvZ1c+TPIK4KJehlz8akfMqQFRqjjTsDyFTzZ2RFVLwbvP5pf0vAeB775maAjANwJ+4302gG6IUlGwHV39ylVZPj57leP9Bc3aESC6krkKJairuBcBD4qDwlsaMQNBEEkiN7/1avXqKoIpt/+9j/h6K1TViY92qQ9e3bJnXfegfOWyz333Ck7d253AaQO8dRUSLd8dW4i/ZeF9O7c7HQqyF66Bd7AItUrsA0BkRnM1HvDlW5a+MpYAL/n8osX2yLicwsabOfPt1CErAjPCh3iHZP31q5dL2Vl2oYsH364Vx544G/kgw92K6X3GPLNH3roAbx173m13O3555/F3w+q1eOUeCT65yZMKB6qNEG+0TjIzWBigvcO6cA76UNM2kXqadKtV02nlG6a6uF+vfabj0pdTmVdwO418LNH5EkOOjZqsxlXQhrlhRM1cPZHmwYdagXR1772DbzO8W/xRPbKiy++IK+//ir8Z8XqCeV7nJxEBtbWXpJ33tmK5XvTwYxOFRvljj+JRtlIKcrEexG8LskjVrgNBXYfwQJQcbggksZCR4edcU7ov/qB2Cn3adPAxwwdExZYW60W9clx8Eb0ueHUeVAKZ2Nft+MlJTotXubt5AB+Cxps8MPcTL0pUsSOUrrdccedavp8/PFvqVgpJWdNTY3X25BZjByA2+qhxlZQkGyJBTZo50KwpadivSxA4p0U8xQfXI9rEonWKTNxvF+LjC7wuRN8tKIkKQnoWge/0+dmNNqsBkPvOp3Ovg0/nfI8J5i/g5pGGxoc3IhkbSSkmmsjCTi+Z+qRR76EDJCX5O6778HC5MnITE1Xi3UzM4dUBq51XLx4iVpHSYOCxGmBIatI6ZGubRut78k2q+QjGTIZ0se7sg/RBsZxIZ8PPIEfGm+c06bnJyUd3mWK0oYH3nsaObe/gIRc19Njn4R2BIUXT94FJdkMho5ler1pAlfkRJoIOCbwLVmyVJYtW6ZWWjHtyMmEHTt2QGfbD92uUm688WYFRCqxGgMF2R95apdGZpAkAqXjvaT5sETNsMK9TqMDnVRyi1NqiERA4pWSio+pkKKeDyzVGqhNJQZD0grkuR3EbepDvJUEBTYEaG9Cch2QHnmwsQMEnHMpIF/2yqmSzOAajIqKCpzBB0tzXhJo6iB3MULJQvYHHcSJArbs9DTJxYp2PXSufq9+MzCLfVc8CQNtqIE87ujoQpq9CV4A8zDAUW2Bo/d65Lk9j9OjD7aLFx3Y0aVtfaSsUDTaL1HEw9QaPKezc2B9A/mq9mZDPLW3Q/ramqS35aIYLx3FhjAWOTd4RXx/oSWaA53NP4EZAAIY4v+0AI6S3zQq6M90TY7gpRQu2Nt4PqKU1ZhKj0P6hWQoBCzZLJa2RUgbLqNYHRVS/IRhAkdmT8s56WtCacFaUzu2T9UheIzf8zNjurddVNmQlZ4qGWl+jAM+iwAaXLuwRLWmOHVfzgTUgYMBIaVbV1ePKklJ7gCmoWAw2Kx6fd81WBS+HRXXaHcM7v+AwQb98SabzawbFbCRE1BUu+uOSk/tMenvwGosMBMhaTCcjDVirzSLFOYOGRLBsWFsnU09jZGDtGT/+/Mqdwc2uNbAxW38zSg0KHRqARFz3QjAQInn0ujiVMpd2l2JEQWoUevhifg1pNtpSLehacf1RD/fAwIbKrfiXVPXREtX89M+PJwIQNvbpOvkbultPo9TwQQ80e6s0PLiChFHjATRY1+AuuZUlcmMyknSjNSnp1/fLnWNsdnqIRUgy4MlahspaxnqRD8KV5klYdsFujGcq9zoDmpt7VDgCVTC8ZlmuIv+taFd2jWOUkeGJ6AAWFh96VLbAfyKl5MHRwGBrbGxdT5uXsVGxJZg2HeDYSewHVbLBejDPpqLJ5KMKsgJHWxc/DuxMFeumo930C+dI3OmlCGPLB0S0yR1DU0YgD755TOvxaT7makpyhIl6H2vbIePEdm7ZkRPbNDvrDDcAARV2Egq+8hJU05xZnEECjhu8EO9nIYgeepKlHxY83CDxaIWxEQHbBAlm5ARq9dWrbvePorf0VF6xrvOHZKeVj9AQxM4UyiwZWeqTzIlEKKZnwFzf8nsqXLd8nmyemE1JAr0PlzfgwHiw9WPBSV2ML8D2RSxIjpzmTDJ2DDG3ieZsUGiJSMLaxQ0oLmfiB3Q4ZO0WnthoQc34xFsvb1WZSy41knXFBYZzQbfFgLYx/DZ5np8pO8+RMXQZaiUr2u83umSGDoS7W96ZWV2Xz6ODo4UsaD+ppO5U8vl8Ufukz++sU0+rjkrrQOrkZwt5YPKN92VFGTL7MoyWT4XrxKaNUVJNOxpjC3poSBDz9EI2SZwO3QDcFve3y8vb9vtrCbqn7REs2Eg+H5o8DRAyhtsACQkm7cTeS1DVQxJMceNUZpApBsf2p6efvWg0TJ1JUpOSEwjJOUN2FVpK44ddT0+0nf32rycXV/fMgcpPdNjbhjAIOg6/xFiWWDSiGDTGp4Er/v9N6+XO6+7Ss5cuiynztXKpYZGSKYe6D9mKYDkm1iUqz5ToBfpoEj3UoLBj4WNowZ7zyWCBNoF7Hf2mxfekl8996Y0tQb1EA/WFewXSluCjRLXlzOXg26wJoshNRuqBdOtvItyDRxGAM6s/GhgJs71mBu9NJBApaHA65w6oPM0TW/vW9XTY5yC+k+ivc6n03mKz88RwQbGcwo1xGwKJS8IgobTcG3g/T0BAo09JHO7MAUQLOVYWzl5UhHsVVTIxxV+JKwjB3bxKmwAmGa+N9LjWjteLvvC9g/kJ394WfYfPenttKj9ZsMA52VlCsNV7M8wYvAdFqgxC68wTx05GYPgpTSnZdrb66W+YTfQ2KUZCn3Q3TggLBpRQgIPGdikex3W/H6AXwN2bfoFGzprwhS6kXN1zAhAk95O6bpwOIxb4j1X0LU8X1w7UoUcmPrmFvkpQPbLZ19XAzTSNZE+no6gOGOiVij4w8CGgdaZoEsVVIk5r1KBzpdUc7aLdZixmzmtVW0VVmDTqdNQ4LV8Vl2JlilAd71OZ3oS9Z8H3wJCMb13Puny5Y5qeJNnxszlgU6xX921NdLXgVcUq3CMz+ZF+ABe6gGp+MaOD+UXf3xtVIDGDtGZm4cw1ZAXH+NIVQLuHkNmsVgrloi5cJrojIG/wpyAs0FSWiyULR7I8cNFJmGqsKDHORQ+mF4ruZgZTt4Mj8M+//Qr2fT6/huxR64xZlMomtlvbwXYjqqp0Gero3CAT28rUm7eP3gEOlysXTxDHVJhKuSwCRaywJEhBjN0s4wCMWVy2syB1o8hUxkaAQmTwYppLBBwdOEE4gohP3gut2SgH88TpHgYdKhnI/Z0ewUHGwdv5OeLT7DhacAU2nZjzKZQdI4Ke9fFw+LoQuaGL5+an86Ec4jqUT1SbT46diqcasK+NicrS3KKJoopf7KYknPFkJIjOjOMGdRM8GlAC/42lG5U+Km7dXT0Y4oe2VggT+gG0QwFtmCIWA9oCcos1H0CU+mI743yCTa8k3IuTN/YTaFgZ1/rZemurwHQRnJ1DHU6Et+oq/HfWzv3yiG4TEaLmOZeOLlaCuZfJ2Zu9QpjBiYNUMbP8In9pO5Gtai7e+QaKd26uvqUY9iMpYSu0o1gxdYbtr6+7mubmuw7cPDUSC30qbNhCr2FViifiJgQXBBd5/YzqQ23c3+Konl/gowuhue37pAfP/kijIohF0g07+utbq6fLSgqlmSuowU/1Oq1CPKfY0nfGXdj11waI48tdTZfMVYmVmL54Ho8I6Wo2yeWnH31egIupPa5KSaOXOCKAfWeyycQ+8QeFjGVasxz7Zct7+2Tr3/v14iBIoNkFIk7MuVjlRg9/9F6yFkvg/UMR2kP9ciAo6HgTGJ1ZQ99r1h2WQwVchUSK0fMgvAKNpjICyDSp8UmForps6MBUg1JoDG1PjW2NUJPe+K/XxwWbXBlaqy+czfw/PyCYY7USN+fYTCuO/Cm+Hvey2ko0Fjw9gDwOOi63l59oee1nn97BRt8LNhm3oxjI6Pes8Lg/kZLsaLbfmo3Au7QLwdaHlwdoZ5NLjlkL3bc/uDj46FWEtHrtO3ncyNap7fKCBqn740O8JHGmYmVNBS8gY36H36fh6Gbi0/u5OiThoENS7ZsqHxj1H1r6olAJy4eRQwUCQQxnj6Z4NqJ1UdPvvx2yK/a9snVEA8QbFmIHnibskKs0udlBA51N6vVOZ36PFUdoN5GR68nsR742/AWI9M1tbXNBZ7HXf8eBraUlLaFuHBy1MEGoeno7oSuVhNTnxoNAj7V7dj15yd/eElef5cRl9Enq9WqVvXzbXwcwFgQEySTESPWplPfdxyaSmm8DW8bEyuhcq1FStMktF2bWL1U58X1oYMj16Lr6Ii2YxNtUlDHJ9vvs4leWh3ST3wDHfOx+uWld96Xnz71srx34EhINUXjIm6wU1hYoKRNrMDG+2h5bza13Za/zBDin9LNm8+NFiuSNSDVdFeBNztRvFpabmDDzS0NDW3XxcQKBcJ0Rryvs2S2dJ6Am4YLXKKms0GeIXx3qb5FvvWz38sLW9/zk5QYDSiNXGcWnLkFBYW07uBOiPaDPtQeAs7pe+vs5DTp+8mnwciH1TMThLVx6BBRuLatzf4E/vQKNrdpFLsXzoYVOiVmnQUAjFmTxFa6EFKOEo4djTyREZ3YEvS7//lHeRYvrfCd/Rr5ewdaI/U1vjJIU9gDvSoy59E6TcGOSVQvfE0x5CE3q9E8FMPHacBQmGu3C7dHdRNizla6gQ3+EmwiEkNHrnqIsMQ/p1xsZYsAODRHhVGczQv3E5vmIbXahHz8d/Cyiqff4MKgsUkEGrf9oqSJNfGedPbSHeLP2cumEVTaMkv3Vmp1QAGzGDZiT0cs+xxOg2DDycjCdmzQgrTDT4zaLwOAM2WXia18iZY2EwzgyAEGppkZgeJQBRuioMFGKMAX6hrkl398XR790W9csnCj1puQKuYAU7JxkbUWswypmrAuIlioj3FK9ecOIdi8WaW8OSMKOLZJr7fTwQtZ6E6D4g5bjJbgHRpzom6Fut9f+wt4cWDtpzF7otgQgO+s2SmOHvrdBp8F96ug36mlfDhXh93C9VjGpzNgY2tIMb0B3nf8/v7effJ/vv8j+fjkGSxYic2qKPdGBv4XX4TGN9V4vpI78BoidyalGxV+bbtUJQkGK9emUuQJYjqlBctYqytR/YIaVomXdiytqXFcKCtzD84Pgg1JHouxz1lqsK8Fcr1ZWN8HJJwhs0iSTCuk89gOlW7k5n/jOQgv6awpYsktFyPO1SEFR8e0G07BeJhUrBPndB6uVVNnWG2K0cV8tSPBhjloVKZR127SHUL9jdJrILPD9bCaQimQKAW9EdxmOmw8dFN2duurOA6JMUSDogOi8yrmPI0qDYBJn5IrSVNWqRx7h2PAMuMxNM+UPUmSp68Ry4SZok/CaiqkSCtyTqU4X4fv+Xi5GHdBigeirpaXx3dwDT77o9ZsTfcyKsD58r9RsvlyPGuRhv41UA34olw3QKk/+CPK4phZof5YSVBBZ9Nh5ZBt8kox4XULDrzHFC9eEGvJTEmuWiRGeL4xzw4YEwqhgzVShaN453uwGGeMB6JxwDfvjRUi4Ci56PD1NBi0qRTrOFQ0wZ33bD99dWazJbevz4jFzJcwUEOkwIY3GWNUHFVjAmzOthFwSBq0VS6F4bBIkqcsEWvxNHXUQSk2XP90Xqk+c3Pz5Oqr17v9Nhb/4GCWlpZi981in9JiNNpNwNFYYHavp8FAQBFsfLC9E7JpHH0bBXB1Pa7ABmfeVDgT032JRtcLYvqdvYE+Zs6vEn3qBEGKFwaEHcFmKr6jImiiQ+k/f/3XD8jcufNi2uRgb7Zo0WLZtOkWJdm85fsHW1+kz6f+xpX1SodRDl9wF8PiDyvaVCpLsAKrDKAdzIRVSgKUwlnMoRpTks3JNXTMdQt8/yDTLiIzKO4nT66U3//+D/LCC8/JqVOn/DLIebtYfVI/Ky0tk1WrVkt5ecWYapsrD6C9YPfPFDWdcltUvnKS0RiqKp7WqPM6um8Q603r7dWtrqsT5I6JWnSrbNeGhvafYW3h32I6dZ6fEJ8EHFfTa5aT6uqY6hedo1zjQYnGaWssE3mpvWNB26WSCZiaPue91bSsYc1uNRr7PolXB6h9QQbMn/6qsSjCvXcj8F85fpSKHR7bMARew/iZTg6QlwxnaSEt8tX/wzHgNpmP6bYc59ZDCvbpmb+GCickIticjBr/jAwHCDBnGalGbUGMObW/37Qc779SVqneZqtHaEGX7U/hG6ni8ePjHPDFARjbq/r66KuCiWEwWLHyVVJHEou+Khv/fZwDvjigBe1lTlKSCa8KdRj10E1zYBnFbsmer5aN/55wHNBmS0chvBwV6JwNGy46ssdCmCThOD3eIaXfwaWGDTSNk+rq2pMxjerTRiNhb3wsrhwOwCOwzGBwZCEmKoX0oYzTOAeiwYGBaMIdMEInGOFYXB6bxcjR6Mp4nWOdA9qs2XcB6pqdm2Qvi9lORWOdM+PtizgHkAGC1CnTr5A3fYoRhJRxt0fEeTxeIThAwxOJlG1I0tmamWmtI9j24se5YyUIT/2R74DXLORIKpP+wyvRRocW3fHeBvfIj/dzot2+SNSvBea1MeN3zJgtWNPyWHe3bQ/+7iDYXkV60ZgAG5eU4XU1fdAht2OV9TZkGKjMAAR8Hcyf4qfGlH5kSTAT3Pn3SKzS42xm+/XjDTx6rIzRwwWEjbJABkM/6tY74Olm3SpVTvuO08SA3/Va+tywWzCnLnBCPbygn8Fsk0n7PnA1d+xS9x742+U7WziQ14ODWCmGtrJdQ+Trt6Ezhr55Xjt0ZPg3rkpzJVzr8uTzmPqbOa0gA1Zd9RsgyAwAlRljY8TiZ7w+omt3ZmbKx/hNpVsDbLpT+MO13lH5ziYYjZauvr6ub2dkJH3H2cBRacz4TaPCATxwustaim9U6g+oUgINuxj29vR0vvTqqy8+Pg60gNgWdyfp8FINvB3ZsI3yejSIQMP9efsz8Mmsy8tLPzoa7Ri/Z/Q5gLRw4xnoL60QcdG/m4874CVsdniZHxsHmg8GJcjP+osXay5h2jrtqRDGon+UalZrEpID7L/PyEj+j1jcc/weo8cB/YwZM7phoe2B4y2mrRgwCPq7ujr32+2OrwPwozOPx7TXV/bNBubO/jdh9ceMEwQawxiYOi9j6/PPFRam1sXs5uM3GjUOKLD193e9Zbd3NsdKb4MU4z5kndiF+n/m56e9O2q9H79xTDmgwJadnX0Gku01roiJNlGq4RVFMDztP83NTft5tO83Xv/Y4cCgCYoNaJ5AyKqfUidaxKphEPR2dra/2tmZ8o1o3We83rHJgUGw5eSk/Blm4SvcSDgaRKBhV2mEa7o+QOTjwaIiXWItUo0G0xKszkGwQaJBqsk3sN6vHU7WiHaTQIO1ixhj33FIz/tzc5MvRPQG45XFBQcGwcbWImi6F2D4BwtecR2p6dQJNOiEZ7Ex9H3Z2alcjj9OVyAHhiloiBvp8OrHnyQnpzzA7RjCCWNpU6fZAVXwnMPRc3dmZuqfr0Aej3d5gANuko2/QaI5Llw4/XkA7RdDb28Lnl8EGjaDxtTpOASwfWIcaMHzMNGuGCbZnB3UJFzH32MX6UcRyrLased4IESQUeeDG6XXbu96raur6/P5+RknArl2/JzE5oBPsDm73dDQstJoNH8LALqKyY1cLaMtPnWeoX0OJD5y10FHT0/Xeeh+321qqvtxWVlZYCh1r278rwTkwIhgY58h5Yytrd2b8O1+bBiyAotOM1Hwll07s2VVGjciEP34/jFO/wO2evsVpuBTCciv8S6FwYH/D8HPV09PyagVAAAAAElFTkSuQmCC" />

                            </td></tr>
                        <tr >
                            <td colspan="2">
                                <?php echo $data['result']; ?>
                            </td>
                        </tr>


                    <?php } } ?>
                </table>
            <?php }else{ ?>
                <h3 style="text-align: center;">No Response from this user</h3>
            <?php } ?>


        </div>
    </div>

</div>


</body>
</html>