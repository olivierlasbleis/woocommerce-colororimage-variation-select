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
                 $(this).closest('.product').find("img.wp-post-image").attr("src",hoverimage);
				         $(this).closest('.product').find("img.wp-post-image").attr("srcset",hoverimage);
                 $(parentdiv).attr("prod-img",productimage);
               }
             }
			 

         );


        jQuery(document).ready(function($) {

             jQuery('.wcva-multiple-items').slick({
               slidesToShow: 4,
               slidesToScroll: 4,
               nextArrow: '<img src="'+wcva_shop.right_icon+'" class="nextArrowBtn">',
               prevArrow: '<img src="'+wcva_shop.left_icon+'" class="nextArrowBtn">',
               

             });     

        });




})(jQuery);