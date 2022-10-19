<?php
	require_once dirname( __FILE__ ) . '/includes/class-acme-theme.php';
	require_once dirname( __FILE__ ) . '/includes/class-acme-theme-extensions.php';
     // Include mobile detect class
    include_once dirname( __FILE__ ) . '/includes/class-mobile-detect.php';
    $device = new Mobile_Detect();

    add_action('wp', function () use ($device) {
        $curPage = get_queried_object();
       // do_action('qm/debug', $curPage);
        if (is_object($curPage)) {
            $pg = 'banpresto-lucca-comics-2022';
            if ( false && $pg != $curPage->post_name) {
                if (!$device->isMobile()&&!$device->isTablet()){
                    wp_redirect(home_url($pg));
                }
            }
        }
    });

	define( 'acme_td', 'acme_td' );
	
	$theme = new AcmeTheme('stage');
	$themeExtra = new AcmeThemeExtensions( acme_td );

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
			50  => 'BIRD STUDIO/SHUEISHA ©2022 DRAGON BALL SUPER Film Partners',
			//125 => 'TiteKubo/Shueisha, TV TOKYO, dentsu, Pierrot',
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
			    'https://www.banprestolucca2022.com/wp-content/uploads/2022/10/toei_logo-W.png'
		    ],
		    'one-piece-film-red' => [
			    'https://www.banprestolucca2022.com/wp-content/uploads/2022/10/one_piece_film_red_logo.png',
			    'https://www.banprestolucca2022.com/wp-content/uploads/2022/10/toei_logo-W.png'
		    ],
		    'naruto'             => [
			    'https://www.banprestolucca2022.com/wp-content/uploads/2022/10/Naruto_Shippuden_logo.png',
		    ],
		    'dragon-ball'        => [
			    'https://www.banprestolucca2022.com/wp-content/uploads/2022/10/dragonball_Z_logo.png',
			    'https://www.banprestolucca2022.com/wp-content/uploads/2022/10/Dragonball_GT_logo.png',
			    'https://www.banprestolucca2022.com/wp-content/uploads/2022/10/dragonball_super_logo.png',
			    'https://www.banprestolucca2022.com/wp-content/uploads/2022/10/toei_logo-W.png'
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
		$styles['acme-slick'] = '/css/slick.css';
        
		return $styles;
	}, 10, 1 );
	
	
	add_filter( 'acme_remove_styles', function ( $handles ) {
		$handles[] = 'wp-block-library';
		$handles[] = 'wc-blocks-vendors-style';
		$handles[] = 'wc-blocks-style';
		
		return $handles;
	} );
	
	add_filter( 'acme_custom_scripts', function ( $args, $environment ) {
		$args['acme-slick'] = [
			'src' => '/js/slick.min.js',
			'dep' => [ 'jquery' ]
		];
		$args['acme-main'] = [
			'src' => '/js/main.js',
			'dep' => [ 'jquery', 'acme-slick' ]
		];

		return $args;
	}, 10, 2 );
	
	
	add_action( 'wp_print_styles', function () {
		wp_enqueue_style( 'google-fonts', 'https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,400;0,500;0,700;1,400;1,500;1,700&display=swap' );
	} );
	
	add_action( 'wp_head', [ $theme, 'wp_head' ], 1 );
	add_action( 'wp_print_styles', [ $theme, 'wp_enqueue_styles' ] );
	add_action( 'wp_enqueue_scripts', [ $theme, 'wp_enqueue_scripts' ] );
	add_action( 'after_setup_theme', [ $theme, 'after_setup_theme' ] );
	