<?php $this->setSiteTitle('Dashboard'); ?>
<?php $this->start('head'); ?>
<?php $this->end(); ?>

<?php $this->start('body'); ?>

<h2>Dashboard</h2>

<table class="table table-hover table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Slug</th>
            <th>Label</th>
            <th>Category</th>
            <th>Tags</th>
            <th>created at</th>
            <th>Updated at</th>
            <th>Deleted</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($this->posts)): ?>
            <?php foreach ($this->posts as $post): ?>
            <tr>
                <td>
                   	<a href="<?= URL . 'posts/show/' . $post->slug; ?>">
                    	<?= $post->id ?>
                    </a>
                </td>
                <td>
                    <?= $post->title ?>
                </td>
                <td>
                    <?= $post->slug ?>
                </td>
                <td>
                    <?= $post->label ?>
                </td>
                <td>
                    <?= $post->category->name ?>
                </td>
                <td>
					<?= $post->tagsString ?>
                </td>
                <td>
                    <?= $post->created_at ?>
                </td>
                <td>
                    <?= $post->updated_at ?>
                </td>
                <td>
                    <?= $post->deleted ?>
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