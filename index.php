<?php 
get_header();

global $wp_query;
$big = 999999999; // need an unlikely integer

$pagination = paginate_links( array(
	'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
	'format' => '?paged=%#%',
	'current' => max( 1, get_query_var('paged') ),
	'total' => $wp_query->max_num_pages
) );
?>

<main id="main" role="main">
	<div class="container">
		<?php if( have_posts() ) : ?>

			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part('templates/post'); ?>
			<?php endwhile; ?>
			
			<?php if ( $pagination ) : ?>
				<nav class="pagination">
					<?php echo $pagination; ?>
				</nav>
			<?php endif; ?>

		<?php endif; ?>
	</div>
</main><!-- /#main -->
<?php get_footer(); ?>
