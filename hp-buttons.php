<?php
/*
Plugin Name: Hocus Pocus Buttons
Plugin URI: http://www.interessante-zeiten.de/goodies/hocus-pocus-buttons-wordpress-plugin
Description: Dynamische Bookmarks
Author: Henning Rosenhagen
Version: 0.5
Author URI: http://www.interessante-zeiten.de/
*/

function HP_Buttons_Init() {

	if ( !function_exists('register_sidebar_widget') || !function_exists('register_widget_control') )
		return;
	
	function HP_Buttons_Widget($args) {
		// Widget ausgeben
	
		global $hp_buttons_css_default, $hp_buttons_css_block, $hp_buttons_css_images;
	
		extract($args);
		$options = get_option('widget_hp_buttons');

		$css = empty($options['css']) ? $hp_buttons_css_default : $options['css'];
		#echo $before_widget;
		#echo $before_title . $title . $after_title;
		
		?>
		
	<li class="hp_buttons">
		<script type="text/javascript">
			var theurl = encodeURIComponent(location.href);
			var thetitle = encodeURIComponent(document.title);
			var mode = 2;
			var bmbuttons = Array(
			    Array('Mister Wong', '//www.mister-wong.de', 'http://www.mister-wong.de/index.php?action=addurl&bm_url='+theurl+'&bm_description='+thetitle, ''),
			    Array('del.icio.us', 'http://del.icio.us', 'http://del.icio.us/post?url='+theurl+'&title='+thetitle, ''),
			    Array('Webnews', 'http://www.webnews.de', 'http://www.webnews.de/einstellen?url='+theurl+'&title='+thetitle, ''),
			    Array('Yigg', 'http://yigg.de', 'http://yigg.de/neu?exturl='+theurl+'&exttitle='+thetitle, ''),
			    Array('Technorati', 'http://www.technorati.com', 'http://www.technorati.com/faves?add='+theurl, ''),
			    Array('Ma.gnolia', 'http://ma.gnolia.com', 'http://ma.gnolia.com/bookmarklet/add?url='+theurl+'&title='+thetitle+'&description='+thetitle, ''),
			    Array('Google', 'http://www.google.de/bookmarks', 'http://www.google.de/bookmarks/mark?op=edit&output=popup&bkmk='+theurl+'&title='+thetitle, 'http://www.google.de'),
			    Array('Google', 'http://www.google.com/bookmarks', 'http://www.google.com/bookmarks/mark?op=edit&output=popup&bkmk='+theurl+'&title='+thetitle, 'http://www.google.com/'),
			    Array('Spurl', 'http://www.spurl.net', 'http://www.spurl.net/spurl.php?url='+theurl+'&title='+thetitle, ''),
			    Array('Reddit', 'http://reddit.com', 'http://reddit.com/submit?url='+theurl+'&title='+thetitle, ''),
			    Array('Stumbleupon', 'http://www.stumbleupon.com', 'http://www.stumbleupon.com/refer.php?url='+theurl+'&title='+thetitle, ''),
			    Array('Yahoo My Web', 'http://myweb2.search.yahoo.com', 'http://myweb2.search.yahoo.com/myresults/bookmarklet?u='+theurl+'&t='+thetitle, ''),
			    Array('Blogmarks', 'http://blogmarks.net', 'http://blogmarks.net/my/new.php?mini=1&simple=1&url='+theurl+'&title='+thetitle, ''),
			    Array('Digg', 'http://digg.com', 'http://digg.com/submit?phase=2&url='+theurl+'&title='+thetitle, '')
			);
			if (navigator.appName=='Netscape') 
				var language = navigator.language;
			else 
				var language = navigator.browserLanguage;
			if (language.indexOf('de') > -1) 
				var lang='de';
			else
				var lang='en';
			document.write('<div>');
			for (var button in bmbuttons) {
				if (lang=='de')
					var lt = "bei " + bmbuttons[button][0] + ' speichern';
				else 
					var lt = "Share at " + bmbuttons[button][0];
				document.write("<a style=\"line-height:150%\"href=\"" + bmbuttons[button][1] + "\" onclick=\"location.href='" + bmbuttons[button][2] + "';return false;\">");	
				<?php 
				if ($options['mode']==1 || $options['mode']==0) { ?>
				document.write("<img type=\"image/x-icon\" align=\"top\" src=\"" + (bmbuttons[button][3].length==0 ? bmbuttons[button][1] : bmbuttons[button][3]) +  "/favicon.ico" + "\" alt=\"" + lt + "\" /> ");<?php }
				if ($options['mode']!=1) { ?>
				document.write(lt);	
				<?php } 
				?>
				document.write('</a>');
			}
			document.write('</div>');
			</script>
		
		<?php
			
		print ('<style type="text/css"> ' . $css  . ($options['mode']==1?$hp_buttons_css_images:$hp_buttons_css_block) . "</style>\n");
		echo $after_widget;
		print('</li>');
	
	}

	function HP_Buttons_Control() {
		// Voreinstellungen
		
		#ini_set('display_errors', 1);
		#error_reporting(E_ALL);
		global $hp_buttons_css_default;
		
		$options = get_option('widget_hp_buttons');
		
		if (@$_POST['hp_buttons-submit'] ) {
			$newoptions['css'] = strip_tags(stripslashes(@$_POST['hp_buttons-css']));
			$newoptions['mode'] = strip_tags(stripslashes(@$_POST['hp_buttons-mode']))+0;
		}
		if ( $options != $newoptions ) {
			$options = $newoptions;
			update_option('widget_hp_buttons', $options);
		}
		$css = htmlspecialchars($options['css'], ENT_QUOTES);
		$mode = $options['mode'];
		if (empty($css)) $css = $hp_buttons_css_default;
		
		?>
		<div>	
			<div>
				<label for="hp_buttons-mode">Mode</label>
				<select name="hp_buttons-mode" id="hp_buttons-mode" size="1">
					<option value="0"<?php echo $mode==0?" selected":'' ?>>Icon and text</option>
					<option value="1"<?php echo $mode==1?" selected":'' ?>>Icon only</option>
					<option value="2"<?php echo $mode==2?" selected":'' ?>>Text only</option>
				</select>
			</div>
			<div>
				<label for="hp_buttons-css">CSS styles for bookmarking links</label>
				<textarea name="hp_buttons-css" id="hp_buttons-css" style="width:100%;height:10em"><?php print($css); ?></textarea>
			</div>
			<input type="hidden" name="hp_buttons-submit" id="hp_buttons-submit" value="1" />
		</div>
		<?php
	}
	register_sidebar_widget('HP-Buttons', 'HP_Buttons_Widget');
	register_widget_control('HP-Buttons', 'HP_Buttons_Control');
}

$hp_buttons_css_default = <<<HEREDOC
.hp_buttons a:link {
    display:none;
}
.hp_buttons a:hover, .hp_buttons a:active {
    background:#ddd;
}
.hp_buttons {
	text-align:center;
}
HEREDOC;
$hp_buttons_css_block = <<<HEREDOC
.hp_buttons a:visited {
    display:block;
    text-align:center;
    padding:0.7ex 1ex;
    line-height:16px;
}
HEREDOC;
$hp_buttons_css_images = <<<HEREDOC
.hp_buttons img {
	border:1px solid;
}
HEREDOC;

add_action('plugins_loaded', 'HP_Buttons_Init');

?>