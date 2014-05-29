<?php
	$wp_query = new WP_Query();
	$wp_query->query( array( 'posts_per_page' => get_option( 'posts_per_page' ), 'paged' => $paged ) );
	$more = 0;
?>

<?php if ( $wp_query->max_num_pages > 1 ) : ?>
	<div id="nav-above" class="navigation">
		<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'sdbx' ) ); ?></div>
		<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'sdbx' ) ); ?></div>
	</div><!-- #nav-above -->
<?php endif; ?>


<?php if ( $wp_query->have_posts() ) : while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>

	<div class="post" id="post-<?php the_ID(); ?>">
		
		<?php sdbx_before_title(); ?>					
		<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'sdbx' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
		<?php sdbx_after_title(); ?>

		<div class="entry-meta">
			<?php sdbx_posted_on(); ?>
		</div><!-- .entry-meta -->
		
		<?php sdbx_before_content(); ?>

		<div class="entry-content">
			<?php 
				$rss_use_excerpt = get_option('rss_use_excerpt');
				if ( "1" === $rss_use_excerpt )
					the_excerpt( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'sdbx' ) ); 
				else
					the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'sdbx' ) ); 
				
			?>
			<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'sdbx' ), 'after' => '</div>' ) ); ?>
		</div><!-- .entry-content -->

		<?php sdbx_after_content(); ?>
		
		<div class="entry-utility">
			<?php if ( count( get_the_category() ) ) : ?>
				<span class="cat-links">
					<?php printf( __( '<span class="%1$s">Posted in</span> %2$s', 'sdbx' ), 'entry-utility-prep entry-utility-prep-cat-links', get_the_category_list( ', ' ) ); ?>
				</span>
			<?php endif; ?>
			<?php
				$tags_list = get_the_tag_list( '', ', ' );
				if ( $tags_list ):
			?>
				<span class="meta-sep">|</span>
				<span class="tag-links">
					<?php printf( __( '<span class="%1$s">Tagged</span> %2$s', 'sdbx' ), 'entry-utility-prep entry-utility-prep-tag-links', $tags_list ); ?>
				</span>
			<?php endif; ?>
			<span class="comments-link"><?php comments_popup_link( __( '| Leave a comment', 'sdbx' ), __( '| 1 Comment', 'sdbx' ), __( '| % Comments', 'sdbx' ), '', '' ); ?></span>
			<?php edit_post_link( __( 'Edit', 'sdbx' ), '<span class="meta-sep">|</span> <span class="edit-link">', '</span>' ); ?>
		</div><!-- .entry-utility -->

	</div>
	<?php endwhile; ?>

<?php else: ?>

	<p class="no-data">
		<?php _e( 'No results.', 'sdbx' ); ?>
	</p><!-- .no-data -->

<?php endif; ?>

<br />
<?php if (  $wp_query->max_num_pages > 1 ) : ?>
	<div id="nav-below" class="navigation">
		<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'sdbx' ) ); ?></div>
		<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'sdbx' ) ); ?></div>
	</div><!-- #nav-below -->
<?php endif; ?>