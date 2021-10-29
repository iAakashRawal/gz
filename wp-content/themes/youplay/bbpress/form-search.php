<?php

/**
 * Search
 *
 * @package bbPress
 * @subpackage Theme
 */
?>
<form role="search" method="get" id="bbp-search-form" action="<?php bbp_search_url(); ?>">
  <p><?php _e( 'Search by Forum:', 'youplay' ); ?></p>

    <input type="hidden" name="action" value="bbp-search-request" />
    <input type="hidden" name="action" value="bbp-search-request" />

  <div class="youplay-input pull-left">
    <input tabindex="<?php bbp_tab_index(); ?>" type="text" value="<?php echo esc_attr( bbp_get_search_terms() ); ?>" name="bbp_search" id="bbp_search" />
  </div>
  <button class="btn pull-right" tabindex="<?php bbp_tab_index(); ?>" id="bbp_search_submit"><?php esc_attr_e( 'Search', 'youplay' ); ?></button>

  <div class="clearfix"></div>
</form>
