
<?php if(isset($messges) && !empty($messges)) { ?>
    <?php foreach($messges as $key=>$messge) { ?>
	<li>
	    <div class="chat-box">
		<?php if($messge['Message']['type'] == 'image'){ ?>
		    <p>
			<a class="fancybox_img_msg" rel="gallery1" href="<?php echo $messge['Message']['image']; ?>">
			    <img src="<?php echo $messge['Message']['image']; ?>" width="50px">
			</a>
		    </p>
		<?php }elseif($messge['Message']['type'] == 'audio'){ ?>
		    <p>
			<div id="mediaplayer_<?php echo $key; ?>"></div>
			<?php echo $this->Html->script(array('jwplayer')); ?>
			<script type="text/javascript">
			    $(document).ready(function(){
				jwplayer("mediaplayer_<?php echo $key; ?>").setup({
				    flashplayer: "<?php echo SITE_URL; ?>js/player.swf",
				    file : "<?php echo $messge['Message']['image']; ?>" ,
				    image: "<?php echo SITE_URL."/img/audio.png"; ?>",
				    height: 200,
				    width: 300,
				    sharing: false
				});
			    });
			</script>
		    </p>
		<?php }elseif($messge['Message']['type'] == 'video'){  ?>
		    <p>
			<div id="mediaplayer_<?php echo $key; ?>"></div>
			<?php echo $this->Html->script(array('jwplayer')); ?>
			<script type="text/javascript">
			    $(document).ready(function(){
				jwplayer("mediaplayer_<?php echo $key; ?>").setup({
				    flashplayer: "<?php echo SITE_URL; ?>js/player.swf",
				    file : "<?php echo $messge['Message']['image']; ?>" ,
				    image: "<?php echo $messge['Message']['thumb_url']; ?>" ,
				    height: 200,
				    width: 300,
				    sharing: false
				});
			    });
			</script>
		    </p>
		<?php }else{ ?>
		    <p><?php echo $messge['Message']['message']; ?></p>
		<?php } ?>
		<a class="del-sub-msg msg_history" title="View Message History"  href="<?php echo SITE_URL."users/msg_history/".$messge['Message']['id']; ?>" >
		    <i class="fa fa-history" style=""></i>
		</a>
		<span><?php echo date('d-M-Y h:i A',strtotime($messge['Message']['created'])); ?></span>
	    </div>
	</li>
	<input type="hidden" id="haveMsg" value="1">
    <?php } ?>
<?php }else{ ?>
	<li>
	    <div class="chat-box"> <p>Not any message found.<input type="hidden" id="haveMsg" value="0"></p></div>
	</li>
<?php } ?>