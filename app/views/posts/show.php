<?php $this->setSiteTitle($this->post->label); ?>
<?php $this->setScript('comments', 'comment.class'); ?>
<?php $this->setScript('comments', 'view.class'); ?>
<?php $this->setScript('load_comments'); ?>
<?php $this->start('head'); ?>

<?php $this->end(); ?>

<?php $this->start('body'); ?>
<div class="container my-5">
    <h2 class="mb-4">
        <?= $this->post->title; ?>

        <small class="h6 d-inline italic ml-2">
            <em>
                <?= Helper::getDate($this->post->created_at); ?>
                in category '<?= $this->post->category->name; ?>'
            </em>
        </small>
        
        <div class="pull-right">
            <a href="<?= URL . 'posts/edit/' . $this->post->slug; ?>" class="btn btn-sm btn-primary">Edit</a>
            <a href="<?= URL . 'posts/delete/' . $this->post->slug; ?>" class="btn btn-sm btn-danger">Delete</a>
        </div>
    </h2>

   	<p>
   		<?= $this->post->tagsString; ?>
   	</p>
   	
    <p>
        <?= $this->post->body; ?>
    </p>
    
    <hr>
    
<!--
    <div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v6.0"></script>
    
    <div class="fb-comments" data-href="https://developers.facebook.com/docs/plugins/comments#configurator" data-width="" data-numposts="5"></div>
    
-->
    <?= HTML::errors($this->errors ?? ''); ?>

    <?php $this->partial('comments', 'form'); ?>

    <hr class="my-5">

    <h2 class="mb-5">Comments</h2>

    <?php $this->partial('comments', 'comments'); ?>
</div>

<?php $this->end(); ?>
