<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model {
    
    public function __construct(){
        parent::__construct();
        $this->load->database();
        $this->config->load('custom_config');
        $this->site_config = $this->config->item('site_config');

    }
    
    public function getUserDataById($user_id){
        $this->db = $this->load->database('moodle_db', TRUE);
        $this->db->select('mdl_user.id,mdl_user.username, mdl_user.firstname,mdl_user.lastname,mdl_user.email,mdl_user.faculty_id,f.name as faculty_name');
        $this->db->from('mdl_user');
        $this->db->join($this->site_config->app_db.'.faculties as f','f.id = mdl_user.faculty_id');
        $this->db->where('mdl_user.id',$user_id);
        return $this->db->get()->row();
    }
    
    public function getUserByCookie($session_id){
        $this->db = $this->load->database('moodle_db', TRUE);
        $this->db->select('*');
        $this->db->from('mdl_sessions');
        $this->db->where('sid',$session_id);
        return $this->db->get()->row();
    }

    public function deleteCookie($session_id){
        $this->db = $this->load->database('moodle_db', TRUE);

        $this->db->trans_start();

        $this->db->where('sid',$session_id);
        $this->db->delete('mdl_sessions');

        $this->db->trans_complete();

        return $this->db->trans_status();

    }

    public function getAdmins(){
        $this->db = $this->load->database('moodle_db', TRUE);
        $this->db->select('value');
        $this->db->where('name','siteadmins');
        return $this->db->get('mdl_config')->row();
    }
}