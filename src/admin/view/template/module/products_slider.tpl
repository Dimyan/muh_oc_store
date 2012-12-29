<?php echo $header; ?>
<div id="content">
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>
    <?php if ($error_warning) { ?>
    <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>
    <div class="box">
        <div class="heading">
            <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
            <div class="buttons">
                <a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a>
                <a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a>
            </div>
        </div>
        <div class="content">
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                <table class="form">
                    <tr style="background: #f2f2f2;">
                        <td><strong><?php echo $text_slider_main_settings; ?></strong></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_product; ?></td>
                        <td>
                            <table>
                                <tr>
                                    <td style="padding: 0;" colspan="3">
                                        <select id="category" style="margin-bottom: 5px;" onchange="getProducts();">
                                        <?php foreach ($categories as $category) { ?>
                                          <option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
                                        <?php } ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 0;">
                                        <select multiple="multiple" id="product" size="10" style="width: 350px;"></select>
                                    </td>
                                    <td style="vertical-align: middle;"><input type="button" value="--&gt;" onclick="addToSlider();" />
                                        <br />
                                        <input type="button" value="&lt;--" onclick="removeFromSlider();" />
                                    </td>
                                    <td style="padding: 0;">
                                        <select multiple="multiple" id="products_slider" size="10" style="width: 350px;"></select>
                                    </td>
                                </tr>
                            </table>
                            <div id="selected_products">
                                <?php foreach ($selected_products as $sproduct_id) { ?>
                                <input type="hidden" name="selected_products[]" value="<?php echo $sproduct_id; ?>" />
                                <?php } ?>
                            </div>
                        </td>
                    </tr>
                    <tr style="background: #f2f2f2;">
                        <td><strong><?php echo $text_slider_settings; ?></strong></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_timeout; ?><br/><span class="help"><?php echo $entry_timeout_desc; ?></span></td>
                        <td><input type="text" name="products_slider_timeout" value="<?php echo $products_slider_timeout; ?>" size="10" /></td>
                    </tr>
                    <tr>
                        <td><?php echo $slider_fx; ?></td>
                        <td><select name="products_slider_fx_info">
                            <?php foreach ($products_slider_fxs as $products_slider_fx) { ?>
                            <?php if ($products_slider_fx_info == $products_slider_fx['products_slider_fx']) { ?>
                            <option value="<?php echo $products_slider_fx['products_slider_fx']; ?>" selected="selected"><?php echo $products_slider_fx['title']; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $products_slider_fx['products_slider_fx']; ?>"><?php echo $products_slider_fx['title']; ?></option>
                            <?php } ?>
                            <?php } ?>
                        </select></td>
                    </tr>
                    <tr>
                        <td><?php echo $slider_random; ?></td>
                        <td><select name="products_slider_randomize_info">
                            <?php foreach ($products_slider_randomize as $products_slider_random) { ?>
                            <?php if ($products_slider_randomize_info == $products_slider_random['products_slider_random']) { ?>
                            <option value="<?php echo $products_slider_random['products_slider_random']; ?>" selected="selected"><?php echo $products_slider_random['title']; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $products_slider_random['products_slider_random']; ?>"><?php echo $products_slider_random['title']; ?></option>
                            <?php } ?>
                            <?php } ?>
                        </select></td>
                    </tr>
                    <tr>
                        <td><?php echo $slider_style; ?></td>
                        <td><select name="products_slider_style_info">
                            <?php foreach ($products_slider_styles as $products_slider_style) { ?>
                            <?php if ($products_slider_style_info == $products_slider_style['products_slider_style']) { ?>
                            <option value="<?php echo $products_slider_style['products_slider_style']; ?>" selected="selected"><?php echo $products_slider_style['title']; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $products_slider_style['products_slider_style']; ?>"><?php echo $products_slider_style['title']; ?></option>
                            <?php } ?>
                            <?php } ?>
                        </select></td>
                    </tr>
                </table>
                <table id="module" class="list">
                    <thead>
                    <tr>
                        <td class="left"><?php echo $entry_limit; ?></td>
                        <td class="left"><?php echo $entry_layout; ?></td>
                        <td class="left"><?php echo $entry_position; ?></td>
                        <td class="left"><?php echo $entry_status; ?></td>
                        <td class="right"><?php echo $entry_sort_order; ?></td>
                        <td></td>
                    </tr>
                    </thead>
                    <?php $module_row = 0; ?>
                    <?php foreach ($modules as $module) { ?>
                    <tbody id="module-row<?php echo $module_row; ?>">
                    <tr>
                        <td class="left"><input type="text" name="products_slider_module[<?php echo $module_row; ?>][limit]" value="<?php echo $module['limit']; ?>" size="1" /></td>
                        <td class="left"><select name="products_slider_module[<?php echo $module_row; ?>][layout_id]">
                            <?php foreach ($layouts as $layout) { ?>
                            <?php if ($layout['layout_id'] == $module['layout_id']) { ?>
                            <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                            <?php } ?>
                            <?php } ?>
                        </select></td>
                        <td class="left"><select name="products_slider_module[<?php echo $module_row; ?>][position]">
                            <?php if ($module['position'] == 'content_top') { ?>
                            <option value="content_top" selected="selected"><?php echo $text_content_top; ?></option>
                            <?php } else { ?>
                            <option value="content_top"><?php echo $text_content_top; ?></option>
                            <?php } ?>
                            <?php if ($module['position'] == 'content_bottom') { ?>
                            <option value="content_bottom" selected="selected"><?php echo $text_content_bottom; ?></option>
                            <?php } else { ?>
                            <option value="content_bottom"><?php echo $text_content_bottom; ?></option>
                            <?php } ?>
                            <?php if ($module['position'] == 'column_left') { ?>
                            <option value="column_left" selected="selected"><?php echo $text_column_left; ?></option>
                            <?php } else { ?>
                            <option value="column_left"><?php echo $text_column_left; ?></option>
                            <?php } ?>
                            <?php if ($module['position'] == 'column_right') { ?>
                            <option value="column_right" selected="selected"><?php echo $text_column_right; ?></option>
                            <?php } else { ?>
                            <option value="column_right"><?php echo $text_column_right; ?></option>
                            <?php } ?>
                        </select></td>
                        <td class="left"><select name="products_slider_module[<?php echo $module_row; ?>][status]">
                            <?php if ($module['status']) { ?>
                            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                            <option value="0"><?php echo $text_disabled; ?></option>
                            <?php } else { ?>
                            <option value="1"><?php echo $text_enabled; ?></option>
                            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                            <?php } ?>
                        </select></td>
                        <td class="right"><input type="text" name="products_slider_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></td>
                        <td class="left"><a onclick="$('#module-row<?php echo $module_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
                    </tr>
                    </tbody>
                    <?php $module_row++; ?>
                    <?php } ?>
                    <tfoot>
                    <tr>
                        <td colspan="5"></td>
                        <td class="left"><a onclick="addModule();" class="button"><?php echo $button_add_module; ?></a></td>
                    </tr>
                    </tfoot>
                </table>
            </form>
        </div>
    </div>
</div>


<script type="text/javascript"><!--
function addToSlider() {
    $('#product :selected').each(function() {
        $(this).remove();

        $('#products_slider option[value=\'' + $(this).attr('value') + '\']').remove();

        $('#products_slider').append('<option value="' + $(this).attr('value') + '">' + $(this).text() + '</option>');

        $('#selected_products input[value=\'' + $(this).attr('value') + '\']').remove();

        $('#selected_products').append('<input type="hidden" name="selected_products[]" value="' + $(this).attr('value') + '" />');
    });
}

function removeFromSlider() {
    $('#products_slider :selected').each(function() {
        $(this).remove();

        $('#selected_products input[value=\'' + $(this).attr('value') + '\']').remove();
    });
}

function getProducts() {
    $('#product option').remove();

    $.ajax({
        url: 'index.php?route=module/products_slider/category&token=<?php echo $token; ?>&category_id=' + $('#category').attr('value'),
        dataType: 'json',
        success: function(data) {
            for (i = 0; i < data.length; i++) {
                $('#product').append('<option value="' + data[i]['product_id'] + '">' + data[i]['name'] + ' (' + data[i]['model'] + ') </option>');
            }
        }
    });
}

function getSlider() {
    $('#products_slider option').remove();

    $.ajax({
        url: 'index.php?route=module/products_slider/product&token=<?php echo $token; ?>',
        type: 'POST',
        dataType: 'json',
        data: $('#selected_products input'),
        success: function(data) {
            $('#selected_products input').remove();

            for (i = 0; i < data.length; i++) {
                $('#products_slider').append('<option value="' + data[i]['product_id'] + '">' + data[i]['name'] + ' (' + data[i]['model'] + ') </option>');

                $('#selected_products').append('<input type="hidden" name="selected_products[]" value="' + data[i]['product_id'] + '" />');
            }
        }
    });
}

getProducts();
getSlider();
//--></script>
<script type="text/javascript"><!--
var module_row = <?php echo $module_row; ?>;

function addModule() {
    html  = '<tbody id="module-row' + module_row + '">';
    html += '  <tr>';
    html += '    <td class="left"><input type="text" name="products_slider_module[' + module_row + '][limit]" value="5" size="1" /></td>';
    html += '    <td class="left"><select name="products_slider_module[' + module_row + '][layout_id]">';
    <?php foreach ($layouts as $layout) { ?>
        html += '      <option value="<?php echo $layout['layout_id']; ?>"><?php echo addslashes($layout['name']); ?></option>';
    <?php } ?>
    html += '    </select></td>';
    html += '    <td class="left"><select name="products_slider_module[' + module_row + '][position]">';
    html += '      <option value="content_top"><?php echo $text_content_top; ?></option>';
    html += '      <option value="content_bottom"><?php echo $text_content_bottom; ?></option>';
    html += '      <option value="column_left"><?php echo $text_column_left; ?></option>';
    html += '      <option value="column_right"><?php echo $text_column_right; ?></option>';
    html += '    </select></td>';
    html += '    <td class="left"><select name="products_slider_module[' + module_row + '][status]">';
    html += '      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
    html += '      <option value="0"><?php echo $text_disabled; ?></option>';
    html += '    </select></td>';
    html += '    <td class="right"><input type="text" name="products_slider_module[' + module_row + '][sort_order]" value="" size="3" /></td>';
    html += '    <td class="left"><a onclick="$(\'#module-row' + module_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
    html += '  </tr>';
    html += '</tbody>';

    $('#module tfoot').before(html);

    module_row++;
}
//--></script>
<?php echo $footer; ?>
