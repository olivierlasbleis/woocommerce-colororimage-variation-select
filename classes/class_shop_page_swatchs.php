<?php
class wcva_shop_page_swatches {

    public function __construct() {

	    add_action('init', array(&$this, 'wcva_shop_page_init'));
	    add_action( 'wp_enqueue_scripts', array(&$this,'wcva_register_shop_scripts' ));
	}
	
	public function wcva_shop_page_init() {
		
		$swatch_location  = get_option('woocommerce_shop_swatches_display',"01");
		
		switch($swatch_location) {
			
			case "01":
			  add_action('woocommerce_after_shop_loop_item_title', array(&$this, 'wcva_change_shop_attribute_swatches'));
			break;
			  
			case "02":
			  add_action('woocommerce_before_shop_loop_item_title', array(&$this, 'wcva_change_shop_attribute_swatches'));
			break;
			
			case "03":
			  add_action('woocommerce_after_shop_loop_item', array(&$this, 'wcva_change_shop_attribute_swatches'));
			break;
			 
			default:
			  add_action('woocommerce_after_shop_loop_item_title', array(&$this, 'wcva_change_shop_attribute_swatches'));
			
		}
		
	   
	}
    
	public function wcva_register_shop_scripts() {
		
        
	    require_once 'wcva_mobile_detect.php';
	    
		$mobile_click                     = get_option('woocommerce_wcva_disable_mobile_hover',0);
	    $load_assets                      = wcva_load_shop_page_assets();
	    $detect                           = new WCVA_Mobile_Detect;
        $woocommerce_enable_shop_slider   = get_option('woocommerce_enable_shop_slider',"no");
        $woocommerce_shop_slider_number   = get_option('woocommerce_shop_slider_number',4);
      
    
	   
        if (isset($load_assets) && ($load_assets == "yes")) {
		   
		    wp_enqueue_script('jquery');

		    if (isset($woocommerce_enable_shop_slider) && ($woocommerce_enable_shop_slider == "yes")) {
		    	wp_enqueue_script( 'wcva-slick', ''.wcva_PLUGIN_URL.'js/slick.js' , array('jquery'));
		    }

		    
		    
		    $wcvi_locals = array(
			  'left_icon'                      => ''.wcva_PLUGIN_URL.'images/left-arrow.png',
			  'right_icon'                     => ''.wcva_PLUGIN_URL.'images/right-arrow.png',
			  'enable_slider'                  => $woocommerce_enable_shop_slider,
			  'slider_no'                      => $woocommerce_shop_slider_number

		    );
  
           
		    if (isset($mobile_click) && ($mobile_click == "yes") && ( $detect->isMobile() ) ) {
			  wp_enqueue_script( 'wcva-shop-frontend-mobile', ''.wcva_PLUGIN_URL.'js/shop-frontend-mobile.js');
			  wp_localize_script( 'wcva-shop-frontend-mobile', 'wcva_shop', $wcvi_locals );
		    } else {
			  wp_enqueue_script( 'wcva-shop-frontend', ''.wcva_PLUGIN_URL.'js/shop-frontend.js');
			  wp_localize_script( 'wcva-shop-frontend', 'wcva_shop', $wcvi_locals );
		    }
		   
		   
		    wp_enqueue_style( 'wcva-shop-frontend', ''.wcva_PLUGIN_URL.'css/shop-frontend.css');
		    
		    if (isset($woocommerce_enable_shop_slider) && ($woocommerce_enable_shop_slider == "yes")) {
		        wp_enqueue_style( 'wcva-slick', ''.wcva_PLUGIN_URL.'css/slick.css');
		    }
       
	    }

	}
	
	public function wcva_change_shop_attribute_swatches($product) {
	  global $product; 
	  
	  $product_type             =  $product->get_type();
	  $product_id               =  $product->get_id();
	  $shop_swatches            =  get_post_meta( $product_id, '_shop_swatches', true );
	  $shop_swatches_attribute  =  get_post_meta( $product_id, '_shop_swatches_attribute', true );
	  
	
	  $shop_swatches            =  apply_filters('wcva_default_shop_swatches_enable',$shop_swatches);
	  $shop_swatches_attribute  =  apply_filters('wcva_default_shop_swatches_attribute',$shop_swatches_attribute);  
	  $instock_array            =  array();


	  if (isset($shop_swatches_attribute)) {

	  	$attribute_slug           =  'attribute_'.$shop_swatches_attribute.'';
      
	  }

	  

	    if (($product_type == "variable") && isset($shop_swatches_attribute) && isset($product)) {

	  	  $variations           =  $product->get_available_variations();

          foreach ($variations as $vkey => $variation) {

	        if ($variation['is_in_stock'] == 1) {

	            if (isset($variation['attributes'][$attribute_slug])) {

                    if (!in_array($attribute_slug, $instock_array))  {
                        $instock_array[] = $variation['attributes'][$attribute_slug];
                    }
          
		            
                }
		    }
          }
        }


        
	
	  
	    $fullarray                =  get_post_meta( $product_id, '_coloredvariables', true );
	    $template                 =  '';
        $display_shape            =  'wcvasquare';
	    $newvaluearray            =  array();
	    $swatch_count             =  count(array_unique($instock_array));
	  
	    if (isset($shop_swatches) && ($shop_swatches == "yes")) {
		  
		    if (isset($shop_swatches_attribute) && ($shop_swatches != "")) {
		  
		        if ( taxonomy_exists( $shop_swatches_attribute ) ) {
		   
		            $terms = wc_get_product_terms( $product_id, $shop_swatches_attribute, array( 'fields' => 'all' ) );
		  
		            foreach ($terms as $term) {

		            	if (in_array($term->slug, $instock_array))  {
			 
			                if (isset($fullarray[$shop_swatches_attribute]['values']) && (!empty($fullarray[$shop_swatches_attribute]['values']))) {
					
					            foreach ($fullarray[$shop_swatches_attribute]['values'] as $key=>$value) {

				                    if ($key == $term->slug) {
					                    $newvaluearray[$shop_swatches_attribute]['values'][$key]            = $fullarray[$shop_swatches_attribute]['values'][$key];
					                    $newvaluearray[$shop_swatches_attribute]['values'][$key]['term_id'] = $term->term_id;
						                 $newvaluearray[$shop_swatches_attribute]['display_type']            = $fullarray[$shop_swatches_attribute]['display_type'];
				                    }
			                    }
				            }

				        }
				    }
	            }
		
		    }
	    
		}
	  
	  
	  
	    if (isset($fullarray[$shop_swatches_attribute]['displaytype']) && ($fullarray[$shop_swatches_attribute]['displaytype'] == 'round')) {
		    $display_shape            =  'wcvaround';
	    }
	  
	        
	    if (isset($fullarray[$shop_swatches_attribute]['values']) ) {
			$_values                  =  $fullarray[$shop_swatches_attribute]['values'];
		}


	      
	
	    if (($product_type == 'variable') && isset($shop_swatches) && ($shop_swatches == "yes") ) {
	     
		    if ((isset($newvaluearray)) && (!empty($newvaluearray))) {
			     
			        if (isset($shop_swatches_attribute) && ($newvaluearray[$shop_swatches_attribute]['display_type'] == "colororimage" || $newvaluearray[$shop_swatches_attribute]['display_type'] == "global")) {
		                
				        $template=$this->wcva_variable_swatches_template($newvaluearray[$shop_swatches_attribute]['values'],$shop_swatches_attribute,$product_id,$display_shape,$newvaluearray[$shop_swatches_attribute]['display_type'],$swatch_count);
	                } 
				
		    } else {

			        if (isset($fullarray[$shop_swatches_attribute])) {
			            if (isset($shop_swatches_attribute) && isset($fullarray[$shop_swatches_attribute]['display_type']) && ($fullarray[$shop_swatches_attribute]['display_type'] == "colororimage" || $fullarray[$shop_swatches_attribute]['display_type'] == "global")) {

			            	$swatch_count = count($_values);
		                    
				            $template=$this->wcva_variable_swatches_template($_values,$shop_swatches_attribute,$product_id,$display_shape,$fullarray[$shop_swatches_attribute]['display_type'],$swatch_count);
	            
				        } 
				    } else {
					   
					    if (isset($shop_swatches_attribute)) {
		                    $main_display_type = "global";
                            
				            $template=$this->wcva_variable_swatches_template_global($shop_swatches_attribute,$product_id,$main_display_type,$swatch_count);
	            
				        } 
					   
				    }
		    }
		 
		 
		 
	    }
	  
	  return $template;
	}



	 /**
	  * Shows text for variable products with swatches enabled
	  * @$values- attribute value array of swatch settings
	  * @name- attribute name
	  * $pid - product id to get product url
	  */
	public function wcva_variable_swatches_template($values,$name,$pid,$display_shape,$main_display_type,$swatch_count ) { 
	  
	        $imagewidth        = get_option('woocommerce_shop_swatch_width',"32");  
            $imageheight       = get_option('woocommerce_shop_swatch_height',"32");  
		    $global_activation = get_option('wcva_woocommerce_global_activation');
			$wcva_global       = get_option('wcva_global');
			$hover_image_size  = get_option('woocommerce_hover_imaga_size',"shop_catalog");  
			$direct_link       = get_option('woocommerce_shop_swatch_link', "no");  
			$product_url       = get_permalink( $pid );
			$mobile_click      = get_option('woocommerce_wcva_disable_mobile_hover',"no");
			$woocommerce_enable_shop_slider   = get_option('woocommerce_enable_shop_slider',"no");
            $woocommerce_shop_slider_number   = get_option('woocommerce_shop_slider_number',4);

			if (isset($woocommerce_enable_shop_slider) && ($woocommerce_enable_shop_slider == "yes") && ($swatch_count > $woocommerce_shop_slider_number)) {
                $sliderclass                  =  'slider wcva-multiple-items';
                $slideclass                   =  'multiple slide';
            } else {
            	$sliderclass                  =  '';
                $slideclass                   =  '';
            }
	   
	        require_once 'wcva_mobile_detect.php';
	   
	        $detect = new WCVA_Mobile_Detect;
			
			if (isset($mobile_click) && ($mobile_click == "yes") && ( $detect->isMobile() ) ) {
			    $load_direct_variation = "no";
			} else {
				$load_direct_variation = "yes";
			}
			
		
        ?>
	<div class="shopswatchinput <?php echo $sliderclass; ?>" swatch-count="<?php echo $swatch_count; ?>" start-slider-count="<?php echo $woocommerce_shop_slider_number; ?>" prod-img="">
	    <?php  
		
		$load_assets   = wcva_load_shop_page_assets();
      
    
	   
        if (isset($load_assets) && ($load_assets == "yes")) {
		
	        foreach ($values as $key=>$value) { 

            
			    $lower_name       =   strtolower( $name );
			    $clean_name       =   str_replace( 'pa_', '', $lower_name );
			    $lower_key        =   rawurldecode($key);
			    $direct_url       =  ''.$product_url.'?'.$clean_name.'='.$lower_key.'';
			
			    if ($main_display_type == "global") {
				
				    if (isset($global_activation) && $global_activation == "yes") {
				
				        if ($wcva_global[$name]['displaytype'] == "round") {
				 	        $display_shape =  'wcvaround';
				        }
		            }
				
			            $swatchtype       = get_term_meta( $value['term_id'], 'display_type', true );
				        $swatchcolor      = get_term_meta( $value['term_id'], 'color', true );
				        $attrtextblock    = get_term_meta( $value['term_id'], 'textblock', true );
				        $swatchimage      = absint( get_term_meta( $value['term_id'], 'thumbnail_id', true ) );
				        $hoverimage       = absint( get_term_meta( $value['term_id'], 'hoverimage', true ) );
			    
			    } else {
				        
						$swatchtype       = $value['type'];
				        $swatchcolor      = $value['color'];
				        $swatchimage      = $value['image'];
				        $hoverimage       = $value['hoverimage'];
				        $attrtextblock    = $value['textblock'];
			    }
			
			

                $swatchimageurl   =  apply_filters('wcva_swatch_image_url',wp_get_attachment_thumb_url($swatchimage),$swatchimage);
			    $hoverimage       =  wp_get_attachment_image_src($hoverimage,$hover_image_size);
                $hoverimageurl    =  apply_filters('wcva_hover_image_url',$hoverimage[0],$hoverimage[0]);
			    

                
			    
                
			    
			
			
			 
			    if (isset($swatchtype)) {
				    switch ($swatchtype) {
             	        case 'Color':
             		        ?>
                            <a <?php if ((isset($direct_link)) && ($direct_link == "yes") && ( $load_direct_variation == "yes" )) { ?> href="<?php echo $direct_url; ?>" <?php } ?> class="<?php echo $slideclass; ?> wcvaswatchinput" data-o-src="<?php if (isset($hoverimageurl)) { echo $hoverimageurl; } ?>" style="width:<?php echo $imagewidth; ?>px; height:<?php echo $imageheight; ?>px;">
                            <div class="wcvashopswatchlabel <?php echo $display_shape; ?>" style="background-color:<?php if (isset($swatchcolor)) { echo $swatchcolor; } else { echo '#ffffff'; } ?>; width:<?php echo $imagewidth; ?>px; float:left; height:<?php echo $imageheight; ?>px;"></div>
                            </a>
             		        <?php
             		    break;

             	        case 'Image':
             		        ?>
                            <a <?php if ((isset($direct_link)) && ($direct_link == "yes") && ( $load_direct_variation == "yes" )) { ?> href="<?php echo $direct_url; ?>" <?php } ?> class="<?php echo $slideclass; ?> wcvaswatchinput" data-o-src="<?php if (isset($hoverimageurl)) { echo $hoverimageurl; } ?>" >
                            <div class="wcvashopswatchlabel <?php echo $display_shape; ?>"  style="background-image:url(<?php if (isset($swatchimageurl)) { echo $swatchimageurl; } ?>); background-size: <?php echo $imagewidth-2; ?>px <?php echo $imageheight-2; ?>px; float:left; width:<?php echo $imagewidth; ?>px; height:<?php echo $imageheight; ?>px;"></div>
                            </a>
             		        <?php
             		    break;
				
				        case 'textblock':
             		        ?>
                            <a <?php if ((isset($direct_link)) && ($direct_link == "yes") && ( $load_direct_variation == "yes" )) { ?> href="<?php echo $direct_url; ?>" <?php } ?> class="<?php echo $slideclass; ?> wcvaswatchinput" data-o-src="<?php if (isset($hoverimageurl)) { echo $hoverimageurl; } ?>" style="width:<?php echo $imagewidth; ?>px; height:<?php echo $imageheight; ?>px;">
                            <div class="wcvashopswatchlabel wcva_shop_textblock <?php echo $display_shape; ?>" style="min-width:<?php echo $imagewidth; ?>px; "><?php  if (isset($attrtextblock)) { echo $attrtextblock; }   ?></div>
                            </a>
             		        <?php
             		    break;
             	
             
                    } 
			    }
			 
            
            }
		}		?>
	</div>
	     
	<?php 
	
	}
	
	
	
	/**
	  * Shows text for variable products with swatches enabled
	  * @$values- attribute value array of swatch settings
	  * @name- attribute name
	  * $pid - product id to get product url
	  */
	public function wcva_variable_swatches_template_global($shop_swatches_attribute,$pid,$main_display_type,$swatch_count ) { 
	  
	        $imagewidth        = get_option('woocommerce_shop_swatch_width',"32");  
            $imageheight       = get_option('woocommerce_shop_swatch_height',"32");  
		    $global_activation = get_option('wcva_woocommerce_global_activation');
			$wcva_global       = get_option('wcva_global');
			$hover_image_size  = get_option('woocommerce_hover_imaga_size',"shop_catalog");  
			$direct_link       = get_option('woocommerce_shop_swatch_link', "no");  
			$product_url       = get_permalink( $pid );
			$mobile_click      = get_option('woocommerce_wcva_disable_mobile_hover',"no");
 
            $woocommerce_enable_shop_slider   = get_option('woocommerce_enable_shop_slider',"no");
			$woocommerce_shop_slider_number   = get_option('woocommerce_shop_slider_number',4);

			if (isset($woocommerce_enable_shop_slider) && ($woocommerce_enable_shop_slider == "yes") && ($swatch_count > $woocommerce_shop_slider_number)) {
                $sliderclass                  =  'slider wcva-multiple-items';
                $slideclass                   =  'multiple slide';
            } else {
            	$sliderclass                  =  '';
                $slideclass                   =  '';
            }


			$display_shape     =  'wcvasquare';
	   
	        require_once 'wcva_mobile_detect.php';
	   
	        $detect = new WCVA_Mobile_Detect;
			
			if (isset($mobile_click) && ($mobile_click == "yes") && ( $detect->isMobile() ) ) {
			    $load_direct_variation = "no";
			} else {
				$load_direct_variation = "yes";
			}
			
		
        ?>
	<div class="shopswatchinput <?php echo $sliderclass; ?>" swatch-count="<?php echo $swatch_count; ?>" start-slider-count="<?php echo $woocommerce_shop_slider_number; ?>" prod-img="">
	    <?php  
		
		$load_assets   = wcva_load_shop_page_assets();
      
         
         
		$product            = wc_get_product($pid);
		  
		  
		$product_type       =  $product->get_type();
		
	    if ( $product_type == 'variable' ) {
	        $product = new WC_Product_Variable( $pid ); 
	        $attributes = $product->get_variation_attributes(); 
		} 
	    
        if (isset($load_assets) && ($load_assets == "yes")) {
		
	        foreach ($attributes as $key=>$value) { 
                
				
				if ( taxonomy_exists( $key  ) ) {
			  
			      $terms = get_terms( $key, array('menu_order' => 'ASC') );
			    
				}
				
				
				
           
			    $lower_name       =   strtolower( $key );
			    $clean_name       =   str_replace( 'pa_', '', $lower_name );
			    $lower_key        =   rawurldecode($key);
			    $direct_url       =  ''.$product_url.'?'.$clean_name.'='.$lower_key.'';
			
	        if ($main_display_type == "global") {
				
				    if (isset($global_activation) && $global_activation == "yes") {
				
				        if (isset($wcva_global[$key]['displaytype']) && ($wcva_global[$key]['displaytype'] == "round")) {
				 	        $display_shape =  'wcvaround';
				        }
		            }
					
			    foreach ($value as $kl=>$vl) {	
					
					 if (isset($terms)) {
				      
				        foreach ( $terms as $term ) {
						  		   
                                if ( $term->slug != $vl  ) continue; { 
							            
							  			$swatchimage 	            = absint( get_term_meta( $term->term_id, 'thumbnail_id', true ) );
										$hoverimage 	            = absint( get_term_meta( $term->term_id, 'hoverimage', true ) );
		                                $swatchtype 	            = get_term_meta($term->term_id, 'display_type', true );
		                                $swatchcolor 	            = get_term_meta($term->term_id, 'color', true );
										$attrtextblock 	            = get_term_meta($term->term_id, 'textblock', true );
						        }
							
			            }					   
		             }
					 
					 
					 
					 
					if (isset($swatchimage)) {
					
                        $swatchimageurl   =  apply_filters('wcva_swatch_image_url',wp_get_attachment_thumb_url($swatchimage),$swatchimage);
				    }
				
				    if (isset($hoverimage)) {
					
			            $hoverimage       =  wp_get_attachment_image_src($hoverimage,$hover_image_size);
                        $hoverimageurl    =  apply_filters('wcva_hover_image_url',$hoverimage[0],$hoverimage[0]);
			        }
			
			    
			  
			        if (isset($swatchtype)) {
				      switch ($swatchtype) {
             	        case 'Color':
             		        ?>
                            <a <?php if ((isset($direct_link)) && ($direct_link == "yes") && ( $load_direct_variation == "yes" )) { ?> href="<?php echo $direct_url; ?>" <?php } ?> class="<?php echo $slideclass; ?> wcvaswatchinput" data-o-src="<?php if (isset($hoverimageurl)) { echo $hoverimageurl; } ?>" style="width:<?php echo $imagewidth; ?>px; height:<?php echo $imageheight; ?>px;">
                            <div class="wcvashopswatchlabel <?php echo $display_shape; ?>" style="background-color:<?php if (isset($swatchcolor)) { echo $swatchcolor; } else { echo '#ffffff'; } ?>; width:<?php echo $imagewidth; ?>px; float:left; height:<?php echo $imageheight; ?>px;"></div>
                            </a>
             		        <?php
             		    break;

             	        case 'Image':
             		        ?>
                            <a <?php if ((isset($direct_link)) && ($direct_link == "yes") && ( $load_direct_variation == "yes" )) { ?> href="<?php echo $direct_url; ?>" <?php } ?> class="<?php echo $slideclass; ?> wcvaswatchinput" data-o-src="<?php if (isset($hoverimageurl)) { echo $hoverimageurl; } ?>" >
                            <div class="wcvashopswatchlabel <?php echo $display_shape; ?>"  style="background-image:url(<?php if (isset($swatchimageurl)) { echo $swatchimageurl; } ?>); background-size: <?php echo $imagewidth-2; ?>px <?php echo $imageheight-2; ?>px; float:left; width:<?php echo $imagewidth; ?>px; height:<?php echo $imageheight; ?>px;"></div>
                            </a>
             		        <?php
             		    break;
				
				        case 'textblock':
             		        ?>
                            <a <?php if ((isset($direct_link)) && ($direct_link == "yes") && ( $load_direct_variation == "yes" )) { ?> href="<?php echo $direct_url; ?>" <?php } ?> class="<?php echo $slideclass; ?> wcvaswatchinput" data-o-src="<?php if (isset($hoverimageurl)) { echo $hoverimageurl; } ?>" style="width:<?php echo $imagewidth; ?>px; height:<?php echo $imageheight; ?>px;">
                            <div class="wcvashopswatchlabel wcva_shop_textblock <?php echo $display_shape; ?>" style="min-width:<?php echo $imagewidth; ?>px; "><?php  if (isset($attrtextblock)) { echo $attrtextblock; }   ?></div>
                            </a>
             		        <?php
             		    break;
             	
             
                      } 
			        }
					
			    }
				
			            
		    }

                					 
            
            }
		}		?>
	</div>
	     
	<?php 
	
	}



}

new wcva_shop_page_swatches();
?>