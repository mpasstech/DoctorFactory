<?php echo $this->Html->css(array('popup')); ?> 
<div id="flip-scroll">
    <table cellpadding="0" cellspacing="0">
	<thead >
	    <tr>
		<th>Name</th>
		<th>Mobile</th>
                <th>Sent via</th>
		<th class="numeric">Status</th>
		<!--<th class="numeric">Reason</th>-->
	    </tr>
	</thead>
	<tbody>
	    <?php if(isset($data) && !empty($data)) { ?>
		<?php foreach($data as $response) { ?>
		    <tr>
			<td><?php if($response['Subscriber']['name'] != ''){ echo $response['Subscriber']['name']; }else{ echo "No Name"; }?></td>
                        <td><?php if($response['Subscriber']['mobile'] != ''){ echo $response['Subscriber']['mobile']; }else{ echo "No Mobile"; }?></td>
                        <td><?php if($response['MessageQueue']['sent_via'] != ''){ echo $response['MessageQueue']['sent_via']; }else{ echo "Unknown"; }?></td>
                        <td>
                            <?php
                                if($response['MessageQueue']['status'] == 'FAILED'){
                                    if($response['MessageQueue']['reason'] != ''){ $reason = $response['MessageQueue']['reason'];}else{ $reason = 'Unknown';}
                                    echo $response['MessageQueue']['status'].'('.$reason.')';
                                }else{
                                    echo $response['MessageQueue']['status'];
                                }
                            ?>
                        </td>
                    </tr>
		<?php } ?>
	    <?php }else{ ?>
	    <tr>
		<td colspan="5">No history found!</td>
	    </tr>
	    <?php } ?>
	</tbody>
    </table>
</div>