<?php

/**
 * Theme specific functions.
 */
class Custom_Functions {

	/**
	 * Retrieves a list of share links for a post.
	 *
	 * @param int|WP_Post $post Post ID or post object.
	 * @return string     A ul list with share links.
	 */
	public function share_post( $post )
	{
		$output = '';

		$post = get_post( $post );
		$url = urlencode( get_permalink( $post ) );
		$title = str_replace( ' ', '%20', get_the_title( $post ) );

		$twitterURL  = 'https://twitter.com/intent/tweet?text=' . $title . '&amp;url=' . $url . '&amp;via=WhistleB';
		$facebookURL = 'https://www.facebook.com/sharer/sharer.php?u=' . $url;
		$linkedInURL = 'https://www.linkedin.com/shareArticle?mini=true&url=' . $url . '&amp;title=' . $url;

		$output .= '<ul class="social-share">';
			$output .= '<li><span>'.__('Share article:', 'theme').'</span></li>';
			$output .= '<li><a href="' . $twitterURL . '" target="_blank">Twitter</a></li>';
			$output .= '<li><a href="' . $linkedInURL . '" target="_blank">LinkedIn</a></li>';
			$output .= '<li><a href="' . $facebookURL . '" target="_blank">Facebook</a></li>';
		$output .= '</ul>';

		return $output;
	}
}