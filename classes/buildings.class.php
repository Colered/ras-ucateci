<?php
class Buildings extends Base {
    public function __construct(){
   		 parent::__construct();
   	}
	/*function for adding Building*/
	public function addBuilding() {
			if(Base::cleanText($_POST['txtBname']) !=""){
				//check if the building name exists
				$area_query="select id, building_name from building where building_name='".Base::cleanText($_POST['txtBname'])."'";
				$q_res = mysqli_query($this->conn, $area_query);
				$dataAll = mysqli_fetch_assoc($q_res);
				if(count($dataAll)>0)
				{
					$message="Building Name already exists.";
					$_SESSION['error_msg'] = $message;
					return 0;
				}else{
					//add the new building
					$currentDateTime = date("Y-m-d H:i:s");
					//update all the value of previously defualt selected building
					if(isset($_POST['is_default']) && $_POST['is_default']=='1'){
						$result2 = mysqli_query($this->conn, "update building set `is_default`='0' where `is_default`='1';");
					}
					//add the new value
					if ($result = mysqli_query($this->conn, "INSERT INTO building VALUES ('', '".Base::cleanText($_POST['txtBname'])."','".Base::cleanText($_POST['txtBlocation'])."', '".$_POST['is_default']."', '".$currentDateTime."', '".$currentDateTime."');")) {
						$message="New building has been added successfully";
						$_SESSION['succ_msg'] = $message;
						return 1;
					}else{
						$message="Cannot add the building";
						$_SESSION['error_msg'] = $message;
						return 0;
					}
				}
			}else{
					$message="Please enter a valid Building name";
					$_SESSION['error_msg'] = $message;
					return 0;
			}
	}
	/*function for listing Area*/
	public function viewBuld() {
			$area_query="select bd.*, lc.id as locationId, lc.name from building as bd LEFT JOIN location as lc ON bd.location_id = lc.id order by bd.is_default DESC";
			$q_res = mysqli_query($this->conn, $area_query);
			return $q_res;
	}

	/*function for fetch data using building ID*/
	public function getDataByBuldID($id) {
			$area_query="select bd.*, lc.id as locationId, lc.name from building as bd LEFT JOIN location as lc ON bd.location_id = lc.id where bd.id='".$id."' limit 1";
			$q_res = mysqli_query($this->conn, $area_query);
			if(mysqli_num_rows($q_res)<=0)
				return 0;
			else
				return $q_res;
	}
	/*function for Update Building*/
	public function updateBuld() {
			//check if the building name exists
			$area_query="select id, building_name from building where building_name='".Base::cleanText($_POST['txtBname'])."' and id !='".$_POST['buldId']."'";
			$q_res = mysqli_query($this->conn, $area_query);
			$dataAll = mysqli_fetch_assoc($q_res);
			if(isset($_POST['is_default']) && $_POST['is_default']=='1'){
						$result2 = mysqli_query($this->conn, "update building set `is_default`='0' where `is_default`='1';");
			}
			if(count($dataAll)>0) {
				$message="Building Name already exists.";
				$_SESSION['error_msg'] = $message;
				header('Location: buildings.php?edit='.base64_encode($_POST['buldId']));
				return 0;
			}elseif ($result = mysqli_query($this->conn, "Update building set building_name = '".Base::cleanText($_POST['txtBname'])."',location_id = '".Base::cleanText($_POST['txtBlocation'])."', is_default = '".$_POST['is_default']."', date_update = '".date("Y-m-d H:i:s")."' where id='".$_POST['buldId']."'")) {
				$message="Building has been updated successfully";
				$_SESSION['succ_msg'] = $message;
				return 1;
			}else{
				$message="Cannot update the Building";
				$_SESSION['error_msg'] = $message;
				header('Location: buildings.php?edit='.base64_encode($_POST['buldId']));
				return 0;
			}
    }
    //function to get rooms
    public function getRoomsDropDwn($room_id='')
    {
		$room_dropDwn = '';
		$slqR="SELECT r.id, r.room_name, rt.room_type, b.building_name FROM room r
				LEFT JOIN room_type rt ON ( rt.id = r.room_type_id )
				LEFT JOIN building b ON ( b.id = r.building_id )";
		if($room_id){
			$slqR .= " WHERE r.id='".$room_id."'";
		}
		$slqR .= " ORDER BY room_name,room_type,building_name";
		$relR = mysqli_query($this->conn, $slqR);
		while($rdata= mysqli_fetch_array($relR)){
			$room_dropDwn .= '<option value="'.$rdata['id'].'">'.$rdata['room_name'].'-'.$rdata['building_name'].'-'.$rdata['room_type'].'</option>';
		}
		return $room_dropDwn;
    }
	//function to get full room name by id
	public function getRoomFullName($rid)
	{
	  if($rid){
		$slqR="SELECT r.id, r.room_name, rt.room_type, b.building_name FROM room r
				LEFT JOIN room_type rt ON ( rt.id = r.room_type_id )
				LEFT JOIN building b ON ( b.id = r.building_id ) WHERE r.id='".$rid."'";
		$relR = mysqli_query($this->conn, $slqR);
		$rdata= mysqli_fetch_array($relR);
		return $rdata['room_name'].'-'.$rdata['building_name'].'-'.$rdata['room_type'];
	  }else{
	    return '';
	  }
	}
	/*function for listing Locations*/
	public function viewLocation() {
			$location_query="select * from location";
			$q_res = mysqli_query($this->conn, $location_query);
			return $q_res;
	}
}
