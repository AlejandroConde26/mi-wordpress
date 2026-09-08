<?php
/**
 * Blog Pagination Template Part
 *
 * @package shopnex
 */

if ( ! empty( $args['query'] ) ) {
    $blog_query = $args['query'];
} else {
    global $wp_query;
    $blog_query = $wp_query;
}

$total_pages = $blog_query->max_num_pages;

if ( $total_pages > 1 ) :

    the_posts_pagination( array(
        'mid_size'  => 2,
        'prev_text' => '<svg viewBox="0 0 24 24"><path d="m15 18l-6-6l6-6"/></svg>',
        'next_text' => '<svg viewBox="0 0 24 24"><path d="m9 18l6-6l-6-6"/></svg>',
        'before_page_number' => '',
        'after_page_number'  => '',
    ) );

endif; ?>
