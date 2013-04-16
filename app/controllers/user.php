<?php
class user extends Controller
{
	private $active = '';

	function __construct()
	{
		$this->cookie_name = $GLOBALS['shortname'];
		$this->cookie_exp = strtotime( '+30 days' );
		$this->cookie_path = '/';

		// Get status
		$setting_obj = new Setting();
		$this->active = $setting_obj->get_prop('status') == 'active';
	} 

	//===============================================================

	function index()
	{
		if( ! $this->active)
		{
			redirect('user/inactive');
		}

		// Is this a returning user?
		if (isset($_COOKIE[$this->cookie_name]))
		{
			$part_obj = new Participant();
			if($part_obj->retrieve_one('cookie=?', $_COOKIE[$this->cookie_name]))
			{
				redirect('user/to_survey');
			}
		}

		$data = array();
		$obj = new View();
		$obj->view('user/intro', $data);
	}


	//===============================================================

	function nomore()
	{
		$data = array();
		$obj = new View();
		$obj->view('user/nomore', $data);
	}

	//===============================================================
	
	function inactive()
	{
		$data = array();
		$obj = new View();
		$obj->view('user/inactive', $data);
	}

	//===============================================================

	function to_survey($mode='')
	{
		// Check if we're active
		if( ! $this->active)
		{
			redirect('user/inactive');
		}

		$code = '';

		// Is this a returning user?
		if (isset($_COOKIE[$this->cookie_name]))
		{
			$part_obj = new Participant();
			if($part_obj->retrieve_one('cookie=?', $_COOKIE[$this->cookie_name]))
			{
				// Check if already completed
				if($part_obj->return_date)
				{
					// In debug mode, you can run multiple sessions
					if( ! _DEBUG )
					{
						write_log(sprintf('TO_SURVEY id:%s no more', $part_obj->id));

						redirect('user/nomore');
					}
				}
				else
				{
					write_log(sprintf('TO_SURVEY id:%s returning user', $part_obj->id));

					$code = $part_obj->code;
				}
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

			write_log(sprintf('TO_SURVEY New user, id:%s', $part_obj->id));


			// Store cookie
			if( ! setcookie($this->cookie_name , $part_obj->cookie, $this->cookie_exp, $this->cookie_path))
			{
				die('Could not set cookie');
			}

			// Increase started counter
			$version_obj->started = $version_obj->started + 1;
			$version_obj->save();
		}

		if ($mode == 'debug')
		{
			echo $code;
		}
		else
		{
			write_log(sprintf('TO_SURVEY Send id:%s to survey', $part_obj->id));

			// Redirect to survey
			$sobj = new Setting();
			$url = $sobj->get_prop('url');
			$qid = $sobj->get_prop('question');
			$rqid = $sobj->get_prop('rquestion');
			redirect($url.'&'.$qid.'='.$code.'&'.$rqid.'='.$part_obj->id);
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
	public function completed($participant_id = '')
	{
		// Check if we're active
		if( ! $this->active)
		{
			return;
		}

		if ($participant_id)
		{
			$part_obj = new Participant($participant_id);

			if($part_obj->send_date)
			{
				if($part_obj->return_date)
				{
					// Participant already returned
					write_log(sprintf('COMPLETED id:%s already returned', $participant_id));

				}
				else
				{
					$part_obj->return_date = date('Y-m-d H:i:s');
					$part_obj->save();

					write_log(sprintf('COMPLETED id:%s successfully', $participant_id));

					$code = $part_obj->code;
					$version_obj = new Version();
					if($version_obj->retrieve_one('code=?', $code))
					{
						$version_obj->completed = $version_obj->completed + 1;
						$version_obj->save();
					}
					else
					{
						write_log(sprintf('COMPLETED code:%s not found', $code));
					}
				}

			}
			else // Participant not found 
			{
				write_log('COMPLETED participant not in db? ' . $participant_id);
			}
		}
		else // No participant_id, maybe blocking third party cookies
		{
			write_log('COMPLETED, no participant_id');
		}
		
		// Prevent caching
		header('Content-Type: image/gif;');
	    header('Cache-Control: no-cache');
	    
		// Return a transparent pixel
		echo base64_decode('R0lGODlhAQABAJEAAAAAAP///////wAAACH5BAEAAAIALAAAAAABAAEAAAICVAEAOw==');

	}

	function logtest($msg = '')
	{
		write_log(print_r($_COOKIE, TRUE));
	}

}