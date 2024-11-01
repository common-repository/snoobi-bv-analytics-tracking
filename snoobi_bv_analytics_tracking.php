<?php

/*
Plugin Name: Snoobi Analytics tracking
Description: Snoobi Analytics tracking plugin
Version: 2.16
Copyright: 2024 Snoobi B.V.
License: GPLv2

Copyright (C) 2024 Snoobi B.V.

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

defined( 'ABSPATH' ) or die( 'Plugin file cannot be accessed directly.' );

function snoobianalyticsplugin_footer() {
    $host = 'eu2.snoobi.com';

    $string=sprintf("
<!-- Snoobi siteanalytics V2.9 -->
<script type='text/javascript'>

var _saq = _saq || [];
(function() {
var host = '%s';
var account = '%s';
var page_name = '';
var section = '';
var anchors = '%s';
var cookies = '%s';
var snbscript = document.createElement('script');
snbscript.type = 'text/javascript';
snbscript.async= true;
snbscript.src = ('https:' == document.location.protocol ? 'https://' : 'http://')
+ host + '/?account=' + account
+ '&page_name=' + page_name
+ '&anchors=' + anchors
+ '&section=' + section
+ '&cookies=' + cookies;
var s = document.getElementsByTagName('script')[0];
s.parentNode.insertBefore(snbscript, s);
})();
</script>
",
        $host,
        get_option('snoobianalyticsplugin_snoobi_id'),
        get_option('snoobianalyticsplugin_anchors'),
        get_option('snoobianalyticsplugin_cookies')
    );

    print($string);
}

function snoobianalyticsplugin_admin_add_page() {
    #title, menutitle, capability, slug, func
    add_options_page('Snoobi Analytics tracking Plugin Page', 'Snoobi Menu', 'manage_options', 'snoobipluginoptions', 'snoobianalyticsplugin_options_page');
}

function snoobianalyticsplugin_init() {
    register_setting( 'snoobi_opts', 'snoobianalyticsplugin_snoobi_id' );
    register_setting( 'snoobi_opts', 'snoobianalyticsplugin_location' );
    register_setting( 'snoobi_opts', 'snoobianalyticsplugin_anchors' );
    register_setting( 'snoobi_opts', 'snoobianalyticsplugin_cookies' );

    add_settings_section(
        'sec1', // ID
        'My Snoobi Analytics Settings', // Title
        'snoobianalyticsplugin_sec1' , // Callback
        'snoobipluginoptions' // Page
    );
    add_settings_field(
        'snoobianalyticsplugin_snoobi_id', // ID
        'Snoobi Account ID', // Title
        'snoobianalyticsplugin_snoobi_id_callback', // Callback
        'snoobipluginoptions',
        'sec1'
    );
    add_settings_field(
        'snoobianalyticsplugin_location', // ID
        'Location of tracking code', // Title
        'snoobianalyticsplugin_location_callback', // Callback
        'snoobipluginoptions',
        'sec1'
    );
    add_settings_field(
        'snoobianalyticsplugin_anchors', // ID
        'Anchors', // Title
        'snoobianalyticsplugin_anchors_callback', // Callback
        'snoobipluginoptions',
        'sec1'
    );

    add_settings_field(
        'snoobianalyticsplugin_cookies', // ID
        'Cookies', // Title
        'snoobianalyticsplugin_cookies_callback', // Callback
        'snoobipluginoptions',
        'sec1'
    );
}

function snoobianalyticsplugin_sec1() { }

function snoobianalyticsplugin_snoobi_id_callback() {
    printf(
        '<input type="text" id="snoobianalyticsplugin_snoobi_id" name="snoobianalyticsplugin_snoobi_id" value="%s" />',
        get_option('snoobianalyticsplugin_snoobi_id')
    );
}

function snoobianalyticsplugin_location_callback() {
    printf(
        '<select id="snoobianalyticsplugin_location" name="snoobianalyticsplugin_location">
				<option value="footer" %s>footer
				<option value="header" %s>header
			</select>
			',
        get_option('snoobianalyticsplugin_location')=='Finland'?'selected':'',
        get_option('snoobianalyticsplugin_location')=='Netherlands'?'selected':''
    );
}

function snoobianalyticsplugin_anchors_callback() {
    $anchor=get_option('snoobianalyticsplugin_anchors');
    if(!$anchor) { $anchor='off';} #default
    printf(
        '<select id="snoobianalyticsplugin_anchors" name="snoobianalyticsplugin_anchors">
			<option %s>on
			<option %s>off
		</select>
		',
        get_option('snoobianalyticsplugin_anchors')=='on'?'selected':'',
        get_option('snoobianalyticsplugin_anchors')=='off'?'selected':''
    );
}

function snoobianalyticsplugin_cookies_callback() {
    printf(
        '<select id="snoobianalyticsplugin_cookies" name="snoobianalyticsplugin_cookies">
			<option %s>on
			<option %s>session
			<option %s>block
		</select>
		',
        get_option('snoobianalyticsplugin_cookies')=='on'?'selected':'',
        get_option('snoobianalyticsplugin_cookies')=='session'?'selected':'',
        get_option('snoobianalyticsplugin_cookies')=='block'?'selected':''
    );
}

function snoobianalyticsplugin_options_page() {
?><!-- fp -->
<div class="wrap">
    <h2>Snoobi Analyics tracking plugin Settings</h2>
    <form method="post" action="options.php">
        <?php
        settings_fields('snoobi_opts');
        do_settings_sections( 'snoobipluginoptions');
        submit_button();
        ?>
    </form><Br><br>
    This plugin adds Snoobi Analytics code to your website. <Br>
    You will need a Snoobi Account ID to be able to activate the plugin. If you don't have a Snoobi Analytics account yet, then request a free trial at the Snoobi Website.<br><br>

    The settings have the following parameters:<br><br>

    <b>Snoobi account ID:</b><br>This is your id you received from Snoobi.<br><br>
    <b>Location of tracking code:</b><br>Change this setting if you want the tracking code to be placed at the top of the page or at the bottom.<br><br>
    <b>Cookies:</b><br>Here you can select to not let Snoobi use any cookies. Snoobi will be fully functional, with the exception of detecting repeat visitors. As an alternative, you can use Cookies =1st which will only place first-party cookies.<br><br>
        Cookies=on: Snoobi uses persistent and session cookies.<br>
		Cookies=session: Snoobi only sets non-persistent cookies.<br>
		Cookies=block: Snoobi does not set any cookies.<br><br>
    <b>Anchors:</b><br>If your website uses html-anchors to navigate on a page, set this on. Then Snoobi will automatically collect statistics from any anchor-click as well. Our advice is to keep this setting ON, which is the default.
    <br>
    <br>
    This plugin is developed on behalf of Snoobi Technology
    <br >
</div>
<!-- fp -->
<?php
}

if(get_option('snoobianalyticsplugin_snoobi_id')) {
    if(get_option('snoobianalyticsplugin_location')=='header') {
        add_action('wp_head', 'snoobianalyticsplugin_footer' );
    } else {
        add_action('wp_footer', 'snoobianalyticsplugin_footer' );
    }
}

if ( is_admin() ) { // admin actions
    add_action('admin_init', 'snoobianalyticsplugin_init' );
    add_action('admin_menu', 'snoobianalyticsplugin_admin_add_page');
}
