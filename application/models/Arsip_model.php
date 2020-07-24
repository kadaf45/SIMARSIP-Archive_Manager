<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Arsip_model extends CI_Model{

  public $table = 'arsip';
  public $id    = 'id_arsip';
  public $order = 'DESC';

  function get_all()
  {
    $this->db->join('rak', 'arsip.rak_id = rak.id_rak');
    $this->db->join('baris', 'arsip.baris_id = baris.id_baris');
    $this->db->where('is_delete', '0');
    $this->db->order_by($this->id, $this->order);
    return $this->db->get($this->table)->result();
  }

  function get_all_combobox()
  {
    $this->db->order_by('arsip_name');
    $data = $this->db->get($this->table);

    if($data->num_rows() > 0)
    {
      foreach($data->result_array() as $row)
      {
        $result[''] = '- Please Choose Arsip';
        $result[$row['id_arsip']] = $row['arsip_name'];
      }
      return $result;
    }
  }

  function get_all_combobox_pengembalian($id)
  {
    $this->db->join('peminjaman', 'arsip.id_arsip = peminjaman.arsip_id');
    $this->db->where('id_peminjaman', $id);
    $this->db->order_by('arsip_name');
    $data = $this->db->get($this->table);

    if($data->num_rows() > 0)
    {
      foreach($data->result_array() as $row)
      {
        $result[''] = '- Please Choose Kode Peminjaman';
        $result[$row['id_arsip']] = $row['arsip_name'];
      }
      return $result;
    }
  }

  function get_all_deleted()
  {
    $this->db->join('rak', 'arsip.rak_id = rak.id_rak');
    $this->db->join('baris', 'arsip.baris_id = baris.id_baris');
    $this->db->where('is_available', '0');
    $this->db->where('is_delete', '1');
    $this->db->order_by($this->id, $this->order);
    return $this->db->get($this->table)->result();
  }

  function get_by_id($id)
  {
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

  function soft_delete($id,$data)
  {
    $this->db->where($this->id, $id);
    $this->db->update($this->table, $data);
  }

  function delete($id)
  {
    $this->db->where($this->id, $id);
    $this->db->delete($this->table);
  }

}
