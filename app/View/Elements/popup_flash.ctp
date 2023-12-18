<?php echo $this->Html->script(array('jquery.fancybox')); ?>
<?php echo $this->Html->css(array('jquery.fancybox')); ?>
<script type="text/javascript">
    var auto_close = "<?php echo $auto_close; ?>";
    $(function(){
        $.fancybox({
            'href' :"#popupFlashDiv",
            //'height' : 10,
            'effect' : false,
            afterLoad:function(){
                if(auto_close == "Y")
                {
                    setTimeout(function(){
                        $.fancybox.close();
                    },3000);
                }
            }
        });
        $(window).scroll(function(){$.fancybox.update();});
    });
</script>
<div id="popupFlashDiv" class="<?php echo $type=="success"?"popupSuccessMsg":"popupErrorMsg"; ?>" >
    <?php
        echo $message;
        unset($_SESSION["Message"]["flash_error"], $_SESSION["Message"]["popup_flash"]);
    ?>
</div>