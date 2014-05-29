<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

	<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
		<?php sdbx_before_title(); ?>
		<?php $sdbx_page_hide_page_title = get_post_meta( $post->ID, '_sdbx_page_hide_page_title', true ); ?>
		<?php if ( "1" !== $sdbx_page_hide_page_title ) : ?>
			<h1 class="entry-title"><?php the_title(); ?></h1>
		<?php endif; ?>
		<?php sdbx_after_title(); ?>

		<div class="entry-meta">
			<?php sdbx_posted_on(); ?>
		</div><!-- .entry-meta -->

		<?php sdbx_before_post_content(); ?>

		<div class="entry-content">
			<?php sdbx_before_content(); ?>
			<?php the_content(); ?>
			<?php sdbx_after_content(); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'sdbx' ), 'after' => '</div>' ) ); ?>
		</div><!-- .entry-content -->
		
		<?php sdbx_after_post_content(); ?>
		
		
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
		
		
	</div><!-- #post-## -->

	<?php comments_template( '', true ); ?>

<?php endwhile; ?>