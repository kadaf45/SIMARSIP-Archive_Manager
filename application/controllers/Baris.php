<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Baris extends CI_Controller{

  public function __construct()
  {
    parent::__construct();

    $this->data['module'] = 'Baris';

    $this->load->model(array('Baris_model'));

    $this->data['company_data']    					= $this->Company_model->company_profile();
		$this->data['layout_template']    			= $this->Template_model->layout();
    $this->data['skins_template']     			= $this->Template_model->skins();

    $this->data['btn_submit'] = 'Save';
    $this->data['btn_reset']  = 'Reset';
    $this->data['btn_add']    = 'Add New Data';
    $this->data['add_action'] = base_url('baris/create');
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

    $this->data['get_all'] = $this->Baris_model->get_all();

    $this->load->view('back/baris/baris_list', $this->data);
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
    $this->data['action']     = 'baris/create_action';

    $this->data['baris_name'] = [
      'name'          => 'baris_name',
      'id'            => 'baris_name',
      'class'         => 'form-control',
      'autocomplete'  => 'off',
      'required'      => '',
      'value'         => $this->form_validation->set_value('baris_name'),
    ];

    $this->load->view('back/baris/baris_add', $this->data);

  }

  function create_action()
  {
    $this->form_validation->set_rules('baris_name', 'Baris Name', 'trim|required');

    $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

    if($this->form_validation->run() === FALSE)
    {
      $this->create();
    }
    else
    {
      $data = array(
        'baris_name'     => $this->input->post('baris_name'),
      );

      $this->Baris_model->insert($data);

			write_log();

      $this->session->set_flashdata('message', '<div class="alert alert-success">Data saved succesfully</div>');
      redirect('baris');
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

    $this->data['baris']     = $this->Baris_model->get_by_id($id);

    if($this->data['baris'])
    {
      $this->data['page_title'] = 'Update Data '.$this->data['module'];
      $this->data['action']     = 'baris/update_action';

      $this->data['id_baris'] = [
        'name'          => 'id_baris',
        'type'          => 'hidden',
      ];
			$this->data['baris_name'] = [
	      'name'          => 'baris_name',
	      'id'            => 'baris_name',
	      'class'         => 'form-control',
	      'autocomplete'  => 'off',
	      'required'      => '',
	    ];

      $this->load->view('back/baris/baris_edit', $this->data);
    }
    else
    {
      $this->session->set_flashdata('message', '<div class="alert alert-danger">Data not found</div>');
      redirect('baris');
    }

  }

  function update_action()
  {
    $this->form_validation->set_rules('baris_name', 'Baris Name', 'trim|required');

    $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

    if($this->form_validation->run() === FALSE)
    {
      $this->update($this->input->post('id_baris'));
    }
    else
    {
			$data = array(
        'baris_name'     => $this->input->post('baris_name'),
      );

      $this->Baris_model->update($this->input->post('id_baris'),$data);

			write_log();

      $this->session->set_flashdata('message', '<div class="alert alert-success">Data saved succesfully</div>');
      redirect('baris');
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

    $delete = $this->Baris_model->get_by_id($id);

    if($delete)
    {
      $this->Baris_model->delete($id);

      $this->session->set_flashdata('message', '<div class="alert alert-success">Data deleted successfully</div>');
      redirect('baris');
    }
    else
    {
      $this->session->set_flashdata('message', '<div class="alert alert-danger">No data found</div>');
      redirect('baris');
    }
  }

}
