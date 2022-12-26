<?php
	require_once dirname( __FILE__ ) . '/includes/class-acme-theme.php';
	require_once dirname( __FILE__ ) . '/includes/class-acme-theme-extensions.php';
     // Include mobile detect class
    include_once dirname( __FILE__ ) . '/includes/class-mobile-detect.php';
    $device = new Mobile_Detect();
	$bp_release = '1.9.8';

		
		add_action( 'wp', function () use ( $device ) {
			$curPage = get_queried_object();
			// do_action('qm/debug', $curPage);
			if ( is_object( $curPage ) ) {
				$pg = 'banpresto-lucca-comics-2022';
				if ( $pg != $curPage->post_name ) {
					if (false && ! $device->isMobile() && ! $device->isTablet() ) {
						wp_redirect( home_url( $pg ) );
					}
				}
			}
		} );
	
	//if ( is_front_page() ) {
		add_action( 'wp_head', function () use ( $bp_release ) {
			if ( !$_GET['pwa'] ) {
				return;
			}
			printf( '
                <script type="text/javascript">
         //FROM MDN
                const registerServiceWorker = async () => {
  if ("serviceWorker" in navigator) {
    try {
      const registration = await navigator.serviceWorker.register("/sw.js?ver=%1$s", {
        scope: "./",
      });
      if (registration.installing) {
        console.log("Service worker installing");
      } else if (registration.waiting) {
        console.log("Service worker installed");
      } else if (registration.active) {
        console.log("Service worker active");
        return true;
      }
    } catch (error) {
      console.error(`Registration failed with ${error}`);
    }
  }
};



registerServiceWorker()
    </script>
                ', $bp_release );
		}, PHP_INT_MIN );
		
	//}
	define( 'acme_td', 'acme_td' );
	
	$theme = new AcmeTheme('production', $bp_release);
	$themeExtra = new AcmeThemeExtensions( acme_td , $theme->getThemeDirectory(), $theme->getThemeDirectoryUri());

    //Acme customs metas

    add_filter('acme_custom_metas', function ( $metas ) {

            $metas['name']='Banpresto Lucca 2022';
            $metas['scope']= '.';
            $metas['display']='standalone';
            $metas['start_url']= '/';
            $metas['short_name']='Banpresto 2022';
            $metas['description']='Edizione speciale catalogo Lucca Comics 2022';
            $metas['background-color']='#000000';
            $metas['theme-color']='#FFFFFF';

    return $metas;
    });


    //Settings sections colors
    add_filter('acme_bp_cat_vars', function ($vars) use ($themeExtra) {
	    $vars['colors'] = [
		    'one-piece'          => $themeExtra->color( 'yellow' ),
		    'one-piece-film-red' => $themeExtra->color( 'red' ),
		    'naruto'             => $themeExtra->color( 'yellow' ),
		    'dragon-ball'        => $themeExtra->color( 'purple' ),
		    'sailor-moon'        => $themeExtra->color( 'pink' ),
		    'my-hero-academia'   => $themeExtra->color( 'blue' ),
		    'jujutsu-kaisen'     => $themeExtra->color( 'orange' ),
		    'demon-slayer'       => $themeExtra->color( 'green' ),
		    'tokyo-revengers'    => $themeExtra->color( 'red' ),
		    'altre-figures'      => $themeExtra->color( 'grey' )
	    ];
		
        return $vars;
    });
	
	
	function bp_special_copies() {
		$copies = [
			50  => 'B.S/S &copy;2022DBSFP - BIRD STUDIO/SHUEISHA ©2022 DRAGON BALL SUPER Film Partners',
			125 => 'Hiroyuki Takei, KODANSHA/ “SHAMAN KING” Production Committee, TX',
			127 => '2017 REKI KAWAHARA/KADOKAWA CORPORATION AMW/SAO-A Project',
			128 => 'Tite Kubo/Shueisha, TV TOKYO, dentsu, Pierrot'
		];
		
		return apply_filters( 'bp_product_copy', $copies );
	}
	
    //Settings copyright strings
    function bp_copyrights(){
        $str='';
	    $copy = [
		    'one-piece'          => 'Eiichiro Oda/Shueisha, Toei Animation',
		    'one-piece-film-red' => 'Eiichiro Oda/2022 "One Piece" production committee',
		    'naruto'             => '2002 MASASHI KISHIMOTO / 2007 SHIPPUDEN All Rights Reserved.',
		    'dragon-ball'        => 'Bird Studio/Shueisha, Toei Animation',
		    'sailor-moon'        => 'Naoko Takeuchi / PNP, TOEI ANIMATION',
		    'my-hero-academia'   => 'K. Horikoshi / Shueisha, My Hero Academia Project',
		    'jujutsu-kaisen'     => 'Gege Akutami/Shueisha, JUJUTSU KAISEN Project',
		    'demon-slayer'       => 'Koyoharu Gotoge / SHUEISHA, Aniplex, ufotable',
		    'tokyo-revengers'    => 'Ken Wakui, KODANSHA / TOKYO REVENGERS Anime Production Committee.',
		    'altre-figures'      => '',
		    'bleach'             => '2001 by Tite Kubo/SHUEISHA Inc.',
		    'attack-on-titan'    => 'Hajime Isayama / Kodansha / “Attack on Titan” The Final Season Production Committee',
		    'sword-art-online'   => '',
		    'shaman-king'        => 'Hiroyuki Takei / Kodansha Ltd.',
		    'blue-lock'          => 'Muneyuki Kaneshiro, Yusuke Nomura, KODANSHA/BLUE LOCK Production Committee.',
		    'digimon'            => 'Akiyoshi Hongo, Toei Animation',
		    'jojo'               => 'LUCKY LAND COMMUNICATIONS /SHUEISHA,JOJO’s Animation DU Project',
		    're-zero'            => 'Tappei Nagatsuki, KADOKAWA/Re:ZERO PARTNERS',
		    'saint-seya'         => 'Masami Kurumada, Toei Animation',
		    'spy-x-family'       => 'Tatsuya Endo/Shueisha, SPY x FAMILY Project'
	    ];
		
        return apply_filters('bp_copy', $str, $copy);
    }

    //Settings logos strings
	function bp_logos() {
        $arr=[];
	    $logos = [
		    'one-piece'          => [
			    'https://www.banprestolucca2022.com/wp-content/uploads/2022/10/one_piece_logo.png',
			    'https://www.banprestolucca2022.com/wp-content/uploads/2022/10/TOEI_LOGO-small2.png'
		    ],
		    'one-piece-film-red' => [
			    'https://www.banprestolucca2022.com/wp-content/uploads/2022/10/one_piece_film_red_logo.png',
			    'https://www.banprestolucca2022.com/wp-content/uploads/2022/10/TOEI_LOGO-small2.png'
		    ],
		    'naruto'             => [
			    'https://www.banprestolucca2022.com/wp-content/uploads/2022/10/Naruto_Shippuden_logo.png',
		    ],
		    'dragon-ball'        => [
			    'https://www.banprestolucca2022.com/wp-content/uploads/2022/10/dragonball_super_logo.png',
			    'https://www.banprestolucca2022.com/wp-content/uploads/2022/10/TOEI_LOGO-small2.png'
		    ],
		    'sailor-moon'        => [
			    'https://www.banprestolucca2022.com/wp-content/uploads/2022/10/sailor_moon_logo.png'
		    ],
		    'my-hero-academia'   => [
			    'https://www.banprestolucca2022.com/wp-content/uploads/2022/10/my_hero_academia_logo.png'
		    ],
		    'jujutsu-kaisen'     => [
			    'https://www.banprestolucca2022.com/wp-content/uploads/2022/10/Jujutsu_logo.png'
		    ],
		    'demon-slayer'       => [
			    'https://www.banprestolucca2022.com/wp-content/uploads/2022/10/demon_slyer_logo.png'
		    ],
		    'tokyo-revengers'    => [
			    'https://www.banprestolucca2022.com/wp-content/uploads/2022/10/Tokyo_Revengers_Logo_EN_Horiz_White@2x.png'
		    ],
		    'altre-figures'      => [  ],
		    'bleach'             => [
			    'https://www.banprestolucca2022.com/wp-content/uploads/2022/10/bleach_logo.png'
		    ],
		    'attack-on-titan'    => [
			    'https://www.banprestolucca2022.com/wp-content/uploads/2022/10/attack_titan_logo.png'
		    ],
		    'sword-art-online'   => [
			    'https://www.banprestolucca2022.com/wp-content/uploads/2022/10/SWORD_ART_ONLINE.png'
		    ],
		    'shaman-king'        => [
			    'https://www.banprestolucca2022.com/wp-content/uploads/2022/10/shaman_king_logo.png'
		    ],
		    'blue-lock'          => [ '' ],
		    'digimon'            => [ '' ],
		    'jojo'               => [ '' ],
		    're-zero'            => [ '' ],
		    'saint-seya'         => [ '' ],
		    'spy-x-family'       => [ '' ]
	    ];
		
		
		add_filter( 'bp-cached', function ( $res ) use ( $logos ) {
			foreach ( $logos as $array ) {
				if ( ! empty( $array ) ) {
					foreach ( $array as $item ) {
						$res[] = $item;
					}
				}
			}
			
			return $res;
		} );
		
        return apply_filters('bp_logo', $arr, $logos);
    }

    //Two coloums in Divi's child tempolate
    add_filter('loop_shop_columns', function($cols){
        return 2;
    }, 999 );

    //remove pagination in Woocommerce
    if (!is_admin()) {
        add_filter( 'loop_shop_per_page', function ( $n ) {	return -1;}, 20 );
    }

    //change the default product ordering
    add_filter('woocommerce_get_catalog_ordering_args', 'catalog_ordering_args');

    function catalog_ordering_args($args) {
	    $args['orderby'] = 'date';
	    $args['order'] = 'ASC';
	    $object = get_queried_object();
	    if ( is_object( $object ) && isset( $object->term_taxonomy_id ) ) {
		    if ( $object->slug == 'dragon-ball' ) {
			    $args['orderby'] = 'menu_order';
		    }
	    }
        
        return $args;
    }


	add_action( 'init', [ $themeExtra, 'init_hooks' ] );
	add_action( 'wp', [ $themeExtra, 'late_hooks' ] );
	
	add_filter( 'theme_styles', function ( $styles ) {
		$styles['acme-scss'] = '/css/main.css';
		//$styles['acme-slick'] = '/css/slick.css';
        
		return $styles;
	}, 10, 1 );
	
	
	add_filter( 'acme_remove_styles', function ( $handles ) {
		$handles[] = 'wp-block-library';
		$handles[] = 'wc-blocks-vendors-style';
		$handles[] = 'wc-blocks-style';
		
		return $handles;
	} );


	add_filter( 'acme_custom_scripts', function ( $args, $environment ) {
		/*$args['acme-slick'] = [
			'src' => '/js/slick.min.js',
			'dep' => [ 'jquery' ]
		];*/
		$args['acme-main'] = [
			'src' => '/js/main.js',
			'dep' => [ 'jquery'/*, 'acme-slick'*/ ]
		];
		/*$args['service-worker'] = [
			'src' => home_url('/sw.js'),
			'dep' => []
		];*/

		return $args;
	}, 10, 2 );
	
	
	add_action( 'wp_print_styles', function () {
		//wp_enqueue_style( 'google-fonts', 'https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,400;0,500;
		//0,700;1,400;1,500;1,700&display=swap' );
	} );


	add_action( 'wp_head', [ $theme, 'wp_head' ], 1 );
	add_action( 'wp_print_styles', [ $theme, 'wp_enqueue_styles' ] );
	add_action( 'wp_enqueue_scripts', [ $theme, 'wp_enqueue_scripts' ] );
	add_action( 'after_setup_theme', [ $theme, 'after_setup_theme' ] );
	add_action( 'get_footer', [ $themeExtra, 'create_cache' ], 90 );
	
	add_filter( 'bp-cached', function ( $ar ) {
		$handle = opendir( dirname( __FILE__ ) );
		$basename = 'https://www.banprestolucca2022.com/wp-content/themes/Divi-banpresto/fonts/';
		while (false !== ($entry = readdir($handle))) {
			if ( strpos( $entry, 'woff2' ) ) {
				$ar[] = $basename . $entry;
			}
		}
		
		return $ar;
	}, 1 );
	
	
	//Remove JQuery migrate
	
	add_action( 'wp_default_scripts', 'remove_jquery_migrate' );
	function remove_jquery_migrate( $scripts ) {
		if ( ! is_admin() && isset( $scripts->registered['jquery'] ) ) {
			$script = $scripts->registered['jquery'];
			if ( $script->deps ) {
// Check whether the script has any dependencies
				
				$script->deps = array_diff( $script->deps, array( 'jquery-migrate' ) );
			}
		}
	}
	
	
	/** NOTES
	 *
	 */
/*
 // Verifichiamo se il browser con cui l’utente sta navigando è
        //ha già attivo un Service Worker
        if ("serviceWorker" in navigator) {
            if (navigator.serviceWorker.controller) {
                console.log("Service Worker attivo trovato. Nessuna necessità di registrarne uno.");
            } else {
                // Registriamo il Service Worker se non attivo
                navigator.serviceWorker.register("/sw.js?ver=%1$s", {
                    scope: "./"
                })
                    .then(function (reg) {
                        console.log("Un nuovo Service Worker registrato per lo scope: " + reg.scope);
                    });
            }
        }
 * */