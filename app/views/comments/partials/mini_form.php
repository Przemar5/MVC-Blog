<form method="post">
    <?= HTML::hidden(['name' => 'token', 'value' => $this->comment->token ?? '']); ?>
    <div class="row my-0">
        <div class="col-sm-6 form-group mb-0">
            <label class="w-100">
                <small>Username</small>
                <input type="text" name="username" value="<?= $this->comment->username ?? ''; ?>" class="form-control form-control-sm"/>
                <span class="small help-block">Something may have gone wrong</span>
            </label>
        </div>
        <div class="col-sm-6 form-group mb-0">
            <label class="w-100">
                <small>Email</small>
                <input type="email" name="email" value="<?= $this->comment->email ?? ''; ?>" class="form-control form-control-sm"/>
                <span class="small help-block">Something may have gone wrong</span>
            </label>
        </div>
        <div class="col-sm-12 form-group">
            <label class="w-100">
                <small>Comment</small>
                <textarea name="message" rows="6" class="form-control form-control-sm"></textarea>
                <span class="small help-block">Something may have gone wrong</span>
            </label>
        </div>
    </div>
    <?= HTML::hidden(['name' => 'id', 'value' => $this->comment->id ?? '']); ?>
    <?= HTML::hidden(['name' => 'post_id', 'value' => $this->post->id ?? $this->comment->post_id ?? '']); ?>
    <?= HTML::hidden(['name' => 'parent_id', 'value' => $this->comment->parent_id ?? 0]); ?>
    <?= HTML::submit(['value' => $this->submitButtonValue, 'class' => 'btn btn-block btn-primary']); ?>
    <?= HTML::reset(['value' => 'Clear', 'class' => 'btn btn-block btn-danger']); ?>
</form>