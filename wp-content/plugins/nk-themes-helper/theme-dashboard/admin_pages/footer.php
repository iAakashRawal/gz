<?php
/**
 * Theme Dashboard footer
 *
 * @package nk-themes-helper
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

?>

<span id="footer-thankyou">
    <?php if ( nkth()->theme_dashboard()->options['purchase_platform'] ) : ?>
        <span data-nk-purchase-platform="">
            <?php
            echo esc_html__( 'Reset purchase platform', 'nk-themes-helper' );
            ?>
        </span>
    <?php endif; ?>

    <?php echo sprintf( esc_html( nkth()->theme_dashboard()->options['foot_message'] ), esc_html( nkth()->theme_dashboard()->theme_name ) ); ?>
</span>
