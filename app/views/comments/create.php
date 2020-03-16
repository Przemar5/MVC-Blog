<?php $this->setSiteTitle('Create comment'); ?>
<?php $this->start('head'); ?>

<?php $this->end(); ?>

<?php $this->start('body'); ?>
<div class="container my-5">
    <h1 class="text-center mb-2">Create Comment</h1>

    <?= HTML::errors($this->errors ?? ''); ?>

    <?php $this->partial('comments', 'form'); ?>
</div>
<?php $this->end(); ?>
