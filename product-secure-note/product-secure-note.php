<?php
/*
Plugin Name: Product Secure Note
Plugin URI:
Description: This plugin allows store owners to add a customizable security note to each WooCommerce product. Enhance customer trust and ensure a safer shopping experience with tailored security information displayed alongside your products.
Version: 1.0
Author: Keramaros Antonios
Author URI: https://keramaros.gr
Requires at least: 5.0
Tested up to: 6.7
Requires plugin: woocommerce
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'plugins_loaded', function () {
	$required_php_version = '7.0.0';
	$current_php_version  = phpversion();

	if ( version_compare( $current_php_version, $required_php_version, '<' ) ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );

		add_action( 'admin_notices', function () use ( $required_php_version, $current_php_version ) {
			?>
            <div class="error">
                <p><strong>WooCommerce Product Note:</strong> This plugin requires PHP version <?php echo esc_html( $required_php_version ); ?> or higher. Your current PHP version is <?php echo esc_html( $current_php_version ); ?>. Please upgrade your PHP version.</p>
            </div>
			<?php
		} );
	}

	if ( ! class_exists( 'woocommerce' ) ) {
		add_action( 'admin_notices', function () {
			?>
            <div class="error">
                <p><strong>WooCommerce Product Note:</strong> This plugin requires the WooCommerce plugin to be installed and activated.</p>
            </div>
			<?php
		} );
		deactivate_plugins( plugin_basename( __FILE__ ) );
	}
} );

register_activation_hook( __FILE__, function () {
	set_transient( 'psnp_activation_redirect', true, 30 );

	add_option( 'psnp_enable', 'yes' );
	add_option( 'psnp_title', 'Shopping Security' );
	add_option( 'psnp_icon', 'yes' );
	add_option( 'psnp_svg_size', '24' );
	add_option( 'psnp_excerpt', 'We guarantee that you will receive the products from your order, or you will get your money back.' );
	add_option( 'psnp_content', 'Our top priority is ensuring your satisfaction and providing a worry-free shopping experience. If there are any issues with your order, our customer support team is here to assist you promptly.' );
	add_option( 'psnp_user_action_label', 'Read more' );
	add_option( 'psnp_color', '#007cba' );
} );

add_action( 'admin_init', function () {
	if ( ! get_transient( 'psnp_activation_redirect' ) ) {
		return;
	}
	delete_transient( 'psnp_activation_redirect' );

	if ( ! isset( $_GET['activate-multi'] ) ) {
		wp_safe_redirect( admin_url( 'admin.php?page=wc-settings&tab=products&section=psnp' ) );
		exit;
	}
} );

add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), function ( $links ) {
	$settings_link = '<a href="admin.php?page=wc-settings&tab=products&section=psnp">Settings</a>';
	array_unshift( $links, $settings_link );

	return $links;
} );

add_action( 'woocommerce_before_add_to_cart_button', function () {
	require PSNP_PATH . 'note.php';
} );

define( 'PSNP_PATH', plugin_dir_path( __FILE__ ) );
define( 'PSNP_URL', plugin_dir_url( __FILE__ ) );

add_filter( 'woocommerce_get_sections_products', function ( $sections ) {
	$sections['psnp'] = __( 'Secure Note', 'product-secure-note' );

	return $sections;
} );

add_filter( 'woocommerce_get_settings_products', function ( $settings, $current_section ) {
	if ( $current_section !== 'psnp' ) {
		return $settings;
	}

	return [
		[
			'title' => __( 'Secure Note', 'product-secure-note' ),
			'type'  => 'title',
			'id'    => 'psnp_form_title',
		],
		[
			'title' => __( 'Enable', 'product-secure-note' ),
			'desc'  => __( 'Show the secure note on each product.', 'product-secure-note' ),
			'id'    => 'psnp_enable',
			'type'  => 'checkbox'
		],
		[
			'title'    => __( 'Title', 'product-secure-note' ),
			'desc'     => __( 'Enter a title for the secure note.', 'product-secure-note' ),
			'id'       => 'psnp_title',
			'type'     => 'text',
			'desc_tip' => __( 'This text will be the title of the secure note.', 'product-secure-note' ),
		],
		[
			'title' => __( 'Icon Show', 'product-secure-note' ),
			'desc'  => __( 'Show the secure note icon before the title.', 'product-secure-note' ),
			'id'    => 'psnp_icon',
			'type'  => 'checkbox'
		],
		[
			'title'    => __( 'Icon Size', 'product-secure-note' ),
			'desc'     => __( 'Adjust the icon size using pixels.', 'product-secure-note' ),
			'id'       => 'psnp_svg_size',
			'type'     => 'number',
			'desc_tip' => __( "Adjust the icon size to match your theme's font size.", 'product-secure-note' ),
		],
		[
			'title'    => __( 'Excerpt', 'product-secure-note' ),
			'desc'     => __( 'Enter the secure note excerpt.', 'product-secure-note' ),
			'id'       => 'psnp_excerpt',
			'type'     => 'textarea',
			'desc_tip' => __( 'This excerpt will be visible after the title.', 'product-secure-note' ),
		],
		[
			'title'    => __( 'Content', 'product-secure-note' ),
			'desc'     => __( 'Enter the secure note content.', 'product-secure-note' ),
			'id'       => 'psnp_content',
			'type'     => 'textarea',
			'desc_tip' => __( 'This content will be visible after user action.', 'product-secure-note' ),
		],
		[
			'title'    => __( 'User action label', 'product-secure-note' ),
			'desc'     => __( 'Enter the title for the user action label.', 'product-secure-note' ),
			'id'       => 'psnp_user_action_label',
			'type'     => 'text',
			'desc_tip' => __( 'By clicking this label, the content will be visible in the page.', 'product-secure-note' ),
		],
		[
			'title'    => __( 'Color Picker', 'product-secure-note' ),
			'desc'     => __( 'Select a color for your secure note.', 'product-secure-note' ),
			'id'       => 'psnp_color',
			'type'     => 'color',
			'desc_tip' => __( 'Choose a color to match your theme.', 'product-secure-note' ),
		],
		[
			'type' => 'sectionend',
			'id'   => 'psnp_end',
		],
	];
}, 10, 2 );

add_action( 'woocommerce_update_options_products', function () {
	if ( isset( $_POST['psnp_enable'] ) ) {
		update_option( 'psnp_enable', 'yes' );
	} else {
		update_option( 'psnp_enable', 'no' );
	}
	if ( isset( $_POST['psnp_icon'] ) ) {
		update_option( 'psnp_icon', 'yes' );
	} else {
		update_option( 'psnp_icon', 'no' );
	}

	if ( isset( $_POST['psnp_title'] ) and is_string( $_POST['psnp_title'] ) ) {
		update_option( 'psnp_title', sanitize_text_field( $_POST['psnp_title'] ) );
	}

	if ( isset( $_POST['psnp_user_action_label'] ) and is_string( $_POST['psnp_user_action_label'] ) ) {
		update_option( 'psnp_user_action_label', sanitize_text_field( $_POST['psnp_user_action_label'] ) );
	}

	if ( isset( $_POST['psnp_content'] ) and is_string( $_POST['psnp_content'] ) ) {
		update_option( 'psnp_content', sanitize_textarea_field( $_POST['psnp_content'] ) );
	}
	if ( isset( $_POST['psnp_excerpt'] ) and is_string( $_POST['psnp_excerpt'] ) ) {
		update_option( 'psnp_excerpt', sanitize_textarea_field( $_POST['psnp_excerpt'] ) );
	}

	if ( isset( $_POST['psnp_color'] ) and is_string( $_POST['psnp_color'] ) ) {
		update_option( 'psnp_color', sanitize_hex_color( $_POST['psnp_color'] ) );
	}
} );

add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style( 'product-secure-note-style', PSNP_URL . 'style.css', [], '1.0', 'all' );
} );
