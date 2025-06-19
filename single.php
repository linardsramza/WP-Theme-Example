<?php get_header(); ?>
<main id="main" role="main">
	<?php while ( have_posts() ) : the_post(); ?>
		<?php
			$content = get_the_content();
			$display_breadcrumbs = get_field('display_breadcrumbs') ?? true;

			// check if hero block is first
			$is_hero_first = false;
			if ( has_blocks( $content ) ) {
			    $blocks = parse_blocks( $content );
			    if ( $blocks[0]['blockName'] === 'acf/hero' || $blocks[0]['blockName'] === 'acf/inner-hero' ) {
			    	$is_hero_first = true;
			    }
			}
			if($display_breadcrumbs && !$is_hero_first){
				get_template_part('templates/breadcrumbs');
			}
		?>
		<?php the_content(); ?>
	<?php endwhile; ?>
</main>
<?php get_footer(); ?>