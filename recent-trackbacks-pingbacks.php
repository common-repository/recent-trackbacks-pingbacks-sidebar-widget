<?php
/*
Plugin Name: Recent Trackbacks & Pingbacks Sidebar Widget
Description: Displays the recent trackbacks and pingbacks in a customizable sidebar widget
Plugin URI:  http://www.officetrend.de/2767/wordpress-plugin-recent-trackbacks-pingbacks-sidebar-widget/
Version:     1.1
Author:      Tanja Preu&szlig;e
Author URI:  http://www.officetrend.de/

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
*/

Class Recent_Trackbacks_Pingbacks_Widget Extends WP_Widget {

	function Recent_Trackbacks_Pingbacks_Widget() {
		if (function_exists('load_plugin_textdomain'))
         load_plugin_textdomain('recent-trackbacks-pingbacks', PLUGINDIR.'/'.dirname(plugin_basename(__FILE__)), dirname(plugin_basename(__FILE__)));

		$widget_ops = array('classname' => 'recent_trackbacks_pingbacks', 'description' => 'Recent Trackbacks & Pingbacks Widget' );
		$this->WP_Widget ( 'recent_trackbacks_pingbacks', 'Recent Trackbacks & Pingbacks', $widget_ops);
	}
	
	function widget($args, $instance) {
		extract($args);
		
		echo $before_widget;
		
		$title = empty($instance['title']) ? 'Recent Trackbacks' : apply_filters('widget_title', $instance['title']);
		if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };
		
		$exclude_own = $instance['exclude_own'] ? '1' : '0';
		$nofollow_link = $instance['nofollow_link'] ? '1' : '0';
		
		if ( !$show_items = (int) $instance['show_items'] )
			$show_items = 5;
        elseif ( $show_items < 1 )
			$show_items = 1;
			
		if ( !$summary_length = (int) $instance['summary_length'] )
			$summary_length = 100;
		else if ( $summary_length < 10 )
			$summary_length = 10;
		else if ( $summary_length > 500 )
			$summary_length = 500;
		
		tp_recent_trackping( $instance );
		
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$new_instance = wp_parse_args( (array) $new_instance, array( 
			'title' => '', 
			'show_items' => 5, 
			'summary_length' => 100, 
			'exclude_own' => 0, 
			'nofollow_link' => 0 ) );
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['exclude_own'] = $new_instance['exclude_own'] ? 1 : 0;
		$instance['nofollow_link'] = $new_instance['nofollow_link'] ? 1 : 0;
		$instance['show_items'] = (int) $new_instance['show_items'];		
		$instance['summary_length'] = (int) $new_instance['summary_length'];
		
		return $instance;
	}

	function form($instance) {			
		$instance = wp_parse_args( (array) $instance, array( 
			'title' => '', 
			'show_items' => 5, 
			'summary_length' => 100, 
			'exclude_own' => 0, 
			'nofollow_link' => 0 ) );
		$title = strip_tags($instance['title']);
		$exclude_own = $instance['exclude_own'] ? 'checked="checked"' : '';
		$nofollow_link = $instance['nofollow_link'] ? 'checked="checked"' : '';
		
		if ( !$show_items = (int) $instance['show_items'] )
			$show_items = 5;
        elseif ( $show_items < 1 )
			$show_items = 1;
		
		if ( !$summary_length = (int) $instance['summary_length'] )
			$summary_length = 100;
		else if ( $summary_length < 10 )
			$summary_length = 10;
		else if ( $summary_length > 500 )
			$summary_length = 500;
?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'recent-trackbacks-pingbacks'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
		</p>
		
		<p>
		    <input id="<?php echo $this->get_field_id('show_items'); ?>" name="<?php echo $this->get_field_name('show_items'); ?>" type="text" value="<?php echo $show_items; ?>" size="3" />
			<label for="<?php echo $this->get_field_id('show_items'); ?>"><?php _e('Items are displayed', 'recent-trackbacks-pingbacks');?></label>
		</p>
		
		<p>
			<input class="checkbox" type="checkbox" <?php echo $nofollow_link; ?> id="<?php echo $this->get_field_id( 'nofollow_link' ); ?>" name="<?php echo $this->get_field_name( 'nofollow_link' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'nofollow_link' ); ?>"><?php _e('Link: rel="nofollow" ?', 'recent-trackbacks-pingbacks'); ?></label>
		</p>
		
		<p>
            <input id="<?php echo $this->get_field_id('summary_length'); ?>" name="<?php echo $this->get_field_name('summary_length'); ?>" type="text" value="<?php echo $summary_length; ?>" size="3" />
			<label for="<?php echo $this->get_field_id('summary_length'); ?>"><?php _e('Characters for excerpt', 'recent-trackbacks-pingbacks'); ?></label>
            <small><?php _e('(between 10 and 500)', 'recent-trackbacks-pingbacks'); ?></small>
        </p>
			
		<p>
			<input class="checkbox" type="checkbox" <?php echo $exclude_own; ?> id="<?php echo $this->get_field_id( 'exclude_own' ); ?>" name="<?php echo $this->get_field_name( 'exclude_own' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'exclude_own' ); ?>"><?php _e('Exclude own Pingbacks ?', 'recent-trackbacks-pingbacks'); ?></label>
			<?php $ownuri = get_bloginfo('siteurl'); ?>
			<small><?php _e('(check to exclude Pingbacks from ', 'recent-trackbacks-pingbacks'); echo $ownuri ?>)</small>
		</p>
<?php		
	}
}
 
add_action('widgets_init', create_function('', 'return register_widget("Recent_Trackbacks_Pingbacks_Widget");'));

function tp_recent_trackping( $args = array() ) {
	$default_args = array ( 'show_items' => 5, 'exclude_own' => 0, 'summary_length' => 100, 'nofollow_link' => 0 );
	$args = wp_parse_args( $args, $default_args );
	extract( $args );
	
	$show_items = (int) $show_items;
	$exclude_own = (int) $exclude_own;
	$summary_length = (int) $summary_length;
	$nofollow_link = (int) $nofollow_link;
	
	global $wpdb;
	
	if ( $exclude_own ) {
		$ownuri = get_bloginfo('siteurl');
		$ex_own_uri = " AND comment_author_url NOT LIKE '$ownuri%' ";
	}
	
	$track_query="SELECT comment_author, comment_author_url, SUBSTRING(comment_content,1,$summary_length) AS com_excerpt, comment_date, DATE_FORMAT(comment_date, '%d.%m.%Y %H:%i:%s') AS comment_date_ger FROM $wpdb->comments
		WHERE comment_approved='1'
		AND comment_type IN('trackback', 'pingback')
		$ex_own_uri
		GROUP BY comment_author_url
		Order by comment_date DESC
		LIMIT $show_items";
	$track_result = $wpdb->get_results($track_query);
    echo '<ul>';
	foreach ($track_result as $comment) :
		$tbCont = $comment->com_excerpt;
		$tbCont = strip_tags ($tbCont);
		$tbCont = str_ireplace("[...] ", " ", $tbCont);
		if ( $nofollow_link ) {
			echo '<li><a href="'.$comment->comment_author_url.'" target="_blank" rel="nofollow">'.$comment->comment_author.'</a><br /> '.$comment->comment_date_ger.' '.$tbCont.'...</li>';
		} else { 
			echo '<li><a href="'.$comment->comment_author_url.'" target="_blank">'.$comment->comment_author.'</a><br /> '.$comment->comment_date_ger.' '.$tbCont.'...</li>';
		}
	endforeach;
	echo '</ul>';
}

?>