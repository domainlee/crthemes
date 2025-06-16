(function($){
	$( document ).ready( function(){
		if($("#_yee_price_name:checked").length > 0){
			$("._regular_price_field, ._sale_price_field").addClass('hidden');
			$(".show_if_yee_price_name").removeClass('hidden');
		}else{
			$("._regular_price_field, ._sale_price_field").removeClass('hidden');
			$(".show_if_yee_price_name").addClass('hidden');
		}
		$( 'body' ).on( 'change',"#_yee_price_name", function(){
			if ($(this).is(':checked')) {
				$("._regular_price_field, ._sale_price_field").addClass('hidden');
				$(".show_if_yee_price_name").removeClass('hidden');
			}else{
				$("._regular_price_field, ._sale_price_field").removeClass('hidden');
				$(".show_if_yee_price_name").addClass('hidden');
			}
		});
	});
})( jQuery );