<?php 

require_once 'dbconfig.php';
if(isset($_GET['delete_id']))
{
	$stmt_select=$DB_con->prepare('SELECT userPic FROM tbl_users WHERE id=:uid');
	$stmt_select->execute(array(':uid'=>$_GET['delete_id']));
	$imgRow=$stmt_select->fetch(PDO::FETCH_ASSOC);
	unlink("user_images/".$imgRow['userPic']);

	$stmt_delete=$DB_con->prepare('DELETE FROM tbl_users WHERE id=:uid');
	$stmt_delete->bindParam(':uid',$_GET['delete_id']);
	$stmt_delete->execute();

	header('Location:index.php');

}


?>

<!DOCTYPE html>
<html>
<head>
	<title>All Employees Information</title>
</head>
<body>
	<div>
		
		<div>
			<h1>All employees.</h1><a href="addnew.php">Add New Employee</a>
		</div>

		<br>
		<div>
			<?php 

			$stmt=$DB_con->prepare('SELECT id,userName,userProfession,userPic from tbl_users ORDER BY id DESC');

			$stmt->execute();

			if($stmt->rowCount()>0)
			{
				while($row=$stmt->fetch(PDO::FETCH_ASSOC))
				{
					extract($row); //This function uses array keys as variable names and values as variable values.

					?>

					<div>
						<p><?php echo $userName. "&nbsp;/&nbsp;".$userProfession ?></p>
						<img src="user_images/<?php echo $row['userPic']; ?>" width="150px" height="150px">
						<p>
							
							<span>
								<a href="editform.php?edit_id=<?php echo $row['id']; ?>" onclick="return confirm('Sure to Edit?')">Edit</a>

								<a href="?delete_id=<?php echo $row['id']; ?>" onclick="return confirm('Sure to Delete?')">Delete</a>
							</span>
						</p>
					</div>

					<?php
				}
			}

			else
			{

			?>

			<div>
				No Data Found...
			</div>
			<?php 
		}

		?>
		</div>
		</div>

	</div>

</body>
</html>