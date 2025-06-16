(function($) {
    "use strict";
    $( document ).ready( function () { 
        $("body").on("click","#doaction",function(e){
            var type = $("#bulk-action-selector-top").val();
            if( type.search("download_pdf_") >= 0) {
                 e.preventDefault();
                var order_ids = [];
                $('#the-list input[name="id[]"]:checked, #the-list input[name="post[]"]:checked').each(function() {
                    order_ids.push($(this).val());
                });
                if( order_ids.length > 0 ){
                    var type_ids = type.split("download_pdf_");
                    var id_template = type_ids[1];
                    var url =yeepdf_woo_acction.link_download +"&id="+id_template+"&woo_order="+order_ids.join(",");
                    window.open(url, '_blank').focus();
                }else{
                    alert(yeepdf_woo_acction.text_no_select_order)
                }
            }
        })
    })
})(jQuery);