<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_users');
		$this->load->model('m_posts');
		$this->load->model('m_gallery');
		$this->load->model('m_reports');
		$this->load->model('m_billboard');
	}

	public function index()
	{
		if ($this->session->userdata('is_logged_in')) {

			if ($this->session->userdata('access') == 3) {
				$header['title'] = 'Reports';
				$header['access'] = $this->session->userdata('access');
				$header['user'] = $this->session->userdata('username');
				$header['user'] = $this->m_users->get_user_info($this->session->userdata('user_id'));

				$data_1['user'] = $header['user'];
				$data_1['gallery'] = $this->m_posts->get_images_from_user($this->session->userdata('user_id'));
				$data_1['gallery_price'] = $this->m_gallery->get_gallery_price($this->session->userdata('user_id')) / 100;

				$data_2['reports_unresolved'] = $this->m_reports->get_all(0);
				$data_2['reports_resolved'] = $this->m_reports->get_all(1);

				foreach ($data_2['reports_unresolved'] as $report) {
					$report->user_report = $this->m_users->get_user_info($report->user_report)->username;
					$report->user_reported = $this->m_users->get_user_info($report->user_reported)->username;
					$report->added = $this->time_elapsed_string($report->added);
				}

				foreach ($data_2['reports_resolved'] as $report) {
					$report->user_report = $this->m_users->get_user_info($report->user_report)->username;
					$report->user_reported = $this->m_users->get_user_info($report->user_reported)->username;
					$report->added = $this->time_elapsed_string($report->added);
				}

				$this->load->view('home/header', $header);
				$this->load->view('reports/content', $data_2);
				$this->load->view('home/footer');

			} else {
				$this->session->set_flashdata('message_f', 'You don\'t have privileges to access this page');
				$this->redirect_back();
			}

		} else {
			$this->session->set_flashdata('message_f', 'You must be logged in to do that');
			redirect('/');
		}
	}

	public function process()
	{
		if ($this->session->userdata('is_logged_in')) {

			if ($this->session->userdata('access') == 3) {
				
				$this->load->library('form_validation');

				$this->form_validation->set_rules('id', 'ID', 'required|trim|is_numeric');

				if ($this->form_validation->run()) {

					$this->m_reports->mark_as_resolved($this->input->post('id'));

					$this->session->set_flashdata('message_s','Report resolved');

				} else {
					foreach($this->form_validation->error_array() as $error) {
						$this->session->set_flashdata('message_f', $error);
						break;
					}
				}

			} else {
				$this->session->set_flashdata('message_f', 'You don\'t have privileges to access this page');
				$this->redirect_back();
			}

		} else {
			$this->session->set_flashdata('message_f', 'You must be logged in to do that');
			redirect('/');
		}

		redirect('/reports');
	}
}