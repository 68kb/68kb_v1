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
<h2><?php echo $this->lang->line('kb_comments'); ?></h2>
<div class="wrap">

	<?php
	$attributes = array('id' => 'search');
	echo form_open($this->uri->uri_string(), $attributes);
	?>
	<fieldset><legend><?php echo $this->lang->line('kb_search_comments'); ?></legend>
		<?php echo $this->lang->line('kb_search_text'); ?>:
		<input type="text" name="searchtext" id="searchtext" value="<?php echo (isset($q)) ? form_prep($q) : ''; ?>" size="17" />
		&nbsp; <?php echo $this->lang->line('kb_status'); ?>: 
		<select name='comment_approved'>
			<option value=''><?php echo $this->lang->line('kb_all'); ?></option>
			<option value='1'<?php echo (isset($s_display) && $s_display=='1') ? ' selected="selected"' : ''; ?>><?php echo $this->lang->line('kb_active'); ?></option>
			<option value='0'<?php echo (isset($s_display) && $s_display=='0') ? ' selected="selected"' : ''; ?>><?php echo $this->lang->line('kb_notactive'); ?></option>
			<option value='spam'<?php echo (isset($s_display) && $s_display=='spam') ? ' selected="selected"' : ''; ?>><?php echo $this->lang->line('kb_spam'); ?></option>
		</select>
		&nbsp; <input type="submit" id="submit" value="<?php echo $this->lang->line('kb_search'); ?> &#187;" class="button" />
	</fieldset>
<?php echo form_close(); ?>



	<div class="clear"></div>
	
	<?php
	$attributes = array('id' => 'comments', 'name' => 'comments');
	echo form_open('admin/comments/update', $attributes);
	?>
	<input type="hidden" name="act" value="xxxxx" />
		
	<table class="main" width="100%" cellpadding="3" cellspacing="1">		
			<tr>
				<th scope="col" width="5%"><input type="checkbox" name="checkbox" id="checkbox" value="checkbox" /></th>
				<th>
					<?php 
						$class='';
						if(isset($orderby) && isset($opp) && $orderby=="comment_author") {
							$class = $opp;
						}
					?>
					<?php if(isset($sort) && $sort == 'desc'): ?>
					<a href="<?php echo site_url('admin/comments/grid/orderby/comment_author/asc'); ?>" class="<?php echo $class; ?>">
					<?php else: ?>
					<a href="<?php echo site_url('admin/comments/grid/orderby/comment_author/desc'); ?>" class="<?php echo $class; ?>">
					<?php endif; ?>
					<?php echo $this->lang->line('kb_name'); ?>
					</a>
				</th>
				<th>
					Content
				</th>
				<th>
					<?php 
						$class='';
						if(isset($orderby) && isset($opp) && $orderby=="article_title") {
							$class = $opp;
						}
					?>
					<?php if(isset($sort) && $sort == 'desc'): ?>
					<a href="<?php echo site_url('admin/comments/grid/orderby/article_title/asc'); ?>" class="<?php echo $class; ?>">
					<?php else: ?>
					<a href="<?php echo site_url('admin/comments/grid/orderby/article_title/desc'); ?>" class="<?php echo $class; ?>">
					<?php endif; ?>
					<?php echo $this->lang->line('kb_article'); ?>
					</a>
				</th>
				<th><?php echo $this->lang->line('kb_actions'); ?></th>
			</tr>
			<?php  $alt = true; foreach($items as $item): ?>
			<tr<?php if ($alt) echo ' class="second"'; else echo ' class="first"'; $alt = !$alt; ?>>
				<td width="5%"><input type="checkbox" name="commentid[]" value="<?php echo $item['comment_ID']; ?>" class="toggable" /></td>
				<td valign="top">
					<div class="gravatar"><img class="gravatar2" src="<?php echo gravatar( $item['comment_author_email'], "PG", "24", "wavatar" ); ?>" /></div>
					<strong>
						<?php echo $item['comment_author']; ?>
					</strong><br />
					<?php echo $item['comment_author_email']; ?><br />
					<?php echo $item['comment_author_IP']; ?>
				</td>
				<td width="50%" valign="top">
					<div class="submitted"><?php echo lang('kb_date_added') .' '. date($this->config->item('comment_date_format'), $item['comment_date']); ?></div><br />
						
					<?php echo word_limiter($item['comment_content'], 15); ?><br />
					<a href="<?php echo site_url('admin/comments/edit/'.$item['comment_ID']); ?>"><?php echo $this->lang->line('kb_edit'); ?></a>
					&nbsp;|&nbsp;
					<a href="javascript:void(0);" onclick="return deleteSomething('<?php echo site_url('admin/comments/delete/'.$item['comment_ID']); ?>');"><?php echo $this->lang->line('kb_delete'); ?></a>
				</td>
				<td valign="top">
					<a href="<?php echo site_url('article/'.$item['article_uri']); ?>/#comment-<?php echo $item['comment_ID']; ?>"><?php echo $item['article_title']; ?></a>
				</td>
				<td>
					<?php
					if($item['comment_approved'] == 'spam') {
						echo '<span class="spam">'.$this->lang->line('kb_spam').'</span>';
					} elseif($item['comment_approved'] == 0) {
						echo '<span class="inactive">'.$this->lang->line('kb_notactive').'</span>';
					} else {
						echo '<span class="active">'.$this->lang->line('kb_active').'</span>';
					}
					?>
				</td>
				
			</tr>
			
			<?php endforeach; ?>
		</table>
		
		<table width="100%"  border="0" cellspacing="0" cellpadding="3">
				<tr>
					<td>
						<select name="newstatus">
							<option value="" selected><?php echo lang('kb_change_status'); ?></option>
							<option value="1"><?php echo $this->lang->line('kb_active'); ?></option>
							<option value="0"><?php echo $this->lang->line('kb_notactive'); ?></option>
							<option value="spam"><?php echo $this->lang->line('kb_spam'); ?></option>
							<option value="5"><?php echo $this->lang->line('kb_delete'); ?></option>

						</select>
						<input type="submit" value="Update" onclick="document.comments.act.value='changestatus';" />
					</td>
					<td align="right">
						<div class="paginationNum">
							<?php echo $this->lang->line('kb_pages'); ?>: <?php echo $pagination; ?>
						</div>	
					</td>
				</tr>
			</table>
	
		<table width="30%" align="center">
			<tr>
				<td align="right"><img src="<?php echo base_url(); ?>images/page_show.png" border="0" alt="<?php echo $this->lang->line('kb_show'); ?>" /></td>
				<td align="left"><?php echo $this->lang->line('kb_show'); ?></td> 
				<td align="right"><img src="<?php echo base_url(); ?>images/page_edit.png" border="0" alt="<?php echo $this->lang->line('kb_edit'); ?>" /></td>
				<td align="left"><?php echo $this->lang->line('kb_edit'); ?></td>  
				<td align="right"><img src="<?php echo base_url(); ?>images/page_delete.png" border="0" alt="<?php echo $this->lang->line('kb_delete'); ?>" /></td>
				<td align="left"><?php echo $this->lang->line('kb_delete'); ?></td>
			</tr>
		</table>
		
</div>