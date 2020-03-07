<div id="comments">
    <?php if (!empty($this->comments) && count($this->comments)): ?>
        <?php foreach ($this->comments as $comment): ?>

            <div class="card my-4">
                <div class="card-body">
                    <h3>
                        <?= $comment->username; ?>
                        <small class="h6 d-inline italic ml-2">
                            <em>
                                <?= $comment->email; ?>,
                                created at <?= Helper::getDate($comment->created_at); ?>
                            </em>
                        </small>
                    </h3>

                    <h2><?= $comment->id; ?></h2>

                    <p>
                        <?= $comment->message; ?>
                    </p>

                    <a href="<?= URL . 'posts/show/' . $comment->id; ?>" class="text-primary pull-right">
                        Read More
                    </a>
                </div>
            </div>

        <?php endforeach; ?>
    <?php else: ?>

    <h4>There are no comments yet. Create one!</h4>

    <?php endif; ?>
</div>

<?php if (!empty($this->comments)): ?>
	<?= $this->loadMore; ?>
<?php endif; ?>