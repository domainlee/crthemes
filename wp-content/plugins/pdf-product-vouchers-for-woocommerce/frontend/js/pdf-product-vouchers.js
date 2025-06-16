(function($) {
"use strict";
    $(document).ready(function($){ 
        console.log(1);
        $('.yeeaddons_product_vouchers_templates').magnificPopup({
                                delegate: 'a', // child items selector, by clicking on it popup will open
                                type: 'image'
                                // other options
                                });
        $("body").on("click",".yeeaddons_product_vouchers_templates a",function(e){
            e.preventDefault();
            var template = $(this).data("id");
            $(".yeeaddons_product_vouchers_templates a").removeClass('active');
            $(this).addClass('active');
            $("#yeeaddons_product_vouchers_template").val(template);
        })
        $('.copy-code').each(function () {
            var t = $(this);
            t.click(function (e) {
                e.preventDefault();
                copy_input( $(t.attr('data-id')) );
                t.addClass('active');
            })
        });

        function copy_input( $input ) {
            $input.focus();
            $input.select();
            try {
                var successful = document.execCommand('copy');
            } catch(err) {
                
            }
        }
    })
})(jQuery);