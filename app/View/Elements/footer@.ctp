<footer>
    <ul>
        <li><a href="<?php echo SITE_URL."homes/index/#tabs1"; ?>" >HOME</a></li>
        <li><a href="<?php echo SITE_URL."inquiry"; ?>" >CONTACT </a></li>
        <li><a href="<?php echo SITE_URL."docs"; ?>">API DOCUMENTATION</a></li>
        <!--<li><a href="<?php echo SITE_URL."blogs"; ?>">BLOG </a></li>
        <li><a href="<?php echo SITE_URL."homes/index/#tabs2"; ?>" >HOW IT WORKS</a></li>
        <li><a href="<?php echo SITE_URL."homes/index/#tabs3"; ?>" >TEAM</a></li>-->
        
        <?php
            $pages = $this->Custom->Getpages();   //pr($pages); die;
            foreach($pages as $page){
            ?>
                <li><a href="<?php echo SITE_URL."pages/".$page['Page']['slug']; ?>"><?php echo strtoupper($page['Page']['page_title']) ?></a></li>
            <?php }
        ?>
        
        </ul>
</footer>
<script type="text/javascript">
  jQuery("#menu").click(function(){
    jQuery("#clickmenu").slideToggle()
  });
</script>