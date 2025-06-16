(function($){
	$( document ).ready( function(){
		$('#yee-nyp-price').autoNumeric();
		$( 'body' ).on( 'click',"#yee-nyp-price", function(){
			$(this).autoNumeric();
			var data = $(this).autoNumeric("get");
            $(this).val(data);
		});
		$( 'body' ).on( 'change',"#yee-nyp-price-select", function(){
			var vl = $(this).val();
			if(vl =="custom"){
				$("#yee-nyp-price, .yee-nyp-des").removeClass("hidden");
			}else{
				$("#yee-nyp-price").val(vl);
				$("#yee-nyp-price").attr("value",vl);
				$("#yee-nyp-price, .yee-nyp-des").addClass("hidden");
			}
		});
		if($( "#yee-nyp-price" ).hasClass( "yee-nyp-price" )){
			var input = $( "#yee-nyp-price" );
			input.autoNumeric();
			var price = input.autoNumeric("get");
			if(price =="" || price == 0){
				input.closest('form').find('[name="add-to-cart"]').addClass("yee-disable");
			}
		}
		$("body").on("click",".yee-disable",function(e){
			$("#yee-nyp-price").focus().click();
			e.preventDefault();
			return false;
		})
		$( 'body' ).on( 'focusout',"#yee-nyp-price", function(e){
			$(this).autoNumeric();
			var check = true;
			var price = $(this).autoNumeric("get");
			var min = $(this).data("min");
			var max = $(this).data("max");
			var li ="";
			if(price != "" && price > 0){
				if(min !="" && min >= 0){
					if(min > price){
						check = false;
						li += "<li>"+yee_nyp.text_min_error+" "+min+"</li>";
					}
				}
				if(max !="" && max > 0){
					if(price > max){
						check = false;
						li += "<li>"+yee_nyp.text_max_error+" "+max+"</li>";
					}
				}
			}else{
				check = false;
				li += "<li>"+yee_nyp.text_price_error+"</li>";
			}
			if(check == false){
				$(this).closest('form').find('[name="add-to-cart"]').addClass("yee-disable");
				$(".yee-name-your-price-container-notify").removeClass("hidden");
				$(".yee-name-your-price-container-notify ul").html(li);
			}else{
				$(this).closest('form').find('[name="add-to-cart"]').removeClass("yee-disable");
				$(".yee-name-your-price-container-notify").addClass("hidden");
			}
		});
	});
})( jQuery );