<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_model extends CI_Model {
    
    public function __construct(){
        parent::__construct();
        $this->load->database();
        
        $this->config->load('custom_config');
        $this->site_config = $this->config->item('site_config');
    }
    
    public function getUsers(){
        $this->db = $this->load->database('moodle_db', TRUE);
        $this->db->select('u.id,u.username, u.firstname,u.lastname,u.email,u.suspended,u.faculty_id,f.name as faculty_name');
        $this->db->from('mdl_user as u');
        $this->db->join($this->site_config->app_db.'.faculties as f','f.id = u.faculty_id');
        $this->db->order_by('u.firstname');
        $this->db->order_by('u.lastname');
        $this->db->where('u.deleted !=',1);
        return $this->db->get()->result();
    }

    public function getUserById($user_id){

        $this->db = $this->load->database('moodle_db', TRUE);
        $this->db->select('u.id,u.username, u.firstname,u.lastname,u.email,u.suspended,u.faculty_id,f.name as faculty_name');
        $this->db->from('mdl_user as u');
        $this->db->join($this->site_config->app_db.'.faculties as f','f.id = u.faculty_id');
        $this->db->order_by('u.firstname');
        $this->db->order_by('u.lastname');
        $this->db->where('u.id',$user_id);
        return $this->db->get()->row();
    }

    public function getUserByWhere($key_where,$value_where){
        $this->db = $this->load->database('moodle_db', TRUE);
        $this->db->select('*');
        $this->db->where($key_where,$value_where);
        return $this->db->get('mdl_user')->row();
    }

    public function insertUser($user_data){
        $this->db = $this->load->database('moodle_db', TRUE);
        $this->db->trans_begin();

		$this->db->insert('mdl_user', $user_data);
        $insert_id = $this->db->insert_id();

        // Condicional del Rollback 
		if ($this->db->trans_status() === FALSE){      
			//Hubo errores en la consulta, entonces se cancela la transacción.   
            $this->db->trans_rollback();  
            return FALSE;    
		}else{      
			//Todas las consultas se hicieron correctamente.  
            $this->db->trans_commit();   
            return $insert_id;
		}//If Rollback
    }

    public function updateAdminSite($data){
        $this->db = $this->load->database('moodle_db', TRUE);

        $this->db->trans_start();

        $this->db->where('name','siteadmins');
        $this->db->update('mdl_config',$data);

        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    public function updateUser($user_data,$user_id){

        $this->db = $this->load->database('moodle_db', TRUE);
        $this->db->trans_start();

        $this->db->where('id',$user_id);
        $this->db->update('mdl_user',$user_data);

        $this->db->trans_complete();

        return $this->db->trans_status();
    }
}