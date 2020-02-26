<?php $this->setSiteTitle($this->post->label); ?>

<div class="container my-5">
    <h2 class="mb-4">
        <?= $this->post->title; ?>

        <small class="h6 d-inline italic ml-2">
            <em>
                <?= Helper::getDate($this->post->created_at); ?>
            </em>
        </small>
        
        <div class="pull-right">
            <a href="<?= URL . 'posts/edit/' . $this->post->slug; ?>" class="btn btn-sm btn-primary">Edit</a>
            <a href="<?= URL . 'posts/delete/' . $this->post->slug; ?>" class="btn btn-sm btn-primary">Delete</a>
        </div>
    </h2>

    <p>
        <?= $this->post->body; ?>
    </p>
</div>