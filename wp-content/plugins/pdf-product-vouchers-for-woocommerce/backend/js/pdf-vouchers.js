(function($) {
"use strict";
  jQuery(document).ready(function($){ 
    $('.yeepdf-admin-table-sort').sortable({
        cursor: 'move',
        axis: 'y',
        handle: 'h3',
        scrollSensitivity: 40,
        forcePlaceholderSize: true,
        helper: 'clone',
        opacity: 0.65,
        placeholder: 'wc-metabox-sortable-placeholder',
        start: function (event, ui) {
            ui.item.css('background-color', '#f6f6f6');
        },
    });
    $("body").on("click",".yeepdf-admin-table .remove_row",function(e){
        e.preventDefault();
        $(this).closest('.wc-metaboxes').remove();
    })
    $("body").on("click",".yeepdf-admin-table .handlediv",function(e){
        e.preventDefault();
        if( $(this).closest('.wc-metaboxes').find('.woocommerce_attribute').hasClass('closed')){
            $(this).closest('.wc-metaboxes').find('.woocommerce_attribute_data').slideUp();
        }else{
            $(this).closest('.wc-metaboxes').find('.woocommerce_attribute_data').slideDown();
        }
    })
    $("body").on("click",'#yeepdf-product-settings-tab-inner li',function(event){
        var tab = $(this).data('show');
        $("#yeepdf-product-settings-tab-inner li").removeClass("active");
        $(this).addClass("active");
        $(".pdf_vou_voucher_tab").addClass('hidden');
        $(".pdf_vou_voucher_tab_"+tab).removeClass('hidden');
    })
    $("body").on("click",'.yeepdf_voucher_add_template',function(event){
        event.preventDefault();
        var html = $("#_yeepdf_product_vouchers_templates_data").html();
        var rand = Math.floor(Math.random() * 10000);
        html = html.replaceAll("yeepdf_template_change_name", "_yeepdf_product_vouchers_templates["+rand+"]");
        html = html.replaceAll("yeepdf_change_rand_name", "name-"+rand);
        $(".pdf_vou_voucher_template_container_data").append(html);
    })
     $("body").on("click",'.yeepdf_voucher_add_field',function(event){
        event.preventDefault();
        var html = $("#_yeepdf_product_vouchers_forms_data").html();
        var rand = Math.floor(Math.random() * 10000);
        html = html.replaceAll("yeepdf_change_name", "_yeepdf_product_vouchers_forms["+rand+"]");
         html = html.replaceAll("yeepdf_change_rand_name", "name-"+rand);
        $(".pdf_vou_voucher_forms_container_data").append(html);
    })
    $("body").on("change",'#_yeepdf_product_vouchers_coupon_discount_type',function(event){
        var value = $(this).val();
        if(value == "percent"){
            $("._yeepdf_product_vouchers_coupon_coupon_amount_type_field").addClass("hidden");
            $(".yeepdf_product_vouchers_coupon_amount").removeClass("hidden");
        }else{
            $("._yeepdf_product_vouchers_coupon_coupon_amount_type_field").removeClass("hidden");
            var type = $("#_yeepdf_product_vouchers_coupon_coupon_amount_type").val();
            if( type == "price" ){
                 $(".yeepdf_product_vouchers_coupon_amount").removeClass("hidden");
            }else{
                 $(".yeepdf_product_vouchers_coupon_amount").removeClass("hidden");
            }
        }
    })
    $("body").on("click",'#_yeepdf_product_vouchers_coupon_coupon_amount_type',function(event){
        var vl = $(this).val();
        if(vl == "price"){
            $(".yeepdf_product_vouchers_coupon_amount").addClass("hidden");
        }else{
             $(".yeepdf_product_vouchers_coupon_amount").removeClass("hidden");
        }
    })
    $("body").on("change",'._yeepdf_product_vouchers-auto_create select',function(event){
        let vl = $(this).val();
        if( vl == "yes"){
            $(".yeepdf-tab-coupon").removeClass("hidden");
        }else{
            $(".yeepdf-tab-coupon").addClass("hidden");
        }
    })
    $("body").on("change",'.yeepdf_attribute_name_update',function(event){
        let vl = $(this).val();
        $(this).val(vl);
        $(this).attr("value",vl);
        console.log(vl);
    })
    $("body").on("click",'.yeepdf-hover-show-input',function(event){
        $(this).find(".yeepdf-hover-show-input-show").addClass("hidden");
        $(this).find(".yeepdf-hover-show-input-hide").removeClass("hidden");
    })
    $("body").on("mouseenter",'.yeepdf-hover-show-input-show',function(event){
        $(this).attr('title', "Click to change the field");
    })
    $("body").on("click",'.yeepdf-action-redeeem-check',function(event){
        event.preventDefault();
        var type = $(this).data("type");
        var id = $("#yeepdf_voucher_voucher_id").val();
        var product_id = $("#yeepdf_voucher_product_id").val();
        var price = $("#yeepdf_voucher_product_amount").val();
        var nonce = $("#yeepdf_order_voucher").val();
        var btn = $(this);
        if(type == "unredeem"){
            id = $(this).data("id");
        }
        var data = {
                'action': 'yeepdf_action_redeeem',
                'id': id,
                'product_id': product_id,
                'price': price,
                'type': type,
                'security': nonce
            };
        if(type =="redeem"){
            btn.html('Redeeming...');
        }else{
            btn.html('Unredeeming...');
        }
       $.post(ajaxurl, data, function(response) {
            $("tr#post-"+id).addClass("hidden");
            $(".yeepdf-ajax-done").removeClass("hidden");
            $(".yeepdf-ajax-done-message").html(response.message);
            if(response.status == "ok"){
                 btn.closest("tr").addClass("hidden");
                $(".yeepdf-ajax-done").removeClass("yeepdf-ajax-done-error");
                $(".yeepdf-ajax-done").addClass("yeepdf-ajax-done-ok");
            }else{
                 btn.html('Redeem');
                $(".yeepdf-ajax-done").addClass("yeepdf-ajax-done-error");
                $(".yeepdf-ajax-done").removeClass("yeepdf-ajax-done-ok");
            }
        });
    })
    $("body").on("change",'#_yeepdf_product_vouchers-auto_create_cp',function(event){
        let vl = $(this).val();
        if( vl == "yes"){
            $("._yeepdf_product_vouchers-codes_field, ._yeepdf_product_vouchers_usage_field").addClass("hidden");
        }else{
            console.log("no");
            $("._yeepdf_product_vouchers-codes_field, ._yeepdf_product_vouchers_usage_field").removeClass("hidden");
        }
    })
    $("body").on("change",'._yeepdf_product_vouchers-expiration_type select',function(event){
        let vl = $(this).val();
        if( vl == "specific_time"){
            $("._yeepdf_product_vouchers-expiration_days").addClass("hidden");
            $(".field_specific_time").removeClass("hidden");
        }else{
            $("._yeepdf_product_vouchers-expiration_days").removeClass("hidden");
            $(".field_specific_time").addClass("hidden");
        }
    })
    $("body").on("click",'.yeepdf_add_note',function(event){
        event.preventDefault();
        var value = $("#yeepdf_add_order_note").val();
        var nonce = $("#yeepdf_order_voucher").val();
        var id = $("#post_ID").val();
        var data = {
                'action': 'yeepdf_action_add_note',
                'note': value,
                'id': id,
                'security': nonce
            };
        $(".yeepdf_add_note").html("Loading...");
        $.post(ajaxurl, data, function(response) {
            $(".yeepdf_add_note").html("Add Note");
            $(".yee_order_notes").prepend('Some text')('<li>'+value+'</li>');
            $("#yeepdf_add_order_note").val("");
        });
    })
    $("body").on("click",'.yeepdf_delete_note',function(event){
        event.preventDefault();
        var nonce = $("#yeepdf_order_voucher").val();
        var id = $(this).data("id");
        var data = {
                'action': 'yeepdf_action_delete_note',
                'id': id,
                'security': nonce
            };
        $(this).closest("li").remove();
        $.post(ajaxurl, data, function(response) {
        });
    })
    function showHideVoucherFields() {
        if( $('#_yee_voucher').is(':checked') ){
            $('._show_if_yee_voucher').show();
        }else{
            $('._show_if_yee_voucher').hide();
        }
    }
    showHideVoucherFields();
    $('#_yee_voucher').on('change', showHideVoucherFields);
    $("body").on("click",".yeepdf-action-redeeem",function(e){
        e.preventDefault();
        var id = $(this).data("id");
        var product_id = $(this).data("product_id");
        var product_name = $(this).data("product_name");
        var price = $(this).data("price");
        var price_fm = $(this).data("price_fm");
        var voucher = $(this).data("voucher");
        var partial_redemption = $(this).data("partial_redemption");
        $(".yeepdf_redeem_product_name").html(product_name);
        $(".yeepdf_redeem_voucher_code").html(voucher);
        $("#yeepdf_voucher_product_id").val(product_id);
        $("#yeepdf_voucher_voucher_id").val(id);
        $("#yeepdf_voucher_product_amount").val(price);
        $(".yeepdf_redeem_price").html(price_fm);
        if( partial_redemption == "yes"){
            $("#yeepdf_voucher_product_amount").attr("type","number");
            $(".yeepdf_redeem_price").addClass("hidden");
        }else{
            $("#yeepdf_voucher_product_amount").attr("type","hidden");
            $(".yeepdf_redeem_price").removeClass("hidden");
        }
        $(".yeepdf_redeem-btn-ok").removeClass("hidden");
        $(".yeepdf-ajax-done").addClass("hidden");
        $(".yeepdf_redeem-btn-ok .yeepdf-action-redeeem-check").html("Redeem");
        $("#yeepdf-action-redeeem-container" ).dialog({
            modal: true,
            width: 800,
            title: "Redeem Voucher Code",
            buttons: {
              Close: function() {
                $( this ).dialog( "close" );
              }
            }
          });
    })
})
})(jQuery);