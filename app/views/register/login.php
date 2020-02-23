<?php $this->setSiteTitle('Home'); ?>
<?php $this->start('head'); ?>
<?php $this->end(); ?>

<?php $this->start('body'); ?>
<h1>Login</h1>

<?php HTML::inputBlock(['type' => 'text', 'id' => 'username', 'class' => 'form-control'], 
					   ['text' => 'Username', 'class' => 'form-group']); ?>
<?php HTML::inputBlock(['type' => 'password', 'id' => 'password', 'class' => 'form-control'], 
					   ['text' => 'Password', 'class' => 'form-group']); ?>

<?php $this->end(); ?>