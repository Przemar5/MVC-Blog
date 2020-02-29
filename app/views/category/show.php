<?php $this->setSiteTitle('Home'); ?>
<?php $this->start('head'); ?>
<?php $this->end(); ?>

<?php $this->start('body'); ?>
<h1>Category</h1>
<h1>Category</h1>

<div class="container">
	<div>
		<strong>ID:</strong>
		<?= $this->category->id; ?>
	</div>
	<div>
		<strong>Name:</strong>
		<?= $this->category->name; ?>
	</div>
	<div>
		<strong>Slug:</strong>
		<?= $this->category->slug; ?>
	</div>
	<div>
		<strong>Posts:</strong>
		<?= $this->category->id; ?>
	</div>
</div>

<?php $this->end(); ?>
