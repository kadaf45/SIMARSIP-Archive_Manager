<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class arsip extends CI_Controller{

  public function __construct()
  {
    parent::__construct();

    $this->data['module'] = 'Arsip';

    $this->load->model(array('Arsip_model', 'Rak_model', 'Baris_model'));

    $this->data['company_data']    					= $this->Company_model->company_profile();
		$this->data['layout_template']    			= $this->Template_model->layout();
    $this->data['skins_template']     			= $this->Template_model->skins();

    $this->data['btn_submit'] = 'Save';
    $this->data['btn_reset']  = 'Reset';
    $this->data['btn_add']    = 'Add New Data';
    $this->data['add_action'] = base_url('arsip/create');
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

    $this->data['get_all'] = $this->Arsip_model->get_all();

    $this->load->view('back/arsip/arsip_list', $this->data);
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
    $this->data['action']     = 'arsip/create_action';

    $this->data['get_all_combobox_rak']     = $this->Rak_model->get_all_combobox();
    $this->data['get_all_combobox_baris']   = $this->Baris_model->get_all_combobox();

    $this->data['kode_arsip'] = [
      'name'          => 'kode_arsip',
      'id'            => 'kode_arsip',
      'class'         => 'form-control',
      'autocomplete'  => 'off',
      'required'      => '',
      'maxlength'     => '15',
      'value'         => $this->form_validation->set_value('kode_arsip'),
    ];
    $this->data['arsip_name'] = [
      'name'          => 'arsip_name',
      'id'            => 'arsip_name',
      'class'         => 'form-control',
      'autocomplete'  => 'off',
      'value'         => $this->form_validation->set_value('arsip_name'),
    ];
    $this->data['rak_id'] = [
      'name'          => 'rak_id',
      'id'            => 'rak_id',
      'class'         => 'form-control',
    ];
    $this->data['baris_id'] = [
      'name'          => 'baris_id',
      'id'            => 'baris_id',
      'class'         => 'form-control',
    ];

    $this->load->view('back/arsip/arsip_add', $this->data);

  }

  function create_action()
  {
    $this->form_validation->set_rules('kode_arsip', 'Kode arsip', 'trim|required');
    $this->form_validation->set_rules('arsip_name', 'Nama arsip', 'trim|is_unique[arsip.arsip_name]|required');
    $this->form_validation->set_rules('rak_id', 'Rak', 'required');
    $this->form_validation->set_rules('baris_id', 'Baris', 'required');

    // $this->form_validation->set_message('required', '{field} wajib diisi');
    // $this->form_validation->set_message('valid_email', '{field} format email tidak benar');

    $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

    if($this->form_validation->run() === FALSE)
    {
      $this->create();
    }
    else
    {
      if($_FILES['file_arsip']['error'] <> 4)
      {
        $nmfile = strtolower(url_title($this->input->post('arsip_name'))).date('YmdHis');

        $config['upload_path']      = './assets/file_arsip/';
        $config['allowed_types']    = 'pdf|doc|docx|xls|xlsx|zip';
        $config['max_size']         = 2048; // 2Mb
        $config['file_name']        = $nmfile;

        $this->load->library('upload', $config);

        if(!$this->upload->do_upload('file_arsip'))
        {
          $error = array('error' => $this->upload->display_errors());
          $this->session->set_flashdata('message', '<div class="alert alert-danger">'.$error['error'].'</div>');

          $this->create();
        }
        else
        {
          $file_arsip = $this->upload->data();

          $data = array(
            'kode_arsip'        => $this->input->post('kode_arsip'),
            'arsip_name'        => $this->input->post('arsip_name'),
            'rak_id'            => $this->input->post('rak_id'),
            'baris_id'          => $this->input->post('baris_id'),
            'file_arsip'             => $this->upload->data('file_name'),
          );

          $this->Arsip_model->insert($data);

          write_log();

          $this->session->set_flashdata('message', '<div class="alert alert-success">Data saved succesfully</div>');
          redirect('arsip');
        }
      }
      else
      {
        $data = array(
          'kode_arsip'        => $this->input->post('kode_arsip'),
          'arsip_name'        => $this->input->post('arsip_name'),
          'rak_id'            => $this->input->post('rak_id'),
          'baris_id'          => $this->input->post('baris_id'),
        );

        $this->Arsip_model->insert($data);

        write_log();

        $this->session->set_flashdata('message', '<div class="alert alert-success">Data saved succesfully</div>');
        redirect('arsip');
      }
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

    $this->data['arsip']     = $this->Arsip_model->get_by_id($id);

    if($this->data['arsip'])
    {
      $this->data['page_title'] = 'Update Data '.$this->data['module'];
      $this->data['action']     = 'arsip/update_action';

      $this->data['id_arsip'] = [
        'name'          => 'id_arsip',
        'type'          => 'hidden',
      ];
      $this->data['get_all_combobox_rak']     = $this->Rak_model->get_all_combobox();
      $this->data['get_all_combobox_baris']   = $this->Baris_model->get_all_combobox();

      $this->data['kode_arsip'] = [
        'name'          => 'kode_arsip',
        'id'            => 'kode_arsip',
        'class'         => 'form-control',
        'autocomplete'  => 'off',
        'required'      => '',
        'maxlength'     => '15',
      ];
      $this->data['arsip_name'] = [
        'name'          => 'arsip_name',
        'id'            => 'arsip_name',
        'class'         => 'form-control',
        'autocomplete'  => 'off',
      ];
      $this->data['rak_id'] = [
        'name'          => 'rak_id',
        'id'            => 'rak_id',
        'class'         => 'form-control',
      ];
      $this->data['baris_id'] = [
        'name'          => 'baris_id',
        'id'            => 'baris_id',
        'class'         => 'form-control',
      ];

      $this->load->view('back/arsip/arsip_edit', $this->data);
    }
    else
    {
      $this->session->set_flashdata('message', '<div class="alert alert-danger">arsip not found</div>');
      redirect('arsip');
    }

  }

  function update_action()
  {
    $this->form_validation->set_rules('kode_arsip', 'Kode arsip', 'trim|required');
    $this->form_validation->set_rules('arsip_name', 'Nama arsip', 'trim|required');
    $this->form_validation->set_rules('rak_id', 'Rak', 'required');
    $this->form_validation->set_rules('baris_id', 'Baris', 'required');

    // $this->form_validation->set_message('required', '{field} wajib diisi');
    // $this->form_validation->set_message('valid_email', '{field} format email tidak benar');

    $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

    if($this->form_validation->run() === FALSE)
    {
      $this->update($this->input->post('id_arsip'));
    }
    else
    {
      if($_FILES['file_arsip']['error'] <> 4)
      {
        $nmfile = strtolower(url_title($this->input->post('arsip_name'))).date('YmdHis');

        $config['upload_path']      = './assets/file_arsip/';
        $config['allowed_types']    = 'pdf|doc|docx|xls|xlsx|zip';
        $config['max_size']         = 2048; // 2Mb
        $config['file_name']        = $nmfile;

        $this->load->library('upload', $config);

        $delete = $this->Arsip_model->get_by_id($this->input->post('id_arsip'));

        $dir        = "./assets/file_arsip/".$delete->file_arsip;
        $dir_thumb  = "./assets/file_arsip/".$delete->file_arsip_thumb;

        if(is_file($dir))
        {
          unlink($dir);
        }

        if(!$this->upload->do_upload('file_arsip'))
        {
          $error = array('error' => $this->upload->display_errors());
          $this->session->set_flashdata('message', '<div class="alert alert-danger">'.$error['error'].'</div>');

          $this->update($this->input->post('id_arsip'));
        }
        else
        {
          $file_arsip = $this->upload->data();

          $data = array(
            'kode_arsip'        => $this->input->post('kode_arsip'),
            'arsip_name'        => $this->input->post('arsip_name'),
            'rak_id'            => $this->input->post('rak_id'),
            'baris_id'          => $this->input->post('baris_id'),
            'file_arsip'        => $this->upload->data('file_name'),
          );

          $this->Arsip_model->update($this->input->post('id_arsip'),$data);

          write_log();

          $this->session->set_flashdata('message', '<div class="alert alert-success">Data update succesfully</div>');
          redirect('arsip');
        }
      }
      else
      {
        $data = array(
          'kode_arsip'        => $this->input->post('kode_arsip'),
          'arsip_name'        => $this->input->post('arsip_name'),
          'rak_id'            => $this->input->post('rak_id'),
          'baris_id'          => $this->input->post('baris_id'),
        );

        $this->Arsip_model->update($this->input->post('id_arsip'),$data);

        write_log();

        $this->session->set_flashdata('message', '<div class="alert alert-success">Data update succesfully</div>');
        redirect('arsip');
      }
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

    $delete = $this->Arsip_model->get_by_id($id);

    if($delete)
    {
      $data = array(
        'is_delete'   => '1',
        'deleted_by'  => $this->session->arsip_name,
        'deleted_at'  => date('Y-m-d H:i:a'),
      );

      $this->Arsip_model->soft_delete($id, $data);

      write_log();

      $this->session->set_flashdata('message', '<div class="alert alert-success">Data deleted successfully</div>');
      redirect('arsip');
    }
    else
    {
      $this->session->set_flashdata('message', '<div class="alert alert-danger">No data found</div>');
      redirect('arsip');
    }
  }

  function delete_permanent($id)
  {
    is_login();
    is_delete();

    if(!is_superadmin())
    {
      $this->session->set_flashdata('message', '<div class="alert alert-danger">You can\'t access last page</div>');
      redirect('dashboard');
    }

    $delete = $this->Arsip_model->get_by_id($id);

    if($delete)
    {
      $dir        = "./assets/file_arsip/".$delete->file_arsip;

      if(is_file($dir))
      {
        unlink($dir);
      }

      $this->Arsip_model->delete($id);

      write_log();

      $this->session->set_flashdata('message', '<div class="alert alert-success">Data deleted permanently</div>');
      redirect('arsip/deleted_list');
    }
    else
    {
      $this->session->set_flashdata('message', '<div class="alert alert-danger">No data found</div>');
      redirect('arsip');
    }
  }

  function deleted_list()
  {
    is_login();
    is_restore();

    if(!is_superadmin())
    {
      $this->session->set_flashdata('message', '<div class="alert alert-danger">You can\'t access last page</div>');
      redirect('dashboard');
    }

    $this->data['page_title'] = 'Deleted '.$this->data['module'].' List';

    $this->data['get_all_deleted'] = $this->Arsip_model->get_all_deleted();

    $this->load->view('back/arsip/arsip_deleted_list', $this->data);
  }

  function restore($id)
  {
    is_login();
    is_restore();

    if(!is_superadmin())
    {
      $this->session->set_flashdata('message', '<div class="alert alert-danger">You can\'t access last page</div>');
      redirect('dashboard');
    }

    $row = $this->Arsip_model->get_by_id($id);

    if($row)
    {
      $data = array(
        'is_delete'   => '0',
        'deleted_by'  => NULL,
        'deleted_at'  => NULL,
      );

      $this->Arsip_model->update($id, $data);

      write_log();

      $this->session->set_flashdata('message', '<div class="alert alert-success">Data restored successfully</div>');
      redirect('arsip/deleted_list');
    }
    else
    {
      $this->session->set_flashdata('message', '<div class="alert alert-danger">No data found</div>');
      redirect('arsip');
    }
  }

  function choose_arsip()
  {
    $this->data['arsip']  = $this->Arsip_model->get_all_combobox_pengembalian($this->uri->segment(3));
    $this->load->view('back/arsip/form_arsip', $this->data);
  }

}
