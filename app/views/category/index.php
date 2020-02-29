<?php $this->setSiteTitle('Home'); ?>
<?php $this->start('head'); ?>
<?php $this->end(); ?>

<?php $this->start('body'); ?>
<h1>Category</h1>
<h1>Category</h1>

<table class="table table-hover table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Slug</th>
            <th>Deleted</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($this->categories)): ?>
            <?php foreach ($this->categories as $category): ?>
            <tr>
                <td>
                   	<a href="<?= URL . 'category/show/' . $category->slug; ?>">
                    	<?= $category->id ?>
                    </a>
                </td>
                <td>
                    <?= $category->name ?>
                </td>
                <td>
                    <?= $category->slug ?>
                </td>
                <td>
                    <?= $category->deleted ?>
                </td>
                <td>
					<a href="<?= URL . 'posts/edit/' . $post->slug; ?>" class="btn btn-sm btn-primary">Edit</a>
					<a href="<?= URL . 'posts/delete/' . $post->slug; ?>" class="btn btn-sm btn-danger">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<?php $this->end(); ?>
