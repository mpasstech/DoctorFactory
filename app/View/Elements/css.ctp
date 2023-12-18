<?php
echo $this->Html->css(array(
       'bootstrap.min.css',
       'bootstrap-theme.min.css',
       'bootstrap-tagsinput.css',
       'font-awesome.min.css',
       'bootstrap-datetimepicker.min.css',
       'jquery-confirm.min.css',
       'style.css',
       'responsive.css',
       "custom.css?".date('Ymdhis'),
       'bootstrap-datepicker.min.css',
       'jquery.timepicker.css',
       'intlTelInput.css',
       'dropzone.min.css',
       'jquery_ui/jquery-ui.min.css'
    ),array("media"=>'all','fullBase' => true));
?>