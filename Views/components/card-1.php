<article class="prjt_cards card col-3 col-md-4 col-sm-6">

	    <figure>
			<img loading="lazy" src="<?= $fields['img'] ?>" alt="">
		</figure>
		<div class="content">
		<h1 class="h2">
			<?= $fields['title'] ?>
		</h1>
		<p>
			<?= $fields['content'] ?>
		</p>
		<a href="<?= array_key_exists("url",$fields) ? $fields['url'] : "#" ?>" class="btn ">
			<?php if ($fields['btn'] === 'Github'): ?>
   				<span class="icon-github"></span><?= $fields['btn'] ?>
			<?php else: ?>
				<?= $fields['btn'] ?>
			<?php endif; ?>
		</a>
		</div>
</article>