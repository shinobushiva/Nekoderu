<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $catImage->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $catImage->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Cat Images'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Cats'), ['controller' => 'Cats', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Cat'), ['controller' => 'Cats', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="catImages form large-9 medium-8 columns content">
    <div><img src="<?= $catImage->url ?>" width="200px"></img></div>
    <div><?=$this->Form->postLink('Rotate 90 Right', ['action' => 'rotate', $catImage->id])?></div>
    <?= $this->Form->create($catImage) ?>
    <fieldset>
        <legend><?= __('Edit Cat Image') ?></legend>
        <?php
            echo $this->Form->input('url');
            echo $this->Form->input('cats_id', ['options' => $cats]);
            echo $this->Form->input('users_id');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
