<?php $this->setSiteTitle('Posts'); ?>
<?php $this->start('head'); ?>

<?php $this->end(); ?>

<?php $this->start('body'); ?>
<h1>Blog</h1>

<?php $this->partial('posts', 'posts'); ?>

<?php $this->end(); ?>
