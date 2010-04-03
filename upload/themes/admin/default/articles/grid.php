<script language="javascript" type="text/javascript">
	<!--
		function deleteSomething(url){
			removemsg = "<?php echo lang('kb_are_you_sure'); ?>"
			if (confirm(removemsg)) {
				document.location = url;
			}
		}
	// -->
</script>
<h2><?php echo lang('kb_articles'); ?>  <a class="addnew" href="<?php echo site_url('admin/articles/add');?>"><?php echo lang('kb_add_article'); ?></a></h2>


	<?php
	$attributes = array('id' => 'search');
	echo form_open($this->uri->uri_string(), $attributes);
	?>
	<table>
			<tr>
				<td><input type="text" name="searchtext" id="searchtext" value="<?php echo (isset($q)) ? form_prep($q) : ''; ?>" size="17" /></td>
				<td>
					<select name="cat" id="cat">
						<option value="0" selected><?php echo lang('kb_categories'); ?></option>
						<option value="0"><?php echo lang('kb_all'); ?></option>
						<?php foreach($categories as $row): ?>
						<option value="<?php echo $row['cat_id']; ?>"<?php echo (isset($s_cat) && $s_cat==$row['cat_id']) ? ' selected="selected"' : ''; ?>><?php echo $row['cat_name']; ?></option>
						<?php endforeach; ?>
					</select>
				</td>
				<td>
					<select name='article_display'>
						<option value='' selected><?php echo lang('kb_status'); ?></option>
						<option value=''><?php echo lang('kb_all'); ?></option>
						<option value='Y'<?php echo (isset($s_display) && $s_display=='Y') ? ' selected="selected"' : ''; ?>><?php echo lang('kb_active'); ?></option>
						<option value='N'<?php echo (isset($s_display) && $s_display=='N') ? ' selected="selected"' : ''; ?>><?php echo lang('kb_notactive'); ?></option>
					</select>
				</td>
				<td>
					<select name='a_author' id='a_author'>
						<option value='0' selected><?php echo lang('kb_author'); ?></option>
						<option value='0'><?php echo lang('kb_all'); ?></option>
						<?php foreach($authors->result() as $rs): ?>
						<option value="<?php echo $rs->id; ?>"<?php echo (isset($s_author) && $s_author==$rs->id) ? ' selected="selected"' : ''; ?>><?php echo $rs->username; ?></option>
						<?php endforeach; ?>
					</select>
				</td>
				<td>
					<input type="submit" id="submit" value="<?php echo lang('kb_search'); ?> &#187;" class="button" />
				</td>
			</tr>
		</table>
		
		
	<input type="hidden" name="search" value="go" />
<?php echo form_close(); ?>



	<div class="clear"></div>
	
	<?php
	$attributes = array('id' => 'articles', 'name' => 'articles');
	echo form_open('admin/articles/update', $attributes);
	?>
	<input type="hidden" name="act" value="xxxxx" />
	
	<table class="main" width="100%" cellpadding="0" cellspacing="0">		
			<tr>
				<th scope="col" class="row_small"><input type="checkbox" name="checkbox" id="checkbox" value="checkbox" /></th>
				
				<th>
					<?php 
						$class='';
						if(isset($orderby) && isset($opp) && $orderby=="article_title") {
							$class = $opp;
						}
					?>
					<?php if(isset($sort) && $sort == 'desc'): ?>
					<a href="<?php echo site_url('admin/articles/grid/orderby/article_title/asc'); ?>" class="<?php echo $class; ?>">
					<?php else: ?>
					<a href="<?php echo site_url('admin/articles/grid/orderby/article_title/desc'); ?>" class="<?php echo $class; ?>">
					<?php endif; ?>
					<?php echo lang('kb_title'); ?>
					</a>
					
				</th>
				<th>
					<?php echo lang('kb_categories'); ?>
				</th>
				<th width="13%">
					<?php 
						$class='';
						if(isset($orderby) && isset($opp) && $orderby=="article_date") {
							$class = $opp;
						}
					?>
						<?php if(isset($sort) && $sort == 'desc'): ?>
						<a href="<?php echo site_url('admin/articles/grid/orderby/article_date/asc'); ?>" class="<?php echo $class; ?>">
						<?php else: ?>
						<a href="<?php echo site_url('admin/articles/grid/orderby/article_date/desc'); ?>" class="<?php echo $class; ?>">
						<?php endif; ?>
						<?php echo lang('kb_date_added'); ?>
						</a>
				</th>
				<th>
					<?php 
						$class='';
						if(isset($orderby) && isset($opp) && $orderby=="article_hits") {
							$class = $opp;
						}
					?>
					<?php if(isset($sort) && $sort == 'desc'): ?>
					<a href="<?php echo site_url('admin/articles/grid/orderby/article_hits/asc'); ?>" class="<?php echo $class; ?>">
					<?php else: ?>
					<a href="<?php echo site_url('admin/articles/grid/orderby/article_hits/desc'); ?>" class="<?php echo $class; ?>">
					<?php endif; ?>
					<?php echo lang('kb_views'); ?>
					</a>
				</th>
				<th>
					<?php 
						$class='';
						if(isset($orderby) && isset($opp) && $orderby=="article_display") {
							$class = $opp;
						}
					?>
					<?php if(isset($sort) && $sort == 'desc'): ?>
					<a href="<?php echo site_url('admin/articles/grid/orderby/article_display/asc'); ?>" class="<?php echo $class; ?>">
					<?php else: ?>
					<a href="<?php echo site_url('admin/articles/grid/orderby/article_display/desc'); ?>" class="<?php echo $class; ?>">
					<?php endif; ?>
					<?php echo lang('kb_display'); ?>
					</a>
				</th>
				<th><?php echo lang('kb_actions'); ?></th>
			</tr>
			
			<?php if(isset($items)): ?>
			
			<?php  $alt = true; foreach($items as $item): ?>
			<tr<?php if ($alt) echo ' class="second"'; else echo ' class="first"'; $alt = !$alt; ?>>
				<td class="row_small"><input type="checkbox" name="articleid[]" value="<?php echo $item['article_id']; ?>" class="toggable" /></td>
				<td class="title"><a href="<?php echo site_url('admin/articles/edit/'.$item['article_id']); ?>"><?php echo $item['article_title']; ?></a></td>
				
				<td class="categories">
					<?php  if (isset($item['cats'])): ?>
						<?php foreach($item['cats']->result() as $row): ?>
							<a href="<?php echo site_url('admin/categories/edit/'.$row->cat_id); ?>"><?php echo $row->cat_name; ?></a>,
						<?php endforeach; ?>
					<?php endif; ?>
				</td>
				<td class=""><?php echo date($this->config->item('article_date_format'), $item['article_date']); ?></td>
				<td><?php echo $item['article_hits']; ?></td>
				<td>
					<?php
					if($item['article_display'] != 'Y') {
						echo '<span class="inactive">'.lang('kb_notactive').'</span>';
					} else {
						echo '<span class="active">'.lang('kb_active').'</span>';
					}
					?>
				</td>
				<td width="10%" nowrap="nowrap">
					<a href="<?php echo site_url('article/'.$item['article_uri']); ?>" target="_blank"><img src="<?php echo base_url(); ?>images/page_show.png" border="0" alt="<?php echo lang('kb_show'); ?>" title="<?php echo lang('kb_show'); ?>" /></a>
					&nbsp;
					<a href="<?php echo site_url('admin/articles/edit/'.$item['article_id']); ?>"><img src="<?php echo base_url(); ?>images/page_edit.png" border="0" alt="<?php echo lang('kb_edit'); ?>" title="<?php echo lang('kb_edit'); ?>" /></a>
					&nbsp;
					<a href="javascript:void(0);" onclick="return deleteSomething('<?php echo site_url('admin/articles/delete/'.$item['article_id']); ?>');"><img src="<?php echo base_url(); ?>images/page_delete.png" border="0" alt="<?php echo lang('kb_delete'); ?>" title="<?php echo lang('kb_delete'); ?>" /></a>
				</td>
			</tr>
			<?php endforeach; ?>
			
			<?php endif; ?>
			
		</table>
	
	<table width="100%"  border="0" cellspacing="0" cellpadding="3">
				<tr>
					<td>
						<select name="newstatus">
							<option value="" selected><?php echo lang('kb_change_status'); ?></option>
							<option value="Y"><?php echo lang('kb_active'); ?></option>
							<option value="N"><?php echo lang('kb_notactive'); ?></option>
							<option value="D"><?php echo lang('kb_delete'); ?></option>

						</select>
						<input type="submit" value="Update" onclick="document.comments.act.value='changestatus';" />
					</td>
					<td align="right">
						<?php if($paginate==TRUE): ?>
						<div class="paginationNum">
							<?php echo lang('kb_pages'); ?>: <?php echo $pagination; ?>
						</div>	
						<?php endif; ?>
					</td>
				</tr>
			</table>
		</form>	
		<table width="30%" align="center">
			<tr>
				<td align="right"><img src="<?php echo base_url(); ?>images/page_show.png" border="0" alt="<?php echo lang('kb_show'); ?>" /></td>
				<td align="left"><?php echo lang('kb_show'); ?></td> 
				<td align="right"><img src="<?php echo base_url(); ?>images/page_edit.png" border="0" alt="<?php echo lang('kb_edit'); ?>" /></td>
				<td align="left"><?php echo lang('kb_edit'); ?></td>  
				<td align="right"><img src="<?php echo base_url(); ?>images/page_delete.png" border="0" alt="<?php echo lang('kb_delete'); ?>" /></td>
				<td align="left"><?php echo lang('kb_delete'); ?></td>
			</tr>
		</table>
		
