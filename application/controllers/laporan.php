<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Laporan extends MY_Controller {

    public function __construct()
	{
		parent::__construct(); 
		$this->load->library('form_validation');   
		$this->_cek_login();
	}

	function _cek_login()
	{
		if (!isset($this->session->userdata['id_user'])) {
	    redirect(base_url("login"));
	    }
    }

    function lapstokgudang()
	{
       
		$status=$this->input->post('status');
		
        $this->form_validation->set_rules('status', 'Status', 'required');
		$this->form_validation->set_message('required', '%s harus diisi');

        if($this->form_validation->run() == TRUE){	
            $this->render_template('laporan/lapstokgudang_filter');
        } else {
			$this->render_template('laporan/lapstokgudang');
        }
    } 

}