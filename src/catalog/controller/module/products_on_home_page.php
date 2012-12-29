<?php
class ControllerModuleProductsOnHomePage extends Controller {
  protected function index($setting) {
    $this->language->load('module/products_on_home_page');

    $this->data['heading_title'] = $this->language->get('heading_title');

    $this->data['button_cart'] = $this->language->get('button_cart');

    $this->load->model('catalog/product');

    $this->load->model('tool/image');

    $this->data['products'] = array();

    //$products = explode(',', $this->config->get('selected'));
    $categories = $this->config->get('selected');

    if (empty($setting['limit'])) {
      $setting['limit'] = 5;
    }

    //Получаем продукты из выбранных категорий
    foreach($categories as $category_id) {
      $products_info = $this->model_catalog_product->getRandomProductsByCategoryId($category_id, (int)$setting['limit']);

      $products = array();

      if ($products_info) {
        foreach($products_info as $product){
          if ($product['image']) {
            $image = $this->model_tool_image->resize($product['image'], $setting['image_width'], $setting['image_height']);
          } else {
            $image = false;
          }

          if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
            $price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')));
          } else {
            $price = false;
          }

          if ((float)$product['special']) {
            $special = $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id'], $this->config->get('config_tax')));
          } else {
            $special = false;
          }

          if ($this->config->get('config_review_status')) {
            $rating = $product['rating'];
          } else {
            $rating = false;
          }

          $products[] = array(
            'product_id' => $product['product_id'],
            'thumb'   	 => $image,
            'name'    	 => $product['name'],
            'price'   	 => $price,
            'special' 	 => $special,
            'rating'     => (int)$rating,
            'reviews'    => sprintf($this->language->get('text_reviews'), (int)$product['reviews']),
            'href'    	 => $this->url->link('product/product', 'product_id=' . $product['product_id']),
          );
        }

        $category = $this->model_catalog_product->getCategory($category_id);

        // Добавляем данные для шаблона
        $this->data['products_by_category'][] = array(
          'category_name' => $category['name'],
          'products'      => $products
        );
      }
    }

    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/products_on_home_page.tpl')) {
      $this->template = $this->config->get('config_template') . '/template/module/products_on_home_page.tpl';
    } else {
      $this->template = 'default/template/module/products_on_home_page.tpl';
    }

    $this->render();
  }
}
?>
