<h1><?php echo lang('kb_search_articles'); ?></h1>

	<div class="search">
		<form method="post" action="<?php echo site_url('search'); ?>" class="searchform">
			<label for="searchtext"><?php echo lang('kb_search_kb'); ?>:</label><br />
			<input type="text" name="searchtext" class="search_input" id="searchtext" value="<?php echo (isset($searchtext)) ? form_prep($searchtext) : ''; ?>" /> 
				<select tabindex="4" name="category" id="category">
					<option value="0"><?php echo lang('kb_incat'); ?></option>
					<?php foreach($cat_tree as $row): ?>
					<?php $default = ((isset($category) && $category == $row['cat_id'])) ? true : false; ?>
					<option value="<?php echo $row['cat_id']; ?>" <?php echo set_select('category', $row['cat_id'], $default); ?>><?php echo $row['cat_name']; ?></option>
					<?php endforeach; ?>
				</select>
			<input type="submit" name="Search" value="<?php echo lang('kb_search'); ?>" />
		</form>
	</div>

<?php  if (isset($articles)): ?>
	
	<?php if ($articles->num_rows() > 0): ?>
		
		<h2><?php echo lang('kb_search_results'); ?></h2>
		
		<table width="100%" border="0"  cellspacing="5">
			
			<?php foreach($articles->result() as $row): ?>
			
			<tr>
				<td>
					<div class="article">
						<a href="<?php echo site_url("article/".$row->article_uri."/"); ?>"><?php echo $row->article_title; ?></a>
					</div>
					<?php echo $row->article_short_desc; ?>
				</td>
			</tr>
			
			<?php endforeach; ?>
			
		</table>
	
	<?php else: ?>
		
		<h2><?php echo lang('kb_search_results'); ?></h2>	
		<p><?php echo lang('kb_no_results'); ?></p>
		
	<?php endif; ?>
	
<?php endif; ?>