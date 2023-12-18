<div class="mid-contant">
    <div class="mid-contant-inner">
        <div class="inwarrper">
            <div>
                <div class="news-top">
                    <br/>
					<h1>We would love to hear from you</h1>
                    <div class="contact_form">
                        <?php echo $this->Session->flash(); ?>
                        <?php
                            echo $this->Form->create('Inquiry',array('url'=>array('controller'=>'Inquiry','action'=>'index'),'inputDefaults'=>array('label'=>false,'required'=>false),'id'=>'update_mobile_form','enctype'=>'multipart/form-data'));
                            echo $this->Form->input('name',array('placeholder' => 'Name'));
                            echo $this->Form->input('email',array('placeholder' => 'Email Address'));
                            echo $this->Form->input('phone',array('type' => 'text','placeholder' => 'Phone No'));
                            echo $this->Form->textarea('message',array('placeholder' => 'Your message'));
                            echo $this->Form->submit('Add',array('class'=>'alt_btn','type'=>'submit','id'=>'up_mobile_number'));
                            echo $this->Form->end();
                        ?>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
    </div>
</div>


