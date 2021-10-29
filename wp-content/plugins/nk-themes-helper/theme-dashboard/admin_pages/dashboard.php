<?php
/**
 * Theme Dashboard template
 *
 * @package nk-themes-helper
 */

// phpcs:disable

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// minimum requirements.
$min_requirements = nkth()->theme_dashboard()->options['min_requirements'];
?>


<h1>
    <?php
    // translators: %s - theme name and version.
    printf( esc_html__( 'Welcome to %s', 'nk-themes-helper' ), esc_html( nkth()->theme_dashboard()->theme_name ) . ' <span class="nk-theme-version">v ' . esc_html( nkth()->theme_dashboard()->theme_version ) . '</span>' );
    ?>
</h1>

<div class="about-text">
    <p class="about-text">
        <?php printf( esc_html( nkth()->theme_dashboard()->options['top_message'] ), esc_html( nkth()->theme_dashboard()->theme_name ) ); ?>
    </p>

    <?php if ( nkth()->theme_dashboard()->options['top_button_url'] && nkth()->theme_dashboard()->options['top_button_text'] ) : ?>
        <a href="<?php echo esc_url( nkth()->theme_dashboard()->options['top_button_url'] ); ?>" class="button button-primary" target="_blank">
            <?php printf( esc_html( nkth()->theme_dashboard()->options['top_button_text'] ), esc_html( nkth()->theme_dashboard()->theme_name ) ); ?>
        </a>
    <?php endif; ?>
</div>

<div class="nk-dashboard-widgets">
    <input id="nk-theme-deactivate-reload" type="hidden" value="<?php echo esc_attr( admin_url( 'admin.php?page=nk-theme' ) ); ?>">
    <input id="nk-theme-purchase-platform-reload" type="hidden" value="<?php echo esc_attr( admin_url( 'admin.php?page=nk-theme' ) ); ?>">
    <div class="clear"></div>

    <?php if ( nkth()->theme_dashboard()->is_show_ask_for_review_notice() ) : ?>
        <div class="nk-dashboard-widget">
            <div class="nk-dashboard-widget-title">
                <mark><?php esc_html_e( 'Leave us a Rating', 'nk-themes-helper' ); ?></mark>
            </div>
            <div class="nk-dashboard-widget-content">
                <p>
                    <?php
                    echo sprintf(
                        // translators: %1s - theme name.
                        esc_html__( 'We\'ve noticed that you\'ve been using %1s for some time now, we hope you are loving it!', 'nk-themes-helper' ),
                        esc_html( nkth()->theme_dashboard()->theme_name )
                    );
                    ?>
                </p>
                <p>
                    <?php
                    echo wp_kses_post( __( 'We will be grateful if you can <strong>leave us 5 stars</strong>. It will really help us in promoting the theme.', 'nk-themes-helper' ) );
                    ?>
                </p>
                <p>
                    <a href="<?php echo esc_url( nkth()->theme_dashboard()->theme_uri ); ?>" class="button button-primary nk-theme-disable-leave-a-rating" target="_blank">
                        <?php echo esc_html__( 'Leave a Rating', 'nk-themes-helper' ); ?>
                    </a>
                    <a href="#" class="button nk-theme-disable-leave-a-rating">
                        <?php echo esc_html__( 'Don\'t show this again', 'nk-themes-helper' ); ?>
                    </a>
                </p>
            </div>
        </div>
    <?php endif; ?>

    <?php if ( nkth()->theme_dashboard()->theme_id ) : ?>
        <?php if ( 'elements' !== nkth()->theme_dashboard()->options['purchase_platform'] ) : ?>
            <?php if ( ! nkth()->theme_dashboard()->is_envato_elements || nkth()->theme_dashboard()->activation()->active || nkth()->theme_dashboard()->options['purchase_platform'] ) : ?>
                <div class="nk-dashboard-widget">
                    <div class="nk-dashboard-widget-title">
                        <?php
                        if ( nkth()->theme_dashboard()->activation()->active ) {
                            ?>
                            <span class="nk-dashboard-widget-title-badge yes"><i class="fa fa-thumbs-up"></i> <?php esc_html_e( 'Activated', 'nk-themes-helper' ); ?></span>
                            <?php
                        } elseif ( ! nkth()->theme_dashboard()->is_envato_elements ) {
                            ?>
                            <span class="nk-dashboard-widget-title-badge error"><i class="fa fa-exclamation-triangle"></i> <?php esc_html_e( 'Not activated', 'nk-themes-helper' ); ?></span>
                            <?php
                        }
                        ?>
                        <mark><?php esc_html_e( 'Activation', 'nk-themes-helper' ); ?></mark>
                    </div>
                    <div class="nk-dashboard-widget-content">
                        <p>
                            <?php
                            echo wp_kses(
                                // translators: %s - theme name.
                                sprintf( __( 'By activating %s you will unlock premium options - <strong>direct theme updates</strong> and <strong>demo import</strong>.', 'nk-themes-helper' ), nkth()->theme_dashboard()->theme_name ),
                                array(
                                    'strong' => array(),
                                )
                            );
                            ?>
                        </p>

                        <?php
                        if ( nkth()->theme_dashboard()->activation()->active ) {
                            // EDD Theme.
                            if ( nkth()->theme_dashboard()->activation()->edd_license ) {
                                ?>
                                <p class="clear"></p>
                                <span id="nk-theme-deactivate-license" class="button button-secondary pull-left">
                                <?php
                                // translators: %s - theme name.
                                echo sprintf( esc_html__( 'Deactivate %s', 'nk-themes-helper' ), esc_html( nkth()->theme_dashboard()->theme_name ) );
                                ?>
                                </span>
                                <span class="spinner pull-left"></span>
                                <div class="clear"></div>
                                <?php

                                // Envato Theme.
                            } else {
                                ?>
                                <a id="nk-theme-deactivate-license" class="button button-secondary pull-left" href="<?php echo esc_attr( 'https://nkdev.info/licenses/?vatomi_item_id=' . nkth()->theme_dashboard()->theme_id . '&vatomi_action=deactivate&vatomi_license=' . esc_attr( nkth()->theme_dashboard()->activation()->purchase_code ) . '&vatomi_redirect=' . urlencode( admin_url( 'admin.php?page=nk-theme' ) ) ); ?>">
                                    <?php
                                    // translators: %s - theme name.
                                    echo sprintf( esc_html__( 'Deactivate %s', 'nk-themes-helper' ), esc_html( nkth()->theme_dashboard()->theme_name ) );
                                    ?>
                                </a>
                                <div class="clear"></div>
                                <?php
                            }
                            ?>
                            <?php
                        } else {
                            ?>
                            <p>
                                <a href="<?php echo esc_attr( 'https://nkdev.info/licenses/?vatomi_item_id=' . nkth()->theme_dashboard()->theme_id . '&vatomi_action=activate&vatomi_site=' . urlencode( home_url( '/' ) ) . '&vatomi_redirect=' . urlencode( admin_url( 'admin.php?page=nk-theme' ) ) ); ?>" class="button button-primary">
                                    <?php
                                    // translators: %s - theme name.
                                    echo sprintf( esc_html__( 'Activate %s with Envato', 'nk-themes-helper' ), esc_html( nkth()->theme_dashboard()->theme_name ) );
                                    ?>
                                </a>

                                <?php if ( nkth()->theme_dashboard()->options['edd_name'] ) : ?>
                                        <a href="#" id="nk-themefromsite-activation-toggle">
                                            <?php
                                            // translators: %s - theme name.
                                            echo sprintf( esc_html__( 'or activate %s purchased on https://nkdev.info/', 'nk-themes-helper' ), esc_html( nkth()->theme_dashboard()->theme_name ) );
                                            ?>
                                        </a>
                                        <input id="nk-themefromsite-activate-license" type="text" value="" placeholder="Enter License Key">

                                        <span id="nk-themefromsite-activate" class="button button-primary pull-left"><?php esc_html_e( 'Activate', 'nk-themes-helper' ); ?></span>
                                        <span class="spinner pull-left"></span>
                                        <p class="clear"></p>
                                        <input id="nk-themefromsite-activate-reload" type="hidden" value="<?php echo esc_attr( admin_url( 'admin.php?page=nk-theme' ) ); ?>">
                                    <?php endif; ?>
                                </p>
                                <p>
                                <em>
                                    <?php esc_html_e( 'Don\'t have valid license yet?', 'nk-themes-helper' ); ?>
                                    <a href="<?php echo esc_url( nkth()->theme_dashboard()->theme_uri ); ?>" target="_blank">
                                        <?php
                                        // translators: %s - theme name.
                                        echo sprintf( esc_html__( 'Purchase %s License', 'nk-themes-helper' ), esc_html( nkth()->theme_dashboard()->theme_name ) );
                                        ?>
                                    </a>
                                </em>
                            </p>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            <?php else : ?>
                <div class="nk-dashboard-widget">
                    <div class="nk-dashboard-widget-title">
                        <?php echo esc_html__( 'Purchase Platform' ); ?>
                    </div>
                    <div class="nk-dashboard-widget-content">
                        <p>
                            <?php echo esc_html__( 'Select platform from where you purchased the theme:' ); ?>
                        </p>
                        <span data-nk-purchase-platform="themeforest" class="button button-secondary">
                            <?php
                            echo esc_html__( 'Themeforest', 'nk-themes-helper' );
                            ?>
                        </span>
                        <span data-nk-purchase-platform="elements" class="button button-secondary">
                            <?php
                            echo esc_html__( 'Envato Elements', 'nk-themes-helper' );
                            ?>
                        </span>
                        <span class="spinner pull-right"></span>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        <div class="nk-dashboard-widget">
            <div class="nk-dashboard-widget-title">
                <?php
                if ( nkth()->theme_dashboard()->updater()->is_update_available() ) {
                    ?>
                    <span class="nk-dashboard-widget-title-badge warning"><i class="fa fa-exclamation-triangle"></i> <?php esc_html_e( 'Update Available', 'nk-themes-helper' ); ?></span>
                    <?php
                } else {
                    ?>
                    <span class="nk-dashboard-widget-title-badge yes"><i class="fa fa-thumbs-up"></i> <?php esc_html_e( 'Theme is up to date', 'nk-themes-helper' ); ?></span>
                    <?php
                }
                ?>
                <mark><?php esc_html_e( 'Update', 'nk-themes-helper' ); ?></mark>
            </div>
            <div class="nk-dashboard-widget-content">
                <p>
                    <strong><?php esc_html_e( 'Installed Version:', 'nk-themes-helper' ); ?></strong>
                    <br>
                    <?php echo esc_html( nkth()->theme_dashboard()->theme_version ); ?>
                </p>
                <p>
                    <strong><?php esc_html_e( 'Latest Version:', 'nk-themes-helper' ); ?></strong>
                    <br>
                    <?php echo esc_html( nkth()->theme_dashboard()->updater()->get_latest_theme_version() ); ?>
                </p>
                <?php

                // major update notice.
                $is_major_update = false;
                $version_arr     = explode( '.', nkth()->theme_dashboard()->theme_version );
                $new_version_arr = explode( '.', nkth()->theme_dashboard()->updater()->get_latest_theme_version() );

                if ( is_array( $version_arr ) && count( $version_arr ) > 1 && is_array( $new_version_arr ) && count( $new_version_arr ) > 1 ) {
                    $is_major_update = version_compare( $new_version_arr[0] . '.' . $new_version_arr[1], $version_arr[0] . '.' . $version_arr[1], '>' );
                }

                if ( $is_major_update ) {
                    ?>
                    <p class="nk-dashboard-alert"><?php echo esc_html__( 'This is a major theme update, please check theme changelog before updating!', 'nk-themes-helper' ); ?></p>
                    <?php
                }

                if ( 'elements' !== nkth()->theme_dashboard()->options['purchase_platform'] ) {
                    if ( nkth()->theme_dashboard()->updater()->is_update_available() ) {
                        if ( nkth()->theme_dashboard()->activation()->active ) {
                            $update_url = wp_nonce_url( admin_url( 'update.php?action=upgrade-theme&amp;theme=' . urlencode( nkth()->theme_dashboard()->theme_slug ) ), 'upgrade-theme_' . nkth()->theme_dashboard()->theme_slug );
                            ?>
                            <a href="<?php echo esc_attr( $update_url ); ?>" class="button button-primary">
                                <?php esc_html_e( 'Update Now', 'nk-themes-helper' ); ?>
                            </a>
                            <?php
                        } elseif ( ! nkth()->theme_dashboard()->is_envato_elements ) {
                            ?>
                            <span class="button button-primary disabled">
                                <?php esc_html_e( 'Update Now', 'nk-themes-helper' ); ?>
                            </span>
                            <?php
                        }
                    } elseif ( ! nkth()->theme_dashboard()->is_envato_elements ) {
                        ?>
                        <span class="button disabled">
                            <?php esc_html_e( 'Update Now', 'nk-themes-helper' ); ?>
                        </span>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if ( nkth()->theme_dashboard()->theme_changelog ) : ?>
        <div class="nk-dashboard-widget">
            <div class="nk-dashboard-widget-title">
                <a href="<?php echo esc_url( nkth()->theme_dashboard()->theme_changelog ); ?>" class="nk-dashboard-widget-title-badge warning"><i class="fa fa-external-link"></i> <?php esc_html_e( 'Read Online', 'nk-themes-helper' ); ?></a>
                <mark><?php esc_html_e( 'Changelog', 'nk-themes-helper' ); ?></mark>
            </div>
            <div class="nk-dashboard-widget-content">
                <div class="nk-theme-changelog" data-nk-changelog="<?php echo esc_url( nkth()->theme_dashboard()->theme_changelog ); ?>">
                    <div class="nk-theme-changelog-spinner" style="display: none;">
                        <span class="spinner pull-left is-active"></span>
                        <div class="clear"></div>
                    </div>
                    <div class="nk-theme-changelog-list"></div>
                    <button class="nk-theme-changelog-show-full-toggle button" style="display: none;">
                        <?php echo esc_html__( 'Expand', 'nk-themes-helper' ); ?>
                    </button>
                    <div class="nk-theme-changelog-not-loaded" style="display: none;"><?php echo esc_html__( 'Changelog data can not be loaded. Please, follow the link above.', 'nk-themes-helper' ); ?></div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="nk-dashboard-widget">
        <?php

        // requirements check.
        $memory           = nkth()->let_to_num( WP_MEMORY_LIMIT );
        $min_memory       = nkth()->let_to_num( $min_requirements['memory_limit'] );
        $req_memory_limit = $memory >= $min_memory;

        $req_php_ver = true;
        if ( function_exists( 'phpversion' ) ) {
            $php_ver     = phpversion();
            $req_php_ver = version_compare( $php_ver, $min_requirements['php_version'], '>=' );
        }

        $req_max_exec_time = true;
        if ( function_exists( 'ini_get' ) ) {
            $time_limit        = ini_get( 'max_execution_time' );
            $req_max_exec_time = $time_limit >= $min_requirements['max_execution_time'];
        }

        $req_wp_remote_get      = true;
        $wp_remote_get_response = wp_remote_get( 'https://nkdev.info/' );
        if ( is_wp_error( $wp_remote_get_response ) ) {
            $req_wp_remote_get = false;
        }

        $req_all_ok = $req_memory_limit && $req_php_ver && $req_max_exec_time && $req_wp_remote_get;

        ?>

        <div class="nk-dashboard-widget-title">
            <?php
            if ( $req_all_ok ) {
                ?>
                <span class="nk-dashboard-widget-title-badge yes"><i class="fa fa-thumbs-up"></i> <?php esc_html_e( 'No Problems', 'nk-themes-helper' ); ?></span>
                <?php
            } else {
                ?>
                <span class="nk-dashboard-widget-title-badge warning"><i class="fa fa-exclamation-triangle"></i> <?php esc_html_e( 'Can be improved', 'nk-themes-helper' ); ?></span>
                <?php
            }
            ?>
            <mark><?php esc_html_e( 'Recommendations', 'nk-themes-helper' ); ?></mark>
        </div>
        <div class="nk-dashboard-widget-content">
            <div class="nk-theme-requirements">
                <table class="widefat" cellspacing="0">
                    <tbody>
                        <tr>
                            <td><?php esc_html_e( 'WP Memory Limit:', 'nk-themes-helper' ); ?></td>
                            <td>
                            <?php
                            if ( $req_memory_limit ) {
                                echo '<mark class="yes"><i class="fa fa-check-circle"></i> ' . esc_html( size_format( $memory ) ) . '</mark>';
                            } else {
                                echo '<mark class="nk-drop"><i class="fa fa-times-circle"></i> ' . esc_html( size_format( $memory ) ) . ' ';
                                echo '<small>' . esc_html__( '[more info]', 'nk-themes-helper' ) . '</small>';
                                echo '<span class="nk-drop-cont" style="display: none;">';
                                echo sprintf(
                                    // translators: %s - memory.
                                    esc_html__( 'We recommend setting memory to at least %s.', 'nk-themes-helper' ),
                                    '<strong>' . esc_html( size_format( $min_memory ) ) . '</strong>'
                                );
                                echo ' <br> ';
                                echo sprintf(
                                    // translators: %s - url to help article.
                                    esc_html__( 'See more: %s', 'nk-themes-helper' ),
                                    sprintf( '<a href="http://codex.wordpress.org/Editing_wp-config.php#Increasing_memory_allocated_to_PHP" target="_blank">%s</a>', esc_html__( 'Increasing memory allocated to PHP.', 'nk-themes-helper' ) )
                                );
                                echo '</span>';
                                echo '</mark>';
                            }
                            ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e( 'PHP Version:', 'nk-themes-helper' ); ?></td>
                            <td>
                            <?php
                            if ( function_exists( 'phpversion' ) ) {
                                if ( $req_php_ver ) {
                                    echo '<mark class="yes"><i class="fa fa-check-circle"></i> ' . esc_html( $php_ver ) . '</mark>';
                                } else {
                                    echo '<mark class="nk-drop">';
                                    echo '<i class="fa fa-times-circle"></i> ' . esc_html( $php_ver );
                                    echo ' <small>' . esc_html__( '[more info]', 'nk-themes-helper' ) . '</small>';
                                    echo '<span class="nk-drop-cont" style="display: none;">';
                                    // translators: %s - php version.
                                    echo sprintf( esc_html__( 'We recommend upgrade php version to at least %s.', 'nk-themes-helper' ), esc_html( $min_requirements['php_version'] ) );
                                    echo '</span>';
                                    echo '</mark>';
                                }
                            }
                            ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e( 'PHP Time Limit:', 'nk-themes-helper' ); ?></td>
                            <td>
                            <?php
                            if ( function_exists( 'ini_get' ) ) :
                                if ( $req_max_exec_time ) {
                                    echo '<mark class="yes"><i class="fa fa-check-circle"></i> ' . esc_html( $time_limit ) . '</mark>';
                                } else {
                                    echo '<mark class="nk-drop">';
                                    echo '<i class="fa fa-times-circle"></i> ' . esc_html( $time_limit );
                                    echo ' <small>' . esc_html__( '[more info]', 'nk-themes-helper' ) . '</small>';
                                    echo '<span class="nk-drop-cont" style="display: none;">';
                                    // translators: %s - execution time.
                                    echo sprintf( esc_html__( 'We recommend setting max execution time to at least %s.', 'nk-themes-helper' ), esc_html( $min_requirements['max_execution_time'] ) );
                                    echo ' <br> ';
                                    echo sprintf(
                                        // translators: %s - url to help article.
                                        esc_html__( 'See more: %s', 'nk-themes-helper' ),
                                        sprintf( '<a href="http://codex.wordpress.org/Common_WordPress_Errors#Maximum_execution_time_exceeded" target="_blank">%s</a>', esc_html__( 'Increasing max execution to PHP', 'nk-themes-helper' ) )
                                    );
                                    echo '</span>';
                                    echo '</mark>';
                                }
                            endif;
                            ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e( 'WP Remote Get:', 'nk-themes-helper' ); ?></td>
                            <td>
                            <?php
                            if ( $req_wp_remote_get ) {
                                echo '<mark class="yes"><i class="fa fa-check-circle"></i> </mark>';
                            } else {
                                echo '<mark class="nk-drop">';
                                echo '<i class="fa fa-times-circle"></i> ' . esc_html__( 'Failed', 'nk-themes-helper' );
                                echo ' <small>' . esc_html__( '[more info]', 'nk-themes-helper' ) . '</small>';
                                echo '<span class="nk-drop-cont" style="display: none;">';
                                echo esc_html__( 'wp_remote_get() failed. Some theme features may not work. Please contact your hosting provider and make sure that https://nkdev.info/ is not blocked.', 'nk-themes-helper' );
                                echo '</span>';
                                echo '</mark>';
                            }
                            ?>
                                </td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e( 'Child Theme:', 'nk-themes-helper' ); ?></td>
                            <td>
                            <?php
                            if ( nkth()->theme_dashboard()->theme_is_child ) {
                                echo '<mark class="yes"><i class="fa fa-check-circle"></i></mark>';
                            } else {
                                ?>
                                <mark class="nk-drop">
                                    <i class="fa fa-times-circle"></i>
                                    <small><?php esc_html_e( '[more info]', 'nk-themes-helper' ); ?></small>
                                    <span class="nk-drop-cont" style="display: none;">
                                        <?php esc_html_e( 'We recommend use child theme to prevent loosing your customizations after theme update.', 'nk-themes-helper' ); ?>
                                    </span>
                                    </mark>
                                    <?php
                            }
                            ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
