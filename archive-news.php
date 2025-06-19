<?php get_header(); ?>
<main id="main" role="main">
	<?php
		$news_archive = get_field('page_template_news_archive', 'options');
		if($news_archive) :
			$content = get_post($news_archive);
			echo apply_filters( 'the_content', $content->post_content);
		endif;
	?>
</main>
<?php get_footer(); ?>