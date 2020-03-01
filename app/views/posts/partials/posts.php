<?php if (!empty($this->posts) && count($this->posts)): ?>
    <?php foreach ($this->posts as $post): ?>
	
        <div class="container">
            <h3>
                <a href="<?= URL . 'posts/show/' . $post->slug; ?>">
                    <?= $post->title; ?>
                </a>

                <small class="h6 d-inline italic ml-2">
                    <em>
                        <?= Helper::getDate($post->created_at); ?>
                        in category '<?= $post->category->name; ?>'
                    </em>
                </small>
            </h3>
                    
			<h2><?= $post->id; ?></h2>

			<p>
				<?= $post->tagsString; ?>
			</p>

            <p>
                <?= $post->body; ?>
            </p>

            <a href="<?= URL . 'posts/show/' . $post->slug; ?>" class="text-primary pull-right">
                Read More
            </a>
        </div>

    <?php endforeach; ?>
<?php else: ?>

<h4>There are no posts yet. Create some!</h4>

<?php endif; ?>

<?php if (!empty($this->posts)): ?>
	<?= $this->pagination; ?>
<?php endif; ?>