<?php $this->partial('posts', 'form'); ?>
<div class="container my-5">
    <div class="col-md-8 offset-md-2 card px-3 py-4 shadow">
        <?php HTML::errors($this->errors); ?>

        <h1 class="text-center">Create Post</h1>

        <div class="card-body">
            <?php $this->partial('posts', 'form'); ?>
        </div>
    </div>
</div>
