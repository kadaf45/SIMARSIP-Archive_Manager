<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rak extends CI_Controller{

  public function __construct()
  {
    parent::__construct();

    $this->data['module'] = 'Rak';

    $this->load->model(array('Rak_model'));

    $this->data['company_data']    					= $this->Company_model->company_profile();
		$this->data['layout_template']    			= $this->Template_model->layout();
    $this->data['skins_template']     			= $this->Template_model->skins();

    $this->data['btn_submit'] = 'Save';
    $this->data['btn_reset']  = 'Reset';
    $this->data['btn_add']    = 'Add New Data';
    $this->data['add_action'] = base_url('rak/create');
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

    $this->data['get_all'] = $this->Rak_model->get_all();

    $this->load->view('back/rak/rak_list', $this->data);
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
    $this->data['action']     = 'rak/create_action';

    $this->data['rak_name'] = [
      'name'          => 'rak_name',
      'id'            => 'rak_name',
      'class'         => 'form-control',
      'autocomplete'  => 'off',
      'required'      => '',
      'value'         => $this->form_validation->set_value('rak_name'),
    ];

    $this->load->view('back/rak/rak_add', $this->data);

  }

  function create_action()
  {
    $this->form_validation->set_rules('rak_name', 'Rak Name', 'trim|required');

    $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

    if($this->form_validation->run() === FALSE)
    {
      $this->create();
    }
    else
    {
      $data = array(
        'rak_name'     => $this->input->post('rak_name'),
      );

      $this->Rak_model->insert($data);

			write_log();

      $this->session->set_flashdata('message', '<div class="alert alert-success">Data saved succesfully</div>');
      redirect('rak');
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

    $this->data['rak']     = $this->Rak_model->get_by_id($id);

    if($this->data['rak'])
    {
      $this->data['page_title'] = 'Update Data '.$this->data['module'];
      $this->data['action']     = 'rak/update_action';

      $this->data['id_rak'] = [
        'name'          => 'id_rak',
        'type'          => 'hidden',
      ];
			$this->data['rak_name'] = [
	      'name'          => 'rak_name',
	      'id'            => 'rak_name',
	      'class'         => 'form-control',
	      'autocomplete'  => 'off',
	      'required'      => '',
	    ];

      $this->load->view('back/rak/rak_edit', $this->data);
    }
    else
    {
      $this->session->set_flashdata('message', '<div class="alert alert-danger">Data not found</div>');
      redirect('rak');
    }

  }

  function update_action()
  {
    $this->form_validation->set_rules('rak_name', 'Rak Name', 'trim|required');

    $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

    if($this->form_validation->run() === FALSE)
    {
      $this->update($this->input->post('id_rak'));
    }
    else
    {
			$data = array(
        'rak_name'     => $this->input->post('rak_name'),
      );

      $this->Rak_model->update($this->input->post('id_rak'),$data);

			write_log();

      $this->session->set_flashdata('message', '<div class="alert alert-success">Data saved succesfully</div>');
      redirect('rak');
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

    $delete = $this->Rak_model->get_by_id($id);

    if($delete)
    {
      $this->Rak_model->delete($id);

      $this->session->set_flashdata('message', '<div class="alert alert-success">Data deleted successfully</div>');
      redirect('rak');
    }
    else
    {
      $this->session->set_flashdata('message', '<div class="alert alert-danger">No data found</div>');
      redirect('rak');
    }
  }

}
