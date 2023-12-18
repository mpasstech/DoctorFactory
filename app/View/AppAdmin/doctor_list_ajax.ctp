
<div class="col-lg-12">
    <div class="card">
        <div class="header">
            <h2><strong>Doctor</strong> List</h2>
        </div>
        <?php if(!empty($list)){ ?>
        <div class="body table-responsive">
            <table class="table table-hover m-b-0 c_list">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Photo</th>
                    <th>Name</th>
                    <th data-breakpoints="xs sm md">Category</th>
                    <th data-breakpoints="xs">Mobile</th>
                    <th data-breakpoints="xs sm md">Timing</th>
                    <th data-breakpoints="xs">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($list as $key =>$doctor){ ?>
                <tr>
                    <td>
                       <?php echo $key+1; ?>
                    </td>
                    <td>
                        <img src="<?php echo $doctor['image']; ?>" class="rounded-circle avatar profile_img" alt="">
                    </td>

                    <td>
                        <p class="c_name"><?php echo $doctor['name']; ?> <span class="badge badge-<?php echo ($doctor['status']=='ONLINE')?'success':'default'; ?> m-l-10 hidden-sm-down"><?php echo $doctor['status']; ?></span></p>
                    </td>
                    <td>
                        <span class="email"><a href="javascript:void(0);"> <i class="zmdi zmdi-home m-r-5"></i> <?php echo $doctor['category']; ?></a></span>
                    </td>
                    <td>
                        <span class="phone"><i class="zmdi zmdi-phone m-r-10"></i><?php echo $doctor['mobile']; ?></span>
                    </td>

                    <td>
                        <?php
                        if(!empty($doctor['time_array'])){
                        foreach ($doctor['time_array'] as $t_key => $time){ ?>
                        <address><i class="zmdi zmdi-time"></i><?php echo $time['time']; ?></address>
                        <?php }}else{ ?>
                            <address> N/A </address>
                        <?php } ?>
                    </td>
                    <td>
                        <button class="btn btn-info btn-icon btn-simple btn-icon-mini btn-round"><i class="fa fa-info"></i></button>
                        <button class="btn btn-<?php echo ($doctor['status']=='ONLINE')?'success':'default'; ?> btn-icon btn-simple btn-icon-mini btn-round load_slot" data-id="<?php echo base64_encode($doctor['doctor_id']); ?>"><i class="fa fa-calendar"></i></button>
                    </td>
                </tr>
                <?php } ?>

                </tbody>
            </table>
        </div>
        <?php } else{ ?>
            <h3> There is no doctor list available.</h3>
        <?php }  ?>
    </div>

</div>




<style>
    .profile_img{height: 40px;}
</style>
