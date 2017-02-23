<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
?>

<?php get_header(); ?>

<section class="content">

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

	<article class="blog-post">
		<div class="content">
			<p class="photo"><?php the_post_thumbnail(); ?></p>
			<blockquote>
				<?php the_content(); ?>
			</blockquote>
		</div>	
	</article>

<?php endwhile; // end of the loop. ?>

</section>

<?php get_footer(); ?>
