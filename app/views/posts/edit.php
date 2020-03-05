<?php $this->setSiteTitle('Edit Post: ' . $this->post->label); ?>
<?php $this->start('head'); ?>

<?php $this->end(); ?>

<?php $this->start('body'); ?>
   
<div class="container my-5">
    <div class="col-md-10 offset-md-1 card px-3 py-4 shadow">
        <div class="card-body">
            <?= HTML::errors($this->errors ?? ''); ?>

            <h1 class="text-center">Edit Post</h1>
            
			<?php $this->partial('posts', 'form'); ?>
        </div>
    </div>
</div>


<?php $this->end(); ?>