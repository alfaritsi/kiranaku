<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @application  : Info Karyawan - Model
 * @author     : Octe Reviyanto Nugroho
 * @contributor  :
 * 1. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * 2. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * etc.
 */
class Dinfokaryawan extends CI_Model
{
	
    public function get_karyawan($pabrik = array("KMTR"),$all = false)
    {
        $this->db->select("tbl_karyawan.*,tbl_departemen.nama as nama_departemen");
        $this->db->select("tbl_divisi.nama as nama_divisi");
        $this->db->select("tbl_sub_divisi.nama as nama_sub_divisi");
        $this->db->select("tbl_seksi.nama as nama_seksi");
        $this->db->select("tbl_wf_master_plant.plant_name as nama_pabrik");
        $this->db->from('tbl_karyawan');
        $this->db->join('tbl_user','tbl_karyawan.id_karyawan = tbl_user.id_karyawan','left outer');
        $this->db->join('tbl_level','tbl_user.id_level= tbl_level.id_level','left outer');
        $this->db->join('tbl_departemen','tbl_departemen.id_departemen= tbl_user.id_departemen','left outer');
        $this->db->join('tbl_divisi','tbl_divisi.id_divisi= tbl_user.id_divisi','left outer');
        $this->db->join('tbl_sub_divisi','tbl_sub_divisi.id_sub_divisi= tbl_user.id_sub_divisi','left outer');
        $this->db->join('tbl_seksi','tbl_seksi.id_seksi= tbl_user.id_seksi','left outer');
        $this->db->join('tbl_wf_master_plant','tbl_wf_master_plant.plant= tbl_karyawan.gsber','left outer');

        if (!isset($all)) {
			$this->db->where('tbl_karyawan.del', 'n');
        }

        if(isset($pabrik))
            $this->db->where_in("tbl_karyawan.gsber",$pabrik);

        $this->db->where('tbl_karyawan.na', 'n');
        if(in_array('KMTR',$pabrik))
        {

            $this->db->or_where("(tbl_karyawan.na = 'n' and tbl_karyawan.ho = 'y' and tbl_karyawan.id_karyawan!='73560001' 
            and tbl_karyawan.id_karyawan!='73560002' 
            and tbl_user.id_karyawan!='6724' 
            and tbl_user.id_karyawan!='6725' 
            and tbl_karyawan.id_karyawan !='73560003' 
            and tbl_karyawan.id_karyawan !='10000001' 
            and tbl_karyawan.email like '%kiranamegatara%')", NULL, FALSE);
        }

        $this->db->order_by('tbl_karyawan.id_karyawan', 'DESC');
        $query = $this->db->get();
        $result = $query->result();

        return $result;
    }
	function get_data_karyawan_bom($conn = NULL, $lokasi = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		$this->datatables->select('tbl_karyawan.id_karyawan');
		$this->datatables->select('tbl_karyawan.nama');
		$this->datatables->select('tbl_karyawan.nik');
		$this->datatables->select('tbl_karyawan.ho');
		$this->datatables->select('tbl_karyawan.email');
		$this->datatables->select('tbl_karyawan.telepon');
		$this->datatables->select('tbl_departemen.nama as nama_departemen');
		$this->datatables->select('tbl_divisi.nama as nama_divisi');
		$this->datatables->select('tbl_seksi.nama as nama_seksi');
		$this->datatables->select("tbl_sub_divisi.nama as nama_sub_divisi");
		$this->datatables->select('tbl_wf_master_plant.plant_name');
		$this->datatables->from('tbl_karyawan');				
        $this->datatables->join('tbl_user','tbl_karyawan.id_karyawan = tbl_user.id_karyawan','left outer');
        $this->datatables->join('tbl_level','tbl_user.id_level= tbl_level.id_level','left outer');
        $this->datatables->join('tbl_departemen','tbl_departemen.id_departemen= tbl_user.id_departemen','left outer');
        $this->datatables->join('tbl_divisi','tbl_divisi.id_divisi= tbl_user.id_divisi','left outer');
        $this->datatables->join('tbl_sub_divisi','tbl_sub_divisi.id_sub_divisi= tbl_user.id_sub_divisi','left outer');
        $this->datatables->join('tbl_seksi','tbl_seksi.id_seksi= tbl_user.id_seksi','left outer');
        $this->datatables->join('tbl_wf_master_plant','tbl_wf_master_plant.plant= tbl_karyawan.gsber','left outer');
		// if($lokasi != NULL){
			// if(is_string($lokasi)) $lokasi = explode(",", $lokasi);
			// $this->datatables->where_in('tbl_karyawan.gsber', $lokasi);
			// if (in_array('KMTR', $lokasi) == true) {
				// $this->datatables->or_where("tbl_karyawan.na = 'n' and tbl_karyawan.ho='y'");
			// }
			
		// }
			
		$this->datatables->where("tbl_karyawan.email like '%kiranamegatara%' and tbl_karyawan.na='n' and tbl_karyawan.id_karyawan!='73560001' and tbl_karyawan.id_karyawan!='73560002' and tbl_user.id_karyawan!='6724' and tbl_user.id_karyawan!='6725' and tbl_karyawan.id_karyawan !='73560003' and tbl_karyawan.id_karyawan !='10000001' ");
		if(isset($lokasi)){
            $this->datatables->group_start();            
            $this->datatables->where_in("tbl_karyawan.gsber",$lokasi);
			if(in_array('KMTR',$lokasi)){
				$this->datatables->or_where("(tbl_karyawan.ho='y' and tbl_karyawan.na='n' and tbl_karyawan.id_karyawan!='73560001' and tbl_karyawan.id_karyawan!='73560002' and tbl_user.id_karyawan!='6724' and tbl_user.id_karyawan!='6725' and tbl_karyawan.id_karyawan !='73560003' and tbl_karyawan.id_karyawan !='10000001' and tbl_karyawan.email like '%kiranamegatara%')");
			}
            $this->datatables->group_end();            
		}
        // // $this->datatables->where('tbl_karyawan.na', 'n');
        // if(isset($lokasi))
            // $this->datatables->where_in("tbl_karyawan.gsber",$lokasi);
		
        // if(in_array('KMTR',$lokasi))
        // {
            // $this->datatables->or_where("(tbl_karyawan.na = 'n' and tbl_karyawan.ho = 'y' and tbl_karyawan.id_karyawan!='73560001' 
            // and tbl_karyawan.id_karyawan!='73560002' 
            // and tbl_user.id_karyawan!='6724' 
            // and tbl_user.id_karyawan!='6725' 
            // and tbl_karyawan.id_karyawan !='73560003' 
            // and tbl_karyawan.id_karyawan !='10000001' 
            // and tbl_karyawan.email like '%kiranamegatara%')");
        // }
		
		if ($conn !== NULL)
			$this->general->closeDb();

		$return = $this->datatables->generate();
		$raw = json_decode($return, true);
		$raw['data'] = $this->general->generate_encrypt_json($raw['data'], array("id_karyawan"));
		// $raw['data'] = $this->general->generate_encrypt_json($raw['data']);
		return $this->general->jsonify($raw);
	}	
	
}