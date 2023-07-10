<?php 
/**
 * Pemgembalian Page Controller
 * @category  Controller
 */
class PemgembalianController extends SecureController{
	function __construct(){
		parent::__construct();
		$this->tablename = "pemgembalian";
	}
	/**
     * List page records
     * @param $fieldname (filter record by a field) 
     * @param $fieldvalue (filter field value)
     * @return BaseView
     */
	function index($fieldname = null , $fieldvalue = null){
		$request = $this->request;
		$db = $this->GetModel();
		$tablename = $this->tablename;
		$fields = array("pemgembalian.id_pengembalian", 
			"pemgembalian.id_sewa", 
			"pemgembalian.nim", 
			"users.nama_lengap AS users_nama_lengap", 
			"pemgembalian.tgl_pengembalian", 
			"pemgembalian.keterangan", 
			"pemgembalian.status_pengembalian");
		$pagination = $this->get_pagination(25); // get current pagination e.g array(page_number, page_limit)
		//search table record
		if(!empty($request->search)){
			$text = trim($request->search); 
			$search_condition = "(
				pemgembalian.id_pengembalian LIKE ? OR 
				pemgembalian.id_sewa LIKE ? OR 
				pemgembalian.id_sepeda LIKE ? OR 
				pemgembalian.nim LIKE ? OR 
				pemgembalian.tgl_pengembalian LIKE ? OR 
				pemgembalian.keterangan LIKE ? OR 
				pemgembalian.status_pengembalian LIKE ?
			)";
			$search_params = array(
				"%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%"
			);
			//setting search conditions
			$db->where($search_condition, $search_params);
			 //template to use when ajax search
			$this->view->search_template = "pemgembalian/search.php";
		}
		$db->join("users", "pemgembalian.nim = users.nim", "INNER");
		if(!empty($request->orderby)){
			$orderby = $request->orderby;
			$ordertype = (!empty($request->ordertype) ? $request->ordertype : ORDER_TYPE);
			$db->orderBy($orderby, $ordertype);
		}
		else{
			$db->orderBy("pemgembalian.id_pengembalian", ORDER_TYPE);
		}
		$allowed_roles = array ('administrator');
		if(!in_array(strtolower(USER_ROLE), $allowed_roles)){
		$db->where("pemgembalian.nim", get_active_user('nim') );
		}
		if($fieldname){
			$db->where($fieldname , $fieldvalue); //filter by a single field name
		}
		$tc = $db->withTotalCount();
		$records = $db->get($tablename, $pagination, $fields);
		$records_count = count($records);
		$total_records = intval($tc->totalCount);
		$page_limit = $pagination[1];
		$total_pages = ceil($total_records / $page_limit);
		$data = new stdClass;
		$data->records = $records;
		$data->record_count = $records_count;
		$data->total_records = $total_records;
		$data->total_page = $total_pages;
		if($db->getLastError()){
			$this->set_page_error();
		}
		$page_title = $this->view->page_title = "Pemgembalian";
		$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
		$this->view->report_title = $page_title;
		$this->view->report_layout = "report_layout.php";
		$this->view->report_paper_size = "A4";
		$this->view->report_orientation = "portrait";
		$this->render_view("pemgembalian/list.php", $data); //render the full page
	}
	/**
     * View record detail 
	 * @param $rec_id (select record by table primary key) 
     * @param $value value (select record by value of field name(rec_id))
     * @return BaseView
     */
	function view($rec_id = null, $value = null){
		$request = $this->request;
		$db = $this->GetModel();
		$rec_id = $this->rec_id = urldecode($rec_id);
		$tablename = $this->tablename;
		$fields = array("pemgembalian.id_pengembalian", 
			"pemgembalian.tgl_pengembalian", 
			"pemgembalian.keterangan", 
			"pemgembalian.status_pengembalian", 
			"pemgembalian.id_sewa", 
			"penyewaan.id_sewa AS penyewaan_id_sewa", 
			"pemgembalian.id_sepeda", 
			"pemgembalian.nim", 
			"users.nama_lengap AS users_nama_lengap", 
			"penyewaan.id_sewa AS penyewaan_id_sewa", 
			"penyewaan.id_sepeda AS penyewaan_id_sepeda", 
			"data_sepeda.merk_sepeda AS data_sepeda_merk_sepeda", 
			"penyewaan.ketersediaan AS penyewaan_ketersediaan", 
			"penyewaan.tgl_mulai AS penyewaan_tgl_mulai", 
			"penyewaan.tgl_akhir AS penyewaan_tgl_akhir", 
			"penyewaan.status AS penyewaan_status", 
			"penyewaan.terlambat AS penyewaan_terlambat", 
			"penyewaan.pinalti AS penyewaan_pinalti", 
			"penyewaan.tgl_booking AS penyewaan_tgl_booking", 
			"penyewaan.nim AS penyewaan_nim");
		$allowed_roles = array ('administrator');
		if(!in_array(strtolower(USER_ROLE), $allowed_roles)){
		$db->where("pemgembalian.nim", get_active_user('nim') );
		}
		if($value){
			$db->where($rec_id, urldecode($value)); //select record based on field name
		}
		else{
			$db->where("pemgembalian.id_pengembalian", $rec_id);; //select record based on primary key
		}
		$db->join("users", "pemgembalian.nim = users.nim", "INNER");
		$db->join("data_sepeda", "penyewaan.id_sepeda = data_sepeda.id_sepeda", "INNER");
		$db->join("penyewaan", "pemgembalian.nim = penyewaan.nim", "INNER ");  
		$record = $db->getOne($tablename, $fields );
		if($record){
			$page_title = $this->view->page_title = "View  Pemgembalian";
		$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
		$this->view->report_title = $page_title;
		$this->view->report_layout = "report_layout.php";
		$this->view->report_paper_size = "A4";
		$this->view->report_orientation = "portrait";
		}
		else{
			if($db->getLastError()){
				$this->set_page_error();
			}
			else{
				$this->set_page_error("No record found");
			}
		}
		return $this->render_view("pemgembalian/view.php", $record);
	}
	/**
     * Insert new record to the database table
	 * @param $formdata array() from $_POST
     * @return BaseView
     */
	function add($formdata = null){
		if($formdata){
			$db = $this->GetModel();
			$tablename = $this->tablename;
			$request = $this->request;
			//fillable fields
			$fields = $this->fields = array("id_sewa","nim","id_sepeda","tgl_pengembalian","keterangan","status_pengembalian");
			$postdata = $this->format_request_data($formdata);
			$this->rules_array = array(
				'id_sewa' => 'required',
				'nim' => 'required',
				'id_sepeda' => 'required',
				'keterangan' => 'required',
				'status_pengembalian' => 'required',
			);
			$this->sanitize_array = array(
				'id_sewa' => 'sanitize_string',
				'nim' => 'sanitize_string',
				'id_sepeda' => 'sanitize_string',
				'keterangan' => 'sanitize_string',
				'status_pengembalian' => 'sanitize_string',
			);
			$this->filter_vals = true; //set whether to remove empty fields
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			$modeldata['tgl_pengembalian'] = date_now();
			if($this->validated()){
				$rec_id = $this->rec_id = $db->insert($tablename, $modeldata);
				if($rec_id){
					$this->set_flash_msg("Record added successfully", "success");
					return	$this->redirect("pemgembalian");
				}
				else{
					$this->set_page_error();
				}
			}
		}
		$page_title = $this->view->page_title = "Add New Pemgembalian";
		$this->render_view("pemgembalian/add.php");
	}
	/**
     * Update table record with formdata
	 * @param $rec_id (select record by table primary key)
	 * @param $formdata array() from $_POST
     * @return array
     */
	function edit($rec_id = null, $formdata = null){
		$request = $this->request;
		$db = $this->GetModel();
		$this->rec_id = $rec_id;
		$tablename = $this->tablename;
		 //editable fields
		$fields = $this->fields = array("id_sewa","id_pengembalian","nim","id_sepeda","tgl_pengembalian","keterangan","status_pengembalian");
		if($formdata){
			$postdata = $this->format_request_data($formdata);
			$this->rules_array = array(
				'id_sewa' => 'required',
				'nim' => 'required',
				'id_sepeda' => 'required',
				'keterangan' => 'required',
				'status_pengembalian' => 'required',
			);
			$this->sanitize_array = array(
				'id_sewa' => 'sanitize_string',
				'nim' => 'sanitize_string',
				'id_sepeda' => 'sanitize_string',
				'keterangan' => 'sanitize_string',
				'status_pengembalian' => 'sanitize_string',
			);
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			$modeldata['tgl_pengembalian'] = date_now();
			if($this->validated()){
		$allowed_roles = array ('administrator');
		if(!in_array(strtolower(USER_ROLE), $allowed_roles)){
		$db->where("pemgembalian.nim", get_active_user('nim') );
		}
				$db->where("pemgembalian.id_pengembalian", $rec_id);;
				$bool = $db->update($tablename, $modeldata);
				$numRows = $db->getRowCount(); //number of affected rows. 0 = no record field updated
				if($bool && $numRows){
					$this->set_flash_msg("Record updated successfully", "success");
					return $this->redirect("pemgembalian");
				}
				else{
					if($db->getLastError()){
						$this->set_page_error();
					}
					elseif(!$numRows){
						//not an error, but no record was updated
						$page_error = "No record updated";
						$this->set_page_error($page_error);
						$this->set_flash_msg($page_error, "warning");
						return	$this->redirect("pemgembalian");
					}
				}
			}
		}
		$allowed_roles = array ('administrator');
		if(!in_array(strtolower(USER_ROLE), $allowed_roles)){
		$db->where("pemgembalian.nim", get_active_user('nim') );
		}
		$db->where("pemgembalian.id_pengembalian", $rec_id);;
		$data = $db->getOne($tablename, $fields);
		$page_title = $this->view->page_title = "Edit  Pemgembalian";
		if(!$data){
			$this->set_page_error();
		}
		return $this->render_view("pemgembalian/edit.php", $data);
	}
	/**
     * Delete record from the database
	 * Support multi delete by separating record id by comma.
     * @return BaseView
     */
	function delete($rec_id = null){
		Csrf::cross_check();
		$request = $this->request;
		$db = $this->GetModel();
		$tablename = $this->tablename;
		$this->rec_id = $rec_id;
		//form multiple delete, split record id separated by comma into array
		$arr_rec_id = array_map('trim', explode(",", $rec_id));
		$db->where("pemgembalian.id_pengembalian", $arr_rec_id, "in");
		$allowed_roles = array ('administrator');
		if(!in_array(strtolower(USER_ROLE), $allowed_roles)){
		$db->where("pemgembalian.nim", get_active_user('nim') );
		}
		$bool = $db->delete($tablename);
		if($bool){
			$this->set_flash_msg("Record deleted successfully", "success");
		}
		elseif($db->getLastError()){
			$page_error = $db->getLastError();
			$this->set_flash_msg($page_error, "danger");
		}
		return	$this->redirect("pemgembalian");
	}
	/**
     * List page records
     * @param $fieldname (filter record by a field) 
     * @param $fieldvalue (filter field value)
     * @return BaseView
     */
	function user_penegembalian($fieldname = null , $fieldvalue = null){
		$request = $this->request;
		$db = $this->GetModel();
		$tablename = $this->tablename;
		$fields = array("pemgembalian.id_pengembalian", 
			"pemgembalian.id_sewa", 
			"penyewaan.id_sewa AS penyewaan_id_sewa", 
			"pemgembalian.nim", 
			"users.nama_lengap AS users_nama_lengap", 
			"pemgembalian.tgl_pengembalian", 
			"pemgembalian.keterangan", 
			"pemgembalian.status_pengembalian");
		$pagination = $this->get_pagination(25); // get current pagination e.g array(page_number, page_limit)
		//search table record
		if(!empty($request->search)){
			$text = trim($request->search); 
			$search_condition = "(
				pemgembalian.id_pengembalian LIKE ? OR 
				pemgembalian.id_sewa LIKE ? OR 
				pemgembalian.id_sepeda LIKE ? OR 
				pemgembalian.nim LIKE ? OR 
				pemgembalian.tgl_pengembalian LIKE ? OR 
				pemgembalian.keterangan LIKE ? OR 
				pemgembalian.status_pengembalian LIKE ? OR 
				penyewaan.id_sewa LIKE ? OR 
				penyewaan.id_sepeda LIKE ? OR 
				penyewaan.ketersediaan LIKE ? OR 
				penyewaan.tgl_mulai LIKE ? OR 
				penyewaan.tgl_akhir LIKE ? OR 
				penyewaan.status LIKE ? OR 
				penyewaan.terlambat LIKE ? OR 
				penyewaan.pinalti LIKE ? OR 
				penyewaan.tgl_booking LIKE ? OR 
				penyewaan.nim LIKE ?
			)";
			$search_params = array(
				"%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%"
			);
			//setting search conditions
			$db->where($search_condition, $search_params);
			 //template to use when ajax search
			$this->view->search_template = "pemgembalian/search.php";
		}
		$db->join("users", "pemgembalian.nim = users.nim", "INNER");
		$db->join("penyewaan", "pemgembalian.nim = penyewaan.nim", "INNER");
		if(!empty($request->orderby)){
			$orderby = $request->orderby;
			$ordertype = (!empty($request->ordertype) ? $request->ordertype : ORDER_TYPE);
			$db->orderBy($orderby, $ordertype);
		}
		else{
			$db->orderBy("pemgembalian.id_pengembalian", ORDER_TYPE);
		}
		$allowed_roles = array ('administrator');
		if(!in_array(strtolower(USER_ROLE), $allowed_roles)){
		$db->where("pemgembalian.nim", get_active_user('nim') );
		}
		if($fieldname){
			$db->where($fieldname , $fieldvalue); //filter by a single field name
		}
		$tc = $db->withTotalCount();
		$records = $db->get($tablename, $pagination, $fields);
		$records_count = count($records);
		$total_records = intval($tc->totalCount);
		$page_limit = $pagination[1];
		$total_pages = ceil($total_records / $page_limit);
		$data = new stdClass;
		$data->records = $records;
		$data->record_count = $records_count;
		$data->total_records = $total_records;
		$data->total_page = $total_pages;
		if($db->getLastError()){
			$this->set_page_error();
		}
		$page_title = $this->view->page_title = "Pemgembalian";
		$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
		$this->view->report_title = $page_title;
		$this->view->report_layout = "report_layout.php";
		$this->view->report_paper_size = "A4";
		$this->view->report_orientation = "portrait";
		$this->render_view("pemgembalian/user_penegembalian.php", $data); //render the full page
	}
}
