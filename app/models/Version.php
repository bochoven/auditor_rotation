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

        function reset_counters()
        {
        	$dbh=$this->getdbh();
        	$fields = array('started', 'completed');
			$s='';
			foreach ( $fields as $k )
				$s .= ', '.$this->enquote( $k ).'=0';
			$s = substr( $s, 1 );
			$sql = 'UPDATE '.$this->enquote( $this->tablename ).' SET '.$s;
			$stmt = $dbh->prepare( $sql );
			return $stmt->execute();
        }


}