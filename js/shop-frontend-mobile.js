(function($) {

       	 $(document).on( 'click', '.wcvaswatchinput',
       	 	function( e ){
              var hoverimage    = $(this).attr('data-o-src');
              var parent        = $(this).closest('li');
              var parentdiv     = $(this).closest('div.shopswatchinput');
              var productimage  = $(this).closest('.product').find("img").attr("src"); 
             
			     $( this ).closest('.shopswatchinput').find('div.selectedswatch').removeClass('selectedswatch').addClass('wcvashopswatchlabel');
			     $( this ).closest('.wcvaswatchinput').find('div.wcvashopswatchlabel').removeClass('wcvashopswatchlabel').addClass( 'selectedswatch' );
			 
               if (hoverimage) {
                 $(this).closest('.product').find("img.attachment-woocommerce_thumbnail").attr("src",hoverimage);
				 $(this).closest('.product').find("img.attachment-woocommerce_thumbnail").attr("srcset",hoverimage);
                 $(parentdiv).attr("prod-img",productimage);
               }
             }
			 

         );


        var slider_count = parseInt(wcva_shop.slider_no);

        jQuery(document).ready(function($) {

          if (wcva_shop.enable_slider == "yes") {

            

             $('.wcva-multiple-items').each(function(){

            

              var swatch_count = $(this).attr("swatch-count");
              
              
              if (swatch_count > slider_count) {
                jQuery(this).slick({
                
                  slidesToShow: slider_count,
                  slidesToScroll: slider_count,
                  nextArrow: '<img src="'+wcva_shop.right_icon+'" class="nextArrowBtn">',
                  prevArrow: '<img src="'+wcva_shop.left_icon+'" class="nextArrowBtn">',
              
                }); 
              }
               
            });

            $('.wcva-multiple-items').show();

          }

        });




})(jQuery);