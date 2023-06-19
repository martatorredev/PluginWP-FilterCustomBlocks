<?php 

function function_render_post_in($atts)
{
	$cat_ide = get_queried_object_id();
	$cantidad = isset($atts['numbers']) ? $atts['numbers'] : 4;

    $carts     = isset($atts['selectedOption']) ? $atts['selectedOption'] : false;

    $number = 100;
	
	query_posts( array(
		'posts_per_page' => $number,
		'post_type'      => 'product',
		'meta_key'       => 'total_sales',
		'orderby'        => 'meta_value_num',
		'order'          => 'DESC',
		'tax_query' => array(
			array(
				'taxonomy'  => 'product_cat',
				'field'     => 'term_id',
				'terms'     => $cat_ide,
			)
		),
	));

    if($carts){
        $datacat = json_decode($carts, true);

        foreach ($datacat as $key => $dc) {
            $atrg[] = $dc['id'];
        }
    }


	/* GET List Categories */
    if($atrg != NULL) {
        $cats =  get_terms( array(
            'taxonomy'   => 'product_cat',
            'hide_empty' => false,
            'number'     => 200,
            'include' => $atrg,
        ) );

    } else {
        $cats =  get_terms( array(
            'taxonomy'   => 'product_cat',
            'hide_empty' => true,
            'number'     => 200,

        ) );
    }

	$cuerpo = '';
	
    $cuerpo .= '<div class="cnt_gr_categories">';
	$cuerpo .= '<div class="gr_categories">';
	
		if (have_posts()) {

			$cuerpo .= '<a data-id="all" data-cat="'.$cat_ide.'" class="item-cat-pfolio on" href="javascript:void(0)">Todos</a>';

			if ( ! empty( $cats ) && ! is_wp_error( $cats ) ){
				foreach( $cats as $cat ){
					$id   = $cat->term_id;
					$name = $cat->name;
					$url  = get_term_link( $cat );
					$cuerpo .= '<a data="'.$number.'" data-cat="'.$cat_ide.'" data-id="'.$id.'" class="item-cat-pfolio" href="javascript:void(0)">'.$name.'</a>';

				}
			}
	
		} else {
			$cuerpo .= '<p>No hay productos en esta sección</p>';
		
		}
	

	$cuerpo .= '</div>';

	$cuerpo .= '<div class="gr_grid">';
		
		if (have_posts()) :
			while (have_posts()) : the_post();
                $post_id = get_the_ID();
                $product = wc_get_product( $post_id );
	
	
				
	
				if($product->is_type('variable')){
					
					$min_price  =  $product->get_variation_sale_price( 'min', true );
        			$max_price  =  $product->get_variation_regular_price( 'max', true );
					
					$pri = $min_price . ' - ' . $max_price;
					
					$url_price = get_the_permalink($post_id);
					$class_price = '';
					
				} else {
					$price_regular = $product->get_regular_price();
                	$price   = $product->get_price();
	
					$pri = wc_price( wc_get_price_including_tax( $product ) );
					$url_price = '?add-to-cart='.$product->get_id();
					$class_price = 'add_to_cart_button ajax_add_to_cart';
				}
	
	
                $average = $product->get_average_rating();
	
	
				$cat_ar = [];
				$cat = get_the_terms( $post_id, 'product_cat' );
				if ( $cat && ! is_wp_error( $cat ) ) :
					foreach ( $cat as $ca ) {
						$url   = get_term_link( $ca );
						$name  = $ca->name;
						$cat_ar[] = '<a href="'.$url.'">' . $name . '</a>';
					}
				endif;                   
       
	
	
				$cuerpo .= '<div class="item-prod">
                                <figure style="position: relative;">
                                    <a href="'.get_permalink().'">'.get_the_post_thumbnail(get_the_ID(), 'large').'</a>';
									if ( $product->is_on_sale() )  {  
					$cuerpo .= '  <div class="wc-block-grid__product-onsale">
										<span aria-hidden="true">Oferta</span>
										<span class="screen-reader-text">Producto rebajado</span>
									</div>';
									}
										
                       $cuerpo.='</figure>
                                <h3><a href="'.get_the_permalink($post_id).'">'.get_the_title($post_id).'</a></h3>
					
                                <div class="item-cats">'.implode(' / ', $cat_ar).'</div>';
							
							if($average){
                           $cuerpo .= '     <div class="item-product">
                                    <div class="star-rating" title=""><span style="width:'.( ( $average / 5 ) * 100 ) . '%"></span></div>
                                </div>'; }
								
			$cuerpo.=	'<div class="wc-block-grid__product-price item-price price ">';
			if ( $product->is_on_sale() )  { 
			$cuerpo.=   '<del class="ml-mk" aria-hidden="true"><span class="woocommerce-Price-amount amount">'.wc_get_price_including_tax( $product, array('price' => $price_regular ) ). get_woocommerce_currency_symbol().'</span></del>';	}
		    $cuerpo.=   '<ins><span class="woocommerce-Price-amount amount">'.$pri.'</span></ins>
						</div>';

                      $cuerpo.=	'
                                <div class="wp-block-button wc-block-grid__product-add-to-cart">
                                    <a data-quantity="1" class="aic wp-block-button__link wp-element-button '.$class_price.'" data-product_id="'. $product->get_id() .'" href="'.$url_price.'">COMPRAR</a>
                                </div>

                            </div>';
			endwhile;
		endif;
	$cuerpo .= '</div></div>';

	return  $cuerpo;
}


class FILTER_Gutenberg
{
    public function register_block()
    {
        /*Prevent Gutenberg*/
        if (!function_exists('register_block_type')) 
            return;

        //Register script
        wp_register_script(
            'filter-gutenberg-script',
            FILTER_DIR_URI . 'build/index.js',
            array('wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor'),
            filemtime(FILTER_DIR_PATH . 'build/index.js')
        );
        //Register style editor
        wp_register_style(
            'filter-gutenberg-editor-styles',
            FILTER_DIR_URI . 'build/editor.css',
            array('wp-edit-blocks'),
            filemtime(FILTER_DIR_PATH . 'build/editor.css')
        );
        //Register style front-end
        wp_register_style(
            'filter-gutenberg-frontend-styles',
            FILTER_DIR_URI . 'build/style.css',
            array(),
            filemtime(FILTER_DIR_PATH . 'build/style.css')
        );

        /*Register bloque dinamico*/
        register_block_type('filter/products', array(
            'editor_script'   => 'filter-gutenberg-script',
            'editor_style'    => 'filter-gutenberg-editor-styles',
            'style'           => 'filter-gutenberg-frontend-styles',
            'render_callback' => 'function_render_post_in',
        ));
    }
    public function create_category_gutenberg($categories, $post)
    {
        return array_merge(
            $categories,
            array(
                array(
                    'slug'  => 'ondesarrollo',
					'title' => 'Bloques Personalizados',
					'icon'  => 'image-filter'
                )
            )
        );
    }
}