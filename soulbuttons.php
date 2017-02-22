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
		'color'             => '#000',
		'color2'	          => '#fff',
    'track'             => false,
    'icon_dashicons'    => false,
    'icon_fontawesome'  => '0',
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
    $depends = array();
    // load Dashicons if needed
    if ( self::$options['icon_dashicons'] ) {
      wp_enqueue_style( 'dashicons' );
      $depends[] = 'dashicons';
    }
    // load local copy or CDN copy of FontAwesome, depending on user preferences
    if ( 'local' === self::$options['icon_fontawesome'] ) {
      wp_register_style( 'font-awesome', plugins_url( 'font-awesome/css/font-awesome.min.css', __FILE__ ) );
      wp_enqueue_style( 'font-awesome' );
      $depends[] = 'font-awesome';
    } elseif ( 'cdn' === self::$options['icon_fontawesome'] ) {
      wp_register_style( 'font-awesome-cdn', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
      wp_enqueue_style( 'font-awesome-cdn' );
      $depends[] = 'font-awesome-cdn';
    }
    wp_register_style( 'soulbuttons', plugins_url( 'soulbuttons.css', __FILE__ ), $depends );
    wp_enqueue_style( 'soulbuttons' );
  }
  public static function scripts() {
    wp_register_script( 'soulbuttons', plugins_url( 'soulbuttons.js', __FILE__ ), array( 'jquery' ) );
    wp_enqueue_script( 'soulbuttons' );
  }
  public static function shortcode( $atts = array(), $content = '' ) {
    $defaults = array(
      'type'            => 'a', // TO DO: could also be 'button', 'span'
      'href'            => '#',
      'style'           => 'solid',
      'class'           => false,
      'css'             => '',
      'color'           => self::$options['color'],
      'text'            => self::$options['color2'],
      'border'          => self::$options['color'],
      'track'           => self::$options['track'],
      'icon'            => false,
      'icon-position'   => 'before',
    );
    $atts = wp_parse_args( $atts, $defaults );
    if ( isset( $atts['link'] ) ) {
      $atts['href'] = $atts['link'];
      unset( $atts['link'] );
    }
    $atts = apply_filters( "soulbuttons_{$atts['style']}", $atts );
    $content = do_shortcode( $content );
    if ( $atts[ 'icon' ] ) {
      $icon = false;
      if ( 0 === strpos( $atts['icon'], 'dashicons' ) ) {
        $icon = "<span class=\"dashicons {$atts['icon']} soulbuttons-icon-{$atts['icon-position']}\"></span>";
      }
      if ( 0 === strpos( $atts['icon'], 'fa' ) ) {
        $icon = "<i class=\"fa {$atts['icon']} soulbuttons-icon-{$atts['icon-position']}\"></i>";
      }
      if ( $icon ) {
        if ( 'after' == $atts['icon-position'] ) {
          $content .= $icon;
        } else {
          $content = $icon . $content;
        }
      }
    }
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
        'icons' => array(
					'title'				=> __( 'Icons', 'soulbuttons' ),
					'description'	=> __( 'Manage icon fonts', 'soulbuttons' ),
					'fields'	=> array(
            'icon_dashicons' => array(
							'title'	   => __( 'Dashicons', 'soulbuttons' ),
              'callback' => 'checkbox',
              'label'    => __( 'Enable use of WordPress native Dashicons font on front-end', 'soulbuttons' ),
              // 'description'	=> __( 'Main color', 'soulbuttons' ),
						),
						'icon_fontawesome' => array(
							'title'	     => __( 'Font Awesome', 'soulbuttons' ),
              'callback'    => 'listfield',
              'list'        => array(
                '0'       => __( 'Disable Font Awesome', 'soulbuttons' ),
                'local'   => __( 'Use local copy of Font Awesome', 'soulbuttons' ),
                'cdn'     => __( 'Use CDN version of Font Awesome', 'soulbuttons' ),
              ),
              'attributes'  => array(
                'type'      => 'radio',
              ),
							'description'	=> __( 'Enable use of popular Font Awesome iconfont', 'soulbuttons' ),
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
