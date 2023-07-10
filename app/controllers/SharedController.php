<?php 

/**
 * SharedController Controller
 * @category  Controller / Model
 */
class SharedController extends BaseController{
	
	/**
     * penyewaan_pinalti_option_list Model Action
     * @return array
     */
	function penyewaan_pinalti_option_list(){
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT denda AS value,denda AS label FROM denda_berlaku";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * penyewaan_id_sewa_value_exist Model Action
     * @return array
     */
	function penyewaan_id_sewa_value_exist($val){
		$db = $this->GetModel();
		$db->where("id_sewa", $val);
		$exist = $db->has("penyewaan");
		return $exist;
	}

	/**
     * penyewaan_nim_option_list Model Action
     * @return array
     */
	function penyewaan_nim_option_list(){
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT nim AS value,nama_lengap AS label FROM users";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * penyewaan_id_sepeda_option_list Model Action
     * @return array
     */
	function penyewaan_id_sepeda_option_list(){
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT id_sepeda AS value,merk_sepeda AS label FROM data_sepeda";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * penyewaan_ketersediaan_option_list Model Action
     * @return array
     */
	function penyewaan_ketersediaan_option_list($lookup_id_sepeda){
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT jumlah_sepeda AS value,jumlah_sepeda AS label FROM data_sepeda WHERE id_sepeda= ?" ;
		$queryparams = array($lookup_id_sepeda);
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * users_prodi_option_list Model Action
     * @return array
     */
	function users_prodi_option_list(){
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT nama_prodi AS value,nama_prodi AS label FROM data_prodi";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * users_email_value_exist Model Action
     * @return array
     */
	function users_email_value_exist($val){
		$db = $this->GetModel();
		$db->where("email", $val);
		$exist = $db->has("users");
		return $exist;
	}

	/**
     * users_users_name_value_exist Model Action
     * @return array
     */
	function users_users_name_value_exist($val){
		$db = $this->GetModel();
		$db->where("users_name", $val);
		$exist = $db->has("users");
		return $exist;
	}

	/**
     * users_nim_value_exist Model Action
     * @return array
     */
	function users_nim_value_exist($val){
		$db = $this->GetModel();
		$db->where("nim", $val);
		$exist = $db->has("users");
		return $exist;
	}

	/**
     * pemgembalian_id_sepeda_option_list Model Action
     * @return array
     */
	function pemgembalian_id_sepeda_option_list($lookup_id_sewa){
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT id_sepeda AS value,id_sepeda AS label FROM penyewaan WHERE id_sewa= ?" ;
		$queryparams = array($lookup_id_sewa);
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * pemgembalian_id_sewa_option_list Model Action
     * @return array
     */
	function pemgembalian_id_sewa_option_list(){
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT id_sewa AS value,id_sewa AS label FROM penyewaan";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * pemgembalian_nim_option_list Model Action
     * @return array
     */
	function pemgembalian_nim_option_list($lookup_id_sewa){
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT nim AS value,nim AS label FROM penyewaan WHERE id_sewa= ?" ;
		$queryparams = array($lookup_id_sewa);
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * penyewaan_penyewaanid_sepeda_option_list Model Action
     * @return array
     */
	function penyewaan_penyewaanid_sepeda_option_list(){
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT id_sepeda AS value,merk_sepeda AS label FROM data_sepeda";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * getcount_tersedia Model Action
     * @return Value
     */
	function getcount_tersedia(){
		$db = $this->GetModel();
		$sqltext = "SELECT  SUM(ds.jumlah_sepeda) AS sum_of_jumlah_sepeda FROM data_sepeda AS ds";
		$queryparams = null;
		$val = $db->rawQueryValue($sqltext, $queryparams);
		
		if(is_array($val)){
			return $val[0];
		}
		return $val;
	}

	/**
	* barchart_merksepedatersedia Model Action
	* @return array
	*/
	function barchart_merksepedatersedia(){
		
		$db = $this->GetModel();
		$chart_data = array(
			"labels"=> array(),
			"datasets"=> array(),
		);
		
		//set query result for dataset 1
		$sqltext = "SELECT  ds.merk_sepeda, SUM(ds.jumlah_sepeda) AS sum_of_jumlah_sepeda FROM data_sepeda AS ds GROUP BY ds.merk_sepeda";
		$queryparams = null;
		$dataset1 = $db->rawQuery($sqltext, $queryparams);
		$dataset_data =  array_column($dataset1, 'sum_of_jumlah_sepeda');
		$dataset_labels =  array_column($dataset1, 'merk_sepeda');
		$chart_data["labels"] = array_unique(array_merge($chart_data["labels"], $dataset_labels));
		$chart_data["datasets"][] = $dataset_data;

		return $chart_data;
	}

}
