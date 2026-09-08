<?php
/**
 * @package shopnex
 */

defined( 'ABSPATH' ) || exit;


/**
* Footer
*/

if ( ! function_exists( 'shopnex_footer_copyrights' ) ) :
function shopnex_footer_copyrights() {
	?>
		<div class="footer-bottom">
            <p>
                <?php

                    $copyright_text = get_theme_mod( 'shopnex_footer_copyright_text' );
                    	if ( ! empty( $copyright_text ) ) :
                    		echo esc_html( $copyright_text );
                    else :
                        echo date_i18n(
                            /* translators: Copyright date format, see https://secure.php.net/date */
                            _x( 'Y', 'copyright date format', 'shopnex' )
                        );
                        ?>
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></a>
                        <?php
                    endif;
                ?>
            </p>
        </div>
	<?php
}
endif;
add_action( 'shopnex_action_footer', 'shopnex_footer_copyrights' );


/**
* Custom excerpt length.
*/
if ( ! function_exists( 'shopnex_my_excerpt_length' ) ) :
function shopnex_my_excerpt_length($length) {
	if ( is_admin() ) :
		return $length;
	endif;
  	return absint(get_theme_mod( 'shopnex_excerpt_length',70));
}
endif;
add_filter('excerpt_length', 'shopnex_my_excerpt_length');


/**
  * Get Breadcrumbs Content
  */
if( !function_exists( 'shopnex_get_breadcrumbs_content' ) ):
    function shopnex_get_breadcrumbs_content() {
        if(!is_front_page()) :

            // Build breadcrumb items
            $breadcrumb_items = array();

            if ( is_single() ) {
                // Single post breadcrumb
                $breadcrumb_items[] = array( 'label' => __( 'Home', 'shopnex' ), 'url' => home_url( '/' ) );

                $blog_page_id = get_option('page_for_posts');
                if ( $blog_page_id ) {
                    $breadcrumb_items[] = array( 'label' => get_the_title( $blog_page_id ), 'url' => get_permalink( $blog_page_id ) );
                } else {
                    $breadcrumb_items[] = array( 'label' => __( 'Journal', 'shopnex' ), 'url' => home_url( '/' ) );
                }

                $categories = get_the_category();
                if ( !empty($categories) ) {
                    $category = $categories[0];
                    $breadcrumb_items[] = array( 'label' => $category->name, 'url' => get_category_link( $category->term_id ) );
                }

                $breadcrumb_items[] = array( 'label' => get_the_title(), 'url' => '' );

            } elseif ( is_category() ) {
                $breadcrumb_items[] = array( 'label' => __( 'Home', 'shopnex' ), 'url' => home_url( '/' ) );

                $blog_page_id = get_option('page_for_posts');
                if ( $blog_page_id ) {
                    $breadcrumb_items[] = array( 'label' => get_the_title( $blog_page_id ), 'url' => get_permalink( $blog_page_id ) );
                }

                $breadcrumb_items[] = array( 'label' => single_cat_title( '', false ), 'url' => '' );

            } elseif ( is_home() ) {
                $breadcrumb_items[] = array( 'label' => __( 'Home', 'shopnex' ), 'url' => home_url( '/' ) );

                $blog_page_id = get_option('page_for_posts');
                if ( $blog_page_id ) {
                    $breadcrumb_items[] = array( 'label' => get_the_title( $blog_page_id ), 'url' => '' );
                } else {
                    $breadcrumb_items[] = array( 'label' => __( 'Blog', 'shopnex' ), 'url' => '' );
                }

            } elseif ( is_page() ) {
                $breadcrumb_items[] = array( 'label' => __( 'Home', 'shopnex' ), 'url' => home_url( '/' ) );

                // Check for parent pages
                $parent_id = wp_get_post_parent_id( get_the_ID() );
                if ( $parent_id ) {
                    $breadcrumb_items[] = array( 'label' => get_the_title( $parent_id ), 'url' => get_permalink( $parent_id ) );
                }

                $breadcrumb_items[] = array( 'label' => get_the_title(), 'url' => '' );

            } elseif ( is_shop() ) {
                $breadcrumb_items[] = array( 'label' => __( 'Home', 'shopnex' ), 'url' => home_url( '/' ) );
                $breadcrumb_items[] = array( 'label' => __( 'Shop', 'shopnex' ), 'url' => '' );

            } elseif ( is_cart() ) {
                $breadcrumb_items[] = array( 'label' => __( 'Home', 'shopnex' ), 'url' => home_url( '/' ) );
                $breadcrumb_items[] = array( 'label' => __( 'Cart', 'shopnex' ), 'url' => '' );

            } elseif ( is_checkout() ) {
                $breadcrumb_items[] = array( 'label' => __( 'Home', 'shopnex' ), 'url' => home_url( '/' ) );
                $breadcrumb_items[] = array( 'label' => __( 'Cart', 'shopnex' ), 'url' => wc_get_cart_url() );
                $breadcrumb_items[] = array( 'label' => __( 'Checkout', 'shopnex' ), 'url' => '' );

            } elseif ( is_product_category() ) {
                $breadcrumb_items[] = array( 'label' => __( 'Home', 'shopnex' ), 'url' => home_url( '/' ) );
                $breadcrumb_items[] = array( 'label' => __( 'Shop', 'shopnex' ), 'url' => get_permalink( wc_get_page_id( 'shop' ) ) );
                $breadcrumb_items[] = array( 'label' => single_term_title( '', false ), 'url' => '' );

            } elseif ( is_archive() ) {
                $breadcrumb_items[] = array( 'label' => __( 'Home', 'shopnex' ), 'url' => home_url( '/' ) );
                $breadcrumb_items[] = array( 'label' => get_the_archive_title(), 'url' => '' );

            } elseif ( is_search() ) {
                $breadcrumb_items[] = array( 'label' => __( 'Home', 'shopnex' ), 'url' => home_url( '/' ) );
                $breadcrumb_items[] = array( 'label' => __( 'Search Results', 'shopnex' ), 'url' => '' );

            } elseif ( is_404() ) {
                $breadcrumb_items[] = array( 'label' => __( 'Home', 'shopnex' ), 'url' => home_url( '/' ) );
                $breadcrumb_items[] = array( 'label' => __( '404 Not Found', 'shopnex' ), 'url' => '' );
            }

            // Only render breadcrumbs if we have items
            if ( empty( $breadcrumb_items ) ) :
                return;
            endif;

            ?>
            <!-- Breadcrumbs -->
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
            <?php
        endif;
    }
endif;

/**
  * Get Page Title
  */

if( !function_exists( 'shopnex_get_title' ) ):
    function shopnex_get_title() {
        if(!is_front_page()) :
            // Skip title on cart page
            if(function_exists('is_cart') && is_cart()) :
                return;
            endif;
            // Skip title on checkout page
            if(function_exists('is_checkout') && is_checkout()) :
                return;
            endif;
            ?>
                <div class="page-title">
                    <?php shopnex_before_title_content(); ?>
                    <div class="container">
                        <h1 class="main-title"><?php the_title(); ?></h1>
                    </div>
                    <?php shopnex_after_title_content(); ?>
                </div>
            <?php
        endif;
    }
endif;


/**
  * Function for displaying menu item description
  *
  */
function shopnex_nav_description( $item_output, $item, $depth, $args ) {
    if( isset($args->theme_location) && !empty($item->description) ) :
        $description_html = '<span class="menu-bubble-description">'.esc_html($item->description).'</span>';
        return $item_output.$description_html;
    endif;
    return $item_output;
}
add_filter( 'walker_nav_menu_start_el', 'shopnex_nav_description', 10, 4 );


/**
  * Function for Minimizing dynamic CSS
  */
function shopnex_minimize_css($css){
    $css = preg_replace('/\/\*((?!\*\/).)*\*\//', '', $css);
    $css = preg_replace('/\s{2,}/', ' ', $css);
    $css = preg_replace('/\s*([:;{}])\s*/', '$1', $css);
    $css = preg_replace('/;}/', '}', $css);
    return $css;
}

/**
  * Adding blog sidebar classes to body
  */
if ( ! function_exists( 'shopnex_add_blog_sidebar_classes_to_body' ) ) :
function shopnex_add_blog_sidebar_classes_to_body($classes = '') {
    $sidebar_layout = get_theme_mod( 'shopnex_blog_single_sidebar_layout', 'no' );
    if ( 'right' === $sidebar_layout && is_single() ) :
        $classes[] = 'single-right-sidebar';

    elseif ( 'left' === $sidebar_layout && is_single() ) :
        $classes[] = 'single-left-sidebar';

    elseif ( 'no' === $sidebar_layout && is_single() ) :
        $classes[] = 'single-no-sidebar';
    endif;
    return $classes;
}
endif;
add_filter('body_class', 'shopnex_add_blog_sidebar_classes_to_body');


/**
 * Estimated Reading Time
 */
if (!function_exists('shopnex_estimated_reading_time')) {
    function shopnex_estimated_reading_time($content) {
        $word_count = str_word_count(strip_tags($content));
        $reading_time = ceil($word_count / 200); // Average reading speed: 200 words per minute
        return $reading_time;
    }
}

/**
 * Custom Comment Callback
 */
if (!function_exists('shopnex_comment_callback')) {
    function shopnex_comment_callback($comment, $args, $depth) {
        $commenter = wp_get_current_commenter();
        $req = get_option('require_name_email');
        ?>
        <div class="comment-item" <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
            <div class="comment-item-header">
                <div class="comment-item-author">
                    <div class="comment-item-avatar">
                        <?php echo get_avatar($comment->comment_author_email, 40); ?>
                    </div>
                    <div class="comment-item-info">
                        <h4 class="comment-item-name"><?php echo wp_kses_post( get_comment_author_link() ); ?></h4>
                        <span class="comment-item-date"><?php printf(esc_html__('%s ago', 'shopnex'), human_time_diff(get_comment_time('U'), time())); ?></span>
                    </div>
                </div>
            </div>
            <div class="comment-item-text">
                <?php comment_text(); ?>
            </div>
            <div class="comment-item-actions">
                <?php
                comment_reply_link(array(
                    'depth'     => $depth,
                    'max_depth' => $args['max_depth'],
                    'before'    => '<button class="comment-action-btn">',
                    'after'     => '</button>',
                    'add_below' => 'comment',
                    'reply_text' => '<svg viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg> ' . esc_html__('Reply', 'shopnex'),
                ));
                ?>
            </div>
        </div>
        <?php
    }
}

/**
 * Fallback menu
 */
if ( ! function_exists( 'shopnex_default_menu_fallback' ) ) :
function shopnex_default_menu_fallback( $args ) {
    $pages = get_pages( array(
        'sort_column'  => 'menu_order, post_title',
        'number'       => 5,
        'hierarchical' => false,
    ) );

    if ( empty( $pages ) ) {
        return;
    }

    foreach ( $pages as $page ) {
        printf(
            '<li class="menu-item"><a href="%s">%s</a></li>',
            esc_url( get_permalink( $page->ID ) ),
            esc_html( $page->post_title )
        );
    }
}
endif;

/**
 * Custom Comment Callback
 */

if ( ! function_exists( 'shopnex_related_blog_posts' ) ) :
function shopnex_related_blog_posts() {
    
    $related_args = array(
        'posts_per_page' => 2,
        'post__not_in'   => array( get_the_ID() ),
        'category__in'   => wp_get_post_categories( get_the_ID(), array( 'fields' => 'ids' ) ),
        'orderby'        => 'rand',
    );
    $related_query = new WP_Query( $related_args );
    if ( $related_query->have_posts() ) :
    ?>
    <!-- Related Posts -->
    <section class="related-section fade-in">
        <h2 class="related-title font-display"><?php esc_html_e( 'Continue reading', 'shopnex' ); ?></h2>
        <div class="related-grid">
            <?php
                while ( $related_query->have_posts() ) :
                    $related_query->the_post();
            ?>
            <a href="<?php the_permalink(); ?>" class="related-card">
                <?php if ( has_post_thumbnail() ) : ?>
                <?php the_post_thumbnail( 'medium' ); ?>
                <?php endif; ?>
                <div class="related-card-body">
                    <?php
                    $rel_categories = get_the_category();
                    if ( ! empty( $rel_categories ) ) :
                    ?>
                    <span class="related-card-category"><?php echo esc_html( $rel_categories[0]->name ); ?></span>
                    <?php endif; ?>
                    <h3 class="related-card-title"><?php the_title(); ?></h3>
                    <span class="related-card-date"><?php echo esc_html( get_the_date() ); ?></span>
                </div>
            </a>
            <?php endwhile; ?>
        </div>
    </section>
    <?php
        wp_reset_postdata();
    endif;
}
endif;

/**
 * Disable onboarding wizards for Elementor and WooCommerce.
 *
 * @return void
 */
if ( ! function_exists( 'shopnex_disable_plugin_onboarding' ) ) :
function shopnex_disable_plugin_onboarding() {
	// Elementor: skip the post-activation onboarding screen.
	if ( did_action( 'elementor/loaded' ) && ! get_option( 'shopnex_elementor_onboarding_disabled' ) ) {
		update_option( 'elementor_onboarded', 'yes' );
		update_option( 'shopnex_elementor_onboarding_disabled', current_time( 'mysql' ) );
	}

	// WooCommerce: hide the setup task list and prevent the wizard redirect.
	if ( class_exists( 'WooCommerce' ) && ! get_option( 'shopnex_woocommerce_onboarding_disabled' ) ) {
		update_option( 'woocommerce_onboarding_opt_in', 'no' );
		update_option( 'woocommerce_task_list_hidden', 'yes' );
		delete_transient( 'wc_setup_wizard_redirect' );
		update_option( 'shopnex_woocommerce_onboarding_disabled', current_time( 'mysql' ) );
	}
}
endif;
add_action( 'admin_init', 'shopnex_disable_plugin_onboarding' );
