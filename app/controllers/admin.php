<?php
class admin extends Controller
{
	function __construct()
	{
		// Auth here
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

	//===============================================================

	function versions()
	{
        $data = array();
		$obj = new View();
		$obj->view('admin/import_export', $data);

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
				move_uploaded_file($_FILES['file']['tmp_name'], APP_ROOT . 'downloads/Auditor_rotation.xls');

			}
			else
			{
				echo 'upload failed';

			}

		}

		redirect('admin/dashboard');
	}

	

}