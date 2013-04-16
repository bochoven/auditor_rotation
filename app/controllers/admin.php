<?php
class admin extends Controller
{
	function __construct()
	{
		if( ! isset($_SESSION['user']))
		{
			redirect('auth/login/admin');
		}	
	} 

	//===============================================================

	function index()
	{
		redirect('admin/dashboard');
	}

	//===============================================================

	function dashboard()
	{
        $data = array();
		$obj = new View();
		$obj->view('admin/dashboard', $data);

	}

	function participants()
	{
        $data = array();
		$obj = new View();
		$obj->view('admin/participants', $data);
	}

	//===============================================================

	function versions()
	{
        $data = array();
		$obj = new View();
		$obj->view('admin/import_export', $data);

	}

	//===============================================================

	function save_settings($settings = '')
	{
        // Use $_POST if no $settings passed
        if( ! is_array($settings))
        {
        	$settings = $_POST;
        }

        $setting_obj = new Setting();
        foreach($settings as $k => $v)
        {
			$setting_obj->id = 0;
        	$setting_obj->retrieve_one('prop=?', $k);
        	$setting_obj->prop = $k;
        	$setting_obj->val = $v;
        	$setting_obj->save();
        }
	}

	//===============================================================

	function xhttp_get_status()
	{
		$out = array('status' => 'inactive');

		$setting_obj = new Setting();
		if($setting_obj->retrieve_one('prop=?', 'status'))
		{
			$out['status'] = $setting_obj->val;
		}
		echo json_encode($out);
	}

	//===============================================================

	function set_status($status = 'inactive')
	{
		if($status == 'active')
		{
			// Reset counters
			$v_obj = new Version();
			$v_obj->reset_counters();

			// Reset participants
			$p_obj = new Participant();
			$p_obj->delete_all();

			// Save start date
			$settings['started'] = date('Y-m-d h:i:s');
			$settings['stopped'] = '';
		}
		else
		{
			$settings['stopped'] = date('Y-m-d h:i:s');
		}

		$settings['status'] = $status;

		$this->save_settings($settings);
	}


	//===============================================================

	function submit()
	{
		if($_FILES)
		{
			if( isset($_FILES['file']) && $_FILES['file']['error'] == 0)
			{
				// Check if file is valid
				require(APP_PATH.'lib/excel_reader2'.EXT);
				$data = new Spreadsheet_Excel_Reader($_FILES['file']['tmp_name'], false);
				
				// Translation table between excel and db
				$colinfo = array(	1 => 'code', 
									2 => 'desc', 
									3 => 'threshold');				

				$sheet = 0;
				
				// Reset db
				$version_obj = new Version();
				$version_obj->delete_all();

				// Loop through rows skip first row
				for($row = 2;$row <= $data->rowcount($sheet); $row++)
				{						
					// If first column is empty, skip
					if(! trim($data->val($row,1,$sheet))) continue;
					
					$version_obj = new Version();
					
					// Loop through columns
					foreach($colinfo AS $col => $colname)
					{
						$val = $data->val($row,$col,$sheet);
						$version_obj->$colname = $val;						
					}

					$version_obj->save();
				}

				// Move excel to downloads folder
				move_uploaded_file($_FILES['file']['tmp_name'], APP_ROOT . 'downloads/'.$GLOBALS['shortname'].'.xls');

			}
			else
			{
				echo 'upload failed';

			}

		}

		redirect('admin/dashboard');
	}

	

}