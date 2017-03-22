<?php
/**
 * SoulButtons
 *
 * @package SoulButtons
 *
 * Plugin Name: SoulButtons
 * Plugin URI: https://gingersoulrecords.com
 * Description: Shortcodes for simple, minimal buttons. Includes options for hover animations, icons, analytics tracking, and click events.
 * Version: 0.1.3
 * Author: Dave Bloom
 * Author URI: https://gingersoulrecords.com
 * Text Domain: soulbuttons
 */

// bootstraping the plugin.
add_action( 'plugins_loaded', array( 'SoulButtons', 'init' ) );

/**
 * Main plugin class
 */
class SoulButtons {
	/**
	 * Plugin Settings Defaults
	 *
	 * @var array
	 */
	public static $options = array(
		'color'             => '#000',
		'color2'	          => '#fff',
		'padding'           => '10px 15px',
		'border'            => '3px',
		'min-width'         => 'auto',
		'track'             => false,
		'icon_dashicons'    => false,
		'icon_fontawesome'  => '0',
	);
	/**
	 * Plugin Settings object
	 *
	 * @var object tinyOptions
	 */
	public static $settings = false;
	/**
	 * Plugin Path
	 *
	 * @var string
	 */
	public static $plugin_path = '';
	/**
	 * Plugin Initialization
	 */
	public static function init() {
		self::$plugin_path = plugin_dir_path( __FILE__ );

		add_shortcode( 'soulbutton',      array( 'SoulButtons', 'shortcode' ) );
		add_shortcode( 'soulbuttons',     array( 'SoulButtons', 'shortcode' ) );

		add_action( 'wp_enqueue_scripts',     array( 'SoulButtons', 'styles' ) );
		add_action( 'wp_enqueue_scripts',     array( 'SoulButtons', 'scripts' ) );
		add_action( 'admin_enqueue_scripts',  array( 'SoulButtons', 'admin_scripts' ) );

		add_filter( 'soulbuttons_border',       array( 'SoulButtons', 'style_border' ) );
		add_filter( 'soulbuttons_transparent',  array( 'SoulButtons', 'style_transparent' ) );

		add_filter( 'mce_external_plugins', array( 'SoulButtons', 'editor_button_js' ) );
		add_filter( 'mce_buttons', 			    array( 'SoulButtons', 'editor_button' ) );

		// tinyOptions v 0.5.0.
		self::$options = wp_parse_args( get_option( 'soulbuttons_options' ), self::$options );
		add_action( 'plugins_loaded', array( 'SoulButtons', 'init_options' ), 9999 - 0050 );
	}
	/**
	 * Add SoulButtons button to WP Editor
	 *
	 * @param array $buttons list of editor buttons.
	 * @return array
	 */
	public static function editor_button( $buttons ) {
		array_push( $buttons, 'soulbuttons_shortcode' );
		return $buttons;
	}
	/**
	 * Add SoulButtons WP Editor button JS file
	 *
	 * @param array $plugin_array list of editor button JS files.
	 * @return array
	 */
	public static function editor_button_js( $plugin_array ) {
		$plugin_array['soulbuttons'] = plugins_url( 'soulbuttons-button.js', __FILE__ );
		return $plugin_array;
	}

	/**
	 * Enqueue SoulButtons styles
	 */
	public static function styles() {
		$depends = array();
		// load Dashicons if needed.
		if ( self::$options['icon_dashicons'] ) {
			wp_enqueue_style( 'dashicons' );
			$depends[] = 'dashicons';
		}
		// load local copy or CDN copy of FontAwesome, depending on user preferences.
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
		$style = self::_generate_style();
		wp_add_inline_style( 'soulbuttons', $style );
		wp_enqueue_style( 'soulbuttons' );
	}
	/**
	 * Enqueue SoulButtons scripts
	 */
	public static function scripts() {
		wp_register_script( 'gsap',             plugins_url( 'TweenMax.min.js', __FILE__ ), array( 'jquery' ) );
		wp_register_script( 'gsap-scrollto',    plugins_url( 'ScrollToPlugin.min.js', __FILE__ ), array( 'jquery', 'gsap' ) );
		wp_register_script( 'soulbuttons',      plugins_url( 'soulbuttons.js', __FILE__ ), array( 'jquery', 'gsap', 'gsap-scrollto' ) );
		wp_enqueue_script( 'soulbuttons' );
	}
	/**
	 * Enqueue SoulButtons WP Admin scripts
	 */
	public static function admin_scripts() {
		wp_register_script( 'soulbuttons-admin', plugins_url( 'soulbuttons-admin.js', __FILE__ ), array( 'jquery' ) );
		$data = array(
			'icon' => plugins_url( 'soulbuttons-button.png', __FILE__ ),
			'texts' => array(
				'main_label' 			=> __( 'Main', 'soulbuttons' ),
				'advanced_label'	=> __( 'Advanced', 'soulbuttons' ),
				'link_label' 		  => __( 'Link', 'soulbuttons' ),
				'style_label' 		=> __( 'Style', 'soulbuttons' ),
				'text_label' 		  => __( 'Text', 'soulbuttons' ),
				'align_label' 		=> __( 'Text Alignment', 'soulbuttons' ),
				'icon_label'      => __( 'Icon', 'soulbuttons' ),
				'icon_tooltip'    => __( 'i.e. "fa-shopping-cart" or "dashicons-arrow-left"', 'soulbuttons' ),
				'iconpos_label'   => __( 'Icon Position', 'soulbuttons' ),
				'style_options'   => array(
					array(
						'text'  => __( 'Solid (default)', 'soulbuttons' ),
						'value' => '',
					),
					array(
						'text'  => __( 'Border', 'soulbuttons' ),
						'value' => 'border',
					),
					array(
						'text'  => __( 'Rounded Corners', 'soulbuttons' ),
						'value' => 'rounded',
					),
					array(
						'text'  => __( 'Transparent', 'soulbuttons' ),
						'value' => 'transparent',
					),
				),
				'align_options'   => array(
					array(
						'text'  => __( 'Center (default)', 'soulbuttons' ),
						'value' => '',
					),
					array(
						'text'  => __( 'Left', 'soulbuttons' ),
						'value' => 'left',
					),
					array(
						'text'  => __( 'Right', 'soulbuttons' ),
						'value' => 'right',
					),
				),
				'iconpos_options'   => array(
					array(
						'text'  => __( 'Before text (default)', 'soulbuttons' ),
						'value' => '',
					),
					array(
						'text'  => __( 'After text', 'soulbuttons' ),
						'value' => 'after',
					),
				),
				'class_label' 					=> __( 'CSS classes', 'soulbuttons' ),
				'wordlimit_label'				=> __( 'Word Limit', 'soulbuttons' ),
				'add_dialog_title'  		=> __( 'Add SoulButton', 'soulbuttons' ),
			),
		);
		wp_localize_script( 'soulbuttons-admin', 'soulbuttons', $data );
		wp_enqueue_script( 'soulbuttons-admin' );
	}
	/**
	 * Generate inline styles
	 *
	 * @return string css styles
	 */
	private static function _generate_style() {
		$style = '.soulbuttons{padding:' . self::$options['padding'] . ';border-width:' . self::$options['border'] . ';min-width:' . self::$options['min-width'] . ';}';
		$style = apply_filters( 'soulbuttons_styles', $style );
		return $style;
	}
	/**
	 * Shortcode callback
	 *
	 * @param array  $atts    shortcode attributes.
	 * @param string $content shortcode content.
	 *
	 * @return $string shortcode output
	 */
	public static function shortcode( $atts = array(), $content = '' ) {
		$defaults = array(
			'type'            => 'a', // TO DO: could also be 'button', 'span'.
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
			'hover'           => false,
			'align'           => false,
			'padding'         => false,
			'border-width'    => false,
			'width'           => false,
			'target'          => false,
			'target-effect'   => 'fadeInFromCenter',
			'scrollto'        => false,
			'scrollto-offset' => 0,
		);
		$atts = wp_parse_args( $atts, $defaults );
		if ( isset( $atts['link'] ) ) {
			$atts['href'] = $atts['link'];
			unset( $atts['link'] );
		}
		// generic filter for all SoulButtons shortcodes.
		$atts = apply_filters( 'soulbuttons', $atts );
		// specific filter for different SoulButtons styles.
		$atts = apply_filters( "soulbuttons_{$atts['style']}", $atts );
		$content = do_shortcode( $content );
		if ( $atts['icon'] ) {
			$icon = false;
			if ( 0 === strpos( $atts['icon'], 'dashicons' ) ) {
				$icon = "<span class=\"dashicons {$atts['icon']} soulbuttons-icon soulbuttons-icon-{$atts['icon-position']}\"></span>";
			}
			if ( 0 === strpos( $atts['icon'], 'fa' ) ) {
				$icon = "<i class=\"fa {$atts['icon']} soulbuttons-icon soulbuttons-icon-{$atts['icon-position']}\"></i>";
			}
			if ( $icon ) {
				if ( 'after' === $atts['icon-position'] ) {
					$content .= $icon;
				} else {
					$content = $icon . $content;
				}
			}
		}
		$style = "background-color:{$atts['color']}; color:{$atts['text']}; border-color:{$atts['border']};";
		if ( false !== $atts['padding'] ) {
			$style .= "padding:{$atts['padding']};";
		}
		if ( false !== $atts['border-width'] ) {
			$style .= "border-width:{$atts['border-width']};";
		}
		if ( false !== $atts['width'] ) {
			$style .= "min-width:{$atts['width']};";
		}
		$class = "soulbuttons soulbuttons-{$atts['style']}" . ( $atts['class'] ? ( ' ' . $atts['class'] ) : '' ) ;
		$class = explode( ' ', $class );
		$hover = array();
		if ( $atts['hover'] ) {
			$hover = explode( ' ', $atts['hover'] );
			foreach ( $hover as $key => $value ) {
				$hover[ $key ] = "soulbuttons-hover-{$value}";
			}
		}
		if ( $atts['align'] ) {
			$class['align'] = "soulbuttons-align-{$atts['align']}";
		}
		if ( $atts['scrollto'] ) {
			if ( in_array( $atts['scrollto'], array( true, 'true' ), true ) ) {
				$atts['scrollto'] = 0.5;
			}
			$class['scrollto'] = 'soulbuttons-scrollto';
			$arguments['data-scrollto'] = $atts['scrollto'];
			$arguments['data-scrollto-offset'] = $atts['scrollto-offset'];
		}
		$class = array_merge( $class, $hover );
		if ( 'false' !== $atts['track'] && $atts['track'] ) {
			$class[] = 'soulbuttons-track';
			$ga = $atts['track'];
			if ( '1' === $ga || 'true' === $ga ) {
				$ga = sanitize_title( $atts['href'] );
			}
			if ( '' === $ga ) {
				$ga = sanitize_title( $content );
			}
			$arguments['data-ga'] = $ga;
		}
		$class = implode( ' ', $class );
		$arguments['style'] = $style;
		$arguments['class'] = $class;
		$arguments['href']  = $atts['href'];
		if ( $atts['target'] ) {
			$arguments['data-target'] = $atts['target'];
			$arguments['data-effect'] = $atts['target-effect'];
		}
		$arguments['id']    = isset( $atts['id'] ) ? $atts['id'] : false;
		foreach ( $arguments as $key => $value ) {
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

	/**
	 * Customization for `border` button style
	 *
	 * @param  array $atts button shortcode attributes.
	 * @return array        button shortcode attributes.
	 */
	public static function style_border( $atts ) {
		$atts['text'] = ( $atts['text'] === self::$options['color2'] ) ? $atts['color'] : $atts['text'];
		$atts['border'] = $atts['color'];
		$atts['color'] = 'transparent';

		return $atts;
	}
	/**
	 * Customization for `transparent` button style
	 *
	 * @param  array $atts button shortcode attributes.
	 * @return array        button shortcode attributes.
	 */
	public static function style_transparent( $atts ) {
		$atts['text'] = ( $atts['text'] === self::$options['color2'] ) ? $atts['color'] : $atts['text'];
		$atts['border'] = 'transparent';
		$atts['color'] = 'transparent';

		return $atts;
	}

	/**
	 * Build plugin options page
	 */
	public static function init_options() {
		self::$settings = array(
			'page' => array(
				'title' 			=> __( 'SoulButtons Settings', 'soulbuttons' ),
				'menu_title'	=> __( 'SoulButtons', 'soulbuttons' ),
				'slug' 				=> 'soulbuttons-settings',
				'option'			=> 'soulbuttons_options',
				// optional.
				'description'	=> __( 'Some general information about the plugin', 'soulbuttons' ),
			),
			'sections' => array(
				'colors' => array(
					'title'				=> __( 'Colors', 'soulbuttons' ),
					'description'	=> __( 'Select default colors', 'soulbuttons' ),
					'fields'	=> array(
						'color' => array(
							'title'	=> __( 'Main Color', 'soulbuttons' ),
							'attributes' => array(
								'type'  => 'colorpicker',
							),
						),
						'color2' => array(
							'title'	=> __( 'Secondary Color', 'soulbuttons' ),
							'attributes' => array(
								'type'  => 'colorpicker',
							),
						),
					),
				),
				'appearance' => array(
					'title'				=> __( 'Appearance', 'soulbuttons' ),
					'description'	=> __( 'Select default appearance settings', 'soulbuttons' ),
					'fields'	=> array(
						'padding' => array(
							'title'	=> __( 'Button padding', 'soulbuttons' ),
							'description'	=> __( 'i.e. <code>10px 10px</code>', 'soulbuttons' ),
						),
						'border' => array(
							'title'	=> __( 'Border size', 'soulbuttons' ),
							'description'	=> __( 'i.e. <code>3px</code>', 'soulbuttons' ),
						),
						'min-width' => array(
							'title'	=> __( 'Minimal width', 'soulbuttons' ),
							'description'	=> __( 'i.e. <code>100px</code>', 'soulbuttons' ),
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
