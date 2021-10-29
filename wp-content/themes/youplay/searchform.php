<?php
/**
 * The theme searchform
 *
 * @package Youplay
 */
?>
<form method="get" action="<?php echo esc_url(home_url()); ?>">
  <div class="youplay-input">
    <input type="text" name="s" placeholder="<?php _e('Site Search', 'youplay'); ?>">
  </div>
</form>
