<?php $this->setSiteTitle('Home'); ?>
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
                    <?= $post->id ?>
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
                    <?= $post->category ?>
                </td>
                <td>

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

                </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<?php $this->end(); ?>