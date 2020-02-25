<?php $this->setSiteTitle($this->post->label); ?>

<div class="container my-5">
    <h2 class="mb-4">
        <?= $this->post->title; ?>

        <small class="h6 d-inline italic ml-2">
            <em>
                <?= Helper::getDate($this->post->created_at); ?>
            </em>
        </small>
    </h2>

    <p>
        <?= $this->post->body; ?>
    </p>
</div>