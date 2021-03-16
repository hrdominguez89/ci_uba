<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Faculties_model extends CI_Model {
    
    public function __construct()
	{
        parent::__construct();
        $this->load->database();
    }
    
    public function getFaculties($where = false, $order_by = false){
        $this->db->select('id,name');
        if($where){
            $this->db->where($where);
        }
        if($order_by){
            $this->db->order_by($order_by);
        }else{
            $this->db->order_by('name');
        }
        return $this->db->get('faculties')->result();
    }

    public function getFacultyById($faculty_id){
        $this->db->select('id,name');
        $this->db->where('id', $faculty_id);
        return $this->db->get('faculties')->row();
    }
}