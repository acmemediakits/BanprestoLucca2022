<?php
	
	class AcmeThemeExtensions {
		public $textdomain;
        private $json_cache;
        private $sw_cache;

		public function __construct($textdomain, $theme_directory, $the_directory_uri) {
			$this->textdomain = $textdomain;
            $this->theme_directory = $theme_directory;
            $this->theme_directory = sprintf( '%s%s', $this->theme_directory, '/' );
            $this->theme_directory_uri = $the_directory_uri;
            $this->theme_directory_uri = sprintf( '%s%s', $this->theme_directory_uri, '/' );
			$this->json_cache = sprintf( '%s/pwa.json', ABSPATH );
            $this->sw_cache = $this->theme_directory_uri . 'js/sw.js';

		}
		
		public function product_vars () {
			return apply_filters( 'acme_bp_product_vars', [] );
		}
		
		public function cat_vars () {
			$vars = [];
			return apply_filters( 'acme_bp_cat_vars', $vars );
		}
		
		public function init_hooks () {
			//Added body class
			add_filter( 'body_class', [ $this, 'body_classes' ] );
			//Products images
			remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
			add_action( 'woocommerce_before_single_product_summary', [$this, 'woocommerce_show_product_images'], 20 );
			
			//Add catalog number after product name
			add_action( 'woocommerce_single_product_summary', function () {
				global $product;
				if (is_object( $product ) ) {
					printf('<h2 class="catalog_number"><span>N&deg; %s</span></h2>', $product->get_attribute('catalog_number'));
				}
			}, 6 );
			
			//Adjust the WooCommerce body classes to remove sidebar
			function bp_body_classes($classes)
			{
				if (function_exists('is_woocommerce') && is_woocommerce()) {
					$remove_classes = array('et_right_sidebar', 'et_left_sidebar', 'et_includes_sidebar');
					foreach ($classes as $key => $value) {
						if (in_array($value, $remove_classes)) unset($classes[$key]);
					}
					$classes[] = 'et_full_width_page';
				}
				return $classes;
			}
			
			add_filter('body_class', 'bp_body_classes', 20);
			
			//Function getCopy
			add_action('wp_footer', function  (){
				$slug='';
				
				switch (true) {
					case is_shop():
						return;
					case is_product():
						$pv = $this->product_vars();
						$product = $pv['product'];
						$catalog_number = $product->get_attribute('catalog_number');
						$terms = get_the_terms($pv['id'], 'product_cat');
						foreach ($terms as $term) {
							$slug = $term->slug;
						}
						
						$product_copies = bp_special_copies();
						
						add_filter( 'bp_copy', function ( $str ) use ( $catalog_number, $product_copies ) {
							
							return $product_copies[ $catalog_number ] ?? $str;
						}, 20 );
						
						break;
					case is_archive():
						$slug = get_queried_object()->slug;
						break;
				}
				
				add_filter('bp_copy', function ($str, $copy) use ($slug) {
					$str = $copy[$slug];
					
					return $str;
				}, 10, 2);
				
				$str_copy = bp_copyrights();
				if ($str_copy !== ''){
					printf('<div class="bpcopyrights">&copy;%s</div>', $str_copy);}
			});
			
			// Tabs, upsells and related products
			remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
			remove_action( 'woocommerce_product_additional_information', 'wc_display_product_attributes', 10 );
			remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
			remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
			
			//Remove Sorting
			remove_action( 'woocommerce_after_shop_loop', 'woocommerce_catalog_ordering', 30 );
			remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
			
			// Product price
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
			add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 30 );
			
			// Cart
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
			
			// Meta
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
			
			//Category page
			remove_action( 'woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10 );
			remove_action( 'woocommerce_archive_description', 'woocommerce_product_archive_description', 10 );
			
			//remove Breadcrumb & page name
			remove_action( 'storefront_content_top', 'woocommerce_breadcrumb', 10);
			remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
			remove_action( 'woocommerce_before_shop_loop' , 'woocommerce_result_count', 20 );
			remove_action( 'woocommerce_after_shop_loop' , 'woocommerce_result_count', 20 );
			add_action( 'woocommerce_before_main_content', [$this, 'woocommerce_showShopHeader'], 35 );
			add_filter('woocommerce_show_page_title', '__return_false');
			
			/* Loop products */
			add_action('woocommerce_shop_loop', function () {
				add_filter( 'post_class', [$this,'productColor'], 20, 1 );
			});
			
			/* Change loop foto size */
			remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
			remove_action('woocommerce_before_subcategory_title', 'woocommerce_subcategory_thumbnail', 10);
			add_action('woocommerce_before_shop_loop_item_title',[$this, 'woocommerce_show_catalog_images'], 10);
			add_action('woocommerce_before_subcategory_title',[$this, 'woocommerce_show_catalog_images'], 10);
			
			//trim category name
			remove_action( 'woocommerce_shop_loop_subcategory_title', 'woocommerce_template_loop_category_title', 10 );
			add_action( 'woocommerce_shop_loop_subcategory_title', [
				$this,
				'woocommerce_template_loop_category_title'
			],
				10 );
			
			
			//wrap title
			add_action( 'woocommerce_shop_loop_subcategory_title', function () {
				print '<div class="wrap_cat">';
			}, 9 );
			add_action( 'woocommerce_shop_loop_subcategory_title', function () {
				print '</div>';
			}, 11 );
			
			
			//Remove products count
			add_filter( 'woocommerce_subcategory_count_html', '__return_false' );
			
			add_action( 'woocommerce_after_shop_loop', function (){
				
				$slug = get_queried_object()->slug;
				
				add_filter('bp_logo', function ($arr, $logos) use ($slug) {
					
					$arr = $logos[$slug];
					return $arr;
				}, 10, 2);
				$array_logos=bp_logos();
				$li = [];
				foreach ($array_logos as $src) {
					$li[] = sprintf('<li><img src="%s" title="%s" /></li>',
						$src,
						$slug
					);
				}
				printf('<div class="logos-container"><ul>%s</ul></div>', implode("\n", $li));
			}, 25 );
			
		}
		
		public function late_hooks() {
			
			switch ( true ) {
				case is_front_page():
					add_action('woocommerce_before_subcategory', [$this,  'add_clip_path'] );
					break;
				default:
					add_action('woocommerce_before_shop_loop_item',[$this,  'add_clip_path'], 11);
			}
			
			add_action('woocommerce_shop_loop_item_title', function () {
				echo '</div><div class="title-container">';
			}, 9);
			add_action('woocommerce_after_shop_loop_item', function () {
				echo '</div>';
			}, 4);
			
			/*Category color filter */
			add_action('acme_bp_filter_cat', function ($cat){
				add_filter('product_cat_class', function ($classes) use ($cat) {
					$newClasses = [];
					$cat_vars = $this->cat_vars();
					foreach ( $classes as $class ) {
						$newClasses[] = str_starts_with( $class, 'color-' ) ? $cat_vars['colors'][ $cat->slug ] : $class;
					}
					if ( ! in_array( $cat_vars['colors'][ $cat->slug ], $newClasses ) ) {
						$newClasses[] = $cat_vars['colors'][$cat->slug];
					}
					return $newClasses;
				});
			});
			
			
			add_action( 'acme_bp_tagname', function ( $productID ) {
				$product = wc_get_product( $productID );
				$tags = $product->get_tag_ids();
				if ( !empty( $tags ) && is_array( $tags ) ) {
					$term = get_term( $tags[0], 'product_tag' );
					echo $term->name;
				}
			}
			);
			add_action( 'acme_bp_featured_image', function ( $productID ) {
				$product = wc_get_product( $productID );
				echo $product->get_image( 'full' );
			} );
		}
		
		
		public function add_clip_path( $category = '' ){
			add_filter( 'the_title', function ( $title ){
				$title = trim( $title );
				
				return $title;
			});
			print( '<div class="bgr"></div>' );
			global $product;
			if ( is_object( $product ) ) {
				$catalog_number = $product->get_attribute('catalog_number');
				$terms = get_the_terms($product->ID, 'product_cat');
				if ( is_array( $terms ) ) {
					foreach ($terms as $term) {
						$product_cat = $term->name;
					}
					if ( empty( $category ) ) {
						printf( '<div class="image-container"><div class="wrap_cat"><span class="category">N&deg; %s</span></div>',
							$catalog_number ); //use $product_cat for category name
					}
					
				}
			}
		}
		
		
		public function woocommerce_template_loop_category_title( $category ) {
			printf( '<h2 class="woocommerce-loop-category__title">%s</h2>', esc_html( $category->name ) );
		}
		
		
		public function woocommerce_show_product_images () {
			get_template_part( 'template_parts/single-image.html' );
		}
		
		public function woocommerce_show_catalog_images( $category = null ) {
			if ( ! empty( $category ) ) {
				$thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
				echo wp_get_attachment_image( $thumbnail_id, [ 600, 600 ] );
				
			}
			global $product;
			if ( is_object( $product ) ) {
				echo $product->get_image( [ 600, 600 ] );
			}
			
		}
		
		public function woocommerce_showShopHeader () {
			switch (true) {
				case is_shop():
					break;
				case is_product():
					//HEADER INNER PRODUCT
					$pv = $this->product_vars();
					$terms = get_the_terms($pv['id'], 'product_cat');
					foreach ($terms as $term) {
						$product_cat = $term->name;
						break;
					}
					$isMain = empty( $term->parent ) || false;
					$idTerm = $isMain ? $term->term_id : $term->parent;
					
					printf('<div class="bp-store-header">
									<div class="wrapper">
									<a class="arrow-back" href="%1$s"></a>
									<a class="category" href="%1$s">%2$s</a>
									</div>
									</div>',
						get_category_link($idTerm),
						$product_cat
					);
					break;
				case is_archive():
					//HEADER INNER PRODUCT CATEGORY
					$cat = get_queried_object();
					
					printf('<div class="bp-store-header">
									<div class="wrapper">
									<a class="arrow-back" href="%1$s"></a>
									<a class="category" href="%1$s">%2$s</a>
									</div>
									</div>',
						home_url('#catalogo'),
						$cat->name
					);
					
					break;
			}
		}
		
		
		public function body_classes ($classes) {
			switch ( true ) {
				case is_product():
					$product = wc_get_product( get_queried_object_id() );
					if ( is_object( $product ) ) {
						$pas = $product->get_attributes();
						add_filter('acme_bp_product_vars', function ($vars) use ($pas, $product) {
							$vars['attributes'] = $pas;
							$vars['id'] = $product->get_id();
							$vars['product'] = $product;
							
							return $vars;
						});
						foreach ( $pas as $pa => $ar ) {
							if ( 'pa_color' == $pa ) {
								$term = get_term( $ar['options'][0], $pa );
								$classes[] = $term->slug;
								
								return $classes;
							}
						}
					}
					//do_action( 'qm/debug', $product->get_attributes() );
					break;
			}
			return $classes;
		}
		public function productColor($classes){
			global $product;
			$pas = $product->get_attributes();
			
			foreach ( $pas as $pa => $ar ) {
				if ( 'pa_color' == $pa ) {
					$term = get_term( $ar['options'][0], $pa );
					$classes[] = $this->color($term->slug);
					break;
				}
			}
			return $classes;
		}
		
		public function color($color){
			return sprintf('color-%s', $color);
		}

        public function pwa_cache()
        {
            
            //Get all products
            $args = [
                'post_type' => 'product',
                'post_status' => 'publish',
                'posts_per_page' => -1
            ];

            $product_query = new WP_Query( $args );

            while ( $product_query->have_posts() ) {
                $product_query->the_post();
                $id = get_the_ID();
                $this->pages[]=get_the_permalink();
                $this->pages[] = get_the_post_thumbnail_url($id, [600, 600]);
                $this->pages[] = get_the_post_thumbnail_url($id, 'full');
            }

            wp_reset_postdata();

            //Get all categories
            $args = [
                'taxonomy' => 'product_cat',
                'hide_empty' => true];

            $terms = get_terms($args );

            foreach ($terms as $term){
                $this->pages[] = get_category_link($term);
            }

            wp_reset_postdata();

            add_filter('bp-cached', function ($ar) {
                foreach ($this->pages as $page) {
                    $ar[] = $page;
                }
               // do_action('qm/debug', $ar);
                return $ar;
            });
			$this->pwa_scripts();

        }

        public function pwa_scripts(){
            /*if (file_exists($this->json_cache)){
                return;
            }*/
            // Get all loaded Styles (CSS) and Scripts (js)
            $this->script = [];

            global $wp_styles, $wp_scripts;

            foreach( $wp_styles->registered as $obj ) {
                if (!is_bool($obj->src) || !empty($obj->src)) {
                    $this->script[] = str_starts_with($obj->src, 'http') ? $obj->src : home_url($obj->src);
                }
            }

            foreach( $wp_scripts->registered as $obj ) {
                if (!is_bool($obj->src) || !empty($obj->src)) {
                    $this->script[] = str_starts_with($obj->src, 'http') ? $obj->src : home_url($obj->src);
                }
            }

            add_filter('bp-cached', function ($ar) {
                foreach ($this->script as $s) {
                    $ar[] = $s;
                }
                /*do_action('qm/debug', $this->theme_directory_uri);
                do_action('qm/debug', $ar);*/

                return $ar;
            });
        }

        public function create_cache()
        {
            $check = true;
	
            if (!file_exists(ABSPATH .'/sw.js')) {
	
	            $this->pwa_cache();
	            $resources = array_unique( apply_filters( 'bp-cached', [] ) );
	            $exclusion = [
					'stats.wp.com',
					'maps.googleapis.com',
					'woocommerce-blocks',
					'filled-cart-block.js',
					'cart-order-summary-subtotal-block.js',
					'cart-order-summary-taxes-block.js'
	            ];
	            foreach ( $resources as $k => $resource ) {
		            if ( ! str_starts_with( $resource, 'https://www.banprestolucca2022.com/' ) ) {
			            unset( $resources[ $k ] );
		            }
		            foreach ( $exclusion as $needle ) {
			            if ( strpos( $resource, $needle ) ) {
				            unset( $resources[ $k ] );
			            }
		            }
				}
                $check = false;
                $cache = [];
	            $handle = fopen( 'sw.js', 'w' );
	
	            $fcontent = sprintf( '
    const cacheName = "pwabuilder-precache";
    const precacheFiles = ["%s"];

    self.addEventListener("install", (e) => {
        //self.skipWaiting();
        e.waitUntil( (async() => {
            const cache = await caches.open(cacheName);
            await cache.addAll(precacheFiles);
        })());
    });

  self.addEventListener("activate", (e) => {
        e.waitUntil(caches.keys().then((keyList) => {
	    return Promise.all(keyList.map((key) => {
	      if (key === cacheName) { return; }
	      return caches.delete(key);
	    }));
	  }));
	});

   self.addEventListener("fetch", (e) => {
      if (e.request.method !== "GET") return;
	  e.respondWith((async () => {
	    const r = await caches.match(e.request);
	    console.log(`[Service Worker] Fetching resource: ${e.request.url}`);
	    if (r) { return r; }
	    const response = await fetch(e.request);
	    const cache = await caches.open(cacheName);
	    console.log(`[Service Worker] Caching new resource: ${e.request.url}`);
	    cache.put(e.request, response.clone());
	    return response;
	  })());
	});
',
		            implode( '","', $resources )
	            );
	                
	                
	                //json_encode(apply_filters('bp-cached', $cache));

                if (!empty(fwrite($handle, $fcontent))) {
                    $check = true;
                }
                fclose($handle);
            }
            if ($check) {

                
                //enqueue sw.js
                //do_action('qm/debug', $this->sw_cache);
                //wp_enqueue_script('sw-js', $this->sw_cache, [], '1.0', true);
            }

        }

    }

