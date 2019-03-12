<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_salesorder extends CI_Model {

	var $table = '(
				   select a.No_So,a.Tgl_So,a.Nm_Cust,a.Jns_Bayar,a.Status,a.Status_Bast,a.Cetak
				   from tb_so a
				   left join tb_so_detail b on a.no_so = b.fk_so
				   left join tb_stock c on b.no_mesin = c.no_mesin
				   left join tb_tipe d on d.kd_tipe = c.kd_tipe
                   left join tb_warna e on e.kd_warna = c.kd_warna
                   Where a.Status != "Batal"
				   ) A'; 
    var $column_order = array(null, 'No_So','Tgl_So','Nm_Cust','Jns_Bayar','Status'); 
    var $column_search = array('No_So','Tgl_So','Nm_Cust','Jns_Bayar','Status'); //field yang diizin untuk pencarian 
    var $order = array('No_So' => 'asc'); 
 
    private function _get_datatables_query_salesorder()
    {         
        $this->db->from($this->table);
 
        $i = 0;
     
        foreach ($this->column_search as $item) // looping awal
        {
            if($_POST['search']['value']) // jika datatable mengirimkan pencarian dengan metode POST
            {
                 
                if($i===0) // looping awal
                {
                    $this->db->group_start(); 
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
 
                if(count($this->column_search) - 1 == $i) 
                    $this->db->group_end(); 
            }
            $i++;
        }
         
        if(isset($_POST['order'])) 
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
 
    function get_datatables_salesorder()
    {
        $this->_get_datatables_query_salesorder();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
 
    function count_filtered()
    {
        $this->_get_datatables_query_salesorder();
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
	}
 
    function cek_no_so($no_so)
	{
		return $this->db
			->select('no_so')
			->where('no_so', $no_so)
			->where('Status_Piutang', 'A')
			->limit(1)
			->get('tb_piutang');
    }
    

    public function viewByNoSpk($NoSpk){	
        $this->db->select('a.Nm_Cust,a.Kd_Cust,a.Alamat,a.Kd_Salesman,a.Jns_Bayar,a.Kd_Fincoy,a.No_Mesin,
                          b.No_Chassis,c.Tipe,d.Warna,a.Jml_Harga,a.Tenor,a.Tipe_Angs,a.Angsuran,
                          a.Tenor');
        $this->db->where('a.No_Spk', $NoSpk);
		$this->db->join('tb_stock b','a.No_Mesin = b.No_Mesin');
        $this->db->join('tb_tipe c','c.Kd_Tipe = b.Kd_Tipe');
        $this->db->join('tb_warna d','d.Kd_Warna = b.Kd_Warna');
		$result = $this->db->get('tb_spk a')->row(); 		
		return $result; 
    }

    public function ambilDataSalesOrder(){	
        $this->db->select('a.No_So,a.Nm_Cust,a.Alamat,b.No_Mesin,a.Jns_Bayar,a.By_Tunai,a.Ttl_Hrg_Otr,
                          a.Tenor,a.Angsuran,b.Diskon,d.Tipe,e.Warna,a.Bunga,a.Kd_Cust,a.Tgl_So,a.Kd_Fincoy,
                          a.ADM,f.Nm_Cust as Nm_Fincoy,a.DP,
                          case when a.Jns_Bayar = '."'Tunai'".' then a.By_Tunai when a.Jns_Bayar =  '."'Kredit'".' then a.Ttl_Hrg_Otr end as Hrg_Jual');
        $this->db->join('tb_so_detail b','a.No_So = b.Fk_So');
        $this->db->join('tb_stock c','b.No_Mesin = c.No_Mesin');
        $this->db->join('tb_tipe d','d.Kd_Tipe = c.Kd_Tipe');
        $this->db->join('tb_warna e','e.Kd_Warna = c.Kd_Warna');
        $this->db->join('tb_customer f','f.Kd_Cust = a.Kd_Fincoy', 'left');
		$result = $this->db->get('tb_so a')->result(); 		
		return $result; 
    }

    function idSalesOrder(){
        $date= date("Y-m-d");
        $tahun=substr($date, 0, 4);
        $bulan=substr($date, 5, 2);
		$q = $this->db->query("select MAX(RIGHT(No_So,6)) as kd_max from tb_so where year(tgl_so) = $tahun ");
        $kd = "";
        if($q->num_rows()>0){
            foreach($q->result() as $k){
                $tmp = ((int)$k->kd_max)+1;
                $kd = sprintf("%06s", $tmp);
            }
        }else{
            $kd = "000001";
        }
        return "01SO".$tahun.$bulan.$kd;
        //urutan 01 itu next kalau ada cabang bisa disetting menjadi 02 dst..
    }

    function simpanDataSalesOrder(){
        $this->db->trans_begin();

        $kd = $this->idSalesOrder();       
        $Tgl_So = $this->input->post('txt_tgl_so');
        $Kd_Cust = $this->input->post('txt_kd_cust');
        $Nm_Cust = $this->input->post('txt_cust');
        $Alamat = $this->input->post('txt_alamat');
        $Kd_Salesman = $this->input->post('kd_salesman');
        $Jns_Bayar = $this->input->post('txt_jns_pembayaran');
        $By_Tunai = $this->input->post('txt_tunai');
        $Ttl_Hrg_Otr = $this->input->post('txt_hrg_mobil');
        $DP = $this->input->post('txt_dp_murni');
        $ADM = $this->input->post('txt_adm');
        $ADDM = $this->input->post('txt_angsuran_1');
        $Bunga = $this->input->post('txt_bunga');
        $Tenor = $this->input->post('txt_lama_angs');
        $Angsuran = $this->input->post('txt_jml_angs');
        $Tipe_Angs = $this->input->post('txt_biaya_adm');
        $Keterangan = $this->input->post('txt_keterangan');
        $No_Spk = $this->input->post('nospk');

        $Kd_Fincoy = $this->input->post('kd_leasing');
        $No_Po_Leasing = $this->input->post('txt_po_leasing');
 
        $No_Mesin = $this->input->post('txt_nomesin');
        $Diskon = $this->input->post('txt_diskon');

        $data = array(
            'No_So'=> $kd,
            'Tgl_So'=> $Tgl_So,
            'Kd_Cust'=> $Kd_Cust, 
            'Nm_Cust'=> $Nm_Cust, 
            'Alamat'=> $Alamat, 
            'Kd_Salesman'=> $Kd_Salesman, 
            'Jns_Bayar'=>  $Jns_Bayar, 
            'By_Tunai'=> preg_replace("/[^0-9]/", "", $By_Tunai), 
            'Ttl_Hrg_Otr'=> preg_replace("/[^0-9]/", "", $Ttl_Hrg_Otr), 
            'DP'=> preg_replace("/[^0-9]/", "", $DP), 
            'ADM'=> preg_replace("/[^0-9]/", "", $ADM), 
            'ADDM'=> preg_replace("/[^0-9]/", "", $ADDM), 
            'Bunga'=> preg_replace("/[^0-9]/", "", $Bunga), 
            'Tenor'=> $Tenor, 
            'Angsuran'=> preg_replace("/[^0-9]/", "", $Angsuran),   
            'Tipe_Angs'=> $Tipe_Angs, 
            'Keterangan'=> $Keterangan, 
            'Status'=> "Waiting Process",
            'Cetak'=> "0",
            'No_Spk'=> $No_Spk, 
            'No_Po_Leasing'=> $No_Po_Leasing,
            'Kd_Fincoy'=> $Kd_Fincoy, 
			);

        $this->db->insert('tb_so', $data);

        $data_detail_so = array(
            'Fk_So'=> $kd,
            'No_Mesin' => $No_Mesin,
            'Diskon' => preg_replace("/[^0-9]/", "", $Diskon), 
            );
        $this->db->insert('tb_so_detail', $data_detail_so); 

        if($this->db->affected_rows() > 0){
            $this->db->trans_commit();
            return true;
        }
        else {
            $this->db->trans_rollback();
            return false;
        }
    }

    function batalsalesorder(){
        $No_So=$this->input->post('txt_no_so');    
        $this->db->query("update tb_so set status='Batal' where No_So = '$No_So'");     
        if($this->db->affected_rows() > 0){
            return true;
        }
        else {
            return false;
        }
    }

    function prosesalesorder(){
        $No_So=$this->input->post('txt_no_so');    
        $this->db->query("update tb_so set status='Waiting Approval' where No_So = '$No_So'");     
        if($this->db->affected_rows() > 0){
            return true;
        }
        else {
            return false;
        }
    }

    function prosespersetujuanbast(){
        $No_So=$this->input->post('txt_no_so'); 
        $keterangan=$this->input->post('txt_keterangan');    

        $this->db->query("update tb_so set Status_Bast= '1',keterangan_bast='$keterangan',tgl_bast=now() where No_So = '$No_So'");     
        if($this->db->affected_rows() > 0){
            return true;
        }
        else {
            return false;
        }
    }

    function printsalesorder(){   
        $No_So=$this->input->post('txt_no_so'); 
        $this->db->query("update tb_so set Cetak= '1' where No_So = '$No_So'");     
        if($this->db->affected_rows() > 0){
            return true;
        }
        else {
            return false;
        }
    }

}