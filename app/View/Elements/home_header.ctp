<style>#Create-APP-btn, .Btn-typ4{display: none !important;} .navbar-right{margin-top: 20px; font-size: 14px;}</style>

<div class="header">
    <div class="top-bar"> </div>
    <div class="navbar navbar-inverse">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
                <a class="navbar-brand wow bounceInRight " href="<?php echo Router::url('/',true); ?>"><img class="logo" src="<?php echo Router::url('/images/logo.jpg',true); ?>" alt="logo" /></a> </div>
            <div class="collapse navbar-collapse navbar-right wow bounceInLeft ">
                <div class="top-info"><i class="fa-mobile-phone fa"> +91 <?php echo $this->App->supportMobile(); ?></i>&nbsp | <i class="fa-envelope fa"> <a href="mailto:<?php echo $this->App->supportEmail(); ?>"><?php echo $this->App->supportEmail(); ?></a></i>&nbsp; </div>
                <?php  $act = $this->request->params['action']; ?>
                <ul class="nav navbar-nav">
                 </ul>
            </div>
        </div>

    </div>

</div>
