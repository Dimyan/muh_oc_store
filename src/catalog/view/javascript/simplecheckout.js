/*
@author	Dmitriy Kubarev
@link	http://www.simpleopencart.com
@link	http://www.opencart.com/index.php?route=extension/extension/info&extension_id=4811
*/

function simplecheckout_submit() {
    if (can_submit_payment_form()) {
        payment_form_submit();
    } else {
        jQuery('#simple_create_order').val(1);
        simplecheckout_reload('create_order');
    }
}

function can_submit_payment_form() {
    return jQuery('#simplecheckout_payment_form').length && jQuery('#simplecheckout_payment_form #payment_form_reload').length == 0 && jQuery('#simplecheckout_payment_form .simplecheckout_overlay').length == 0 && jQuery('.agree-warning:visible').length == 0;
}

function payment_form_submit() {
    var simplecheckout_payment_form = jQuery('#simplecheckout_payment_form .simplecheckout-block-content');
    var gateway_link = simplecheckout_payment_form.find('div.buttons a:last').attr('href');
    var submit_button = simplecheckout_payment_form.find('div.buttons a:last,div.buttons input[type=button]:last,div.buttons input[type=submit]:last');
    var last_button = simplecheckout_payment_form.find('input[type=button]:last,input[type=submit]:last');
    var last_link = simplecheckout_payment_form.find('a:last').attr('href');

    var proceed_show = false;

    if (typeof gateway_link != 'undefined' && gateway_link != '' && gateway_link != '#') {
        set_location_hash('proceed_payment');
        overlay_button();
        jQuery.get('index.php?'+simple_route+'route=checkout/simplecheckout/prevent_delete', function(){
            overlay_button_remove();
            location = gateway_link;
            if (!payment_form_visible()) {
                jQuery('#simplecheckout_proceed_payment').show();
            }
        });

    } else if (submit_button.length) {
        set_location_hash('proceed_payment');
        overlay_button();
        jQuery.get('index.php?'+simple_route+'route=checkout/simplecheckout/prevent_delete', function(){
            overlay_button_remove();
            !submit_button.attr('disabled') && submit_button.click();
            if (!payment_form_visible()) {
                jQuery('#simplecheckout_proceed_payment').show();
            }
        });
    } else if (last_button.length) {
        set_location_hash('proceed_payment');
        overlay_button();  
        jQuery.get('index.php?'+simple_route+'route=checkout/simplecheckout/prevent_delete', function(){
            overlay_button_remove();
            !last_button.attr('disabled') && last_button.click();
            if (!payment_form_visible()) {
                jQuery('#simplecheckout_proceed_payment').show();
            }
        });
    } else if (typeof last_link != 'undefined' && last_link != '' && last_link != '#') {
        set_location_hash('proceed_payment');
        overlay_button();
        jQuery.get('index.php?'+simple_route+'route=checkout/simplecheckout/prevent_delete', function(){
            overlay_button_remove();
            location = last_link;
            if (!payment_form_visible()) {
                jQuery('#simplecheckout_proceed_payment').show();
            }
        });
    }
}

function set_button_text() {
    if (can_submit_payment_form()) {
        var simplecheckout_payment_form = jQuery('#simplecheckout_payment_form .simplecheckout-block-content');

        var gateway_link = simplecheckout_payment_form.find('div.buttons a:last');
        var submit_button = simplecheckout_payment_form.find('div.buttons input[type=button]:last,div.buttons input[type=submit]:last');
        var last_button = simplecheckout_payment_form.find('input[type=button]:last,input[type=submit]:last');
        var last_link = simplecheckout_payment_form.find('a:last');
        
        if (gateway_link.length) {
            jQuery('#simplecheckout_button_confirm span').text(gateway_link.text());
        } else if (submit_button.length) {
            jQuery('#simplecheckout_button_confirm span').text(submit_button.val());
        } else if (last_button.length) {
            jQuery('#simplecheckout_button_confirm span').text(last_button.val());
        } else if (last_link.length && last_link.text().trim() != '') {
            jQuery('#simplecheckout_button_confirm span').text(last_link.text());
        } else {
            jQuery.get('index.php?'+simple_route+'route=checkout/simplecheckout/prevent_delete');
            $('#simplecheckout_form #buttons').hide();
        }
    }
}

function payment_form_visible() {
    return jQuery('#simplecheckout_payment_form .simplecheckout-block-content :visible:not(form)').length > 0;
}

function hide_payment_form() {
    if (!payment_form_visible()) {
        jQuery('#simplecheckout_payment_form').hide();
    }
}

function block_form() {
    jQuery('input,select,textarea','#simplecheckout_form').attr('disabled', 'disabled');
}

function unblock_form() {
    jQuery('input,select,textarea','#simplecheckout_form').removeAttr('disabled');
}

function overlay_block(selector) {
    var obj = jQuery("#" + selector);
    if (obj.length > 0) {
        var blockHeight = obj.height();
        var blockWidth =  obj.width();
        var blockOffset = obj.offset();
        obj.append("<div class='simplecheckout_overlay'></div>");
        jQuery("#" + selector + " .simplecheckout_overlay")
            .css({
                'background' : 'url('+simple_path+'catalog/view/image/loading.gif) no-repeat center center',
                'opacity' : 0.4,
                'position': 'absolute',
                'width': blockWidth,
                'height': blockHeight,
                'z-index': 5000
            })
            .offset({top: blockOffset.top,left: blockOffset.left});
    }
}

function overlay_button() {
    jQuery('#simplecheckout_button_confirm').attr('disabled', true);
    if (jQuery('.wait').length == 0) {
        jQuery('#simplecheckout_button_confirm').after('<span class="wait">&nbsp;<img src="'+simple_path+'catalog/view/theme/default/image/loading.gif" alt="" /></span>');        
    }
}

function overlay_button_remove() {
    jQuery('#simplecheckout_button_confirm').attr('disabled', false);
    jQuery('.wait').remove();
}

function customer_field_changed() {
    if (payment_form_visible()) {
        jQuery('#simplecheckout_payment_form').show();
    }
    var obj = jQuery('#simplecheckout_payment_form');
    if (obj.length > 0 && obj.find('#payment_form_reload').length == 0) {
        obj.find('.simplecheckout-block-content').empty().append('<div id="payment_form_reload" style="padding:5px;height:60px;cursor:pointer;background:url('+simple_path+'catalog/view/image/simple_update.png) no-repeat center center" onclick="simplecheckout_submit(\'save_changes\');">'+jQuery('#need_save_changes').text()+'</div>');
        jQuery('#simplecheckout_button_confirm span').text($('#default_button').text());
        $('#simplecheckout_form #buttons').show();
    }
}

function overlay_simplecheckout() {
    overlay_block('simplecheckout_cart');
    overlay_block('simplecheckout_customer');
    overlay_block('simplecheckout_shipping');
    overlay_block('simplecheckout_payment');
    overlay_block('simplecheckout_payment_form');
}

function overlay_remove() {
    jQuery(".simplecheckout_overlay").remove();
}

function scroll_to_error() {
    if (jQuery('.simplecheckout-warning-block:visible').length > 0) {
        jQuery('.simplecheckout-warning-block').parent().show();
    }

    if (jQuery('#simplecheckout_customer .simplecheckout-error-text:visible').length > 0 || jQuery('#simplecheckout-customer-fields .simplecheckout-error-text').length > 0 || jQuery('.simplecheckout-warning-block:visible').length > 0) {
        var offset1 = jQuery('#simplecheckout_customer .simplecheckout-error-text:first').prev().offset();
        offset1 = offset1 ? offset1.top : 10000;
        var offset2 = jQuery('.simplecheckout-warning-block:first').offset();
        offset2 = offset2 ? offset2.top : 10000;
        var offset3 = jQuery('#simplecheckout-customer-fields .simplecheckout-error-text:first').offset();
        offset3 = offset3 ? offset3.top : 10000;
        var offset = offset1 >= offset2 ? offset2 : offset1;

        jQuery('html, body').animate({ scrollTop: offset }, 'slow');
    }
}

function make_tab() {
    if (typeof simplecheckout_reload.field !== 'undefined') {
        var fields = [];
        jQuery('input[type=text]:visible,select:visible,textarea:visible','#simplecheckout_customer').each(function(){
            fields[fields.length] = jQuery(this).attr('id');
        });

        var focus = false;
        var focus_key = '';
        for (var i=0;i<fields.length;i++) {
            if (focus) {
                focus_key = fields[i];
                break;
            }
            if (fields[i] == simplecheckout_reload.field) {
                focus = true;
            }
        }
        focus_key = focus_key ? focus_key : simplecheckout_reload.field;
        jQuery('#'+focus_key).focus();
    }
}

function set_special_hash() {
    if (jQuery('#customer_registered').length) {
        set_location_hash('customer_registered');
    }
    if (jQuery('.simplecheckout-error-text:visible').length) {
        set_location_hash('warn_in_customer');
    }
    if (jQuery('.simplecheckout-warning-block:visible').length > 0) {
        set_location_hash('warn_in_'+jQuery('.simplecheckout-warning-block:first').parents('div').attr('id'));
    }
    if (can_submit_payment_form()) {
        set_location_hash('wait_for_payment');
    }
}


function set_location_hash(hash) {
    window.location.hash = hash;
}

function simplecheckout_reload(from) {
    var data = jQuery('#simplecheckout_form').find('input,select,textarea').serialize();

    if (!data.length) {
        return;
    }
    set_location_hash(from);
    jQuery.ajax({
        url: 'index.php?'+simple_route+'route=checkout/simplecheckout',
        data: data,
        type: 'POST',
        dataType: 'text',
        beforeSend: function() {
            block_form();
            overlay_simplecheckout();
            overlay_button();
            jQuery('#payment_form_reload').text(jQuery('#saving_changes').text());
        },      
        success: function(data) {
            jQuery('#simplecheckout_form').replaceWith(data);
            simplecheckout_init();
            make_tab();
            scroll_to_error();
            if (from == 'create_order' && can_submit_payment_form() && !payment_form_visible() && simple_asap) {
                payment_form_submit();
            }
        },
		error: function(xhr, ajaxOptions, thrownError) {
			console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            unblock_form();
            overlay_remove();
		}
    });
}

function set_places() {
    var fields = [];

    jQuery('#simplecheckout_customer tr[place]').each(function() {
        var place = jQuery(this).attr('place');
        if (place == '' || place == 'customer') {
            return;
        }
        fields[place] = typeof fields[place] != 'undefined' ? fields[place] : '';
        fields[place] += '<tr>' + jQuery(this).html() + '</tr>';
        jQuery(this).remove();
    });

    for (var place in fields) {
        jQuery('#simplecheckout_' + place + ' .simplecheckout-methods-table:last').after(
            '<table style="width:100%;" class="simplecheckout-customer-fields">' + fields[place] + '</table>'
        );
    }    
}

function simplecheckout_init() {
    set_masks();
    set_placeholders();
    set_datepickers();
    set_autocomplete();
    set_popups();
    set_button_text();
    set_special_hash();
    hide_payment_form();
    set_googleapi();
    set_places();
}

function simplecheckout_login() {
    jQuery.ajax({
        url: 'index.php?'+simple_route+'route=checkout/simplecheckout_customer/login',
        data: jQuery('#simplecheckout_login input'),
        type: 'POST',
        dataType: 'text',
        success: function(data) {
            jQuery('#simplecheckout_login').replaceWith(data);
        }
    });
}

function simple_login_open() {
    var parent_position = jQuery('#simple_login_layer').parent().css('position');
    if (jQuery('#simple_login_layer').length == 0 || parent_position == 'fixed' || parent_position == 'relative' || parent_position == 'absolute') {
        jQuery('#simple_login_layer').remove();
        jQuery('#simple_login').remove();
        jQuery('body').append('<div id="simple_login_layer" onclick="simple_login_close();"></div><div id="simple_login"><div id="simple_login_header"><img style="cursor:pointer;" src="'+simple_path+'catalog/view/image/close.png" onclick="simple_login_close();"></div><div id="simple_login_content"></div></div>');
    }
    jQuery('#simple_login').show();
    jQuery('#simple_login_content').load('index.php?'+simple_route+'route=checkout/simplecheckout_customer/login');
    var loginHeight = jQuery(document).height();
	var loginWidth = jQuery(window).width();
	jQuery('#simple_login_layer').css('height', loginHeight);
	var winH = jQuery(window).height();
	var winW = jQuery(window).width();
	jQuery('#simple_login').css('top',  winH/2-jQuery('#simple_login').height()/2);
	jQuery('#simple_login').css('left', winW/2-jQuery('#simple_login').width()/2);
	jQuery('#simple_login_layer').fadeTo(500,0.8);
	return false;
}

function simple_login_close() {
    jQuery('#simple_login_layer').fadeOut(500, function() {
		jQuery('#simple_login_layer').hide().css('opacity','1');
	});
    jQuery('#simple_login').fadeOut(500, function() {
		jQuery('#simple_login').hide();
        jQuery('#simple_login_content').empty();
	});
}

jQuery(function(){

    simplecheckout_init();

    jQuery('input[reload]:not([autocomplete]):not([googleapi]),select[reload],textarea[reload]').live('change', function(){
        var from = jQuery(this).attr('reload');
        simplecheckout_reload.field = null;
        if (from.indexOf('checkout_') == 0) {
            simplecheckout_reload.field = jQuery(this).attr('id');
        }
        simplecheckout_reload(from);
    });

    jQuery('#simplecheckout_customer input, #simplecheckout_customer textarea').live('keydown', function(){
        customer_field_changed();
    });

    jQuery('#simplecheckout_customer input[type=radio]:not([reload]),#simplecheckout_customer input[type=checkbox]:not([reload]),#simplecheckout_customer select:not([reload])').live('change', function(){
        customer_field_changed();
    });

    jQuery('#agree').live('change', function(){
        var checked = jQuery(this).attr('checked') ? 1 : 0;
        if (!checked) {
            jQuery('.agree-warning').show();
        } else {
            jQuery('.agree-warning').hide();
        }
    });

    jQuery('#checkout_customer_main_email').live('change',function(){
        jQuery(this).next().remove();
        if (jQuery(this).parent().prev().find('.simplecheckout-required').length && jQuery(this).val()) {
            var register = ~~jQuery('input[name=register]:checked').val();
            jQuery.ajax({
                url: 'index.php?'+simple_route+'route=checkout/simplecheckout_customer/check_email',
                data: { email : jQuery(this).val(), register : register },
                type: 'GET',
                dataType: 'text',
                success: function(data) {
                    if (data) {
                        jQuery('#checkout_customer_main_email').after('<span class="simplecheckout-error-text">'+data+'</span>');
                    }
                }
            });
        }
    });

    jQuery('#email_confirm').live('change',function(){
        var confirm = $(this).val().trim();
        var email = jQuery('#checkout_customer_main_email').val().trim();
        if (confirm != email) {
            $('#email_confirm_error').show();
        } else {
            $('#email_confirm_error').hide();
        }
    });

    jQuery('table.cart td.remove img,.mini-cart-info td.remove img,table.s_cart_items a.s_button_remove').live('click', function(){
        simplecheckout_reload('header_cart_changed');
    });

    jQuery('html,body').keydown(function(event){
        if (event.keyCode == 27) {
            simple_login_close();
        }
    });
});