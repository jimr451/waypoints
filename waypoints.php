<?
class WayPoint { 
  public $id;
  public $name;
  public $latitude;
  public $longitude;
  // Should error check incoming parameters

  public static function listAll($userid) { 
    global $database;
    $r = mysql_query("select * from waypoints where userid = $userid",$database);
    if (!$r) {
     $message  = 'Invalid query: ' . mysql_error() . "\n";
     $message .= 'Whole query: ' . $sql;
     print $message;
     exit;
    }
    $waypoints = array();
    while ($results = mysql_fetch_array($r, MYSQL_ASSOC)) {
        array_push($waypoints,$results);
    }
    mysql_free_result($r);
    return($waypoints);
  }
  public function savePoint($userid) { 
    global $database; 
    if(!$this->id) { 
      $r = mysql_query("insert into waypoints (id,userid,name,latitude,longitude) values (null,$userid,'".mysql_real_escape_string($this->name)."',".$this->latitude.",".$this->longitude.")",$database);
	$this->id = mysql_insert_id();
    } else { 
      $r = mysql_query("update waypoints set name '".mysql_real_escape_string($this->name)."',latitude = ".$this->latitude.",longitude = ".$this->longitude." where userid = $userid and id = ".$this->id,$database);
    }
  }
} 
?>
