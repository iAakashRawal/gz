<?php

/**
 * User Roles Profile Edit Part
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<p><?php _e( 'Blog Role', 'youplay' ) ?></p>
<div class="youplay-select">
  <?php bbp_edit_user_blog_role(); ?>
</div>


<p><?php _e( 'Forum Role', 'youplay' ) ?></p>
<div class="youplay-select">
  <?php bbp_edit_user_forums_role(); ?>
</div>
