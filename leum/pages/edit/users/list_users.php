<?php
/**
* View Tags
*/
class list_users implements IPage
{
	private $users;
	private $total;
	public function __construct($leum, $dbc, $userInfo, $arguments)
	{
		$leum->PermissionCheck("admin-pages", "users-edit");
		$leum->SetTitle("Users");
		$this->users = User::GetAll($dbc, true);	
		$this->total = count($this->users);
	}
	public function Content()
	{ ?>

<div class="main">
	<div class="header">
		<div class="content">
			<h1>Users</h1>
			<div class="pure-menu pure-menu-horizontal">
				<ul class="pure-menu">
					<li class="pure-menu-item"><a href="<?=ROOT."/edit/";?>" class="pure-menu-link">&#10094; Edit</a></li>
					<li class="pure-menu-item"><a href="<?=ROOT."/edit/user/new";?>" class="pure-menu-link">New User <i class="fa fa-plus"></i></a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="content">
		<?php
		if(isset($this->users) && $this->total > 0)
		{
		?>
		<p>Found <?php echo $this->total; ?> tags.</p>
		<table class="pure-table pure-table-striped full-width">
			<thead>
				<tr>
					<th>ID</th>
					<th>Username</th>
					<th>Email</th>
					<th>Last Login</th>
					<th>Roles</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
			<?php 
				foreach ($this->users as $user)
				{
					$roles = implode(', ', $user->roles);
				?>
				<tr>
					<td><?=$user->user_id;?></td>
					<td><?=$user->username;?></td>
					<td><?=$user->email;?></td>
					<td><?=$user->last_login;?></td>
					<td><?=$roles;?></td>
					<td>
						<a class="pure-button button-compact" href="<?php echo ROOT."/edit/user/$user->user_id"; ?>">
							<i class="fa fa-edit"></i>
						</a>
						<a class="pure-button button-delete button-compact" data-title="<?php echo $user->username; ?>" href="<?php echo ROOT."/api/v2/user/$user->user_id"; ?>">
							<i class="fa fa-trash"></i>
						</a>
					</td>
				</tr>
				<?php
				}
			?>		
			</tbody>
		</table>
		<script type="text/javascript" src="<?php Asset("/resources/js/deleter.js");?>"></script>
		<?php
		}
		else
			echo "Zero results found.";
		?>
	</div>
</div>

<?php }
}
?>
