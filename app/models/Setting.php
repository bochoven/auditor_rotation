<?php
class Setting extends Model {

        function __construct($id='')
        {
			parent::__construct('id','setting'); //primary key, tablename
			$this->rs['id'] = 0;
			$this->rs['prop'] = '';
			$this->rs['val'] = '';
                
                // Create table if it does not exist
        		$this->create_table();
                
                if ($id)
                  $this->retrieve($id);
        }
        /**
         * Return value from property
         *
         * @return string val
         * @author 
         **/
        function get_prop($prop)
        {
        	$val = '';

        	if($this->retrieve_one('prop=?', $prop))
        		$val = $this->rs['val'];

        	return $val;
        }


}