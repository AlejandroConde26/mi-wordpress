<?php
/**
 * Template part for displaying posts in the blog archive
 *
 * @package shopnex
 */

$categories = get_the_category();
$primary_category = ! empty( $categories ) ? $categories[0] : null;
$category_slugs = array();
if ( ! empty( $categories ) ) {
    foreach ( $categories as $cat ) {
        $category_slugs[] = $cat->slug;
    }
}
$reading_time = shopnex_estimated_reading_time( get_the_content() );
?>

<a href="<?php the_permalink(); ?>" class="blog-card" data-category="<?php echo esc_attr( implode( ',', $category_slugs ) ); ?>">
    <?php if ( has_post_thumbnail() ) : ?>
    <img class="blog-card-image" src="<?php echo esc_url( get_the_post_thumbnail_url( get_the_ID(), 'large' ) ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" loading="lazy">
    <?php else : ?>
    <div class="blog-card-image blog-card-image-placeholder"></div>
    <?php endif; ?>
    <div class="blog-card-body">
        <?php if ( $primary_category ) : ?>
        <span class="blog-card-category"><?php echo esc_html( $primary_category->name ); ?></span>
        <?php endif; ?>
        <h3 class="blog-card-title"><?php the_title(); ?></h3>
        <p class="blog-card-excerpt"><?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?></p>
        <div class="blog-card-meta">
            <span><?php echo get_the_date(); ?></span>
            <span>·</span>
            <span><?php echo esc_html( $reading_time ); ?> <?php esc_html_e( 'min read', 'shopnex' ); ?></span>
        </div>
    </div>
</a>
