<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

	<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
		<?php sdbx_before_title(); ?>
		<?php $sdbx_page_hide_page_title = get_post_meta( $post->ID, '_sdbx_page_hide_page_title', true ); ?>
		<?php if ( "1" !== $sdbx_page_hide_page_title ) : ?>
			<h1 class="entry-title"><?php the_title(); ?></h1>
		<?php endif; ?>
		<?php sdbx_after_title(); ?>	

		<?php sdbx_before_post_content(); ?>

		<div class="entry-content">
			<?php sdbx_before_content(); ?>
			<?php the_content(); ?>
			<?php sdbx_after_content(); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'sdbx' ), 'after' => '</div>' ) ); ?>
			<?php edit_post_link( __( 'Edit', 'sdbx' ), '<span class="edit-link">', '</span>' ); ?>
		</div><!-- .entry-content -->
		
		<?php sdbx_after_post_content(); ?>
		
	</div><!-- #post-## -->

	<?php comments_template( '', true ); ?>

<?php endwhile; ?>