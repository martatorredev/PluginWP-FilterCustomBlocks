<?php
class FILTER_Ajax_Public {
    public function loadpfolio() {
        if( isset( $_POST[ 'action' ] ) ) {
   
            $ide = $_POST['ide'];
			$catCurrent = $_POST['catCurrent'];
            $cat_ide = get_queried_object_id();


           
            $args = array(
                'post_type'      => 'product',
                'posts_per_page' => 40,
                'post_status'    => 'publish',
                'meta_key'       => 'total_sales',
                'orderby'        => 'meta_value_num',
                'order'          => 'DESC',
            );

            if($ide != 'all') {
                    $args['tax_query'][0]['taxonomy'] = 'product_cat';
                    $args['tax_query'][0]['terms'] = $ide;
            } else {

                // all product_cat all terms of $catCurrent
                $args['tax_query'][0]['taxonomy'] = 'product_cat';
                $args['tax_query'][0]['terms'] = $catCurrent;
				
			}

            $the_query = new WP_Query( $args );
            if ( $the_query->have_posts() ) :
                while ( $the_query->have_posts() ) : $the_query->the_post();
                    $post_id = get_the_ID();
                    $product = wc_get_product( $post_id );
                    $average = $product->get_average_rating();
                    $price   = $product->get_price();
			
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
			
					$cat_ar = [];
					$cat = get_the_terms( $post_id, 'product_cat' );
					if ( $cat && ! is_wp_error( $cat ) ) :
						foreach ( $cat as $ca ) {
							$url   = get_term_link( $ca );
							$name  = $ca->name;
							$cat_ar[] = '<a href="'.$url.'">' . $name . '</a>';
						}
					endif; 
			
		


?>
                    <div class="item-prod">
                        <figure style="position: relative;">
                            <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( get_the_ID(), 'large' )?></a>
							<?php if ( $product->is_on_sale() )  {  ?>
							<div class="wc-block-grid__product-onsale">
										<span aria-hidden="true">Oferta</span>
										<span class="screen-reader-text">Producto rebajado</span>
									</div>
							<?php } ?>
                        </figure>
                        <h3><a href="<?php echo get_the_permalink($post_id); ?>"><?php echo get_the_title($post_id); ?></a></h3>
                        <div class="item-cats"><?php echo implode(', ', $cat_ar); ?></div>
						<?php if($average) { ?>
                        <div class="item-product">
                            <div class="star-rating" title="">
                                <span style="width:<?php echo ( ( $average / 5 ) * 100 );  ?>%"></span>
                            </div>
                        </div><?php } ?>
                        <div class="wc-block-grid__product-price item-price price ">
							
                            <?php if ( $product->is_on_sale() )  { 
								echo '<del class="ml-mk" aria-hidden="true"><span class="woocommerce-Price-amount amount">'.wc_get_price_including_tax( $product, array('price' => $price_regular ) ). get_woocommerce_currency_symbol().'</span></del>';
							}
							
							echo '<ins><span class="woocommerce-Price-amount amount">'.$pri.'</span></ins>'; ?>
                        </div>
                        <?php echo '<div class="wp-block-button wc-block-grid__product-add-to-cart">
                                    <a data-quantity="1" class="aic wp-block-button__link wp-element-button '.$class_price.'" data-product_id="'. $product->get_id() .'" href="'.$url_price.'">COMPRAR</a>
                                </div>'; ?>
                    </div>
                <?php endwhile;
            endif; wp_reset_query();
            wp_die();
        }
    }
}