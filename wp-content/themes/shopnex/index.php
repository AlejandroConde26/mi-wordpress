<?php
/**
 * The main template file.
 *
 * This is most generic template file in a WordPress theme
 * and one of two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g. it puts together home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package shopnex
 */

get_header();
?>

<div id="primary" class="content-area">
    <div id="main-content" class="site-main" role="main">

    <?php if ( is_search() ) : ?>

        <?php get_template_part( 'template-parts/page/content-search' ); ?>

    <?php else : ?>

    <!-- Page Header -->
    <section class="blog-page-header">
        <div class="container">
            <div class="blog-page-breadcrumb">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'shopnex' ); ?></a>
                <span class="breadcrumb-sep">/</span>
                <span><?php echo esc_html( get_theme_mod( 'shopnex_blog_heading', __( 'Blog', 'shopnex' ) ) ); ?></span>
            </div>
            <h1 class="blog-page-title font-display">
                <?php
                if ( is_category() ) {
                    single_cat_title();
                } elseif ( is_tag() ) {
                    single_tag_title();
                } elseif ( is_author() ) {
                    echo esc_html__( 'Author: ', 'shopnex' ) . esc_html( get_the_author() );
                } elseif ( is_date() ) {
                    if ( is_day() ) {
                        echo esc_html( get_the_date() );
                    } elseif ( is_month() ) {
                        echo esc_html( get_the_date( 'F Y' ) );
                    } elseif ( is_year() ) {
                        echo esc_html( get_the_date( 'Y' ) );
                    }
                } else {
                    echo esc_html( get_theme_mod( 'shopnex_blog_heading', __( 'Blog', 'shopnex' ) ) );
                }
                ?>
            </h1>
        </div>
    </section>

    <!-- Blog Content -->
    <section class="blog-content-section">
        <div class="container">

            <?php
            if ( have_posts() ) :

                $paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;

                // Featured post (first post on first page)
                if ( $paged <= 1 ) :
                    the_post();
                    get_template_part( 'template-parts/blog/blog-featured' );
                endif;

                // Blog Grid
                ?>
                <div class="blog-grid">
                    <?php
                    while ( have_posts() ) :
                        the_post();
                        get_template_part( 'template-parts/blog/content-archive' );
                    endwhile;
                    ?>
                </div>

                <!-- Pagination -->
                <?php get_template_part( 'template-parts/blog/blog-pagination', null, array( 'paged' => $paged ) ); ?>

            <?php else : ?>
                <div class="no-posts-found">
                    <h2><?php esc_html_e( 'No posts found', 'shopnex' ); ?></h2>
                    <p><?php esc_html_e( 'It seems we can\'t find what you\'re looking for.', 'shopnex' ); ?></p>
                </div>
            <?php
            endif;
            ?>

        </div>
    </section>

    <?php endif; ?>

    </div>
</div>

<?php get_footer(); ?>
