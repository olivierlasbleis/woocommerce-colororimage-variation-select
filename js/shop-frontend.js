(function($) {

        $(document).on( 'mouseover', '.wcvaswatchinput',
       	 	function( event ){
              event.preventDefault();
              
              var hoverimage    = $(this).attr('data-o-src');
              var parent        = $(this).closest('li');
              var parentdiv     = $(this).closest('div.shopswatchinput');
              

               if (hoverimage) {
                 $(this).closest('.product').find(".img-first img.attachment-woocommerce_thumbnail").attr("src",hoverimage);
				         $(this).closest('.product').find(".img-first img.attachment-woocommerce_thumbnail").attr("srcset",hoverimage);
                 
               }

               

               return false;
        }
			      



         );

      
        if (wcva_shop.hover_swap == "yes") {
          $(document).on("mouseleave", '.wcvaswatchinput',function(event) {

              event.preventDefault();
              var parent         = $(this).closest('li');
              var parentdiv      = $(this).closest('div.shopswatchinput');
              var default_value  = $(parentdiv).attr("prod-img");

              $(this).closest('.product').find(".img-first img.attachment-woocommerce_thumbnail").attr("src",default_value);
              $(this).closest('.product').find(".img-first img.attachment-woocommerce_thumbnail").attr("srcset",default_value);
           
              return false;
          }); 
        }

         

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