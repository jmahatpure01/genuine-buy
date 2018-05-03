<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Kolkata');

use CodeItNow\BarcodeBundle\Utils\QrCode;

class Admin_Model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    
    public function login($email, $password)
    {
        $this->db->where('user_email', $email);
        $query = $this->db->get('users');
        if ($query->num_rows() > 0) {
            $queryResult = $query->row_array();
            $saltPassword = sha1($password.$queryResult['user_salt'], false);
            if ($saltPassword == $queryResult['user_password']) {
                $this->session->set_userdata('sessionData', $queryResult['user_public_id']);
                $this->session->set_userdata('userType', $queryResult['user_type']);
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function changePassword($password)
    {
        $publicId = $this->session->userdata('sessionData');
        $this->db->where('user_public_id', $publicId);
        $query = $this->db->get('users');
        if ($query->num_rows() > 0) {
            $queryResult = $query->row_array();
            $newPassword = sha1($password.$queryResult['user_salt'], false);
            $this->db->where('user_public_id', $publicId);
            if ($this->db->update('users', array('user_password' => $newPassword))) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function getAll($tableName)
    {
        $query = $this->db->get($tableName);
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    public function add($tableName, $name, $tablePrefix)
    {
        if ($this->db->insert($tableName, array($tablePrefix."name" => $name))) {
            return true;
        } else {
            return false;
        }
    }

    public function delete($tablePrefix, $id)
    {
        $tableName = "";
        if ($tablePrefix == "category") {
            $tableName = "categories";
        } elseif ($tablePrefix == "tag") {
            $tableName = "tags";
        }
        $this->deleteFromBlogPosts($tablePrefix, $id, $tableName);
        $this->db->where($tablePrefix."_id", $id);
        if ($this->db->delete($tableName)) {
            return true;
        } else {
            return false;
        }
    }


    public function getAllUsers()
    {
        $userData = $this->getUserDetails();
        $this->db->where('user_created_by', $userData['user_id']);
        $query = $this->db->get('users');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    public function getUserDetails()
    {
        $publicId = $this->session->userdata('sessionData');
        $this->db->where('user_public_id', $publicId);
        $query = $this->db->get('users');
        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return array();
        }
    }

    public function getUser($userPublicId)
    {
        $this->db->where('user_public_id', $userPublicId);
        $query = $this->db->get('users');
        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return false;
        }
    }

    
    public function createUser($data, $password)
    {
        $this->db->where('user_email', $data['user_email']);
        $query = $this->db->get('users');
        if ($query->num_rows() > 0) {
            return "A user with this Email Address already present";
        } else {
            $data['user_public_id'] = $this->generateUniquePublicId('users', 'user_public_id');

            if ($this->db->insert('users', $data)) {
                $this->addParticipant($data);
                $subject = "Account Credentials";
                $html = "<p>Your Account has been successfully created. Your login credentials are:</p>
                <p>Email: ".$data['user_email']."</p>
                <p>Password: ".$password."</p>";
                //$this->sendMail($data['user_email'], $subject, $html);
                return array('publicId'=> $data['user_public_id']);
            } else {
                return false;
            }
        }
    }

    public function updateUser($data, $publicId)
    {
        $this->db->where('user_public_id', $publicId);
        $query = $this->db->get('users');
        if ($query->num_rows() > 0) {
            $queryResult = $query->row_array();
            if ($data['user_email'] != $queryResult['user_email']) {
                //email address send mail with password
                $this->db->where('user_email', $data['user_email']);
                $query = $this->db->get('users');
                if ($query->num_rows() > 0) {
                    return "A user with this Email Address already present";
                } else {
                    $this->db->where('user_public_id', $publicId);
                    if ($this->db->update('users', $data)) {
                        return true;
                    } else {
                        return false;
                    }
                }
            } else {
                $this->db->where('user_public_id', $publicId);
                if ($this->db->update('users', $data)) {
                    $this->updateParticipant($data);
                    return true;
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
    }

    public function deleteUser($publicId)
    {
        $this->db->where('user_public_id', $publicId);
        if ($this->db->delete('users')) {
            return true;
        } else {
            return false;
        }
    }



    public function saveManufacturerDetails($data)
    {
        $this->db->insert('manufacturer_details',$data);
    }



    public function getMedicines()
    {
        $userId = $this->getUserDetails()['user_id'];
        $this->db->where('user_id', $userId);
        $query = $this->db->get('user_medicines');
        if ($query->num_rows() > 0) {
            $queryResult = $query->result_array();
            $resultArray = array();
            foreach ($queryResult as $medicine) {
                $temp = $this->getMedicineById($medicine['user_medicine_id']);
                $ownerPublicId = substr($temp['owner'], -6);
                $ownerName = $this->getUser($ownerPublicId)['user_name'];
                $temp['owner'] = $ownerName;
                $userPublicId = $this->session->userdata('sessionData');
                if ($userPublicId == $ownerPublicId) {
                    $temp['transfer'] = true;
                } else {
                    $temp['transfer'] = false;
                }
                array_push($resultArray, $temp);
            }
            return $resultArray;
        }
    }

    public function getMedicineHistory($medicineId)
    {
        $transactionIds = $this->getTransactionsByMedicine($medicineId);
        if ($transactionIds !== false && is_array($transactionIds)) {
            $resultArray = array();
            $resultArray['name'] = $this->getMedicineById($medicineId)['name'];
            $resultArray['data'] = array();
            foreach($transactionIds as $transaction) {
                $tempId = $transaction['transactionId'];
                $tempData = $this->getHistoryByTransactionId($tempId);
                array_push($resultArray['data'], $tempData['eventsEmitted'][0]);
            }
            return $resultArray;
        } else {
            return false;
        }
    }
    
    
    private function getNameFromId($tableName, $tablePrefix, $id)
    {
        $this->db->where($tablePrefix.'_id', $id);
        $query = $this->db->get($tableName);
        if ($query->num_rows() > 0) {
            $queryResult = $query->row_array();
            return $queryResult[$tablePrefix.'_name'];
        } else {
            return false;
        }
    }

    public function generateUniquePublicId($tableName, $publicIdColumn)
    {
        $publicId = bin2hex(random_bytes(3));

        $this->db->where($publicIdColumn, $publicId);
        $query = $this->db->get($tableName);
        $result = $query->row_array();

        if ($result) {
            $this->generateUniquePublicId($tableName, $publicIdColumn);
        } else {
            return $publicId;
        }
    }

    public function forgotPassword($email)
    {
        $this->db->where('user_email', $email);
        $query = $this->db->get('users');
        if ($query->num_rows() > 0) {
            $queryResult = $query->row_array();
            $salt = bin2hex(random_bytes(3));
            $password = random_int(100000, 999999);
            $newPassword = sha1($password.$salt, false);
            $this->db->where('user_public_id', $queryResult['user_public_id']);
            if ($this->db->update('users', array('user_salt'=> $salt, 'user_password' => $newPassword))) {
                $subject = "Password Recovery";
                $html = "<p>Your new Auto-Generated Password is: ".$password."</p>";
                //$this->sendMail($email, $subject, $html);
                $this->session->set_flashdata(
                    'notify',
                    "$.notify({ allow_dismiss: true, icon: 'pe-7s-bell',
                    message:'A Mail has been sent to you with the new auto-generated password.'}, {
                                type: 'success'
                            });"
                );
                return true;
            } else {
                return false;
            }
        } else {
            $this->session->set_flashdata(
                'notify',
                "$.notify({ allow_dismiss: true, icon: 'pe-7s-bell', message:'Incorrect Email Address'}, {
                                type: 'danger'
                            });"
            );
            return false;
        }
    }

    public function isUserNotAdmin()
    {
        $publicId = $this->session->userdata('sessionData');
        if (!empty($publicId) && is_string($publicId)) {
            $this->db->where('user_public_id', $publicId);
            $query = $this->db->get('users');
            if ($query->num_rows() > 0) {
                $queryResult = $query->row_array();
                $this->session->set_userdata('userType', $queryResult['user_type']);
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }

    public function getUsersLike($string)
    {
        $userId = $this->getUserDetails()['user_id'];
        $this->db->like('user_name', $string);
        $this->db->where('user_created_by', $userId);
        $query = $this->db->get('users');
        if ($query->num_rows() > 0) {
            $queryResult = $query->result_array();
            return $this->generateList($queryResult, 'user_');
        } else {
            return false;
        }
    }

    public function addMedicineIdToTable($id, $userId = null)
    {
        if (is_null($userId)) {
            $publicId = $this->session->userdata('sessionData');
        } else {
            $publicId = $userId;
        }
        
        $userId = $this->getUser($publicId)['user_id'];
        $this->db->insert('user_medicines', array('user_medicine_id' => $id, 'user_id' => $userId));
    }

    private function generateList($data, $tablePrefix)
    {
        $html = "<ul type='none' class='list-group'>";
        foreach ($data as $row) {
            $html .= "<li class='list-group-item' onclick=\"setOwner('" . $row[$tablePrefix . 'public_id'] . "')\">" .
                    $row[$tablePrefix . 'name'] . "</li>";
        }
        $html .= "</ul>";
        return $html;
    }

    public function validateUserForTransfer($id)
    {
        $userId = $this->getUserDetails()['user_id'];
        $this->db->where('user_public_id', $id);
        $this->db->where('user_created_by', $userId);
        $query = $this->db->get('users');
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    public function sendMail($to, $subject, $html)
    {
        $this->load->library('email');
        $config['useragent'] = 'Genuine Buy';
        $config['protocol'] = 'smtp';
        $config['smtp_crypto'] =  'ssl';
        $config['smtp_host'] =  '';
        $config['smtp_user'] =  '';
        $config['smtp_pass'] = '';
        $config['smtp_port'] = '465';
        $config['charset'] = 'UTF8';
        $config['mailtype'] = 'html';
        $this->email->set_newline("\r\n");
        $this->email->initialize($config);
        $this->from = 'Genuine Buy';
        $this->from_email = 'genuine-buy';
        $this->email->from($this->from_email, $this->from);
        $this->email->reply_to($this->from, $this->from_email);
        $this->email->to($to);

        $this->email->subject($subject);

        $this->email->message($html);


        if (! $this->email->send()) {
            echo show_error($this->email->print_debugger())."<br>";
        }
    }


    /****************************************************************************************BLOCKCHAIN API CALLS ************************************/


    private function addParticipant($data)
    {
        $client = new GuzzleHttp\Client();
            $res = $client->request('POST', 'localhost:3000/api/User', [ 'json' => ['$class' => "org.medic.chain.User",
                'id' => $data['user_public_id'], 'name' => $data['user_name'], 'type' => $data['user_type']]]);

            $code = $res->getStatusCode();
            //$result1 = json_decode($res->getBody(), true);
            if ($code == 200) {
                return true;
            } else {
                return false;
            }
    }

    private function updateParticipant($data)
    {
        $client = new GuzzleHttp\Client();
            $res = $client->request('PUT', 'localhost:3000/api/User/'.$data['user_public_id'], [ 'json' => ['$class' => "org.medic.chain.User",
                'id' => $data['user_public_id'], 'name' => $data['user_name'], 'type' => $data['user_type']]]);

            $code = $res->getStatusCode();
            //$result1 = json_decode($res->getBody(), true);
            if ($code == 200) {
                return true;
            } else {
                return false;
            }
    }

    public function addMedicine($data)
    {
        $this->addMedicineIdToTable($data['id']);

        $userId = $this->session->userdata('sessionData');

        $client = new GuzzleHttp\Client();
            $res = $client->request('POST', 'localhost:3000/api/Medicine', [ 'json' => ['$class' => "org.medic.chain.Medicine",
                'id' => $data['id'], 'owner' => "org.medic.chain.User#".$userId, 'name' => $data['name'], 'ExpiryDate' => $data['ExpiryDate'], 'BatchNo' => $data['BatchNo']]]);

            $code = $res->getStatusCode();
            //$result1 = json_decode($res->getBody(), true);
            if ($code == 200) {
                return true;
            } else {
                return false;
            }
    }

    public function updateMedicine($data)
    {
        $userId = $this->session->userdata('sessionData');

        $client = new GuzzleHttp\Client();
            $res = $client->request('PUT', 'localhost:3000/api/Medicine/'.$data['id'], [ 'json' => ['$class' => "org.medic.chain.Medicine",
                'id' => $data['id'], 'owner' => "org.medic.chain.User#".$userId, 'name' => $data['name'], 'ExpiryDate' => $data['ExpiryDate'], 'BatchNo' => $data['BatchNo']]]);

            $code = $res->getStatusCode();
            //$result1 = json_decode($res->getBody(), true);
            if ($code == 200) {
                return true;
            } else {
                return false;
            }
    }

    public function getMedicineById($id)
    {
        $client = new GuzzleHttp\Client();
            $res = $client->request('GET', 'localhost:3000/api/Medicine/'.$id);

            $code = $res->getStatusCode();
            if ($code == 200) {
                $result = json_decode($res->getBody(), true);
                return $result;
            } else {
                return false;
            }   
    }

    public function transferMedicine($data)
    {
        $client = new GuzzleHttp\Client();
            $res = $client->request('POST', 'localhost:3000/api/Transfer', [ 'json' => ['$class' => "org.medic.chain.Transfer",
                'medicine' => $data['medicine'], 'newOwner' => $data['newOwner']]]);

            $code = $res->getStatusCode();
            //$result1 = json_decode($res->getBody(), true);
            if ($code == 200) {
                return true;
            } else {
                return false;
            }
    }

    private function getTransactionsByMedicine($medicineId)
    {
        $client = new GuzzleHttp\Client();
            $res = $client->request('GET', 'localhost:3000/api/queries/selectTransfersByMedicine', ['query' => ['medicine' => "resource:org.medic.chain.Medicine#".$medicineId]]);

            $code = $res->getStatusCode();
            if ($code == 200) {
                $result = json_decode($res->getBody(), true);
                return $result;
            } else {
                return false;
            }   
    }

    private function getHistoryByTransactionId($id)
    {
        $client = new GuzzleHttp\Client();
            $res = $client->request('GET', 'localhost:3000/api/system/historian/'.$id);

            $code = $res->getStatusCode();
            if ($code == 200) {
                $result = json_decode($res->getBody(), true);
                return $result;
            } else {
                return false;
            }   
    }


    /*******************************************************QR CODE GENERATOR ****************************************************************************/

    public function generateQRCode($data)
    {
        $qrCode = new QrCode();
        $qrCode
            ->setText(json_encode($data))
            ->setSize(300)
            ->setPadding(10)
            ->setErrorCorrection('high')
            ->setForegroundColor(array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0))
            ->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0))
            ->setImageType(QrCode::IMAGE_TYPE_PNG)
        ;
        return '<a class="btn btn-default" download="qrcode.png" href="data:'.$qrCode->getContentType().';base64,'.$qrCode->generate().'">Download QR Code</a>';
    }
}



