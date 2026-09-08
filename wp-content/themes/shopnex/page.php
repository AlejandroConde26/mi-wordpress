<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package shopnex
 */

get_header();

if ( function_exists( 'is_cart' ) && is_cart() ) :
    // Build breadcrumb items
    $breadcrumb_items = array();
    $breadcrumb_items[] = array( 'label' => __( 'Home', 'shopnex' ), 'url' => home_url( '/' ) );
    $breadcrumb_items[] = array( 'label' => __( 'Cart', 'shopnex' ), 'url' => '' );
    ?>
    <div class="shop-breadcrumbs">
        <div class="container">
            <nav class="shop-breadcrumb-nav" aria-label="<?php esc_attr_e( 'Breadcrumb', 'shopnex' ); ?>">
                <?php foreach ( $breadcrumb_items as $i => $item ) : ?>
                    <?php if ( ! empty( $item['url'] ) ) : ?>
                        <a href="<?php echo esc_url( $item['url'] ); ?>"><?php echo esc_html( $item['label'] ); ?></a>
                        <span class="shop-breadcrumb-sep">/</span>
                    <?php else : ?>
                        <span class="shop-breadcrumb-current"><?php echo esc_html( $item['label'] ); ?></span>
                    <?php endif; ?>
                <?php endforeach; ?>
            </nav>
        </div>
    </div>

    <div class="container">
        <div id="primary" class="content-area">
            <main id="main" class="site-main" role="main">
                <div class="content-inner">
                    <?php
                        while ( have_posts() ) : the_post();
                            the_content();
                        endwhile;
                    ?>
                </div>
            </main>
        </div>
    </div>

<?php
elseif ( function_exists( 'is_checkout' ) && is_checkout() ) :
    $breadcrumb_items = array();
    $breadcrumb_items[] = array( 'label' => __( 'Home', 'shopnex' ), 'url' => home_url( '/' ) );
    $breadcrumb_items[] = array( 'label' => __( 'Cart', 'shopnex' ), 'url' => wc_get_cart_url() );
    $breadcrumb_items[] = array( 'label' => __( 'Checkout', 'shopnex' ), 'url' => '' );
    ?>
    <div class="shop-breadcrumbs">
        <div class="container">
            <nav class="shop-breadcrumb-nav" aria-label="<?php esc_attr_e( 'Breadcrumb', 'shopnex' ); ?>">
                <?php foreach ( $breadcrumb_items as $i => $item ) : ?>
                    <?php if ( ! empty( $item['url'] ) ) : ?>
                        <a href="<?php echo esc_url( $item['url'] ); ?>"><?php echo esc_html( $item['label'] ); ?></a>
                        <span class="shop-breadcrumb-sep">/</span>
                    <?php else : ?>
                        <span class="shop-breadcrumb-current"><?php echo esc_html( $item['label'] ); ?></span>
                    <?php endif; ?>
                <?php endforeach; ?>
            </nav>
        </div>
    </div>

    <div class="container">
        <div id="primary" class="content-area">
            <main id="main" class="site-main" role="main">
                <div class="content-inner">
                    <?php
                        while ( have_posts() ) : the_post();
                            the_content();
                        endwhile;
                    ?>
                </div>
            </main>
        </div>
    </div>

<?php
elseif ( function_exists( 'is_account_page' ) && is_account_page() ) :
    $breadcrumb_items = array();
    $breadcrumb_items[] = array( 'label' => __( 'Home', 'shopnex' ), 'url' => home_url( '/' ) );
    $breadcrumb_items[] = array( 'label' => __( 'My Account', 'shopnex' ), 'url' => '' );
    ?>
    <div class="shop-breadcrumbs">
        <div class="container">
            <nav class="shop-breadcrumb-nav" aria-label="<?php esc_attr_e( 'Breadcrumb', 'shopnex' ); ?>">
                <?php foreach ( $breadcrumb_items as $i => $item ) : ?>
                    <?php if ( ! empty( $item['url'] ) ) : ?>
                        <a href="<?php echo esc_url( $item['url'] ); ?>"><?php echo esc_html( $item['label'] ); ?></a>
                        <span class="shop-breadcrumb-sep">/</span>
                    <?php else : ?>
                        <span class="shop-breadcrumb-current"><?php echo esc_html( $item['label'] ); ?></span>
                    <?php endif; ?>
                <?php endforeach; ?>
            </nav>
        </div>
    </div>

    <div class="container">
        <div id="primary" class="content-area">
            <main id="main" class="site-main" role="main">
                <div class="content-inner">
                    <?php
                        while ( have_posts() ) : the_post();
                            the_content();
                        endwhile;
                    ?>
                </div>
            </main>
        </div>
    </div>

<?php
// ── Regular (non-WooCommerce) pages ──
else :

    shopnex_get_breadcrumbs_content();
    shopnex_before_title();
    if ( true === get_theme_mod( 'shopnex_enable_page_title', true ) ) :
        shopnex_get_title();
    endif;
    shopnex_after_title();

    ?>

    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">
            <div class="content-inner clearfix">
                <?php
                    $elementor_page = get_post_meta( get_the_ID(), '_elementor_edit_mode', true );
                    if ( (bool) $elementor_page ) {
                        ?>
                            <div class="containerno clearfix">
                                <div class="clearfix"></div>
                                <?php
                                    while ( have_posts() ) : the_post();
                                        get_template_part( 'template-parts/page/content', 'page' );

                                        if ( comments_open() || get_comments_number() ) :
                                            comments_template();  
                                        endif;
                                    endwhile;
                                ?>
                            </div>
                        <?php  
                    } else {
                        ?>
                            <div class="container clearfix"> <!-- Added clearfix class -->
                                <div class="row clearfix">
                                    <div class="col-md-12 clearfix">
                                        <?php
                                            while ( have_posts() ) : the_post();
                                                get_template_part( 'template-parts/page/content', 'page' );

                                            if ( comments_open() || get_comments_number() ) :
                                                comments_template();  
                                            endif;
                                            endwhile;
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php
                    }
                ?>
            </div>     
        </main>
    </div>

<?php endif; ?>

<?php
get_footer();
