<?php

/**
 * Password Protected
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<div>
	<fieldset class="bbp-form" id="bbp-protected">
		<Legend><?php _e( 'Protected', 'youplay' ); ?></legend>

		<?php echo get_the_password_form(); ?>

	</fieldset>
</div>
