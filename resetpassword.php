<?php
include("connection.php");
$response = array();
if($_POST['oldpassword'] == $_POST['currentpassword']) {
	if($sql = mysqli_query($conn,"UPDATE user_records set userpassword='".$_POST['newpassword']."' where id='".$_POST["id"]."'")) {
		$response['success'] = "Password changed successfully";
	}
} else {
	$response['error'] = " Invalid current password";
}
echo json_encode($response);
?>