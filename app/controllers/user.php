<?php
class user extends Controller
{
	function __construct()
	{

	} 

	//===============================================================

	function index()
	{
		// Is this a new user?

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
		$version_obj->started = $version_obj->started + 1;
		$version_obj->save();

		echo $version_obj->code;

		// Redirect to survey FIXME wijst nu naar test
		redirect('http://ic.vupr.nl/survey/index.php?sid=58328&newtest=Y&lang=en&58328X15X88='.$code);
	}

	public function completed($value='')
	{
		$version_obj = new Version();
		if($version_obj->retrieve_one('code=?', $value))
		{
			$version_obj->completed = $version_obj->completed + 1;
			$version_obj->save();
		}

		echo 'Completed';
	}

	

}