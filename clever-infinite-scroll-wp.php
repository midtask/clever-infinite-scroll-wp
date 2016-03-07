<?php
/* 
	Plugin Name: Clever Infinite Scroll
	Plugin URI: https://github.com/midtask/clever-infinite-scroll-wp
	Description: Clever Infinite Scroll for WordPress
	Version: v1.0
	Author: Midtask
	Author URI: http://www.midtask.com

	https://github.com/wataruoguchi/clever-infinite-scroll
	https://github.com/midtask/clever-infinite-scroll-wp
	https://www.freelancer.com/projects/9849798.html
*/

function midtask_9849798_wp_footer(){
	if (!($options = get_option('cleverinfinitescroll'))){
		midtask_9849798_admin_menu_callback(true);
		$options = get_option('cleverinfinitescroll');
	}
	?>
<script src='<?php echo plugins_url('assets/jquery.clever-infinite-scroll.js', __FILE__); ?>'></script>
<script>
(function($){
$(document).ready(function(){
	$('<?php echo esc_attr($options['contentsWrapperSelector']); ?>').cleverInfiniteScroll({
	contentsWrapperSelector: '<?php echo esc_attr($options['contentsWrapperSelector']); ?>',
	contentSelector: '<?php echo esc_attr($options['contentSelector']); ?>',
	nextSelector: '<?php echo esc_attr($options['nextSelector']); ?>',
	loadImage: '<?php echo esc_attr($options['loadImage']); ?>'<?php if ($options["offset"]): ?>,
	offset: <?php echo $options['offset']; endif; ?>
	});
});
})(jQuery);
</script>
<?php if ($options["customcss"]): ?>
<style><?php echo $options["customcss"]; ?></style>
<?php endif;
}
add_action('wp_footer', 'midtask_9849798_wp_footer');

function midtask_9849798_admin_menu_callback($quiet){
	if (!isset($_REQUEST['settings-updated']))
		$_REQUEST['settings-updated'] = false;
	settings_fields('cleverinfinitescroll');
	$options = get_option('cleverinfinitescroll');
	$changed = false;
	$defaults = array(
		"contentsWrapperSelector"  => "#primary",
		"contentSelector"          => "article",
		"nextSelector"             => "a.next",
		"loadImage"                => plugins_url('assets/ajax-loader.gif', __FILE__),
		"offset"                   => false,
		"customcss"                => false /* nav.navigation.pagination{display:none;} */
	);

	foreach ($defaults as $k=>$v){
		if (!isset($options[$k])){
			$options[$k] = $v;
			$changed = true;
		}
		if (!$options[$k] && $defaults[$k]!==false){
			$options[$k] = $v;
			$changed = true;
		}
		if (isset($_POST[$k])){
			$options[$k] = $_POST[$k];
			$changed = true;
		}
	}
	if ($changed)
		update_option("cleverinfinitescroll", $options);
	if ($quiet === true)
		return;
	?>
	<style>
	div.wrap.inputwidths input[type=text],
	div.wrap.inputwidths textarea {
		width:100%;}
	</style>
	<div class="wrap inputwidths">
		<?php if ($changed): ?>
			<div class="updated fade"><p><strong>Options saved!</strong></p></div>
		<?php endif; ?>

		<form method="post" action="<?php echo esc_attr($_SERVER["REQUEST_URI"]); ?>">
		<h2><?php echo esc_html(get_admin_page_title()); ?></h2>

		<h3>Settings</h3>
		<p>contentsWrapperSelector</p>
		<p><input type="text" name="contentsWrapperSelector" value="<?php echo esc_attr($options['contentsWrapperSelector']); ?>"></p>
		<p>contentSelector</p>
		<p><input type="text" name="contentSelector" value="<?php echo esc_attr($options['contentSelector']); ?>"></p>
		<p>nextSelector</p>
		<p><input type="text" name="nextSelector" value="<?php echo esc_attr($options['nextSelector']); ?>"></p>
		<p>loadImage</p>
		<p><input type="text" name="loadImage" value="<?php echo esc_attr($options['loadImage']); ?>"></p>
		<p>offset <br/><i>Note: Input is treated as raw javascript. "$" is available in this context.</i></p>
		<p><input type="text" name="offset" value="<?php echo esc_attr($options['offset']); ?>"></p>
		<p><?php submit_button(); ?></p>

		<h3>Custom CSS</h3>
		<p><textarea name="customcss"><?php echo esc_attr($options['customcss']); ?></textarea></p>
		<p><?php submit_button(); ?></p>
		</form>
	</div>
	<?php
}

function midtask_9849798_admin_menu() {
    add_options_page(
        'Clever Infinite Scroll',
        'Clever Infinite Scroll',
        'manage_options',
        'cleverinfinitescroll',
        'midtask_9849798_admin_menu_callback'
	);
}
add_action('admin_menu', 'midtask_9849798_admin_menu');

function midtask_9849798_admin_init(){
	register_setting('cleverinfinitescroll', 'cleverinfinitescroll');
}
add_action('admin_init', 'midtask_9849798_admin_init');
