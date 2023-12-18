<?php
echo $this->Html->script(array(
    'jquery.js',
    'html5shiv.js',
    'respond.min.js',
    'jquery_ui/jquery-ui.min.js',
    'bootstrap.min.js',
    'bootstrap-tagsinput.min.js',
    'jquery.isotope.min.js',
    'bootstrap-datepicker.min.js',
    'jquery-confirm.min.js',
    'jquery.timepicker.min.js',
    'moment.js',
    'bootstrap-datetimepicker.min.js',
	'jquery.tablednd.0.7.min.js',
	'loader.js',
	'ckeditor/ckeditor.js',
	'angular.min.js',
	'intlTelInput.min.js',
	'dropzone.min.js',
	'jquery.inlineStyler.min.js',
	'jquery.maskedinput-1.2.2-co.min.js',
	'comman.js'
),array('fullBase' => true));
?>
<?php if( !in_array($this->request->params['controller'],array('cms_body','cms_theme','cms'))  &&  !in_array($this->request->params['action'],array('cms_body','discharge_patient','manage'))){ ?>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB2yqFQyH4TumEEkM3o_6KSgoYDN_3w94A&v=3.exp&libraries=places"></script>
<?php } ?>
