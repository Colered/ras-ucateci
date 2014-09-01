<?php
require_once('config.php');
if (isset($_POST['form_action']) && $_POST['form_action']!=""){
	$formPost = $_POST['form_action'];
	switch ($formPost) {
		case 'Login':
			//conditions for user login
			if($_POST['txtUName']!="" && $_POST['txtPwd']!="" ){
				$obj = new Users();
				$resp = $obj->userLogin();
				$location = ($resp == 1) ? "timetable_dashboard.php" : "index.php";
				header('Location: '.$location);
			}else{
				$message="Please enter username and password";
				$_SESSION['error_msg'] = $message;
				header('Location: index.php');
			}
		break;
		case 'addEditArea':
			//adding new areas
			if($_POST['txtAreaName']!="" && $_POST['txtAreaCode']!="" ){
				$obj = new Areas();
				if(isset($_POST['areaId']) && $_POST['areaId']!=''){
					//update area
					$resp = $obj->updateArea();
				}else{
					//add new area
					$resp = $obj->addArea();
				}
				if($resp==0){
					//return back data to the form
					echo "<html><head></head><body>";
					echo "<form name='formarea' method='post' action='areas.php'>";
					reset($_POST);
					while(list($iname,$ival) = each($_POST)) {
						echo "<input type='hidden' name='$iname' value='$ival'>";
					}
					echo "</form>";
					echo "</body></html>";
					echo"<script language='JavaScript'>function submit_back(){ window.document.formarea.submit();}submit_back();</script>";
					exit();
					//end return back
				}else{
					header('Location: areas_view.php');
					exit();
				}
			}else{
				$message="Please enter all required fields";
				$_SESSION['error_msg'] = $message;
				header('Location: areas.php');
			}
		break;
		case 'addEditBuild':
			if($_POST['txtBname']!="" ){
				$obj = new Buildings();
				if(isset($_POST['buldId']) && $_POST['buldId']!=''){
					//update new building
					$resp = $obj->updateBuld();
				}else{
					//add new building
					$resp = $obj->addBuilding();
				}
				if($resp==0){
					//return back data to the form
					echo "<html><head></head><body>";
					echo "<form name='formbuild' method='post' action='buildings.php'>";
					reset($_POST);
					while(list($iname,$ival) = each($_POST)) {
						echo "<input type='hidden' name='$iname' value='$ival'>";
					}
					echo "</form>";
					echo "</body></html>";
					echo"<script language='JavaScript'>function submit_back(){ window.document.formbuild.submit();}submit_back();</script>";
					exit();
					//end return back
				}else{
					header('Location: buildings_view.php');
					exit();
				}
			}else{
				$message="Please enter all required fields";
				$_SESSION['error_msg'] = $message;
				header('Location: buildings.php');
			}
		break;
		case "add_edit_professor":
			//add and edit professor
			$obj = new Teacher();
			if(isset($_POST['form_edit_id']) && $_POST['form_edit_id']!=''){
			    $resp = $obj->editProfessor();
				header('Location: teacher_view.php');
				exit();
			}else{
				$resp = $obj->addProfessor();
				if($resp==0){
					//return back data to the form
					echo "<html><head></head><body>";
					echo "<form name='form55' method='post' action='professor.php'>";
					reset($_POST);
					while(list($iname,$ival) = each($_POST)) {
						echo "<input type='hidden' name='$iname' value='$ival'>";
					}
					echo "</form>";
					echo "</body></html>";
					echo"<script language='JavaScript'>function submit_back(){ window.document.form55.submit();}submit_back();</script>";
					exit();
				//end return back
				}else{
					header('Location: teacher_view.php');
					exit();
				}
			}
		break;
<<<<<<< HEAD
		case 'Subject':
			//adding new subjects
			if((isset($_POST['txtSubjName']) && $_POST['txtSubjName']!="") && (isset($_POST['txtSubjName']) && $_POST['txtSubjCode']!="")){
=======
		case 'addEditSubject':
		//adding new subject
			if($_POST['txtSubjName']!="" && $_POST['txtSubjCode']!="" ){
				$obj = new Subjects();
				if(isset($_POST['subjectId']) && $_POST['subjectId']!=''){
					//update subject
					$resp = $obj->updateSubject();
				}else{
					//add new subject
					$resp = $obj->addSubject();
				}
				if($resp==0){
					//return back data to the form
					echo "<html><head></head><body>";
					echo "<form name='formsubject' method='post' action='subjects.php'>";
					reset($_POST);
					while(list($iname,$ival) = each($_REQUEST)) {
						echo "<input type='hidden' name='$iname' value='$ival'>";
					}
					echo "</form>";
					echo "</body></html>";
					echo"<script language='JavaScript'>function submit_back(){ window.document.formsubject.submit();}submit_back();</script>";
					exit();
					//end return back
				}else{
					header('Location: subject_view.php');
					exit();
				}
			}else{
				$message="Please enter all required fields";
				$_SESSION['error_msg'] = $message;
				header('Location: subjects.php');
			}
			/*//adding new subjects
			if($_POST['txtSubjName']!="" && $_POST['txtSubjCode']!="" ){
>>>>>>> changes in subject management module
				$obj = new Subjects();
				$resp = $obj->addSubject();
				$location = ($resp == 1) ? "subject_view.php" : "subjects.php";
				header('Location: '.$location);
			}else{
				$message="Please enter all required fields";
				$_SESSION['error_msg'] = $message;
				header('Location: subjects.php');
			}
<<<<<<< HEAD
		break;
		case "add_program":
			//adding new areas
			if(trim($_POST['txtPrgmName'])!=""){
				$obj = new Programs();
				$resp = $obj->addProgram();

				if($resp==0){
					//return back data to the form
					echo "<html><head></head><body>";
					echo "<form name='form55' method='post' action='programs.php'>";
					reset($_POST);
					while(list($iname,$ival) = each($_POST)) {
					    if($iname=='slctDays1' || $iname=='slctDays2' || $iname=='slctDays2'){
					        foreach($_POST[$iname] as $value){
							  echo '<input type="hidden" name="'.$iname.'[]" value="'. $value. '">';
							}
					    }else{
							echo "<input type='hidden' name='$iname' value='$ival'>";
						}
					}
					echo "</form>";
					echo "</body></html>";
					echo"<script language='JavaScript'>function submit_back(){ window.document.form55.submit();}submit_back();</script>";
					exit();
				//end return back
				}else{
					header('Location: programs_view.php');
					exit();
				}

			}else{
				$message="Please fill all required fields";
				$_SESSION['error_msg'] = $message;
				header('Location: programs.php');
			}
		break;
		case "edit_program":
			//adding new areas
			if(isset($_POST['programId']) && $_POST['programId']<>''){
				$obj = new Programs();
				$resp = $obj->editProgram();
				if($resp==0){
					header('Location: programs.php?edit='.$_POST['programId']);
					exit();
				}else{
					header('Location: programs_view.php');
					exit();
				}
			}else{
				$message="Please fill all required fields";
				$_SESSION['error_msg'] = $message;
				header('Location: programs.php');
			}
		break;
		case 'addEditClassroom':
			//adding new areas
			if(isset($_POST['txtRmName']) && $_POST['txtRmName']!="" ){
				$obj = new Classroom();
				if(isset($_POST['roomId']) && $_POST['roomId']!=''){
					//update area
					$resp = $obj->updateRoom();
				}else{
					//add new area
					$resp = $obj->addRoom();
				}
				if($resp==0){
					//return back data to the form
					echo "<html><head></head><body>";
					echo "<form name='formroom' method='post' action='areas.php'>";
					reset($_POST);
					while(list($iname,$ival) = each($_POST)) {
						echo "<input type='hidden' name='$iname' value='$ival'>";
					}
					echo "</form>";
					echo "</body></html>";
					echo"<script language='JavaScript'>function submit_back(){ window.document.formroom.submit();}submit_back();</script>";
					exit();
					//end return back
				}else{
					header('Location: rooms_view.php');
					exit();
				}
			}else{
				$message="Please enter all required fields";
				$_SESSION['error_msg'] = $message;
				header('Location: rooms.php');
			}
		break;
=======
		break;*/
>>>>>>> changes in subject management module
	}
}
?>