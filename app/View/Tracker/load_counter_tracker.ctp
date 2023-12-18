<div class="row load_list">
    <?php foreach ($tracker_data as $key => $data){ ?>
    <div class="col-6" style="border-right: 2px solid; height: 100%;margin-bottom: 1rem; ">
        <div class="row" style="">
            <div class="col-12" style=""><h1 style="text-align: center;width: 100%;"><?php echo $key; ?></h1></div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-6" style="background: #00ff4e;"><h1 style="text-align: center;width: 100%;">Counter</h1></div>
                    <div class="col-6" style="background: #00ff4e;"><h1 style="text-align: center;width: 100%;">Token</h1></div>
                </div>
                <?php foreach ($data as $key_1 => $list){ ?>
                    <div class="row">
                        <div class="col-6"><h1 style="text-align: center;width: 100%;"><?php echo $list['counter']; ?></h1></div>
                        <div class="col-6"><h1 style="text-align: center;width: 100%;"><?php echo $list['token']; ?></h1></div>
                     </div>
                <?php } ?>
            </div>

        </div>
    </div>
    <?php } ?>
</div>



