<?php
/**
 * Content Loop for archives - Grid Style.
 */

if ( ! have_posts() ) {
	newsplus_no_posts();
}
?>
<div class="grid-row<?php if ( get_option( 'pls_enable_masonry', false ) ) echo ' masonry-enabled'; ?> clear">
	<?php
    $schema =  newsplus_schema( get_option( 'pls_schema', false ) );

    /* Initialize counter and class variables */
    $col = intval( get_option( 'pls_grid_col', 2 ) );
    $count = 1;
    $thumbclass = '';
    $fclass = '';
    $lclass = '';
    while ( have_posts()) :
		the_post();

		/* Calculate appropriate class names for first and last grids */
		$fclass = ( 0 == ( ( $count - 1 ) % (int)$col ) ) ? ' first-grid' : '';
		$lclass = ( 0 == ( $count % (int)$col ) ) ? ' last-grid' : '';

		$post_opts = get_post_meta( get_the_id(), 'post_options', true );
        $ad_class = isset( $post_opts['sp_post'] ) ? ' sp-post' : '';
		?>
		<article<?php echo $schema['container']; ?> id="post-<?php the_ID();?>" <?php post_class( 'newsplus entry-grid col'. $col . $fclass . $lclass . $ad_class ); ?>>
			<?php
			if ( $ad_class ) {
                echo '<span class="sp-label-archive">' . get_option( 'pls_sp_label', __( 'Advertisement', 'newsplus') ) . '</span>';
            }
            get_template_part( 'formats/format', get_post_format() );
            $share_btns = get_option( 'pls_inline_social_btns', array() ) ;
            $meta_args = array(
				'date_format' => ( 'human' == get_option( 'pls_date_format' ) ) ? 'human' : get_option( 'date_format' ),
				'enable_schema' => get_option( 'pls_schema' ),
				'hide_cats' => get_option( 'pls_hide_cats' ),
				'hide_reviews' => get_option( 'pls_hide_reviews' ),
				'hide_date' => get_option( 'pls_hide_date' ),
				'hide_author' => get_option( 'pls_hide_author' ),
				'hide_excerpt' => get_option( 'pls_hide_excerpt' ),
				'show_avatar' => get_option( 'pls_show_avatar' ),
				'hide_views' => get_option( 'pls_hide_views' ),
				'hide_comments' => get_option( 'pls_hide_comments' ),
				'readmore' => get_option( 'pls_readmore' ),
				'publisher_logo' => '' != get_option( 'pls_logo' ) ? esc_url( get_option( 'pls_logo' ) ) : get_template_directory_uri() . '/images/logo.png',
				'ext_link' => get_option( 'pls_ext_links' ),
				'sharing'	=> get_option( 'pls_inline_sharing', false ),
				'share_btns' => is_array( $share_btns ) ? implode( ',', $share_btns ) : ''
            );

			$rows = newsplus_meta( $meta_args );

            $short_title = get_post_meta( $post->ID, 'np_short_title', true );
            $custom_link = get_post_meta( $post->ID, 'np_custom_link', true );
            ?>
            <div class="entry-content">
				<?php
                echo $rows['row_1'];

                printf( '<h2%1$s class="entry-title"><a href="%2$s" title="%3$s">%3$s</a></h2>',
					$schema['heading'],
					get_option( 'pls_ext_link' ) && $custom_link ? esc_url( $custom_link ) : esc_url( get_permalink() ),
					( get_option( 'pls_short_title' ) && $short_title ) ? $short_title : esc_attr( get_the_title() )
                );

                echo $rows['row_2'];
                if ( ! get_option( 'pls_hide_excerpt' ) ) { ?>
                    <p<?php echo $schema['text']; ?> class="post-excerpt">
						<?php if ( 'true' == get_option( 'pls_use_word_length', false ) ) {
							echo newsplus_short_by_word( get_the_excerpt(), get_option( 'pls_word_length', 20 ) );
                        }
                        else {
							echo newsplus_short( get_the_excerpt(), get_option( 'pls_char_length', 100 ) );
                        } ?>
                    </p>
                    <?php
                }

				echo $rows['row_3']; ?>
            </div><!-- /.entry-content -->
		</article><!-- #post-<?php the_ID();?> -->
		<?php $count++;
    endwhile; // End the loop ?>
</div><!-- .clear -->
<?php
// Previous/next page navigation.
the_posts_pagination( array(
	'prev_text'          => __( 'Previous page', 'newsplus' ),
	'next_text'          => __( 'Next page', 'newsplus' ),
	'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'newsplus' ) . ' </span>',
) );