<?php
/*
 * This is the page users will see logged out.
 * You can edit this, but for upgrade safety you should copy and modify this file into your template folder.
 * The location from within your template folder is plugins/login-with-ajax/ (create these directories if they don't exist)
*/

global $NK_LWA_WIDGET_ID;
if(!isset($NK_LWA_WIDGET_ID)) {
    $NK_LWA_WIDGET_ID = 0;
}
$NK_LWA_WIDGET_ID++;
?>
	<div class="lwa lwa-default"><?php //class must be here, and if this is a template, class name should be that of template directory ?>
        <form class="lwa-form block-content" action="<?php echo esc_attr(LoginWithAjax::$url_login); ?>" method="post">

        	<span class="lwa-status"></span>

            <p>
                <?php esc_html_e( 'Username:','youplay' ) ?>
            </p>
            <div class="youplay-input">
                <input type="text" name="log" />
            </div>

            <p>
                <?php esc_html_e( 'Password:','youplay' ) ?>
            </p>
            <div class="youplay-input">
                <input type="password" name="pwd" />
            </div>

            <?php do_action('login_form'); ?>

            <div class="youplay-checkbox mb-15 ml-5">
                <input type="checkbox" name="rememberme" value="forever" id="rememberme-lwa-<?php echo esc_attr($NK_LWA_WIDGET_ID); ?>" tabindex="103">
                <label for="rememberme-lwa-<?php echo esc_attr($NK_LWA_WIDGET_ID); ?>"><?php esc_html_e( 'Remember Me','youplay' ) ?></label>
            </div>

            <button class="btn btn-sm ml-0 mr-0" name="wp-submit" id="lwa_wp-submit-<?php echo esc_attr($NK_LWA_WIDGET_ID); ?>" tabindex="100">
                <?php esc_html_e('Log In', 'youplay'); ?>
            </button>

            <br>

            <input type="hidden" name="lwa_profile_link" value="<?php echo esc_attr($lwa_data['profile_link']); ?>" />
            <input type="hidden" name="login-with-ajax" value="login" />
            <?php if( !empty($lwa_data['redirect']) ): ?>
                <input type="hidden" name="redirect_to" value="<?php echo esc_url($lwa_data['redirect']); ?>" />
            <?php endif; ?>

            <p></p>
            <?php if( !empty($lwa_data['remember']) ): ?>
                <a class="lwa-links-remember no-fade" href="<?php echo esc_attr(LoginWithAjax::$url_remember); ?>" title="<?php esc_attr_e('Password Lost and Found','youplay') ?>"><?php esc_attr_e('Lost password?','youplay') ?></a>
            <?php endif; ?>

            <?php if(yp_opts('navigation_login_registration_url')) : ?>
                |
                <a href="<?php echo esc_attr(yp_opts('navigation_login_registration_url')); ?>"><?php esc_html_e('Register','youplay') ?></a>
            <?php elseif ( get_option('users_can_register') && !empty($lwa_data['registration']) ) : ?>
                |
                <a href="<?php echo esc_attr(LoginWithAjax::$url_register); ?>" class="lwa-links-register lwa-links-modal no-fade"><?php esc_html_e('Register','youplay') ?></a>
            <?php endif; ?>

        </form>
        <?php if( !empty($lwa_data['remember']) ): ?>
        <form class="lwa-remember block-content" action="<?php echo esc_attr(LoginWithAjax::$url_remember) ?>" method="post" style="display:none;">
        	<div>
        	<span class="lwa-status"></span>
            <table>
                <tr>
                    <td>
                        <strong><?php esc_html_e("Forgotten Password", 'youplay'); ?></strong>
                    </td>
                </tr>
                <tr>
                    <td class="lwa-remember-email">
                        <?php $msg = __("Enter username or email", 'youplay'); ?>
                        <div class="youplay-input">
                            <input type="text" name="user_login" class="lwa-user-remember" value="<?php echo esc_attr($msg); ?>" onfocus="if(this.value == '<?php echo esc_attr($msg); ?>'){this.value = '';}" onblur="if(this.value == ''){this.value = '<?php echo esc_attr($msg); ?>'}" />
                        </div>
                        <?php do_action('lostpassword_form'); ?>
                    </td>
                </tr>
                <tr>
                    <td class="lwa-remember-buttons">
                        <button class="btn btn-sm"><?php esc_html_e("Get New Password", 'youplay'); ?></button>
                        <a href="#" class="lwa-links-remember-cancel"><?php esc_html_e("Cancel", 'youplay'); ?></a>
                        <input type="hidden" name="login-with-ajax" value="remember" />
                    </td>
                </tr>
            </table>
            </div>
        </form>
        <?php endif; ?>
		<?php if( get_option('users_can_register') && !empty($lwa_data['registration']) && $lwa_data['registration'] == 1 ): ?>
		<div class="lwa-register lwa-register-default lwa-modal modal-dialog" style="display:none;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close lwa-modal-close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><?php esc_html_e('Register','youplay') ?></h4>
                </div>
                <div class="modal-body">
                    <form class="lwa-register-form" action="<?php echo esc_attr(LoginWithAjax::$url_register); ?>" method="post">
                        <div>
                        <span class="lwa-status"></span>

                        <p>
                            <?php esc_html_e('Username:','youplay') ?>
                        </p>
                        <div class="youplay-input">
                            <input type="text" name="user_login" id="user_login-<?php echo esc_attr($NK_LWA_WIDGET_ID); ?>" class="input" size="20" tabindex="10" />
                        </div>

                        <p>
                            <?php esc_html_e('E-mail:','youplay') ?>
                        </p>
                        <div class="youplay-input">
                            <input type="text" name="user_email" id="user_email-<?php echo esc_attr($NK_LWA_WIDGET_ID); ?>" class="input" size="25" tabindex="20" />
                        </div>

                        <?php do_action('register_form'); ?>
                        <?php do_action('lwa_register_form'); ?>

                        <p>
                            <em class="lwa-register-tip"><?php esc_html_e('A password will be e-mailed to you.','youplay') ?></em>
                        </p>

                        <button class="btn ml-3" name="wp-submit" id="wp-submit-<?php echo esc_attr($NK_LWA_WIDGET_ID); ?>" tabindex="100"><?php esc_html_e('Register', 'youplay'); ?></button>

                        <input type="hidden" name="login-with-ajax" value="register" />
                        </div>
                    </form>
                </div>
            </div>
		</div>
		<?php endif; ?>
	</div>
