<?php
/**
 * Theme Dashboard plugins template
 *
 * @package nk-themes-helper
 */

// phpcs:disable

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'TGM_Plugin_Activation' ) ) {
    ?>
    <p class="about-description"><?php esc_html__( 'TGMPA library is required to show plugins', 'nk-themes-helper' ); ?></p>
    <?php
    return;
}

$plugin_table = new TGMPA_List_Table;
$instance     = TGM_Plugin_Activation::$instance;

// Force refresh of available plugin information so we'll know about manual updates/deletes.
wp_clean_plugins_cache( false );

?>
<p class="about-description">
    <?php
    // translators: %s - theme name.
    printf( esc_html__( 'These plugins comes with %s theme. If you want full functionality from demo page, you should activate all of these plugins.', 'nk-themes-helper' ), esc_html( nkth()->theme_dashboard()->theme_name ) );
    ?>
</p>

<div class="tgmpa">
    <?php $plugin_table->prepare_items(); ?>

    <?php
    if ( ! empty( $instance->message ) && is_string( $instance->message ) ) {
        echo wp_kses_post( $instance->message );
    }
    ?>
    <?php $plugin_table->views(); ?>

    <form id="tgmpa-plugins" action="" method="post">
        <input type="hidden" name="tgmpa-page" value="<?php echo esc_attr( $instance->menu ); ?>" />
        <input type="hidden" name="plugin_status" value="<?php echo esc_attr( $plugin_table->view_context ); ?>" />
        <?php $plugin_table->display(); ?>
    </form>
</div>
