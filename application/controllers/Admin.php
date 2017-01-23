<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller 
{

	function __construct()
	{
		parent::__construct();

		$this->load->library('session');
		$this->load->model('Admin_CRUD');
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
	}

	public function index()
	{
		$this->login();
	}

	public function login($value='')
	{
		$admin = $this->session->userdata('admin');
		
		if (!empty($admin)) 
		{
			redirect(site_url('admin/dashboard'));
		}
		else
		{
			$this->load->view('admin/production/login');
		}
	}

	public function login_validation()
	{
		if ($this->input->post()) 
		{
			$username = $this->input->post('username');
			$password = $this->input->post('password');

			$result = $this->Admin_CRUD->user_validation($username, $password);

			if (count($result) > 0) 
			{
				$id = $result->id;
				$this->session->set_userdata('admin_id',$id);
				$this->session->set_userdata('admin',$username);
				redirect(site_url('admin/dashboard'));
			}
			else
			{
				redirect(site_url('admin/login'));
			}
		}
		else
		{
			redirect(site_url());
		}
	}

	public function dashboard($value='')
	{
		$admin = $this->session->userdata('admin');

		if (!empty($admin)) 
		{
			$this->load->view('admin/common/header');
			$this->load->view('admin/production/index');
			$this->load->view('admin/common/footer');
		}
		else
		{
			redirect(site_url('admin/login'));
		}
	}

	public function delete_patients($id='')
	{
		$admin = $this->session->userdata('admin');
		if (!empty($admin)) 
		{
			$result = $this->Admin_CRUD->delete_record('patients','patient_id',$id);
			$result = $this->Admin_CRUD->delete_record('tests','test_patient_id',$id);
			$result = $this->Admin_CRUD->delete_record('report_tests','patient_id',$id);
			
			if ($result) 
			{
				$this->session->set_flashdata('info_message', 'Record Deleted Succsesfully !');
				redirect(site_url('admin/manage_patients'));
			}
			else
			{
				$this->session->set_flashdata('error_message', 'Could not update data! Error occured');
				redirect(site_url('admin/manage_patients'));
			}
		}
		else
		{
			redirect(site_url('admin/login'));
		}
	}

	public function edit_patients($id='')
	{
		$admin = $this->session->userdata('admin');
		if (!empty($admin)) 
		{
			if (!empty($this->input->post())) 
			{
				$id 		= $this->input->post('patient_id');
				$first_name = $this->input->post('first-name');
				$last_name  = $this->input->post('last-name');
				$phone      = $this->input->post('phone-number');
				$gender 	= $this->input->post('gender');
				$age 		= $this->input->post('age');
				$passcode   = $this->input->post('passcode');

				$update = array(
					'patient_firstname' 	=> $first_name,
					'patient_lastname' 		=> $last_name,
					'patient_phone_number'  => $phone,
					'patient_gender' 		=> $gender,
					'patient_passcode'		=> $passcode,
					'patient_age'			=> $age
					);
				$result = $this->Admin_CRUD->update_record('patients','patient_id',$id,$update);
				
				if ($result) 
				{
					$where = array('patient_id' => $id);
					$data['patient'] = $this->Admin_CRUD->get_record_by_id('patients',$where);
					$this->session->set_flashdata('success_message', 'Record Updated Succsesfully !');
					redirect(site_url('admin/edit_patients/'.$id));
				}
				else
				{
					$where = array('patient_id' => $id);
					$data['patient'] = $this->Admin_CRUD->get_record_by_id('patients',$where);
					$this->session->set_flashdata('error_message', 'Could not update data! Error occured');
					redirect(site_url('admin/edit_patients/'.$id));
				}
			}
			else
			{
				$where = array('patient_id' => $id);
				$data['patient'] = $this->Admin_CRUD->get_record_by_id('patients',$where);
				// $data['patient'] = $this->db->get_where('patients',$arrayName = array('patient_id' => $id ))->row();
				$this->load->view('admin/common/header');
				$this->load->view('admin/production/patients/view_edit_patients',$data);	
			}
		}
		else
		{
			redirect(site_url('admin/login'));
		}
	}

	public function manage_patients($value='')
	{
		$admin = $this->session->userdata('admin');
		if (!empty($admin)) 
		{
			$data['patients']   = $this->Admin_CRUD->get_all_data('patients','patient_id');
			// $data['admin']		= $this->Admin_CRUD->get_current_user($admin);
			$this->load->view('admin/common/header');
			$this->load->view('admin/production/patients/view_all_patients',$data);
			// $this->load->view('admin/common/footer');
		}
		else
		{
			redirect(site_url('admin/login'));
		}
	}

	function randomNumber() 
	{
	    $result = '';

	    for($i = 0; $i < 8; $i++) 
	    {
	        $result .= mt_rand(0, 9);
	    }

	    return $result;
	}

	public function add_patients($value='')
	{
		$admin = $this->session->userdata('admin');
		if (!empty($admin)) 
		{
			if ($this->input->post()) 
			{

				$this->form_validation->set_rules('first-name',	'First Name', 'required');
				$this->form_validation->set_rules('last-name', 'Last Name', 'required');
				$this->form_validation->set_rules('phone-number', 'Phone Number', 'required');
				$this->form_validation->set_rules('gender', 'Gender', 'required');
				$this->form_validation->set_rules('dob', 'Date of Birth', 'required');

				if ($this->form_validation->run() == FALSE)
	            {
					$this->load->view('admin/common/header');
					$this->load->view('admin/production/patients/view_add_patients');
	            }
	            else
	            {

					$patient_firstname = $this->input->post('first-name');
					$patient_lastname  = $this->input->post('last-name');
					$patient_phone	   = $this->input->post('phone-number');
					$patient_gender	   = $this->input->post('gender');
					$patient_dob	   = $this->input->post('dob');
					$admin_id 		   = $this->session->userdata('admin_id');

					$patient_dob = date_create($patient_dob);
					$patient_dob = date_format($patient_dob,"Y-m-d");

					$patient_age = date_diff(date_create($patient_dob), date_create('today'))->y;


					$patient = array(
						'patient_firstname' 	=> $patient_firstname,
						'patient_lastname'  	=> $patient_lastname,
						'patient_gender'		=> $patient_gender,
						'patient_age'			=> $patient_age,
						'patient_phone_number'  => $patient_phone,
						'admin_id'				=> $admin_id 
					);

					$result = $this->Admin_CRUD->add_record('patients',$patient);
					
					if (count($result) > 0) 
					{
						$this->session->set_flashdata('success_message', 'Patient Succsesfully Added !');
						redirect(site_url('admin/manage_patients'));	
					}
					else
					{
						$this->session->set_flashdata('error_message', "Error, Couldn't add this patient. Please try again!");
						$this->load->view('admin/common/header');
						$this->load->view('admin/production/patients/view_add_patients');
					}
	            }
			}
			else
			{
				$this->load->view('admin/common/header');
				$this->load->view('admin/production/patients/view_add_patients');
				// $this->load->view('admin/common/footer');
			}
		}
		else
		{
			redirect(site_url('admin/login'));
		}
	}

	public function add_report($value='')
	{
		$admin = $this->session->userdata('admin');
		$admin_id = $this->session->userdata('admin_id');
		if (!empty($admin)) 
		{
			if ($this->input->post()) 
			{
				$patient_id   = $this->input->post('patient-id');
				$report_name  = $this->input->post('report_name');
				$test_ids     = $this->input->post('tests_id');
				$date_created = date("Y-m-d");

				$last_report  = 0;
				$last_test	  = 0;

				$patient_pass_code  = $this->randomNumber();

				$patient_p_c = array('patient_passcode' => $patient_pass_code );
				$patient_update = $this->Admin_CRUD->update_record('patients','patient_id',$patient_id,$patient_p_c);

				$report_record = array(
					'report_name' 		  => $report_name,
					'report_date_created' => $date_created,
					'admin_id'			  => $admin_id );

				$report_result = $this->Admin_CRUD->add_record('reports',$report_record);

				if (count($report_result) > 0) 
				{
					$last_report = $this->db->insert_id();
				}

				$total  = count($test_ids);
				$result = 0;

				for($i = 0; $i < $total; $i++)
				{

					$report_test_result = array(
					'report_id'   => $last_report,
					'patient_id'  => $patient_id,	
					'test_id' 	  => $test_ids[$i] );

					$result = $this->Admin_CRUD->add_record('report_tests',$report_test_result);
				}

				if (count($result) > 0) 
				{
					$this->session->set_flashdata('success_message',"Report Succsesfully added !");
					redirect(site_url('admin/manage_reports'));
				}
				else
				{
					$this->session->set_flashdata('error_message',"Couldn't add report, error occured. Please try again!");
					redirect(site_url('admin/add_report'));
				}
			}
			else
			{
				$data['patients'] = $this->Admin_CRUD->get_all_data('patients','patient_id');
				// $data['tests']    = $this->Admin_CRUD->get_all_data_by_join();
				$this->load->view('admin/common/header');
				$this->load->view('admin/production/reports/view_add_reports',$data);
				// $this->load->view('admin/common/footer');
			}
		}
		else
		{
			redirect(site_url('admin/login'));
		}
	}

	public function edit_report($id)
	{
		$admin = $this->session->userdata('admin');
		if (!empty($admin)) 
		{
			if (empty($id)) 
			{
				redirect(site_url('admin/manage_reports'));
			}
			
			if ($this->input->post()) 
			{
				$patient_id   = $this->input->post('patient-id');
				$report_name  = $this->input->post('report_name');
				$test_ids     = $this->input->post('tests_id');

				$last_report  = 0;
				$last_test	  = 0;

				$report_record = array(
					'report_name' 		  => $report_name
					);
				$report_result = $this->Admin_CRUD->update_record('reports','report_id',$id,$report_record);
				if (count($report_result) > 0) 
				{
					$last_report = $id;

					$where = array('report_id' => $id );
					$get_test_num_records = $this->Admin_CRUD->get_all_data_where('report_tests',$where);

					if (count($get_test_num_records)>0) 
					{
						$where = array('report_id' => $id );
						$delete_record_against_report = $this->Admin_CRUD->delete_multiple_rows('report_tests',$where);

						if (count($delete_record_against_report) > 0) 
						{

							$total  = count($test_ids);
							$result = 0;

							for($i = 0; $i < $total; $i++)
							{
								$report_test_result = array(
								'report_id'   => $id,
								'patient_id'  => $patient_id,	
								'test_id' 	  => $test_ids[$i] 
								);

								$result = $this->Admin_CRUD->add_record('report_tests',$report_test_result);

							}

							if (count($result) > 0) 
							{
								$this->session->set_flashdata('success_message',"Report Succsesfully Updated !!!");
								redirect(site_url('admin/manage_reports'));
							}
							else
							{
								$this->session->set_flashdata('error_message',"Couldn't udpate report, error occured. Please try again!");
								redirect(site_url('admin/edit_report/'.$id));
							}
						}
						else
						{
							$this->session->set_flashdata('error_message',"Couldn't Delte Reports");
							redirect(site_url('admin/edit_report/'.$id));
						}
					}
					elseif (count($get_test_num_records) == 0) 
					{
						$total  = count($test_ids);
						$result = 0;

						for($i = 0; $i < $total; $i++)
						{
							$report_test_result = array(
							'report_id'   => $last_report,
							'patient_id'  => $patient_id,	
							'test_id' 	  => $test_ids[$i] 
							);

							$result = $this->Admin_CRUD->add_record('report_tests',$report_test_result);
						}

						if (count($result) > 0) 
						{
							$this->session->set_flashdata('success_message',"Report Succsesfully Updated !!");
							redirect(site_url('admin/manage_reports'));
						}
						else
						{
							$this->session->set_flashdata('error_message',"Couldn't udpate report, error occured. Please try again!");
							redirect(site_url('admin/edit_report/'.$id));
						}
					}
					else
					{
							$this->session->set_flashdata('error_message',"Couldn't Update Record.");
							redirect(site_url('admin/manage_reports'));

					}

				}
				else
				{
					$this->session->set_flashdata('success_message',"Error in updating Report !");
					redirect(site_url('admin/edit_report'.$id));
				}

			}

			$where = array('report_id' => $id );
			$data['report']    	= $this->Admin_CRUD->get_record_by_id('reports',$where);
			$data['patient_id'] = $this->Admin_CRUD->get_record_by_id('report_tests',$where);
			$data['patients']	= $this->Admin_CRUD->get_all_data('patients','patient_id');
			$data['report_id']  = $id;

			$data['tests_reports_patient'] = $this->Admin_CRUD->join_tests_reports();

			$this->load->view('admin/common/header');
			$this->load->view('admin/production/reports/view_edit_reports',$data);

		}
		else
		{
			redirect(site_url('admin/login'));
		}
	}

	public function manage_reports()
	{
		$admin = $this->session->userdata('admin');
		if (!empty($admin)) 
		{
			$data['reports']   = $this->Admin_CRUD->get_all_data('reports','report_id');
			$data['tests_reports_patient'] = $this->Admin_CRUD->join_tests_reports();

			$this->load->view('admin/common/header');
			$this->load->view('admin/production/reports/view_all_reports',$data);
		}
		else
		{
			redirect(site_url('admin/login'));
		}
	}

	public function add_test()
	{
		$admin = $this->session->userdata('admin');
		$admin_id = $this->session->userdata('admin_id');

		if (!empty($admin)) 
		{
			if ($this->input->post()) 
			{
				$this->form_validation->set_rules('test-name',	'Test Name', 'required');
				$this->form_validation->set_rules('testof[]', 'Name of Conent', 'required');
				$this->form_validation->set_rules('normal_value[]', 'Normal value', 'required');
				$this->form_validation->set_rules('unit[]', 'Unit', 'required');
				$this->form_validation->set_rules('result[]', 'Result', 'required');

				if ($this->form_validation->run() == FALSE)
	            {
					$data['patients']   = $this->Admin_CRUD->get_all_data('patients','patient_id');
					$this->load->view('admin/common/header');
					$this->load->view('admin/production/tests/view_add_test',$data);
					// $this->load->view('admin/common/footer');
	            }
	            else
	            {

					$patient_id 	= $this->input->post('patient_id');
					$test_name  	= $this->input->post('test-name');
					$testof     	= $this->input->post('testof');
					$normal_value	= $this->input->post('normal_value');
					$unit			= $this->input->post('unit');
					$test_result	= $this->input->post('result');
					$date_created   = date("Y-m-d");

					$test_record = array(
						'test_name' 		=> $test_name,
						'test_patient_id'   => $patient_id,
						'test_date_created' => $date_created,
						'admin_id'			=> $admin_id );

					$result  = $this->Admin_CRUD->add_record('tests',$test_record);
					$test_id = $this->db->insert_id();
					
					if (count($result) > 0) 
					{
						$total 	 = count($testof);
						for($i = 0; $i < $total; $i++)
						{
							if ( $normal_value[$i] != '' || $testof[$i] != '' || $test_result[$i] != '' || $unit[$i] != '') 
							{
								// add all fields against the test
								$fields = array(
									'field_test_id'	  	=> $test_id,
									'field_name' 	  	=> $testof[$i],
									'field_normal_value'=> $normal_value[$i],
									'field_unit'    	=> $unit[$i],
									'field_result' 		=> $test_result[$i]
									);
								$query = $this->Admin_CRUD->add_record('fields',$fields);
							}	
						}
						if (count($query) > 0) 
						{
							$this->session->set_flashdata('success_message',"Patient Test Succsesfully Added !");
							redirect(site_url('admin/manage_tests'));
						}
					}
					else
					{
						$this->session->set_flashdata('error_message',"Couldn't insert tests data, Please try again!");
					}
	            }	
			}
			else
			{
				$data['patients']   = $this->Admin_CRUD->get_all_data('patients','patient_id');
				$this->load->view('admin/common/header');
				$this->load->view('admin/production/tests/view_add_test',$data);
			}
		}
		else
		{
			redirect(site_url('admin/login'));
		}

	}

	public function delete_reports($id)
	{
		if (empty($id)) 
		{
			redirect(site_url());
		}

		$admin = $this->session->userdata('admin');
		if (!empty($admin)) 
		{
			$result = $this->Admin_CRUD->delete_multiple_rows('reports',array('report_id' => $id ));
			
			$get_test_id = $this->Admin_CRUD->get_record_by_id('report_tests',array('report_id' => $id ));
			$get_test_id = $get_test_id->test_id;

			$result = $this->Admin_CRUD->delete_multiple_rows('tests',array('test_id' => $get_test_id));
			$result1 = $this->Admin_CRUD->delete_multiple_rows('report_tests', array('report_id' => $id ));

			if ($result) 
			{
				$this->session->set_flashdata('success_message',"Test Deleted!");
				redirect(site_url('admin/manage_reports'));
			}
			else
			{
				$this->session->set_flashdata('error_message',"Couldn't delete record. ");
				redirect(site_url('admin/manage_reports'));
			}
		}
	}

	public function delete_tests($id)
	{
		if (empty($id)) 
		{
			redirect(site_url());
		}
		$admin = $this->session->userdata('admin');
		if (!empty($admin)) 
		{
			$result = $this->Admin_CRUD->delete_multiple_rows('tests',array('test_id' => $id ));
			$fields = $this->Admin_CRUD->delete_multiple_rows('fields', array('field_test_id' => $id ));
			if ($result) 
			{
				$this->session->set_flashdata('success_message',"Test Deleted!");
				redirect(site_url('admin/manage_tests'));
			}
		}
	}

	public function edit_tests($id)
	{
		$admin = $this->session->userdata('admin');
		if (!empty($admin)) 
		{
			if ($this->input->post()) 
			{

				$patient_id 	= $this->input->post('patient_id');
				$test_name  	= $this->input->post('test-name');
				$testof     	= $this->input->post('testof');
				$normal_value	= $this->input->post('normal_value');
				$unit			= $this->input->post('unit');
				$test_result	= $this->input->post('result');

				$test_record = array(
					'test_name' 		=> $test_name,
					'test_patient_id'   => $patient_id 
					);

				$where = array('field_test_id' => $id );
				$get_num_test_fields = $this->Admin_CRUD->get_record_by_id('fields',$where);

				$result  = $this->Admin_CRUD->update_record('tests','test_id',$id,$test_record);

				if (count($get_num_test_fields) > 0) 
				{
					$where = array('field_test_id' => $id );
					$delete_tests = $this->Admin_CRUD->delete_multiple_rows('fields',$where);
				}

				
				if (count($result) > 0) 
				{
					$total 	 = count($testof);
					for($i = 0; $i < $total; $i++)
					{
						if ( $normal_value[$i] != '' || $testof[$i] != '' || $test_result[$i] != '' || $unit[$i] != '') 
						{
							// add all fields against the test
							$fields = array(
								'field_test_id'	  	=> $id,
								'field_name' 	  	=> $testof[$i],
								'field_normal_value'=> $normal_value[$i],
								'field_unit'    	=> $unit[$i],
								'field_result' 		=> $test_result[$i]
								);
							$query = $this->Admin_CRUD->add_record('fields',$fields);
						}	
					}
					if (count($query) > 0) 
					{
						$this->session->set_flashdata('success_message',"Patient Updated Succsesfully Updated !");
						redirect(site_url('admin/manage_tests'));
					}
				}
				else
				{
					$this->session->set_flashdata('error_message',"Couldn't Update tests data, Please try again!");
					redirect(site_url('admin/manage_tests/'.$id));
				}
			}

			$data['patients']   	= $this->Admin_CRUD->get_all_data('patients','patient_id');
			
			$where = array('test_id' => $id );
			$data['test']	= $this->Admin_CRUD->get_record_by_id('tests',$where);

			$where = array('field_test_id' => $id );
			$data['tests_fields'] = $this->Admin_CRUD->get_all_data_where('fields',$where);
			$data['test_id'] 	  = $id;

			$this->load->view('admin/common/header');
			$this->load->view('admin/production/tests/view_edit_tests',$data);
		}
		else
		{
			redirect(site_url('admin/login'));
		}
	}

	public function manage_tests()
	{
		$admin = $this->session->userdata('admin');
		if (!empty($admin)) 
		{
			$data['tests']   = $this->Admin_CRUD->get_all_data('tests','test_id');
			$data['tests_fields_patient'] = $this->Admin_CRUD->join_tests_fields_data();
			$data['patient_name']		  = $this->Admin_CRUD->join_patients_tests_data();
			$this->load->view('admin/common/header');
			$this->load->view('admin/production/tests/view_all_tests',$data);
			// $this->load->view('admin/common/footer');
		}
		else
		{
			redirect(site_url('admin/login'));
		}

	}

	public function get_selected_record_by_id($id)
	{
		$admin = $this->session->userdata('admin');
		if (!empty($admin)) 
		{
			$get = $this->input->post('get');
			if (!empty($get)) 
			{
				$report_id = $this->input->post('report_id');
				
				$where = array('report_id' => $report_id );
				$selected_reports = $this->Admin_CRUD->get_all_data_where('report_tests',$where);

				$id = $this->input->post('id');
				$where = array(
					'test_patient_id' => $id );
				$query = $this->Admin_CRUD->get_all_data_where('tests',$where);

	            if($query)
	            {
	                $list   =   '<option value="">Select</option>';
	                foreach ($query as $row)
	                {
	                	$selected = '';
	                	foreach ($selected_reports as $value) 
	                	{
		                    if ($value->test_id == $row->test_id) 
		                    {
		                    	$selected = 'selected';
		                    }
	                	}

	                	$list   .=  "<option value='".$row->test_id."' $selected>".$row->test_name."</option>";
	                }
	                echo $list;
	            }

	            else
	            {
	                echo "<option value=''>No Test Found</option>";
	            }
			}
		}
		else
		{
			redirect(site_url('admin/login'));
		}
	}

	public function get_record_by_id($id)
	{
		$admin = $this->session->userdata('admin');
		if (!empty($admin)) 
		{
			$get = $this->input->post('get');
			if (!empty($get)) 
			{
				$id = $this->input->post('id');
				$where = array(
					'test_patient_id' => $id );
				$query = $this->Admin_CRUD->get_all_data_where('tests',$where);

	            if($query)
	            {
	                $list   =   '<option value="">Select</option>';
	                foreach ($query as $row)
	                {
	                    $list   .=  "<option value='".$row->test_id."' >".$row->test_name."</option>";
	                }
	                echo $list;
	            }

	            else
	            {
	                echo "<option value=''>No Test Found</option>";
	            }
			}
		}
		else
		{
			redirect(site_url('admin/login'));
		}
	}

	public function logout()
	{
		$this->session->unset_userdata('admin');
		$this->session->sess_destroy();

		redirect(site_url('admin/login'));
	}
}
