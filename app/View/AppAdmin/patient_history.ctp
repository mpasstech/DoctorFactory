<div class="container-fluid" style="background-color:#e8e8e8">
    <div class="container container-pad" id="property-listings">

        <div class="row">
            <div class="col-md-7">
                <h1>Our Doctors</h1>
                <p>Book appointment with our expert doctors.</p>
            </div>

        </div>

        <div class="row">

            <?php foreach ($data  as $key => $doctor){ ?>
                <div class="col-sm-8 col-sm-offset-2">

                    <div class="brdr bgc-fff pad-10 box-shad btm-mrg-20 property-listing">
                        <div class="media">
                            <a class="pull-left" href="#" target="_blank" >

                                <?php
                                $label = strtoupper(strtolower($doctor['label']));
                                $path = $doctor['profile_photo'];

                                if($label =='APPOINTMENT'){
                                    $path = Router::url('/thinapp_images/appointment_das.png',true);
                                }else if($label =='LAB REPORT'){
                                    $path = Router::url('/thinapp_images/document.png',true);
                                }else if($label =='VACCINATION'){
                                    $path = Router::url('/thinapp_images/vaccination.png',true);
                                }else if($label =='BILLING'){
                                    $path = Router::url('/thinapp_images/hospital_bill.png',true);
                                }


                                if(empty($path)){
                                    $path = Router::url('/images/channel-icon.png',true);
                                }
                                ?>


                                <img alt="image" class="img-responsive" src="<?php echo $path; ?>">
                            </a>

                            <div class="clearfix visible-sm"></div>

                            <div class="media-body fnt-smaller">
                                <a href="#" target="_parent"></a>

                                <h4 class="media-heading">
                                    <a href="javascript:void(0)" target="_blank"><?php echo $doctor['label']; ?><small class="pull-right"><?php echo $doctor['patient_name']; ?></small></a></h4>
                                <ul class="list-inline mrg-0 btm-mrg-10 clr-535353">
                                    <li><i class="fa fa-clock-o"></i> <?php echo $doctor['date']; ?></li>

                                    <li style="list-style: none">|</li>

                                    <li>Gender : <?php echo $doctor['gender']; ?></li>

                                    <li style="list-style: none">|</li>

                                    <li><i class="fa fa-id-card-o "></i> <?php echo $doctor['uhid']; ?></li>
                                </ul>

                                <p class="hidden-xs"><?php echo $doctor['purpose']; ?></p><span class="fnt-smaller fnt-lighter fnt-arial"><i class="fa fa-graduation-cap"></i> Sub title goed here</span>
                            </div>
                        </div>
                    </div>

                </div>

            <?php } ?>
        </div><!-- End row -->
    </div><!-- End container -->
</div>

<style>

    /**** BASE ****/
    body {
        color: #888;
    }
    a {
        color: #03a1d1;
        text-decoration: none!important;
    }

    /**** LAYOUT ****/
    .list-inline>li {
        padding: 0 10px 0 0;
    }
    .container-pad {
        padding: 30px 15px;
    }


    /**** MODULE ****/
    .bgc-fff {
        background-color: #fff!important;
    }
    .box-shad {
        -webkit-box-shadow: 1px 1px 0 rgba(0,0,0,.2);
        box-shadow: 1px 1px 0 rgba(0,0,0,.2);
    }
    .brdr {
        border: 1px solid #ededed;
    }

    /* Font changes */
    .fnt-smaller {
        font-size: .9em;
    }
    .fnt-lighter {
        color: #bbb;
    }

    /* Padding - Margins */
    .pad-10 {
        padding: 10px!important;
    }
    .mrg-0 {
        margin: 0!important;
    }
    .btm-mrg-10 {
        margin-bottom: 10px!important;
    }
    .btm-mrg-20 {
        margin-bottom: 20px!important;
    }

    /* Color  */
    .clr-535353 {
        color: #535353;
    }

    .img-responsive{
        width: 150px;
        height: 150px;
        border-radius: 80px;
    }



    /**** MEDIA QUERIES ****/
    @media only screen and (max-width: 991px) {
        #property-listings .property-listing {
            padding: 5px!important;
        }
        #property-listings .property-listing a {
            margin: 0;
        }
        #property-listings .property-listing .media-body {
            padding: 10px;
        }
    }

    @media only screen and (min-width: 992px) {
        #property-listings .property-listing img {
            max-width: 180px;
        }
    }

</style>
