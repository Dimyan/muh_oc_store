<?php
class ControllerModuleProductsSlider extends Controller {
  protected function index($setting) {
    $this->language->load('module/products_slider');

    $this->document->addStyle('catalog/view/theme/muhovyaz/stylesheet/products_slider.css', $rel = 'stylesheet', $media = 'screen');

    $this->document->addScript('catalog/view/javascript/jquery/jquery.cycle.all.min.js');

    $this->data['heading_title']  = $this->language->get('heading_title');
    $this->data['text_buy']       = $this->language->get('text_buy');
    $this->data['text_details']   = $this->language->get('text_details');

    $this->data['timeout']        = $this->config->get('products_slider_timeout');
    $this->data['slider_fx']      = $this->config->get('products_slider_fx_info');
    $this->data['slider_style']   = $this->config->get('products_slider_style_info');
    $this->data['random']         = $this->config->get('products_slider_randomize_info');

    $this->load->model('catalog/product');
    $this->load->model('tool/image');

    $this->data['button_add_to_cart'] = $this->language->get('button_add_to_cart');

    if (empty($setting['limit'])) {
      $setting['limit'] = 5;
    }

    $this->data['products'] = array();

    // ID выбраных для отображения продуктов
    $products_id = implode(',', $this->config->get('selected_products'));

    // Получаем данные по продуктам ограничиваясь лимитом выбора
    // Если количество продуктов выше лимита, то выборка в случайном порядке
    $results = $this->model_catalog_product->getRandomProductsByProductsList($products_id, $setting['limit']);

    foreach ($results as $result) {
      if ($result['image']) {
        $image = $result['image'];
      } else {
        $image = 'no_image.jpg';
      }

      if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
        $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
      } else {
        $price = false;
      }

      if ((float)$result['special']) {
        $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
      } else {
        $special = false;
      }

      if ($this->config->get('config_review_status')) {
        $rating = $result['rating'];
      } else {
        $rating = false;
      }

      $this->data['products'][] = array(
        'product_id'  => $result['product_id'],
        'name'    		=> $result['name'],
        'model'   		=> $result['model'],
        'rating'  		=> (int)$rating,
        'reviews'     => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
        'price'   		=> $price,
        'special' 		=> $special,
        'image'   		=> $this->model_tool_image->resize($image, 152, 152),
        'thumb'   		=> $this->model_tool_image->resize($image, $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height')),
        'href'    	  => $this->url->link('product/product', 'product_id=' . $result['product_id'])
      );
    }

    $this->id = 'products_slider';


    if ($setting['position'] == 'content_top' || $setting['position'] == 'content_bottom') {
      $this->data['heading_title'] .= (' ' . $this->language->get('text_products'));
      if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/products_slider_home.tpl')) {
        $this->template = $this->config->get('config_template') . '/template/module/products_slider_home.tpl';
      } else {
        $this->template = 'default/template/module/products_slider_home.tpl';
      }
    } else {
      if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/products_slider.tpl')) {
        $this->template = $this->config->get('config_template') . '/template/module/products_slider.tpl';
      } else {
        $this->template = 'default/template/module/products_slider.tpl';
      }
    }

    $this->render();
  }
}
?>
