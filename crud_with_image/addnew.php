<?php 
error_reporting(~E_NOTICE);
require_once 'dbconfig.php';

if(isset($_POST['btnsave']))
{
	$username=$_POST['user_name'];
	$userjob=$_POST['user_job'];

	$imgFile=$_FILES['user_img']['name'];
	$tmp_dir=$_FILES['user_img']['tmp_name'];
	$imgSize=$_FILES['user_img']['size'];

	if(empty($username))
	{
		$errorMSG="Please enter username";
	}

	else if(empty($userjob))
	{
		$errorMSG="Plaese enter employees job";
	}

	else if(empty($imgFile))
	{
		$errorMSG="Please select employees image";
	}

	else
	{
		$upload_dir='user_images/'; //upload directory
		$imgExt=strtolower(pathinfo($imgFile,PATHINFO_EXTENSION));
		$valid_extentions=array('jpeg','jpg','png','gif');
		$userpic=rand(1000,100000000).".".$imgExt;

		if(in_array($imgExt,$valid_extentions))
		{
			if($imgSize < 5000000)
			{
				move_uploaded_file($tmp_dir,$upload_dir.$userpic);
			}

			else
			{
				$errorMSG="Sorry, your file is too large";
			}
		}

			else
			{
				$errorMSG="Sorry, only jpeg, jpg,png,gif files are allowed";
			}
		}

		if(!isset($errorMSG))
		{
			$stmt=$DB_con->prepare("INSERT INTO tbl_users(userName,userProfession,userPic) VALUES(:uname,:ujob,:upic)");

			$stmt->bindParam(':uname',$username);
			$stmt->bindParam(':ujob',$userjob);
			$stmt->bindParam(':upic',$userpic);

			if($stmt->execute())
			{
				$successMSG="New Record Inserted Successfuly";
				header('refresh:5; index.php');
			}

			else
			{
				$errorMSG="Error while inserting...";
			}

		}



	}



?>



<!DOCTYPE html>
<html>
<head>
	<title>Add New Employee Records</title>
</head>
<body>

	<div>
		
		<div>
			<h1>Add New Employee</h1> <a href="index.php"><br><b>View All Employee</b></a>
		</div>

		<?php 

		error_reporting(~E_NOTICE);

		if(isset($errorMSG))
		{

			?>

		<div>
			<span></span><b><?php echo $errorMSG; ?></b>
	
	</div>
		<?php
			}
			

		else if(isset($successMSG))
		{

		?>
		<div>
			<b><span></span><?php echo $successMSG; ?></b>
		</div>
		<?php 
	}

	?>

	<form method="post" enctype="multipart/form-data">

		<table>
			
			<tr>
				<td>
					<label>Username</label>
				</td>
				<td>
					<input type="text" name="user_name" value="<?php echo $username;?>">
				</td>
			</tr>

			<tr>
				<td>
					<label>Profession</label>
					</td>
					<td>
						<input type="text" name="user_job" value="<?php echo $userjob; ?>">
					</td>
			</tr>

			<tr>
				<td><label>Profile Image</label></td>
				<td>
					<input type="file" name="user_img" accept="image/*">
				</td>
			</tr>

			<tr>
				<td colspan="2">
					<button type="submit" name="btnsave">
					<span></span>&nbsp;save
					</button>
				</td>
			</tr>
		</table>
		


	</form>
	</div>

</body>
</html>