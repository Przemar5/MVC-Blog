<form method="post" class="m-5 pt-5">
    <?= HTML::inputBlock(['type' => 'text', 'id' => 'title', 'name' => 'title', 'value' => $this->post->title, 'class' => 'form-control'],
        ['text' => 'Title', 'class' => 'form-group']); ?>
    <?= HTML::inputBlock(['type' => 'text', 'id' => 'label', 'name' => 'label', 'value' => $this->post->label, 'class' => 'form-control'],
        ['text' => 'Label', 'class' => 'form-group']); ?>
    <?= HTML::inputBlock(['type' => 'text', 'id' => 'slug', 'name' => 'slug', 'value' => $this->post->slug, 'class' => 'form-control'],
        ['text' => 'Slug', 'class' => 'form-group']); ?>
    <?= HTML::selectBlock(['id' => 'category_id', 'name' => 'category_id', 'class'  => 'form-control', 'data' => $this->categories,
        'options' => ['value' => 'id', 'text' => 'name']],
        ['text' => 'Category', 'class' => 'form-group']); ?>
    <?= HTML::inputBlock(['type' => 'text', 'id' => 'tags', 'name' => 'tags', 'value' => $this->post->tags, 'class' => 'form-control'],
        ['text' => 'Tags', 'class' => 'form-group']); ?>
    <?= HTML::textareaBlock(['id' => 'body', 'name' => 'body', 'rows' => '10', 'text' => $this->post->body, 'class' => 'form-control'],
        ['text' => 'Post Body', 'class' => 'form-group']); ?>
    <?= HTML::submit(['value' => 'Create', 'class' => 'btn btn-block btn-primary']); ?>
    <?= HTML::reset(['value' => 'Clear', 'class' => 'btn btn-block btn-default']); ?>
</form>