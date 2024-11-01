<?php
/*
Plugin Name: WP Chesstempo
Plugin URI: http://zenoweb.nl
Description: Widget that displays the chess puzzle from chesstempo.com.
Author: Marcel Pol
Version: 1.0.0
Author URI: http://timelord.nl
License: GPLv2 or later


Copyright 2016 - 2018  Marcel Pol  (email: marcel@timelord.nl)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


if ( function_exists('register_sidebar') && class_exists('WP_Widget') ) {
	class Chesstempo_Widget extends WP_Widget {

		/* Constructor */
		function __construct() {
			$widget_ops = array( 'classname' => 'chesstempo_widget', 'description' => __( 'Displays the puzzle from Chesstempo.', 'wp-chesstempo' ) );
			parent::__construct('chesstempo_widget', 'WP Chesstempo', $widget_ops);
			$this->alt_option_name = 'chesstempo_widget';
		}

		/** @see WP_Widget::widget */
		function widget($args, $instance) {
			extract($args);

			$default_value = array(
					'title' => 'Chesstempo',
					'sizes' => (int) 29,
				);
			$instance = wp_parse_args( (array) $instance, $default_value );

			$widget_title = esc_attr($instance['title']);
			$sizes        = (int) $instance['sizes'];
			if ( $sizes == 0 ) { $sizes = 29; }

			echo $before_widget; ?>
			<div class="chesstempo_widget">

			<?php
			if ($widget_title !== FALSE) {
				echo $before_title . apply_filters('widget_title', $widget_title) . $after_title;
			} ?>

				<div id="puzzle">
					<div id="puzzle-container">
						<link id="puzzleCss" type="text/css" rel="stylesheet" href="https://chesstempo.com/css/dailypuzzle.css"/>
						<script type="text/javascript" src="https://chesstempo.com/js/dailypuzzle.js"></script>
						<script>
							new Puzzle({ pieceSize: <?php echo $sizes; ?> });
						</script>
					</div>
					<a id="ct-link" href="https://chesstempo.com/play-chess-online.html">Chesstempo</a>
				</div>
			</div>

			<?php
			echo $after_widget;
		}

		/** @see WP_Widget::update */
		function update($new_instance, $old_instance) {
			$instance = $old_instance;
			$instance['title'] = strip_tags($new_instance['title']);
			$instance['sizes'] = (int) $new_instance['sizes'];

			return $instance;
		}

		/** @see WP_Widget::form */
		function form($instance) {

			$default_value = array(
					'title' => 'Chesstempo',
					'sizes' => (int) 29,
				);
			$instance = wp_parse_args( (array) $instance, $default_value );

			$title = esc_attr($instance['title']);
			$sizes = (int) $instance['sizes'];
			if ( $sizes == 0 ) { $sizes = 29; }
			?>

			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>" />Titel:</label>
				<br />
				<input type="text" id="<?php echo $this->get_field_id('title'); ?>" value="<?php echo $title; ?>" name="<?php echo $this->get_field_name('title'); ?>" />
			</p>

			<p>
				<label for="<?php echo $this->get_field_id('sizes'); ?>" />Piecesizes:</label>
				<br />
				<select name="<?php echo $this->get_field_name('sizes'); ?>" id="<?php echo $this->get_field_id('sizes'); ?>">
					<?php
					$presets = array( 20,24,29,35,40,46,55,65,75,85,95,105,115,125,135,145,155,165,180,200,220,240 );
					foreach ( $presets as $preset ) {
						echo '
						<option value="' . $preset . '"';
						if ( $preset == $sizes ) {
							echo ' selected="selected"';
						}
						echo '>' . $preset . '</option>';
					} ?>

				</select>
			</p>

			<?php
		}
	}

	function chesstempo_widget() {
		register_widget('Chesstempo_Widget');
	}
	add_action('widgets_init', 'chesstempo_widget' );
}


/*
 * Add example text to the privacy policy.
 *
 * @since 1.0.0
 */
function chesstempo_add_privacy_policy_content() {
	if ( ! function_exists( 'wp_add_privacy_policy_content' ) ) {
		return;
	}

	$content = sprintf(
		'<p>' . __( 'The chess puzzle from Chesstempo loads files from the Chesstempo website. The Chesstempo website can see who is using this widget and where they are visiting it. There might be cookies set.', 'wp-chesstempo' ) . '</p>'
	);

	wp_add_privacy_policy_content(
		'WP Chesstempo',
		wp_kses_post( wpautop( $content, false ) )
	);
}
add_action( 'admin_init', 'chesstempo_add_privacy_policy_content' );
