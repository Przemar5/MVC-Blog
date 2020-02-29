<?php $this->setSiteTitle($this->post->label); ?>

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
    
    <form action="" method="post">
    	<?= HTML::inputBlock(['type' => 'text', 'id' => 'nick', 'name' => 'nick', 'class' => 'form-control'],
			['text' => 'Nick', 'class' => 'form-group']); ?>
		<?= HTML::inputBlock(['type' => 'email', 'id' => 'email', 'name' => 'email', 'class' => 'form-control'],
			['text' => 'Email', 'class' => 'form-group']); ?>
		<?= HTML::textareaBlock(['id' => 'message', 'name' => 'message', 'rows' => '10', 'class' => 'form-control'],
			['text' => 'Comment', 'class' => 'form-group']); ?>
		<?= HTML::submit(['value' => 'Create', 'class' => 'btn btn-block btn-primary']); ?>
		<?= HTML::reset(['value' => 'Clear', 'class' => 'btn btn-block btn-default']); ?>
    </form>
</div>