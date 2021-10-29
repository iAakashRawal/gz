<?php
/**
 * Theme Dashboard demos template
 *
 * @package nk-themes-helper
 */

// phpcs:disable

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$demos = nkth()->theme_dashboard()->options['demos'];
wp_enqueue_script( 'thickbox' );
wp_enqueue_script( 'plugin-install' );
wp_enqueue_script( 'event-source-polyfill' );
add_thickbox();

if ( ! nkth()->theme_dashboard()->is_envato_hosted && ! nkth()->theme_dashboard()->is_envato_elements && ! nkth()->theme_dashboard()->activation()->active ) {
    ?>
    <div class="about-description about-description-error">
        <div class="about-notes">
            <?php
            echo sprintf(
                // translators: %s - link to dashboard.
                esc_html__( 'Demos can only be imported in activated theme. Please visit the %s page and activate the theme.', 'nk-themes-helper' ),
                '<a
                href="' . esc_url( admin_url( 'admin.php?page=nk-theme' ) ) . '">' . esc_html__( 'Dashboard', 'nk-themes-helper' ) . '</a>'
            );
            ?>
        </div>
    </div>
    <?php
    return;
}

?>

<div class="about-description">
    <div class="about-notes">
        <?php esc_html_e( 'Important Notes:', 'nk-themes-helper' ); ?>
        <ol>
            <li>
                <?php
                echo sprintf(
                    // translators: %s - link to plugin.
                    esc_html__( 'We recommend import demo on a clean WordPress website. To reset your installation use %s.', 'nk-themes-helper' ),
                    '<a href="' . esc_url( network_admin_url( 'plugin-install.php?tab=plugin-information&plugin=wp-reset&TB_iframe=true&width=600&height=550' ) ) . '" class="thickbox open-plugin-details-modal">' . esc_html__( 'WP Reset Plugin', 'nk-themes-helper' ) . '</a>'
                );
                ?>
            </li>
            <li><?php esc_html_e( 'All recommended and required plugins should be installed.', 'nk-themes-helper' ); ?></li>
            <li>
                <?php
                echo sprintf(
                    // translators: %s - link to plugin.
                    esc_html__( 'After demo data imported, run %s plugin.', 'nk-themes-helper' ),
                    '<a href="' . esc_url( network_admin_url( 'plugin-install.php?tab=plugin-information&plugin=regenerate-thumbnails&TB_iframe=true&width=600&height=550' ) ) . '" class="thickbox open-plugin-details-modal">' . esc_html__( 'Regenerate Thumbnails', 'nk-themes-helper' ) . '</a>'
                );
                ?>
            </li>
            <li><?php esc_html_e( 'Importing a demo provides images, pages, posts, theme options, widgets and more. . Please, wait before the process end, it may take a while.', 'nk-themes-helper' ); ?></li>
        </ol>
    </div>

    <div class="about-success" style="display: none;">
        <?php
        echo sprintf(
            // translators: %s - plugin url and label.
            esc_html__( 'Demo data successfully imported. Now, please install and run %s plugin once.', 'nk-themes-helper' ),
            sprintf( '<a href="' . esc_url( network_admin_url( 'plugin-install.php?tab=plugin-information&plugin=regenerate-thumbnails&TB_iframe=true&width=600&height=550' ) ) . '" class="thickbox open-plugin-details-modal">%s</a>', esc_html__( 'Regenerate Thumbnails', 'nk-themes-helper' ) )
        )
        ?>
    </div>

    <div class="about-error" style="display: none;"></div>
</div>

<div class="nk-import-result"></div>

<div class="feature-section theme-browser rendered nk-demos-list">
    <?php
    // Loop through all demos.
    $active_demo = nkth()->theme_dashboard()->get_option( 'active_demo', null );

    foreach ( $demos as $name => $demo ) {
        $is_active = false;
        if ( $active_demo && $active_demo === $name ) {
            $is_active = true;
        }
        ?>
        <div class="theme <?php echo $is_active ? 'active' : ''; ?>">
            <div class="theme-wrapper">
                <a class="theme-screenshot" target="_blank" href="<?php echo esc_attr( $demo['preview'] ); ?>">
                    <img src="<?php echo esc_attr( $demo['thumbnail'] ); ?>" />
                </a>
                <?php
                // translators: %1s - preview url.
                // translators: %1s - label.
                printf( '<a target="_blank" href="%1s"><span class="more-details">%2s</span></a>', esc_url( $demo['preview'] ), esc_html__( 'Live Preview', 'nk-themes-helper' ) );
                ?>
                <div class="theme-progress">
                    <div class="theme-progress-bar"></div>
                </div>
                <div class="theme-id-container">
                    <h3 class="theme-name" id="<?php echo esc_html( $name ); ?>"><?php echo esc_html( $demo['title'] ); ?></h3>
                    <div class="theme-actions">
                        <?php
                        // translators: %1s - demo name.
                        // translators: %1s - label.
                        printf( '<a class="button button-primary button-demo" data-demo="%s" href="#">%s</a>', esc_attr( strtolower( $name ) ), esc_html__( 'Import', 'nk-themes-helper' ) );
                        ?>
                        <span class="spinner"></span>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
