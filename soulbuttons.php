<?php
/*
Plugin Name: SoulButtons
Plugin URI: http://gingersoulrecords.com
Description: A starter plugin template
Version: 0.1.0
Author: Dave Bloom
Author URI: http://gingersoulrecords.com
Text Domain: soulbuttons
*/

// bootstraping the plugin
add_action( 'plugins_loaded', array( 'SoulButtons', 'init' ) );

class SoulButtons {
  public static $options = array(
		'color' => '#000',
		'color2'	=> '#fff',
    'track'   => false,
	);
	public static $settings = false;
  public static $plugin_path = '';
  public static function init() {
    self::$plugin_path = plugin_dir_path( __FILE__ );

    add_shortcode( 'soulbutton',      array( 'SoulButtons', 'shortcode' ) );

    add_action( 'wp_enqueue_scripts', array( 'SoulButtons', 'styles' ) );
    add_action( 'wp_enqueue_scripts', array( 'SoulButtons', 'scripts' ) );

    add_filter( 'soulbuttons_border',       array( 'SoulButtons', 'style_border' ) );
    add_filter( 'soulbuttons_transparent',  array( 'SoulButtons', 'style_transparent' ) );

    // tinyOptions v 0.4.0
		self::$options = wp_parse_args( get_option( 'soulbuttons_options' ), self::$options );
		add_action( 'plugins_loaded', array( 'SoulButtons', 'init_options' ), 9999 - 0040 );
  }
  public static function styles() {
    wp_register_style( 'soulbuttons', plugins_url( 'soulbuttons.css', __FILE__ ) );
    wp_enqueue_style( 'soulbuttons' );
  }
  public static function scripts() {
    wp_register_script( 'soulbuttons', plugins_url( 'soulbuttons.js', __FILE__ ), array( 'jquery' ) );
    wp_enqueue_script( 'soulbuttons' );
  }
  public static function shortcode( $atts = array(), $content = '' ) {
    $defaults = array(
      'type'  => 'a', // could also be 'button', 'span'
      'href'  => '#',
      'style' => 'solid',
      'class' => false,
      'css'   => '',
      'color' => self::$options['color'],
      'text'  => self::$options['color2'],
      'border'=> self::$options['color'],
      'track' => self::$options['track'],
    );
    $atts = wp_parse_args( $atts, $defaults );
    if ( isset( $atts['link'] ) ) {
      $atts['href'] = $atts['link'];
      unset( $atts['link'] );
    }
    $atts = apply_filters( "soulbuttons_{$atts['style']}", $atts );
    $content = do_shortcode( $content );
    $style = "background-color:{$atts['color']}; color:{$atts['text']}; border-color:{$atts['border']};";
    $class = "soulbuttons soulbuttons-{$atts['style']}" . ( $atts['class'] ? ( ' ' . $atts['class'] ) : '' ) ;
    if ( 'false' !== $atts['track'] && $atts['track'] ) {
      $class .= ' soulbuttons-track';
      $ga = $atts['track'];
      if ( '1' === $ga || 'true' === $ga ) {
        $ga = sanitize_title( $atts['href'] );
      }
      if ( "" === $ga ) {
        $ga = sanitize_title( $content );
      }
      $arguments['data-ga'] = $ga;
    }
    $arguments['style'] = $style;
    $arguments['class'] = $class;
    $arguments['href']  = $atts['href'];
    $arguments['id']    = isset( $atts['id'] ) ? $atts['id'] : false;
    foreach( $arguments as $key => $value ) {
      if ( is_array( $value ) ) {
        $value = implode( ' ', $value );
      }
      $arguments[ $key ] = "{$key}=\"{$value}\"";
    }
    $arguments = implode( ' ', $arguments );
    $before = "<{$atts['type']} {$arguments}>";
    $after = "</{$atts['type']}>";
    $content = "{$before}{$content}{$after}";
    return $content;
  }

  public static function style_border( $atts ) {
    $atts['text'] = ( $atts['text'] === self::$options['color2'] ) ? $atts['color'] : $atts['text'];
    $atts['border'] = $atts['color'];
    $atts['color'] = 'transparent';

    return $atts;
  }
  public static function style_transparent( $atts ) {
    $atts['text'] = ( $atts['text'] === self::$options['color2'] ) ? $atts['color'] : $atts['text'];
    $atts['border'] = 'transparent';
    $atts['color'] = 'transparent';

    return $atts;
  }

  public static function init_options() {
		self::$settings = array(
			'page' => array(
				'title' 			=> __( 'SoulButtons Settings', 'soulbuttons' ),
				'menu_title'	=> __( 'SoulButtons', 'soulbuttons' ),
				'slug' 				=> 'soulbuttons-settings',
				'option'			=> 'soulbuttons_options',
				// optional
				'description'	=> __( 'Some general information about the plugin', 'soulbuttons' ),
			),
			'sections' => array(
				'colors' => array(
					'title'				=> __( 'Colors', 'soulbuttons' ),
					'description'	=> __( 'Select default colors', 'soulbuttons' ),
					'fields'	=> array(
						'color' => array(
							'title'	=> __( 'Main Color', 'soulbuttons' ),
              // 'description'	=> __( 'Main color', 'soulbuttons' ),
						),
						'color2' => array(
							'title'	=> __( 'Simple Input', 'soulbuttons' ),
							'description'	=> __( 'With a description', 'soulbuttons' ),
						),
					),
				),
        'advanced' => array(
					'title'				=> __( 'Advanced', 'soulbuttons' ),
					'description'	=> __( 'Advanced sections', 'soulbuttons' ),
					'fields'	=> array(
						'track' => array(
							'title'	   => __( 'Tracking', 'soulbuttons' ),
              'callback' => 'checkbox',
              'label'    => __( 'Enable button click event tracking via Google Analytics', 'soulbuttons' ),
              // 'description'	=> __( 'Main color', 'soulbuttons' ),
						),
						'color2' => array(
							'title'	=> __( 'Simple Input', 'soulbuttons' ),
							'description'	=> __( 'With a description', 'soulbuttons' ),
						),
					),
				),
			),
			'l10n' => array(
				'no_access'			=> __( 'You do not have sufficient permissions to access this page.', 'soulbuttons' ),
				'save_changes'	=> esc_attr( 'Save Changes', 'soulbuttons' ),
			),
		);
		require_once( self::$plugin_path . 'tiny/tiny.options.php' );
		self::$settings = new tinyOptions( self::$settings, __CLASS__ );
	}

}
