<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Peminjaman_model extends CI_Model{

  public $table = 'peminjaman';
  public $id    = 'id_peminjaman';
  public $order = 'DESC';

  function get_all()
  {
    $this->db->join('users', 'peminjaman.user_id = users.id_users');
    $this->db->join('arsip', 'peminjaman.arsip_id = arsip.id_arsip');
    return $this->db->get($this->table)->result();
  }

  function get_all_periode($tgl_awal, $tgl_akhir)
  {
    $this->db->join('users', 'peminjaman.user_id = users.id_users');
    $this->db->join('arsip', 'peminjaman.arsip_id = arsip.id_arsip');
    $this->db->where('peminjaman.created_at >=', $tgl_awal);
    $this->db->where('peminjaman.created_at <=', $tgl_akhir);
    return $this->db->get($this->table)->result();
  }

  function get_all_combobox()
  {
    $this->db->order_by('kode_peminjaman');
    $data = $this->db->get($this->table);

    if($data->num_rows() > 0)
    {
      foreach($data->result_array() as $row)
      {
        $result[''] = '- Please Choose Peminjaman';
        $result[$row['id_peminjaman']] = $row['kode_peminjaman'];
      }
      return $result;
    }
  }

  function get_by_id($id)
  {
    $this->db->join('users', 'peminjaman.user_id = users.id_users');
    $this->db->join('arsip', 'peminjaman.arsip_id = arsip.id_arsip');
    $this->db->where($this->id, $id);
    return $this->db->get($this->table)->row();
  }

  function total_rows()
  {
    return $this->db->get($this->table)->num_rows();
  }

  function insert($data)
  {
    $this->db->insert($this->table, $data);
  }

  function update($id,$data)
  {
    $this->db->where($this->id, $id);
    $this->db->update($this->table, $data);
  }

  function delete($id)
  {
    $this->db->where($this->id, $id);
    $this->db->delete($this->table);
  }

  function lock_account($id,$data)
  {
    $this->db->where('username', $id);
    $this->db->update($this->table, $data);
  }

  // login attempt
  function get_total_login_attempts_per_user($id)
  {
    $this->db->where('username', $id);
    return $this->db->get('login_attempts')->num_rows();
  }

  function insert_login_attempt($data)
  {
    $this->db->insert('login_attempts', $data);
  }

  function clear_login_attempt($id)
  {
    $this->db->where('username', $id);
    $this->db->delete('login_attempts');
  }

}
