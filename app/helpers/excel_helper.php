<?php

	function get_teams_from_excel($file)
	{
		$teams = array();
		
		include(APP_PATH.'lib/excel_reader2.php');
		
		$data = new Spreadsheet_Excel_Reader($file, FALSE, 'utf-8'); //Todo: do some error checking

		// Get number of sheets
		$sheets = count($data->sheets);

		// Loop through all sheets
		for ($sheet = 0; $sheet < count($data->sheets); $sheet++)
		{
			// Loop through rows
			for($row = 1;$row <= $data->rowcount($sheet); $row++)
			{
				// Loop through columns
				for($col = 1;$col <= $data->colcount($sheet); $col++)
				{
					$val = $data->val($row,$col,$sheet);

					// Find a team
					if(strpos($val, 'DATUM') !== FALSE)
					{
						$team_name = trim($data->val($row + 1, $col + 2,$sheet));
						$teams[$team_name]['date'] = trim($data->val($row, $col + 2,$sheet));
						$teams[$team_name]['time'] = trim($data->val($row + 2, $col + 1,$sheet));
						$teams[$team_name]['game'] = trim($data->val($row + 3, $col,$sheet));
						$teams[$team_name]['meeting_place'] = trim($data->val($row + 5, $col + 2,$sheet));
						$teams[$team_name]['meeting_time'] = trim($data->val($row + 5, $col + 1,$sheet));
						$niet_aanwezig = FALSE;

						// Get team members
						for($trow = $row + 6;$trow <= $data->rowcount($sheet); $trow++)
						{
							$number = trim($data->val($trow, $col,$sheet));
							$f_name = trim($data->val($trow, $col + 1,$sheet));
							$l_name = trim($data->val($trow, $col + 2,$sheet));

							// Check if in next team
							if(strpos($number, 'DATUM') !== FALSE)
							{
								break;
							}

							// Check if we're in the niet aanwezig part
							if(stripos($f_name, 'niet aanwezig') !== FALSE)
							{
								$niet_aanwezig = TRUE;
							}

							if( ! $niet_aanwezig)
							{
								if($number && $f_name && $l_name)
								{
									$teams[$team_name]['players'][$number] = array('fname' => $f_name, 'lname' => $l_name);
								}
							}
							else
							{
								if($f_name && $l_name)
								{
									$teams[$team_name]['not_present'][] = array('fname' => $f_name, 'lname' => $l_name);
								}
							}

						}
					}
				}
			}
		}
		
		return $teams;
	}