<?php 
/**
 * Account Page Controller
 * @category  Controller
 */
class AccountController extends SecureController{
	function __construct(){
		parent::__construct(); 
		$this->tablename = "users";
	}
	/**
		* Index Action
		* @return null
		*/
	function index(){
		$db = $this->GetModel();
		$rec_id = $this->rec_id = USER_ID; //get current user id from session
		$db->where ("nim", $rec_id);
		$tablename = $this->tablename;
		$fields = array("nama_lengap", 
			"nim", 
			"users_name", 
			"email", 
			"kontak", 
			"prodi", 
			"ktm", 
			"level", 
			"account_status");
		$user = $db->getOne($tablename , $fields);
		if(!empty($user)){
			$page_title = $this->view->page_title = "My Account";
			$this->render_view("account/view.php", $user);
		}
		else{
			$this->set_page_error();
			$this->render_view("account/view.php");
		}
	}
	/**
     * Update user account record with formdata
	 * @param $formdata array() from $_POST
     * @return array
     */
	function edit($formdata = null){
		$request = $this->request;
		$db = $this->GetModel();
		$rec_id = $this->rec_id = USER_ID;
		$tablename = $this->tablename;
		 //editable fields
		$fields = $this->fields = array("nama_lengap","nim","prodi","kontak","users_name","image","ktm","level","account_status");
		if($formdata){
			$postdata = $this->format_request_data($formdata);
			$this->rules_array = array(
				'nama_lengap' => 'required',
				'nim' => 'required|numeric',
				'prodi' => 'required',
				'kontak' => 'required',
				'users_name' => 'required',
				'image' => 'required',
				'ktm' => 'required',
				'level' => 'required',
				'account_status' => 'required',
			);
			$this->sanitize_array = array(
				'nama_lengap' => 'sanitize_string',
				'nim' => 'sanitize_string',
				'prodi' => 'sanitize_string',
				'kontak' => 'sanitize_string',
				'users_name' => 'sanitize_string',
				'image' => 'sanitize_string',
				'ktm' => 'sanitize_string',
				'level' => 'sanitize_string',
				'account_status' => 'sanitize_string',
			);
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			//Check if Duplicate Record Already Exit In The Database
			if(isset($modeldata['nim'])){
				$db->where("nim", $modeldata['nim'])->where("nim", $rec_id, "!=");
				if($db->has($tablename)){
					$this->view->page_error[] = $modeldata['nim']." Already exist!";
				}
			}
			//Check if Duplicate Record Already Exit In The Database
			if(isset($modeldata['users_name'])){
				$db->where("users_name", $modeldata['users_name'])->where("nim", $rec_id, "!=");
				if($db->has($tablename)){
					$this->view->page_error[] = $modeldata['users_name']." Already exist!";
				}
			} 
			if($this->validated()){
				$db->where("users.nim", $rec_id);;
				$bool = $db->update($tablename, $modeldata);
				$numRows = $db->getRowCount(); //number of affected rows. 0 = no record field updated
				if($bool && $numRows){
					$this->set_flash_msg("Record updated successfully", "success");
					$db->where ("id_users", $rec_id);
					$user = $db->getOne($tablename , "*");
					set_session("user_data", $user);// update session with new user data
					return $this->redirect("account");
				}
				else{
					if($db->getLastError()){
						$this->set_page_error();
					}
					elseif(!$numRows){
						//not an error, but no record was updated
						$this->set_flash_msg("No record updated", "warning");
						return	$this->redirect("account");
					}
				}
			}
		}
		$db->where("users.nim", $rec_id);;
		$data = $db->getOne($tablename, $fields);
		$page_title = $this->view->page_title = "My Account";
		if(!$data){
			$this->set_page_error();
		}
		return $this->render_view("account/edit.php", $data);
	}
	/**
     * Change account email
     * @return BaseView
     */
	function change_email($formdata = null){
		if($formdata){
			$email = trim($formdata['email']);
			$db = $this->GetModel();
			$rec_id = $this->rec_id = USER_ID; //get current user id from session
			$tablename = $this->tablename;
			$db->where ("nim", $rec_id);
			$result = $db->update($tablename, array('email' => $email ));
			if($result){
				$this->set_flash_msg("Email address changed successfully", "success");
				$this->redirect("account");
			}
			else{
				$this->set_page_error("Email not changed");
			}
		}
		return $this->render_view("account/change_email.php");
	}
}
