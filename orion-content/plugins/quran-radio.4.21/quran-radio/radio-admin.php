<?php
/**
 * The Quran Radio
 *
 * Plugin Name: The Quran Radio
 * Plugin URI:  https://wordpress.org/plugins/quran-radio/
 * Description: Quran Radio is the first WordPress plugin that allows you to add the translations of the Quran in 40 languages on posts, pages or widgets.
 * Version:     4.21
 * Author:      EDC TEAM
 * Author URI:  https://edc.org.kw
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Text Domain: the-quran-radio
 * Domain Path: /languages
 * Requires at least: 5.0
 * Requires PHP: 7.4
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation. You may NOT assume
 * that you can use any other version of the GPL.
 */

require_once( plugin_dir_path( __FILE__ ) . '/radio-hook.php' );
require_once( plugin_dir_path( __FILE__ ) . '/radio-list.php' );
require_once( plugin_dir_path( __FILE__ ) . '/radio-widget.php' );

function EDC_content_replace($t){
	$text = preg_replace_callback("/radio\[([0-9]*?)\]/s", "get_radio", $t);
	return $text;
}
add_filter('the_content','EDC_content_replace');

function edc_radio_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.', 'the-quran-radio' ) );
	}

	$Radio_Languages = radios_list();

	if(isset($_POST['submitted']) && $_POST['submitted'] == 1){
		$radio_key = ( isset($_POST['radio_key']) ? esc_attr($_POST['radio_key']) : '' );
		$show_radio_url = ( isset($_POST['show_radio_url']) ? 1 : 0 );
		$show_radio_pdf = ( isset($_POST['show_radio_pdf']) ? 1 : 0 );
		$show_radio_podcast = ( isset($_POST['show_radio_podcast']) ? 1 : 0 );
		$show_radio_alllinks = ( isset($_POST['show_radio_alllinks']) ? 1 : 0 );
		$show_radio_MediaPlayer = ( isset($_POST['show_radio_MediaPlayer']) ? 1 : 0 );
		$show_radio_QuickTime = ( isset($_POST['show_radio_QuickTime']) ? 1 : 0 );
		$show_radio_realplayer = ( isset($_POST['show_radio_realplayer']) ? 1 : 0 );
		$show_radio_realplayer = ( isset($_POST['show_radio_realplayer']) ? 1 : 0 );
		$show_radio_Winamp = ( isset($_POST['show_radio_Winamp']) ? 1 : 0 );
		$show_radio_appstore = ( isset($_POST['show_radio_appstore']) ? 1 : 0 );
		$show_radio_tunein = ( isset($_POST['show_radio_tunein']) ? 1 : 0 );
		$show_radio_soundcloud = ( isset($_POST['show_radio_soundcloud']) ? 1 : 0 );
		$show_radio_twitter = ( isset($_POST['show_radio_twitter']) ? 1 : 0 );
		$show_radio_facebook = ( isset($_POST['show_radio_facebook']) ? 1 : 0 );
		$check_autostart = ( isset($_POST['check_autostart']) ? 1 : 0 );
		$hidden_radio_player = ( isset($_POST['hidden_radio_player']) ? 1 : 0 );
		$hidden_radio_title = ( isset($_POST['hidden_radio_title']) ? 1 : 0 );

		if( get_option( 'radio_key' ) !== false ){
			update_option( 'radio_key', $radio_key );
			update_option( 'show_radio_url', $show_radio_url );
			update_option( 'show_radio_pdf', $show_radio_pdf );
			update_option( 'show_radio_podcast', $show_radio_podcast );
			update_option( 'show_radio_alllinks', $show_radio_alllinks );
			update_option( 'show_radio_MediaPlayer', $show_radio_MediaPlayer );
			update_option( 'show_radio_QuickTime', $show_radio_QuickTime );
			update_option( 'show_radio_realplayer', $show_radio_realplayer );
			update_option( 'show_radio_Winamp', $show_radio_Winamp );
			update_option( 'show_radio_appstore', $show_radio_appstore );
			update_option( 'show_radio_tunein', $show_radio_tunein );
			update_option( 'show_radio_soundcloud', $show_radio_soundcloud );
			update_option( 'show_radio_twitter', $show_radio_twitter );
			update_option( 'show_radio_facebook', $show_radio_facebook );
			update_option( 'check_autostart', $check_autostart );
			update_option( 'radio_title', esc_attr($_POST['radio_title']) );
			update_option( 'hidden_radio_player', $hidden_radio_player );
			update_option( 'hidden_radio_title', $hidden_radio_title );
		}else{
			add_option( 'radio_key', $radio_key, null );
			add_option( 'show_radio_url', '1', null );
			add_option( 'show_radio_pdf', '1', null );
			add_option( 'show_radio_podcast', '1', null );
			add_option( 'show_radio_alllinks', '1', null );
			add_option( 'show_radio_MediaPlayer', '1', null );
			add_option( 'show_radio_QuickTime', '1', null );
			add_option( 'show_radio_realplayer', '1', null );
			add_option( 'show_radio_Winamp', '1', null );
			add_option( 'show_radio_appstore', '1', null );
			add_option( 'show_radio_tunein', '1', null );
			add_option( 'show_radio_soundcloud', '1', null );
			add_option( 'show_radio_twitter', '1', null );
			add_option( 'show_radio_facebook', '1', null );
			add_option( 'check_autostart', '1', null );
			add_option( 'radio_title', '', null );
			add_option( 'hidden_radio_player', '0', null );
			add_option( 'hidden_radio_title', '0', null );
		}
	}
	?>

	<div class="wrap nosubsub">
		<h1><?php _e('Quran Radio Setting', 'the-quran-radio'); ?></h1>
		<div id="col-container">

			<div id="col-right">
				<div class="col-wrap">
					<div class="form-wrap">
						<?php echo get_radio(get_option('radio_key'), 1); ?>
					</div>
				</div>
			</div>

			<div id="col-left">
				<div class="col-wrap">
					<div class="form-wrap">
						<form name="sytform" action="" method="post">
							<input type="hidden" name="submitted" value="1" />

							<div class="form-field">
								<label for="radio_key"><?php _e('Select Language', 'the-quran-radio'); ?></label>
								<select name="radio_key" id="radio_key" style="width:100%;">
									<?php foreach($Radio_Languages as $k => $v){ ?>
										<option value="<?php echo $k; ?>" <?php echo ( get_option('radio_key') == $k ) ? 'selected="yes"' : ''; ?>><?php echo $k.'- '.$v['title']; ?></option>
									<?php } ?>
								</select>
							</div>

							<div class="form-field">
								<label for="radio_title"><?php _e('Radio Title', 'the-quran-radio'); ?></label>
								<input id="radio_title" type="text" name="radio_title" value="<?php echo htmlentities(get_option('radio_title')); ?>" />
								<p><?php _e('if empty will write language title.', 'the-quran-radio'); ?></p>
							</div>

							<div class="form-field">
								<h2><?php _e('Show Icons:', 'the-quran-radio'); ?></h2>
								<p><input id="show_radio_url" type="checkbox" name="show_radio_url"<?php echo ( get_option('show_radio_url') == '1' ? ' checked' : '' ); ?>> <?php _e('Source Icon', 'the-quran-radio'); ?></p>
								<p><input id="show_radio_pdf" type="checkbox" name="show_radio_pdf"<?php echo ( get_option('show_radio_pdf') == '1' ? ' checked' : '' ); ?>> <?php _e('PDF Book Icon', 'the-quran-radio'); ?></p>
								<p><input id="show_radio_podcast" type="checkbox" name="show_radio_podcast"<?php echo ( get_option('show_radio_podcast') == '1' ? ' checked' : '' ); ?>> <?php _e('Podcast Icon', 'the-quran-radio'); ?></p>
								<p><input id="show_radio_alllinks" type="checkbox" name="show_radio_alllinks"<?php echo ( get_option('show_radio_alllinks') == '1' ? ' checked' : '' ); ?>> <?php _e('Download Icon', 'the-quran-radio'); ?></p>
								<p><input id="show_radio_MediaPlayer" type="checkbox" name="show_radio_MediaPlayer"<?php echo ( get_option('show_radio_MediaPlayer') == '1' ? ' checked' : '' ); ?>> <?php _e('MediaPlayer Icon', 'the-quran-radio'); ?></p>
								<p><input id="show_radio_QuickTime" type="checkbox" name="show_radio_QuickTime"<?php echo ( get_option('show_radio_QuickTime') == '1' ? ' checked' : '' ); ?>> <?php _e('QuickTime Icon', 'the-quran-radio'); ?></p>
								<p><input id="show_radio_realplayer" type="checkbox" name="show_radio_realplayer"<?php echo ( get_option('show_radio_realplayer') == '1' ? ' checked' : '' ); ?>> <?php _e('Realplayer Icon', 'the-quran-radio'); ?></p>
								<p><input id="show_radio_Winamp" type="checkbox" name="show_radio_Winamp"<?php echo ( get_option('show_radio_Winamp') == '1' ? ' checked' : '' ); ?>> <?php _e('Winamp Icon', 'the-quran-radio'); ?></p>
								<p><input id="show_radio_tunein" type="checkbox" name="show_radio_tunein"<?php echo ( get_option('show_radio_tunein') == '1' ? ' checked' : '' ); ?>> <?php _e('Tunein Icon', 'the-quran-radio'); ?></p>
								<p><input id="show_radio_soundcloud" type="checkbox" name="show_radio_soundcloud"<?php echo ( get_option('show_radio_soundcloud') == '1' ? ' checked' : '' ); ?>> <?php _e('Soundcloud Icon', 'the-quran-radio'); ?></p>
								<p><input id="show_radio_twitter" type="checkbox" name="show_radio_twitter"<?php echo ( get_option('show_radio_twitter') == '1' ? ' checked' : '' ); ?>> <?php _e('Twitter Icon', 'the-quran-radio'); ?></p>
								<p><input id="show_radio_facebook" type="checkbox" name="show_radio_facebook"<?php echo ( get_option('show_radio_facebook') == '1' ? ' checked' : '' ); ?>> <?php _e('Facebook Icon', 'the-quran-radio'); ?></p>
								<p><input id="hidden_radio_player" type="checkbox" name="hidden_radio_player"<?php echo ( get_option('hidden_radio_player') == '1' ? ' checked' : '' ); ?>> <?php _e('Hidden Player', 'the-quran-radio'); ?></p>
								<p><input id="hidden_radio_title" type="checkbox" name="hidden_radio_title"<?php echo ( get_option('hidden_radio_title') == '1' ? ' checked' : '' ); ?>> <?php _e('Hidden Title', 'the-quran-radio'); ?></p>
							</div>

							<p class="submit"><input type="submit" name="Submit" id="submit" class="button button-primary" value="<?php _e('Update options', 'the-quran-radio'); ?>"  /></p>

						</form>
					</div>

				</div>
			</div>

		</div>
	</div>
<?php
}
