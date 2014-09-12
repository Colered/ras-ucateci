<?php
include('header.php');
$objT = new Teacher();
?>
<script src="js/jquery.dataTables.js" type="text/javascript"></script>
<script type="text/javascript" charset="utf-8">
$(document).ready(function(){
	$('#datatables').dataTable({
		"sPaginationType":"full_numbers",
		"aaSorting":[[0, "asc"]],
		"bJQueryUI":true
	});
})

</script>
<style type="text/css">
	@import "css/demo_table_jui.css";
	@import "css/jquery-ui-1.8.4.custom.css";
</style>
<div id="content">
    <div id="main">
    <?php if(isset($_SESSION['succ_msg'])){ echo '<div class="full_w green center">'.$_SESSION['succ_msg'].'</div>'; $_SESSION['succ_msg']="";} ?>
        <div class="full_w">
            <div class="h_title">Teacher Activity View<a href="teacher_activity.php" class="gird-addnew" title="Add New Activity">Add new</a></div>
            <table id="datatables" class="display">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Activity</th>
                        <th>Program</th>
                        <th>Subject</th>
                        <th>Session</th>
                        <th>Teacher</th>
                        <th>Room</th>
                        <th>Timeslot</th>
                        <th>Reserved</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
				<?php
					$result = $objT->getTeachersAct();
					if($result->num_rows){
						while($row = $result->fetch_assoc()){
					?>
						<tr>
							<td class="align-center"><?php echo $row['id'];?></td>
							<td><?php echo $row['name'];?></td>
							<td><?php echo $row['program_name'];?></td>
							<td><?php echo $row['subject_name'];?></td>
							<td><?php echo $row['session_name'];?></td>
							<td><?php echo $row['teacher_name'].'('.$row['email'].')';?></td>
							<td><?php echo $objT->getFielldVal("room","room_name",'id',$row['room_id']);?></td>
							<td><?php echo $objT->getFielldVal("timeslot","timeslot_range",'id',$row['timeslot_id']);?></td>
							<td><?php echo ($row['reserved_flag']==1) ? "Reserved" : "Free";?></td>
							<td class="align-center" id="<?php echo $row['id'];?>">
								<a href="edit_teacher_activity.php?edit=<?php echo base64_encode($row['id']);?>&pyid=<?php echo base64_encode($row['program_year_id']);?>&sid=<?php echo base64_encode($row['subject_id']);?>&sessId=<?php echo base64_encode($row['session_id']);?>&tid=<?php echo base64_encode($row['teacher_id']);?>" class="table-icon edit" title="Edit"></a>
								<a href="#" class="table-icon delete" onClick="deleteTeacherActivity('<?php echo $row['id'] ?>')"></a>
							</td>
						</tr>
					<?php } ?>
				<?php } ?>
				</tbody>
            </table>
			<?php if(isset($_SESSION['error_msg'])){ ?>
					<div><span class="red"><?php echo $_SESSION['error_msg']; $_SESSION['error_msg']=""; ?></span></div>
			<?php } ?>
        </div>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
</div>
<?php include('footer.php'); ?>

