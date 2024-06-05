<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
@application    : Attachment Accounting
@author 		: Syah Jadianto (8604)
@contributor	: 
			1. <insert your fullname> (<insert your nik>) <insert the date>
			   <insert what you have modified>			   
			2. <insert your fullname> (<insert your nik>) <insert the date>
			   <insert what you have modified>
			etc.
*/

class Dtransactionacc extends CI_Model{

	function get_data_uploadjurnal($id=NULL, $plant=NULL, $tgl=NULL, $account=NULL, $no_doc=NULL, $noupload=NULL){
		$this->general->connectDbPortal();

		$string	= "Select id, no_doc, text, tipe, account, tgl, bukrs, gsber, reff, info, cancel, data, num_data,
			replace(replace(data, 'img/acc/KUT1/2016/',''), 'assets/file/acc/uploadjurnal/', '') data2,
			in_date, in_datefirst, in_by, upload_date, no_doc + '_' +  gsber + '_' + cast(year(tgl) as char(4)) filename,
			case when datediff(D, cast(tgl as datetime), getdate()) >= 10 and ISNULL(data,'') = '' 
				then '<span class=''badge bg-green''>Tanggal Postng Jurnal Telah 10 Hari yang lalu, Silahkan Request Re-Upload </span>'
			when datediff(D, cast(appv_date as datetime), getdate()) >= 10 and ISNULL(data,'') = '-' and alow_edit = 1
				then '<span class=''badge bg-green''>Tanggal Postng Jurnal Telah 10 Hari yang lalu, Silahkan Request Re-Upload </span>'
			when alow_edit = 0 
				then '<span class=''badge''>Menunggu approval </span>'
				else NULL
			end remark, 
			alow_edit, reject_edit, appv_date, checklist
			from tbl_acc_upload_jurnal
			where isnull(cancel,'') <> 'X' and gsber = '".$plant."' and (isnull(data,'') = '' or isnull(data,'') <> '' or isnull(data,'') = '-') 
			and ISNULL(alow_edit,1) in (1,'')
			";
		if(isset($id) && $id != ""){
			$string .= " AND id = ".$id."";				
		}
		if(isset($tgl) && $tgl != ""){
			$string .= " AND CAST(tgl AS DATE) = '".$tgl."'";				
		}
		if(isset($account) && $account != ""){
			$string .= " AND account like '%".$account."%'";				
		}
		if(isset($no_doc) && $no_doc != ""){
			$string .= " AND no_doc = '".$no_doc."'";				
		}
		if(isset($noupload) && $noupload != ""){
			$string .= " AND isnull(data,'') in ('','-')";				
		}
		$string	.= " order by tgl, no_doc";

		// echo "<pre>".$string."</pre>"; die();
		
		$query	= $this->db->query($string);
		$result	= $query->result();

		return $result;
	}

	function get_data_uploadetc($id=NULL, $plant=NULL, $from=NULL, $to=NULL, $nocheck=NULL){
		$this->general->connectDbPortal();

		$string	= "Select id, no_doc, text, tipe, account, tgl, bukrs, gsber, reff, info, cancel, data, num_data,
			replace(replace(data, 'img/acc/KUT1/2016/',''), 'assets/file/acc/uploadjurnal/', '') data2,
			in_date, in_datefirst, in_by, upload_date, no_doc + '_' +  gsber + '_' + cast(year(tgl) as char(4)) filename,
			case when datediff(D, cast(tgl as datetime), getdate()) >= 10 and ISNULL(data,'') = '' 
				then 'Tanggal Postng Jurnal Telah 10 Hari yang lalu, Silahkan Request Re-Upload' 
			when datediff(D, cast(appv_date as datetime), getdate()) >= 10 and ISNULL(data,'') = '-' and alow_edit = 1
				then 'Tanggal Postng Jurnal Telah 10 Hari yang lalu, Silahkan Request Re-Upload' 
			end remark, 
			alow_edit, reject_edit, appv_date, nama_jenis, isnull(checklist,'n') checklist
			from tbl_acc_upload_jurnal 
			where isnull(cancel,'') <> 'X' and gsber = '".$plant."' and lap_lain = 1 and del = 'n'";
		if(isset($id) && $id != ""){
			$string .= " AND id = ".$id."";				
		}
		if(isset($from) && $from != ""){
			$string .= " AND tgl between '".$from."' and '".$to."'";				
		}
		if(isset($nocheck) && $nocheck != ""){
			$string .= " AND isnull(checklist,'n') = 'n'";				
		}
		$string	.= " order by tgl desc, no_doc";

		// echo "<pre>".$string."</pre>"; die();
		
		$query	= $this->db->query($string);
		$result	= $query->result();

		return $result;
	}

	function get_uploadetc($id=NULL, $plant=NULL, $from=NULL, $to=NULL, $nocheck=NULL){

		$string	= "Select id, no_doc, text, tipe, account, tgl, convert(char(10),tgl,104) tgl_sap, bukrs, gsber, reff, info, cancel, data, num_data,
			replace(replace(data, 'img/acc/KUT1/2016/',''), 'assets/file/acc/uploadjurnal/', '') data2,
			in_date, in_datefirst, in_by, upload_date, no_doc + '_' +  gsber + '_' + cast(year(tgl) as char(4)) filename,
			case when datediff(D, cast(tgl as datetime), getdate()) >= 10 and ISNULL(data,'') = '' 
				then 'Tanggal Postng Jurnal Telah 10 Hari yang lalu, Silahkan Request Re-Upload' 
			when datediff(D, cast(appv_date as datetime), getdate()) >= 10 and ISNULL(data,'') = '-' and alow_edit = 1
				then 'Tanggal Postng Jurnal Telah 10 Hari yang lalu, Silahkan Request Re-Upload' 
			end remark, 
			alow_edit, reject_edit, appv_date, nama_jenis, isnull(checklist,'n') checklist, id_jenis
			from tbl_acc_upload_jurnal 
			where isnull(cancel,'') <> 'X' and lap_lain = 1 ";
		if(isset($id) && $id != ""){
			$string .= " AND id = ".$id."";				
		}
		if(isset($plant) && $plant != ""){
			$string .= " gsber = '".$plant."'";				
		}
		if(isset($from) && $from != ""){
			$string .= " AND tgl between '".$from."' and '".$to."'";				
		}
		if(isset($nocheck) && $nocheck != ""){
			$string .= " AND isnull(checklist,'n') = 'n'";				
		}
		$string	.= " order by tgl desc, no_doc";

		// echo "<pre>".$string."</pre>"; die();
		
		$query	= $this->db->query($string);
		$result	= $query->result();

		return $result;
	}

	function get_data_uploadlaporan($id=NULL, $plant=NULL, $source=NULL, $jenis=NULL, $from=NULL, $to=NULL, $search=NULL, $param=NULL, $nocheck=NULL, $noupload=NULL, $type=NULL){
		$this->general->connectDbPortal();

		$param = ($search == "in_date") ? $this->generate->regenerateDateFormat($param) : $param;
		$search = ($search == "in_date") ? "cast(in_date as date)" : $search;

		if(isset($type) && $type == "HO"){
			$string	= "
			Select DISTINCT convert(char(8),tgl,112) periode, 0 id, NULL no_doc, NULL dmbtr, NULL text, NULL tipe, NULL account, 
			tgl, NULL bukrs, NULL gsber, NULL reff, NULL info, NULL cancel, NULL data, NULL num_data, NULL data2,
			NULL in_date, NULL in_datefirst, NULL in_by, NULL upload_date, NULL filename,
			NULL remark, NULL alow_edit, NULL reject_edit, NULL appv_date, NULL nama_jenis, NULL nama_user, NULL checklist
			from tbl_acc_upload_jurnal a
			INNER JOIN tbl_acc_master_gl on a.account = tbl_acc_master_gl.account
			left join tbl_karyawan k
				on in_by = nik
			where tbl_acc_master_gl.na = 'n' and isnull(cancel,'') <> 'X' and a.gsber = '".$plant."' and a.del = 'n'";
		}else if(isset($type) && $type == "BRANCH"){
			$string	= "
			Select DISTINCT convert(char(8),tgl,112) periode, 0 id, NULL no_doc, NULL dmbtr, NULL text, NULL tipe, NULL account, 
			tgl, NULL bukrs, NULL gsber, NULL reff, NULL info, NULL cancel, NULL data, NULL num_data, NULL data2,
			NULL in_date, NULL in_datefirst, NULL in_by, NULL upload_date, NULL filename,
			NULL remark, NULL alow_edit, NULL reject_edit, NULL appv_date, NULL nama_jenis, NULL nama_user, NULL checklist
			from tbl_acc_upload_jurnal a
			LEFT JOIN tbl_acc_master_gl on a.account = tbl_acc_master_gl.account
			left join tbl_karyawan k
				on in_by = nik
			where isnull(cancel,'') <> 'X' and (tbl_acc_master_gl.account is NULL OR tbl_acc_master_gl.na = 'y') AND a.gsber = '".$plant."' and a.del = 'n'";
		}else{
			$string	= "
			Select DISTINCT convert(char(8),tgl,112) periode, 0 id, NULL no_doc, NULL dmbtr, NULL text, NULL tipe, NULL account, 
			tgl, NULL bukrs, NULL gsber, NULL reff, NULL info, NULL cancel, NULL data, NULL num_data, NULL data2,
			NULL in_date, NULL in_datefirst, NULL in_by, NULL upload_date, NULL filename,
			NULL remark, NULL alow_edit, NULL reject_edit, NULL appv_date, NULL nama_jenis, NULL nama_user, NULL checklist
			from tbl_acc_upload_jurnal a
			left join tbl_karyawan k
				on in_by = nik
			where isnull(cancel,'') <> 'X' and a.gsber = '".$plant."' and a.del = 'n'";
		}

		
		if(isset($id) && $id != ""){
			$string .= " AND id = ".$id."";				
		}
		if(isset($jenis) && $jenis != ""){
			$string .= " AND id_jenis = ".$jenis."";				
		}
		if(isset($from) && $from != ""){
			$string .= " AND tgl between '".$from."' and '".$to."'";				
		}
		if(isset($source) && $source != ""){
			$string .= " AND isnull(lap_lain,2) = ".$source."";				
		}
		if(isset($search) && $search != ""){
			$string .= " AND ".$search." = '".$param."'";				
		}
		if(isset($nocheck) && $nocheck != ""){
			$string .= " AND isnull(checklist,'n') = 'n'";				
		}
		if(isset($noupload) && $noupload != ""){
			$string .= " AND isnull([data],'n') = 'n'";				
		}

		if(isset($type) && $type == "HO"){
			$string .= "
			 UNION
			Select convert(char(8),tgl,112) periode, id, no_doc, dmbtr, text, tipe, a.account, tgl, bukrs, a.gsber, reff, info, cancel, data, num_data,
			replace(replace(data, 'img/acc/KUT1/2016/',''), 'assets/file/acc/uploadjurnal/', '') data2,
			in_date, in_datefirst, in_by, upload_date, no_doc + '_' +  a.gsber + '_' + cast(year(tgl) as char(4)) filename,
			case when datediff(D, cast(tgl as datetime), getdate()) >= 10 and ISNULL(data,'') = '' 
				then 'Tanggal Postng Jurnal Telah 10 Hari yang lalu, Silahkan Request Re-Upload' 
			when datediff(D, cast(appv_date as datetime), getdate()) >= 10 and ISNULL(data,'') = '-' and alow_edit = 1
				then 'Tanggal Postng Jurnal Telah 10 Hari yang lalu, Silahkan Request Re-Upload' 
			end remark, 
			alow_edit, reject_edit, appv_date, nama_jenis, 
			case when ISNULL(data,'') = '' or (ISNULL(data,'') = '-' and alow_edit = 1) then NULL else k.nama end nama_user, 
			isnull(checklist,'n') checklist
			from tbl_acc_upload_jurnal a
			INNER JOIN tbl_acc_master_gl on a.account = tbl_acc_master_gl.account
			left join tbl_karyawan k
				on in_by = nik
			where tbl_acc_master_gl.na = 'n' and isnull(cancel,'') <> 'X' and a.gsber = '".$plant."' and a.del = 'n'";
		}else if(isset($type) && $type == "BRANCH"){
			$string .= "
			 UNION
			Select convert(char(8),tgl,112) periode, id, no_doc, dmbtr, text, tipe, a.account, tgl, bukrs, a.gsber, reff, info, cancel, data, num_data,
			replace(replace(data, 'img/acc/KUT1/2016/',''), 'assets/file/acc/uploadjurnal/', '') data2,
			in_date, in_datefirst, in_by, upload_date, no_doc + '_' +  a.gsber + '_' + cast(year(tgl) as char(4)) filename,
			case when datediff(D, cast(tgl as datetime), getdate()) >= 10 and ISNULL(data,'') = '' 
				then 'Tanggal Postng Jurnal Telah 10 Hari yang lalu, Silahkan Request Re-Upload' 
			when datediff(D, cast(appv_date as datetime), getdate()) >= 10 and ISNULL(data,'') = '-' and alow_edit = 1
				then 'Tanggal Postng Jurnal Telah 10 Hari yang lalu, Silahkan Request Re-Upload' 
			end remark, 
			alow_edit, reject_edit, appv_date, nama_jenis, 
			case when ISNULL(data,'') = '' or (ISNULL(data,'') = '-' and alow_edit = 1) then NULL else k.nama end nama_user, 
			isnull(checklist,'n') checklist
			from tbl_acc_upload_jurnal a
			LEFT JOIN tbl_acc_master_gl on a.account = tbl_acc_master_gl.account
			left join tbl_karyawan k
				on in_by = nik
			where isnull(cancel,'') <> 'X' and (tbl_acc_master_gl.account is NULL OR tbl_acc_master_gl.na = 'y') and a.gsber = '".$plant."' and a.del = 'n'";
		
		}else{
			$string .= "
			 UNION
			Select convert(char(8),tgl,112) periode, id, no_doc, dmbtr, text, tipe, account, tgl, bukrs, a.gsber, reff, info, cancel, data, num_data,
			replace(replace(data, 'img/acc/KUT1/2016/',''), 'assets/file/acc/uploadjurnal/', '') data2,
			in_date, in_datefirst, in_by, upload_date, no_doc + '_' +  a.gsber + '_' + cast(year(tgl) as char(4)) filename,
			case when datediff(D, cast(tgl as datetime), getdate()) >= 10 and ISNULL(data,'') = '' 
				then 'Tanggal Postng Jurnal Telah 10 Hari yang lalu, Silahkan Request Re-Upload' 
			when datediff(D, cast(appv_date as datetime), getdate()) >= 10 and ISNULL(data,'') = '-' and alow_edit = 1
				then 'Tanggal Postng Jurnal Telah 10 Hari yang lalu, Silahkan Request Re-Upload' 
			end remark, 
			alow_edit, reject_edit, appv_date, nama_jenis, 
			case when ISNULL(data,'') = '' or (ISNULL(data,'') = '-' and alow_edit = 1) then NULL else k.nama end nama_user, 
			isnull(checklist,'n') checklist
			from tbl_acc_upload_jurnal a
			left join tbl_karyawan k
				on in_by = nik
			where isnull(cancel,'') <> 'X' and a.gsber = '".$plant."' and a.del = 'n'";
		}

		if(isset($id) && $id != ""){
			$string .= " AND id = ".$id."";				
		}
		if(isset($jenis) && $jenis != ""){
			$string .= " AND id_jenis = ".$jenis."";				
		}
		if(isset($from) && $from != ""){
			$string .= " AND tgl between '".$from."' and '".$to."'";				
		}
		if(isset($source) && $source != ""){
			$string .= " AND isnull(lap_lain,2) = ".$source."";				
		}
		if(isset($search) && $search != ""){
			$string .= " AND ".$search." = '".$param."'";				
		}
		if(isset($nocheck) || $nocheck != ""){
			$string .= " AND isnull(checklist,'n') = 'n'";				
		}
		if(isset($noupload) && $noupload != ""){
			$string .= " AND isnull([data],'n') = 'n'";				
		}
		$string	.= " order by periode, id";

		// echo "<pre>".$string."</pre>"; die();
		
		$query	= $this->db->query($string);
		$result	= $query->result();

		return $result;
	}


	function get_new_number($plant=NULL, $tgl=NULL){

		$string	= "Select RIGHT(convert(char(6),CAST('".$tgl."' AS DATE),112),4) + RIGHT('000' + CAST(CAST(RIGHT(ISNULL(MAX(no_doc),0),3) AS INT) + 1 AS VARCHAR(3)),3) new_number
			from tbl_acc_upload_jurnal 
			where gsber = '".$plant."' and lap_lain = 1 and convert(char(6),tgl,112) = convert(char(6),CAST('".$tgl."' AS DATE),112)";

		// echo "<pre>".$string."</pre>"; die();
		
		$query	= $this->db->query($string);
		$result	= $query->result();

		return $result;
	}

	function get_uploadjurnal($id=NULL, $plant=NULL, $tgl=NULL, $no_doc=NULL){

		$string	= "Select id, no_doc, text, tipe, account, tgl, bukrs, gsber, reff, isnull(info,'') info, cancel, data, num_data,
			replace(replace(data, 'img/acc/KUT1/2016/',''), 'assets/file/acc/uploadjurnal/', '') data2,
			in_date, in_datefirst, in_by, upload_date, no_doc + '_' +  gsber + '_' + cast(year(tgl) as char(4)) filename,
			case when datediff(D, cast(tgl as datetime), getdate()) >= 10 and ISNULL(data,'') in ('','-') then 'Tanggal Postng Jurnal Telah 10 Hari yang lalu, Silahkan Request Re-Upload' end remark, checklist
			from tbl_acc_upload_jurnal where 1 = 1 ";
		if(isset($id) && $id != ""){
			$string .= " AND id = ".$id."";				
		}
		if(isset($plant) && $plant != ""){
			$string .= " AND gsber = '".$plant."'";				
		}
		if(isset($tgl) && $tgl != ""){
			$string .= " AND CONVERT(CHAR(8),tgl,112) = '".$tgl."'";				
		}
		if(isset($no_doc) && $no_doc != ""){
			$string .= " AND no_doc = '".$no_doc."'";				
		}
		$string	.= " order by tgl desc";
		
		$query	= $this->db->query($string);
		$result	= $query->result();

		return $result;
	}

	function get_data_uploadsync($id=NULL, $plant=NULL, $from=NULL, $to=NULL, $account=NULL, $doc=NULL){
		$this->general->connectDbPortal();

		$string	= "Select id, no_doc, text, tipe, account, tgl, bukrs, gsber, reff, info, cancel, data, num_data,
			replace(replace(data, 'img/acc/KUT1/2016/',''), 'assets/file/acc/uploadjurnal/', '') data2,
			in_date, in_datefirst, in_by, upload_date, no_doc + '_' +  gsber + '_' + cast(year(tgl) as char(4)) filename,
			case when datediff(D, cast(tgl as datetime), getdate()) >= 10 and ISNULL(data,'') = '' 
				then 'Tanggal Postng Jurnal Telah 10 Hari yang lalu, Silahkan Request Re-Upload' 
			when datediff(D, cast(appv_date as datetime), getdate()) >= 10 and ISNULL(data,'') = '-' and alow_edit = 1
				then 'Tanggal Postng Jurnal Telah 10 Hari yang lalu, Silahkan Request Re-Upload' 
			end remark, 
			alow_edit, reject_edit, appv_date
			from tbl_acc_upload_jurnal 
			where isnull(cancel,'') <> 'X' and gsber = '".$plant."' 
			and (isnull(data,'') = '' or isnull(data,'') <> '' or (isnull(data,'') = '-' and alow_edit = 1))";
		if(isset($id) && $id != ""){
			$string .= " AND id = ".$id."";				
		}
		if(isset($from) && $from != ""){
			$string .= " AND tgl between '".$from."' and '".$to."'";				
		}
		if(isset($account) && $account != ""){
			$string .= " AND account like '%".$account."%'";				
		}
		if(isset($doc) && $doc != ""){
			$string .= " AND no_doc = '".$doc."'";				
		}
		$string	.= " order by tgl desc";

		// echo "<pre>".$string."</pre>"; die();
		
		$query	= $this->db->query($string);
		$result	= $query->result();

		return $result;
	}

	function get_request_upload($id=NULL, $plant=NULL){
		$this->general->connectDbPortal();

		$string	= "Select id, no_doc, text, tipe, account, tgl, bukrs, gsber, reff, info, cancel, data,
			replace(replace(data, 'img/acc/KUT1/2016/',''), 'assets/file/acc/uploadjurnal/', '') data2,
			in_date, in_datefirst, in_by, upload_date, no_doc + '_' +  gsber + '_' + cast(year(tgl) as char(4)) filename,
			case when datediff(D, cast(tgl as datetime), getdate()) >= 10 and ISNULL(data,'') in ('','-') then 'Tanggal Postng Jurnal Telah 10 Hari yang lalu, Silahkan Request Re-Upload' end remark
			from tbl_acc_upload_jurnal 
			where isnull(cancel,'') <> 'X' and data = '-' and alow_edit = 0 and isnull(reject_edit,0) <> 1 and gsber in ('".$plant."')";
		if(isset($id) && $id != ""){
			$string .= " AND id = ".$id."";				
		}
		$string	.= " order by edit_date";
		
		$query	= $this->db->query($string);
		$result	= $query->result();

		return $result;
	}

	function get_laporan_persentase($param = NULL){
		if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
			$this->general->connectDbPortal();

		$query = $this->db->query('EXEC SP_Kiranaku_laporan_accounting \'' . $param['type'] . '\',\'' . $param['year'] . '\'');
		
		// $query = $this->db->get();
		if (isset($param['single_row']) && $param['single_row'] !== NULL && $param['single_row'] == TRUE)
			$result = $query->row();
		else $result = $query->result();

		if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
			$this->general->closeDb();

		return $result;
	}

	function get_data_tahun($param = NULL){
		if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
			$this->general->connectDbPortal();

		$this->db->select('YEAR(tbl_acc_upload_jurnal.tgl) as tahun');
		$this->db->from('tbl_acc_upload_jurnal');
		$this->db->where('tbl_acc_upload_jurnal.del', 'n');
		$this->db->order_by('YEAR(tbl_acc_upload_jurnal.tgl)', 'ASC');
		$this->db->group_by('YEAR(tbl_acc_upload_jurnal.tgl)');
		
		$query = $this->db->get();
		if (isset($param['single_row']) && $param['single_row'] !== NULL && $param['single_row'] == TRUE)
			$result = $query->row();
		else $result = $query->result();

		if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
			$this->general->closeDb();

		return $result;
	}

}

?>
