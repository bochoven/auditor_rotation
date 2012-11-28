<?php
class user extends Controller
{
	function __construct()
	{
		$this->cookie_name = 'Auditor_rotation';
		$this->cookie_exp = strtotime( '+30 days' );
		$this->cookie_path = '/';
	} 

	//===============================================================

	function index()
	{

		$data = array();
		$obj = new View();

		$setting_obj = new Setting();
		if($setting_obj->get_prop('status') == 'active')
		{
			$obj->view('user/intro', $data);
		}
		else
		{
			$obj->view('user/inactive', $data);
		}

	}


	//===============================================================
	function nomore()
	{
		$data = array();
		$obj = new View();
		$obj->view('user/nomore', $data);
	}

	//===============================================================

	function to_survey($mode='')
	{
		$code = '';

		// Is this a returning user?
		if (isset($_COOKIE[$this->cookie_name]))
		{
			$part_obj = new Participant();
			if($part_obj->retrieve_one('cookie=?', $_COOKIE[$this->cookie_name]))
			{
				$code = $part_obj->code;
			}
		}

		if ( ! $code)
		{
			// Get appropriate code
			$version_obj = new Version();

			// First get versions with a threshold
			$sql = 'completed < threshold ORDER BY threshold ASC, completed ASC, started ASC';
			if( ! $version_obj->retrieve_one($sql))
			{
				// Otherwise get the version with the lowest completed
				$sql = 'id > 0 ORDER BY completed ASC, started ASC';
				$version_obj->retrieve_one($sql);
			}

			$code = $version_obj->code;

			// Register this user
			$part_obj = new Participant();
			$part_obj->code = $code;
			$part_obj->send_date = date('Y-m-d H:i:s');
			$part_obj->ip = $_SERVER['REMOTE_ADDR'];
			$part_obj->save();
			$part_obj->cookie = hash('sha256', $part_obj->id);
			$part_obj->save();

			// Store cookie
			if( ! setcookie($this->cookie_name , $part_obj->cookie, $this->cookie_exp, $this->cookie_path))
			{
				die('Could not set cookie');
			}

			// Increase started counter
			$version_obj->started = $version_obj->started + 1;
			$version_obj->save();
		}

		// Check if already completed
		if(TRUE OR $part_obj->return_date)
		{
			redirect('user/nomore');
		}

		if ($mode == 'debug')
		{
			echo $code;
		}
		else
		{
			// Redirect to survey
			$sobj = new Setting();
			$url = $sobj->get_prop('url');
			$qid = $sobj->get_prop('question');
			redirect($url.'&'.$qid.'='.$code);
		}
		
	}

	/**
	 * Completed, called by ending page of survey
	 * sets return_date on participant record and
	 * Increases the completed counter
	 *
	 * @return void
	 * @author 
	 **/
	public function completed($value='')
	{
		// Check if we're active
		$setting_obj = new Setting();
		if($setting_obj->get_prop('status') != 'active')
		{
			return;
		}

		if (isset($_COOKIE[$this->cookie_name]))
		{
			$part_obj = new Participant();
			if($part_obj->retrieve_one('cookie=?', $_COOKIE[$this->cookie_name]))
			{
				if($part_obj->return_date)
				{
					// Participant already returned
				}
				else
				{
					$part_obj->return_date = date('Y-m-d H:i:s');
					$part_obj->save();

					$code = $part_obj->code;
					$version_obj = new Version();
					if($version_obj->retrieve_one('code=?', $code))
					{
						$version_obj->completed = $version_obj->completed + 1;
						$version_obj->save();
					}
				}

			}
			else // No cookie
			{

			}
		}

		

		//echo 'Completed';
	}	

}