<?php
/*
 * This is the page users will see logged in.
 * You can edit this, but for upgrade safety you should copy and modify this file into your template folder.
 * The location from within your template folder is plugins/login-with-ajax/ (create these directories if they don't exist)
*/
?>
<div class="lwa block-content">
	<?php
		global $current_user;
		wp_get_current_user();
	?>
	<span class="lwa-title-sub" style="display:none"><?php echo __( 'Hi', 'youplay' ) . " " . $current_user->display_name  ?></span>
	<table>
		<tr>
			<td>
				<ul class="list-unstyled lwa-profile-links">
				<?php
					if ( $lwa_data['profile_link'] == '1' ) :
						/* BuddyPress */
						if( function_exists('buddypress') ){
							$bp = buddypress();

							// Loop through each navigation item.
							foreach ( (array) $bp->members->nav->get_primary() as $nav_item ) {
								$title = $nav_item->name;
								$url = $nav_item->link;

								// remove default badge
								$title = preg_replace("#<span[^>]*>(.*)</span>#isU", "", $title);

								// show messages count
								if($nav_item->slug === 'messages') {
									$messages_count = bp_get_total_unread_messages_count();
									if($messages_count) {
										$title .= ' <span class="badge bg-default ml-0 mnt-2">' . $messages_count . '</span>';
									}
								}

								// show friends requests count
								if($nav_item->slug === 'friends') {
									$friends_requests_count = bp_friend_get_total_requests_count();
									if($friends_requests_count) {
										$title .= ' <span class="badge bg-default ml-0 mnt-2">' . $friends_requests_count . '</span>';
										$url = $bp->members->nav->get('friends/requests');
										$url = $url['link'];
									}
								}

								// show groups requests count
								if($nav_item->slug === 'groups') {
									$groups_requests_count = groups_get_invites_for_user(bp_loggedin_user_id());
									$groups_requests_count = $groups_requests_count['total'];
									if($groups_requests_count) {
										$title .= ' <span class="badge bg-default ml-0 mnt-2">' . $groups_requests_count . '</span>';
										$url = $bp->members->nav->get('groups/invites');
										$url = $url['link'];
									}
								}

								// show total notifications
								if($nav_item->slug === 'notifications') {
									$notifications_count = bp_notifications_get_unread_notification_count( bp_loggedin_user_id() );
									if($notifications_count) {
										$title .= ' <span class="badge bg-default ml-0 mnt-2">' . $notifications_count . '</span>';
									}
								}

								// Echo out the final list item.
								echo apply_filters_ref_array( 'bp_get_loggedin_user_nav_' . $nav_item->css_id, array( '<li id="li-nav-' . $nav_item->css_id . '"><a id="my-' . $nav_item->css_id . '" href="' . esc_url($url) . '">' . $title . '</a></li>', &$nav_item ) );
							}
						}

						/* Default WordPress */
						else {
							?>
							<li><a href="<?php echo trailingslashit(get_admin_url()); ?>profile.php"><?php esc_html_e('Profile','youplay') ?></a></li>
							<?php
						}
					endif;

					//Blog Admin
					if( current_user_can('list_users') ) : ?>
						<li><a href="<?php echo get_admin_url(); ?>"><?php esc_html_e("WP Admin", 'youplay'); ?></a></li>
					<?php endif;

					//Logout URL
					?>
					<li><a id="wp-logout" href="<?php echo wp_logout_url() ?>"><?php esc_html_e( 'Log Out' , 'youplay') ?></a></li>
				</ul>
			</td>
			<td width="80">
				<div class="angled-img ml-10">
					<div class="img">
						<?php echo get_avatar( $current_user->ID, $size = '100' );  ?>
					</div>
				</div>
			</td>
		</tr>
	</table>
</div>
