<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Kolkata');

class Admin extends CI_Controller
{
    public function __construct()
    {
        parent:: __construct();
        $this->load->library('table');
        $this->load->model('Admin_Model');
    }

    public function index($data = null)
    {
        $this->load->view('header');
        $this->load->view('login', $data);
        $this->load->view('footer');
    }

    public function login()
    {
        $this->form_validation->set_rules('email', "Email Address", "required|valid_email");
        $this->form_validation->set_rules('password', "Password", "required|trim");
        $email = $this->input->post('email');
        if ($this->form_validation->run() == true) {
            $password = $this->input->post('password');
            $loggingIn = $this->Admin_Model->login($email, $password);
            if ($loggingIn === true) {
                redirect('admin/users');
            } else {
                $this->session->set_flashdata(
                    'notify',
                    "$.notify({ allow_dismiss: true, icon: 'pe-7s-bell',
                    message:'Incorrect Email Address or Password'}, {
                                type: 'danger'
                            });"
                );
                redirect('admin/index/'.array('email'=> $email));
            }
        }
    }

    public function signup()
    {
        $this->form_validation->set_rules('user_name', 'Name', "required|trim");
        $this->form_validation->set_rules('user_email', 'Email Address', "required|valid_email");
        $this->form_validation->set_rules('user_phone', 'Phone', "required|max_length[12]|min_length[10]");
        $this->form_validation->set_rules('user_address', 'Address', "required");
        $this->form_validation->set_rules('user_gstin', 'GSTIN', "required|exact_length[15]");
        $this->form_validation->set_rules('user_city', 'City', "required|trim");
        $this->form_validation->set_rules('user_state', 'State', "required|trim");
        $this->form_validation->set_rules('manufacturer_registered_office_address', 'Registered Office Address', "required");
        $this->form_validation->set_rules('manufacturer_plant_addresses', 'Plant Address', "required");

        $formData = array(
            'user_name' => $this->input->post('user_name'),
            'user_email' => $this->input->post('user_email'),
            'user_type' => "Manufacturer",
            'user_phone' => $this->input->post('user_phone'),
            'user_address' => $this->input->post('user_address'),
            'user_gstin' => $this->input->post('user_gstin'),
            'user_city' => $this->input->post('user_city'),
            'user_state' => $this->input->post('user_state')
        );

        if ($this->form_validation->run() == true) {
            $salt = bin2hex(random_bytes(3));
            $formData['user_salt'] = $salt; 
            $password = $this->input->post('user_password');
            $formData['user_password'] = sha1($password.$salt, false);
            $creation = $this->Admin_Model->createUser($formData, $password);

            if ($creation !== false) {
                if (is_string($creation)) {
                    $this->session->set_flashdata(
                        'notify',
                        "$.notify({ allow_dismiss: true, icon: 'pe-7s-bell', message:'".$creation."'}, {
                            type: 'danger'
                        });"
                    );
                } else {
                    $otherData = array(
                        'manufacturer_public_id' => $creation['publicId'],
                        'manufacturer_registered_office_address' => $this->input->post('manufacturer_registered_office_address'),
                        'manufacturer_plant_addresses' => $this->input->post('manufacturer_plant_addresses')
                    );
                    $this->Admin_Model->saveManufacturerDetails($otherData);
                    $this->session->set_flashdata(
                        'notify',
                        "$.notify({ allow_dismiss: true, icon: 'pe-7s-bell', message:'Manufacturer Signup Successful'}, {
                            type: 'success'
                        });"
                    );
                    $this->session->set_flashdata('info', "<p class='paddingAll'>Auto-Generated Password: ".
                        $password."</p>");
                    redirect('admin');
                }
            } else {
                echo "Some Error occurred";
            }

        } else {
            $formData['manufacturer_registered_office_address'] = $this->input->post('manufacturer_registered_office_address');
            $formData['manufacturer_plant_addresses'] = $this->input->post('manufacturer_plant_addresses');
            $this->load->view('header');
            $this->load->view('signup', $formData);
            $this->load->view('footer');
        }

    }



    public function users()
    {
        if ($this->Admin_Model->isUserNotAdmin()) {
            echo "You are not Authorised";
            return;
        }

        $data['title'] = "Users";
        $users = $this->Admin_Model->getAllUsers();
        $data['table'] = $this->generateUsersTable($users);
        $data['user_types'] = $this->getTypeOptions();
        $this->view('users', $data);
    }

    public function addUser()
    {
        if ($this->Admin_Model->isUserNotAdmin()) {
            echo "You are not Authorised";
            return;
        }
        $this->form_validation->set_rules('user_name', 'Name', "required|trim");
        $this->form_validation->set_rules('user_email', 'Email Address', "required|valid_email");
        $this->form_validation->set_rules('user_phone', 'Phone', "required|max_length[12]|min_length[10]");
        $this->form_validation->set_rules('user_address', 'Address', "required|trim");
        $this->form_validation->set_rules('user_gstin', 'GSTIN', "required|exact_length[15]");
        $this->form_validation->set_rules('user_city', 'City', "required|trim");
        $this->form_validation->set_rules('user_state', 'State', "required|trim");

        $formData = array(
            'user_name' => $this->input->post('user_name'),
            'user_email' => $this->input->post('user_email'),
            'user_type' => $this->input->post('user_type'),
            'user_phone' => $this->input->post('user_phone'),
            'user_address' => $this->input->post('user_address'),
            'user_gstin' => $this->input->post('user_gstin'),
            'user_city' => $this->input->post('user_city'),
            'user_state' => $this->input->post('user_state'),
            'user_created_by' => $this->Admin_Model->getUserDetails()['user_id']
        );
        $userPublicId = $this->input->post('user_public_id');

        if ($this->form_validation->run() == true) {
            if (empty($userPublicId)) {
                $password = random_int(100000, 999999);
                $salt = bin2hex(random_bytes(3));
                $formData['user_salt'] = $salt;
                $formData['user_password'] = sha1($password.$salt, false);
                $creation = $this->Admin_Model->createUser($formData, $password);
                if ($creation !== false) {
                    if (is_string($creation)) {
                        $this->session->set_flashdata(
                            'notify',
                            "$.notify({ allow_dismiss: true, icon: 'pe-7s-bell', message:'".$creation."'}, {
                                type: 'danger'
                            });"
                        );
                        $formData['title'] = "Users";
                        $users = $this->Admin_Model->getAllUsers();
                        $formData['table'] = $this->generateUsersTable($users);
                        $this->view('users', $formData);
                    } else {
                        $this->session->set_flashdata(
                            'notify',
                            "$.notify({ allow_dismiss: true, icon: 'pe-7s-bell', message:'User Creation Successful'}, {
                                type: 'success'
                            });"
                        );
                        $this->session->set_flashdata('info', "<p class='paddingAll'>Auto-Generated Password: ".
                            $password."</p>");
                        redirect('admin/updateUser/' . $creation['publicId']);
                    }
                } else {
                    echo "Some Error occurred";
                }
            } else {
                $updation = $this->Admin_Model->updateUser($formData, $userPublicId);
                if ($updation !== false) {
                    if (is_string($updation)) {
                        $this->session->set_flashdata(
                            'notify',
                            "$.notify({ allow_dismiss: true, icon: 'pe-7s-bell', message:'".$updation."'}, {
                                type: 'danger'
                            });"
                        );
                        $formData['title'] = "Users";
                        $formData['user_public_id'] = $userPublicId;
                        $users = $this->Admin_Model->getAllUsers();
                        $formData['table'] = $this->generateUsersTable($users);
                        $this->view('users', $formData);
                    } else {
                        redirect('admin/users');
                    }
                } else {
                    echo "Some error occurred";
                }
            }
        } else {
            $formData['title'] = "Users";
            $formData['user_public_id'] = $userPublicId;
            $users = $this->Admin_Model->getAllUsers();
            $formData['table'] = $this->generateUsersTable($users);
            $this->view('users', $formData);
        }
    }

    public function updateUser($userPublicId = null)
    {
        if ($this->Admin_Model->isUserNotAdmin()) {
            echo "You are not Authorised";
            return;
        }
        if (is_null($userPublicId)) {
            $userPublicId = $this->input->post('userPublicId');
        }
        if (!empty($userPublicId)) {
            $data = $this->Admin_Model->getUser($userPublicId);
            $data['title'] = "Users";
            $users = $this->Admin_Model->getAllUsers();
            $data['table'] = $this->generateUsersTable($users);
            $data['user_types'] = $this->getTypeOptions($data['user_type']);
            $this->view('users', $data);
        } else {
            echo "Invalid User Id";
        }
    }

    public function deleteUser($userPublicId = null)
    {
        if ($this->Admin_Model->isUserNotAdmin()) {
            echo "You are not Authorised";
            return;
        }
        if (!is_null($userPublicId)) {
            if ($this->Admin_Model->deleteUser($userPublicId)) {
                redirect('admin/users');
            } else {
                echo "Some Error occurred";
            }
        } else {
            echo "Invalid user id";
        }
    }


    public function addMedicine()
    {
        if ($this->Admin_Model->isUserNotAdmin()) {
            echo "You are not Authorised";
            return;
        }

        $this->form_validation->set_rules('name', 'Name of Medicine', "required|trim");
        $this->form_validation->set_rules('ExpiryDate', 'Expiry Date', "required|trim");
        $this->form_validation->set_rules('BatchNo', 'Batch Number', "required|trim");

        if ($this->form_validation->run() == true) {
            $medicineId = $this->input->post('id');
            if (empty($medicineId)) {
                $data = array(
                    'id' => bin2hex(random_bytes(5)),
                    'name' => $this->input->post('name'),
                    'ExpiryDate' => $this->input->post('ExpiryDate'),
                    'BatchNo' => $this->input->post('BatchNo')
                );
                $creation = $this->Admin_Model->addMedicine($data);
                
                if ($creation === true) {
                    $this->session->set_flashdata(
                        'notify',
                        "$.notify({ allow_dismiss: true, icon: 'pe-7s-bell', message:'Medicine Creation Successful'}, {
                            type: 'success'
                        });"
                    );
                    $qrcode = $this->Admin_Model->generateQRCode($data);
                    $this->session->set_flashdata('qrcode', $qrcode);
                } else {
                    $this->session->set_flashdata(
                        'notify',
                        "$.notify({ allow_dismiss: true, icon: 'pe-7s-bell', message:'Medicine Creation Failed. Please Contact support'}, {
                            type: 'danger'
                        });"
                    );
                }

                $data['title'] = "Add New Medicine";
                $this->view('medicine', $data);
            } else {
                $data = array(
                    'id' => $medicineId,
                    'name' => $this->input->post('name'),
                    'ExpiryDate' => $this->input->post('ExpiryDate'),
                    'BatchNo' => $this->input->post('BatchNo')
                );

                $updation = $this->Admin_Model->updateMedicine($data);

                if ($updation === true) {
                    $this->session->set_flashdata(
                        'notify',
                        "$.notify({ allow_dismiss: true, icon: 'pe-7s-bell', message:'Medicine Updation Successful'}, {
                            type: 'success'
                        });"
                    );
                    $qrcode = $this->Admin_Model->generateQRCode($data);
                    $this->session->set_flashdata('qrcode', $qrcode);
                } else {
                    $this->session->set_flashdata(
                        'notify',
                        "$.notify({ allow_dismiss: true, icon: 'pe-7s-bell', message:'Medicine Updation Failed. Please Contact support'}, {
                            type: 'danger'
                        });"
                    );
                }
                $data['title'] = "Update Medicine";
                $data['id'] = $medicineId;
                $this->view('medicine', $data);
            }
        } else {
            $data['title'] = "Add New Medicine";
            $this->view('medicine', $data);
        }
    }

    public function editMedicine()
    {
        if ($this->Admin_Model->isUserNotAdmin()) {
            $userType = $this->session->userdata('userType');
            if ($userType == "Manufacturer") {
                echo "You are not Authorised";
                return;
            }
        }
        $medicineId = $this->input->post('medicineId');
        $data = $this->Admin_Model->getMedicineById($medicineId);
        $data['title'] = "Update Medicine";
        $data['id'] = $medicineId;
        $this->view('medicine', $data);
    }

    public function medicines()
    {
        if ($this->Admin_Model->isUserNotAdmin()) {
            echo "You are not Authorised";
            return;
        }

        $data['title'] = "Medicines";
        $medicines = $this->Admin_Model->getMedicines();
        $data['table'] = $this->generateMedicineTable($medicines);
        $this->view('medicines', $data);
    }

    public function transfer()
    {
        if ($this->Admin_Model->isUserNotAdmin()) {
            echo "You are not Authorised";
            return;
        }

        $this->form_validation->set_rules('medicineId', 'Medicine Id', "required|trim");
        $this->form_validation->set_rules('newOwner', 'New Owner', "required|trim|callback_validateUser");

        if ($this->form_validation->run() > 0) {
            $medicineId = $this->input->post('medicineId');
            $newOwnerId = $this->input->post('newOwner');
            $data = array(
                'medicine' => "org.medic.chain.Medicine#".$medicineId,
                'newOwner' => "org.medic.chain.User#".$newOwnerId
            );
            $transfer = $this->Admin_Model->transferMedicine($data);

            if ($transfer === true) {
                $this->Admin_Model->addMedicineIdToTable($medicineId, $newOwnerId);
                $this->session->set_flashdata(
                    'notify',
                    "$.notify({ allow_dismiss: true, icon: 'pe-7s-bell', message:'Medicine Transfer Successful'}, {
                        type: 'success'
                    });"
                );
            } else {
                $this->session->set_flashdata(
                    'notify',
                    "$.notify({ allow_dismiss: true, icon: 'pe-7s-bell', message:'Medicine Transfer Failed. Please Contact support'}, {
                        type: 'danger'
                    });"
                );
            }
            redirect('admin/medicines');

        } else {
            $data['title'] = "Transfer Medicine";
            $this->view('transfer', $data);
        }
    }


    public function transferHistory()
    {
        if ($this->Admin_Model->isUserNotAdmin()) {
            echo "You are not Authorised";
            return;
        }
        $medicineId = $this->input->post('medicineId');
        $history = $this->Admin_Model->getMedicineHistory($medicineId);
        $data['title'] = "Transfer History of ".$history['name'];
        $data['table'] = $this->generateHistoryTable($history['data']);
        $this->view('transferHistory', $data);
    }

    public function validateUser($userId)
    {
        $check = $this->Admin_Model->validateUserForTransfer($userId);

        if ($check == true) {
            return true;
        } else {
            $this->form_validation->set_message('validateUser', 'Invalid Owner');
            $this->session->set_flashdata(
                'notify',
                "$.notify({ allow_dismiss: true, icon: 'pe-7s-bell', message:'Enter Valid Owner Id'}, {
                    type: 'danger'
                });"
            );
            return false;
        }
    }

    public function account()
    {
        if ($this->Admin_Model->isUserNotAdmin()) {
            echo "You are not Authorised";
            return;
        }
        $data = $this->Admin_Model->getUserDetails();
        $data['title'] = "Account";
        $this->view('account', $data);
    }

    public function changePassword()
    {
        if ($this->Admin_Model->isUserNotAdmin()) {
            echo "You are not Authorised";
            return;
        }
        $this->form_validation->set_rules('password1', 'Password', 'required');
        $this->form_validation->set_rules('password2', "Password", 'required|matches[password1]');

        if ($this->form_validation->run() == true) {
            $password = $this->input->post('password1');
            if ($this->Admin_Model->changePassword($password)) {
                $this->session->set_flashdata(
                    'notify',
                    "$.notify({ allow_dismiss: true, icon: 'pe-7s-bell',
                    message:'Password Change Successful'}, {
                                type: 'success'
                            });"
                );
            } else {
                $this->session->set_flashdata(
                    'notify',
                    "$.notify({ allow_dismiss: true, icon: 'pe-7s-bell',
                    message:'Password Change Failed. Try Again'}, {
                                type: 'danger'
                            });"
                );
            }
            redirect('admin/account');
        }
    }

   
    
    private function generateUsersTable($users)
    {
        $table = array();
        if (is_array($users)) {
            foreach ($users as $user) {
                $temp = array();
                $temp['Name'] = $user['user_name'];
                $temp['Email'] = $user['user_email'];
                $temp['Type'] = $user['user_type'];

                $temp['edit'] = form_open('admin/updateUser') .
                    "<input type='hidden' name='userPublicId' value='" . $user['user_public_id'] . "'/>
                <button type='submit' class='btn btn-primary btn-fill'><i class='fa fa-pencil'></i></button>"
                    . form_close();

                //     $temp['delete'] = "<!-- Button trigger modal -->
                // <button type=\"button\" class=\"btn btn-danger btn-fill\" onclick=\"setUrl('" . $user['user_public_id'] . "')\">
                //  <i class=\"fa fa-trash\"></i></button>";

                array_push($table, $temp);
            }
        }
        $template = array(
            'table_open' => '<table id="table" border="1" cellpadding="3" cellspacing="1" class="table table-hover table-striped">'
        );
        $this->table->set_template($template);
        // if ($this->Admin_Model->isAdminNormal()) {
            $this->table->set_heading("Name", "Email Address", "User Type", "Edit");
        // } else {
            // $this->table->set_heading("Name", "Email Address", "User Type", "Edit", "Delete");
        // }
        return $this->table->generate($table);
    }

    
    private function generateMedicineTable($medicines)
    {
        $table = array();
        $userType = $this->session->userdata('userType');

        if (is_array($medicines)) {
            foreach ($medicines as $medicine) {
                $temp = array();
                $temp['Id'] = $medicine['id'];
                $temp['Name'] = $medicine['name'];
                $temp['Expiry Date'] = $medicine['ExpiryDate'];
                $temp['Batch Number'] = $medicine['BatchNo'];
                $temp['Owner Name'] = $medicine['owner'];
                

                if ($medicine['transfer'] == true) {
                    $temp['transfer'] = form_open('admin/transfer') .
                            "<input type='hidden' name='medicineId' value='" . $medicine['id'] . "'/>
                        <button type='submit' class='btn btn-primary btn-fill'><i class='fa fa-exchange'></i></button>"
                            . form_close();
                } else {
                    $temp['transfer'] = "<button type='button' class='btn btn-primary btn-fill' disabled><i class='fa fa-exchange'></i></button>";
                }

                $temp['history'] = form_open('admin/transferHistory') .
                "<input type='hidden' name='medicineId' value='" . $medicine['id'] . "'/>
            <button type='submit' class='btn btn-default btn-fill'><i class='fa fa-history'></i></button>"
                . form_close();

                if ($userType == "Manufacturer") {
                    if ($medicine['transfer'] == true) {
                        $temp['edit'] = form_open('admin/editMedicine') .
                            "<input type='hidden' name='medicineId' value='" . $medicine['id'] . "'/>
                        <button type='submit' class='btn btn-info btn-fill'><i class='fa fa-pencil'></i></button>"
                            . form_close();
                    } else {
                        $temp['edit'] = "<button type='button' class='btn btn-info btn-fill' disabled><i class='fa fa-pencil'></i></button>";
                    }
                }

                
                array_push($table, $temp);
            }
        }
        $template = array(
            'table_open' => '<table id="table" border="1" cellpadding="3" cellspacing="1" class="table table-hover table-striped">'
        );
        $this->table->set_template($template);
        
        if ($userType == "Manufacturer") {
           $this->table->set_heading("Id", "Name", "Expiry Date", "Batch Number", "Current Owner Name", "Transfer", "Transfer History", "Edit");
        } else {
            $this->table->set_heading("Id", "Name", "Expiry Date", "Batch Number", "Current Owner Name", "Transfer", "Transfer History");
        }
        return $this->table->generate($table);
    }


    private function generateHistoryTable($history)
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
    
    private function getTypeOptions($type = null)
    {
        $userType = $this->session->userdata('userType');
        if (is_null($type)) {
            $type = "";
        }
        $optionsArray = array(
            'Manufacturer' => '<option value="Manufacturer">Manufacturer</option>',
            'Distributor' => '<option value="Distributor">Distributor</option>',
            'Wholesaler' => '<option value="Wholesaler">Wholesaler</option>',
            'Retailer' => '<option value="Retailer">Retailer</option>'
            );
        if ($userType == 'Manufacturer') {
            $types = array('Distributor', 'Wholesaler', 'Retailer');
            for ($i = 0; $i < sizeof($types); $i++) {
                if ($type == $types[$i]) {
                    $tmp = $types[$i];
                    $types[$i] = $types[0];
                    $types[0] = $tmp;
                }
            }
            $options = "";
            for ($i = 0; $i < sizeof($types); $i++) {
                $options .= $optionsArray[$types[$i]];
            }   
        } elseif ($userType == 'Distributor') {
            $types = array('Wholesaler', 'Retailer');
            for ($i = 0; $i < sizeof($types); $i++) {
                if ($type == $types[$i]) {
                    $tmp = $types[$i];
                    $types[$i] = $types[0];
                    $types[0] = $tmp;
                }
            }
            $options = "";
            for ($i = 0; $i < sizeof($types); $i++) {
                $options .= $optionsArray[$types[$i]];
            }
        } elseif ($userType == 'Wholesaler') {
            $options = '<option value="Retailer">Retailer</option>';
        }
        return $options;
    }
   
    private function view($page, $data)
    {
        $this->load->view('header', $data);
        $this->load->view('sidebar', $data);
        $this->load->view($page, $data);
        $this->load->view('footer');
    }

    public function getUsers($userName = null)
    {
        if (!is_null($userName)) {
            echo $this->Admin_Model->getUsersLike($userName);
        } else {
            echo "";
        }
    }

    public function forgotPassword()
    {
        $this->form_validation->set_rules('email', "Email Address", "required|valid_email");

        if ($this->form_validation->run() == true) {
            $email = $this->input->post('email');
            if ($this->Admin_Model->forgotPassword($email)) {
                redirect('admin');
            } else {
                redirect('admin/forgotPassword');
            }
        } else {
            $this->load->view('header');
            $this->load->view('forgotPassword');
            $this->load->view('footer');
        }
    }

    public function logout()
    {
        session_destroy();
        redirect('admin');
    }

}