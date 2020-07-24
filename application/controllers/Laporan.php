<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller{

  public function __construct()
  {
    parent::__construct();

    $this->data['module'] = 'Laporan';

    $this->load->model(array('Peminjaman_model', 'Pengembalian_model', 'Arsip_model'));

    $this->data['company_data']    					= $this->Company_model->company_profile();
		$this->data['layout_template']    			= $this->Template_model->layout();
    $this->data['skins_template']     			= $this->Template_model->skins();

    $this->data['btn_submit'] = 'Save';
    $this->data['btn_reset']  = 'Reset';
    $this->data['btn_add']    = 'Add New Data';
    $this->data['add_action'] = base_url('laporan/create');
  }

  function peminjaman()
  {
    is_login();
    is_read();

    if(!is_superadmin())
    {
      $this->session->set_flashdata('message', '<div class="alert alert-danger">You can\'t access last page</div>');
      redirect('dashboard');
    }

    $this->data['page_title'] = $this->data['module'].' Peminjaman';

    $this->data['get_all'] = $this->Peminjaman_model->get_all();

    $this->load->view('back/laporan/laporan_peminjaman', $this->data);
  }

  function peminjaman_print_all()
  {
    is_login();
    is_read();

    if(!is_superadmin())
    {
      $this->session->set_flashdata('message', '<div class="alert alert-danger">You can\'t access last page</div>');
      redirect('dashboard');
    }

    $this->load->library('Pdf');

    $this->data['get_all'] = $this->Peminjaman_model->get_all();

    $this->load->view('back/laporan/print_peminjaman_all', $this->data);
  }

  function peminjaman_print_periode()
  {
    is_login();
    is_read();

    if(!is_superadmin())
    {
      $this->session->set_flashdata('message', '<div class="alert alert-danger">You can\'t access last page</div>');
      redirect('dashboard');
    }

    $this->load->library('Pdf');

    $this->data['get_all_periode'] = $this->Peminjaman_model->get_all_periode($this->input->post('tgl_awal'), $this->input->post('tgl_akhir'));

    $this->load->view('back/laporan/print_peminjaman_periode', $this->data);
  }

  function pengembalian()
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

    $this->load->view('back/laporan/laporan_list', $this->data);
  }

  function pengembalian_print_all()
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

    $this->load->view('back/laporan/laporan_list', $this->data);
  }

}
