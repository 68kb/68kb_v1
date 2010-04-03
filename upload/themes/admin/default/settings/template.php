	<div id="theme">
			<h3><?php echo lang('kb_current_template'); ?></h3>
			<table id="current-theme">
				<tr>
					<td>
						<img class="current" src="<?php echo $active['preview']; ?>" alt="Current theme preview" />
					</td>
					<td>
						<h3><?php echo $active['name']; ?></h3>
						<p class="description"><?php echo $active['description']; ?></p>
					</td>

				</tr>
			</table>

			<h3><?php echo lang('kb_available_templates'); ?></h3>
			<br class="clear" />
				<?php if(is_array($available_themes)): foreach($available_themes AS $row): ?>
					<div class="available_box">
						<div class="available_box_heading"><?php echo $row['title']; ?></div>
						<a href="<?php echo site_url('admin/settings/templates/'.$row['file']);?>"><img src="<?php echo $row['preview']; ?>" width="200" height="167" /></a>
						<p><a href="<?php echo site_url('admin/settings/templates/'.$row['file']);?>"><?php echo lang('kb_activate'); ?></a></p>
					</div>
				<?php endforeach; endif; ?>
			<br class="clear" />
		</div>
