<div class="Inner-banner">
    <div class="container">
        <div class="row">
       
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Constant List</h2> </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 pull-right create-btn-box">
            </div>
        </div>
    </div>
</div>
<section class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">
                <div class="middle-block">
                    <?php echo $this->element('admin_leftsidebar'); ?>
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">


                        <div class="Social-login-box payment_bx">
                            <?php echo $this->element('message'); ?>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <div class="table-responsive">
                                        <form method="post">

                                            <table class="table">
                                                <thead>
                                                <th>#</th>
                                                <th>Key</th>
                                                <th>Value</th>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $num = 1;
                                                foreach ($constants as $key => $constant){
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $num++; ?></td>
                                                        <td><?php echo $constant['Constant']['key']; ?></td>
                                                        <td><input type="text" name="data[<?php echo $constant['Constant']['key']; ?>]" value="<?php echo $constant['Constant']['value']; ?>" class="form-control cnt"></td>
                                                    </tr>
                                                <?php } ?>
                                                </tbody>
                                            </table>

                                            <div class="form-group">
                                                <div class="col-sm-3 pull-right">
                                                   <input type="submit" class='Btn-typ5' value="Update">
                                                </div>
                                            </div>
                                        </form>


                                    </div>
                                </div>
                            </div>

                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>