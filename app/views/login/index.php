<?php $this->setSiteTitle('Home'); ?>
<?php $this->start('head'); ?>
<?php $this->end(); ?>

<?php $this->start('body'); ?>

<div class="container my-5">
	<div class="col-md-8 offset-md-2 card px-3 py-4 shadow">
        <?= HTML::errors($this->errors); ?>

		<h1 class="text-center">Login</h1>
		
		<form action="<?= URL; ?>login/verify" method="post" class="card-body">
			<?= HTML::inputBlock(['type' => 'text', 'id' => 'username', 'name' => 'username', 'class' => 'form-control'],
								   ['text' => 'Username', 'class' => 'form-group']); ?>
			<?= HTML::inputBlock(['type' => 'password', 'id' => 'password', 'name' => 'password', 'class' => 'form-control'],
								   ['text' => 'Password', 'class' => 'form-group']); ?>
			<?= HTML::submit(['value' => 'Login', 'class' => 'btn btn-block btn-primary']); ?>
		</form>
	</div>
</div>

<?php $this->end(); ?>