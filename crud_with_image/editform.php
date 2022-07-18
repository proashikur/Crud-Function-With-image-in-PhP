<?php 
error_reporting(~E_NOTICE);

require_once 'dbconfig.php';

if(isset($_GET['edit_id'])&& !empty($_GET['edit_id']))
{
	$id=$_GET['edit_id'];
	$stmt_edit=$DB_con->prepare('SELECT userName,userProfession,userPic FROM tbl_users WHERE id=:uid');
	$stmt_edit->execute(array(':uid'=>$id));

	$edit_row=$stmt_edit->fetch(PDO::FETCH_ASSOC);
	extract($edit_row);
}
else
{
	header('Location:index.php');
}

if(isset($_POST['btn_save_update']))
{
	$username=$_POST['user_name'];
	$userjob=$_POST['user_job'];

	$imgFile=$_FILES['user_image']['name'];
	$tmp_dir=$_FILES['user_image']['tmp_name'];
	$imgSize=$_FILES['user_image']['size'];

	if($imgFile)
	{
		$upload_dir='user_images/'; //upload directory
		$imgExt=strtolower(pathinfo($imgFile,PATHINFO_EXTENSION));
		$valid_extentions=array('jpeg','jpg','png','gif');
		$userpic=rand(1000,100000000).".".$imgExt;

		if(in_array($imgExt,$valid_extentions))
		{
			if($imgSize < 5000000)
			{
				unlink("user_images/".$imgRow['userPic']);
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

	else
	{
		$userpic=$edit_row['userPic'];
	}

	if(!isset($errorMSG))
	{
		$stmt=$DB_con->prepare('UPDATE tbl_users SET userName=:uname,userProfession=:ujob,userPic=:upic WHERE id=:uid');

			$stmt->bindParam(':uname',$username);
			$stmt->bindParam(':ujob',$userjob);
			$stmt->bindParam(':upic',$userpic);
			$stmt->bindParam(':uid',$id);

			if($stmt->execute())
			{

				?>

			<script>
				
				alert('Successfully Updated...');
				window.location.href='index.php';
			</script>

			<?php 
		}

		else
		{
			$errorMSG="Sorry Data Could Not Be Updated!";
		}

	}
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Edit Employee Information</title>
</head>
<body>
	<div>
		<div>
			<h1>Update Profile...</h1><a href="index.php">All Employees</a>
		</div>

		<form method="POST" enctype="multipart/form-data">

			<?php 

				if(isset($errorMSG))
				{
			?>
		<div>
			<span></span>&nbsp;<?php echo $errorMSG; ?>
		</div>
		<?php
	}
	?>

	<table>
		
		<tr>
			<td>
				<label>Username</label>
			</td>
			<td>
				<input type="text" name="user_name" value="<?php echo $userName; ?>">
			</td>
			</tr>

			<tr>
				<td>
					<label>Profession</label>
				</td>
				<td>
					<input type="text" name="user_job" value="<?php echo $userProfession;?>">
				</td>
			</tr>

			<tr>
				<td>
					<label>Profile Picture</label>
				</td>
				<td>
					<p>
						<img src="user_images/<?php echo $userPic; ?>" height="150" width="150">
					</p>
					<input type="file" name="user_image" accept="image/*">
				</td>
			</tr>

			<tr>
				<td colspan="2">
					<button type="submit" name="btn_save_update"><span></span>Update</button>

					<a href="index.php">Cancel</a>
				</td>
			</tr>
	</table>
			
		</form>
	</div>

</body>
</html>