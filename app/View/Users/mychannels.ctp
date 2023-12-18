<div>
    <?php echo $this->Html->link('Add Channel',array('controller'=>'channels','action'=>'add'),array('style'=>'color:blue')); ?> ||
    <?php echo $this->Html->link('View Channel',array('controller'=>'channels'),array('style'=>'color:blue')); ?>
</div>
<table>
    <thead>
        <tr>
            <th>Channel name</th>
            <!--<th>Channel desc</th>-->
            <th>View Subscribers</th>
            <th>Created</th>
        </tr>
    </thead>
    <tbody>
        <?php if(!empty($channels)){ ?>
            <?php foreach($channels as $channel){ ?>
            <tr>
                <td><?php echo $channel['Channel']['channel_name']; ?></td>
                <!--<td><?php echo $channel['Channel']['channel_desc']; ?></td>-->
                <td><?php echo $this->Html->link('Subscribers',array('controller'=>'users','action'=>'subscribers',$channel['Channel']['id']),array('style'=>'color:blue'));?>(<?php echo $this->Custom->GetSubscribersCount($channel['Channel']['id']); ?>)</td>
                <td><?php echo $channel['Channel']['created']; ?></td>
            </tr>
            <?php } ?>
        <?php }else{ ?>
        <tr>
            <td> not any channel found</td>
            <td></td>
        </tr>
        <?php } ?>
    </tbody>
</table>

    <!-- paging  start -->
    <table border="0" cellpadding="0" cellspacing="0" id="paging-table" >
        <tr>
            <td><!--<a href="" class="page-far-left"></a>-->
            <div style="float:left">
              <?php
                  echo $this->Paginator->first('First', array('escape'=>false), null, array('class'=>'prev page-far-left disabled', 'escape'=>false));
                  echo $this->Paginator->prev('Prev', array('escape' => false), null, array('class' => 'prev disabled', 'escape'=>false)); 
              ?>
            </div>
            <div id="page-info" style="color:#000;">
            <?php
                  echo $this->Paginator->counter(array('format' => __('Page {:page} of {:pages}')));
            ?>
            </div>
            <div style="float:right">
            <?php
                  echo $this->Paginator->next('Next', array('escape' => false), null, array('class' => 'next disabled', 'escape'=>false));
                  echo $this->Paginator->last('Last', array('escape' => false), null, array('class' => 'next disabled', 'escape'=>false)); 
            ?>
            </div>
            </td>
        </tr>
    </table>
    <!-- paging  end -->