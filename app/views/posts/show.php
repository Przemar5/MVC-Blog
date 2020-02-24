<?php $this->setSiteTitle($this->post->label); ?>

<div class="container my-5">
    <h2 class="mb-4">
        <?= $this->post->title; ?>
    </h2>
    <p>
        <?= $this->post->body; ?>
    </p>
</div>