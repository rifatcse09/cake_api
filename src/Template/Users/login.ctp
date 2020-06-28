<?= $this->Flash->render() ?> 
<?= $this->Form->create() ?>
<?= $this->Form->control('emailaddress', ['type' => 'email']) ?> 
<?= $this->Form->control('password') ?>
<?= $this->Form->button(__('Login'), ['class' => 'btn btn-info']); ?> 
<?= $this->Form->end() ?>