<h1>Blog</h1>

<?php if (count($this->posts)): ?>
    <?php foreach ($this->posts as $post): ?>

        <div class="container">
            <h3>
                <a href="<?= URL . 'posts/show/' . $post->slug; ?>">
                    <?= $post->title; ?>
                </a>

                <small class="h6 d-inline italic ml-2">
                    <em>
                        <?= Helper::getDate($post->created_at); ?>
                    </em>
                </small>
            </h3>

            <p>
                <?= $post->body; ?>
            </p>

            <a href="<?= URL . 'posts/show/' . $post->slug; ?>" class="text-primary pull-right">
                Read More
            </a>
        </div>

    <?php endforeach; ?>
<?php else: ?>

<h4>There are no posts yet. Create some!</h4>

<?php endif; ?>

<?= $this->pagination; ?>
