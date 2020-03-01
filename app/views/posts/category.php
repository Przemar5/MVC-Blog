<?php $this->setSiteTitle('Category: ' . $this->category->name); ?>
<?php $this->start('head'); ?>
<?php $this->end(); ?>

<?php $this->start('body'); ?>
<h1>Category</h1>
<div class="container">
	<div class="p-3">
		<h1>Category: 
			<?= $this->category->name; ?>
		</h1>

		<div class="float-right">
			<a href="<?= URL . 'category/edit/' . $this->category->slug; ?>" class="btn btn-sm btn-primary">Edit</a>
			<a href="<?= URL . 'category/delete/' . $this->category->slug; ?>" class="btn btn-sm btn-danger">Delete</a>
		</div>

		<h4>
			<strong>Posts:</strong> <?= $this->category->numOfPosts; ?>
		</h4>
	</div>

	<?php $this->partial('posts', 'posts'); ?>
	
</div>
<?php $this->end(); ?>