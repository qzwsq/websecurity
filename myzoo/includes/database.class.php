<?php


class Database{

	private $db_host = 'mysql';
	private $db_user = 'root';
	private $db_pass = "123456";
	private $db_dbname = "websecurity";

	var $link_id = 0;
	var $query_id = 0;
	
	function Database($database){
	
	$this->db_dbname =  $database;
	//echo "$this->db_dbname <br/>";

	$this->link_id=mysqli_connect($this->db_host,$this->db_user,$this->db_pass, "websecurity");
	#$this->link_id= new mysqli($this->db_host,$this->db_user,$this->db_pass, $this->db_dbname);

	

	if (!$this->link_id) {//open failed
		//echo "couldn't connect.<br/>";
		$this->oops("Could not connect to server: <b>$this->db_host</b>.");
		}
	
    /**
	if(!mysql_select_db($this->db_dbname, $this->link_id)) {//no database
		//echo "couldn't select.<br/>";
	 	$this->oops("Could not open database: <b>$this->db_dbname</b>.");
		}
    */

	}


	function executeQuery($sql) {
        // do query
		$this->query_id = @mysqli_query($this->link_id, $sql);
		if (!$this->query_id) {
			$this->oops("<b>MySQL Query fail:</b> $sql");
			return 0;
		}
		
		$this->affected_rows = @mysqli_affected_rows($this->link_id);

	return $this->query_id;
}#-#query()

		

	function oops($msg='') {
		if($this->link_id>0){
#		$this->error=mysql_error($this->link_id);
#		$this->errno=mysql_errno($this->link_id);
            $this->error=$this->link_id->error;
            $this->errno=$this->link_id->errno;
	}
	else{
		#$this->error=mysql_error();
		#$this->errno=mysql_errno();
        $this->error=$this->link_id->error;
        $this->errno=$this->link_id->errno;

	}

	?>

	<table align="center" border="1" cellspacing="0" style="background:white;color:black;width:80%;">
		<tr><th colspan=2>Database Error</th></tr>
		<tr><td align="right" valign="top">Message:</td><td><?php echo $msg; ?></td></tr>
		<?php if(!empty($this->error)) echo '<tr><td align="right" valign="top" nowrap>MySQL Error:</td><td>'.$this->error.'</td></tr>'; ?>
		<tr><td align="right">Date:</td><td><?php echo date("l, F j, Y \a\\t g:i:s A"); ?></td></tr>
		<?php if(!empty($_SERVER['REQUEST_URI'])) echo '<tr><td align="right">Script:</td><td><a href="'.$_SERVER['REQUEST_URI'].'">'.$_SERVER['REQUEST_URI'].'</a></td></tr>'; ?>
		<?php if(!empty($_SERVER['HTTP_REFERER'])) echo '<tr><td align="right">Referer:</td><td><a href="'.$_SERVER['HTTP_REFERER'].'">'.$_SERVER['HTTP_REFERER'].'</a></td></tr>'; ?>
		</table>
	<?php
}#-#oops()





}



?>
