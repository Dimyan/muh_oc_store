/*
@author	Dmitriy Kubarev
@link	http://www.simpleopencart.com
@link	http://www.opencart.com/index.php?route=extension/extension/info&extension_id=4811
*/ 

function simpleaddress_init() {
    set_masks();
    set_placeholders();
    set_datepickers();
    set_autocomplete();
    set_googleapi();
}

jQuery(function() {
    simpleaddress_init();
});