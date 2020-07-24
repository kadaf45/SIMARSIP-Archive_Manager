<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Submenu extends CI_Controller{

  public function __construct()
  {
    parent::__construct();

    $this->data['module'] = 'SubMenu';

    $this->data['company_data']    					= $this->Company_model->company_profile();
		$this->data['layout_template']    			= $this->Template_model->layout();
    $this->data['skins_template']     			= $this->Template_model->skins();

    $this->data['btn_submit'] = 'Save';
    $this->data['btn_reset']  = 'Reset';
    $this->data['btn_add']    = 'Add New Data';
    $this->data['add_action'] = base_url('submenu/create');
  }

  function index()
  {
    is_login();
    is_read();

    if(!is_superadmin())
    {
      $this->session->set_flashdata('message', '<div class="alert alert-danger">You can\'t access last page</div>');
      redirect('dashboard');
    }

    $this->data['page_title'] = $this->data['module'].' List';

    $this->data['get_all'] = $this->Submenu_model->get_all();

    $this->load->view('back/submenu/submenu_list', $this->data);
  }

  function create()
  {
    is_login();
    is_create();

    if(!is_superadmin())
    {
      $this->session->set_flashdata('message', '<div class="alert alert-danger">You can\'t access last page</div>');
      redirect('dashboard');
    }

    $this->data['page_title'] = 'Create New '.$this->data['module'];
    $this->data['action']     = 'submenu/create_action';

    $this->data['get_all_combobox_menu']      = $this->Menu_model->get_all_combobox();

    $this->data['menu_id'] = [
      'name'          => 'menu_id',
      'id'            => 'menu_id',
      'class'         => 'form-control',
      'required'      => '',
    ];
    $this->data['submenu_name'] = [
      'name'          => 'submenu_name',
      'id'            => 'submenu_name',
      'class'         => 'form-control',
      'autocomplete'  => 'off',
      'required'      => '',
      'value'         => $this->form_validation->set_value('submenu_name'),
    ];
    $this->data['submenu_url'] = [
      'name'          => 'submenu_url',
      'id'            => 'submenu_url',
      'class'         => 'form-control',
      'autocomplete'  => 'off',
      'required'      => '',
      'value'         => $this->form_validation->set_value('submenu_url'),
    ];
    $this->data['order_no'] = [
      'name'          => 'order_no',
      'id'            => 'order_no',
      'class'         => 'form-control',
      'autocomplete'  => 'off',
      'required'      => '',
      'type'          => 'number',
      'value'         => $this->form_validation->set_value('order_no'),
    ];

    $this->load->view('back/submenu/submenu_add', $this->data);

  }

  function create_action()
  {
    $this->form_validation->set_rules('menu_id', 'Menu Name', 'trim|required');
    $this->form_validation->set_rules('submenu_name', 'SubMenu Name', 'trim|required');
    $this->form_validation->set_rules('submenu_url', 'SubMenu URL', 'trim|required');
    $this->form_validation->set_rules('order_no', 'Order No.', 'is_numeric|required');

    $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

    if($this->form_validation->run() === FALSE)
    {
      $this->create();
    }
    else
    {
      $data = array(
        'menu_id'             => $this->input->post('menu_id'),
        'submenu_name'        => $this->input->post('submenu_name'),
        'submenu_url'         => $this->input->post('submenu_url'),
        'order_no'            => $this->input->post('order_no'),
      );

      $this->Submenu_model->insert($data);

			write_log();

      $this->session->set_flashdata('message', '<div class="alert alert-success">Data saved succesfully</div>');
      redirect('submenu');
    }
  }

  function update($id)
  {
    is_login();
    is_update();

    if(!is_superadmin())
    {
      $this->session->set_flashdata('message', '<div class="alert alert-danger">You can\'t access last page</div>');
      redirect('dashboard');
    }

    $this->data['submenu']     = $this->Submenu_model->get_by_id($id);

    if($this->data['submenu'])
    {
      $this->data['page_title'] = 'Update Data '.$this->data['module'];
      $this->data['action']     = 'submenu/update_action';

      $this->data['get_all_combobox_menu']      = $this->Menu_model->get_all_combobox();

      $this->data['id_submenu'] = [
        'name'          => 'id_submenu',
        'type'          => 'hidden',
      ];
      $this->data['menu_id'] = [
        'name'          => 'menu_id',
        'id'            => 'menu_id',
        'class'         => 'form-control',
        'required'      => '',
      ];
      $this->data['submenu_name'] = [
        'name'          => 'submenu_name',
        'id'            => 'submenu_name',
        'class'         => 'form-control',
        'autocomplete'  => 'off',
        'required'      => '',
      ];
      $this->data['submenu_url'] = [
        'name'          => 'submenu_url',
        'id'            => 'submenu_url',
        'class'         => 'form-control',
        'autocomplete'  => 'off',
        'required'      => '',
      ];
      $this->data['order_no'] = [
        'name'          => 'order_no',
        'id'            => 'order_no',
        'class'         => 'form-control',
        'autocomplete'  => 'off',
        'required'      => '',
        'type'          => 'number',
      ];

      $this->load->view('back/submenu/submenu_edit', $this->data);
    }
    else
    {
      $this->session->set_flashdata('message', '<div class="alert alert-danger">Data not found</div>');
      redirect('submenu');
    }

  }

  function update_action()
  {
    $this->form_validation->set_rules('menu_id', 'Menu Name', 'trim|required');
    $this->form_validation->set_rules('submenu_name', 'SubMenu Name', 'trim|required');
    $this->form_validation->set_rules('submenu_url', 'SubMenu URL', 'trim|required');
    $this->form_validation->set_rules('order_no', 'Order No.', 'is_numeric|required');

    $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

    if($this->form_validation->run() === FALSE)
    {
      $this->update($this->input->post('id_submenu'));
    }
    else
    {
      $data = array(
        'menu_id'             => $this->input->post('menu_id'),
        'submenu_name'        => $this->input->post('submenu_name'),
        'submenu_url'         => $this->input->post('submenu_url'),
        'order_no'            => $this->input->post('order_no'),
      );

      $this->Submenu_model->update($this->input->post('id_submenu'),$data);

			write_log();

      $this->session->set_flashdata('message', '<div class="alert alert-success">Data saved succesfully</div>');
      redirect('submenu');
    }
  }

  function delete($id)
  {
    is_login();
    is_delete();

    if(!is_superadmin())
    {
      $this->session->set_flashdata('message', '<div class="alert alert-danger">You can\'t access last page</div>');
      redirect('dashboard');
    }

    $delete = $this->Submenu_model->get_by_id($id);

    if($delete)
    {
      $this->Submenu_model->delete($id);

      $this->session->set_flashdata('message', '<div class="alert alert-success">Data deleted successfully</div>');
      redirect('submenu');
    }
    else
    {
      $this->session->set_flashdata('message', '<div class="alert alert-danger">No data found</div>');
      redirect('submenu');
    }
  }

  function choose_submenu()
  {
    $this->data['submenu']  = $this->Submenu_model->get_all_combobox($this->uri->segment(3));
    $this->load->view('back/submenu/form_submenu', $this->data);
  }

}
