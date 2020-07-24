<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends CI_Controller{

  public function __construct()
  {
    parent::__construct();

    $this->data['module'] = 'Menu';

    $this->data['company_data']    					= $this->Company_model->company_profile();
		$this->data['layout_template']    			= $this->Template_model->layout();
    $this->data['skins_template']     			= $this->Template_model->skins();

    $this->data['btn_submit'] = 'Save';
    $this->data['btn_reset']  = 'Reset';
    $this->data['btn_add']    = 'Add New Data';
    $this->data['add_action'] = base_url('menu/create');
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

    $this->data['get_all'] = $this->Menu_model->get_all();

    $this->load->view('back/menu/menu_list', $this->data);
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
    $this->data['action']     = 'menu/create_action';

    $this->data['menu_name'] = [
      'name'          => 'menu_name',
      'id'            => 'menu_name',
      'class'         => 'form-control',
      'autocomplete'  => 'off',
      'required'      => '',
      'value'         => $this->form_validation->set_value('menu_name'),
    ];
    $this->data['menu_url'] = [
      'name'          => 'menu_url',
      'id'            => 'menu_url',
      'class'         => 'form-control',
      'autocomplete'  => 'off',
      'required'      => '',
      'value'         => $this->form_validation->set_value('menu_url'),
    ];
    $this->data['menu_icon'] = [
      'name'          => 'menu_icon',
      'id'            => 'menu_icon',
      'class'         => 'form-control',
      'autocomplete'  => 'off',
      'required'      => '',
      'value'         => $this->form_validation->set_value('menu_icon'),
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

    $this->load->view('back/menu/menu_add', $this->data);

  }

  function create_action()
  {
    $this->form_validation->set_rules('menu_name', 'Menu Name', 'trim|required');
    $this->form_validation->set_rules('menu_url', 'Menu URL', 'trim|required');
    $this->form_validation->set_rules('menu_icon', 'Menu Icon', 'trim|required');

    $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

    if($this->form_validation->run() === FALSE)
    {
      $this->create();
    }
    else
    {
      $data = array(
        'menu_name'     => $this->input->post('menu_name'),
        'menu_slug'     => strtolower(clean3($this->input->post('menu_name'))),
        'menu_url'      => $this->input->post('menu_url'),
        'menu_icon'     => $this->input->post('menu_icon'),
        'order_no'      => $this->input->post('order_no'),
      );

      $this->Menu_model->insert($data);

			write_log();

      $this->session->set_flashdata('message', '<div class="alert alert-success">Data saved succesfully</div>');
      redirect('menu');
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

    $this->data['menu']     = $this->Menu_model->get_by_id($id);

    if($this->data['menu'])
    {
      $this->data['page_title'] = 'Update Data '.$this->data['module'];
      $this->data['action']     = 'menu/update_action';

      $this->data['id_menu'] = [
        'name'          => 'id_menu',
        'type'          => 'hidden',
      ];
			$this->data['menu_name'] = [
	      'name'          => 'menu_name',
	      'id'            => 'menu_name',
	      'class'         => 'form-control',
	      'autocomplete'  => 'off',
	      'required'      => '',
	    ];
      $this->data['menu_url'] = [
        'name'          => 'menu_url',
        'id'            => 'menu_url',
        'class'         => 'form-control',
        'autocomplete'  => 'off',
        'required'      => '',
      ];
      $this->data['menu_icon'] = [
        'name'          => 'menu_icon',
        'id'            => 'menu_icon',
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

      $this->load->view('back/menu/menu_edit', $this->data);
    }
    else
    {
      $this->session->set_flashdata('message', '<div class="alert alert-danger">Data not found</div>');
      redirect('menu');
    }

  }

  function update_action()
  {
    $this->form_validation->set_rules('menu_name', 'Menu Name', 'trim|required');
    $this->form_validation->set_rules('menu_url', 'Menu URL', 'trim|required');
    $this->form_validation->set_rules('menu_icon', 'Menu Icon', 'trim|required');

    $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

    if($this->form_validation->run() === FALSE)
    {
      $this->update($this->input->post('id_menu'));
    }
    else
    {
      $data = array(
        'menu_name'     => $this->input->post('menu_name'),
        'menu_slug'     => strtolower(clean3($this->input->post('menu_name'))),
        'menu_url'      => $this->input->post('menu_url'),
        'menu_icon'      => $this->input->post('menu_icon'),
        'order_no'      => $this->input->post('order_no'),
      );

      $this->Menu_model->update($this->input->post('id_menu'),$data);

			write_log();

      $this->session->set_flashdata('message', '<div class="alert alert-success">Data saved succesfully</div>');
      redirect('menu');
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

    $delete = $this->Menu_model->get_by_id($id);

    if($delete)
    {
      $this->Menu_model->delete($id);

      $this->session->set_flashdata('message', '<div class="alert alert-success">Data deleted successfully</div>');
      redirect('menu');
    }
    else
    {
      $this->session->set_flashdata('message', '<div class="alert alert-danger">No data found</div>');
      redirect('menu');
    }
  }

  public function activate($id)
	{
		$this->Menu_model->update($this->uri->segment(3), array('is_active'=>'1'));

		$this->session->set_flashdata('message', '<div class="alert alert-success">Menu ACTIVATE successfully</div>');
		redirect('menu');
	}

	public function deactivate($id)
	{
  	$this->Menu_model->update($this->uri->segment(3), array('is_active'=>'0'));

		$this->session->set_flashdata('message', '<div class="alert alert-success">Menu DEACTIVATE successfully</div>');
		redirect('menu');
	}

}
