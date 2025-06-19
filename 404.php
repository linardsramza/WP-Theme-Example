<?php get_header(); ?>
<main id="main" role="main">
	<?php
		$template_404 = get_field('page_template_404', 'options');
		if($template_404) :
			$content = get_post($template_404);
			echo apply_filters( 'the_content', $content->post_content);
		endif;
	?>
</main>
<?php get_footer(); ?>