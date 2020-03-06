<form method="post">
    <?= HTML::inputBlock(['type' => 'text', 'id' => 'username', 'name' => 'username', 'class' => 'form-control'],
        ['text' => 'Username', 'class' => 'form-group']); ?>
    <?= HTML::inputBlock(['type' => 'email', 'id' => 'email', 'name' => 'email', 'class' => 'form-control'],
        ['text' => 'Email', 'class' => 'form-group']); ?>
    <?= HTML::textareaBlock(['id' => 'message', 'name' => 'message', 'rows' => '10', 'class' => 'form-control'],
        ['text' => 'Comment', 'class' => 'form-group']); ?>
    <?= HTML::hidden(['name' => 'post_id', 'value' => $this->post->id]); ?>
    <?= HTML::hidden(['name' => 'parent_id', 'value' => '']); ?>
    <?= HTML::submit(['value' => 'Create', 'class' => 'btn btn-block btn-primary']); ?>
    <?= HTML::reset(['value' => 'Clear', 'class' => 'btn btn-block btn-danger']); ?>
</form>