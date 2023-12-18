<div class="content">

    <?php if(!empty($data)){ ?>


        <img  class ="logo_img" src="<?php echo Router::url('/images/logo.png'); ?>">
        <div class="doctor_section">
            <label>Appointment Tracker</label>
        </div>
        <h1 class="app_header">
            <?php if(!empty($data)){ ?>
                <img  class ="doctor_image" src="<?php echo $data['doctor_image']; ?>">
            <?php } ?>
            <?php echo @$data['app_name']; ?>

            <div class="time"></div>

        </h1>
        <div class="row interval">
            <div class="col-2">
                <header>
                    <h5>Start</h5>
                </header>
                <p> <?php echo $data['doctor_start_time']; ?></p>


            </div>
            <div class="col-3">

                <header>
                    <h5></h5>
                </header>
                <p> </p>
            </div>

            <div class="col-2 crt_tok_col">

                <header>
                    <h5 class="current_token">&nbsp;</h5>
                </header>
                <p> <?php echo $data['current_token_number']; ?> </p>

            </div>
            <div class="col-3">

                <header>
                    <h5></h5>
                </header>
                <p> </p>
            </div>
            <div class="col-2">
                <header>
                    <h5>End</h5>
                </header>
                <p> <?php echo $data['doctor_end_time']; ?></p>

            </div>
        </div>

        <div class="row">
            <div class="col-4">
                <header>
                    <i class="icon fa-user"></i>
                    <h3>Patient Name</h3>
                </header>
                <h2><?php echo $data['patient_name']; ?></h2>

            </div>
            <div class="col-4 center_div">
                <header>
                    <i class="icon fa-arrow-up"></i>
                    <h3>Current Token</h3>
                </header>

            </div>
            <div class="col-4">
                <header>
                    <i class="icon fa-clock-o"></i>
                    <h3>Approx Time</h3>
                </header>
                <h2><?php echo $data['time_slot']; ?></h2>
            </div>
        </div>
    <?php } else{  ?>
        <h1>There is no appointment available</h1>
    <?php } ?>

</div>