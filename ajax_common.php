<?php
require_once('config.php');
function RexExpFormat($str)
{
	 $TempStr="";
	 $tempId=explode(",",$str);
	 for($i=0;$i<count($tempId);$i++)
	 {
		$TempStr=$TempStr."[[:<:]]".$tempId[$i]."[[:>:]]"."|";
	 }
	 if ($TempStr<>"")
	 {
		$TempStr=substr($TempStr,0,strlen($TempStr)-1);
	 }
	 return $TempStr;
}
$options = '';
$codeBlock = trim($_POST['codeBlock']);
switch ($codeBlock) {
    case "del_area":
		if(isset($_POST['id'])){
			$id = $_POST['id'];
			//check if are is being used for any subject
			$sub_query="select id from subject where area_id='".$_POST['id']."'";
			$q_res = mysqli_query($db, $sub_query);
			$dataAll = mysqli_fetch_assoc($q_res);
			if(count($dataAll)>0)
			{
				echo 2;
			}else{
				$del_area_query="delete from area where id='".$id."'";
				$qry = mysqli_query($db, $del_area_query);
				if(mysqli_affected_rows($db)>0)
					echo 1;
				else
					echo 0;
			}
		}
    break;
	case "del_teacher":
		if(isset($_POST['id'])){
			$id = $_POST['id'];
			//delete the teacher
			$del_query="delete from teacher where id='".$id."'";
			$qry = mysqli_query($db, $del_query);
			if(mysqli_affected_rows($db)>0){
				// delete all activities related to the teacher
				$del_act_query="delete from teacher_activity where teacher_id ='".$id."'";
				mysqli_query($db, $del_act_query);
				// delete all the rule associated to this teacher from teacher_availability_rule_teacher_map
				$del_teacher_avail_query="delete from teacher_availability_rule_teacher_map where teacher_id ='".$id."'";
				mysqli_query($db, $del_teacher_avail_query);
				// delete all the exception dates for the teacher availability from teacher_availability_exception
				$del_exception_query="delete from teacher_availability_exception where teacher_id teacher_id ='".$id."'";
				mysqli_query($db, $del_exception_query);
				echo 1;
			}else{
				echo 0;
			}
		}
	 break;
	 case "del_buld":
		if(isset($_POST['id'])){
			$id = $_POST['id'];
			$del_buld_query="delete from building where id='".$id."'";
			$qry = mysqli_query($db, $del_buld_query);
			if(mysqli_affected_rows($db)>0){
				// delete all the rule associated to this rooms classroom_availability_rule_room_map
				$del_room_query="delete from classroom_availability_rule_room_map where room_id IN(select id from room where building_id='".$id."')";
				mysqli_query($db, $del_room_query);
				// delete all the exception dates for the room
				$del_exception_query="delete from classroom_availability_exception where room_id IN(select id from room where building_id='".$id."')";
				mysqli_query($db, $del_exception_query);
				// delete all the rooms associated to this building
				$del_rules_query="delete from room where building_id='".$id."'";
				mysqli_query($db, $del_rules_query);
				echo 1;
			}else{
				echo 0;
			}
		}
	break;
	case "del_program":
	if(isset($_POST['id'])){
		$id = $_POST['id'];
		$del_query="delete from program where id='".$id."'";
		$qry = mysqli_query($db, $del_query);
		if(mysqli_affected_rows($db)>0){
			//firstly delete all the dependent data on program year id
		    // delete all the cycles related to this program
		    $del_cycle_query="delete from cycle where program_year_id in(select id from program_years where program_id='".$id."')";
		    mysqli_query($db, $del_cycle_query);
			//delete associated program cycle exceptions
			$del_pgm_cycle_exp_query="delete from program_cycle_exception where program_year_id in(select id from program_years where program_id='".$id."')";
		    mysqli_query($db, $del_pgm_cycle_exp_query);
			//delete associated program cycle additional day and time
			$del_pgm_cycle_add_date="delete from program_cycle_additional_day_time where program_year_id in(select id from program_years where program_id='".$id."')";
		    mysqli_query($db, $del_pgm_cycle_add_date);
		    //delete associated groups
			$del_pg_query="delete from program_group where program_year_id in(select id from program_years where program_id='".$id."')";
			mysqli_query($db, $del_pg_query);
			//delete associated sessions
			$del_sess_query="delete from subject_session where subject_id in(select id from subject where program_year_id in(select id from program_years where program_id='".$id."'))";
			mysqli_query($db, $del_sess_query);
			//delete associated subjects
			$del_sub_query="delete from subject where program_year_id in(select id from program_years where program_id='".$id."')";
		    mysqli_query($db, $del_sub_query);
			//delete associated activities
			$del_act_query="delete from teacher_activity where program_year_id in(select id from program_years where program_id='".$id."')";
		    mysqli_query($db, $del_act_query);
			// at the end delete all the program years related to this program
			$del_cycle_query="delete from program_years where program_id='".$id."'";
			$qry = mysqli_query($db, $del_cycle_query);
			echo 1;
		}else{
			echo 0;
	    }
	}
	break;
	case "del_subject":
		if(isset($_POST['id'])){
			$id = $_POST['id'];
			//delete all activity related to that subject
			$del_act_query="delete from teacher_activity where subject_id='".$id."'";
			$qry = mysqli_query($db, $del_act_query);
			//delete all sessions related to that subject
			$del_session_query="delete from subject_session where subject_id='".$id."'";
			$qry = mysqli_query($db, $del_session_query);
			//delete the subject
			$del_subject_query="delete from subject where id='".$id."'";
			$qry = mysqli_query($db, $del_subject_query);
			if(mysqli_affected_rows($db)>0)
			   echo 1;
		    else
			   echo 0;
		}
    break;
	case "getRooms":
		if(isset($_POST['roomTypeValue']) && $_POST['roomTypeValue']!=""){
			$room_type_val=explode(",",$_POST['roomTypeValue']);
			for($i=0;$i<count($room_type_val);$i++){
				$room_val=explode("#",$room_type_val[$i]);
				$room_type_id=$room_val['0'];
				$room_type_name=$room_val['1'];
				$options .='<option value="">--Select Room--</option>';
				$room_query="select id,room_name from  room where room_type_id='".trim($room_type_id)."'";
				$qry = mysqli_query($db, $room_query);
				while($room_data= mysqli_fetch_array($qry)){
				  $selected = ($_POST['roomId'] == $room_data['id']) ? ' selected="selected"' : '';

				  $options .='<option value="'.$room_data['id'].'" '.$selected.' >'.$room_data['room_name'].'</option>';
				 }
			}
		}
		echo $options;
	break;
	case "del_room":
	if(isset($_POST['id'])){
		$id = $_POST['id'];
		$del_room_query="delete from room where id='".$id."'";
		$qry = mysqli_query($db, $del_room_query);
		if(mysqli_affected_rows($db)>0){
			// delete all the rule associated to this rooms classroom_availability_rule_room_map
			$del_room_query="delete from classroom_availability_rule_room_map where room_id ='".$id."'";
			mysqli_query($db, $del_room_query);
			// delete all the exception dates for the room
			$del_exception_query="delete from classroom_availability_exception where room_id ='".$id."'";
			mysqli_query($db, $del_exception_query);
			echo 1;
		}else{
			echo 0;
		}
	}
    break;
	case "del_group":
		if(isset($_POST['id'])){
			$id = $_POST['id'];
			$del_area_query="delete from group_master where id='".$id."'";
			$qry = mysqli_query($db, $del_area_query);
			if(mysqli_affected_rows($db)>0)
				echo 1;
			else
				echo 0;
		}
	break;
	case "getGroups":
	    $options = '';
	    $dataArr = array();
		if(isset($_POST['program_id']) && $_POST['program_id']!=""){
		    //fetch all the groups related to a program
		    $query="SELECT * FROM program_group WHERE program_year_id='".$_POST['program_id']."'";
			$result = mysqli_query($db, $query);
			while($data= mysqli_fetch_array($result)){
				 $dataArr[] = $data['group_id'];
			}
	    }
		$query="select id,name from group_master";
		$result = mysqli_query($db, $query);
		while($data= mysqli_fetch_array($result)){
			 $selected = in_array($data['id'],$dataArr) ? "selected":"";
			 $options .='<option value="'.$data['id'].'" '.$selected.'>'.$data['name'].'</option>';
		}
		echo $options;
	break;
	case "del_associated_prog_group":
		if(isset($_POST['id'])){
			$id = $_POST['id'];
			$del_query="delete from program_group where program_year_id='".$id."'";
			$qry = mysqli_query($db, $del_query);
			if(mysqli_affected_rows($db)>0)
				echo 1;
			else
				echo 0;
		}
    break;
	case "del_timeslot":
		if(isset($_POST['id'])){
			$id = $_POST['id'];
			$del_timeslot_query="delete from timeslot where id='".$id."'";
			$qry = mysqli_query($db, $del_timeslot_query);
			if(mysqli_affected_rows($db)>0)
				echo 1;
			else
				echo 0;
		}
	break;
	case "del_activity":
		if(isset($_POST['activityId']) && isset($_POST['sessionID'])){
			$activityId = $_POST['activityId'];
			//check if activity id is o means there is no activity so simply delete the session
			if($activityId==0){
				$del_session="delete from subject_session where id='".$_POST['sessionID']."'";
				$qry = mysqli_query($db, $del_session);
				if(mysqli_affected_rows($db)>0){
					echo 1;
				}else{
					echo 0;
				}
			}else{
				//check if some other activities exists for the session
				$del_activity_query="select session_id from teacher_activity where session_id = '".$_POST['sessionID']."' and id!='".$_POST['activityId']."'";
				$qry = mysqli_query($db, $del_activity_query);
				if(mysqli_affected_rows($db)>0){
					//delete only activity not the session
					$del_act_query="delete from teacher_activity where id='".$_POST['activityId']."'";
					$qry = mysqli_query($db, $del_act_query);
					if(mysqli_affected_rows($db)>0)
						echo 1;
					else
						echo 0;
				}else{
					//session also needs to be deleted as this is the last activity of the session
					 echo 2;
				}
			}
	}
    break;
	case "getSubjects":
		$options='<option value="">--Select Subject--</option>';
		if(isset($_POST['year_id']) && $_POST['year_id']!=""){
		    $year_id = $_POST['year_id'];
			$query="select id,subject_name from subject where program_year_id='".$year_id."'";
			$result = mysqli_query($db, $query);
			while($data= mysqli_fetch_array($result)){
				 $options .='<option value="'.$data['id'].'">'.$data['subject_name'].'</option>';
			}
		}
		echo $options;
	break;
	case "getSessions":
		$options='<option value="">--Select Session--</option>';
		if(isset($_POST['subject_id']) && $_POST['subject_id']!=""){
		    $subject_id = $_POST['subject_id'];
			$query="SELECT id,session_name FROM subject_session WHERE subject_id = '".$subject_id."' ORDER BY order_number";
			$result = mysqli_query($db, $query);
			while($data= mysqli_fetch_array($result)){
				 $options .='<option value="'.$data['id'].'">'.$data['session_name'].'</option>';
			}
		}
		echo $options;
	break;
	case "addTeacherAct":
	     $objS = new Subjects();
	     $objB = new Buildings();
		 $objTS = new Timeslot();
		 $objT = new Teacher();

         if($_POST['program_year_id']<>"" && $_POST['subject_id']<>"" && $_POST['session_id']<>"" && !empty($_POST['teachersArr'])){
            $program_year_id = $_POST['program_year_id'];
            $subject_id = $_POST['subject_id'];
            $sessionid = $_POST['session_id'];
            $cycle_id = $_POST['cycle_id'];
            //add teacher activity row in table if not exist
            $objT->insertActivityRow($program_year_id,$cycle_id,$subject_id,$sessionid,$_POST['teachersArr']);
			//room dropdown
			$preallocated_room = $objT->getAllocatedRoomBySubject($subject_id);
			$room_dropDwn = $objB->getRoomsDropDwn($preallocated_room);
			//timeslot dropdown
			$tslot_dropDwn = $objTS->getTimeSlotDropDwn();
            echo '<table cellspacing="0" cellpadding="0" border="0">';
            echo '<tr>';
			echo '<th>Reserved</th>';
			echo '<th>Name</th>';
			echo '<th>Program</th>';
			echo '<th>Subject</th>';
			echo '<th>Session</th>';
			echo '<th>Teacher</th>';
			echo '<th>Room</th>';
			echo '<th>Timeslot</th>';
			echo '<th>Date</th>';
			echo '<th>&nbsp;</th>';
			echo '</tr>';
            $slqT="SELECT * FROM teacher_activity WHERE program_year_id='".$program_year_id."' AND subject_id='".$subject_id."' AND session_id='".$sessionid."' ORDER BY id";
			$relT = mysqli_query($db, $slqT);
			while($data= mysqli_fetch_array($relT)){
			    $reserved_flag_checked = ($data['reserved_flag']==1) ? "checked" : '';
				echo '<tr>';
				echo '<td align="center"><input type="hidden" name="activitiesArr[]" value="'.$data['id'].'"><input type="radio" name="reserved_flag" value="'.$data['id'].'" '.$reserved_flag_checked.' onclick="roomTslotValidate(\''.$data['id'].'\');"></td>';
				echo '<td align="center">'.$data['name'].'</td>';
				echo '<td>'.$objS->getFielldVal("program_years","name","id",$program_year_id).'</td>';
				echo '<td>'.$objS->getSubjectByID($data['subject_id']).'</td>';
				echo '<td>'.$objS->getSessionByID($data['session_id']).'</td>';
				echo '<td>'.$objT->getTeacherByID($data['teacher_id']).'<input type="hidden" id="reserved_teacher_id_'.$data['id'].'" name="reserved_teacher_id_'.$data['id'].'" value="'.$data['teacher_id'].'"></td>';

				echo '<td><select name="room_id_'.$data['id'].'" id="room_id_'.$data['id'].'" class="activity_row_chk" disabled>';
				echo '<option value="">--Room--</option>';
				echo $room_dropDwn;
				echo '</select><br><span id="room_validate_'.$data['id'].'" class="rfv_error" style="display:none;color:#ff0000;">Choose room</span></td>';
				echo '<script type="text/javascript">jQuery("#room_id_'.$data['id'].'").val("'.$data['room_id'].'")</script>';

				echo '<td><select name="tslot_id_'.$data['id'].'" id="tslot_id_'.$data['id'].'" class="activity_row_chk" disabled>';
				echo '<option value="">--Time Slot--</option>';
				echo $tslot_dropDwn;
				echo '</select><br><span id="tslot_validate_'.$data['id'].'" class="rfv_error" style="display:none;color:#ff0000;">Choose time slot</span></td>';
				echo '<script type="text/javascript">jQuery("#tslot_id_'.$data['id'].'").val("'.$data['timeslot_id'].'")</script>';

				echo '<td><input type="text" size="12" id="activityDateCal_'.$data['id'].'" class="activityDateCal" name="activityDateCal_'.$data['id'].'" value="'.$objT->formatDate($data['act_date']).'" readonly disabled/><br><span id="activityDate_validate_'.$data['id'].'" class="rfv_error" style="display:none;color:#ff0000;">Choose date</span></td>';
				echo '<td><input class="buttonsub btnTeacherCheckAbail" type="button" value="Check Availability" name="btnTeacherCheckAbail_'.$data['id'].'" id="btnTeacherCheckAbail_'.$data['id'].'" onclick="checkActAvailability(\''.$program_year_id.'\',\''.$subject_id.'\',\''.$sessionid.'\',\''.$data['teacher_id'].'\',\''.$data['id'].'\');" style="display:none;"/>
				<br><span class="rfv_error" id="room_tslot_availability_avail_'.$data['id'].'" style="color:#009900;display:none;">Available</span><span class="rfv_error" id="room_tslot_availability_not_avail_'.$data['id'].'" style="color:#ff0000;display:none;">Not Available</span></td>';
				echo '</tr>';
				if($data['reserved_flag']==1){
					echo '<script type="text/javascript">roomTslotValidateEdit(\''.$data['id'].'\');</script>';
				}
			}
            echo '</table>';
	     }
	break;
	case "del_teacher_activity":
		if(isset($_POST['id'])){
			$id = $_POST['id'];
			$del_query="delete from teacher_activity where id='".$id."'";
			$qry = mysqli_query($db, $del_query);
			if(mysqli_affected_rows($db)>0)
				echo 1;
			else
				echo 0;
		}
	break;
	case "deleteExcepTeachAvail":
		if(isset($_POST['id'])){
			$id = $_POST['id'];
			$del_ExcepTeachAvail_query="delete from teacher_availability_exception where id='".$id."'";
			$qry = mysqli_query($db, $del_ExcepTeachAvail_query);
			if(mysqli_affected_rows($db)>0)
				echo 1;
			else
				echo 0;
		}
	break;
	case "deleteExcepProgCycle":
		if(isset($_POST['id'])){
			$id = $_POST['id'];
			$del_ExcepTeachAvail_query="delete from program_cycle_exception where id='".$id."'";
			$qry = mysqli_query($db, $del_ExcepTeachAvail_query);
			if(mysqli_affected_rows($db)>0)
				echo 1;
			else
				echo 0;
		}
	break;
	case "createRules":
	 $data='';
	 if(isset($_POST['dateFrom']) && $_POST['dateFrom']!="" && isset($_POST['dateTo']) && $_POST['dateTo']!="" && $_POST['days']!=""){
	 $options .='<option value="" class="ruleOptDate" >'.$_POST['dateRange'].'</option>';
		 for($i=0;$i<count($_POST['days']);$i++){
		    $data=$_POST['days'][$i].' '.$_POST['timeSolteArr'][$i];
			$options .='<option value="" selected="selected">'.$data.'</option>';
	     }
	  }
	 echo $options;
	break;
	case "createClassAvailabilityRules":
			$objCA = new Classroom_Availability();
			$_POST['timeSoltArr1'] = $objCA->getTimeslotId($_POST['timeSoltArr']);
			$data='';
			$list='';
			$currentDateTime = date("Y-m-d H:i:s");
			$dateFrom=$_POST['dateFrom'];
			$dateTo=$_POST['dateTo'];
			$cnt=$_POST['countRule'];
			//check if the rule name exists
			$rule_query="select id, rule_name from classroom_availability_rule where rule_name='".Base::cleanText($_POST['SchdName'])."'";
			$q_res = mysqli_query($db, $rule_query);
			$dataAll = mysqli_fetch_assoc($q_res);
			//echo "count=".count($dataAll);
			if(count($dataAll)>0){
				echo '0';
			}elseif(isset($_POST['dateFrom']) && $_POST['dateFrom']!="" && isset($_POST['dateTo']) && $_POST['dateTo']!="" && $_POST['days']!="" && Base::cleanText($_POST['SchdName'])!=""){

			 $rule_qry="INSERT INTO  classroom_availability_rule VALUES ('', '".Base::cleanText($_POST['SchdName'])."', '".$dateFrom."','".$dateTo."','".$currentDateTime."', '".$currentDateTime."')";
				 $result_qry=mysqli_query($db,$rule_qry);
				 $j=0;
				 if($result_qry){
					   $last_insert_id=mysqli_insert_id($db);
					   for($i=0;$i<count($_POST['days']);$i++){
							$rule_day_qry="INSERT INTO  classroom_availability_rule_day_map VALUES ('', '".$last_insert_id."', '".$_POST['timeSoltArr'][$i]."','".$_POST['timeSoltArr1'][$i]."','".$_POST['days'][$i]."','".$currentDateTime."', '".$currentDateTime."')";
							$rule_day_qry_rslt=mysqli_query($db,$rule_day_qry);
							if($rule_day_qry_rslt){
						  $j++;
						  if($j==count($_POST['days'])){
							$message="Rules has been added successfully";
							$_SESSION['succ_msg'] = $message;
							echo '1';
						  }else{
							 $message="Rules cannot be add";
							 $_SESSION['succ_msg'] = $message;
							 echo '0';
						  }
						}
					   }
				 }else{
				   $message="Rules cannot be add";
				   $_SESSION['succ_msg'] = $message;
				   echo '0';
				  }
			}
	break;
	case "checkActAvailability":
	  $objT = new Teacher();
	  if($_POST['program_year_id']<>"" && $_POST['subject_id']<>"" && $_POST['teacher_id']<>"" && $_POST['sessionid']<>"")
	  {
			$program_year_id = $_POST['program_year_id'];
			$subject_id = $_POST['subject_id'];
			$sessionid = $_POST['sessionid'];
			$teacher_id = $_POST['teacher_id'];
			$room_id = $_POST['room_id'];
			$tslot_id = $_POST['tslot_id'];
			$act_date_val = $_POST['act_date_val'];
            //check if a reserved activity already exist
			$preReserved_Id = $objT->getReservedByProgSubjSess($program_year_id,$subject_id,$sessionid);
            //check activity availability
            $resp = $objT->checkActTeaRoomTimeDate($teacher_id,$room_id,$tslot_id,$act_date_val,$preReserved_Id);
            echo $resp;
	  }
    break;
	case "createTeachAvaRule":
		$objT = new Teacher();
		//check if the rule name exists
		$rule_query="select id, rule_name from teacher_availability_rule where rule_name='".$_POST['rule_name']."'";
		$q_res = mysqli_query($db, $rule_query);
		$dataAll = mysqli_fetch_assoc($q_res);
		if(count($dataAll)>0)
		{
			echo '0';
		}else{
			//Add the new rule
			$currentDateTime = date("Y-m-d H:i:s");
			$weeksDataVal = "";
			if((isset($_POST['start_date']) && $_POST['start_date']!="") && (isset($_POST['end_date']) && $_POST['end_date']!="")){
				$weeksData = array();
				$obj = new Teacher();
				$weeksData = $obj->getWeeksInDateRange($_POST['start_date'], $_POST['end_date']);
				$weeksDataVal = implode(",",$weeksData);
			}
			if ($result = mysqli_query($db, "INSERT INTO teacher_availability_rule VALUES ('', '".$_POST['rule_name']."', '".$_POST['start_date']."', '".$_POST['end_date']."', '".$weeksDataVal."', '".$currentDateTime."', '".$currentDateTime."');")){
				//insert the days and timeslots for created rule
				$teacher_availability_rule_id = $db->insert_id;
				if($teacher_availability_rule_id!=""){
						//insert values for monday
						if(isset($_POST['timeslotMon']) && ($_POST['timeslotMon'])!=""){
						$timeslotMon = substr($_POST['timeslotMon'], 1, -1);
						$timeslotMon1 = $objT->getTimeslotId($timeslotMon);
						$result = mysqli_query($db, "INSERT INTO teacher_availability_rule_day_map VALUES ('', '".$teacher_availability_rule_id."', '".$timeslotMon."','".$timeslotMon1."', 0, 'Mon', '".$currentDateTime."', '".$currentDateTime."');");
						}
						//insert values for Tuesday
						if(isset($_POST['timeslotTue'])  && ($_POST['timeslotTue'])!=""){
						$timeslotTue = substr($_POST['timeslotTue'], 1, -1);
						$timeslotTue1 = $objT->getTimeslotId($timeslotTue);
						$result = mysqli_query($db, "INSERT INTO teacher_availability_rule_day_map VALUES ('', '".$teacher_availability_rule_id."', '".$timeslotTue."','".$timeslotTue1."', 1, 'Tue', '".$currentDateTime."', '".$currentDateTime."');");
						}
						//insert values for Wednesday
						if(isset($_POST['timeslotWed']) && ($_POST['timeslotWed'])!=""){
						$timeslotWed = substr($_POST['timeslotWed'], 1, -1);
						$timeslotWed1 = $objT->getTimeslotId($timeslotWed);
						$result = mysqli_query($db, "INSERT INTO teacher_availability_rule_day_map VALUES ('', '".$teacher_availability_rule_id."', '".$timeslotWed."','".$timeslotWed1."', 2, 'Wed', '".$currentDateTime."', '".$currentDateTime."');");
						}
						//insert values for Thursday
						if(isset($_POST['timeslotThu']) && ($_POST['timeslotThu'])!=""){
						$timeslotThu = substr($_POST['timeslotThu'], 1, -1);
						$timeslotThu1 = $objT->getTimeslotId($timeslotThu);
						$result = mysqli_query($db, "INSERT INTO teacher_availability_rule_day_map VALUES ('', '".$teacher_availability_rule_id."', '".$timeslotThu."','".$timeslotThu1."', 3, 'Thu', '".$currentDateTime."', '".$currentDateTime."');");
						}
						//insert values for Friday
						if(isset($_POST['timeslotFri']) && ($_POST['timeslotFri'])!=""){
						$timeslotFri = substr($_POST['timeslotFri'], 1, -1);
						$timeslotFri1 = $objT->getTimeslotId($timeslotFri);
						$result = mysqli_query($db, "INSERT INTO teacher_availability_rule_day_map VALUES ('', '".$teacher_availability_rule_id."', '".$timeslotFri."','".$timeslotFri1."', 4, 'Fri', '".$currentDateTime."', '".$currentDateTime."');");
						}
						//insert values for Saturday
						if(isset($_POST['timeslotSat']) && ($_POST['timeslotSat'])!=""){
						$timeslotSat =  substr($_POST['timeslotSat'], 1, -1);
						$timeslotSat1 = $objT->getTimeslotId($timeslotSat);
						$result = mysqli_query($db, "INSERT INTO teacher_availability_rule_day_map VALUES ('', '".$teacher_availability_rule_id."', '".$timeslotSat."','".$timeslotSat1."', 5, 'Sat', '".$currentDateTime."', '".$currentDateTime."');");
						}
						echo '1';
				}else{
						echo '0';
				}
			}else{
				echo '0';
			}
		}
	break;
	case "del_teachAvailMap":
		if(isset($_POST['id'])){
			$id = $_POST['id'];
			$del_TeachAvail_query="delete from teacher_availability_rule_teacher_map where teacher_id='".$id."'";
			$qry = mysqli_query($db, $del_TeachAvail_query);
			if(mysqli_affected_rows($db)>0){
				echo 1;
				//delete the related exception for the teacher
				$del_ExcepTeachAvail_query="delete from teacher_availability_exception where teacher_id='".$id."'";
				$qry = mysqli_query($db, $del_ExcepTeachAvail_query);
			}else
				echo 0;
			}
    break;
	case "del_cls_exception":
		if(isset($_POST['id'])){
			$id = $_POST['id'];
			$del_class_excepton_query="delete from classroom_availability_exception where id='".$id."'";
			$qry = mysqli_query($db, $del_class_excepton_query);
			if(mysqli_affected_rows($db)>0)
				echo 1;
			else
				echo 0;
		}
    break;
	case "del_classroom_availabilty":
		if(isset($_POST['id'])){
		 $id = $_POST['id'];
		 $del_clsrmAvail_query="delete from classroom_availability_rule_room_map where room_id='".$id."'";
		 $qry = mysqli_query($db, $del_clsrmAvail_query);
		if(mysqli_affected_rows($db)>0){
			echo 1;
		 //delete the related exception for the teacher
		$del_ExcepclsrmAvail_query="delete from classroom_availability_exception where room_id='".$id."'";
		$qry = mysqli_query($db, $del_ExcepclsrmAvail_query);
		}else
		 echo 0;
		}
    break;
	case "del_holiday":
		if(isset($_POST['id'])){
			$id = $_POST['id'];
			$del_holiday_query="delete from holidays where id='".$id."'";
			$qry = mysqli_query($db, $del_holiday_query);
			if(mysqli_affected_rows($db)>0)
				echo 1;
			else
				echo 0;
		}
	break;
	case "getCyclesByPyId":
		$options='<option value="">--Select Cycle--</option>';
		if(isset($_POST['py_id']) && $_POST['py_id']!=""){
			$py_id = $_POST['py_id'];
			$query="SELECT * FROM cycle WHERE program_year_id = '".$py_id."' ORDER BY id";
			$result = mysqli_query($db, $query);
			$i=0;
			while($data= mysqli_fetch_array($result)){
			     $i++;
				 $options .='<option value="'.$data['id'].'">'.$i.'</option>';
			}
		}
		echo $options;
	break;
	case "del_cycle":
	if(isset($_POST['id'])){
		$id = $_POST['id'];
		$del_cycle_query="delete from cycle where program_year_id='".$id."'";
		$qry = mysqli_query($db, $del_cycle_query);
		if(mysqli_affected_rows($db)>0){
			//delete the related exception if exists
			$slct_qry = "select * from program_cycle_exception where program_year_id='".$id."'";
			$qry_slct = mysqli_query($db,$slct_qry);
			if(mysqli_num_rows($qry_slct)>0){
				$del_pgm_exp_query="delete from program_cycle_exception where program_year_id='".$id."'";
				$qry_pgm_exp = mysqli_query($db,$del_pgm_exp_query);
			}
			echo 1;
		}else{
			echo 0;
		}
	}
	break;
	case "getCycles":
		if(isset($_POST['progId']) && $_POST['progId']!=""){
				$options .='<option value="">--Select Cycle--</option>';
				$cycle_query="select * from cycle where program_year_id='".$_POST['progId']."'";
				$cycleDataall = mysqli_query($db, $cycle_query);
				$cycleData = array();
				while ($cycleDatas = mysqli_fetch_assoc($cycleDataall)){
					$cycleData[] = $cycleDatas['id'];
				}
				if(count($cycleData)>0){
					for($i=0; $i<count($cycleData); $i++){
						$indexData = $i+1;
						$options .='<option value="'.$cycleData[$i].'" >'.$indexData.'</option>';
					}
				}
		}
		echo $options;
	break;
    case "add_sub_session":
        $sess_hidden_id = trim($_POST['sess_hidden_id']);
        $act_hidden_id = trim($_POST['act_hidden_id']);
        //check if same session name already created
        $rule_query = "select id, session_name from subject_session where subject_id='" . $_POST['subjectId'] . "' and session_name = '" . $_POST['txtSessionName'] . "'";
        if ($sess_hidden_id <> ""){
            $rule_query .= " AND id != " . $sess_hidden_id . "";
        }
        $q_res = mysqli_query($db, $rule_query);
        $dataAll = mysqli_fetch_assoc($q_res);
        if (count($dataAll) > 0) {
            echo '2';
        } else {
            //if only session name and duration is provide, just create a session and exit
            $currentDateTime = date("Y-m-d H:i:s");
            if ((isset($_POST['txtSessionName']) && $_POST['txtSessionName'] != "") && (isset($_POST['tslot_id']) && $_POST['tslot_id'] == "") && (isset($_POST['room_id']) && $_POST['room_id'] == "") && (isset($_POST['subSessDate']) && $_POST['subSessDate'] == "")) {
                //check the total no of values in subject session to make a new order no
                $sessCount_query = "select count(id) as total from subject_session";
                $sessCount_res = mysqli_query($db, $sessCount_query);
                $sessCount_data = mysqli_fetch_assoc($sessCount_res);
                $txtOrderNum = $sessCount_data['total'] + 1;
                $duration = $_POST['duration'];
                if ($_POST['duration'] == "") {
                    $duration = 60;
                }
                if (!empty($sess_hidden_id)) {
                    //add new session
                    $result = mysqli_query($db, "UPDATE subject_session SET
                                                        session_name = '" . $_POST['txtSessionName'] . "',
                                                        description = '" . $_POST['txtareaSessionDesp'] . "',
                                                        case_number = '" . $_POST['txtCaseNo'] . "',
                                                        technical_notes = '" . $_POST['txtareatechnicalNotes'] . "',
                                                        date_update = NOW() WHERE id = $sess_hidden_id");
                } else {
                    //add new session
                    $result = mysqli_query($db, "INSERT INTO subject_session (id, subject_id, cycle_no, session_name, order_number, description, case_number, technical_notes, duration, date_add, date_update) VALUES('', '" . $_POST['subjectId'] . "', '" . $_POST['cycleId'] . "', '" . $_POST['txtSessionName'] . "', '" . $txtOrderNum . "', '" . $_POST['txtareaSessionDesp'] . "', '" . $_POST['txtCaseNo'] . "', '" . $_POST['txtareatechnicalNotes'] . "', '" . $duration . "', NOW(), NOW());");
                }

                //if only teacher name is also provided then create an un-reserved activity
                if (mysqli_affected_rows($db) > 0) {
                    if (isset($_POST['slctTeacher']) && $_POST['slctTeacher'] != "") {
                        if(!empty($sess_hidden_id))
                            $sessionId = $sess_hidden_id;
                        else
                            $sessionId = mysqli_insert_id($db);
                        $group_id = "";
                        //get last created activity name
                        $result3 = mysqli_query($db, "SELECT name FROM teacher_activity ORDER BY id DESC LIMIT 1");
                        $dRow = mysqli_fetch_assoc($result3);
                        $actCnt = substr($dRow['name'], 1);
                        $actName = 'A' . ($actCnt + 1);

                        if (!empty($act_hidden_id)) {
                            //update activity
                            $result2 = mysqli_query($db, "UPDATE teacher_activity SET
															teacher_id = '" . $_POST['slctTeacher'] . "',
															room_id = '',
															start_time = '',
															timeslot_id = '',
															act_date = '',
															reserved_flag = '0',
															date_update = NOW() WHERE id = $act_hidden_id");
                        } else {
							//insert new activity
                            $result2 = mysqli_query($db, "INSERT INTO teacher_activity (id, name, program_year_id, cycle_id, subject_id, session_id, teacher_id, group_id, room_id, start_time, timeslot_id, act_date, reserved_flag, date_add, date_update) VALUES ('', '" . $actName . "', '" . $_POST['programId'] . "', '" . $_POST['cycleId'] . "', '" . $_POST['subjectId'] . "', '" . $sessionId . "', '" . $_POST['slctTeacher'] . "', '" . $group_id . "','', '', '', '' , '0', NOW(), NOW());");
                        }
                        if (mysqli_affected_rows($db) > 0) {
                            echo 1;
                        } else {
                            echo 0;
                        }
                        exit;
                    } else {
                        $del_act_query = "delete from teacher_activity where id='" . $act_hidden_id . "'";
                        $qry = mysqli_query($db, $del_act_query);
                    }
                    echo 1;
                } else {
                    echo 0;
                }
                exit;
            }

            //if all fields are present then first add session and then create activities after checking the availability
            if ((isset($_POST['txtSessionName']) && $_POST['txtSessionName'] != "") && (isset($_POST['slctTeacher']) && $_POST['slctTeacher'] != "") && (isset($_POST['tslot_id']) && $_POST['tslot_id'] != "") && (isset($_POST['duration']) && $_POST['duration'] != "") && (isset($_POST['room_id']) && $_POST['room_id'] != "") && (isset($_POST['subSessDate']) && $_POST['subSessDate'] != "")) {
                //calculate all TS ids for the activity
                $tsIdsAll = "";
                $timeslotIdsArray = array();
                if ($_POST['duration'] > 15) {
                    $noOfslots = $_POST['duration'] / 15;
                    $startTS = $_POST['tslot_id'];
                    $endTS = $startTS + $noOfslots;
                    for ($i = $startTS; $i < $endTS; $i++) {
                        $timeslotIdsArray[] = $i;
                    }
                } else {
                    $timeslotIdsArray[] = $_POST['tslot_id'];
                }
                $tsIdsAll = implode(',', $timeslotIdsArray);
                $group_id = "";
                //check the availability of activity with different restrictions
                $valid = 1;
                //Rule: check if program can have class on selected date and time.
                if ($valid == 1) {
                    //start
                    $final_programs = array();
					$sql_pgm_cycle = mysqli_query($db, "select * from cycle where program_year_id = '" . $_POST['programId'] . "' and '" . $_POST['subSessDate'] . "' >= start_week and '" . $_POST['subSessDate'] . "' <= end_week");
                    $dayFromDate = date('w', strtotime($_POST['subSessDate']));
                    $day = $dayFromDate - 1;
                    //$pgm_cycle_data = mysqli_fetch_assoc($sql_pgm_cycle);
                    $pgm_cycle_cnt = mysqli_num_rows($sql_pgm_cycle);
                    if ($pgm_cycle_cnt > 0) {
                        $result_pgm_cycle = mysqli_fetch_array($sql_pgm_cycle);
                        //echo $result_pgm_cycle['occurrence'];
                        if ($result_pgm_cycle['occurrence'] == '1w') {
                            $week1 = $result_pgm_cycle['week1'];
                            $week1 = unserialize($week1);
                            foreach ($week1 as $key => $value) {
                                if ($day == $key && in_array($_POST['tslot_id'], $week1[$day])) {
                                    $sql_pgm_cycle_exp = mysqli_query($db, "SELECT exception_date from program_cycle_exception where program_year_id='" . $result_pgm_cycle['program_year_id'] . "' and exception_date='" . $_POST['subSessDate'] . "'");
                                    $sql_pgm_cycle_exp_cnt = mysqli_num_rows($sql_pgm_cycle_exp);
                                    if ($sql_pgm_cycle_exp_cnt <= 0) {
                                        $final_programs[] = $result_pgm_cycle['program_year_id'];
                                        //$i++;
                                    } else {
                                        echo 9;
                                        $valid = 0;
                                        exit;
                                    }
                                }
                            }
                        } else if ($result_pgm_cycle['occurrence'] == '2w') {
                            $obj = new Subjects();
                            $week = $obj->getWeekFromDate($_POST['subSessDate'], $result_pgm_cycle['start_week'], $result_pgm_cycle['end_week']);
                            if ($week == '1') {
                                $week1 = $result_pgm_cycle['week1'];
                                $week1 = unserialize($week1);
                                foreach ($week1 as $key => $value) {
                                    if ($day == $key && in_array($_POST['tslot_id'], $week1[$day])) {
                                        $sql_pgm_cycle_exp = mysqli_query($db, "SELECT exception_date from program_cycle_exception where program_year_id='" . $result_pgm_cycle['program_year_id'] . "' and exception_date='" . $_POST['subSessDate'] . "'");
                                        $sql_pgm_cycle_exp_cnt = mysqli_num_rows($sql_pgm_cycle_exp);
                                        if ($sql_pgm_cycle_exp_cnt <= 0) {
                                            $final_programs[] = $result_pgm_cycle['program_year_id'];
                                            //$i++;
                                        } else {
                                            echo 9;
                                            $valid = 0;
                                            exit;
                                        }
                                    }
                                }
                            } else if ($week == '2' and count(unserialize($result_pgm_cycle['week2'])) > 0) {
                                $week2 = $result_pgm_cycle['week2'];
                                $week2 = unserialize($week2);
                                foreach ($week2 as $key => $value) {
                                    if ($day == $key && in_array($_POST['tslot_id'], $week2[$day])) {
                                        $sql_pgm_cycle_exp = mysqli_query($db, "SELECT exception_date from program_cycle_exception where program_year_id='" . $result_pgm_cycle['program_year_id'] . "' and exception_date='" . $_POST['subSessDate'] . "'");
                                        $sql_pgm_cycle_exp_cnt = mysqli_num_rows($sql_pgm_cycle_exp);
                                        if ($sql_pgm_cycle_exp_cnt <= 0) {
                                            $final_programs[] = $result_pgm_cycle['program_year_id'];
                                            //$i++;
                                        } else {
                                            echo 9;
                                            $valid = 0;
                                            exit;
                                        }
                                    }
                                }
                            }
                        }
						//check for additional days availability
						$sql_pgm_add_date = mysqli_query($db, "select additional_date,actual_timeslot_id from program_cycle_additional_day_time where additional_date between  '".$result_pgm_cycle['start_week']."' and '".$result_pgm_cycle['end_week']."' and program_year_id = '".$result_pgm_cycle['program_year_id']."'");
						while($result_pgm_add_date = mysqli_fetch_array($sql_pgm_add_date))
						{
							$ts_array = explode(",",$result_pgm_add_date['actual_timeslot_id']);
							if(array_key_exists($result_pgm_add_date['additional_date'],$final_programs))
							{
								$new_arr = array_unique(array_merge($final_programs,$ts_array));		
								$final_programs[$result_pgm_cycle['program_year_id']][$result_pgm_cycle['id']][$result_pgm_add_date['additional_date']] = $new_arr;
							}else{
								$final_programs[$result_pgm_cycle['program_year_id']][$result_pgm_cycle['id']][$result_pgm_add_date['additional_date']] = $ts_array;
							}
						}	
                    } else {
                        echo 9;
                        $valid = 0;
                        exit;
                    }
                    if (count($final_programs) <= 0) {
                        echo 9;
                        $valid = 0;
                        exit;
                    }
                }
                //Rule: All the activities of a program needs to be in same classroom for one week
                if (isset($_POST['subjectId']) && $_POST['subjectId'] != "") {
                    //find out the start and end date of the week in which this activity will happen
                    $timestamp = strtotime($_POST['subSessDate']);
                    $startDateOfWeek = (date("D", $timestamp) == 'Mon') ? date('Y-m-d', $timestamp) : date('Y-m-d', strtotime('Last Monday', $timestamp));
                    $endDateOfWeek = (date("D", $timestamp) == 'Sun') ? date('Y-m-d', $timestamp) : date('Y-m-d', strtotime('Next Sunday', $timestamp));
                    $query = "select ss.*, ta.room_id, ta.act_date, ta.reserved_flag from subject_session as ss LEFT JOIN teacher_activity as ta ON ss.id=ta.session_id where ss.subject_id='" . $_POST['subjectId'] . "' and ta.reserved_flag=1 and ta.act_date > '".$startDateOfWeek."' and ta.act_date < '".$endDateOfWeek."'";
                    if ($sess_hidden_id <> "") {
                        $query .= " AND ss.id != " . $sess_hidden_id . "";
                    }
                    if ($act_hidden_id <> "") {
                        $query .= " AND ta.id != " . $act_hidden_id . "";
                    }
                    $q_res = mysqli_query($db, $query);
                    $dataAll = mysqli_fetch_assoc($q_res);
                    if (mysqli_affected_rows($db) > 0) {
                        if ($dataAll['room_id'] != $_POST['room_id']) {
                            echo 6;
                            $valid = 0;
                            exit;
                        }
                    }
                }
                //check if teacher is available on the given time and day
                if ($valid == 1) {
                    //check if the selected date is not added as exception by the teacher while providing availability
                    $query = "select id from teacher_availability_exception where teacher_id = '" . $_POST['slctTeacher'] . "' and exception_date = '" . $_POST['subSessDate'] . "'";
                    $q_res = mysqli_query($db, $query);
                    if (mysqli_affected_rows($db) == 0) {
                        //find the day using date
                        $day = date('w', strtotime($_POST['subSessDate']));
                        $final_day = $day - 1;
                        //check if teacher is available on the given time and day
                        $teachAvail_query = "select tm.id
												from teacher_availability_rule_teacher_map tm
												inner join teacher_availability_rule_day_map td on td.teacher_availability_rule_id = tm.teacher_availability_rule_id
												inner join teacher_availability_rule ta on ta.id = td.teacher_availability_rule_id
												where start_date <= '" . $_POST['subSessDate'] . "' and end_date >= '" . $_POST['subSessDate'] . "' and day= '" . $final_day . "' and tm.teacher_id='" . $_POST['slctTeacher'] . "' and td.actual_timeslot_id like '%" . $tsIdsAll . "%'";
                        $q_res = mysqli_query($db, $teachAvail_query);
                        if (mysqli_affected_rows($db) <= 0) {
                            echo 5;
                            $valid = 0;
                            exit;
                        }
                    } else {
                        echo 5;
                        $valid = 0;
                        exit;
                    }
                }
                //check if teacher is not engaged in some other reserved activity
                if ($valid == 1) {
                    $teachAvail_query = "select ss.*, ta.room_id, ta.act_date, ta.timeslot_id, ta.teacher_id, ta.reserved_flag from subject_session as ss LEFT JOIN teacher_activity as ta ON ss.id=ta.session_id where ta.reserved_flag=1 and ta.timeslot_id REGEXP '" . RexExpFormat($tsIdsAll) . "' and ta.act_date='" . $_POST['subSessDate'] . "' and ta.teacher_id='" . $_POST['slctTeacher'] . "'";
                    if ($sess_hidden_id <> "") {
                        $teachAvail_query .= " AND ss.id != " . $sess_hidden_id . "";
                    }
                    if ($act_hidden_id <> "") {
                        $teachAvail_query .= " AND ta.id != " . $act_hidden_id . "";
                    }
                    $q_res = mysqli_query($db, $teachAvail_query);
                    if (mysqli_affected_rows($db) > 0) {
                        echo 3;
                        $valid = 0;
                        exit;
                    }
                }
                //Rule: A teacher cannot have more than 4 sessions per day
                if ($valid == 1) {
                    $teachAvail_query = "select id from teacher_activity where reserved_flag=1 and act_date='" . $_POST['subSessDate'] . "' and teacher_id='" . $_POST['slctTeacher'] . "'";
					if ($act_hidden_id <> "") {
                        $teachAvail_query .= " AND ta.id != " . $act_hidden_id . "";
                    }
                    $q_res = mysqli_query($db, $teachAvail_query);
                    if (mysqli_affected_rows($db) >= 4) {
                        echo 10;
                        $valid = 0;
                        exit;
                    }
                }
                //Rule: a teacher cannot have classes in different locations the same day
                if ($valid == 1) {
                    $loc_query = "select location_id from room r inner join building b on b.id = r.building_id where r.id = '" . $_POST['room_id'] . "'";
                    $loc_res = mysqli_query($db, $loc_query);
                    $dataLoc = mysqli_fetch_assoc($loc_res);
                    $teachAvail_query = "select location_id from teacher_activity ta inner join room r on r.id = ta.room_id
						inner join building b on b.id = r.building_id where reserved_flag=1 and act_date='" . $_POST['subSessDate'] . "' and teacher_id='" . $_POST['slctTeacher'] . "'";
					if ($act_hidden_id <> "") {
                        $teachAvail_query .= " AND ta.id != " . $act_hidden_id . "";
                    }
                    $q_res = mysqli_query($db, $teachAvail_query);
                    if (mysqli_affected_rows($db) > 0) {
                        $dataAll = mysqli_fetch_assoc($q_res);
                        if ($dataAll['location_id'] != $dataLoc['location_id']) {
                            echo 11;
                            $valid = 0;
                            exit;
                        }
                    }
                }
                //Rule: a teacher can have maximum two saturdays per cycle
				if($_POST['force_var']==""){
                	if ($valid == 1) {
					$day = date('w', strtotime($_POST['subSessDate']));
					$final_day = $day - 1;
					if ($final_day == 5) {
						$cycle_query = "select id from cycle where program_year_id = '" . $_POST['programId'] . "' and start_week <= '" . $_POST['subSessDate'] . "' and end_week >= '" . $_POST['subSessDate'] . "'";
						$cycle_res = mysqli_query($db, $cycle_query);
						$dataCycle = mysqli_fetch_assoc($cycle_res);

						$teachAvail_query = "select distinct act_date from teacher_activity where reserved_flag=1 and cycle_id='" . $dataCycle['id'] . "' and teacher_id='" . $_POST['slctTeacher'] . "' and act_date != '" . $_POST['subSessDate'] . "'";
						$q_res = mysqli_query($db, $teachAvail_query);
						$count = 0;
						while ($dataAll = mysqli_fetch_assoc($q_res)) {
							$day = date('w', strtotime($dataAll['act_date']));
							$final_day = $day - 1;
							if ($final_day == 5) {
								$count++;
							}
						}
						if ($count >= 2) {
							echo 12;
							$valid = 0;
							exit;
						}
					}
                  }
				}
                //Rule the sessions scheduled on Saturdays should be from the same academic area.
				if($_POST['force_var']==""){
                  if ($valid == 1) {
                    $day = date('w', strtotime($_POST['subSessDate']));
                    $final_day = $day - 1;
                    if ($final_day == 5) {
                        $sub_query = "select area_id from subject s where id = '" . $_POST['subjectId'] . "'";
                        $sub_res = mysqli_query($db, $sub_query);
                        $dataSub = mysqli_fetch_assoc($sub_res);
                        $teachAvail_query = "select s.area_id from teacher_activity ta inner join subject s on s.id = ta.subject_id where reserved_flag=1 and act_date='" . $_POST['subSessDate'] . "' and ta.program_year_id = '".$_POST['programId']."' and ta.forced_flag = '0'";
						$q_res = mysqli_query($db, $teachAvail_query);
                        if (mysqli_affected_rows($db) > 0) {
                            $dataAll = mysqli_fetch_assoc($q_res);
                            if ($dataAll['area_id'] != $dataSub['area_id']) {
                                echo 13;
                                $valid = 0;
                                exit;
                            }
                        }
                    }
                  }
				}

                //check if room is free at given time and day
                if ($valid == 1) {
                    //check if the selected date is not added as exception in classroom availability
                    $query = "select id from classroom_availability_exception where room_id = '" . $_POST['room_id'] . "' and exception_date = '" . $_POST['subSessDate'] . "'";
                    $q_res = mysqli_query($db, $query);
                    if (mysqli_affected_rows($db) == 0) {
                        //find the day using date
                        $day = date('w', strtotime($_POST['subSessDate']));
                        $final_day = $day - 1;
                        //check if classroom is available on the given time and day
                        $classroomAvail_query = "select cm.room_id, room.room_name
											from classroom_availability_rule_room_map cm
											inner join classroom_availability_rule_day_map cd on cd.classroom_availability_rule_id = cm.classroom_availability_rule_id
											inner join classroom_availability_rule ca on ca.id = cd.classroom_availability_rule_id
											inner join room on room.id = cm.room_id
											where start_date <= '" . $_POST['subSessDate'] . "' and end_date >= '" . $_POST['subSessDate'] . "' and day= '" . $final_day . "' and cm.room_id='" . $_POST['room_id'] . "' and actual_timeslot_id like '%" . $tsIdsAll . "%'";
                        $q_res = mysqli_query($db, $classroomAvail_query);
                        if (mysqli_affected_rows($db) <= 0) {
                            echo 7;
                            $valid = 0;
                            exit;
                        }
                    } else {
                        echo 7;
                        $valid = 0;
                        exit;
                    }
                    //check if room is not engaged in any reserved activity
                    $roomAvail_query = "select ss.*, ta.room_id, ta.act_date, ta.timeslot_id, ta.teacher_id, ta.reserved_flag from subject_session as ss LEFT JOIN teacher_activity as ta ON ss.id=ta.session_id where ta.reserved_flag=1 and ta.room_id='" . $_POST['room_id'] . "' and ta.timeslot_id REGEXP '" . RexExpFormat($tsIdsAll) . "' and ta.act_date='" . $_POST['subSessDate'] . "'";
                    if ($sess_hidden_id <> "") {
                        $roomAvail_query .= " AND ss.id != " . $sess_hidden_id . "";
                    }
                    if ($act_hidden_id <> "") {
                        $roomAvail_query .= " AND ta.id != " . $act_hidden_id . "";
                    }
                    $q_res = mysqli_query($db, $roomAvail_query);
                    mysqli_affected_rows($db);
                    if (mysqli_affected_rows($db) > 0) {
                        echo 4;
                        $valid = 0;
                        exit;
                    }
                    //end
                }
                //add a new session and activity if all the validations are passed
                if ($valid == 1) {
                    $reserved_flag = "1";
                    //check the total no of values in subject session to make a new order no
                    $sessCount_query = "select count(id) as total from subject_session";
                    $sessCount_res = mysqli_query($db, $sessCount_query);
                    $sessCount_data = mysqli_fetch_assoc($sessCount_res);
                    $txtOrderNum = $sessCount_data['total'] + 1;
                    if ($sess_hidden_id <> "") {
                        $result = mysqli_query($db, "UPDATE subject_session SET
							                                    cycle_no = '" . $_POST['cycleId'] . "',
							                                    session_name = '" . $_POST['txtSessionName'] . "',
							                                    description = '" . $_POST['txtareaSessionDesp'] . "',
							                                    case_number = '" . $_POST['txtCaseNo'] . "',
							                                    technical_notes = '" . $_POST['txtareatechnicalNotes'] . "',
							                                    duration = '" . $_POST['duration'] . "',
							                                    date_update = NOW() WHERE id = $sess_hidden_id");
                    } else {
                        $result = mysqli_query($db, "INSERT INTO subject_session(id, subject_id, cycle_no, session_name, order_number, description, case_number, technical_notes, duration, date_add, date_update) VALUES ('', '" . $_POST['subjectId'] . "', '" . $_POST['cycleId'] . "', '" . $_POST['txtSessionName'] . "', '" . $txtOrderNum . "', '" . $_POST['txtareaSessionDesp'] . "', '" . $_POST['txtCaseNo'] . "', '" . $_POST['txtareatechnicalNotes'] . "', '" . $_POST['duration'] . "', NOW(), NOW());");
                    }

                    if (mysqli_affected_rows($db) > 0) {
                        if(!empty($sess_hidden_id))
                            $sessionId = $sess_hidden_id;
                        else
                            $sessionId = mysqli_insert_id($db);
                        //get last created activity name
                        $result3 = mysqli_query($db, "SELECT name FROM teacher_activity ORDER BY id DESC LIMIT 1");
                        $dRow = mysqli_fetch_assoc($result3);
                        $actCnt = substr($dRow['name'], 1);
                        $actName = 'A' . ($actCnt + 1);
                        //insert new activity
                        if ($act_hidden_id <> "") {
                            $result2 = mysqli_query($db, "UPDATE teacher_activity SET
							                                       program_year_id = '" . $_POST['programId'] . "',
							                                       cycle_id = '" . $_POST['cycleId'] . "',
							                                       subject_id = '" . $_POST['subjectId'] . "',
							                                       teacher_id = '" . $_POST['slctTeacher'] . "',
							                                       group_id = '" . $group_id . "',
							                                       room_id = '" . $_POST['room_id'] . "',
							                                       start_time = '" . $_POST['tslot_id'] . "',
							                                       timeslot_id = '" . $tsIdsAll . "',
							                                       act_date = '" . $_POST['subSessDate'] . "',
							                                       reserved_flag = '" . $reserved_flag . "',
							                                       date_update=  NOW(),
																   forced_flag = '".$_POST['force_flag']."' WHERE id= $act_hidden_id");
                        } else {
                            $result2 = mysqli_query($db, "INSERT INTO teacher_activity (id, name, program_year_id, cycle_id, subject_id, session_id, teacher_id, group_id, room_id, start_time, timeslot_id, act_date, reserved_flag, date_add, date_update, forced_flag) VALUES ('', '" . $actName . "', '" . $_POST['programId'] . "', '" . $_POST['cycleId'] . "', '" . $_POST['subjectId'] . "', '" . $sessionId . "', '" . $_POST['slctTeacher'] . "', '" . $group_id . "','" . $_POST['room_id'] . "', '" . $_POST['tslot_id'] . "', '" . $tsIdsAll . "', '" . $_POST['subSessDate'] . "', '" . $reserved_flag . "', NOW(), NOW(), '".$_POST['force_flag']."');");
                        }

                        if (mysqli_affected_rows($db) > 0) {
                            echo 1;
                        } else {
                            echo 0;
                        }
                    } else {
                        echo 0;
                    }
                } else {
                    echo 0;
                }
            } else {
                echo 8;
            }
        }
    break;
	case "del_sub_activity_session":
		if(isset($_POST['activityId']) && isset($_POST['sessionID'])){
			$activityId = $_POST['activityId'];
			//delete activity
			$del_act_query="delete from teacher_activity where id='".$_POST['activityId']."'";
			$qry = mysqli_query($db, $del_act_query);
			if(mysqli_affected_rows($db)>0){
				$del_session="delete from subject_session where id='".$_POST['sessionID']."'";
				$qry = mysqli_query($db, $del_session);
				if(mysqli_affected_rows($db)>0){
					echo 1;
				}else{
					echo 0;
				}
			}else{
				echo 0;
			}
	}
    break;
    case "checkAvailabilitySession":
        //check if same session name already created	
        $sess_hidden_id = trim($_POST['sess_hidden_id']);
        $act_hidden_id = trim($_POST['act_hidden_id']);
        $rule_query = "select id, session_name from subject_session where subject_id='" . $_POST['subjectId'] . "' and session_name = '" . $_POST['txtSessionName'] . "'";
        if ($sess_hidden_id <> "") {
            $rule_query .= " AND id != " . $sess_hidden_id . "";
        }
        $q_res = mysqli_query($db, $rule_query);
        $dataAll = mysqli_fetch_assoc($q_res);
        if (count($dataAll) > 0) {
            echo '2';
            $valid = 0;
            exit;
        } else {
            $currentDateTime = date("Y-m-d H:i:s");
            //if all fields are present CHECK ALL THE VALIDATIONS
            if ((isset($_POST['txtSessionName']) && $_POST['txtSessionName'] != "") && (isset($_POST['slctTeacher']) && $_POST['slctTeacher'] != "") && (isset($_POST['tslot_id']) && $_POST['tslot_id'] != "") && (isset($_POST['duration']) && $_POST['duration'] != "") && (isset($_POST['room_id']) && $_POST['room_id'] != "") && (isset($_POST['subSessDate']) && $_POST['subSessDate'] != "")) {
                //calculate all TS ids for the activity
                $tsIdsAll = "";
                $timeslotIdsArray = array();
                if ($_POST['duration'] > 15) {
                    $noOfslots = $_POST['duration'] / 15;
                    $startTS = $_POST['tslot_id'];
                    $endTS = $startTS + $noOfslots;
                    for ($i = $startTS; $i < $endTS; $i++) {
                        $timeslotIdsArray[] = $i;
                    }
                } else {
                    $timeslotIdsArray[] = $_POST['tslot_id'];
                }
                $tsIdsAll = implode(',', $timeslotIdsArray);
                $group_id = "";
                //check the availability of activity with different restrictions
                $valid = 1;
                //Rule: check if program can have class on selected date and time.
                if ($valid == 1) {
					$final_programs = array(); $availTSIdsonSelDay = array();
                    $ttObj = new Timetable();
					$new_pgms['0'] = $_POST['programId'];
					$final_programs = $ttObj->search_programs($_POST['subSessDate'],$_POST['subSessDate'],$new_pgms);
					if(isset($final_programs[$_POST['programId']][$_POST['cycleId']][$_POST['subSessDate']])){
						$availTSIdsonSelDay = $final_programs[$_POST['programId']][$_POST['cycleId']][$_POST['subSessDate']];
					}
					//print"<pre>";print_r($availTSIdsonSelDay);die;
					if(count($timeslotIdsArray)>0 && count($availTSIdsonSelDay)>0 && (count(array_intersect($timeslotIdsArray, $availTSIdsonSelDay)) == count($timeslotIdsArray))){
						//program is available
					}else{
                        echo 9;
                        $valid = 0;
                        exit;
                    }
                }
                //Rule: All the activities of a program needs to be in same classroom for one week
                if (isset($_POST['subjectId']) && $_POST['subjectId'] != "") {
                    //find out the start and end date of the week in which this activity will happen
                    $timestamp = strtotime($_POST['subSessDate']);
                    $startDateOfWeek = (date("D", $timestamp) == 'Mon') ? date('Y-m-d', $timestamp) : date('Y-m-d', strtotime('Last Monday', $timestamp));
                    $endDateOfWeek = (date("D", $timestamp) == 'Sun') ? date('Y-m-d', $timestamp) : date('Y-m-d', strtotime('Next Sunday', $timestamp));
                    $query = "select ss.*, ta.room_id, ta.act_date, ta.reserved_flag from subject_session as ss LEFT JOIN teacher_activity as ta ON ss.id=ta.session_id where ss.subject_id='" . $_POST['subjectId'] . "' and ta.reserved_flag=1 and ta.act_date > '".$startDateOfWeek."' and ta.act_date < '".$endDateOfWeek."'";
                    if ($sess_hidden_id <> "") {
                        $query .= " AND ss.id != " . $sess_hidden_id . "";
                    }
                    if ($act_hidden_id <> "") {
                        $query .= " AND ta.id != " . $act_hidden_id . "";
                    }
                    $q_res = mysqli_query($db, $query);
                    $dataAll = mysqli_fetch_assoc($q_res);
                    if (mysqli_affected_rows($db) > 0) {
                        if ($dataAll['room_id'] != $_POST['room_id']) {
                            echo 6;
                            $valid = 0;
                            exit;
                        }
                    }
                }
                //check if teacher is available on the given time and day
                if ($valid == 1) {
                    //check if the selected date is not added as exception by the teacher while providing availability
                    $query = "select id from teacher_availability_exception where teacher_id = '" . $_POST['slctTeacher'] . "' and exception_date = '" . $_POST['subSessDate'] . "'";
                    $q_res = mysqli_query($db, $query);
                    if (mysqli_affected_rows($db) == 0) {
                        //find the day using date
                        $day = date('w', strtotime($_POST['subSessDate']));
                        $final_day = $day - 1;
                        //check if teacher is available on the given time and day
                        $teachAvail_query = "select tm.id
												from teacher_availability_rule_teacher_map tm
												inner join teacher_availability_rule_day_map td
												on td.teacher_availability_rule_id = tm.teacher_availability_rule_id
												inner join teacher_availability_rule ta
												on ta.id = td.teacher_availability_rule_id
												where start_date <= '" . $_POST['subSessDate'] . "' and end_date >= '" . $_POST['subSessDate'] . "' and day= '" . $final_day . "' and tm.teacher_id='" . $_POST['slctTeacher'] . "' and td.actual_timeslot_id like '%" . $tsIdsAll . "%'";
                        $q_res = mysqli_query($db, $teachAvail_query);
                        if (mysqli_affected_rows($db) <= 0) {
                            echo 5;
                            $valid = 0;
                            exit;
                        }
                    } else {
                        echo 5;
                        $valid = 0;
                        exit;
                    }
                }
                //check if teacher is not engaged in some other reserved activity
                if ($valid == 1) {
                    $teachAvail_query = "select ss.*, ta.room_id, ta.act_date, ta.timeslot_id, ta.teacher_id, ta.reserved_flag from subject_session as ss LEFT JOIN teacher_activity as ta ON ss.id=ta.session_id where ta.reserved_flag=1 and ta.timeslot_id REGEXP '" . RexExpFormat($tsIdsAll) . "' and ta.act_date='" . $_POST['subSessDate'] . "' and ta.teacher_id='" . $_POST['slctTeacher'] . "'";
                    if ($sess_hidden_id <> "") {
                        $teachAvail_query .= " AND ss.id != " . $sess_hidden_id . "";
                    }
                    if ($act_hidden_id <> "") {
                        $teachAvail_query .= " AND ta.id != " . $act_hidden_id . "";
                    }

                    //destination REGEXP '".RexExpFormat($group_name)."'

                    $q_res = mysqli_query($db, $teachAvail_query);
                    if (mysqli_affected_rows($db) > 0) {
                        echo 3;
                        $valid = 0;
                        exit;
                    }
                }
                //Rule: a teacher cannot have more than 4 sessions per day
                if ($valid == 1) {
                    $teachAvail_query = "select id from teacher_activity where reserved_flag=1 and act_date='" . $_POST['subSessDate'] . "' and teacher_id='" . $_POST['slctTeacher'] . "'";
                    if ($act_hidden_id <> "") {
                        $teachAvail_query .= " AND id != " . $act_hidden_id . "";
                    }

                    $q_res = mysqli_query($db, $teachAvail_query);
                    if (mysqli_affected_rows($db) >= 4) {
                        echo 10;
                        $valid = 0;
                        exit;
                    }
                }
                //Rule: a teacher cannot have classes in different locations the same day
                if ($valid == 1) {
					$loc_query = "select location_id from room r inner join building b on b.id = r.building_id where r.id = '" . $_POST['room_id'] . "'";
                    $loc_res = mysqli_query($db, $loc_query);
                    $dataLoc = mysqli_fetch_assoc($loc_res);
                    $teachAvail_query = "select location_id from teacher_activity ta inner join room r on r.id = ta.room_id
						inner join building b on b.id = r.building_id where reserved_flag=1 and act_date='" . $_POST['subSessDate'] . "' and teacher_id='" . $_POST['slctTeacher'] . "'";
					if ($act_hidden_id <> "") {
                        $teachAvail_query .= " AND ta.id != " . $act_hidden_id . "";
                    }
                    $q_res = mysqli_query($db, $teachAvail_query);
                    if (mysqli_affected_rows($db) > 0) {
                        $dataAll = mysqli_fetch_assoc($q_res);
                        if ($dataAll['location_id'] != $dataLoc['location_id']) {
                            echo 11;
                            $valid = 0;
                            exit;
                        }
                    }
                }
				if($_POST['check_avail_force_entry']==""){
                //Rule: a teacher can have maximum two saturdays per cycle
                 if ($valid == 1) {
					$day = date('w', strtotime($_POST['subSessDate']));
					$final_day = $day - 1;
					if ($final_day == 5) {
    					$cycle_query = "select id from cycle where program_year_id = '" . $_POST['programId'] . "' and start_week <= '" . $_POST['subSessDate'] . "' and end_week >= '" . $_POST['subSessDate'] . "'";
						$cycle_res = mysqli_query($db, $cycle_query);
						$dataCycle = mysqli_fetch_assoc($cycle_res);

						$teachAvail_query = "select distinct act_date from teacher_activity where reserved_flag=1 and cycle_id='" . $dataCycle['id'] . "' and teacher_id='" . $_POST['slctTeacher'] . "' and act_date != '" . $_POST['subSessDate'] . "'";
						$q_res = mysqli_query($db, $teachAvail_query);
						$count = 0;
						while ($dataAll = mysqli_fetch_assoc($q_res)) {
							$day = date('w', strtotime($dataAll['act_date']));
							$final_day = $day - 1;
							if ($final_day == 5) {
								$count++;
							}
						}
						if ($count >= 2) {
							echo 12;
							$valid = 0;
							exit;
						}
                    }
                  }
				}
                //Rule the sessions scheduled on Saturdays should be from the same academic area.
				if($_POST['check_avail_force_entry']==""){
               	 if ($valid == 1) {
                    $day = date('w', strtotime($_POST['subSessDate']));
                    $final_day = $day - 1;
                    if ($final_day == 5) {
                        $sub_query = "select area_id from subject s where id = '" . $_POST['subjectId'] . "'";
                        $sub_res = mysqli_query($db, $sub_query);
                        $dataSub = mysqli_fetch_assoc($sub_res);
                        $teachAvail_query = "select s.area_id from teacher_activity ta inner join subject s on s.id = ta.subject_id where reserved_flag=1 and act_date='" . $_POST['subSessDate'] . "' and ta.program_year_id = '".$_POST['programId']."' and ta.forced_flag = '0'";
						$q_res = mysqli_query($db, $teachAvail_query);
                        if (mysqli_affected_rows($db) > 0) {
                            $dataAll = mysqli_fetch_assoc($q_res);
                            if ($dataAll['area_id'] != $dataSub['area_id']) {
                                echo 13;
                                $valid = 0;
                                exit;
                            }
                        }
                    }
                  }
				}
                //check if room is free at given time and day
                if ($valid == 1) {
                    //check if the selected date is not added as exception in classroom availability
                    $query = "select id from classroom_availability_exception where room_id = '" . $_POST['room_id'] . "' and exception_date = '" . $_POST['subSessDate'] . "'";
                    $q_res = mysqli_query($db, $query);
                    if (mysqli_affected_rows($db) == 0) {
                        //find the day using date
                        $day = date('w', strtotime($_POST['subSessDate']));
                        $final_day = $day - 1;
                        //check if classroom is available on the given time and day
                        $classroomAvail_query = "select cm.room_id, room.room_name
											from classroom_availability_rule_room_map cm
											inner join classroom_availability_rule_day_map cd on cd.classroom_availability_rule_id = cm.classroom_availability_rule_id
											inner join classroom_availability_rule ca on ca.id = cd.classroom_availability_rule_id
											inner join room on room.id = cm.room_id
											where start_date <= '" . $_POST['subSessDate'] . "' and end_date >= '" . $_POST['subSessDate'] . "' and day= '" . $final_day . "' and cm.room_id='" . $_POST['room_id'] . "' and actual_timeslot_id like '%" . $tsIdsAll . "%'";
                        $q_res = mysqli_query($db, $classroomAvail_query);
                        if (mysqli_affected_rows($db) <= 0) {
                            echo 7;
                            $valid = 0;
                            exit;
                        }
                    } else {
                        echo 7;
                        $valid = 0;
                        exit;
                    }
                    //check if room is not engaged in any reserved activity
                    $roomAvail_query = "select ss.*, ta.room_id, ta.act_date, ta.timeslot_id, ta.teacher_id, ta.reserved_flag from subject_session as ss LEFT JOIN teacher_activity as ta ON ss.id=ta.session_id where ta.reserved_flag=1 and ta.room_id='" . $_POST['room_id'] . "' and ta.timeslot_id REGEXP '" . RexExpFormat($tsIdsAll) . "' and ta.act_date='" . $_POST['subSessDate'] . "'";
                    if ($sess_hidden_id <> "") {
                        $roomAvail_query .= " AND ss.id != " . $sess_hidden_id . "";
                    }
                    if ($act_hidden_id <> "") {
                        $roomAvail_query .= " AND ta.id != " . $act_hidden_id . "";
                    }
                    $q_res = mysqli_query($db, $roomAvail_query);
                    mysqli_affected_rows($db);
                    if (mysqli_affected_rows($db) > 0) {
                        echo 4;
                        $valid = 0;
                        exit;
                    }
                }
            } else {
                echo 8;
            }
        }
        echo $valid;
        break;
		case "UpdateSessionOrderPosition":
		if(isset($_POST['subject_id']) && $_POST['sessionIDSArrValue']!=""){
			$sessionIdsArr=$_POST['sessionIDSArrValue'];
			$sessionID_array=explode(',',$sessionIdsArr);
			$cnt = count($sessionID_array);
			$subjectId=$_POST['subject_id'];
			foreach($sessionID_array as $key=>$val){
			$key=$key+1;
			$del_query="update subject_session set order_number = '".$key."' where id='".$val."' and subject_id='".$subjectId."'";
				$qry = mysqli_query($db, $del_query);
			}
			if(mysqli_affected_rows($db)>0)
				echo 1;
			else
				echo 0;
		}
	    break;
		case "del_rule_teacher":
		if(isset($_POST['rule_id']) && $_POST['rule_id']!=""){
			//check if rule is not being used by any other teacher
			$query = "select id from teacher_availability_rule_teacher_map where teacher_availability_rule_id = '".$_POST['rule_id']."'";
			$q_res = mysqli_query($db, $query);
			if(mysqli_affected_rows($db)<=0)
			{
				//then delete the days associate with rule from table teacher_availability_rule_day_map
				$delRuleDay="delete from teacher_availability_rule_day_map where teacher_availability_rule_id='".$_POST['rule_id']."'";
				$qry = mysqli_query($db, $delRuleDay);
				//delete the rule
				$delRule="delete from teacher_availability_rule where id='".$_POST['rule_id']."'";
				$qry = mysqli_query($db, $delRule);
				echo 1;
			}else{
				echo 0;
			}
		}
    	break;
		case "del_rule_classroom":
		if(isset($_POST['rule_id']) && $_POST['rule_id']!=""){
			//check if rule is not being used by any other teacher
			$query = "select id from classroom_availability_rule_room_map where classroom_availability_rule_id = '".$_POST['rule_id']."'";
			$q_res = mysqli_query($db, $query);
			if(mysqli_affected_rows($db)<=0)
			{
				//then delete the days associate with rule from table teacher_availability_rule_day_map
				$delRuleDay="delete from classroom_availability_rule_day_map where classroom_availability_rule_id='".$_POST['rule_id']."'";
				$qry = mysqli_query($db, $delRuleDay);
				//delete the rule
				$delRule="delete from classroom_availability_rule where id='".$_POST['rule_id']."'";
				$qry = mysqli_query($db, $delRule);
				echo 1;
			}else{
				echo 0;
			}
		}
    	break;
		case "del_loc":
		if(isset($_POST['id'])){
			$id = $_POST['id'];
			$query = "select id from building where location_id = '".$id."'";
			$q_res = mysqli_query($db, $query);
			if(mysqli_affected_rows($db)<=0){
				// then delete the location
				$del_buld_query="delete from location where id='".$id."'";
				$qry = mysqli_query($db, $del_buld_query);
				echo 1;
			}else{
				echo 0;
			}
		}
		break;
		case "getTimeslots":
			if(isset($_POST['time_slot']) && $_POST['time_slot']!="")
			{
				foreach($_POST['time_slot'] as $ts_id)
				{
					$query = "select timeslot_range from timeslot where id = '".$ts_id."'";
					$q_res = mysqli_query($db, $query);
					$data= mysqli_fetch_array($q_res);
					$timeslots[] = $data['timeslot_range'];
				}
				$ts_values = implode(",",$timeslots);
				$ts_id = implode(",",$_POST['time_slot']);
				echo $ts_values."_".$ts_id;die;
			}
			break;
			case "deleteAddProgCycle":
			if(isset($_POST['id'])){
				$id = $_POST['id'];
				$del_ExcepTeachAvail_query="delete from program_cycle_additional_day_time where id='".$id."'";
				$qry = mysqli_query($db, $del_ExcepTeachAvail_query);
				if(mysqli_affected_rows($db)>0)
					echo 1;
				else
					echo 0;
			}
		break;
		case "del_timetable":
		if(isset($_POST['id'])){
			$id = $_POST['id'];
			$objTT = new Timetable();
			if($objTT->deleteData())
				echo 1;
			else
				echo 0;
		}
		break;
		case "getCycleDateRange":
		if(isset($_POST['cycle_id'])){
			$cycle_id = $_POST['cycle_id'];
			$query="select start_week,end_week from cycle where id='".$cycle_id."' ";
			$result = mysqli_query($db, $query);
			$dataAll = mysqli_fetch_assoc($result);
			$dateRange=$dataAll['start_week'].'#'.$dataAll['end_week'];
			echo $dateRange;
		}
		break;
		//Generate timetable
		case 'generateTimetable':			
			if($_POST['txtAName'] != "" && $_POST['fromGenrtTmtbl'] != "" &&  $_POST['toGenrtTmtbl'] != "" && $_POST['programs'] != "")
			{
				$obj = new Timetable();
				$_SESSION['error_msg']='';
				$fromGenrtTmtbl = $_POST['fromGenrtTmtbl'];
				$toGenrtTmtbl = $_POST['toGenrtTmtbl'];
				$name = $_POST['txtAName'];
				$start_date = date('Y-m-d', strtotime($_POST['fromGenrtTmtbl']));
				$end_date = date('Y-m-d', strtotime($_POST['toGenrtTmtbl']));
				$programs = $_POST['programs'];
				$program_list = implode(",",$programs);
				if(!$obj->checkName($_POST['txtAName']))
				{
					$from_time = date('Y', strtotime($_POST['fromGenrtTmtbl']));
					$output_array = $obj->generateTimetable($start_date, $end_date,$programs);
					if(isset($output_array['program_not_found'])){
						$_SESSION['error_msg'] = $output_array['program_not_found'];
					}elseif(isset($output_array['teacher_not_found'])){
						$_SESSION['error_msg'] = $output_array['teacher_not_found'];
					}elseif(isset($output_array['timeslot_not_found'])){
						$_SESSION['error_msg'] = $output_array['timeslot_not_found'];
					}elseif(isset($output_array['system_error'])){
						$_SESSION['error_msg'] = $output_array['system_error'];
					}
					if(isset($_SESSION['error_msg']) && $_SESSION['error_msg']!='')
					{
					    echo "Session-Error";
						exit;
					}
					if($output_array)
					{
						$obj->deleteData();
						$res = $obj->addTimetable($_POST['txtAName'], $start_date, $end_date,$program_list);
						if($res)
						{
							foreach($output_array as $key=>$value)
							{
								foreach($value as $newkey=>$val)
								{
									foreach($val as $k=>$v)
									{
										$timeslot = $k;
										$tt_id = $res;
										$activity_id = $v['activity_id'];
										$program_year_id = $v['program_year_id'];
										$area_id = $v['area_id'];
										$teacher_id = $v['teacher_id'];
										$room_id = $v['room_id'];
										$session_id = $v['session_id'];
										$room_name = $v['room_name'];
										$name = $v['name'];
										$program_name = $v['program_name'];
										$subject_name = $v['subject_name'];
										$session_name = $v['session_name'];
										$teacher_name = $v['teacher_name'];
										$teacher_type = $v['teacher_type'];
										$cycle_id = $v['cycle_id'];
										$subject_id = $v['subject_id'];
										$description = $program_name."-".$subject_name."-".$session_name."-".$teacher_name;
										$date = $v['date'];
										$date_add = date("Y-m-d H:i:s");
										$date_upd = date("Y-m-d H:i:s");

										$resp = $obj->addTimetableDetail($timeslot, $tt_id, $activity_id, $program_year_id, $teacher_id, $room_id, $session_id, $subject_id, $date, $date_add, $date_upd,$cycle_id);
										if($resp)
										{
											$ts_array = explode("-", $timeslot);
											$entry_time = date("H:i",strtotime(trim($ts_array['0'])));
											$st = strtotime($entry_time);
											$et = strtotime(date("H:i",strtotime(trim($ts_array['1']))));
											$duration = abs($et - $st) / 60;
											$entry_array = explode(":", $entry_time);
											$entry_hour = $entry_array['0'];
											$entry_minute = $entry_array['1'];

											$date_array = explode("-", $date);
											$year = $date_array['0'];
											$month = $date_array['1'];
											$day = $date_array['2'];
											//$zone=3600*+5;//India
											date_default_timezone_set("America/New_York");
											$eventstart = mktime ( $entry_hour, $entry_minute, 0, $month, $day, $year );
											$eventstartdate = gmdate ( 'Ymd', $eventstart );
											$cal_time = gmdate('His', $eventstart);
											$cal_id = $obj->addWebCalEntry($eventstartdate, $cal_time, $name, $room_name, $description,$duration, $teacher_id, $subject_id, $room_id, $program_year_id,$cycle_id,$area_id,$teacher_type);
											if($cal_id){
												$obj->addWebCalEntryUser($cal_id);

											}
										}
									}
								}
							}
							echo 1;
						}
					}
				}else{
					$message="Timetable with this name already exist in database. Please choose a new one.";
					$_SESSION['error_msg'] = $message;
					echo "Name-Exist";
			    }
			}else{
				$message="Please enter all required fields";
				$_SESSION['error_msg'] = $message;
				echo "Enter-Rquired-Feild";
			}
			break;
}
?>