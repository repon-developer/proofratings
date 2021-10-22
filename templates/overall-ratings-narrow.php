<?php $narrow_ratings = get_proofratings_overall_ratings_narrow(); ?>
<table class="form-table">
	<tr>
		<th scope="row">
			<?php _e('Shortcode', 'proofratings') ?>
			<p class="description" style="font-weight: normal">Embed shortcode</p>
		</th>
		<td><code class="shortocde-area">[proofratings_overall_ratings type="narrow"]</code></td>
	</tr>

	<tr>
		<th scope="row"><?php _e('Float Visibility', 'proofratings') ?></th>
		<td>
			<label>
				<input name="proofratings_overall_ratings_narrow[float]" value="no" type="hidden">
				<input class="checkbox-switch checkbox-float-embed" name="proofratings_overall_ratings_narrow[float]" value="yes" type="checkbox" <?php checked( 'yes', $narrow_ratings->float ) ?>>
				<?php _e('Float/Embed only', 'proofratings'); ?>
			</label>
		</td>
	</tr>

	<tbody id="overall-ratings-narrow-float-options">
		<tr>
			<th scope="row"><?php _e('Tablet Visibility', 'proofratings') ?></th>
			<td>
				<label>
					<input name="proofratings_overall_ratings_narrow[tablet]" value="no" type="hidden">
					<input class="checkbox-switch" name="proofratings_overall_ratings_narrow[tablet]" value="yes" type="checkbox" <?php checked( 'yes', $narrow_ratings->tablet ) ?>>
					<?php _e('Show/Hide on tablet', 'proofratings'); ?>
				</label>
			</td>
		</tr>

		<tr>
			<th scope="row"><?php _e('Mobile Visibility', 'proofratings') ?></th>
			<td>
				<label>
					<input name="proofratings_overall_ratings_narrow[mobile]" value="no" type="hidden">
					<input class="checkbox-switch" name="proofratings_overall_ratings_narrow[mobile]" value="yes" type="checkbox" <?php checked( 'yes', $narrow_ratings->mobile ) ?>>
					<?php _e('Show/Hide on mobile', 'proofratings'); ?>
				</label>
			</td>
		</tr>

		<tr>
			<th scope="row"><?php _e('Close option', 'proofratings') ?></th>
			<td>
				<label>
					<input name="proofratings_overall_ratings_narrow[close_button]" value="no" type="hidden">
					<input class="checkbox-switch" name="proofratings_overall_ratings_narrow[close_button]" value="yes" type="checkbox" <?php checked( 'yes', $narrow_ratings->close_button ) ?>>
				</label>
			</td>
		</tr>

		<tr>
			<th scope="row"><?php _e('Position', 'proofratings') ?></th>
			<td>
				<select name="proofratings_overall_ratings_narrow[position]">
					<option value="left" <?php selected('left', $narrow_ratings->position) ?>><?php _e('Left', 'proofratings') ?></option>
					<option value="center" <?php selected('center', $narrow_ratings->position) ?>><?php _e('Center', 'proofratings') ?></option>
					<option value="right" <?php selected('right', $narrow_ratings->position) ?>><?php _e('Right', 'proofratings') ?></option>
				</select>
			</td>
		</tr>
	</tbody>

	<tr>
		<td style="padding-left: 0" colspan="2">
			<label><input name="proofratings_overall_ratings_narrow[customize]" class="checkbox-switch checkbox-yesno" value="yes" type="checkbox" <?php checked( 'yes', $narrow_ratings->customize) ?>> Customize</label>
		</td>
	</tr>

	<tbody id="overall-ratings-narrow-customize-options" style="display: none">
		<tr>
			<td style="padding-left: 0" colspan="2">
				<?php echo do_shortcode( '[proofratings_overall_ratings type="rectangle"]');  ?>
			</td>
		</tr>

		<tr>
			<th scope="row"><?php _e('Star Color', 'proofratings') ?></th>
			<td>
				<input class="proofratings-color-field" type="text" 
					name="proofratings_overall_ratings_narrow[star_color]"
					value="<?php esc_attr_e($narrow_ratings->star_color) ?>" data-default-color="#212A3D">
			</td>
		</tr>

		<tr id="overall-ratings-shadow">
			<th scope="row"><?php _e('Shadow', 'proofratings') ?></th>
			<td>
				<input name="proofratings_overall_ratings_narrow[shadow]" value="no" type="hidden">
				<input class="checkbox-switch" name="proofratings_overall_ratings_narrow[shadow]" value="yes" type="checkbox" <?php checked( 'yes', $narrow_ratings->shadow ) ?>>
			</td>
		</tr>

		
		<tr class="overall-ratings-narrow-shadow-options">
			<th scope="row"><?php _e('Shadow Color', 'proofratings') ?></th>
			<td>
				<input class="proofratings-color-field" type="text" 
					name="proofratings_overall_ratings_narrow[shadow_color]"
					value="<?php esc_attr_e($narrow_ratings->shadow_color) ?>" data-default-color="#f6d300">
			</td>
		</tr>

		<tr class="overall-ratings-narrow-shadow-options">
			<th scope="row"><?php _e('Shadow Hover Color', 'proofratings') ?></th>
			<td>
				<input class="proofratings-color-field" type="text" 
					name="proofratings_overall_ratings_narrow[shadow_hover]"
					value="<?php esc_attr_e($narrow_ratings->shadow_hover) ?>" data-default-color="#377dbc">
			</td>
		</tr>

		<tr>
			<th scope="row"><?php _e('Background Color', 'proofratings') ?></th>
			<td>
				<input class="proofratings-color-field" type="text" 
					name="proofratings_overall_ratings_narrow[background_color]" 
					value="<?php esc_attr_e($narrow_ratings->background_color) ?>" data-default-color="#fff">
			</td>
		</tr>

		<tr>
			<th scope="row"><?php _e('Review Count Text Color', 'proofratings') ?></th>
			<td>
				<input class="proofratings-color-field" type="text" 
					name="proofratings_overall_ratings_narrow[review_text_color]" 
					value="<?php esc_attr_e($narrow_ratings->review_text_color) ?>">
			</td>
		</tr>
	</tbody>
</table>


<table id="overall-narrow-ratings-pages" class="form-table">
	<caption>Page to show on</caption>
	<?php foreach (get_pages() as $page) : ?>
	<tr>
		<th scope="row"><?php echo $page->post_title ?></th>
		<td>	
		<?php
			$checked = !isset($narrow_ratings->pages[$page->ID]) || $narrow_ratings->pages[$page->ID] == 'yes'? 'checked' : '';
			printf('<input name="proofratings_overall_ratings_narrow[pages][%s]" value="no" type="hidden">', $page->ID);
			printf(
				'<label><input class="checkbox-switch" name="proofratings_overall_ratings_narrow[pages][%s]" value="yes" %s type="checkbox"></label>',
				$page->ID, $checked
			);
		?>
		</td>
	</tr>
	<?php endforeach; ?>
</table>