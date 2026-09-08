<?php
/**
 * 404 Error Page template.
 *
 * @package shopnex
 */

get_header();
?>

<div id="primary" class="content-area">
    <main id="main-content" class="site-main" role="main">
        <div class="container">
            <div class="error-404-wrapper">

                <!-- Background element -->
                <div class="error-404-bg-accent" aria-hidden="true"></div>

                <div class="error-404-content">

                    <!-- Large 404 number -->
                    <div class="error-404-code">
                        <span class="error-404-code__digit">4</span>
                        <span class="error-404-code__zero">
                            <span class="error-404-code__zero-inner">0</span>
                        </span>
                        <span class="error-404-code__digit">4</span>
                    </div>

                    <h1 class="error-404-title">
                        <?php esc_html_e( 'Page not found', 'shopnex' ); ?>
                    </h1>

                    <p class="error-404-text">
                        <?php esc_html_e( 'The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.', 'shopnex' ); ?>
                    </p>

                    <!-- Search Form -->
                    <div class="error-404-search">
                        <?php get_search_form(); ?>
                    </div>

                    <!-- Action Buttons -->
                    <div class="error-404-actions">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="error-404-btn error-404-btn--primary">
                            <?php esc_html_e( 'Back to Home', 'shopnex' ); ?>
                        </a>
                    </div>

                    <!-- Helpful Links -->
                    <div class="error-404-links">
                        <p class="error-404-links__label"><?php esc_html_e( 'Helpful Links', 'shopnex' ); ?></p>
                        <ul class="error-404-links__list">
                            <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'shopnex' ); ?></a></li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </main>
</div>

<?php get_footer(); ?>
