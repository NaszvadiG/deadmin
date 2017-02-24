<?php defined('BASEPATH') OR exit('No Direct Script Access Allowed');

class Login extends CI_Controller
{
    public function index()
    {
        $asset = array(
                    'title'     =>'Login Sukses',
                    'js'        =>array(),
                    'css'       =>array()
                );

        $this->load->view('admin/template/header', $asset);
        $this->load->view('admin/template/top');
        $this->load->view('admin/template/main_page');
        $this->load->view('admin/template/footer');
    }

}