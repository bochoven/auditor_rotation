<?php
class Version extends Model {

        function __construct($id='')
        {
			parent::__construct('id','version'); //primary key, tablename
			$this->rs['id'] = 0;
			$this->rs['code'] = '';
			$this->rs['desc'] = '';
			$this->rs['threshold'] = 0;
			$this->rs['started'] = 0;
			$this->rs['completed'] = 0;
                
                // Create table if it does not exist
        		$this->create_table();
                
                if ($id)
                  $this->retrieve($id);
        }


}