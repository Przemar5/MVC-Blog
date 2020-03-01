<?php $this->setSiteTitle('Categories'); ?>
<?php $this->start('head'); ?>

<?php $this->end(); ?>

<?php $this->start('body'); ?>
<h1>Category</h1>
<h1>Category
	<a href="<?= URL . 'category/create'; ?>" class="btn btn-primary float-right">Create</a>
</h1>
<table class="table table-hover table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Slug</th>
            <th>NO. of posts</th>
            <th>Deleted</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($this->categories)): ?>
            <?php foreach ($this->categories as $category): ?>
            <tr>
                <td>
                   	<a href="<?= URL . 'posts/category/' . $category->slug; ?>">
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
                    <?= $category->numOfPosts ?>
                </td>
                <td>
                    <?= $category->deleted ?>
                </td>
                <td>
					<a href="<?= URL . 'category/edit/' . $category->slug; ?>" class="btn btn-sm btn-primary">Edit</a>
					<a href="<?= URL . 'category/delete/' . $category->slug; ?>" class="btn btn-sm btn-danger">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<?php $this->end(); ?>
