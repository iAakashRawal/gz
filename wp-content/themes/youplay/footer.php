<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Youplay
 */

?>

        <?php if ( !is_404() ) : ?>
            <div class="clearfix"></div>

            <!-- Footer -->
            <footer id="footer" class="<?php echo yp_opts('footer_parallax') ? 'youplay-footer-parallax' : ''; ?>">
                <?php if( yp_opts('footer_show_background') && yp_opts('footer_background') ): ?>
                    <div class="image" data-speed="<?php echo esc_attr( yp_opts('footer_parallax_speed') ) ?>">
                        <?php
                        echo youplay_get_image( yp_opts('footer_background'), '1920x1080', false, array(
                            'class' => 'jarallax-img'
                        ) );
                        ?>
                    </div>
                <?php endif; ?>

                <div class="wrapper">
                    <?php if(yp_opts('footer_widgets')): ?>
                        <?php
                        $widget_1_width = yp_opts('footer_widget_1_width');
                        $widget_2_width = yp_opts('footer_widget_2_width');
                        $widget_3_width = yp_opts('footer_widget_3_width');
                        $widget_4_width = yp_opts('footer_widget_4_width');
                        $any_widget_valid = $widget_1_width !== 0 && is_active_sidebar('footer_widgets_1') || $widget_2_width !== 0 && is_active_sidebar('footer_widgets_2') || $widget_3_width !== 0 && is_active_sidebar('footer_widgets_3') || $widget_4_width !== 0 && is_active_sidebar('footer_widgets_4');

                        if ($any_widget_valid) :
                        ?>
                            <div class="widgets">
                                <div class="container">
                                    <div class="row">

                                        <?php if ($widget_1_width !== 0 && is_active_sidebar('footer_widgets_1')) : ?>
                                            <div class="col-md-<?php echo esc_attr($widget_1_width); ?>">
                                                <?php dynamic_sidebar('footer_widgets_1'); ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if ($widget_2_width !== 0 && is_active_sidebar('footer_widgets_2')) : ?>
                                            <div class="col-md-<?php echo esc_attr($widget_2_width); ?>">
                                                <?php dynamic_sidebar('footer_widgets_2'); ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if ($widget_3_width !== 0 && is_active_sidebar('footer_widgets_3')) : ?>
                                            <div class="col-md-<?php echo esc_attr($widget_3_width); ?>">
                                                <?php dynamic_sidebar('footer_widgets_3'); ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if ($widget_4_width !== 0 && is_active_sidebar('footer_widgets_4')) : ?>
                                            <div class="col-md-<?php echo esc_attr($widget_4_width); ?>">
                                                <?php dynamic_sidebar('footer_widgets_4'); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if(yp_opts('footer_social')): ?>
                        <!-- Social Buttons -->
                        <div class="social">
                            <div class="container">

                                <?php echo wp_kses_post(yp_opts('footer_social_text')); ?>

                                <?php
                                    // chech how many social buttons anabled and create additional offset or hide all buttons
                                    $social_buttons = 4;
                                    if(!yp_opts('footer_social_fb')) {
                                        $social_buttons--;
                                    }
                                    if(!yp_opts('footer_social_tw')) {
                                        $social_buttons--;
                                    }
                                    if(!yp_opts('footer_social_gp')) {
                                        $social_buttons--;
                                    }
                                    if(!yp_opts('footer_social_yt')) {
                                        $social_buttons--;
                                    }
                                ?>

                                <?php if($social_buttons != 0): ?>
                                    <div class="social-icons">
                                        <?php if(yp_opts('footer_social_fb')): ?>
                                            <div class="social-icon">
                                                <a href="<?php echo esc_url(yp_opts('footer_social_fb_url')); ?>" target="_blank">
                                                    <i class="<?php echo esc_attr(yp_opts('footer_social_fb_icon')); ?>"></i>
                                                    <span><?php echo esc_html(yp_opts('footer_social_fb_label')); ?></span>
                                                </a>
                                            </div>
                                        <?php endif; ?>

                                        <?php if(yp_opts('footer_social_tw')): ?>
                                            <div class="social-icon">
                                                <a href="<?php echo esc_url(yp_opts('footer_social_tw_url')); ?>" target="_blank">
                                                    <i class="<?php echo esc_attr(yp_opts('footer_social_tw_icon')); ?>"></i>
                                                    <span><?php echo esc_html(yp_opts('footer_social_tw_label')); ?></span>
                                                </a>
                                            </div>
                                        <?php endif; ?>

                                        <?php if(yp_opts('footer_social_gp')): ?>
                                            <div class="social-icon">
                                                <a href="<?php echo esc_url(yp_opts('footer_social_gp_url')); ?>" target="_blank">
                                                    <i class="<?php echo esc_attr(yp_opts('footer_social_gp_icon')); ?>"></i>
                                                    <span><?php echo esc_html(yp_opts('footer_social_gp_label')); ?></span>
                                                </a>
                                            </div>
                                        <?php endif; ?>

                                        <?php if(yp_opts('footer_social_yt')): ?>
                                            <div class="social-icon">
                                                <a href="<?php echo esc_url(yp_opts('footer_social_yt_url')); ?>" target="_blank">
                                                    <i class="<?php echo esc_attr(yp_opts('footer_social_yt_icon')); ?>"></i>
                                                    <span><?php echo esc_html(yp_opts('footer_social_yt_label')); ?></span>
                                                </a>
                                            </div>
                                        <?php endif; ?>

                                        <?php if(yp_opts('footer_social_fifth')): ?>
                                            <div class="social-icon">
                                                <a href="<?php echo esc_url(yp_opts('footer_social_fifth_url')); ?>" target="_blank">
                                                    <i class="<?php echo esc_attr(yp_opts('footer_social_fifth_icon')); ?>"></i>
                                                    <span><?php echo esc_html(yp_opts('footer_social_fifth_label')); ?></span>
                                                </a>
                                            </div>
                                        <?php endif; ?>

                                        <?php if(yp_opts('footer_social_sixth')): ?>
                                            <div class="social-icon">
                                                <a href="<?php echo esc_url(yp_opts('footer_social_sixth_url')); ?>" target="_blank">
                                                    <i class="<?php echo esc_attr(yp_opts('footer_social_sixth_icon')); ?>"></i>
                                                    <span><?php echo esc_html(yp_opts('footer_social_sixth_label')); ?></span>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

                            </div>
                        </div>
                        <!-- /Social Buttons -->
                    <?php endif; ?>

                    <!-- Copyright -->
                    <div class="copyright">
                        <div class="container">
                            <?php echo wp_kses_post(yp_opts('footer_text')); ?>
                        </div>
                    </div>
                    <!-- /Copyright -->

                </div>
            </footer>
            <!-- /Footer -->
        <?php endif; ?>

    </section>
    <!-- /Content -->


    <!-- Search Block -->
    <div class="search-block">
        <a href="#" class="search-toggle glyphicon glyphicon-remove"></a>
        <?php get_search_form(); ?>
    </div>
    <!-- /Search Block -->

    <?php wp_footer(); ?>
</body>
</html>
