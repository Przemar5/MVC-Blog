<form action="<?= $this->formAction; ?>" method="post" class="m-5 pt-5">
    <?= HTML::inputBlock(['type' => 'text', 'id' => 'title', 'name' => 'title', 'class' => 'form-control'],
        ['text' => 'Title', 'class' => 'form-group']); ?>
    <?= HTML::inputBlock(['type' => 'text', 'id' => 'label', 'name' => 'label', 'class' => 'form-control'],
        ['text' => 'Label', 'class' => 'form-group']); ?>
    <?= HTML::inputBlock(['type' => 'text', 'id' => 'slug', 'name' => 'slug', 'class' => 'form-control'],
        ['text' => 'Slug', 'class' => 'form-group']); ?>
    <?= HTML::selectBlock(['id' => 'category_id', 'name' => 'category_id', 'class'  => 'form-control', 'data' => $this->categories,
        'options' => ['value' => 'id', 'text' => 'name']],
        ['text' => 'Category', 'class' => 'form-group']); ?>
    <?= HTML::inputBlock(['type' => 'text', 'id' => 'tags', 'name' => 'tags', 'class' => 'form-control'],
        ['text' => 'Tags', 'class' => 'form-group']); ?>
    <?= HTML::textareaBlock(['id' => 'body', 'name' => 'body', 'rows' => '10', 'class' => 'form-control'],
        ['text' => 'Post Body', 'class' => 'form-group']); ?>
    <?= HTML::submit(['value' => 'Login', 'class' => 'btn btn-block btn-primary']); ?>
</form>