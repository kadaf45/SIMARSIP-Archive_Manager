<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Peminjaman extends CI_Controller{

  public function __construct()
  {
    parent::__construct();

    $this->data['module'] = 'Peminjaman';

    $this->load->model(array('Peminjaman_model', 'Arsip_model'));

    $this->data['company_data']    					= $this->Company_model->company_profile();
		$this->data['layout_template']    			= $this->Template_model->layout();
    $this->data['skins_template']     			= $this->Template_model->skins();

    $this->data['btn_submit'] = 'Save';
    $this->data['btn_reset']  = 'Reset';
    $this->data['btn_add']    = 'Add New Data';
    $this->data['add_action'] = base_url('peminjaman/create');
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

    $this->data['get_all'] = $this->Peminjaman_model->get_all();

    $this->load->view('back/peminjaman/peminjaman_list', $this->data);
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
    $this->data['action']     = 'peminjaman/create_action';

    $this->data['get_all_combobox_user']      = $this->Auth_model->get_all_combobox();
    $this->data['get_all_combobox_arsip']     = $this->Arsip_model->get_all_combobox();

    $this->data['kode_peminjaman'] = [
      'name'          => 'kode_peminjaman',
      'id'            => 'kode_peminjaman',
      'class'         => 'form-control',
      'required'      => '',
      'value'         => $this->form_validation->set_value('kode_peminjaman'),
    ];
    $this->data['user_id'] = [
      'name'          => 'user_id',
      'id'            => 'user_id',
      'class'         => 'form-control',
      'required'      => '',
    ];
    $this->data['arsip_id'] = [
      'name'          => 'arsip_id',
      'id'            => 'arsip_id',
      'class'         => 'form-control',
      'required'      => '',
    ];

    $this->load->view('back/peminjaman/peminjaman_add', $this->data);

  }

  function create_action()
  {
    $this->form_validation->set_rules('kode_peminjaman', 'Kode Peminjaman', 'trim|required');
    $this->form_validation->set_rules('user_id', 'Peminjaman Name', 'trim|required');
    $this->form_validation->set_rules('arsip_id', 'Peminjaman URL', 'trim|is_unique[peminjaman.arsip_id]|required');

    $this->form_validation->set_message('is_unique', '{field} sudah ada, silahkan ganti arsip yang lain');

    $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

    if($this->form_validation->run() === FALSE)
    {
      $this->create();
    }
    else
    {
      $data = array(
        'kode_peminjaman'   => $this->input->post('kode_peminjaman'),
        'user_id'           => $this->input->post('user_id'),
        'arsip_id'          => $this->input->post('arsip_id'),
      );

      $this->Peminjaman_model->insert($data);
			write_log();

      // mengganti status is_available arsip
      $this->db->where('id_arsip', $this->input->post('arsip_id'));
      $this->db->update('arsip', array('is_available'=>'1'));

      $this->session->set_flashdata('message', '<div class="alert alert-success">Data saved succesfully</div>');
      redirect('peminjaman');
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

    $this->data['peminjaman']     = $this->Peminjaman_model->get_by_id($id);

    if($this->data['peminjaman'])
    {
      $this->data['page_title'] = 'Update Data '.$this->data['module'];
      $this->data['action']     = 'peminjaman/update_action';

      $this->data['get_all_combobox_user']      = $this->Auth_model->get_all_combobox();
      $this->data['get_all_combobox_arsip']     = $this->Arsip_model->get_all_combobox();

      $this->data['id_peminjaman'] = [
        'name'          => 'id_peminjaman',
        'type'          => 'hidden',
      ];
      $this->data['kode_peminjaman'] = [
        'name'          => 'kode_peminjaman',
        'id'            => 'kode_peminjaman',
        'class'         => 'form-control',
        'required'      => '',
      ];
      $this->data['user_id'] = [
        'name'          => 'user_id',
        'id'            => 'user_id',
        'class'         => 'form-control',
        'required'      => '',
      ];
      $this->data['arsip_id'] = [
        'name'          => 'arsip_id',
        'id'            => 'arsip_id',
        'class'         => 'form-control',
        'required'      => '',
      ];

      $this->load->view('back/peminjaman/peminjaman_edit', $this->data);
    }
    else
    {
      $this->session->set_flashdata('message', '<div class="alert alert-danger">Data not found</div>');
      redirect('peminjaman');
    }

  }

  function update_action()
  {
    $this->form_validation->set_rules('kode_peminjaman', 'Menu Name', 'trim|required');
    $this->form_validation->set_rules('user_id', 'Peminjaman Name', 'trim|required');
    $this->form_validation->set_rules('arsip_id', 'Peminjaman URL', 'trim|required');

    $this->form_validation->set_message('is_unique', '{field} sudah ada, silahkan ganti arsip yang lain');

    $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

    if($this->form_validation->run() === FALSE)
    {
      $this->update($this->input->post('id_peminjaman'));
    }
    else
    {
      $data = array(
        'kode_peminjaman'   => $this->input->post('kode_peminjaman'),
        'user_id'           => $this->input->post('user_id'),
        'arsip_id'          => $this->input->post('arsip_id'),
      );

      $this->Peminjaman_model->update($this->input->post('id_peminjaman'),$data);

			write_log();

      $this->session->set_flashdata('message', '<div class="alert alert-success">Data saved succesfully</div>');
      redirect('peminjaman');
    }
  }

  function detail($id)
  {
    is_login();

    if(!is_superadmin())
    {
      $this->session->set_flashdata('message', '<div class="alert alert-danger">You can\'t access last page</div>');
      redirect('dashboard');
    }

    $this->data['peminjaman']     = $this->Peminjaman_model->get_by_id($id);

    if($this->data['peminjaman'])
    {
      $this->data['page_title'] = 'Detail Data '.$this->data['module'];

      $this->load->view('back/peminjaman/peminjaman_detail', $this->data);
    }
    else
    {
      $this->session->set_flashdata('message', '<div class="alert alert-danger">Data not found</div>');
      redirect('peminjaman');
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

    $delete = $this->Peminjaman_model->get_by_id($id);

    if($delete)
    {
      $this->Peminjaman_model->delete($id);

      $this->session->set_flashdata('message', '<div class="alert alert-success">Data deleted successfully</div>');
      redirect('peminjaman');
    }
    else
    {
      $this->session->set_flashdata('message', '<div class="alert alert-danger">No data found</div>');
      redirect('peminjaman');
    }
  }

}
