<?php
/*
Plugin Name: SoulButton
Plugin URI: http://gingersoulrecords.com
Description: A starter plugin template
Version: 0.1.0
Author: Dave Bloom
Author URI: http://gingersoulrecords.com
Text Domain: soulbutton
*/

add_action( 'plugins_loaded', array( 'SoulButton', 'init' ) );

class SoulButton {
  // private static $styles = array(
  //   'solid' => 'background-color:#000; color:#fff; border',
  //   'transparent' => 'display:inline-block; padding:0.5em 1em; border:5px solid #000; background-color:transparent; color:#000; text-decoration:none;',
  // );
  public static $options = array(
		'color' => '#000',
		'color2'	=> '#fff',
	);
	public static $settings = false;
  public static $plugin_path = '';
  public static function init() {
    self::$plugin_path = plugin_dir_path( __FILE__ );

    add_shortcode( 'soulbutton', array( 'SoulButton', 'shortcode' ) );
    add_action( 'wp_enqueue_scripts', array( 'SoulButton', 'styles' ) );

    add_filter( 'soulbutton_transparent', array( 'SoulButton', 'style_transparent' ) );

    // tinyOptions v 0.4.0
		self::$options = wp_parse_args( get_option( 'soulbutton_options' ), self::$options );
		add_action( 'plugins_loaded', array( 'SoulButton', 'init_options' ), 9999 - 0040 );
  }
  public static function styles() {
    wp_register_style( 'soulbutton', plugins_url( 'SoulButton.css', __FILE__ ) );
    wp_enqueue_style( 'soulbutton' );
  }
  public static function shortcode( $atts = array(), $content = '' ) {
    $defaults = array(
      'type'  => 'a', // could also be 'button', 'span'
      'href'  => '#',
      'style' => 'solid',
      'css'   => '',
      'color' => self::$options['color'],
      'text'  => self::$options['color2'],
      'border'=> self::$options['color'],
    );
    $atts = wp_parse_args( $atts, $defaults );
    if ( isset( $atts['link'] ) ) {
      $atts['href'] = $atts['link'];
      unset( $atts['link'] );
    }
    $atts = apply_filters( "soulbutton_{$atts['style']}", $atts );
    $content = do_shortcode( $content );
    $style = "background-color:{$atts['color']}; color:{$atts['text']}; border-color:{$atts['border']};";
    $class = "soulbutton soulbutton-{$atts['style']}";
    $id = isset( $atts['id'] ) ? $atts['id'] : false;
    $before = "<{$atts['type']} href=\"{$atts['href']}\" class=\"{$class}\" style=\"{$style}\" {$id}>";
    $after = "</{$atts['type']}>";
    $content = "{$before}{$content}{$after}";
    return $content;
  }

  public static function style_transparent( $atts ) {
    $atts['text'] = $atts['color'];
    $atts['border'] = $atts['color'];
    $atts['color'] = 'transparent';

    return $atts;
  }

  public static function init_options() {
		self::$settings = array(
			'page' => array(
				'title' 			=> __( 'SoulButton Settings', 'soulbutton' ),
				'menu_title'	=> __( 'SoulButton', 'soulbutton' ),
				'slug' 				=> 'soulbutton-settings',
				'option'			=> 'soulbutton_options',
				// optional
				'description'	=> __( 'Some general information about the plugin', 'soulbutton' ),
			),
			'sections' => array(
				'colors' => array(
					'title'				=> __( 'Colors', 'soulbutton' ),
					'description'	=> __( 'Select default colors', 'soulbutton' ),
					'fields'	=> array(
						'color' => array(
							'title'	=> __( 'Main Color', 'soulbutton' ),
              // 'description'	=> __( 'Main color', 'soulbutton' ),
						),
						'color2' => array(
							'title'	=> __( 'Simple Input', 'soulbutton' ),
							'description'	=> __( 'With a description', 'soulbutton' ),
						),
					),
				),
			),
			'l10n' => array(
				'no_access'			=> __( 'You do not have sufficient permissions to access this page.', 'soulbutton' ),
				'save_changes'	=> esc_attr( 'Save Changes', 'soulbutton' ),
			),
		);
		require_once( self::$plugin_path . 'tiny/tiny.options.php' );
		self::$settings = new tinyOptions( self::$settings, __CLASS__ );
	}


}
