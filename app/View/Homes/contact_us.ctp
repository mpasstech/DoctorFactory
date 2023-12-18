
<div class="Inner-banner">
    <div class="container">
        <div class="row">
            <!--  SLIDER IMAGE -->
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Contact Us</h2> </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 pull-right create-btn-box">
                <a href="<?php echo Router::url('/register-org'); ?>" class="Btn-typ4" title="Create Own APP">Create Own APP</a>
            </div>
        </div>
    </div>
</div>
<!--//----- Google map Start here ---//-->



<div class="Home-section-2">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">

                <div itemscopeitemtype='https://schema.org/WPHeader'>
                    <h2 itemprop='name headline' class="title1">Get in touch with us! We are waiting for your query.</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">
                <!--box 1 -->
                <div style="visibility: visible;" class="col-lg-6 col-md-6 col-sm-6 col-xs-12  wow bounceInRight animated animated">
                    <div class="Contact-frm">

                        <div itemscopeitemtype='https://schema.org/WPHeader'>
                            <h2 itemprop='name headline' >Send us an email</h2>
                        </div>

                        <p>Have a question? send us a message.</p>


                        <?php echo $this->Session->flash('success'); ?>
                        <?php echo $this->Session->flash('error'); ?>

                        <?php echo $this->Form->create('AppEnquiry',array('type'=>'file','id'=>'login_form','class'=>'form-horizontal')); ?>


                        <div class="form-group">
                            <div class="col-sm-12">
                                <?php echo $this->Form->input('name',array('type'=>'text','placeholder'=>'Name','label'=>false,'id'=>'mobile','class'=>'form-control cnt')); ?>

                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <?php echo $this->Form->input('email',array('type'=>'email','placeholder'=>'E-mail','label'=>false,'id'=>'mobile','class'=>'form-control cnt')); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12">
                                <?php echo $this->Form->input('phone',array('type'=>'text','placeholder'=>'Phone','label'=>false,'id'=>'mobile','class'=>'form-control cnt')); ?>

                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12">
                                <?php echo $this->Form->input('message',array('type'=>'textarea','placeholder'=>'Your note here','label'=>false,'class'=>'form-control cnt')); ?>
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-sm-5 chack-text">
                                <?php echo $this->Form->input('newletter', array('type'=>'checkbox', 'label'=>'Subscribe our daily newsletter.', 'checked'=>'checked')); ?>
                            </div>
                            <div class="col-sm-6 pull-right">
                                <?php echo $this->Form->submit('Submit',array('class'=>'Btn-typ5','id'=>'signup')); ?>
                            </div>
                        </div>



                        <?php echo $this->Form->end(); ?>




                    </div>
                </div>

                <div style="visibility: visible;" class="col-lg-6 col-md-6 col-sm-6 col-xs-12  wow bounceInLeft animated animated">
                    <div class="Contact-frm Get-touch-box" style="width: 100%;">

                        <div itemscopeitemtype='https://schema.org/WPHeader'>

                            <h3 itemprop='name headline' >Quick Contact</h3>
                            <meta itemprop='description' content='Quick Contact'/>
                        </div>



                    <div class="content">
                      <p>We are available on here:</p>
                      <div itemscopeitemtype="http://schema.org/LocalBusiness">
                      <div itemprop="address" itemscopeitemtype="http://schema.org/PostalAddress">
                      <ul class="contact-info-list1">


                                                       <li>
                                                           <p>
                                                               <img src="images/img3.png" alt=""> &nbsp;
                                                              <a itemprop="email" href="mailto:<?php echo $this->App->supportEmail(); ?>"><?php echo $this->App->supportEmail(); ?></a>
                                                           </p>
                                                       </li>

                                                       <li>
                                                           <p>
                                                          <img src="images/img2.png" alt=""> &nbsp;
                                                                	<span itemprop=" telephone">91-<?php echo $this->App->supportMobile(); ?></span>

                                                           </p>
                                                       </li>

                                                       <li>
                                                           <p>
                                                           <img src="images/img1.png" alt=""> &nbsp;
                   										<span itemprop="streetAddress">424-425, Okay Plus Spaces, Near Apex Circle, Spaces, Near Apex Circle</span>
                   										<span itemprop="streetAddress">Address Malviya Industrial Area</span>
                   	<span itemprop="addressLocality">Jaipur</span>
                   	<span itemprop="addressRegion">Rajasthan, India</span>
                   	<span itemprop="postalCode">302017</span>
                                                           </p>
                                                       </li>
                                                   </ul>
                       </div>

                      </div>
                       </div>

                    </div>
                </div>
                <!--box 2 -->

                <!--box 3 -->

            </div>
        </div>
    </div>
</div>

