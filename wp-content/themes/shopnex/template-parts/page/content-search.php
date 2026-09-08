<?php
/**
 * Template part for displaying search results.
 *
 * @package shopnex
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$search_query = get_search_query();
$search_post_type = isset( $_GET['post_type'] ) ? sanitize_key( $_GET['post_type'] ) : 'post';
$is_product_search = ( 'product' === $search_post_type && shopnex_is_active_woocommerce() );

$count_args = array(
    'post_type'      => $is_product_search ? 'product' : 'post',
    'post_status'    => 'publish',
    's'              => $search_query,
    'posts_per_page' => 1,
    'fields'         => 'ids',
);
$count_query  = new WP_Query( $count_args );
$result_count = $count_query->found_posts;
wp_reset_postdata();
?>

<!-- Search Hero -->
<section class="blog-hero search-hero">
    <div class="container">
        <div class="search-hero-label">
            <?php
            if ( $is_product_search ) :
                esc_html_e( 'Product Search Results', 'shopnex' );
            else :
                esc_html_e( 'Search Results', 'shopnex' );
            endif;
            ?>
        </div>
        <h1 class="blog-hero-title">
            <?php
            printf(
                /* translators: %s: search query */
                esc_html__( 'Results for "%s"', 'shopnex' ),
                esc_html( $search_query )
            );
            ?>
        </h1>
        <p class="blog-hero-subtitle">
            <?php
            if ( $is_product_search ) :
                printf(
                    /* translators: %1$d: result count, %2$s: search query */
                    esc_html( _n(
                        '%1$d product found for "%2$s"',
                        '%1$d products found for "%2$s"',
                        $result_count,
                        'shopnex'
                    ) ),
                    absint( $result_count ),
                    esc_html( $search_query )
                );
            else :
                printf(
                    /* translators: %1$d: result count, %2$s: search query */
                    esc_html( _n(
                        '%1$d article found for "%2$s"',
                        '%1$d articles found for "%2$s"',
                        $result_count,
                        'shopnex'
                    ) ),
                    absint( $result_count ),
                    esc_html( $search_query )
                );
            endif;
            ?>
        </p>

        <!-- Inline Search Form -->
        <div class="search-hero-form">
            <?php get_search_form(); ?>
        </div>
    </div>
</section>

<?php if ( $is_product_search ) : ?>

<!-- Product Search Results -->
<section class="blog-content-section">
    <div class="container">
        <div class="search-products-wrapper">

            <?php
            $paged          = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
            $posts_per_page = 12;

            $args = array(
                'post_type'      => 'product',
                'post_status'    => 'publish',
                'posts_per_page' => $posts_per_page,
                'paged'          => $paged,
                's'              => $search_query,
            );

            $query = new WP_Query( $args );

            if ( $query->have_posts() ) :
            ?>
                <ul class="products shop-product-grid">
                    <?php
                    while ( $query->have_posts() ) :
                        $query->the_post();
                        global $product;
                        $product = wc_get_product( get_the_ID() );
                        if ( $product ) :
                            wc_get_template_part( 'content', 'product' );
                        endif;
                    endwhile;
                    ?>
                </ul>
            <?php
            else :
            ?>
                <!-- No Results State -->
                <div class="search-no-results">
                    <div class="search-no-results-icon">
                        <svg viewBox="0 0 24 24" width="48" height="48"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
                    </div>
                    <h3 class="search-no-results-title"><?php esc_html_e( 'No products found', 'shopnex' ); ?></h3>
                    <p class="search-no-results-text"><?php esc_html_e( 'We couldn\'t find any products matching your search. Try different keywords or browse our shop.', 'shopnex' ); ?></p>
                    <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="search-no-results-btn">
                        <?php esc_html_e( 'Browse All Products', 'shopnex' ); ?>
                        <svg viewBox="0 0 24 24" width="16" height="16"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </a>
                </div>
            <?php
            endif;
            wp_reset_postdata();
            ?>

            <!-- Pagination -->
            <?php get_template_part( 'template-parts/blog/blog-pagination', null, array( 'query' => $query, 'paged' => $paged ) ); ?>

        </div>
    </div>
</section>

<?php else : ?>

<!-- Article Search Results -->
<section class="blog-content-section">
    <div class="container">
        <div class="blog-layout">

            <!-- Main Content -->
            <div class="blog-content">

                <!-- Posts Grid -->
                <div class="posts-grid">
                    <?php
                    $paged          = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
                    $posts_per_page = 7;

                    $args = array(
                        'post_type'      => 'post',
                        'post_status'    => 'publish',
                        'posts_per_page' => $posts_per_page,
                        'paged'          => $paged,
                        's'              => $search_query,
                    );

                    $query = new WP_Query( $args );

                    if ( $query->have_posts() ) :
                        $post_count = 0;
                        while ( $query->have_posts() ) :
                            $query->the_post();
                            $post_count++;
                            get_template_part( 'template-parts/blog/content-archive', null, array( 'post_count' => $post_count ) );
                        endwhile;
                    else :
                    ?>
                        <!-- No Results State -->
                        <div class="search-no-results">
                            <div class="search-no-results-icon">
                                <svg viewBox="0 0 24 24" width="48" height="48"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
                            </div>
                            <h3 class="search-no-results-title"><?php esc_html_e( 'No articles found', 'shopnex' ); ?></h3>
                            <p class="search-no-results-text"><?php esc_html_e( 'We couldn\'t find any articles matching your search. Try different keywords or browse all articles.', 'shopnex' ); ?></p>
                            <a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>" class="search-no-results-btn">
                                <?php esc_html_e( 'Browse All Articles', 'shopnex' ); ?>
                                <svg viewBox="0 0 24 24" width="16" height="16"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                            </a>
                        </div>
                    <?php
                    endif;
                    wp_reset_postdata();
                    ?>
                </div>

                <!-- Pagination -->
                <?php get_template_part( 'template-parts/blog/blog-pagination', null, array( 'query' => $query, 'paged' => $paged ) ); ?>

            </div>

            <!-- Sidebar -->
            <?php get_template_part( 'template-parts/blog/blog-sidebar' ); ?>

        </div>
    </div>
</section>

<?php endif; ?>
