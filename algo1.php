<?php
$link = mysql_connect('172.16.220.164', 'root', 'cidot@123');
if($link) {
   mysql_select_db('cidot_ras' ,$link);
}
$reserved_activities = array();
$each_day_activities = array(
"0" => [],
"1" => [],
"2" => [],
"3" => [],
"4" => [],
"5" => [], 
);
$days = array('0','1','2','3','4','5');
$counter = array();
$cnt=0;
$daycnt=0;
$sql_pgm = mysql_query("select program_name from program");
while($result_pgm = mysql_fetch_array($sql_pgm)){
	$sql_slot = mysql_query("select timeslot_range from timeslot");
	while($result_slot = mysql_fetch_array($sql_slot)){
		shuffle($days);
		//print"<pre>";print_r($days);
		foreach($days as $shuffled_days)
		{			
			$i = 0;
			$reserved_array = array();
			$reserved_groups = array();
			$teachers = search_teachers($result_slot['timeslot_range'],$shuffled_days);
			//print"<pre>";print_r($teachers);die;
			foreach($teachers as $teacher)
			{	
				$sql_act = mysql_query("select name,program_id,subject_id,group_id from teacher_activity where teacher_id = ".$teacher." ORDER BY RAND()");
				while($result_act = mysql_fetch_array($sql_act))
				{						
					//print"<pre>";print_r($result_act);
					$rooms = search_room($result_slot['timeslot_range'],$shuffled_days);
					$subject_cnt = get_subject_count($result_act['subject_id'], $result_act['group_id']);
					if(array_key_exists($result_act['subject_id'], $counter) && array_key_exists($result_act['group_id'], $counter[$result_act['subject_id']]))
					{
						$count = $counter[$result_act['subject_id']][$result_act['group_id']];
					}else{
						$count = 0;
					}
					
					//print"<pre>";print_r($reserved_array);
					if(!search_array($result_act['group_id'],$reserved_groups) &&
					   !search_array($result_act['name'],$each_day_activities[$shuffled_days]) &&
					(!search_array($result_act['name'],$reserved_activities) || $count <$subject_cnt['count']))
					{						
						
							$reserved_array[$result_slot['timeslot_range']][$shuffled_days][$i]['name'] = $result_act['name'];
							$reserved_activities[$result_slot['timeslot_range']][++$cnt] = $result_act['name'];
							//$each_day_activities[$shuffled_days][++$daycnt] = $result_act['name'];
							$reserved_array[$result_slot['timeslot_range']][$shuffled_days][$i]['teacher_id'] = $teacher;
							$reserved_array[$result_slot['timeslot_range']][$shuffled_days][$i]['program_id'] = $result_act['program_id'];
							$reserved_array[$result_slot['timeslot_range']][$shuffled_days][$i]['subject_id'] = $result_act['subject_id'];							
							$reserved_array[$result_slot['timeslot_range']][$shuffled_days][$i]['group_id'] = $result_act['group_id'];
							
							if(array_key_exists($result_act['subject_id'], $counter))
							{
								
								if(array_key_exists($result_act['group_id'], $counter[$result_act['subject_id']]))
								{
									 $counter[$result_act['subject_id']][$result_act['group_id']]=$counter[$result_act['subject_id']][$result_act['group_id']]+1;
								}else{
									$counter[$result_act['subject_id']][$result_act['group_id']] = 1;
								}
							}
							else{
								$counter[$result_act['subject_id']][$result_act['group_id']] = 1;
							}
							if(array_key_exists($shuffled_days, $each_day_activities))
							{
								$each_day_activities[$shuffled_days][++$daycnt] = $result_act['name'];
							}else{
								$each_day_activities[$shuffled_days][++$daycnt] = $result_act['name'];
							}
							$reserved_groups[$result_slot['timeslot_range']][$i] = $result_act['group_id'];
							$reserved_array[$result_slot['timeslot_range']][$shuffled_days][$i]['room_id'] = $rooms[$i];
							$i++;							
							break;						
							
					}
				}			
			}
			print"<pre>";print_r($reserved_array);
			//print"<pre>";print_r($reserved_activities);
			//print"<pre>";print_r($each_day_activities);
			
		}

	}
}

function search_array($needle, $haystack) {
     if(in_array($needle, $haystack)) {
          return true;
     }
     foreach($haystack as $element) {
          if(is_array($element) && search_array($needle, $element))
               return true;
     }
   return false;
}
function search_room($slot,$shuffled_days)
{
	$sql_room = mysql_query("select room_id 
							from classroom_availability
							inner join classroom_availability_days on classroom_availability_days.classroom_availability_id = classroom_availability.id
							where timeslots like '%".$slot."%' and days=".$shuffled_days);
	while($result_room = mysql_fetch_array($sql_room))
	{
		$rooms[] = $result_room['room_id'];
	}
	return $rooms;
}
function search_teachers($slot,$shuffled_days)
{
	
	$sql_teachers = mysql_query("select teacher_id 
							from teacher_availability
							inner join teacher_availability_days on teacher_availability_days.teacher_availability_id = teacher_availability.id
							where timeslots like '%".$slot."%' and day=".$shuffled_days);
	while($result_teachers = mysql_fetch_array($sql_teachers))
	{
		$teachers[] = $result_teachers['teacher_id'];
	}
	return $teachers;
}
function get_subject_count($subject_id, $grp_id){
	$sql_subj_cnt = mysql_query("select count 
							from subject_weekly_count
							where subject_id = ".$subject_id." and group_id = ".$grp_id);
	$subject_cnt = mysql_fetch_array($sql_subj_cnt);
	return $subject_cnt;
}

?>