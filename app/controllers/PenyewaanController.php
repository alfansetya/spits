<?php 
/**
 * Penyewaan Page Controller
 * @category  Controller
 */
class PenyewaanController extends SecureController{
	function __construct(){
		parent::__construct();
		$this->tablename = "penyewaan";
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
		$fields = array("penyewaan.id_sewa", 
			"penyewaan.tgl_booking", 
			"penyewaan.nim", 
			"users.nama_lengap AS users_nama_lengap", 
			"penyewaan.id_sepeda", 
			"data_sepeda.merk_sepeda AS data_sepeda_merk_sepeda", 
			"penyewaan.tgl_mulai", 
			"penyewaan.tgl_akhir", 
			"penyewaan.status", 
			"penyewaan.terlambat", 
			"penyewaan.pinalti", 
			"pinalti AS total_denda");
		$pagination = $this->get_pagination(25); // get current pagination e.g array(page_number, page_limit)
		//search table record
		if(!empty($request->search)){
			$text = trim($request->search); 
			$search_condition = "(
				penyewaan.id_sewa LIKE ? OR 
				penyewaan.tgl_booking LIKE ? OR 
				penyewaan.nim LIKE ? OR 
				penyewaan.id_sepeda LIKE ? OR 
				penyewaan.ketersediaan LIKE ? OR 
				penyewaan.tgl_mulai LIKE ? OR 
				penyewaan.tgl_akhir LIKE ? OR 
				penyewaan.status LIKE ? OR 
				penyewaan.terlambat LIKE ? OR 
				penyewaan.pinalti LIKE ? OR 
				pinalti LIKE ?
			)";
			$search_params = array(
				"%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%"
			);
			//setting search conditions
			$db->where($search_condition, $search_params);
			 //template to use when ajax search
			$this->view->search_template = "penyewaan/search.php";
		}
		$db->join("users", "penyewaan.nim = users.nim", "INNER");
		$db->join("data_sepeda", "penyewaan.id_sepeda = data_sepeda.id_sepeda", "INNER");
		if(!empty($request->orderby)){
			$orderby = $request->orderby;
			$ordertype = (!empty($request->ordertype) ? $request->ordertype : ORDER_TYPE);
			$db->orderBy($orderby, $ordertype);
		}
		else{
			$db->orderBy("penyewaan.id_sewa", ORDER_TYPE);
		}
		$allowed_roles = array ('administrator');
		if(!in_array(strtolower(USER_ROLE), $allowed_roles)){
		$db->where("penyewaan.nim", get_active_user('nim') );
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
		$page_title = $this->view->page_title = "Penyewaan";
		$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
		$this->view->report_title = $page_title;
		$this->view->report_layout = "report_layout.php";
		$this->view->report_paper_size = "A4";
		$this->view->report_orientation = "portrait";
		$this->render_view("penyewaan/list.php", $data); //render the full page
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
		$fields = array("penyewaan.id_sewa", 
			"penyewaan.id_sepeda", 
			"data_sepeda.merk_sepeda AS data_sepeda_merk_sepeda", 
			"penyewaan.tgl_mulai", 
			"penyewaan.tgl_akhir", 
			"penyewaan.status", 
			"penyewaan.terlambat", 
			"penyewaan.pinalti", 
			"penyewaan.tgl_booking", 
			"penyewaan.nim", 
			"users.nama_lengap AS users_nama_lengap", 
			"pemgembalian.id_pengembalian AS pemgembalian_id_pengembalian");
		$allowed_roles = array ('administrator');
		if(!in_array(strtolower(USER_ROLE), $allowed_roles)){
		$db->where("penyewaan.nim", get_active_user('nim') );
		}
		if($value){
			$db->where($rec_id, urldecode($value)); //select record based on field name
		}
		else{
			$db->where("penyewaan.id_sewa", $rec_id);; //select record based on primary key
		}
		$db->join("data_sepeda", "penyewaan.id_sepeda = data_sepeda.id_sepeda", "INNER");
		$db->join("users", "penyewaan.nim = users.nim", "INNER");
		$db->join("pemgembalian", "penyewaan.nim = pemgembalian.nim", "INNER ");  
		$record = $db->getOne($tablename, $fields );
		if($record){
			$page_title = $this->view->page_title = "View  Penyewaan";
		}
		else{
			if($db->getLastError()){
				$this->set_page_error();
			}
			else{
				$this->set_page_error("No record found");
			}
		}
		return $this->render_view("penyewaan/view.php", $record);
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
			$fields = $this->fields = array("id_sewa","nim","id_sepeda","ketersediaan","tgl_mulai","tgl_akhir","status","terlambat","pinalti","tgl_booking");
			$postdata = $this->format_request_data($formdata);
			$this->rules_array = array(
				'id_sewa' => 'required|numeric',
				'nim' => 'required',
				'id_sepeda' => 'required',
				'ketersediaan' => 'required',
				'tgl_mulai' => 'required',
				'tgl_akhir' => 'required',
				'status' => 'required',
			);
			$this->sanitize_array = array(
				'id_sewa' => 'sanitize_string',
				'nim' => 'sanitize_string',
				'id_sepeda' => 'sanitize_string',
				'ketersediaan' => 'sanitize_string',
				'tgl_mulai' => 'sanitize_string',
				'tgl_akhir' => 'sanitize_string',
				'status' => 'sanitize_string',
				'pinalti' => 'sanitize_string',
			);
			$this->filter_vals = true; //set whether to remove empty fields
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			$modeldata['terlambat'] = "1";
$modeldata['tgl_booking'] = date_now();
			//Check if Duplicate Record Already Exit In The Database
			$db->where("id_sewa", $modeldata['id_sewa']);
			if($db->has($tablename)){
				$this->view->page_error[] = $modeldata['id_sewa']." Already exist!";
			} 
			if($this->validated()){
				$rec_id = $this->rec_id = $db->insert($tablename, $modeldata);
				if($rec_id){
					$this->set_flash_msg("Record added successfully", "success");
					return	$this->redirect("penyewaan");
				}
				else{
					$this->set_page_error();
				}
			}
		}
		$page_title = $this->view->page_title = "Add New Penyewaan";
		$this->render_view("penyewaan/add.php");
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
		$fields = $this->fields = array("id_sewa","nim","id_sepeda","ketersediaan","tgl_mulai","tgl_akhir","status","terlambat","pinalti","tgl_booking");
		if($formdata){
			$postdata = $this->format_request_data($formdata);
			$this->rules_array = array(
				'id_sewa' => 'required|numeric',
				'nim' => 'required',
				'id_sepeda' => 'required',
				'ketersediaan' => 'required',
				'tgl_mulai' => 'required',
				'tgl_akhir' => 'required',
				'status' => 'required',
			);
			$this->sanitize_array = array(
				'id_sewa' => 'sanitize_string',
				'nim' => 'sanitize_string',
				'id_sepeda' => 'sanitize_string',
				'ketersediaan' => 'sanitize_string',
				'tgl_mulai' => 'sanitize_string',
				'tgl_akhir' => 'sanitize_string',
				'status' => 'sanitize_string',
				'pinalti' => 'sanitize_string',
			);
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			$modeldata['terlambat'] = "1";
$modeldata['tgl_booking'] = date_now();
			//Check if Duplicate Record Already Exit In The Database
			if(isset($modeldata['id_sewa'])){
				$db->where("id_sewa", $modeldata['id_sewa'])->where("id_sewa", $rec_id, "!=");
				if($db->has($tablename)){
					$this->view->page_error[] = $modeldata['id_sewa']." Already exist!";
				}
			} 
			if($this->validated()){
		$allowed_roles = array ('administrator');
		if(!in_array(strtolower(USER_ROLE), $allowed_roles)){
		$db->where("penyewaan.nim", get_active_user('nim') );
		}
				$db->where("penyewaan.id_sewa", $rec_id);;
				$bool = $db->update($tablename, $modeldata);
				$numRows = $db->getRowCount(); //number of affected rows. 0 = no record field updated
				if($bool && $numRows){
					$this->set_flash_msg("Record updated successfully", "success");
					return $this->redirect("penyewaan");
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
						return	$this->redirect("penyewaan");
					}
				}
			}
		}
		$allowed_roles = array ('administrator');
		if(!in_array(strtolower(USER_ROLE), $allowed_roles)){
		$db->where("penyewaan.nim", get_active_user('nim') );
		}
		$db->where("penyewaan.id_sewa", $rec_id);;
		$data = $db->getOne($tablename, $fields);
		$page_title = $this->view->page_title = "Edit  Penyewaan";
		if(!$data){
			$this->set_page_error();
		}
		return $this->render_view("penyewaan/edit.php", $data);
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
		$db->where("penyewaan.id_sewa", $arr_rec_id, "in");
		$allowed_roles = array ('administrator');
		if(!in_array(strtolower(USER_ROLE), $allowed_roles)){
		$db->where("penyewaan.nim", get_active_user('nim') );
		}
		$bool = $db->delete($tablename);
		if($bool){
			$this->set_flash_msg("Record deleted successfully", "success");
		}
		elseif($db->getLastError()){
			$page_error = $db->getLastError();
			$this->set_flash_msg($page_error, "danger");
		}
		return	$this->redirect("penyewaan");
	}
	/**
     * List page records
     * @param $fieldname (filter record by a field) 
     * @param $fieldvalue (filter field value)
     * @return BaseView
     */
	function laporan($fieldname = null , $fieldvalue = null){
		$request = $this->request;
		$db = $this->GetModel();
		$tablename = $this->tablename;
		$fields = array("penyewaan.id_sewa", 
			"penyewaan.tgl_booking", 
			"penyewaan.nim", 
			"users.nama_lengap AS users_nama_lengap", 
			"penyewaan.id_sepeda", 
			"data_sepeda.merk_sepeda AS data_sepeda_merk_sepeda", 
			"penyewaan.tgl_akhir", 
			"pemgembalian.tgl_pengembalian AS pemgembalian_tgl_pengembalian", 
			"penyewaan.terlambat", 
			"penyewaan.pinalti", 
			"pinalti AS total_denda", 
			"pemgembalian.keterangan AS pemgembalian_keterangan", 
			"pemgembalian.status_pengembalian AS pemgembalian_status_pengembalian");
		$pagination = $this->get_pagination(25); // get current pagination e.g array(page_number, page_limit)
		//search table record
		if(!empty($request->search)){
			$text = trim($request->search); 
			$search_condition = "(
				penyewaan.id_sewa LIKE ? OR 
				penyewaan.tgl_booking LIKE ? OR 
				penyewaan.nim LIKE ? OR 
				penyewaan.id_sepeda LIKE ? OR 
				penyewaan.ketersediaan LIKE ? OR 
				penyewaan.tgl_mulai LIKE ? OR 
				penyewaan.tgl_akhir LIKE ? OR 
				penyewaan.status LIKE ? OR 
				pemgembalian.tgl_pengembalian LIKE ? OR 
				penyewaan.terlambat LIKE ? OR 
				penyewaan.pinalti LIKE ? OR 
				penyewaan.total_denda LIKE ? OR 
				pemgembalian.id_pengembalian LIKE ? OR 
				pemgembalian.keterangan LIKE ? OR 
				pemgembalian.status_pengembalian LIKE ? OR 
				pemgembalian.id_sewa LIKE ? OR 
				pemgembalian.id_sepeda LIKE ?
			)";
			$search_params = array(
				"%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%"
			);
			//setting search conditions
			$db->where($search_condition, $search_params);
			 //template to use when ajax search
			$this->view->search_template = "penyewaan/search.php";
		}
		$db->join("users", "penyewaan.nim = users.nim", "INNER");
		$db->join("data_sepeda", "penyewaan.id_sepeda = data_sepeda.id_sepeda", "INNER");
		$db->join("pemgembalian", "penyewaan.id_sewa = pemgembalian.id_sewa", "INNER");
		if(!empty($request->orderby)){
			$orderby = $request->orderby;
			$ordertype = (!empty($request->ordertype) ? $request->ordertype : ORDER_TYPE);
			$db->orderBy($orderby, $ordertype);
		}
		else{
			$db->orderBy("penyewaan.id_sewa", ORDER_TYPE);
		}
		if($fieldname){
			$db->where($fieldname , $fieldvalue); //filter by a single field name
		}
		if(!empty($request->penyewaan_tgl_booking)){
			$vals = explode("-to-", str_replace(" ", "", $request->penyewaan_tgl_booking));
			$startdate = $vals[0];
			$enddate = $vals[1];
			$db->where("penyewaan.tgl_booking BETWEEN '$startdate' AND '$enddate'");
		}
		if(!empty($request->penyewaan_tgl_akhir)){
			$vals = explode("-to-", str_replace(" ", "", $request->penyewaan_tgl_akhir));
			$startdate = $vals[0];
			$enddate = $vals[1];
			$db->where("penyewaan.tgl_akhir BETWEEN '$startdate' AND '$enddate'");
		}
		if(!empty($request->pemgembalian_tgl_pengembalian)){
			$vals = explode("-to-", str_replace(" ", "", $request->pemgembalian_tgl_pengembalian));
			$startdate = $vals[0];
			$enddate = $vals[1];
			$db->where("pemgembalian.tgl_pengembalian BETWEEN '$startdate' AND '$enddate'");
		}
		if(!empty($request->penyewaan_id_sepeda)){
			$val = $request->penyewaan_id_sepeda;
			$db->where("penyewaan.id_sepeda", $val , "=");
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
		$page_title = $this->view->page_title = "Penyewaan";
		$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
		$this->view->report_title = $page_title;
		$this->view->report_layout = "report_layout.php";
		$this->view->report_paper_size = "A4";
		$this->view->report_orientation = "portrait";
		$this->render_view("penyewaan/laporan.php", $data); //render the full page
	}
	/**
     * List page records
     * @param $fieldname (filter record by a field) 
     * @param $fieldvalue (filter field value)
     * @return BaseView
     */
	function halaman_user($fieldname = null , $fieldvalue = null){
		$request = $this->request;
		$db = $this->GetModel();
		$tablename = $this->tablename;
		$fields = array("penyewaan.id_sewa", 
			"penyewaan.tgl_booking", 
			"penyewaan.nim", 
			"users.nama_lengap AS users_nama_lengap", 
			"penyewaan.id_sepeda", 
			"data_sepeda.merk_sepeda AS data_sepeda_merk_sepeda", 
			"penyewaan.tgl_mulai", 
			"penyewaan.tgl_akhir", 
			"penyewaan.status", 
			"penyewaan.terlambat", 
			"penyewaan.pinalti", 
			"pinalti AS total_denda");
		$pagination = $this->get_pagination(25); // get current pagination e.g array(page_number, page_limit)
		//search table record
		if(!empty($request->search)){
			$text = trim($request->search); 
			$search_condition = "(
				penyewaan.id_sewa LIKE ? OR 
				penyewaan.tgl_booking LIKE ? OR 
				penyewaan.nim LIKE ? OR 
				penyewaan.id_sepeda LIKE ? OR 
				penyewaan.ketersediaan LIKE ? OR 
				penyewaan.tgl_mulai LIKE ? OR 
				penyewaan.tgl_akhir LIKE ? OR 
				penyewaan.status LIKE ? OR 
				penyewaan.terlambat LIKE ? OR 
				penyewaan.pinalti LIKE ? OR 
				pinalti LIKE ?
			)";
			$search_params = array(
				"%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%"
			);
			//setting search conditions
			$db->where($search_condition, $search_params);
			 //template to use when ajax search
			$this->view->search_template = "penyewaan/search.php";
		}
		$db->join("users", "penyewaan.nim = users.nim", "INNER");
		$db->join("data_sepeda", "penyewaan.id_sepeda = data_sepeda.id_sepeda", "INNER");
		if(!empty($request->orderby)){
			$orderby = $request->orderby;
			$ordertype = (!empty($request->ordertype) ? $request->ordertype : ORDER_TYPE);
			$db->orderBy($orderby, $ordertype);
		}
		else{
			$db->orderBy("penyewaan.id_sewa", ORDER_TYPE);
		}
		$allowed_roles = array ('administrator');
		if(!in_array(strtolower(USER_ROLE), $allowed_roles)){
		$db->where("penyewaan.nim", get_active_user('nim') );
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
		$page_title = $this->view->page_title = "Penyewaan";
		$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
		$this->view->report_title = $page_title;
		$this->view->report_layout = "report_layout.php";
		$this->view->report_paper_size = "A4";
		$this->view->report_orientation = "portrait";
		$this->render_view("penyewaan/halaman_user.php", $data); //render the full page
	}
	/**
     * Insert new record to the database table
	 * @param $formdata array() from $_POST
     * @return BaseView
     */
	function halaman_user_add($formdata = null){
		if($formdata){
			$db = $this->GetModel();
			$tablename = $this->tablename;
			$request = $this->request;
			//fillable fields
			$fields = $this->fields = array("id_sewa","nim","id_sepeda","ketersediaan","tgl_mulai","tgl_akhir","status","terlambat","pinalti","tgl_booking");
			$postdata = $this->format_request_data($formdata);
			$this->rules_array = array(
				'id_sewa' => 'required|numeric',
				'id_sepeda' => 'required',
				'ketersediaan' => 'required',
				'tgl_mulai' => 'required',
				'tgl_akhir' => 'required',
			);
			$this->sanitize_array = array(
				'id_sewa' => 'sanitize_string',
				'id_sepeda' => 'sanitize_string',
				'ketersediaan' => 'sanitize_string',
				'tgl_mulai' => 'sanitize_string',
				'tgl_akhir' => 'sanitize_string',
			);
			$this->filter_vals = true; //set whether to remove empty fields
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			$modeldata['nim'] = USER_ID;
$modeldata['status'] = "Pending";
$modeldata['terlambat'] = "1";
$modeldata['pinalti'] = "0";
$modeldata['tgl_booking'] = date_now();
			//Check if Duplicate Record Already Exit In The Database
			$db->where("id_sewa", $modeldata['id_sewa']);
			if($db->has($tablename)){
				$this->view->page_error[] = $modeldata['id_sewa']." Already exist!";
			} 
			if($this->validated()){
				$rec_id = $this->rec_id = $db->insert($tablename, $modeldata);
				if($rec_id){
					$this->set_flash_msg("Record added successfully", "success");
					return	$this->redirect("penyewaan/halaman_user");
				}
				else{
					$this->set_page_error();
				}
			}
		}
		$page_title = $this->view->page_title = "Add New Penyewaan";
		$this->render_view("penyewaan/halaman_user_add.php");
	}
	/**
     * List page records
     * @param $fieldname (filter record by a field) 
     * @param $fieldvalue (filter field value)
     * @return BaseView
     */
	function page_pending($fieldname = null , $fieldvalue = null){
		$request = $this->request;
		$db = $this->GetModel();
		$tablename = $this->tablename;
		$fields = array("penyewaan.id_sewa", 
			"penyewaan.tgl_booking", 
			"penyewaan.nim", 
			"users.nama_lengap AS users_nama_lengap", 
			"penyewaan.id_sepeda", 
			"data_sepeda.merk_sepeda AS data_sepeda_merk_sepeda", 
			"penyewaan.tgl_mulai", 
			"penyewaan.tgl_akhir", 
			"penyewaan.status", 
			"penyewaan.terlambat", 
			"penyewaan.pinalti", 
			"pinalti AS total_denda");
		$pagination = $this->get_pagination(25); // get current pagination e.g array(page_number, page_limit)
		//search table record
		if(!empty($request->search)){
			$text = trim($request->search); 
			$search_condition = "(
				penyewaan.id_sewa LIKE ? OR 
				penyewaan.tgl_booking LIKE ? OR 
				penyewaan.nim LIKE ? OR 
				penyewaan.id_sepeda LIKE ? OR 
				penyewaan.ketersediaan LIKE ? OR 
				penyewaan.tgl_mulai LIKE ? OR 
				penyewaan.tgl_akhir LIKE ? OR 
				penyewaan.status LIKE ? OR 
				penyewaan.terlambat LIKE ? OR 
				penyewaan.pinalti LIKE ? OR 
				pinalti LIKE ?
			)";
			$search_params = array(
				"%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%"
			);
			//setting search conditions
			$db->where($search_condition, $search_params);
			 //template to use when ajax search
			$this->view->search_template = "penyewaan/search.php";
		}
		$db->join("users", "penyewaan.nim = users.nim", "INNER");
		$db->join("data_sepeda", "penyewaan.id_sepeda = data_sepeda.id_sepeda", "INNER");
		if(!empty($request->orderby)){
			$orderby = $request->orderby;
			$ordertype = (!empty($request->ordertype) ? $request->ordertype : ORDER_TYPE);
			$db->orderBy($orderby, $ordertype);
		}
		else{
			$db->orderBy("penyewaan.id_sewa", ORDER_TYPE);
		}
		$allowed_roles = array ('administrator');
		if(!in_array(strtolower(USER_ROLE), $allowed_roles)){
		$db->where("penyewaan.nim", get_active_user('nim') );
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
		$page_title = $this->view->page_title = "Penyewaan";
		$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
		$this->view->report_title = $page_title;
		$this->view->report_layout = "report_layout.php";
		$this->view->report_paper_size = "A4";
		$this->view->report_orientation = "portrait";
		$this->render_view("penyewaan/page_pending.php", $data); //render the full page
	}
	/**
     * List page records
     * @param $fieldname (filter record by a field) 
     * @param $fieldvalue (filter field value)
     * @return BaseView
     */
	function page_ditolak($fieldname = null , $fieldvalue = null){
		$request = $this->request;
		$db = $this->GetModel();
		$tablename = $this->tablename;
		$fields = array("penyewaan.id_sewa", 
			"penyewaan.tgl_booking", 
			"penyewaan.nim", 
			"users.nama_lengap AS users_nama_lengap", 
			"penyewaan.id_sepeda", 
			"data_sepeda.merk_sepeda AS data_sepeda_merk_sepeda", 
			"penyewaan.tgl_mulai", 
			"penyewaan.tgl_akhir", 
			"penyewaan.status", 
			"penyewaan.terlambat", 
			"penyewaan.pinalti", 
			"pinalti AS total_denda");
		$pagination = $this->get_pagination(25); // get current pagination e.g array(page_number, page_limit)
		//search table record
		if(!empty($request->search)){
			$text = trim($request->search); 
			$search_condition = "(
				penyewaan.id_sewa LIKE ? OR 
				penyewaan.tgl_booking LIKE ? OR 
				penyewaan.nim LIKE ? OR 
				penyewaan.id_sepeda LIKE ? OR 
				penyewaan.ketersediaan LIKE ? OR 
				penyewaan.tgl_mulai LIKE ? OR 
				penyewaan.tgl_akhir LIKE ? OR 
				penyewaan.status LIKE ? OR 
				penyewaan.terlambat LIKE ? OR 
				penyewaan.pinalti LIKE ? OR 
				pinalti LIKE ?
			)";
			$search_params = array(
				"%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%"
			);
			//setting search conditions
			$db->where($search_condition, $search_params);
			 //template to use when ajax search
			$this->view->search_template = "penyewaan/search.php";
		}
		$db->join("users", "penyewaan.nim = users.nim", "INNER");
		$db->join("data_sepeda", "penyewaan.id_sepeda = data_sepeda.id_sepeda", "INNER");
		if(!empty($request->orderby)){
			$orderby = $request->orderby;
			$ordertype = (!empty($request->ordertype) ? $request->ordertype : ORDER_TYPE);
			$db->orderBy($orderby, $ordertype);
		}
		else{
			$db->orderBy("penyewaan.id_sewa", ORDER_TYPE);
		}
		$allowed_roles = array ('administrator');
		if(!in_array(strtolower(USER_ROLE), $allowed_roles)){
		$db->where("penyewaan.nim", get_active_user('nim') );
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
		$page_title = $this->view->page_title = "Penyewaan";
		$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
		$this->view->report_title = $page_title;
		$this->view->report_layout = "report_layout.php";
		$this->view->report_paper_size = "A4";
		$this->view->report_orientation = "portrait";
		$this->render_view("penyewaan/page_ditolak.php", $data); //render the full page
	}
	/**
     * List page records
     * @param $fieldname (filter record by a field) 
     * @param $fieldvalue (filter field value)
     * @return BaseView
     */
	function page_diterima($fieldname = null , $fieldvalue = null){
		$request = $this->request;
		$db = $this->GetModel();
		$tablename = $this->tablename;
		$fields = array("penyewaan.id_sewa", 
			"penyewaan.tgl_booking", 
			"penyewaan.nim", 
			"users.nama_lengap AS users_nama_lengap", 
			"penyewaan.id_sepeda", 
			"data_sepeda.merk_sepeda AS data_sepeda_merk_sepeda", 
			"penyewaan.tgl_mulai", 
			"penyewaan.tgl_akhir", 
			"penyewaan.status", 
			"penyewaan.terlambat", 
			"penyewaan.pinalti", 
			"pinalti AS total_denda");
		$pagination = $this->get_pagination(25); // get current pagination e.g array(page_number, page_limit)
		//search table record
		if(!empty($request->search)){
			$text = trim($request->search); 
			$search_condition = "(
				penyewaan.id_sewa LIKE ? OR 
				penyewaan.tgl_booking LIKE ? OR 
				penyewaan.nim LIKE ? OR 
				penyewaan.id_sepeda LIKE ? OR 
				penyewaan.ketersediaan LIKE ? OR 
				penyewaan.tgl_mulai LIKE ? OR 
				penyewaan.tgl_akhir LIKE ? OR 
				penyewaan.status LIKE ? OR 
				penyewaan.terlambat LIKE ? OR 
				penyewaan.pinalti LIKE ? OR 
				pinalti LIKE ?
			)";
			$search_params = array(
				"%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%"
			);
			//setting search conditions
			$db->where($search_condition, $search_params);
			 //template to use when ajax search
			$this->view->search_template = "penyewaan/search.php";
		}
		$db->join("users", "penyewaan.nim = users.nim", "INNER");
		$db->join("data_sepeda", "penyewaan.id_sepeda = data_sepeda.id_sepeda", "INNER");
		if(!empty($request->orderby)){
			$orderby = $request->orderby;
			$ordertype = (!empty($request->ordertype) ? $request->ordertype : ORDER_TYPE);
			$db->orderBy($orderby, $ordertype);
		}
		else{
			$db->orderBy("penyewaan.id_sewa", ORDER_TYPE);
		}
		$allowed_roles = array ('administrator');
		if(!in_array(strtolower(USER_ROLE), $allowed_roles)){
		$db->where("penyewaan.nim", get_active_user('nim') );
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
		$page_title = $this->view->page_title = "Penyewaan";
		$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
		$this->view->report_title = $page_title;
		$this->view->report_layout = "report_layout.php";
		$this->view->report_paper_size = "A4";
		$this->view->report_orientation = "portrait";
		$this->render_view("penyewaan/page_diterima.php", $data); //render the full page
	}
}
