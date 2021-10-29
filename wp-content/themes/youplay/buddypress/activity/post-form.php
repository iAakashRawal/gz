<?php
/**
 * BuddyPress - Activity Post Form
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

?>

<div class="clearfix"></div>
<div class="comments-block">
	<form action="<?php bp_activity_post_form_action(); ?>" method="post" id="whats-new-form" name="whats-new-form" role="complementary" class="comment-form">

		<?php

		/**
		 * Fires before the activity post form.
		 *
		 * @since 1.2.0
		 */
		do_action( 'bp_before_activity_post_form' ); ?>

		<div class="comment-cont clearfix">

			<p class="activity-greeting"><?php if ( bp_is_group() )
				printf( __( "What's new in %s, %s?", 'youplay' ), bp_get_group_name(), bp_get_user_firstname( bp_get_loggedin_user_fullname() ) );
			else
				printf( __( "What's new, %s?", 'youplay' ), bp_get_user_firstname( bp_get_loggedin_user_fullname() ) );
			?></p>

			<div id="whats-new-content">
				<div id="whats-new-textarea" class="youplay-textarea">
					<textarea class="bp-suggestions" name="whats-new" id="whats-new" cols="50" rows="10" placeholder="<?php esc_attr_e( 'Post what\'s new', 'youplay' ); ?>"
						<?php if ( bp_is_group() ) : ?>data-suggestions-group-id="<?php echo esc_attr( (int) bp_get_current_group_id() ); ?>" <?php endif; ?>
					><?php if ( isset( $_GET['r'] ) ) : ?>@<?php echo esc_textarea( $_GET['r'] ); ?> <?php endif; ?></textarea>
				</div>

				<div id="whats-new-options">
					<div id="whats-new-submit">
                        <span class="btn btn-sm btn-default">
                            <?php esc_html_e( 'Post Update', 'youplay' ); ?>
                            <input type="submit" name="aw-whats-new-submit" id="aw-whats-new-submit" value="<?php esc_attr_e( 'Post Update', 'youplay' ); ?>" />
                        </span>
					</div>

					<?php if ( bp_is_active( 'groups' ) && !bp_is_my_profile() && !bp_is_group() ) : ?>

						<div id="whats-new-post-in-box">

							<?php _e( 'Post in', 'youplay' ); ?>:

							<label for="whats-new-post-in" class="bp-screen-reader-text"><?php _e( 'Post in', 'youplay' ); ?></label>
							<select id="whats-new-post-in" name="whats-new-post-in">
								<option selected="selected" value="0"><?php _e( 'My Profile', 'youplay' ); ?></option>

								<?php if ( bp_has_groups( 'user_id=' . bp_loggedin_user_id() . '&type=alphabetical&max=100&per_page=100&populate_extras=0&update_meta_cache=0' ) ) :
									while ( bp_groups() ) : bp_the_group(); ?>

										<option value="<?php bp_group_id(); ?>"><?php bp_group_name(); ?></option>

									<?php endwhile;
								endif; ?>

							</select>
						</div>
						<input type="hidden" id="whats-new-post-object" name="whats-new-post-object" value="groups" />

					<?php elseif ( bp_is_group_activity() ) : ?>

						<input type="hidden" id="whats-new-post-object" name="whats-new-post-object" value="groups" />
						<input type="hidden" id="whats-new-post-in" name="whats-new-post-in" value="<?php bp_group_id(); ?>" />

					<?php endif; ?>

					<?php

					/**
					 * Fires at the end of the activity post form markup.
					 *
					 * @since 1.2.0
					 */
					do_action( 'bp_activity_post_form_options' ); ?>

				</div><!-- #whats-new-options -->
			</div><!-- #whats-new-content -->

			<?php wp_nonce_field( 'post_update', '_wpnonce_post_update' ); ?>
			<?php

			/**
			 * Fires after the activity post form.
			 *
			 * @since 1.2.0
			 */
			do_action( 'bp_after_activity_post_form' ); ?>
		</div>

	</form><!-- #whats-new-form -->
</div>
