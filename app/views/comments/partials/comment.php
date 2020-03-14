<div class="card my-4 comment">
    <div class="card-body">
        <h3>
            <?= $comment->username; ?>
            <small class="h6 d-inline italic ml-2">
                <em>
                    <?= $comment->email; ?>,
                    created at <?= Helper::getDate($comment->created_at); ?>
                </em>
            </small>

            <div class="pull-right">
                <a href="<?= URL . 'comments/edit/' . $comment->id; ?>" class="btn btn-sm btn-primary">Edit</a>
                <a href="<?= URL . 'comments/delete/' . $comment->id; ?>" class="btn btn-sm btn-danger">Delete</a>
            </div>
        </h3>

        <h2><?= $comment->id; ?></h2>

        <p>
            <?= $comment->message; ?>
        </p>

        <?php if (!empty($comment->subcomments) && count($comment->subcomments)): ?>
            <?php foreach ($comment->subcomments as $comment): ?>
                <?php include(__DIR__ . DS . 'comment.php'); ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>