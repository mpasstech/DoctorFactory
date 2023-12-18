<?php $span = isset($span) ? $span : 8; ?>
<?php $page = isset($this->request->params['named']['page']) ? $this->request->params['named']['page'] : 1; ?>
<?php 
$pass = $this->params->pass;
$pass = implode($pass,"/");
?>


<div class="pagination_main" >
    <div class="total_record">
        <?php
        echo $this->Paginator->counter(
            'Showing page {:page} of {:pages},  Total Record {:count}'
        );
        ?>
    </div>
    <ul class="pagination">
        <?php echo $this->Paginator->prev(
            '&larr; ' . __('Previous'),
            array(
                'escape' => false,
                'tag' => 'li'
            ),
            '<a onclick="return false;">&larr; Previous</a>',
            array(
                'class'=>'disabled prev',
                'escape' => false,
                'tag' => 'li'
            )
        );?>

        <?php $count = $page + $span; ?>
        <?php $i = $page - $span; ?>
        <?php while ($i < $count): ?>
            <?php $options = ''; ?>
            <?php if ($i == $page): ?>
                <?php $options = ' class="active"'; ?>
            <?php endif; ?>
            <?php if ($this->Paginator->hasPage($i) && $i > 0): ?>
                <li<?php echo $options; ?>><?php echo $this->Html->link($i, array($pass,"page" => $i, '?' => $this->request->query)); ?></li>
            <?php endif; ?>
            <?php $i += 1; ?>
        <?php endwhile; ?>

        <?php echo $this->Paginator->next(
            __('Next') . ' &rarr;',
            array(
                'escape' => false,
                'tag' => 'li'
            ),
            '<a onclick="return false;">Next &rarr;</a>',
            array(
                'class' => 'disabled next',
                'escape' => false,
                'tag' => 'li'
            )
        );?>
    </ul>
</div>