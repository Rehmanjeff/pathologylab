<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_CRUD extends CI_Model 
{

	function user_validation($username,$password)
	{
		$password = sha1($password);
		$where = array(
			'username' => $username,
			'password' => $password );

		$query = $this->db->get_where('admin',$where);
		
		if ($query->num_rows() > 0) 
		{
			return $query->row();
		}
		else
		{
			return FALSE;
		}
	}


	function add_record($db,$data)
	{
		$query = $this->db->insert($db,$data);
		
		if ($query) 
		{
			return $query;
		}
		else
		{
			return FALSE;
		}
	}

	function delete_multiple_rows($db_table,$where)
	{
		$result = $this->db->delete($db_table, $where);
		if ($result) 
		{
			return $result;
		}
		else
		{
			return FALSE;
		}
	}

	function get_all_data($table,$order)
	{
		$admin_id = $this->session->userdata('admin_id');
		$where = array('admin_id' => $admin_id );
		$this->db->order_by($order,'DESC');
		$query = $this->db->get_where($table,$where);

		if ($query->num_rows() > 0) 
		{
			return $query->result();
		}
	}

	function get_all_data_where($db,$where)
	{
		$query = $this->db->get_where($db, $where);
		
		if ($query->num_rows() > 0) 
		{
			$query = $query->result();
			return $query;
		}
		else
		{
			return FALSE;
		}
	}

	function get_record_by_id($db,$where)
	{
		$query = $this->db->get_where($db, $where);
		
		if ($query->num_rows() > 0) 
		{
			$query = $query->row();
			return $query;
		}
		else
		{
			return FALSE;
		}
	}

	function delete_record($db,$column,$id)
	{
		$this->db->where($column,$id);
		$query = $this->db->delete($db);
		if ($query) 
		{
			return $query;
		}
		else
		{
			return FALSE;
		}
	}

	function update_record($tbl,$tbl_column,$id,$where)
	{
		$this->db->where($tbl_column,$id);
		$result = $this->db->update($tbl,$where);
		
		if ($result) 
		{
			return $result;
		}
		else
		{
			return FALSE;
		}
	}

	function join_tests_reports()
	{
		$this->db->select('*');    
		$this->db->from('report_tests');
		$this->db->join('reports', 'report_tests.report_id = reports.report_id');
		$this->db->join('tests', 'report_tests.test_id = tests.test_id');
		$this->db->join('fields', 'tests.test_id = fields.field_test_id');
		$query = $this->db->get();
		if ($query) 
		{
			return $query->result();
		}
		else
		{
			return FALSE;
		}
	}

	function join_patients_tests_data()
	{
		$this->db->select('*');    
		$this->db->from('tests');
		$this->db->join('patients', 'tests.test_patient_id = patients.patient_id');
		$query = $this->db->get();
		if ($query) 
		{
			return $query->result();
		}
		else
		{
			return FALSE;
		}
	}

	function join_tests_fields_data()
	{
		$this->db->select('*');    
		$this->db->from('tests');
		$this->db->join('fields', 'tests.test_id = fields.field_test_id');
		$query = $this->db->get();
		if ($query) 
		{
			return $query->result();
		}
		else
		{
			return FALSE;
		}
	}

	/*function get_current_user($id='')
	{
		$query = $this->db->get_where('admin', array('id' => $id ));
		
		if ($query->num_rows > 0) 
		{
			return $query->row();
		}
		else
		{
			return FALSE;
		}
	}*/
	
}
