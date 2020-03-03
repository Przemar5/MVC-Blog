<?php $this->setSiteTitle('Categories'); ?>
<?php $this->start('head'); ?>

<?php $this->end(); ?>

<?php $this->start('body'); ?>
<h1>Tags</h1>
<h1>Tags
	<a href="<?= URL . 'tag/create'; ?>" class="btn btn-primary float-right">Create</a>
</h1>
<table class="table table-hover table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>NO. of posts</th>
            <th>Deleted</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($this->tags)): ?>
            <?php foreach ($this->tags as $tag): ?>
            <tr>
                <td>
                   	<a href="<?= URL . 'posts/index?page=1&tag[]=' . $tag->name; ?>">
                    	<?= $tag->id ?>
                    </a>
                </td>
                <td>
                    <?= $tag->name ?>
                </td>
                <td>
                    <?= $tag->numOfPosts ?>
                </td>
                <td>
                    <?= $tag->deleted ?>
                </td>
                <td>
					<a href="<?= URL . 'tag/edit/' . $tag->name; ?>" class="btn btn-sm btn-primary">Edit</a>
					<a href="<?= URL . 'tag/delete/' . $tag->name; ?>" class="btn btn-sm btn-danger">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<?php $this->end(); ?>
