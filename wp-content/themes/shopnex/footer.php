<?php
/**
 * The template for displaying the footer.
 *
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package shopnex
 */

?>

<!-- FOOTER -->
<footer class="site-footer">
    <div class="footer-inner">
        <?php $widget_num = absint(get_theme_mod( 'shopnex_footer_widgets', '4' )); ?>
        <div class="footer-grid footer-cols-<?php echo esc_attr( $widget_num ); ?>">
            <!-- Footer Widget Columns -->
            <?php
            for ( $i = 1; $i <= $widget_num; $i++ ) :
                if ( is_active_sidebar( 'footer-' . $i ) ) :
            ?>
            <div class="footer-col<?php echo ( $i === 1 ) ? ' footer-brand-col' : ''; ?>">
                <?php dynamic_sidebar( 'footer-' . $i ); ?>
            </div>
            <?php endif; ?>
            <?php endfor; ?>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <span class="footer-copyright">
                <?php
                $footer_text = get_theme_mod( 'shopnex_footer_copyright_text', '' );
                if ( ! empty( $footer_text ) ) :
                    echo esc_html( $footer_text );
                else :
                    echo '&copy; ' . esc_html( date( 'Y' ) ) . ' ' . esc_html( get_bloginfo( 'name' ) );
                    esc_html_e( '. All rights reserved.', 'shopnex' );
                endif;
                if ( apply_filters( 'shopnex_show_footer_credit', true ) ) :
                ?>
                <span class="footer-credit">
                    <?php
                    printf(
                        __( ' | Theme by %s', 'shopnex' ),
                        '<a href="' . esc_url( 'https://spiraclethemes.com/' ) . '" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Spiracle Themes', 'shopnex' ) . '</a>'
                    );
                    ?>
                </span>
                <?php endif; ?>
            </span>
            <div class="footer-bottom-links">
                <?php
                $privacy_url = get_theme_mod( 'shopnex_footer_privacy_policy_url', '' );
                $cookie_url  = get_theme_mod( 'shopnex_footer_cookie_policy_url', '' );
                $terms_url   = get_theme_mod( 'shopnex_footer_terms_url', '' );

                if ( ! empty( $privacy_url ) ) :
                ?>
                    <a href="<?php echo esc_url( $privacy_url ); ?>"><?php esc_html_e( 'Privacy Policy', 'shopnex' ); ?></a>
                <?php endif; ?>

                <?php if ( ! empty( $cookie_url ) ) : ?>
                    <a href="<?php echo esc_url( $cookie_url ); ?>"><?php esc_html_e( 'Cookie Policy', 'shopnex' ); ?></a>
                <?php endif; ?>

                <?php if ( ! empty( $terms_url ) ) : ?>
                    <a href="<?php echo esc_url( $terms_url ); ?>"><?php esc_html_e( 'Terms & Conditions', 'shopnex' ); ?></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
