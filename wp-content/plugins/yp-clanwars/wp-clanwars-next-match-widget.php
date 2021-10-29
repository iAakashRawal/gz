<?php

if(!function_exists('add_action')) die('Cheatin&#8217; uh?');

class WP_ClanWarsNextMatch_Widget extends WP_Widget {

	var $default_settings = array();
	var $newer_than_options = array();

	function __construct() {
		$widget_ops = array('classname' => 'widget_clanwars_next_match', 'description' => __('Show Next Match', 'youplay'));
		parent::__construct('ClanWarsNextMatch', __('ClanWars Next Match', 'youplay'), $widget_ops);

		$this->default_settings = array('title' => __('ClanWars Next Match', 'youplay'));
	}

	function current_time_fixed( $type, $gmt = 0 ) {
		$t =  ( $gmt ) ? gmdate( 'Y-m-d H:i:s' ) : gmdate( 'Y-m-d H:i:s', ( time() + ( get_option( 'gmt_offset' ) * 3600 ) ) );
		switch ( $type ) {
			case 'mysql':
				return $t;
				break;
			case 'timestamp':
				return strtotime($t);
				break;
		}
	}

	function widget($args, $instance) {
		extract($args);

		$instance = wp_parse_args((array)$instance, $this->default_settings);

		$title = apply_filters('widget_title', empty($instance['title']) ? __('ClanWars', 'youplay') : $instance['title']);

		$matches = array();
		$games = \WP_Clanwars\Games::get_game(array(
				'id' => 'all',
				'orderby' => 'title',
				'order' => 'asc'
			));

		$timestamp = $this->current_time_fixed('timestamp');
		foreach($games as $g) {
			$m = \WP_Clanwars\Matches::get_match(array('from_date' => $timestamp, 'game_id' => $g->id, 'limit' => 1, 'order' => 'asc', 'orderby' => 'date', 'sum_tickets' => true));

			if(sizeof($m)) {
				$matches = array_merge($matches, $m);
			}
		}

		usort(
			$matches,
			function( $a, $b ) {
				$t1 = mysql2date("U", $a->date);
				$t2 = mysql2date("U", $b->date);
	
				if($t1 == $t2) return 0;
	
				return $t1 < $t2 ? -1 : 1;
			}
		);

		echo $before_widget;
		if ( $title ) {
			echo $before_title . $title . $after_title;
		}

		?> <div class="block-content"> <?php
		if(isset($matches[0])) {
			$match = $matches[0];

			$match_date = mysql2date(get_option('date_format') . ', ' . get_option('time_format'), $match->date);

			echo '<a class="youplay-match-widget" href="' . get_permalink($match->post_id) . '">
				<h3 class="youplay-match-title">' . esc_attr($match->title) . '</h3>
				<div class="date mb-15">' . $match_date . '</div>
				<div class="row">
				  <div class="col-xs-6">
					<div class="angled-img">
					  <div class="img">
						' . wp_get_attachment_image(
							$match->team1_logo,
							array(200, 200),
							false,
							array(
								'class' => 'op-10',
								'alt'   => $match->team1_title
							))
						. '
					  </div>
					</div>
				  </div>
				  <div class="col-xs-6">
					<div class="angled-img">
					  <div class="img">
						' . wp_get_attachment_image(
							$match->team2_logo,
							array(200, 200),
							false,
							array(
								'class' => 'op-10',
								'alt'   => $match->team2_title
							))
						. '
					  </div>
					</div>
				  </div>
				</div>
				<div class="row">
				  <div class="col-xs-6">
					<h5>' . $match->team1_title . '</h5>
				  </div>
				  <div class="col-xs-6">
					<h5>' . $match->team2_title . '</h5>
				  </div>
				</div>
			  </a>';

		} else {
			echo __('Next match not found', 'youplay');
		}
		?> </div> <?php
		
		echo $after_widget;
	}

	function update($new_instance, $old_instance) {
		return $new_instance;
	}

	function form($instance) {
		$instance = wp_parse_args((array)$instance, $this->default_settings);
		$title = esc_attr($instance['title']);
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'youplay'); ?></label> <input class="widefat" name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>" value="<?php echo esc_attr($title); ?>" type="text" /></p>
		<?php
	}
}

?>