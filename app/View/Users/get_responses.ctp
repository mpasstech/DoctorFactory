   <div id="flip-scroll">
	<table cellpadding="0" cellspacing="0">
	    <thead >
		<tr>
		    <th>Channel</th>
		    <th>Name</th>
		    <th class="numeric">Mobile</th>
		    <th class="numeric">Message</th>
		    <th class="numeric">Time</th>
		</tr>
	    </thead>
	    <tbody>
		<?php if(isset($data) && !empty($data)) { ?>
		    <?php foreach($data as $response) { ?>
			<tr>
			    <td><?php echo $response['Channel']['channel_name']; ?></td>
			    <td class="numeric"><?php echo $response['Subscriber']['name']; ?></td>
			    <td class="numeric"><?php echo $response['Subscriber']['mobile']; ?></td>
			    <td class="numeric"><?php echo $response['ChannelResponse']['response']; ?></td>
			    <td class="numeric"><?php echo date('d-M-Y h:i A',strtotime($response['ChannelResponse']['created'])); ?></td>
			</tr>
		    <?php } ?>
		<?php }else{ ?>
		<tr>
		    <td colspan="5">No response found!</td>
		</tr>
		<?php } ?>
	    </tbody>
	</table>
    </div>