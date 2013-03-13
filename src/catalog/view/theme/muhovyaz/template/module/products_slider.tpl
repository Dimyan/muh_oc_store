<div id="box-yaslider">
    <?php if ($products) { ?>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#slideshow').cycle({
                fx:      '<?php echo $slider_fx; ?>',
                timeout: <?php echo $timeout; ?>,
            random:  <?php echo $random; ?>,
            pause:   true,
                    next:    '#rarr',
                    prev:    '#larr'
        });

        $('#larr, #rarr').hide();
        $('.slideshow').hover(
                function(){
                    $('#larr, #rarr').show();
                }, function(){
                    $('#larr, #rarr').hide();
                });
        });
    </script>
    <div class="slideshow <?php echo $slider_style; ?>"><div id="slideshow">
        <?php foreach ($products as $product) { ?>
        <div class="slide clear">
            <div class="yaproduct">
                <div class="yapr_image">
                    <a href="<?php echo str_replace('&', '&amp;', $product['href']); ?>"><img src="<?php echo $product['image']; ?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>" /></a>
                </div>
                <div class="yapr_info">
                    <?php if ($product['price']) { ?>
                    <div class="yapr_price">
                        <div class="yapr_price_original">
                            <?php if (!$product['special']) { ?>
                            <?php echo $product['price']; ?>
                            <?php } else { ?>
                            <span class="yapr_price_old"><?php echo $product['price']; ?></span> <span class="yapr_price_new"><?php echo $product['special']; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                    <?php } ?>

                    <div class="yapr_name">
                        <h3><a href="<?php echo str_replace('&', '&amp;', $product['href']); ?>"><?php echo $product['name']; ?></a></h3>
                        <p><?php echo $product['model']; ?><br />
                            <?php if ($product['rating']) { ?>
                            <span><img src="catalog/view/theme/default/image/stars_<?php echo $product['rating'] . '.png'; ?>" alt="<?php echo $product['stars']; ?>" /></span>
                            <?php } ?>
                        </p>
                    </div>
                    <div class="yapr_add">
                        <a href="<?php echo $product['add']; ?>" title="<?php echo $text_buy; ?>" ><?php echo $text_buy; ?></a>
                        <a href="<?php echo str_replace('&', '&amp;', $product['href']); ?>" title="<?php echo $text_details; ?>" ><?php echo $text_details; ?></a>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
        <a href="javascript: void(0);" id="larr"></a>
        <a href="javascript: void(0);" id="rarr"></a>
    </div>
    <?php } ?>
</div>
