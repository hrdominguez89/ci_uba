<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Platforms_model extends CI_Model {
    
    public function __construct(){
        parent::__construct();
        $this->load->database();
    }
    
    public function getPlatforms(){
        $this->db->select('id,name,url');
        $this->db->order_by('name');
        return $this->db->get('platforms')->result();
    }
    
    public function getPlatformById($platform_id){
        $this->db->select('id,name,url');
        $this->db->where('id', $platform_id);
        return $this->db->get('platforms')->row();
    }

    public function insertPlatform($data_platform){
        // Iniciamos Rollback
		$this->db->trans_begin();

		$this->db->insert('platforms', $data_platform);
        $platform_inserted = $this->db->insert_id();
	
		// Condicional del Rollback 
		if ($this->db->trans_status() === FALSE){      
			//Hubo errores en la consulta, entonces se cancela la transacción.   
				$this->db->trans_rollback();  
				return FALSE;    
		}else{      
			//Todas las consultas se hicieron correctamente.  
				$this->db->trans_commit();   
				return $platform_inserted; 
		}//If Rollback
    }

    public function updatePlatform($data_platform,$platform_id){
        $this->db->trans_start();

        $this->db->where('id',$platform_id);
        $this->db->update('platforms',$data_platform);

        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    public function deletePlatform($platform_id){
        $this->db->trans_start();

        $this->db->where('id',$platform_id);
        $this->db->delete('platforms');

        $this->db->trans_complete();

        return $this->db->trans_status();
    }
}