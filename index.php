<!-- //grant_admin controller -->

<?php require_once "connect.php";
$id	= $_GET['id'];
$update_user_query = "SELECT role FROM users WHERE id = $id;";
$query_result = mysqli_query($conn, $update_user_query);
$user_role = mysqli_fetch_assoc($query_result);

if ($user_role['role']==2) {
	$update_role_query = "UPDATE users SET role =1 WHERE id = $id";
}else{
	$update_role_query = "UPDATE users SET role =2 WHERE id = $id";
}

mysqli_query($conn, $update_role_query);
header("Location: ../views/users.php?p=1");
 ?>


<!-- views user -->

<?php require_once "../partials/template.php"; ?>
<?php function get_page_content() { ?>
<?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] == 1){ ?>
<?php global $conn; ?>

		<div class="container-fluid" id="users-page-container">
			<div class="row">
				<div class="offset-lg-2 col-lg-8">
					<h1 class="text-center my-1"><img src="../assets/images/search.png" style="width: 80px; height: auto;">View Users</h1>
				</div>
			</div>
			<div class="table-responsive">
				<table class="table table-striped table-bordered" id="view_users">
					<thead class="thead-dark">
						<tr>
							<th class="text-center">Username</th>
							<th class="text-center">Firstname</th>
							<th class="text-center">Lastname</th>
							<th class="text-center">Email</th>
							<th class="text-center">Role</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$rowsperpage = 10;
							$page = $_GET['p'];
							$page = $page - 1;

							$p = $page * $rowsperpage; 

							$get_users_query = "SELECT u.id, u.username, u.firstname, u.lastname, u.email, r.name AS role FROM users u JOIN roles r ON (u.role = r.id) ;";

							$page_query = "SELECT u.id, u.username, u.firstname, u.lastname, u.email, r.name AS role FROM users u JOIN roles r ON (u.role = r.id) LIMIT ".$p.", ".$rowsperpage.";";


							$user_list = mysqli_query($conn, $get_users_query);
							$rowsperpage_list =  mysqli_query($conn, $page_query);

							$count = mysqli_num_rows($user_list); ?>


							<div class="justify-content-right">
								<nav aria-label="Page navigation example">
									<ul class="pagination">
							<?php  
								if ($_GET['p'] > 1) {
								$prev_page = $_GET['p']-1;
								echo "<li class='page-item'><a href='users.php?p=$prev_page' class='page-link'>back</a></li>" ;
							
							}

							$limit = ceil($count/$rowsperpage);
							for ($i=1; $i<=$limit  ; $i++) { 
								echo "<li class='page-item'><a href='users.php?p=$i' class='page-link'> $i </a></li>";
							}

							$check = $p + $rowsperpage;
							if ($count > $check) {
								$next_page = $_GET['p']+1;
								echo "<li class='page-item'><a href='users.php?p=$next_page' class='page-link'>next</a></li>" ;
							}

							foreach ($rowsperpage_list as $indiv_user) {

							?>
									</ul>
								</nav>
							</div>

						
						<tr>
							<td><?php echo $indiv_user['username']; ?></td>
							<td><?php echo $indiv_user['firstname']; ?></td>
							<td><?php echo $indiv_user['lastname']; ?></td>
							<td><?php echo $indiv_user['email']; ?></td>
							<td><?php echo $indiv_user['role']; ?></td>
							<td>
							<?php $id = $indiv_user['id'];
							//echo $i;
								if ($indiv_user['role'] == "admin") {
									echo "<a href='../controllers/grant_admin.php?id=$id&p=1' class='btn btn-danger'>Revoke Admin</a>";
								}else{
									echo "<a href='../controllers/grant_admin.php?id=$id&p=1' class='btn btn-primary'>Make Admin</a>";
								}
							?>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
				
			</div>
			</div>

	

<?php } else { 
	header("Location: ./error.php");
} ?>
<?php }?>

