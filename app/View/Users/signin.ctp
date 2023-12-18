<?php echo $this->Html->script(array('jquery.validationEngine.js','jquery.validationEngine-en.js')); ?>
<?php echo $this->Html->css(array('validationEngine.jquery')); ?>
<div class="mid_content">
    <div class="login-content">
        <div class="warrper">
            <h1>Text anywhere, anytime and on any device!</h1>
            <p>Send and receive all your texts on your notebook, desktop computer or tablet ? <br />just like on your smartphone.</p>
            <?php echo $this->Session->flash("front_error"); ?>
            <?php echo $this->Session->flash("front_success"); ?>
            <div class="login-form">
            <?php echo $this->Form->create('User',array('url'=>array('controller'=>'users','action'=>'login'),'id'=>'login_form','inputDefaults'=>array('required'=>false))); ?>
                <!--<div class="countryListBox">
                    <?php //echo $this->Form->input('location',array('options'=>$locations,'label'=>false,'id'=>'location','class'=>'list validate[required]')); ?>
                    <div id="c_flag" class="icon">
                        <?php //echo $this->Html->image('india-map-icon.png',array('alt'=>'Letout','class'=>'')); ?>
                    </div>
                    <div class="dropdown"></div>
                    <div class="info2" id="info2">+91 (India)</div>
                    <div class="info" style="display: none;" aria-hidden="true"></div>
                </div>-->
                <?php echo $this->Form->input('username',array('type'=>'text','placeholder'=>'Name','label'=>false,'class'=>'validate[required]')); ?>
                <?php //echo $this->Form->input('email',array('placeholder'=>'Email Address','label'=>false)); ?>
                <?php echo $this->Form->input('mobile',array('type'=>'text','placeholder'=>'Mobile','label'=>false,'class'=>'validate[required,custom[phone]]')); ?>
                <?php echo $this->Form->input('password',array('placeholder'=>'Password','label'=>false,'class'=>'validate[required]')); ?>
                <?php echo $this->Form->submit('Register',array('class'=>'login-submit-btn')); ?>
                <?php //echo $this->Form->submit('Sign up'); ?>
            <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $("#UserMobile").intlTelInput({
            autoFormat: true,
            autoHideDialCode: false,
            defaultCountry: "in",
            //nationalMode: true,
            //numberType: "MOBILE",
            //onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
            preferredCountries: ['in','us'],
            responsiveDropdown: true,
            //utilsScript: "<?php echo SITE_URL."js/utils.js"; ?>"
            //utilsScript: "js/utils.js"
        });
        /*
        $("#login_form").validationEngine();
        $("#location").change(function(){
            var s_val = this.value;
            if(s_val == 'in'){
                var c_htm = '<?php echo $this->Html->image('india-map-icon.png',array('alt'=>'Letout','class'=>'')); ?>';
            }else{
                var c_htm = s_val.toUpperCase();
            }
            $('#info2').html(this.options[this.selectedIndex].innerHTML);
            $('#c_flag').html(c_htm);
        });
        */
    });
</script>