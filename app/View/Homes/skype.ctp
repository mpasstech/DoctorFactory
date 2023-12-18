<section class="Home-section-2">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="container">
      <div class="row">
        <h2 class="title1">Schedule your personalized demo</h2>
      </div>
    </div>
  </div>

      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="container">
      <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 hr-line"></div>
          <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
          <div class="Ticket-box">
          <h3>Please fill this form, we will call you soon:</h3>
          <!--p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. <br />
</p-->

                      <?php echo $this->Session->flash('success'); ?>
                      <?php echo $this->Session->flash('error'); ?>
              <div class="clear" style="margin-bottom: 15px;"></div>
              <?php echo $this->Form->create('AppEnquiry',array('type'=>'file','id'=>'login_form','class'=>'form-horizontal')); ?>


              <div class="form-group">
                  <div class="col-sm-12">
                      <?php echo $this->Form->input('name',array('type'=>'text','placeholder'=>'Name','required'=>true,'label'=>false,'id'=>'name','class'=>'form-control cnt')); ?>
                  </div>
              </div>
              <div class="form-group">
                  <div class="col-sm-12">
                      <?php echo $this->Form->input('email',array('type'=>'email','placeholder'=>'Email ID','required'=>true,'label'=>false,'id'=>'email','class'=>'form-control cnt')); ?>
                  </div>
              </div>
              <div class="form-group">
                  <div class="col-sm-12">

                      <?php echo $this->Form->input('phone',array('type'=>'text','placeholder'=>'Phone No.','required'=>true,'label'=>false,'id'=>'phone','class'=>'form-control cnt')); ?>

                  </div>
              </div>
              <div class="form-group">
                  <div class="col-sm-12">

                      <?php echo $this->Form->input('skype_id',array('type'=>'text','placeholder'=>'Skype ID','required'=>true,'label'=>false,'id'=>'skype','class'=>'form-control cnt')); ?>

                  </div>
              </div>
              <div class="form-group">
                  <div class="col-sm-12">
                       <?php echo $this->Form->textarea('message',array('placeholder'=>'Your Notes Here','required'=>true,'label'=>false,'id'=>'notes','class'=>'form-control cnt')); ?>
                  </div>
              </div>
              <div class="form-group">
                  <div class="col-sm-12">
                      <?php echo $this->Form->input('on_date',array('placeholder'=>'Select Date','required'=>true,'readonly'=>'readonly','label'=>false,'id'=>'datePick','class'=>'form-control cnt')); ?>
                  </div>
              </div>
              <div class="form-group">
                  <div class="col-sm-12">
                      <?php echo $this->Form->input('on_time',array('placeholder'=>'Select Time','required'=>true,'label'=>false,'id'=>'timePick','class'=>'form-control cnt')); ?>
                  </div>
              </div>

              <div class="form-group">
                  <div class="col-sm-3 pull-right">
                      <button type="submit" class="Btn-typ3" title="Submit" name="submitSkype">Submit</button>

                  </div>
              </div>

              <div class="form-group">
              </div>
              <?php echo $this->Form->end(); ?>
          </div>
          </div>
          
           <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
          <div class="Faq-box">
          <p><strong>Membership Quires:</strong></p>
          <a href="<?php echo Router::url('/support'); ?>">How to signupin the website?</a><br />
          <a href="<?php echo Router::url('/support'); ?>">I unable to get the confirmation email.</a><br />
          <a href="<?php echo Router::url('/support'); ?>">How to verify account?</a><br />
          <a href="<?php echo Router::url('/support'); ?>">Need more info</a><br />
          
          </div>
          
          <div class="Faq-box">
          <p><strong>Membership Quires:</strong></p>
          <a href="<?php echo Router::url('/support'); ?>">How to signupin the website?</a><br />
          <a href="<?php echo Router::url('/support'); ?>">I unable to get the confirmation email.</a><br />
          <a href="<?php echo Router::url('/support'); ?>">How to verify account?</a><br />
          <a href="<?php echo Router::url('/support'); ?>">Need more info</a><br />
          
          </div>
          
          <div class="Faq-box">
          <p><strong>Membership Quires:</strong></p>
          <a href="<?php echo Router::url('/support'); ?>">How to signupin the website?</a><br />
          <a href="<?php echo Router::url('/support'); ?>">I unable to get the confirmation email.</a><br />
          <a href="<?php echo Router::url('/support'); ?>">How to verify account?</a><br />
          <a href="<?php echo Router::url('/support'); ?>">Need more info</a><br />
          
          </div>
          
         
          
        
          </div>
          
          
      
      </div>
      </div>
      </div>

</section>

<script>
    $(document).ready(function(){
        $('#datePick').datepicker({startDate:new Date(),format: 'dd-M-yyyy',});
        $('#timePick').timepicker();
    });
</script>