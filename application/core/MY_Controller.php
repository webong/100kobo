<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    protected $data = array();
    private $current_uri;
    private $last_uri;

    function __construct()
    {
        parent::__construct();
        $this->output->enable_profiler(FALSE); // I keep this here so I dont have to manualy edit each controller to see profiler or not

        // Set current uri
        $this->current_uri = base_url(uri_string());
        // Set last uri
        $this->last_uri = $this->session->flashdata('last_uri');
        // Set current uri as last uri in session
        $data = array('last_uri' => base_url());
    	$this->session->set_flashdata($data);

    	// Check activity for 5 mins online equals 1kobo
    	$this->check_activity_1kobo();
    }

    protected function time_elapsed_string($datetime, $full = false)
	{
	    $now = new DateTime;
	    $ago = new DateTime($datetime);
	    $diff = $now->diff($ago);

	    $diff->w = floor($diff->d / 7);
	    $diff->d -= $diff->w * 7;

	    $string = array(
	        'y' => 'year',
	        'm' => 'month',
	        'w' => 'week',
	        'd' => 'day',
	        'h' => 'hour',
	        'i' => 'minute',
	        's' => 'second',
	    );
	    foreach ($string as $k => &$v) {
	        if ($diff->$k) {
	            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
	        } else {
	            unset($string[$k]);
	        }
	    }

	    if (!$full) $string = array_slice($string, 0, 1);
	    return $string ? implode(', ', $string) . ' ago' : 'just now';
	}

	protected function post_worth($post_id)
	{
		$this->load->model('m_posts');

		$posts = count($this->m_posts->get_sub_posts($post_id));

		if ($posts <= 5) return 100;
		else return 100 + ($posts - 5) * 10;
	}

	protected function post_worth_trending($post_id)
	{
		$this->load->model('m_posts');

		$posts = count($this->m_posts->get_sub_posts($post_id));

		/*
		if ($posts <= 5) return 500;
		else return 100 + ($posts - 5) * 10;
		*/
		if ($posts <= 5) return 50;
		else return 100 + ($posts - 5) * 10;
	}

	protected function redirect_back()
    {
        if($this->last_uri) {
        	header('Location: '.$this->last_uri);
        } else {
        	redirect('/');
        }
    }

    protected function check_activity_1kobo()
    {
    	if ($this->session->userdata('is_logged_in')) {
	    	$date = date('Y-m-d H:i:s');
	    	$interval = abs(strtotime($date) - strtotime($this->session->userdata('login_time')));
			$minutes = round($interval / 60);
			if ($minutes > 5 && $this->session->userdata('user_id') > 0) {
		    	$this->load->model('m_wallet');
		    	$this->m_wallet->add_amount(0, $this->session->userdata('user_id'), 1, 'wallet', 8);
		    	$this->session->set_userdata('login_time', $date);
		    }
		}
    }

    protected function no_xss($string)
    {
    	return htmlspecialchars($string, ENT_QUOTES,'UTF-8');
    }

    protected function generateRandomName($length = 6)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    protected function getLocationInfoByIp()
    {
    	$client  = @$_SERVER['HTTP_CLIENT_IP'];
	    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
	    $remote  = @$_SERVER['REMOTE_ADDR'];
	    $result = '';

	    if (filter_var($client, FILTER_VALIDATE_IP)) {
	        $ip = $client;
	    } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
	        $ip = $forward;
	    } else {
	        $ip = $remote;
	    }

	    $ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));    

	    if ($ip_data && $ip_data->geoplugin_countryName != null) {

	        $result = $ip_data->geoplugin_countryCode;
	    }

	    return $result;
	}
}