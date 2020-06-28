<?= $this->Form->create($user) ?>
<?= $this->Form->control('email', ['type' => 'email']) ?> 
<?= $this->Form->control('password') ?>
<?= $this->Form->button(__('Submit')); ?> 
<?= $this->Form->end() ?>