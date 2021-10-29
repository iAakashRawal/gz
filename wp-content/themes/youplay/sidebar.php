<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package Youplay
 */

$layout = yp_get_layout_data();
if ( ! $layout['is_active_sidebar'] ) {
    return;
}
?>

<div class="<?php echo esc_attr($layout['sidebar_class']); ?>">
    <?php
    dynamic_sidebar( $layout['sidebar_name'] );
    ?>
</div>
