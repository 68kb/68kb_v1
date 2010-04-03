<h2>Installation Step 1 - Checking Settings</h2>
<div id="form" class="wrap">
	
	<form action="<?php echo site_url('setup/kb/run'); ?>" method="post">
	 <strong>Server Settings:</strong>
			<table width="100%" align="center" cellpadding="5" cellspacing="0" class="modules">
				<tr>
					<td width="50%" class="row1">PHP Version</td>
					<td width="50%" class="row1">
						<?php 
							if (version_compare(phpversion(), "5.1.0", ">="))
							{
								echo phpversion() ." Installed"; 
							}
							else 
							{
								$error=TRUE;
								echo "<span class='spam'>".phpversion() ." Installed</span>"; 
							}
							?>
					</td>
				</tr>
				<tr>
					<td class="row2">MySQL Version</td>
					<td class="row2">
						<?php 
							if($this->db->version() > '3.23')
							{
								echo $this->db->version() ." Installed";
							}
							else
							{
								$error=TRUE;
								echo "<span class='spam'>".$this->db->version() ." Installed</span>";
							}
						?>
					</td>
				</tr>
			</table>
			<br />
			<strong>CHMOD Settings:</strong>
			<table width="100%" align="center" cellpadding="5" cellspacing="0" class="modules">
				<tr>
					<td class="row2">uploads</td>
					<td class="row2">
						<?php if($uploads != 'Ok') $error=TRUE; ?>
						<?php echo $uploads; ?>
					</td>
				</tr>
			</table>
			<br />
			<strong>Administration Settings:</strong>
			<table width="100%" align="center" cellpadding="5" cellspacing="0" class="modules">
				<tr>
					<td width="50%" class="row1">Admin Username</td>
					<td width="50%" class="row1">
						<input type="text" name="username" />
					</td>
				</tr>
				<tr>
					<td class="row2">Admin Password</td>
					<td class="row2">
						<input type="password" name="password" />
					</td>
				</tr>
				<tr>
					<td width="50%" class="row1">Admin Email</td>
					<td width="50%" class="row1">
						<input type="text" name="adminemail" />
					</td>
				</tr>
				<tr>
					<td width="50%" class="row1">Overwrite Tables:</td>
					<td width="50%" class="row1">
						<input type="checkbox" name="drop" value="Y" />
					</td>
				</tr>
			</table>
			
			<p align="right">
			<?php
			if($error==TRUE)
			{
				echo "<strong>Please fix the above errors and refresh this page.";
			}
			else
			{
			?>
			<input type="hidden" name="step" value="<?php echo $nextStep; ?>" />
			<input type="submit" name="submit" class="save" value="Next Step" />
			<?php } ?>
			</p>
		</form>
</div>