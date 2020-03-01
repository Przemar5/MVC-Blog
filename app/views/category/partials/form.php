<form method="post" class="m-5 pt-5">
    <?= HTML::inputBlock(['type' => 'text', 'id' => 'name', 'name' => 'name', 'value' => $this->category->name ?? '', 'class' => 'form-control'],
        ['text' => 'Name', 'class' => 'form-group']); ?>
    <?= HTML::inputBlock(['type' => 'text', 'id' => 'slug', 'name' => 'slug', 'value' => $this->category->slug ?? '', 'class' => 'form-control'],
        ['text' => 'Slug', 'class' => 'form-group']); ?>
    <?= HTML::submit(['value' => $this->submitButtonValue ?? 'Submit', 'class' => 'btn btn-block btn-primary']); ?>
    <?= HTML::reset(['value' => 'Clear', 'class' => 'btn btn-block btn-danger']); ?>
</form>