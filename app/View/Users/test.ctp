<?php echo $this->Form->create('Spec'); ?>
<fieldset>
    <legend><?php echo __('Add Spec'); ?></legend>
<?php
    echo $this->Form->input('ref');
    echo $this->Form->input('service_id',array('empty'=>'Please Select'));

    echo $this->Form->input('a1',array(
                                                        'label' => 'Background:',
                                                        'div' => false
                                                    ));
    echo $this->Form->input('a2',array(
                                                        'label' => 'Business objectives:',
                                                        'div' => false
                                                    ));