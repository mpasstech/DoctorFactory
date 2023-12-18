<?php if(!empty($tracker_data)){
    foreach ($tracker_data as $key => $data)
    { ?>
    <div style="display: <?php echo ($key==0)?'block;':'none;'; ?>" id="<?php echo $data['doctor_id']; ?>"  class="main_div mu-hero-featured-area">
        <!-- Start center Logo -->
        <div class="mu-logo-area">
            <!-- text based logo -->
            <a class="mu-logo" href="#"><?php echo $data['doctor_name']; ?></a>
        </div>
        <!-- End center Logo -->
        <div class="mu-event-counter-area">
            <div id="mu-event-counter">
                <span class="mu-event-counter-block">
                    <span style="<?php echo($data['token_number'] == "0")?'font-size:100px':''; ?>"><?php echo $data['token_number']; ?></span>
                    <p class="stat"><?php echo $data['file_status']; ?></p>
                </span>
            </div>
        </div>
        <div class="mu-hero-featured-content">

            <h1><?php echo $data['patient_name']; ?></h1>
            <p class="mu-event-date-line"><?php echo date('d F, Y'); ?></p>
        </div>


    </div>
    <?php } ?>


<?php } else{  ?>
    <h1 style="text-align: center;"> There is no appointment available </h1>
<?php } ?>

