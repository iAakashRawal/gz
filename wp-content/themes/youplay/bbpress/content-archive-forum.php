<?php

/**
 * Archive Forum Content Part
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<div>

	<?php yp_bb_breadcrumb( false ); ?>

	<?php if ( bbp_allow_search() ) : ?>

		<div class="pull-right">
			<?php bbp_get_template_part( 'form', 'search' ); ?>
		</div>
	  <div class="clearfix"></div>
	  
	<?php endif; ?>

	<div class="clearfix"></div>

	<?php bbp_forum_subscription_link(); ?>

	<?php do_action( 'bbp_template_before_forums_index' ); ?>

	<?php if ( bbp_has_forums() ) : ?>

		<?php bbp_get_template_part( 'loop',     'forums'    ); ?>

	<?php else : ?>

		<?php bbp_get_template_part( 'feedback', 'no-forums' ); ?>

	<?php endif; ?>

	<?php do_action( 'bbp_template_after_forums_index' ); ?>

</div>
