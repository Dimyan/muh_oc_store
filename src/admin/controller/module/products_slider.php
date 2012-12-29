<?php
class ControllerModuleProductsSlider extends Controller {
  private $error = array();

  public function index() {
    $this->load->language('module/products_slider');

    $this->document->setTitle($this->language->get('heading_title'));

    $this->load->model('setting/setting');

    if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
      $this->model_setting_setting->editSetting('products_slider', $this->request->post);

      $this->session->data['success'] = $this->language->get('text_success');

      //$this->cache->delete('product');

      $this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
    }

    $this->data['heading_title']                = $this->language->get('heading_title');

    $this->data['text_enabled']                 = $this->language->get('text_enabled');
    $this->data['text_disabled']                = $this->language->get('text_disabled');
    $this->data['text_left']                    = $this->language->get('text_left');
    $this->data['text_right']                   = $this->language->get('text_right');
    $this->data['text_home']                    = $this->language->get('text_home');
    $this->data['text_content_top']             = $this->language->get('text_content_top');
    $this->data['text_content_bottom']          = $this->language->get('text_content_bottom');
    $this->data['text_column_left']             = $this->language->get('text_column_left');
    $this->data['text_column_right']            = $this->language->get('text_column_right');

    $this->data['entry_limit']                  = $this->language->get('entry_limit');
    $this->data['entry_position']               = $this->language->get('entry_position');
    $this->data['entry_status']                 = $this->language->get('entry_status');
    $this->data['entry_sort_order']             = $this->language->get('entry_sort_order');
    $this->data['entry_layout']                 = $this->language->get('entry_layout');
    $this->data['entry_product']                = $this->language->get('entry_product');

    $this->data['button_save']                  = $this->language->get('button_save');
    $this->data['button_cancel']                = $this->language->get('button_cancel');
    $this->data['button_add_module']            = $this->language->get('button_add_module');
    $this->data['button_remove']                = $this->language->get('button_remove');

    $this->data['text_slider_main_settings']    = $this->language->get('text_slider_main_settings');
    $this->data['text_slider_settings']         = $this->language->get('text_slider_settings');
    $this->data['entry_timeout']                = $this->language->get('entry_timeout');
    $this->data['entry_timeout_desc']           = $this->language->get('entry_timeout_desc');
    $this->data['slider_fx']                    = $this->language->get('slider_fx');
    $this->data['slider_fx_vert']               = $this->language->get('slider_fx_vert');
    $this->data['slider_fx_horz']               = $this->language->get('slider_fx_horz');
    $this->data['slider_fx_fade']               = $this->language->get('slider_fx_fade');

    $this->data['slider_random']                = $this->language->get('slider_random');
    $this->data['slider_random_yes']            = $this->language->get('slider_random_yes');
    $this->data['slider_random_no']             = $this->language->get('slider_random_no');

    $this->data['slider_style']                 = $this->language->get('slider_style');
    $this->data['slider_style_default']         = $this->language->get('slider_style_default');
    $this->data['slider_style_blue']            = $this->language->get('slider_style_blue');
    $this->data['slider_style_green']           = $this->language->get('slider_style_green');
    $this->data['slider_style_red']             = $this->language->get('slider_style_red');

    if (isset($this->error['warning'])) {
      $this->data['error_warning'] = $this->error['warning'];
    } else {
      $this->data['error_warning'] = '';
    }

    $this->data['breadcrumbs'] = array();

    $this->data['breadcrumbs'][] = array(
      'text'      => $this->language->get('text_home'),
      'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      'separator' => false
    );

    $this->data['breadcrumbs'][] = array(
      'text'      => $this->language->get('text_module'),
      'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      'separator' => ' :: '
    );

    $this->data['breadcrumbs'][] = array(
      'text'      => $this->language->get('heading_title'),
      'href'      => $this->url->link('module/products_on_home_page', 'token=' . $this->session->data['token'], 'SSL'),
      'separator' => ' :: '
    );

    $this->data['action'] = $this->url->link('module/products_slider', 'token=' . $this->session->data['token'], 'SSL');

    $this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

    $this->data['token'] = $this->session->data['token'];


    if (isset($this->request->post['products_slider_timeout'])) {
      $this->data['products_slider_timeout'] = $this->request->post['products_slider_timeout'];
    } else {
      $this->data['products_slider_timeout'] = $this->config->get('products_slider_timeout');
    }

    // FX
    $this->data['products_slider_fxs'] = array();

    $this->data['products_slider_fxs'][] = array(
      'products_slider_fx' => 'scrollVert',
      'title'    => $this->language->get('slider_fx_vert'),
    );

    $this->data['products_slider_fxs'][] = array(
      'products_slider_fx' => 'scrollHorz',
      'title'    => $this->language->get('slider_fx_horz'),
    );

    $this->data['products_slider_fxs'][] = array(
      'products_slider_fx' => 'fade',
      'title'    => $this->language->get('slider_fx_fade'),
    );

    if (isset($this->request->post['products_slider_fx_info'])) {
      $this->data['products_slider_fx_info'] = $this->request->post['products_slider_fx_info'];
    } else {
      $this->data['products_slider_fx_info'] = $this->config->get('products_slider_fx_info');
    }

    //Styles
    $this->data['products_slider_styles'] = array();

    $this->data['products_slider_styles'][] = array(
      'products_slider_style' => 'default',
      'title'    => $this->language->get('slider_style_default'),
    );

    $this->data['products_slider_styles'][] = array(
      'products_slider_style' => 'blue',
      'title'    => $this->language->get('slider_style_blue'),
    );

    $this->data['products_slider_styles'][] = array(
      'products_slider_style' => 'green',
      'title'    => $this->language->get('slider_style_green'),
    );

    $this->data['products_slider_styles'][] = array(
      'products_slider_style' => 'red',
      'title'    => $this->language->get('slider_style_red'),
    );

    if (isset($this->request->post['products_slider_style_info'])) {
      $this->data['products_slider_style_info'] = $this->request->post['products_slider_style_info'];
    } else {
      $this->data['products_slider_style_info'] = $this->config->get('products_slider_style_info');
    }

    //Random Slides
    $this->data['products_slider_randomize'] = array();

    $this->data['products_slider_randomize'][] = array(
      'products_slider_random' => '0',
      'title'    => $this->language->get('slider_random_no'),
    );

    $this->data['products_slider_randomize'][] = array(
      'products_slider_random' => 'true',
      'title'    => $this->language->get('slider_random_yes'),
    );

    if (isset($this->request->post['products_slider_randomize_info'])) {
      $this->data['products_slider_randomize_info'] = $this->request->post['products_slider_randomize_info'];
    } else {
      $this->data['products_slider_randomize_info'] = $this->config->get('products_slider_randomize_info');
    }

    $this->data['positions'] = array();

    $this->data['positions'][] = array(
      'position' => 'home',
      'title'    => $this->language->get('text_home'),
    );
    $this->data['positions'][] = array(
      'position' => 'left',
      'title'    => $this->language->get('text_left'),
    );

    $this->data['positions'][] = array(
      'position' => 'right',
      'title'    => $this->language->get('text_right'),
    );

    $this->load->model('catalog/category');

    $this->data['categories'] = $this->model_catalog_category->getCategories(0);

    $this->load->model('catalog/product');

    if (isset($this->request->post['selected_products'])) {
      $this->data['selected_products'] = $this->request->post['selected_products'];
    } else {
      $this->data['selected_products'] = $this->config->get('selected_products');
    }

    $this->data['modules'] = array();

    if (isset($this->request->post['products_slider_module'])) {
      $this->data['modules'] = $this->request->post['products_slider_module'];
    } elseif ($this->config->get('products_slider_module')) {
      $this->data['modules'] = $this->config->get('products_slider_module');
    }

    $this->load->model('design/layout');

    $this->data['layouts'] = $this->model_design_layout->getLayouts();

    $this->template = 'module/products_slider.tpl';
    $this->children = array(
      'common/header',
      'common/footer'
    );

    $this->response->setOutput($this->render());
  }

  private function validate() {
    if (!$this->user->hasPermission('modify', 'module/products_slider')) {
      $this->error['warning'] = $this->language->get('error_permission');
    }

    if (!$this->error) {
      return TRUE;
    } else {
      return FALSE;
    }
  }


  public function category() {
    $this->load->model('catalog/product');

    if (isset($this->request->get['category_id'])) {
      $category_id = $this->request->get['category_id'];
    } else {
      $category_id = 0;
    }

    $product_data = array();

    $results = $this->model_catalog_product->getProductsByCategoryId($category_id);

    foreach ($results as $result) {
      $product_data[] = array(
        'product_id' => $result['product_id'],
        'name'       => $result['name'],
        'model'      => $result['model']
      );
    }
    //print_r($product_data);
    //$this->load->library('json');

    $this->response->setOutput(json_encode($product_data));
  }



  public function product() {
    $this->load->model('catalog/product');

    if (isset($this->request->post['selected_products'])) {
      $products = $this->request->post['selected_products'];
    } else {
      $products = array();
    }

    $product_data = array();

    foreach ($products as $product_id) {
      $product_info = $this->model_catalog_product->getProduct($product_id);

      if ($product_info) {
        $product_data[] = array(
          'product_id' => $product_info['product_id'],
          'name'       => $product_info['name'],
          'model'      => $product_info['model']
        );
      }
    }

    //$this->load->library('json');

    //$this->response->setOutput(Json::encode($product_data));
    $this->response->setOutput(json_encode($product_data));
  }
}
?>
