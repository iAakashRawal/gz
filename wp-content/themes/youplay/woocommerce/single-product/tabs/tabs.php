<?php
/**
 * Single Product tabs
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

/**
 * Filter tabs and allow third parties to add their own
 *
 * Each tab is an array containing title, callback and priority.
 * @see woocommerce_default_product_tabs()
 */
$tabs = apply_filters( 'woocommerce_product_tabs', array() );

if ( ! empty( $tabs ) ) : ?>
	<div class="clearfix"></div>
	<?php foreach ( $tabs as $key => $tab ) : ?>

		<div id="tab-<?php echo esc_html($key) ?>">
			<?php call_user_func( $tab['callback'], $key, $tab ) ?>
		</div>

	<?php endforeach; ?>

<?php endif; ?>
