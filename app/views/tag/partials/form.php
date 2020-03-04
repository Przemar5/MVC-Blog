<form method="post" class="m-5 pt-5">
    <?= HTML::inputBlock(['type' => 'text', 'id' => 'name', 'name' => 'name', 'value' => $this->tag->name ?? '', 'class' => 'form-control'],
        ['text' => 'Name', 'class' => 'form-group']); ?>
    <?= HTML::submit(['value' => $this->submitButtonValue ?? 'Submit', 'class' => 'btn btn-block btn-primary']); ?>
    <?= HTML::reset(['value' => 'Reset', 'class' => 'btn btn-block btn-danger']); ?>
</form>