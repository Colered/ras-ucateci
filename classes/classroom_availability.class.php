<?php
class Classroom_Availability extends Base {
    public function __construct(){
   		 parent::__construct();
   	}
	/*function for adding class room availability*/
	public function addClassroomAvail() {
		    $roomId = (isset($_POST['slctRmName'])) ? ($_POST['slctRmName']) : '';
			$ruleId = (isset($_POST['ckbruleVal'])) ? ($_POST['ckbruleVal']) : '';
			$exceptionDate = (isset($_POST['exceptionDate'])) ? ($_POST['exceptionDate']) : '';
			$exceptionDateArr=$this->formingArray($exceptionDate);
			$currentDateTime = date("Y-m-d H:i:s");
			if($ruleId!=''){
				$ruleIdArr=$this->formingArray($ruleId);
			}
			//delete old mapping
			
				$del_roomRuleMap_query="delete from classroom_availability_rule_room_map where room_id='".$roomId."'";
				$qry = mysqli_query($this->conn, $del_roomRuleMap_query);
		
			//add new mapping
			if($ruleId!==''){
			  foreach($ruleIdArr as $key=>$value){
				$ruleIdVal=$value;
			 	$rule_room_qry="INSERT INTO  classroom_availability_rule_room_map VALUES ('', '".$ruleIdVal."', '".$roomId."','".$currentDateTime."', '".$currentDateTime."')";
				$rule_room_qry_rslt=mysqli_query($this->conn,$rule_room_qry);
				}
			}
			//delete old exceptions
			$del_roomRuleMapExceptn_query="delete from classroom_availability_exception where room_id='".$roomId."'";
			$qry = mysqli_query($this->conn, $del_roomRuleMapExceptn_query);
			if($exceptionDate!=""){
			  foreach($exceptionDateArr as $k=>$v){
				$exceptnDateVal=$v;
				$exception_qry="INSERT INTO classroom_availability_exception VALUES ('', '".$roomId."', '".$exceptnDateVal."','".$currentDateTime."', '".$currentDateTime."')";
				$exception_qry_rslt=mysqli_query($this->conn,$exception_qry);
				
			}
		  }
		  return 1;
		}	
	/*function for get the timesolt */
	public function getTimeslot(){
	  $tmslot_query="select * from  timeslot";
	  $q_res = mysqli_query($this->conn, $tmslot_query);
	  return $q_res;
	}
	/*function for fectching class room type */
	public function getRoomType(){
	  $room_type_qry="select * from  room_type";
	  $q_res= mysqli_query($this->conn, $room_type_qry);
	  return $q_res;
	}
	/*function to  converting into array*/ 
	public function formingArray($dataArr){
		 $newArr = array();
			foreach ($dataArr as $key => $val) {
					if (trim($val) <> "") {
					 $newArr[] = trim($val);
					}
			}
  		return $newArr;
	}
	/*getting classroom rule detail*/
	public function getClassroomAvailRule()
	{
		$class_query="select id, rule_name, start_date, end_date from classroom_availability_rule ORDER BY id DESC"; 
		$q_res = mysqli_query($this->conn, $class_query);
		if(mysqli_num_rows($q_res)<=0){
			$message="No class availability rule exist.";
			$_SESSION['error_msg'] = $message;
		}
		return $q_res;
	}
	/*getting classroom days*/
	public function getClassroomAvailDay($id)
	{
		$classromm_avail_query="select id, timeslot_id, day from classroom_availability_rule_day_map where classroom_availability_rule_id ='".$id."'"; 
		$q_res = mysqli_query($this->conn, $classromm_avail_query);
		return $q_res;
	}
	/*getting timeslot */
	public function getClassroomAvailTimeslot($ids)
	{
		$tmslot_query="select id, timeslot_range from timeslot where id IN(".$ids.")"; 
		$q_res = mysqli_query($this->conn, $tmslot_query);
		return $q_res;
	}
	/*getting classroom id of a particular room*/
	public function getRuleIdsForRoom($ids)
	{   
	    $ruleid_room_query="select classroom_availability_rule_id from classroom_availability_rule_room_map where room_id =".$ids; 
		$q_res = mysqli_query($this->conn, $ruleid_room_query);
		$allIds = array();
		while($data = $q_res->fetch_assoc()){
			$allIds[] =  $data['classroom_availability_rule_id'];
		}
		return $allIds;
	}
	/*function to view detail of classroom availability */
	public function viewClassAvail(){
		$classAvail_query="select tartm.id, tr.room_name, tr.room_name, tartm.classroom_availability_rule_id, tartm.room_id  from classroom_availability_rule_room_map as tartm LEFT JOIN room as tr ON tartm.room_id  = tr.id GROUP BY tartm.room_id"; 
		$q_res = mysqli_query($this->conn, $classAvail_query);
		return $q_res;
	}
	/*getting rule detail with classroom*/
	public function getRulesForRoom($ids)
	{
		$rule_query="select tartm.classroom_availability_rule_id, tar.rule_name from classroom_availability_rule_room_map as tartm LEFT JOIN classroom_availability_rule as tar on tartm.classroom_availability_rule_id = tar.id  where tartm.room_id =".$ids;
		$q_res = mysqli_query($this->conn, $rule_query);
		return $q_res;
	}
	/*getting exception date associated with room id*/
	public function getExceptionForRoom($ids)
	{
		$excep_query="select exception_date from classroom_availability_exception where room_id =".$ids;
		$q_excep = mysqli_query($this->conn, $excep_query);
		return $q_excep;
	}
	/*getting room type id*/
	public function getRoomTypeById($id){
	  $room_type_qry="select room_type_id,room_name from  room where id='".$id."'";
	  $q_res= mysqli_query($this->conn, $room_type_qry);
	  return $q_res;
	}
	public function getTimeslotId($timeSoltArr)
	{
		$timeslotIds = array();
		for($i=0;$i<count($timeSoltArr);$i++)
		{			
			$ts_array = array();
			$ts_array = explode(",",$timeSoltArr[$i]);
			$timeslots = array();
			foreach($ts_array as $val)
			{			
				$time = explode("-",$val);
				$start_time  = $time['0'];
				$end_time = $time['1'];
				$sql_time_slct = "select id from timeslot where start_time = '".$start_time."' OR end_time = '".$end_time."'";
				$q_res= mysqli_query($this->conn, $sql_time_slct);
				$tempIdRange= array();
				while($data = $q_res->fetch_assoc()){
				$tempIdRange[] =  $data['id'];
				}
				for($j=min($tempIdRange); $j<=max($tempIdRange); $j++){
					$timeslots[] = $j;
				}	
			}
			$timeslotIds[] = implode(',',$timeslots);
		}
		return $timeslotIds;
	}
	public function getClsrmRuleStartEndDate($id){
		$classromm_start_end_date="select rule_name,start_date,end_date from classroom_availability_rule where id ='".$id."'"; 
		$q_res = mysqli_query($this->conn, $classromm_start_end_date);
		$data=$q_res->fetch_assoc();
	    $data['start_date']= date('Y-m-d', strtotime($data['start_date']));
		$data['end_date']=date('Y-m-d', strtotime($data['end_date']));
		return $data;
	}
	public function getResDatesForClass($class_id='')
	{
		$resDates = array();
		$res_sql = "select act_date,group_concat(timeslot_id) as timeslot_id from teacher_activity ta where room_id = '".$class_id."' group by act_date";
		$res_query = mysqli_query($this->conn, $res_sql);
		while($data = $res_query->fetch_assoc()){
			$activity_date = date("Ymd",strtotime($data['act_date']));
			$resDates[$activity_date]['act_date'] =  $activity_date;
			$resDates[$activity_date]['ts_id'] =  $data['timeslot_id'];
		}
		return $resDates;		
	}
	
	
}