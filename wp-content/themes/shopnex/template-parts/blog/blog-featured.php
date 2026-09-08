<?php
/**
 * Template part for displaying featured post on blog page
 *
 * @package shopnex
 */

$categories = get_the_category();
$category = ! empty( $categories ) ? $categories[0] : null;
?>

<!-- Featured Post -->
<a href="<?php the_permalink(); ?>" class="featured-post">
    <?php if ( has_post_thumbnail() ) : ?>
    <img class="featured-image" src="<?php echo esc_url( get_the_post_thumbnail_url( get_the_ID(), 'large' ) ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" loading="eager">
    <?php else : ?>
    <div class="featured-image featured-image-placeholder"></div>
    <?php endif; ?>
    <div class="featured-overlay"></div>
    <div class="featured-content">
        <?php if ( $category ) : ?>
        <span class="featured-category"><?php echo esc_html( $category->name ); ?></span>
        <?php endif; ?>
        <h2 class="featured-title"><?php the_title(); ?></h2>
        <p class="featured-excerpt"><?php echo wp_trim_words( get_the_excerpt(), 15, '...' ); ?></p>
        <span class="featured-date"><?php echo get_the_date(); ?></span>
    </div>
</a>
