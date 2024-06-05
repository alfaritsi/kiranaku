<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dapi extends CI_Model
{
    public function get_app_version()
    {
        $this->db->select('*');
        $this->db->from('tbl_mobile_apps');

        $this->db->where('package', 'com.kiranamegatara.ict.mobileRanger');

        $query = $this->db->get();

        return $query->row();
    }

    public function get_user_login($params = array())
    {
        $username = isset($params['username']) ? $params['username'] : null;
        $password = isset($params['password']) ? $params['password'] : null;
        $id_pabrik = isset($params['id_pabrik']) ? $params['id_pabrik'] : null;
        $depo = isset($params['depo']) ? $params['depo'] : null;

        $this->db->query("SET ANSI_NULLS ON");
        $this->db->query("SET ANSI_WARNINGS ON");
        $this->db->select('*');
        $this->db->select('lokasi as jenis');
        $this->db->from('vUserRanger');

        $this->db->where('is_aktif', true);

        if (isset($username))
            $this->db->where('uid', $username);
        if (isset($id_pabrik))
            $this->db->where('factory_cd', $id_pabrik);
        if (isset($depo))
            $this->db->where('nm_depo', $depo);
        if (isset($password))
            $this->db->where('password', "CONVERT(BINARY(50),'$password')", false);

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    public function get_user_ranger($params = array())
    {
        $username = isset($params['username']) ? $params['username'] : null;
        $password = isset($params['password']) ? $params['password'] : null;

        $this->db->query("SET ANSI_NULLS ON");
        $this->db->query("SET ANSI_WARNINGS ON");
        $this->db->select('factory_cd as id_pabrik');
        $this->db->select('uid as id_user');
        $this->db->select('user_nm as username');
        $this->db->from('vUserRanger');

        $this->db->where('is_aktif', true);

        if (isset($username))
            $this->db->where('uid', $username);
        if (isset($password))
            $this->db->where('password', "CONVERT(BINARY(50),'$password')", false);

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    public function get_plant($params = array())
    {
        $kode_pabrik = isset($params['kode_pabrik']) ? $params['kode_pabrik'] : null;

        $this->db->select('factory_cd as id');
        $this->db->select('factory_nm as name');
        $this->db->select('kode_pabrik as kode');
        $this->db->from('mpabrik');

        if (isset($kode_pabrik))
            $this->db->where('factory_cd', $kode_pabrik);

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    public function get_depo($params = array())
    {
        $id_pabrik = isset($params['id_pabrik']) ? $params['id_pabrik'] : null;
        $client = isset($params['client']) ? $params['client'] : 310;
        $kode_depo = isset($params['kode_depo']) ? $params['kode_depo'] : null;

        $this->db->select('factory_cd as id_pabrik');
        $this->db->select('client_sap as client');
        $this->db->select('kode_depo as kode');
        $this->db->select('nm_depo as nama');
        $this->db->select('kode_sj as kode_sj');
        $this->db->select('prov_nm as provinsi');
        $this->db->select('kab_nm as kabupaten');
        //        $this->db->select('*');
        $this->db->from('MNmDepo');

        $this->db->where('jns_dp', 'RNGER');

        if (isset($id_pabrik)) {
            if (is_array($id_pabrik))
                $this->db->where_in('factory_cd', $id_pabrik);
            else
                $this->db->where('factory_cd', $id_pabrik);
        }
        if (isset($client))
            $this->db->where('client_sap', $client);
        if (isset($kode_depo)) {
            if (is_array($kode_depo))
                $this->db->where_in('kode_depo', $kode_depo);
            else
                $this->db->where('kode_depo', $kode_depo);
        }

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    public function get_vendor($params = array())
    {
        $id_pabrik = isset($params['id_pabrik']) ? $params['id_pabrik'] : null;
        $client = isset($params['client']) ? $params['client'] : 310;
        $kode_vendor = isset($params['kode_vendor']) ? $params['kode_vendor'] : null;
        $group = isset($params['group']) ? $params['group'] : null;
        $lastSync = isset($params['lastSync']) ? $params['lastSync'] : null;

        $this->db->select('factory_cd as id_pabrik');
        $this->db->select('client_sap as client');
        $this->db->select('rtrim(ltrim(vendor_cd)) as kode');
        $this->db->select('vendor_grp as group');
        $this->db->select('vendor_nm as nama');
        $this->db->select('address1 as street');
        $this->db->select('house_no');
        $this->db->select('postal_cd as postal_code');
        $this->db->select('city');
        $this->db->select('npwp');
        $this->db->select('pph22');
        $this->db->select('status_pkp');
        $this->db->select('vendor_cat');
        $this->db->select('convert(datetime2,date_input,126) as timestamp');
        //        $this->db->select('*');
        $this->db->from('MVendor');

        if (isset($id_pabrik))
            $this->db->where('factory_cd', $id_pabrik);
        if (isset($client))
            $this->db->where('client_sap', $client);
        if (isset($kode_vendor))
            $this->db->where('vendor_cd', $kode_vendor);
        if (isset($group))
            $this->db->where('vendor_grp', $group);
        if (isset($lastSync))
            $this->db->where('convert(datetime2,date_input,126) >= convert(datetime2,\'' . $lastSync . '\',126)', null, true);

        $this->db->where('is_aktif', 1);

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    public function get_vendor_cat($params = array())
    {
        $id_pabrik = isset($params['id_pabrik']) ? $params['id_pabrik'] : null;
        $client = isset($params['client']) ? $params['client'] : 310;
        $kode = isset($params['kode']) ? $params['kode'] : null;

        $this->db->select('factory_cd as id_pabrik');
        $this->db->select('client_sap as client');
        $this->db->select('vendor_cat as kode');
        $this->db->select('deskripsi as nama');
        //        $this->db->select('*');
        $this->db->from('MVendor_Cat');

        if (isset($id_pabrik))
            $this->db->where('factory_cd', $id_pabrik);
        if (isset($client))
            $this->db->where('client_sap', $client);
        if (isset($kode))
            $this->db->where('vendor_cat', $kode);

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    public function get_vendor_eks($params = array())
    {
        $id_pabrik = isset($params['id_pabrik']) ? $params['id_pabrik'] : null;
        $client = isset($params['client']) ? $params['client'] : 310;
        $kode_vendor = isset($params['kode_vendor']) ? $params['kode_vendor'] : null;
        $group = isset($params['group']) ? $params['group'] : null;

        $this->db->select('factory_cd as id_pabrik');
        $this->db->select('client_sap as client');
        $this->db->select('rtrim(ltrim(vendor_cd)) as kode');
        $this->db->select('vendor_grp as group');
        $this->db->select('vendor_nm as nama');
        //        $this->db->select('*');
        $this->db->from('TMVDR_EKSP');

        if (isset($id_pabrik))
            $this->db->where('factory_cd', $id_pabrik);
        if (isset($client))
            $this->db->where('client_sap', $client);
        if (isset($kode_vendor))
            $this->db->where('vendor_cd', $kode_vendor);
        if (isset($group))
            $this->db->where('vendor_grp', $group);

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    public function get_vendor_rel($params = array())
    {
        $id_pabrik = isset($params['id_pabrik']) ? $params['id_pabrik'] : null;
        $client = isset($params['client']) ? $params['client'] : 310;
        $kode_vendor_a = isset($params['kode_vendor_a']) ? $params['kode_vendor_a'] : null;
        $kode_vendor_b = isset($params['kode_vendor_b']) ? $params['kode_vendor_b'] : null;
        $lastSync = isset($params['lastSync']) ? $params['lastSync'] : null;

        $this->db->select('MVendor_Rel.factory_cd as id_pabrik');
        $this->db->select('MVendor_Rel.client_sap as client');
        $this->db->select('rtrim(ltrim(vendor_cd_a)) as kode_a');
        $this->db->select('rtrim(ltrim(vendor_cd_b)) as kode_b');
        $this->db->select('convert(datetime2,MVendor_Rel.date_input,126) as timestamp');
        //        $this->db->select('*');
        $this->db->from('MVendor_Rel');

        $this->db->join('mvendor vendorA', 'vendorA.vendor_cd = mvendor_rel.vendor_cd_a and vendorA.client_sap = mvendor_rel.client_sap AND vendorA.Is_Aktif = 1', 'inner');
        $this->db->join('mvendor as vendorB', 'vendorB.vendor_cd = mvendor_rel.vendor_cd_b and vendorB.client_sap = mvendor_rel.client_sap AND vendorB.Is_Aktif = 1', 'inner');

        $this->db->where('vendorA.vendor_cd is not null', null, true);
        $this->db->where('vendorB.vendor_cd is not null', null, true);

        $this->db->where('isnull(vendor_cd_a, \'\') <> \'\'', null, true);
        $this->db->where('isnull(vendor_cd_b, \'\') <> \'\'', null, true);

        if (isset($id_pabrik))
            $this->db->where('MVendor_Rel.factory_cd', $id_pabrik);
        if (isset($client))
            $this->db->where('MVendor_Rel.client_sap', $client);
        if (isset($kode_vendor_a))
            $this->db->where('vendor_cd_a', $kode_vendor_a);
        if (isset($kode_vendor_b))
            $this->db->where('vendor_cd_b', $kode_vendor_b);
        if (isset($lastSync))
            $this->db->where('convert(datetime2,MVendor_Rel.date_input,126) >= convert(datetime2,\'' . $lastSync . '\',126)', null, true);

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    public function get_angkutan($params = array())
    {
        $id_pabrik = isset($params['id_pabrik']) ? $params['id_pabrik'] : null;
        $client = isset($params['client']) ? $params['client'] : 310;
        $kode = isset($params['kode']) ? $params['kode'] : null;

        $this->db->select('factory_cd as id_pabrik');
        $this->db->select('client_sap as client');
        $this->db->select('kd_angkt as kode');
        $this->db->select('nm_angkt as nama');
        $this->db->select('qty as qty');
        //        $this->db->select('*');
        $this->db->from('MEkspedisi');

        if (isset($id_pabrik))
            $this->db->where('factory_cd', $id_pabrik);
        if (isset($client))
            $this->db->where('client_sap', $client);
        if (isset($kode))
            $this->db->where('kd_angkt', $kode);

        //        $this->db->where('is_eksp', true);

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    public function get_provinsi($params = array())
    {
        $id_pabrik = isset($params['id_pabrik']) ? $params['id_pabrik'] : null;
        $client = isset($params['client']) ? $params['client'] : 310;
        $kode = isset($params['kode']) ? $params['kode'] : null;

        $this->db->select('factory_cd as id_pabrik');
        $this->db->select('client_sap as client');
        $this->db->select('kode_prov as kode');
        $this->db->select('prov_nm as nama');
        //        $this->db->select('*');
        $this->db->from('MProvinsi');

        if (isset($id_pabrik))
            $this->db->where('factory_cd', $id_pabrik);
        if (isset($client))
            $this->db->where('client_sap', $client);
        if (isset($kode))
            $this->db->where('kode_prov', $kode);

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    public function get_kabupaten($params = array())
    {
        $id_pabrik = isset($params['id_pabrik']) ? $params['id_pabrik'] : null;
        $client = isset($params['client']) ? $params['client'] : 310;
        $kode = isset($params['kode']) ? $params['kode'] : null;
        $kode_prov = isset($params['kode_prov']) ? $params['kode_prov'] : null;

        $this->db->select('factory_cd as id_pabrik');
        $this->db->select('client_sap as client');
        $this->db->select('kode_kab as kode');
        $this->db->select('kode_prov as kode_prov');
        $this->db->select('kab_nm as nama');
        //        $this->db->select('*');
        $this->db->from('MKabupaten');

        if (isset($id_pabrik))
            $this->db->where('factory_cd', $id_pabrik);
        if (isset($client))
            $this->db->where('client_sap', $client);
        if (isset($kode))
            $this->db->where('kode_kab', $kode);
        if (isset($kode_prov))
            $this->db->where('kode_prov', $kode_prov);

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    public function get_subarea($params = array())
    {
        $id_pabrik = isset($params['id_pabrik']) ? $params['id_pabrik'] : null;
        $client = isset($params['client']) ? $params['client'] : 310;
        $depo = isset($params['depo']) ? $params['depo'] : null;

        $this->db->select('factory_cd as id_pabrik');
        $this->db->select('client_sap as client');
        $this->db->select('nm_depo as nama_depo');
        $this->db->select('sub_area as nama');
        $this->db->from('MSubArea');

        if (isset($id_pabrik))
            $this->db->where('factory_cd', $id_pabrik);
        if (isset($client))
            $this->db->where('client_sap', $client);
        if (isset($depo))
            $this->db->where('nm_depo', $depo);

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    public function get_pembelian($params = array())
    {
        $id_pabrik = isset($params['id_pabrik']) ? $params['id_pabrik'] : null;
        $client = isset($params['client']) ? $params['client'] : 310;
        $depo = isset($params['depo']) ? $params['depo'] : null;
        $uid = isset($params['uid']) ? $params['uid'] : null;
        $id_transaksi = isset($params['id_transaksi']) ? $params['id_transaksi'] : null;
        $nota_timbang = isset($params['nota_timbang']) ? $params['nota_timbang'] : null;
        $order_by = isset($params['order_by']) ? $params['order_by'] : 'TRekap_Timbang_Depo.trans_date asc';

        $this->db->select('TRekap_Timbang_Depo.factory_cd as id_pabrik');
        $this->db->select('TRekap_Timbang_Depo.client_sap as client');
        $this->db->select('TRekap_Timbang_Depo.trans_no as id_transaksi');
        $this->db->select('TRekap_Timbang_Depo.no_kpb as nota_timbang');
        $this->db->select('TRekap_Timbang_Depo.trans_date as tanggal_transaksi');
        $this->db->select('TRekap_Timbang_Depo.vendor_hdr_cd as id_pic');
        $this->db->select('TRekap_Timbang_Depo.vendor_cld_cd as id_vendor');
        $this->db->select('TRekap_Timbang_Depo.type_trn as jenis_po');
        $this->db->select('TRekap_Timbang_Depo.item_cd as kode_barang');
        $this->db->select('TRekap_Timbang_Depo.asal_getah as asal_getah');
        $this->db->select('TRekap_Timbang_Depo.getah as jenis_bokar');
        $this->db->select('TRekap_Timbang_Depo.batch as no_batch');
        $this->db->select('TRekap_Timbang_Depo.plat_no as nomor_mobil');
        $this->db->select('TRekap_Timbang_Depo.metode_pmbyr as metode_pembayaran');

        $this->db->select('TRekap_Timbang_Depo.drc');
        $this->db->select('TRekap_Timbang_Depo.qty_wet as berat_basah');
        $this->db->select('TRekap_Timbang_Depo.price_wet as harga_basah');
        $this->db->select('TRekap_Timbang_Depo.qty_dry as berat_kering');
        $this->db->select('TRekap_Timbang_Depo.price_dry as harga_kering');

        $this->db->select('TRekap_Timbang_Depo.geo_tag as koordinat');

        $this->db->select('TRekap_Timbang_Depo.nm_depo as depo');
        $this->db->select('TRekap_Timbang_Depo.plant_afl as pabrik_afliasi');
        $this->db->select('TRekap_Timbang_Depo.uid as id_user');

        $this->db->select('TFaktur.Trans_No as id_faktur');
        $this->db->select('TFaktur.No_Fak as nomor_faktur');
        $this->db->select('TFaktur.Ongkos_Bongkar as biaya_bongkar');
        //
        //        $this->db->select('TSRT_JLN_DTL.no_srt as id_pengiriman');
        //        $this->db->select('TSRT_JLN_DTL.qty as berat_sample');

        $this->db->from('TRekap_Timbang_Depo');

        $this->db->join('TFaktur', 'TRekap_Timbang_Depo.No_KPB = TFaktur.No_KPB
            AND TRekap_Timbang_Depo.Trans_No = TFaktur.Trans_No_KPB
            AND TRekap_Timbang_Depo.client_sap = TFaktur.client_sap
            AND TRekap_Timbang_Depo.factory_cd = TFaktur.factory_cd
            AND TRekap_Timbang_Depo.nm_depo = TFaktur.nm_depo  
            AND TFaktur.Date_Input = (SELECT TOP 1 TFaktur2.Date_Input FROM TFaktur as TFaktur2 WHERE TFaktur2.Trans_No_KPB = TFaktur.Trans_No_KPB AND TFaktur2.client_sap = TFaktur.client_sap ORDER BY TFaktur2.Date_Input DESC) ', 'left');
        //        $this->db->join('TSRT_JLN_DTL', 'TRekap_Timbang_Depo.No_KPB = TSRT_JLN_DTL.no_kpb
        //            AND TRekap_Timbang_Depo.client_sap = TSRT_JLN_DTL.client_sap
        //            AND TRekap_Timbang_Depo.factory_cd = TSRT_JLN_DTL.factory_cd
        //            AND TRekap_Timbang_Depo.nm_depo = TSRT_JLN_DTL.nm_depo ', 'left outer');

        $this->db->where(
            'convert(date, TRekap_Timbang_Depo.Trans_Date) >=',
            date_create()->modify('-1 month')->format('Y-m-d')
        );

        if (isset($id_pabrik))
            $this->db->where('TRekap_Timbang_Depo.factory_cd', $id_pabrik);
        if (isset($client))
            $this->db->where('TRekap_Timbang_Depo.client_sap', $client);
        if (isset($uid))
            $this->db->where('TRekap_Timbang_Depo.uid', $uid);
        if (isset($depo))
            $this->db->where('TRekap_Timbang_Depo.nm_depo', $depo);
        if (isset($id_transaksi))
            $this->db->where('TRekap_Timbang_Depo.trans_no', $id_transaksi);
        if (isset($nota_timbang))
            $this->db->where('TRekap_Timbang_Depo.no_kpb', $nota_timbang);

        $this->db->order_by($order_by);

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    public function get_faktur($params = array())
    {
        $id_pabrik = isset($params['id_pabrik']) ? $params['id_pabrik'] : null;
        $client = isset($params['client']) ? $params['client'] : 310;
        $uid = isset($params['uid']) ? $params['uid'] : null;
        $depo = isset($params['depo']) ? $params['depo'] : null;
        $nomor_faktur = isset($params['nomor_faktur']) ? $params['nomor_faktur'] : null;
        $nomor_faktur_tkr = isset($params['nomor_faktur_tkr']) ? $params['nomor_faktur_tkr'] : null;
        $id_faktur = isset($params['id_faktur']) ? $params['id_faktur'] : null;
        $onlyMonthly = isset($params['onlyMonthly']) ? $params['onlyMonthly'] : true;
        $null_faktur = isset($params['null_faktur']) ? $params['null_faktur'] : null;
        $not_null_faktur = isset($params['not_null_faktur']) ? $params['not_null_faktur'] : null;
        $tanggal_transaksi = isset($params['tanggal_transaksi']) ? $params['tanggal_transaksi'] : null;
        $order_by = isset($params['order_by']) ? $params['order_by'] : 'no_fak asc';

        $this->db->select('factory_cd as id_pabrik');
        $this->db->select('client_sap as client');
        $this->db->select('trans_no as id_faktur');
        $this->db->select('no_fak as nomor_faktur');
        $this->db->select('no_fak_tkr as nomor_faktur_tkr');
        $this->db->select('vendor_hdr_cd as id_pic');
        $this->db->select('vendor_cld_cd as id_vendor');

        $this->db->select('trans_no_kpb as id_transaksi');
        $this->db->select('no_kpb as nota_timbang');

        $this->db->select('materai as biaya_materai');
        $this->db->select('ongkos_bongkar as biaya_bongkar');

        $this->db->select('nm_depo as depo');
        $this->db->select('uid as id_user');

        $this->db->select('Tgl_Bukti as tanggal_transaksi');
        $this->db->select('CONVERT(VARCHAR, Date_Input, 121) as tanggal_input');

        $this->db->from('TFaktur');

        if (isset($id_pabrik))
            $this->db->where('factory_cd', $id_pabrik);
        if (isset($client))
            $this->db->where('client_sap', $client);
        if (isset($uid))
            $this->db->where('uid', $uid);
        if (isset($depo))
            $this->db->where('nm_depo', $depo);
        if (isset($null_faktur)) {
            $this->db->group_start();
            $this->db->where('no_fak is null or no_fak = \'\'', null, false);
            $this->db->group_end();
        }
        if (isset($not_null_faktur)) {
            $this->db->group_start();
            $this->db->where('no_fak is not null or no_fak <> \'\'', null, false);
            $this->db->group_end();
        }
        if (isset($nomor_faktur))
            $this->db->where('no_fak', $nomor_faktur);
        if (isset($nomor_faktur_tkr))
            $this->db->where('no_fak_tkr', $nomor_faktur_tkr);
        if (isset($id_faktur))
            $this->db->where('trans_no', $id_faktur);

        if ($onlyMonthly) {
            $this->db->where(
                'convert(date, Date_Input) >=',
                date_create()->modify('-1 month')->format('Y-m-d')
            );
        }

        if (isset($client)) {
            $this->db->where(
                'Date_Input = (SELECT TOP 1 TFaktur2.Date_Input
                                 FROM [TFaktur] TFaktur2
                                WHERE TFaktur2.Trans_No_KPB = [TFaktur].Trans_No_KPB
                                  AND TFaktur2.client_sap = \'' . $client . '\'
                                ORDER BY TFaktur2.Date_Input DESC)'
            );
        }
        if (isset($tanggal_transaksi))
            $this->db->where('Tgl_Bukti <=', $tanggal_transaksi);

        $this->db->order_by($order_by);

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    public function get_faktur_compact($params = array())
    {
        $id_pabrik = isset($params['id_pabrik']) ? $params['id_pabrik'] : null;
        $client = isset($params['client']) ? $params['client'] : 310;
        $uid = isset($params['uid']) ? $params['uid'] : null;
        $depo = isset($params['depo']) ? $params['depo'] : null;
        $nomor_faktur = isset($params['nomor_faktur']) ? $params['nomor_faktur'] : null;

        $this->db->distinct();
        $this->db->select('factory_cd as id_pabrik');
        $this->db->select('client_sap as client');
        $this->db->select('no_fak as nomor_faktur');
        $this->db->select('no_fak_tkr as nomor_faktur_tkr');
        $this->db->select('vendor_hdr_cd as id_pic');
        $this->db->select('vendor_cld_cd as id_vendor');

        $this->db->select('materai as biaya_materai');
        $this->db->select('ppn_ongkos_bongkar as ppn_biaya_bongkar');

        $this->db->select('nm_depo as depo');
        $this->db->select('uid as id_user');

        $this->db->select('Tgl_Bukti as tanggal_transaksi');
        $this->db->select('CONVERT(VARCHAR, Date_Input, 121) as tanggal_input');
        $this->db->select('CONVERT(INT, SUBSTRING(no_fak, 10, 4)) as thn');
        $this->db->select('CONVERT(INT, SUBSTRING(no_fak, 7, 2)) as bln');
        $this->db->select('CONVERT(INT, SUBSTRING(no_fak, 0, 6)) as running_number');

        $this->db->from('TFaktur');

        $this->db->where(
            'convert(date, Tgl_Bukti) >=',
            date_create()->modify('-1 month')->format('Y-m-d')
        );

        if (isset($id_pabrik))
            $this->db->where('factory_cd', $id_pabrik);
        if (isset($client))
            $this->db->where('client_sap', $client);
        if (isset($uid))
            $this->db->where('uid', $uid);
        if (isset($depo))
            $this->db->where('nm_depo', $depo);
        if (isset($nomor_faktur))
            $this->db->where('no_fak', $nomor_faktur);

        if (isset($client)) {
            $this->db->where(
                'Date_Input = (SELECT TOP 1 TFaktur2.Date_Input
                                 FROM [TFaktur] TFaktur2
                                WHERE TFaktur2.Trans_No_KPB = [TFaktur].Trans_No_KPB
                                  AND TFaktur2.client_sap = \'' . $client . '\'
                                ORDER BY TFaktur2.Date_Input DESC)'
            );
        }

        $this->db->order_by('thn', 'asc');
        $this->db->order_by('bln', 'asc');
        $this->db->order_by('running_number', 'asc');

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    public function get_faktur_pembelian($params = array())
    {
        $id_pabrik = isset($params['id_pabrik']) ? $params['id_pabrik'] : null;
        $client = isset($params['client']) ? $params['client'] : 310;
        $depo = isset($params['depo']) ? $params['depo'] : null;
        $uid = isset($params['uid']) ? $params['uid'] : null;
        $nomor_faktur = isset($params['nomor_faktur']) ? $params['nomor_faktur'] : null;
        $id_faktur = isset($params['id_faktur']) ? $params['id_faktur'] : null;
        $onlyMonthly = isset($params['onlyMonthly']) ? $params['onlyMonthly'] : true;

        $this->db->distinct();
        $this->db->select('trans_no as id_faktur');
        $this->db->select('trans_no_kpb as id_transaksi');
        $this->db->select('no_kpb as nota_timbang');
        $this->db->select('no_fak as nomor_faktur');
        $this->db->select('ongkos_bongkar as biaya_bongkar');
        $this->db->select('ppn_ongkos_bongkar as ppn_biaya_bongkar');
        $this->db->select('Tgl_Bukti as tanggal_transaksi');
        $this->db->select('Date_Input as tanggal_input');

        $this->db->from('TFaktur');

        if (isset($id_pabrik))
            $this->db->where('factory_cd', $id_pabrik);
        if (isset($client))
            $this->db->where('client_sap', $client);
        if (isset($depo))
            $this->db->where('nm_depo', $depo);
        if (isset($uid))
            $this->db->where('uid', $uid);
        if (isset($nomor_faktur))
            $this->db->where('no_fak', $nomor_faktur);
        if (isset($id_faktur))
            $this->db->where('trans_no', $id_faktur);

        if ($onlyMonthly) {
            $this->db->where(
                'convert(date, Tgl_Bukti) >=',
                date_create()->modify('-1 month')->format('Y-m-d')
            );
        }

        if (isset($client)) {
            $this->db->where(
                'TFaktur.Date_Input = (SELECT TOP 1 TFaktur2.Date_Input
                                        FROM [TFaktur] TFaktur2
                                        WHERE TFaktur2.Trans_No_KPB = [TFaktur].Trans_No_KPB
                                        AND TFaktur2.client_sap = \'' . $client . '\'
                                        ORDER BY TFaktur2.Date_Input DESC)'
            );
        }

        $this->db->order_by('tanggal_input', 'ASC');

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    public function get_pengiriman($params = array())
    {
        $id_pabrik = isset($params['id_pabrik']) ? $params['id_pabrik'] : null;
        $client = isset($params['client']) ? $params['client'] : 310;
        $depo = isset($params['depo']) ? $params['depo'] : null;
        $uid = isset($params['uid']) ? $params['uid'] : null;
        $id_pengiriman = isset($params['id_pengiriman']) ? $params['id_pengiriman'] : null;
        $tahun = isset($params['tahun']) ? $params['tahun'] : null;
        $order_by = isset($params['order_by']) ? $params['order_by'] : 'no_srt asc';

        $this->db->select('factory_cd as id_pabrik');
        $this->db->select('client_sap as client');
        $this->db->select('batch as no_batch');
        $this->db->select('no_srt as id_pengiriman');
        $this->db->select('vendor_cd as id_vendor_ekspedisi');

        $this->db->select('factory_cd_dst as id_pabrik_tujuan');

        $this->db->select('jenis_getah as jenis_getah');
        $this->db->select('jns_kend as jenis_kendaraan_nama');
        $this->db->select('nm_sopir as nama_supir');
        $this->db->select('plat_no as nomor_polisi');
        $this->db->select('qty as tonase_kirim');

        $this->db->select('date_input as tanggal_berangkat');
        $this->db->select('jam_krm as jam_berangkat');

        $this->db->select('nm_depo as depo');
        $this->db->select('uid as id_user');

        $this->db->select('trans_date as tanggal_transaksi');

        $this->db->from('TSRT_JLN');

        $this->db->where(
            'convert(date, Date_Input) >=',
            date_create()->modify('-1 month')->format('Y-m-d')
        );

        if (isset($id_pabrik))
            $this->db->where('factory_cd', $id_pabrik);
        if (isset($client))
            $this->db->where('client_sap', $client);
        if (isset($uid))
            $this->db->where('uid', $uid);
        if (isset($depo))
            $this->db->where('nm_depo', $depo);
        if (isset($id_pengiriman))
            $this->db->where('no_srt', $id_pengiriman);
        if (isset($tahun))
            $this->db->where('YEAR(Date_Input)', $tahun);

        $this->db->order_by($order_by);

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    public function get_pengiriman_pembelian($params = array())
    {
        $id_pabrik = isset($params['id_pabrik']) ? $params['id_pabrik'] : null;
        $client = isset($params['client']) ? $params['client'] : 310;
        $uid = isset($params['uid']) ? $params['uid'] : null;
        $depo = isset($params['depo']) ? $params['depo'] : null;
        $id_pengiriman = isset($params['id_pengiriman']) ? $params['id_pengiriman'] : null;

        $this->db->select('trans_no_kpb as id_transaksi');
        $this->db->select('no_kpb as nota_timbang');
        $this->db->select('no_srt as id_pengiriman');
        $this->db->select('qty as berat_sample');

        $this->db->from('TSRT_JLN_DTL');

        $this->db->where(
            'convert(date, Date_Input) >=',
            date_create()->modify('-1 month')->format('Y-m-d')
        );

        if (isset($id_pabrik))
            $this->db->where('factory_cd', $id_pabrik);
        if (isset($client))
            $this->db->where('client_sap', $client);
        if (isset($uid))
            $this->db->where('uid', $uid);
        if (isset($depo))
            $this->db->where('nm_depo', $depo);
        if (isset($id_pengiriman))
            $this->db->where('no_srt', $id_pengiriman);

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    function get_vendor_contract($params = array())
    {
        $id_pabrik = isset($params['id_pabrik']) ? $params['id_pabrik'] : null;
        $client = isset($params['client']) ? $params['client'] : 310;
        $depo = isset($params['depo']) ? $params['depo'] : null;

        $this->db->select("
            [Mkontrak].[Client_SAP] as client,
            [Mkontrak].[Contract_No] as no_kontrak,
            [Mkontrak].[Vendor_Cd] as kode,
            [MVendor].[Vendor_Nm] as nama_vendor,
            [MVendor].[Vendor_Grp] as group_vendor,
            [MVendor].[Vendor_Cat] as cat_vendor,
            [Mkontrak].[Factory_Cd] as pabrik,
            [Mkontrak].[nm_depo] as depo,
            [Mkontrak].[Valid_From] as tgl_mulai,
            [Mkontrak].[Valid_To] as tgl_akhir,
            [Mkontrak].[Qty] as qty_kontrak,
            ([Mkontrak].[Qty] * 1.05) as qty_max,
            ISNULL([Transaksi].[Qty_Beli], 0) as qty_beli,
            [Mkontrak].[Price] as harga,
            ([Mkontrak].[Price] - 50) as harga_min,
            ([Mkontrak].[Price] + 10) as harga_max,
            CASE 
                WHEN [Mkontrak].[Qty] - ISNULL([Transaksi].[Qty_Beli], 0) > 0 THEN [Mkontrak].[Qty] - ISNULL([Transaksi].[Qty_Beli], 0)
                ELSE 0
            END as selisih,
            CASE 
                WHEN ([Mkontrak].[Qty] * 1.05) - ISNULL([Transaksi].[Qty_Beli], 0) > 0 THEN ([Mkontrak].[Qty] * 1.05) - ISNULL([Transaksi].[Qty_Beli], 0)
                ELSE 0
            END as selisih_max,
            [Mkontrak].[Status]
        ");
        $this->db->from('Mkontrak');
        $this->db->join(
            "MNmDepo",
            "MNmDepo.client_sap = Mkontrak.Client_SAP
            AND MNmDepo.factory_cd = Mkontrak.Factory_Cd
            AND MNmDepo.nm_depo = Mkontrak.nm_depo
            AND MNmDepo.Jns_Dp = 'RNGER'",
            "INNER"
        );
        $this->db->join(
            "MVendor",
            "MVendor.Client_SAP = Mkontrak.Client_SAP
            AND MVendor.Factory_Cd = Mkontrak.Factory_Cd
            AND MVendor.Vendor_Cd = Mkontrak.Vendor_Cd
            AND MVendor.Is_Aktif = 1",
            "INNER"
        );
        $this->db->join(
            "(
                SELECT Client_SAP,
                       Factory_Cd,
                       Contract_No,
                       SUM(Qty_Dry) as Qty_Beli
                  FROM TRekap_Timbang_Depo
                 WHERE Contract_No IS NOT NULL AND Contract_No <> ''
                 GROUP BY Client_SAP,
                          Factory_Cd,
                          Contract_No 
             ) Transaksi",
            "Transaksi.Client_SAP = Mkontrak.Client_SAP
            AND Transaksi.Factory_Cd = Mkontrak.Factory_Cd
			AND Transaksi.Contract_No = Mkontrak.Contract_No",
            "LEFT"
        );

        if (isset($id_pabrik))
            $this->db->where('Mkontrak.Factory_Cd', $id_pabrik);
        if (isset($client))
            $this->db->where('Mkontrak.Client_SAP', $client);
        if (isset($depo))
            $this->db->where('Mkontrak.nm_depo', $depo);

        $this->db->where('CONVERT(DATE, GETDATE()) BETWEEN Mkontrak.Valid_From AND Mkontrak.Valid_To');
        $this->db->where('
            (
                CASE 
                    WHEN ([Mkontrak].[Qty] * 1.05) - ISNULL([Transaksi].[Qty_Beli], 0) > 0 THEN ([Mkontrak].[Qty] * 1.05) - ISNULL([Transaksi].[Qty_Beli], 0)
                    ELSE 0
                END
            ) > 0
        ');

        $this->db->group_by("
            [Mkontrak].[Client_SAP],
            [Mkontrak].[Contract_No],
            [Mkontrak].[Vendor_Cd],
            [MVendor].[Vendor_Nm],
            [MVendor].[Vendor_Grp],
            [MVendor].[Vendor_Cat],
            [Mkontrak].[Factory_Cd],
            [Mkontrak].[nm_depo],
            [Mkontrak].[Valid_From],
            [Mkontrak].[Valid_To],
            [Mkontrak].[Qty],
            ([Mkontrak].[Qty] * 1.05),
            ISNULL([Transaksi].[Qty_Beli], 0),
            [Mkontrak].[Price],
            ([Mkontrak].[Price] - 50),
            ([Mkontrak].[Price] + 10),
            [Mkontrak].[Status]
        ");

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }
}
