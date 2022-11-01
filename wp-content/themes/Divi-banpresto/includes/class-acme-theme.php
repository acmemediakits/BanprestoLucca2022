<?php
	
	class AcmeTheme
	{
		private $version;
		private $theme_directory_uri;
		private $theme_directory;
		private $environment;
		private $vars;
		
		public function __construct ( $environment = 'production', $version = '1.0' ) {
			$this->environment = $environment;
			$this->version = $version;
			$this->initTheme();
		}

        public function getThemeDirectory(){

            return $this->theme_directory;
        }

        public function getThemeDirectoryUri(){

            return $this->theme_directory_uri;
        }

		private function initTheme() {
			$this->theme_directory = get_stylesheet_directory();
			$split = preg_split( '/\//', $this->theme_directory );
			$theme_name = end( $split );
			$split = preg_split( '/-/', $theme_name );
			$myname = end( $split );
			$this->theme_directory_uri = sprintf( '%s-%s', get_template_directory_uri(), $myname );
			//do_action( 'qm/debug', [$this->theme_directory_uri, $this->theme_directory] );
		}
		
		public function get_options () {
			if ( $options = get_transient( $this->vars['options_name'] ) ) {
				return $options;
			}
			
			return get_option( $this->vars['options_name'] );
		}
		
		public function wp_head () {
			add_filter( 'theme_styles', function ( $styles ) {
				//$styles['funky-header'] = '/css/header.css';
				//$styles['funky-search'] = '/css/search.css';
				
				return $styles;
			} );
			
			print '<link rel="manifest" href="/manifest.json">';
			add_filter('acme_custom_metas', function ( $metas ) {
				//$metas['acme_is_mobile'] = $this->isMobile();
				
				return $metas;
			});
			
			foreach ( $this->custom_metas() as $name => $content ) {
				printf( '<meta name="%s" content="%s" />', $name, is_bool( $content ) ? intval( $content ) : $content );
			}
		}
		
		public function after_setup_theme () {
			add_theme_support( 'woocommerce' );
			add_filter( 'body_class', function ( $classes ) {
				/*if ( $this->isMobile() ) {
					$classes[] = 'mobile';
				}*/
				
				return $classes;
			});
			
		}
		
		public function custom_metas () {
			$args = [
				'acme_blog_info_language' => get_bloginfo( 'language' ),
				'acme_environment'        => $this->environment,
				'acme_theme_version'      => $this->version
			];
			
			$metas = apply_filters( 'acme_custom_metas', $args );
			
			return $metas;
		}
		
		public function wp_enqueue_scripts () {

			$ver = $this->environment == 'production' ? $this->version : time();
			$arScripts = $this->wp_register_scripts();
			foreach ( $arScripts as $handle => $array ) {
				$handles[] = $handle;
				//New release
				$path = $this->theme_directory_uri . $array['src'];
				if (str_starts_with($array['src'], 'http')) {
					$path = $array['src'];
				}
				add_filter( 'bp-cached', function ( $res ) use ( $path ) {
					$res[] = $path;
					
					return $res;
				}, 1 );
				//end release
				wp_register_script( $handle, $path, $array['dep'], $ver, true );
			}

            wp_localize_script( 'acme-main',
                'bpCached',
                apply_filters('bp-cached',[])
            );
			foreach ( $handles as $script ) {
				wp_enqueue_script( $handle );
			}
		}
		
		public function wp_register_scripts ( ) {
			
			return apply_filters( 'acme_custom_scripts', [], $this->environment );
		}
		
		public function wp_enqueue_styles () {
			$ver = $this->environment == 'production' ? $this->version : time();
			$styles = $this->theme_styles();
			foreach ( $styles as $handle => $file ) {
				$path = $this->theme_directory_uri . $file;
				add_filter( 'bp-cached', function ( $res ) use ( $path ) {
					$res[] = $path;
					
					return $res;
				}, 1 );
				wp_register_style( $handle, $path, [], $ver, 'all' );
				wp_enqueue_style( $handle );
			}
			
			$dereg = apply_filters( 'acme_remove_styles', [] );
			foreach ( $dereg as $handle ) {
				wp_dequeue_style( $handle );
			}
		}
		
		public function theme_styles ( $append = [] ) {
			$append = [ 'acme-main' => '/style.css' ];
			
			return apply_filters( 'theme_styles', $append );
		}
		
		
		public function get_template_part_example () {
			get_template_part( 'template-dir/file_name_without_extension' );
		}
		
		public function test_filter () {
			add_action( 'test_filter', function () {
			
			echo $this->toy()['title'];
		} );
		}
		
		public function toy ( $args = [] ) {
			$args = wp_parse_args( $args, [ 'title' => 'Testing Puroposes' ] );
			
			return apply_filters( 'filter_test', $args );
		}
	}
