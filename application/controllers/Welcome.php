<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function __construct()
    {
        parent:: __construct();
        $this->load->library('table');
        $this->load->model('Admin_Model');
    } 

	public function index()
	{
		$this->load->view('welcome_message');
	}

	public function searchMedicine($data = null)
	{
		$this->load->view('header');
        $this->load->view('searchMedicine', $data);
        $this->load->view('footer');
	}

	public function displayResult()
	{
		$this->form_validation->set_rules('medicineId', "Medicine Id", "required|exact_length[10]");
		$medicineId = $this->input->post('medicineId');
		if ($this->form_validation->run() == true) {
			$data['details'] = $this->Admin_Model->getMedicineById($medicineId);
			$history = $this->Admin_Model->getMedicineHistory($medicineId);
			$data['table'] = $this->generateMedicineTable($history['data']);
			$this->load->view('header');
			$this->load->view('medicineDetails', $data);
			$this->load->view('footer');
		}  else {
			$this->session->set_flashdata(
				'notify',
				"$.notify({ allow_dismiss: true, icon: 'pe-7s-bell',
				message:'Invalid Medicine Id'}, {
							type: 'danger'
						});"
			);
			redirect('welcome/searchMedicine/'.array('medicineId'=> $medicineId));
		}
	}

	private function generateMedicineTable($history)
	{
		$table = array();

        if (is_array($history)) {
            foreach ($history as $item) {
                $temp = array();
                $temp['oldOwner'] = $this->Admin_Model->getUser(substr($item['oldOwner'], -6))['user_name'];
                $temp['newOwner'] = $this->Admin_Model->getUser(substr($item['newOwner'], -6))['user_name'];
                $date = date_create($item['timestamp']);
                $temp['timestamp'] = date_format($date, "Y-m-d H:i:s");

                
                array_push($table, $temp);
            }
        }
        $template = array(
            'table_open' => '<table id="table" border="1" cellpadding="3" cellspacing="1" class="table table-hover table-striped">'
        );
        $this->table->set_template($template);
        
        
        $this->table->set_heading("Old Owner", "New Owner", "TimeStamp");
        
        return $this->table->generate($table);
	}
}
