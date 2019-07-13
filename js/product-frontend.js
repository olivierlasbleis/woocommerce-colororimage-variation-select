jQuery(document).ready(function($) {
        
	   

	    if (wcva.tooltip == "yes") {
		    $('.swatchinput label').powerTip();
	    }
      
	    $('form.variations_form').on( 'change', '.wcva-standard-select', function() {
			var selectedtext         = $(this).val();
			
			if (wcva.show_attribute == "yes") {
			   $( this ).closest('tr').prev().find('.wcva_selected_attribute').text(selectedtext);
		    }
		});



	



        
     
	    $('form.variations_form').on( 'click', '.swatchinput label', function() {
		    var selectid           = $(this).attr("selectid");
            var dataoption         = $(this).attr("data-option");
			var selectedtext       = $(this).attr("selectedtext");
		    var attributeindex     = $(this).closest('.attribute-swatch').attr('attribute-index');
	
		    


		    if (wcva.quick_view == "off") {
			    if ($(this).hasClass('selectedswatch')) {
				
				$(this).removeClass('selectedswatch').addClass('wcvaswatchlabel');
				
				var currentoptionToSelect = parent.jQuery("form.variations_form #" + selectid + "").children("[value='']");

               //mark the option as selected
                currentoptionToSelect.prop("selected", "selected").change();
				
				$( this ).closest('tr').prev().find('.wcva_selected_attribute').text("");
				
				return;
			   }
		    }

		    
		   if (wcva.show_attribute == "yes") {
			   $( this ).closest('tr').prev().find('.wcva_selected_attribute').text(selectedtext);
		   }
	      
		   
		   $( this ).closest('.attribute-swatch').find('.selectedswatch').removeClass('selectedswatch').addClass('wcvaswatchlabel');
	       $( this ).removeClass('wcvaswatchlabel').addClass( 'selectedswatch' );
		  
		  
		  
           //find the option to select
           var optionToSelect = parent.jQuery("form.variations_form #" + selectid + "").children("[value='" + dataoption + "']");

           //mark the option as selected
           optionToSelect.prop("selected", "selected").change();
		 
					    
		});	   
		
		
		if (wcva.disable_options == "yes") {
			 		
			$('form.variations_form').on( 'click', '.swatchinput label', function( event ) {

				 var selectid           = $(this).attr("selectid");
                 var dataoption         = $(this).attr("data-option");

                 wcva_disable_swatches_as_dropdown();
                 //wcva_disable_outofstock_options(selectid,dataoption);

		    });
			
			$('form.variations_form').on( 'click', '.wcva-standard-select', function(event) {

				 
			
			     wcva_disable_swatches_as_dropdown();
			     
		    });

		    $('form.variations_form').on( 'change', '.wcva-standard-select', function(event) {

		    	var selectid           = $(this).attr("id");
                var dataoption         = $(this).val();
               
                //wcva_disable_outofstock_options(selectid,dataoption);


		    });
		}
	   
	   
	    
        $( window ).load(function() {
           	
           	   wcva_disable_swatches_as_dropdown();

               /*
           	   if (wcva_attribute_number == 1) {
                   $('form.variations_form' ).find('.wcvadisabled').addClass('wcvaoutofstock');
           	   }
           	   */



            $('.attribute-swatch').each(function(){

                var show_number       = $(this).find('.wcva_show_more_link').attr("show-number");
                var swatch_count      = $(this).find('.swatchinput').length;
                 
                var show_more_link    = $(this).find('.wcva_show_more_link');

                var swatch_hide_count = swatch_count - show_number;
                var swatch_hide_count = Math.abs(swatch_hide_count) * -1;

                

                if (swatch_count > show_number) {
                 	
                    $(this).find('.swatchinput').slice(swatch_hide_count).addClass("hidden_swatchinput");
                    $(this).find('.swatchinput').slice(swatch_hide_count).hide();

                 	show_more_link.show();


                 	$(show_more_link).on( 'click', function(event) {
                    
                        event.preventDefault();
                	    
                	    $(this).parents('.attribute-swatch').find('.swatchinput').slice(swatch_hide_count).show();
                        $(this).parents('.attribute-swatch').find('.swatchinput').slice(swatch_hide_count).removeClass("hidden_swatchinput");
                	    
                	    $(this).hide();
                	    
                	    return false;

                    });

                }
            });
           	   
        });
      
       
        $('form.variations_form').on( 'click', '.reset_variations', function() {
			
			$('form.variations_form').find('.selectedswatch').removeClass('selectedswatch').addClass('wcvaswatchlabel');
			$('form.variations_form').find('.wcva_selected_attribute').text("");
            /*
            if (wcva_attribute_number != 1) {
			    $('form.variations_form' ).find('.wcvaoutofstock').removeClass('wcvaoutofstock');
			}
			*/
		
		if (wcva.disable_options == "yes") {
			
			$('form.variations_form' ).find('.wcvadisabled').removeClass('wcvadisabled');
			jQuery('.swatchinput').removeClass('wcvadisabled');
            
            if (wcva.hide_options == "yes") {
				if (!jQuery(this).parent().hasClass("hidden_swatchinput")) {
						jQuery(this).parent().show();
                }
			}

            if (wcva_attribute_number == 1) {

               wcva_disable_swatches_as_dropdown();
              
            }

		}
			
		});



		function wcva_disable_swatches_as_dropdown() {

            var availableoptions = [];
			
			jQuery('form.variations_form').find( '.variations select' ).each( function( i, e ) {
				
				var eachselect = jQuery( e );
				
				
				
				jQuery(e).trigger('focusin');
				
				jQuery(eachselect).find('option').each(function(index,element){
					
					
						
						availableoptions.push(element.value);
						
					
				});


				
				var wcvalabel = jQuery(this).closest('td').find('.swatchinput label');
				
				jQuery(wcvalabel).each(function(){
					var dataoption = jQuery(this).attr("data-option");
					
					if(jQuery.inArray( dataoption, availableoptions ) < 0){
						
						if ($(this).hasClass('selectedswatch')) {
						   jQuery(this).removeClass('selectedswatch').addClass('wcvaswatchlabel');
		                   jQuery(this).addClass('wcvadisabled');
						   
                           if (wcva.hide_options == "yes") {
						     jQuery(this).parent().hide();
                           }
						 
						} else {
						   jQuery(this).addClass('wcvadisabled');
						   if (wcva.hide_options == "yes") {
						    jQuery(this).parent().hide();
						   }


						 
						}
						
					}else{
						
						jQuery(this).removeClass('wcvadisabled');
						if (wcva.hide_options == "yes") {
                            if (!jQuery(this).parent().hasClass("hidden_swatchinput")) {
						        jQuery(this).parent().show();
                            }
						}
					}
				});
			});
		}
        
        /*
        
		function wcva_disable_outofstock_options(selectid,dataoption) {

			var outofarray = [];

			var outattribute     = 'attribute_'+selectid+'';
			var outvalue         = dataoption;
			
            
            var tempoutofstock_array = outofstock_array;
			
            
            $.each(tempoutofstock_array, function( fkey, fvalue ) {
                 var outselectval = fvalue[outattribute];
                 
                 if (outselectval == outvalue) {

                 	delete fvalue;
                 	
                 	outofarray.push(fvalue);

                 	
                 }
            });

            

          

            
            
            if (wcva_attribute_number != 1) {
                $('form.variations_form' ).find('.wcvaoutofstock').removeClass('wcvaoutofstock');
            }
            

            

            

			jQuery('form.variations_form').find( '.variations select' ).each( function( index, event ) {
				
				var eachselect = jQuery( event );


				var wcvalabel = jQuery(this).closest('td').find('.swatchinput label');
				
				jQuery(wcvalabel).each(function(){

					var selectid2           = $(this).attr("selectid");
                    var dataoption2         = $(this).attr("data-option");

                    
                    
					
					$.each(outofarray, function( fkey2, fvalue2 ) {
                         
                        

                        $.each(fvalue2, function( fkey3, fvalue3 ) {

                        	option_to_disable = $('.'+fkey3+'_'+dataoption2+'');

                            if  (fvalue3 == dataoption2) {

                           	  
                              if ((outattribute != fkey3)) {

                              	if (!$(option_to_disable).hasClass("selectedswatch")) {
                              	  if (wcva_attribute_number != 1) {
                              	   option_to_disable.addClass('wcvaoutofstock');
                              	  }
                              	} 
                              }
                           	  

                            } else {

                              
                                option_to_disable.removeClass('wcvaoutofstock');

                            }
                           
                        });

                         
                    });

				});



			});
            

		}

		*/


	   
});