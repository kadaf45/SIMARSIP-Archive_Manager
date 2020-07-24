<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Log_model extends CI_Model{

  public $table = 'log_queries';
  public $id    = 'id';
  public $order = 'DESC';

  function get_all()
  {
    $this->db->order_by($this->id, $this->order);
    return $this->db->get($this->table)->result();
  }

  function total_rows()
  {
    return $this->db->get($this->table)->num_rows();
  }

}
