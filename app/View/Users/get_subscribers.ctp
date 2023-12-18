
<?php if(isset($subscribers) && !empty($subscribers)) { ?>
    <?php foreach($subscribers as $subscriber) { ?>
	<li id="subscriber_<?php echo $subscriber['Subscriber']['id']; ?>">
	    <div class="chat-box"> 
		<p><?php echo $subscriber['Subscriber']['name']; ?></p>
		<a class="del-sub-msg" title="Delete Subscirber" id="delete_subscriber_<?php echo $subscriber['Subscriber']['id']; ?>" href="javascript:void(0)" ><i class="fa fa-remove" style=""></i></a>
		<span><?php echo $subscriber['Subscriber']['mobile']; ?></span>
	    </div>
	    <input type="hidden" id="haveSubs" value="1">
	</li>
    <?php } ?>
<?php }else{ ?>
	<li>
	    <div class="chat-box"> <input type="hidden" id="haveSubs" value="0"><p>No subscribers found!</p></div>
	</li>
<?php } ?>