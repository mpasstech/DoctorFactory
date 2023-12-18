<?php
header('Access-Control-Allow-Origin: *');

if(!empty($url)){ ?>
    <html>


    <head>

        <script type="text/javascript">


            (function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s); js.id = id;
                js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.8&appId=1081082955247867";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));


           /* window.onload = function() {
                FB.Event.subscribe('xfbml.render', function(response) {
                    alert("asdf");
                    console.log('rendered.... finally!');
                });
            };*/

        </script>

    </head>

    <body>



    <div id="fb-root"></div>
    <div class="fb-page" data-href="<?php echo $url;  ?>" data-tabs="timeline" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true">
        <blockquote style="text-align: center;" cite="<?php echo $url;  ?>" class="fb-xfbml-parse-ignore">
            <img style="margin: 58% auto;" src="<?php echo Router::url("/images/facebook_loading_2.gif",true); ?>" alt="">
            <!--<a href="https://www.facebook.com/mbroadcastapp/">Bhai yar Setting Krwade?</a>-->
        </blockquote>
    </div>

    </body>

    </html>
<?php }else{ ?>
    <html>
    <head>
        <style type="text/css">
            /* reset */
            html,body,div,span,applet,object,iframe,h1,h2,h3,h4,h5,h6,p,blockquote,pre,a,abbr,acronym,address,big,cite,code,del,dfn,em,img,ins,kbd,q,s,samp,small,strike,strong,sub,sup,tt,var,b,u,i,dl,dt,dd,ol,nav ul,nav li,fieldset,form,label,legend,table,caption,tbody,tfoot,thead,tr,th,td,article,aside,canvas,details,embed,figure,figcaption,footer,header,hgroup,menu,nav,output,ruby,section,summary,time,mark,audio,video{margin:0;padding:0;border:0;font-size:100%;font:inherit;vertical-align:baseline;}
            article, aside, details, figcaption, figure,footer, header, hgroup, menu, nav, section {display: block;}
            ol,ul{list-style:none;margin:0px;padding:0px;}
            blockquote,q{quotes:none;}
            blockquote:before,blockquote:after,q:before,q:after{content:'';content:none;}
            table{border-collapse:collapse;border-spacing:0;}
            /* start editing from here */
            a{text-decoration:none;}
            .txt-rt{text-align:right;}/* text align right */
            .txt-lt{text-align:left;}/* text align left */
            .txt-center{text-align:center;}/* text align center */
            .float-rt{float:right;}/* float right */
            .float-lt{float:left;}/* float left */
            .clear{clear:both;}/* clear float */
            .pos-relative{position:relative;}/* Position Relative */
            .pos-absolute{position:absolute;}/* Position Absolute */
            .vertical-base{	vertical-align:baseline;}/* vertical align baseline */
            .vertical-top{	vertical-align:top;}/* vertical align top */
            .underline{	padding-bottom:5px;	border-bottom: 1px solid #eee; margin:0 0 20px 0;}/* Add 5px bottom padding and a underline */
            nav.vertical ul li{	display:block;}/* vertical menu */
            nav.horizontal ul li{	display: inline-block;}/* horizontal menu */
            img{max-width:100%;}
            /*end reset*/
            body{
                font-family: "Century Gothic" Helvetica, sans-serif;
                background: #f8e94a;
            }
            .wrap{
                margin:0 auto;
                padding: 10px;
            }
            h1{
                text-align:center;
                margin-top: 20px;
                color: #603813;
                font-size: 1.5em;
                text-transform: uppercase;
                font-weight: bold;
            }
            .banner{
                text-align:center;
                margin-top: 15px;
            }
            .page{
                text-align:center;
                font-family: "Century Gothic";
            }
            .page h2{
                font-size:1.4em;
                color: rgb(99, 44, 37);
                font-weight:bold;
            }
            .footer{
                font-family: "Century Gothic";
                margin:15px 0 10px;
            }
            .footer p{
                text-align:right;
                font-size:0.9em;
                color: #603813;
            }
            .footer a{
                color: #f9614d;
            }
            .footer a:hover{
                text-decoration:underline;
            }

        </style>
    </head>

    <body>

    <div class="wrap">
        <h1>Opss..!</h1>
        <div class="banner">
            <img src="<?php echo Router::url("/images/404banner.png",true)?>" alt="">
        </div>
        <!---320x50--->
        <div class="page">


            <h2>Facebook not config</h2>
        </div>
        <!---320x50--->
        <div class="footer">
            <!--   <p>© 2013 Poses-404. All Rights Reserved | Design by <a href="http://w3layouts.com/">w3layouts</a></p>-->
        </div>
        <!---320x50--->
    </div>

    </body>

    </html>


<?php } ?>
