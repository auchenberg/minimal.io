<?php
/**
 * The loop that displays posts.
 *
 * The loop displays the posts and the post content.  See
 * http://codex.wordpress.org/The_Loop to understand it and
 * http://codex.wordpress.org/Template_Tags to understand
 * the tags used in it.
 *
 * This can be overridden in child themes with loop.php or
 * loop-template.php, where 'template' is the loop context
 * requested by a template. For example, loop-index.php would
 * be used if it exists and we ask for the loop with:
 * <code>get_template_part( 'loop', 'index' );</code>
 *
 * @package WordPress
 * @subpackage Minimal
 */
?>

<section class="content">

<?php while ( have_posts() ) : the_post(); ?>

	<article class="blog-post">
		<div class="content">
			<p class="photo"><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a></p>
			<blockquote>
				<?php the_content(); ?>
			</blockquote>
		</div>	
	</article>

<?php endwhile; // End the loop. Whew. ?>
</section>

<footer>
<?php /* Display navigation to next/previous pages when applicable */ ?>
<?php if (  $wp_query->max_num_pages > 1 ) : ?>
				<div id="nav-below" class="navigation">
					<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'minimal' ) ); ?></div>
					<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'minimal' ) ); ?></div>
				</div><!-- #nav-below -->
<?php endif; ?>
</footer>
