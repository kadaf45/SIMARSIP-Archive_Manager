<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengembalian extends CI_Controller{

  public function __construct()
  {
    parent::__construct();

    $this->data['module'] = 'Pengembalian';

    $this->load->model(array('Pengembalian_model', 'Arsip_model', 'Peminjaman_model'));

    $this->data['company_data']    					= $this->Company_model->company_profile();
		$this->data['layout_template']    			= $this->Template_model->layout();
    $this->data['skins_template']     			= $this->Template_model->skins();

    $this->data['btn_submit'] = 'Save';
    $this->data['btn_reset']  = 'Reset';
    $this->data['btn_add']    = 'Add New Data';
    $this->data['add_action'] = base_url('pengembalian/create');
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

    $this->data['get_all'] = $this->Pengembalian_model->get_all();

    $this->load->view('back/pengembalian/pengembalian_list', $this->data);
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
    $this->data['action']     = 'pengembalian/create_action';

    $this->data['get_all_combobox_peminjaman']      = $this->Peminjaman_model->get_all_combobox();

    $this->data['kode_pengembalian'] = [
      'name'          => 'kode_pengembalian',
      'id'            => 'kode_pengembalian',
      'class'         => 'form-control',
      'required'      => '',
      'value'         => $this->form_validation->set_value('kode_pengembalian'),
    ];
    $this->data['peminjaman_id'] = [
      'name'          => 'peminjaman_id',
      'id'            => 'peminjaman_id',
      'class'         => 'form-control',
      'required'      => '',
      'onChange'      => 'showArsip()',
    ];
    $this->data['arsip_id'] = [
      'name'          => 'arsip_id',
      'id'            => 'arsip_id',
      'class'         => 'form-control',
    ];

    $this->load->view('back/pengembalian/pengembalian_add', $this->data);

  }

  function create_action()
  {
    $this->form_validation->set_rules('kode_pengembalian', 'Nama Pengembalian', 'trim|required');
    $this->form_validation->set_rules('peminjaman_id', 'Kode Peminjaman', 'trim|required');

    $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

    if($this->form_validation->run() === FALSE)
    {
      $this->create();
    }
    else
    {
      $data = array(
        'kode_pengembalian'       => $this->input->post('kode_pengembalian'),
        'peminjaman_id'           => $this->input->post('peminjaman_id'),
      );

      $this->Pengembalian_model->insert($data);

			write_log();

      // mengganti status is_available arsip
      $this->db->where('id_arsip', $this->input->post('arsip_id'));
      $this->db->update('arsip', array('is_available'=>'0'));

      write_log();

      $this->session->set_flashdata('message', '<div class="alert alert-success">Data saved succesfully</div>');
      redirect('pengembalian');
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

    $this->data['pengembalian']     = $this->Pengembalian_model->get_by_id($id);

    if($this->data['pengembalian'])
    {
      $this->data['page_title'] = 'Update Data '.$this->data['module'];
      $this->data['action']     = 'pengembalian/update_action';

      $this->data['get_all_combobox_user']      = $this->Auth_model->get_all_combobox();
      $this->data['get_all_combobox_arsip']     = $this->Arsip_model->get_all_combobox();

      $this->data['id_pengembalian'] = [
        'name'          => 'id_pengembalian',
        'type'          => 'hidden',
      ];
      $this->data['kode_pengembalian'] = [
        'name'          => 'kode_pengembalian',
        'id'            => 'kode_pengembalian',
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

      $this->load->view('back/pengembalian/pengembalian_edit', $this->data);
    }
    else
    {
      $this->session->set_flashdata('message', '<div class="alert alert-danger">Data not found</div>');
      redirect('pengembalian');
    }

  }

  function update_action()
  {
    $this->form_validation->set_rules('kode_pengembalian', 'Menu Name', 'trim|required');
    $this->form_validation->set_rules('user_id', 'Pengembalian Name', 'trim|required');
    $this->form_validation->set_rules('arsip_id', 'Pengembalian URL', 'trim|required');

    $this->form_validation->set_message('is_unique', '{field} sudah ada, silahkan ganti arsip yang lain');

    $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

    if($this->form_validation->run() === FALSE)
    {
      $this->update($this->input->post('id_pengembalian'));
    }
    else
    {
      $data = array(
        'kode_pengembalian'   => $this->input->post('kode_pengembalian'),
        'user_id'           => $this->input->post('user_id'),
      );

      $this->Pengembalian_model->update($this->input->post('id_pengembalian'),$data);

			write_log();

      $this->session->set_flashdata('message', '<div class="alert alert-success">Data saved succesfully</div>');
      redirect('pengembalian');
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

    $delete = $this->Pengembalian_model->get_by_id($id);

    if($delete)
    {
      $this->Pengembalian_model->delete($id);

      $this->session->set_flashdata('message', '<div class="alert alert-success">Data deleted successfully</div>');
      redirect('pengembalian');
    }
    else
    {
      $this->session->set_flashdata('message', '<div class="alert alert-danger">No data found</div>');
      redirect('pengembalian');
    }
  }

}
