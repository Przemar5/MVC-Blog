<?php $this->setSiteTitle('Category: ' . $this->category->name); ?>
<?php $this->start('head'); ?>
<?php $this->end(); ?>

<?php $this->start('body'); ?>
<h1>Category: 
    <?= $this->category->name; ?>
</h1>

<h4>
	<strong>Posts:</strong> <?= $this->category->numOfPosts; ?>
</h4>
        
<div class="pull-right">
	<a href="<?= URL . 'category/edit/' . $this->category->slug; ?>" class="btn btn-sm btn-primary">Edit</a>
	<a href="<?= URL . 'category/delete/' . $this->category->slug; ?>" class="btn btn-sm btn-danger">Delete</a>
</div>

<div class="container">
	<?php $this->partial('posts', 'posts'); ?>
</div>

<?php $this->end(); ?>
