
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 fotter">
    <div class="container">
        <div class="row">


            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 fotter-in1 wow bounceInRight">
                <div class="blog-text">
                    <div class="blog-txt">
                        <h2><img src="<?php echo Router::url('/images/footer-logo.jpg',true)?>" alt=""/></h2>
                        <p>MEngage develop mass consumer engagement application for doctors on both android and IOS platforms. It is a team of wide variety of technology individuals, Start-up Entrepreneur’s and Co-founders. The organisation believes in building never-ending relationships with customers by sharing professional and ethical values. </p>

                        <div itemscope itemtype="http://schema.org/LocalBusiness">
                            <div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
                                <img src="<?php echo Router::url('/images/address-icon.jpg',true)?>" alt="Address" /><span itemprop="streetAddress">
                                    228, Okay Plus Spaces, Near Apex Circle,
                                </span><br>
                                <img src="<?php echo Router::url('/images/address-icon.jpg',true)?>" alt="Address" /><span itemprop="streetAddress">
                                     Malviya Industrial Area
                                </span>

                                <span itemprop="addressLocality">Jaipur</span><br/> &nbsp; &nbsp; &nbsp;
                                <span itemprop="addressRegion">Rajasthan, India</span>
                                <span itemprop="postalCode">302017</span><br/>
                                <img src="<?php echo Router::url('/images/call-icon.jpg',true)?>" alt="call" /><span itemprop="telephone">0141-4910316</span>
                                <span itemprop=" telephone"> &nbsp; <i class="fa-mobile-phone fa"></i> +91-<?php echo $this->App->supportMobile(); ?></span><br/>
                                <img src="<?php echo Router::url('/images/mail-icon.jpg',true)?>" alt="email" /><a itemprop="email" href="mailto:<?php echo $this->App->supportEmail(); ?>"><?php echo $this->App->supportEmail(); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 fotter-adders wow bounceInLeft">
                <!--/logo-->
                <div class=" blog-txt2">
                    <h2>Social With us</h2>
                </div>
                <ul class="social-network">
                    <li><a href="https://www.facebook.com/MEngageApp" target="_blank"><i class="fa fa-facebook-square"></i> &nbsp; Facebook</a></li>
                    <li><a href="https://twitter.com/engage_patients" target="_blank"><i class="fa fa-twitter-square"></i> &nbsp; Twitter</a></li>
                    <li><a href="https://www.instagram.com/mengagepatients/" target="_blank"><i class="fa fa-instagram"></i> &nbsp; Instagram</a></li>
                    <li><a href="https://www.linkedin.com/in/mengage-engaging-patients-2aa119154" target="_blank"><i class="fa fa-linkedin-square"></i> &nbsp; Linked In</a></li>
                </ul>
            </div>



            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 subscribe-box1 wow bounceInLeft">
                <!--/logo-->
                <div class=" blog-txt2">
                    <h2>Subscribe us</h2>
                </div>
                <div class="subscribe-box">
                    <p>Keep updated with our latest news and updates:</p>
                    <form method="POST" id='addNewsLatter'>
                        <input type="email" name="email" class="inputbox" id="subscribeEmail" placeholder="Please write your E-mail" required />
                        <button type="submit" class="subs-btn Btn-typ3">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 Footer-copyright">
    <div class="container">
        <div class="row">
            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 terms">
                <!--<p><a href="<?php /*echo Router::url('/term',true); */?>">Terms of Use</a> | <a href="<?php /*echo Router::url('/privacy',true); */?>" >Privacy Policy</a> | <a href="<?php /*echo Router::url('/refund',true); */?>" >Refund Policy</a></p>
                -->
                <p> <a href="<?php echo Router::url('/privacy',true); ?>" >Privacy Policy</a> </p>
            </div>
            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 copyright">
                <p>&copy; 2016. All Right Reserved. </p>
            </div>

        </div>
    </div>
</div>

<!--
<div id="Create-APP-btn">
    <a href="<?php /*echo Router::url('/register-org',true); */?>"><img src="<?php /*echo Router::url('/images/create-app-btn-left.png',true); */?>" alt="Create Your Own APP" /> </a>
</div>
-->


<!--Start of Tawk.to Script-->
<script type="text/javascript">
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
        var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
        s1.async=true;
        s1.src='https://embed.tawk.to/586f789cd35229472a42f195/default';
        s1.charset='UTF-8';
        s1.setAttribute('crossorigin','*');
        s0.parentNode.insertBefore(s1,s0);
    })();
</script>
<!--End of Tawk.to Script-->
<script>
    $(document).ready(function(){
        $(document).on('submit','#addNewsLatter',function(e){
            e.preventDefault();
            var dataToSend = $(this).serialize();
            var form = $(this);
            var thisButton = $(this).find('button');

            $.ajax({
                url: baseurl+'/homes/add_to_news_latter',
                data:dataToSend,
                type:'POST',
                beforeSend:function () {
                    thisButton.button('loading').val('Wait..');
                },
                success: function(result){
                    var data = JSON.parse(result);
                    if(data.status != 1)
                    {
                        alert(data.message);
                    }
                    else
                    {
                        $('#addNewsLatter').trigger('reset');
                    }
                    thisButton.button('reset');
                }
            });


        });
    });
</script>