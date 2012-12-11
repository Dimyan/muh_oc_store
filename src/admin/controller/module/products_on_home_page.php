<?php
class ControllerModuleProductsOnHomePage extends Controller {
  private $error = array();

  public function index() {
    $this->load->language('module/products_on_home_page');

    $this->document->setTitle($this->language->get('heading_title'));

    $this->load->model('setting/setting');

    if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
      $this->model_setting_setting->editSetting('products_on_home_page', $this->request->post);

      $this->session->data['success'] = $this->language->get('text_success');

      $this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
    }

    $this->data['heading_title']        = $this->language->get('heading_title');

    $this->data['text_enabled']         = $this->language->get('text_enabled');
    $this->data['text_disabled']        = $this->language->get('text_disabled');
    $this->data['text_content_top']     = $this->language->get('text_content_top');
    $this->data['text_content_bottom']  = $this->language->get('text_content_bottom');
    $this->data['text_column_left']     = $this->language->get('text_column_left');
    $this->data['text_column_right']    = $this->language->get('text_column_right');

    $this->data['column_name']          = $this->language->get('column_name');

    $this->data['entry_category']       = $this->language->get('entry_category');
    $this->data['entry_limit']          = $this->language->get('entry_limit');
    $this->data['entry_image']          = $this->language->get('entry_image');
    $this->data['entry_layout']         = $this->language->get('entry_layout');
    $this->data['entry_position']       = $this->language->get('entry_position');
    $this->data['entry_status']         = $this->language->get('entry_status');
    $this->data['entry_sort_order']     = $this->language->get('entry_sort_order');

    $this->data['button_save']          = $this->language->get('button_save');
    $this->data['button_cancel']        = $this->language->get('button_cancel');
    $this->data['button_add_module']    = $this->language->get('button_add_module');
    $this->data['button_remove']        = $this->language->get('button_remove');

    if (isset($this->error['warning'])) {
      $this->data['error_warning'] = $this->error['warning'];
    } else {
      $this->data['error_warning'] = '';
    }

    if (isset($this->error['image'])) {
      $this->data['error_image'] = $this->error['image'];
    } else {
      $this->data['error_image'] = array();
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

    #$this->load->model('module/products_on_home_page');
    $this->load->model('catalog/category');

    if (isset($this->request->post['selected'])) {
      $selected_categories = $this->request->post['selected'];
    } else {
      $selected_categories = $this->config->get('selected');
    }
    //Список категорий
    $categories = array();

    // Находим рутовые элементы
    $root_categories = $this->model_catalog_category->getCategoriesByParentId(0);

    foreach($root_categories as $category){
      $categories[] = array(
        'category_id' => $category['category_id'],
        'name'        => $category['name'],
        'selected'    => array_search((int)$category['category_id'], $selected_categories) !== false ? true : false
      );

      // Ищем детей
      $child_categories = $this->getChildCategories((int)$category['category_id']);
      if ($child_categories){
        foreach($child_categories as $child_category){
          $categories[] = array(
            'category_id' => $child_category['category_id'],
            'name'        => $category['name'] . "->" . $child_category['name'],
            'selected'    => array_search((int)$child_category['category_id'], $selected_categories) !== false ? true : false
          );
        }
      }
    }

    $this->data['categories'] = $categories;

    $this->data['action'] = $this->url->link('module/products_on_home_page', 'token=' . $this->session->data['token'], 'SSL');

    $this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

    $this->data['token'] = $this->session->data['token'];

    $this->data['modules'] = array();

    if (isset($this->request->post['products_on_home_page_module'])) {
      $this->data['modules'] = $this->request->post['products_on_home_page_module'];
    } elseif ($this->config->get('products_on_home_page_module')) {
      $this->data['modules'] = $this->config->get('products_on_home_page_module');
    }

    $this->load->model('design/layout');

    $this->data['layouts'] = $this->model_design_layout->getLayouts();

    $this->template = 'module/products_on_home_page.tpl';
    $this->children = array(
      'common/header',
      'common/footer'
    );

    $this->response->setOutput($this->render());
  }

  # Получение подкатегорий
  private function getChildCategories($parent_id){
    $categories = array();

    $child_categories = $this->model_catalog_category->getCategoriesByParentId($parent_id);
    if ($child_categories) {
      foreach ($child_categories as $category){
        $categories[] = array(
          'category_id' => $category['category_id'],
          'name'       => $category['name']
        );

        $childs = $this->getChildCategories((int)$category['category_id']);
        if ($childs){
          foreach($childs as $child_category){
            $categories[] = array(
              'category_id' => $child_category['category_id'],
              'name'       => $category['name'] . "->" . $child_category['name']
            );
          }
        }
      }
    }
    return $categories;
  }

  private function validate() {
    if (!$this->user->hasPermission('modify', 'module/products_on_home_page')) {
      $this->error['warning'] = $this->language->get('error_permission');
    }

    if (isset($this->request->post['products_on_home_page_module'])) {
      foreach ($this->request->post['products_on_home_page_module'] as $key => $value) {
        if (!$value['image_width'] || !$value['image_height']) {
          $this->error['image'][$key] = $this->language->get('error_image');
        }
      }
    }

    if (!$this->error) {
      return true;
    } else {
      return false;
    }
  }
}
?>
