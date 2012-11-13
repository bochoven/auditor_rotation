<?php
class Participant extends Model {

        function __construct($id='')
        {
			parent::__construct('id','participant'); //primary key, tablename
			$this->rs['id'] = 0;
			$this->rs['cookie'] = ''; //Cookie value
			$this->rs['code'] = ''; // Assigned code
			$this->rs['ip'] = ''; // IP address
			$this->rs['send_date'] = '';
			$this->rs['return_date'] = '';
                
                // Create table if it does not exist
        		$this->create_table();
                
                if ($id)
                  $this->retrieve($id);
        }

}