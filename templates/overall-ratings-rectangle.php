<?php $overall_rectangle = get_proofratings_overall_ratings_rectangle(); ?>
<table class="form-table">
	<tr>
		<th scope="row">
			<?php _e('Shortcode', 'proofratings') ?>
			<p class="description" style="font-weight: normal">Embed shortcode</p>
		</th>
		<td><code class="shortocde-area">[proofratings_overall_ratings type="rectangle"]</code></td>
	</tr>

	<tr>
		<th scope="row"><?php _e('Float Visibility', 'proofratings') ?></th>
		<td>
			<label>
				<input name="proofratings_overall_ratings_rectangle[float]" value="no" type="hidden">
				<input class="checkbox-switch checkbox-float-embed" name="proofratings_overall_ratings_rectangle[float]" value="yes" type="checkbox" <?php checked( 'yes', $overall_rectangle->float ) ?>>
				<?php _e('Float/Embed only', 'proofratings'); ?>
			</label>
		</td>
	</tr>

	<tr id="badge-tablet-visibility">
		<th scope="row"><?php _e('Tablet Visibility', 'proofratings') ?></th>
		<td>
			<label>
				<input name="proofratings_overall_ratings_rectangle[tablet]" value="no" type="hidden">
				<input class="checkbox-switch" name="proofratings_overall_ratings_rectangle[tablet]" value="yes" type="checkbox" <?php checked( 'yes', $overall_rectangle->tablet ) ?>>
				<?php _e('Show/Hide on tablet', 'proofratings'); ?>
			</label>
		</td>
	</tr>

	<tr id="badge-mobile-visibility">
		<th scope="row"><?php _e('Mobile Visibility', 'proofratings') ?></th>
		<td>
			<label>
				<input name="proofratings_overall_ratings_rectangle[mobile]" value="no" type="hidden">
				<input class="checkbox-switch" name="proofratings_overall_ratings_rectangle[mobile]" value="yes" type="checkbox" <?php checked( 'yes', $overall_rectangle->mobile ) ?>>
				<?php _e('Show/Hide on mobile', 'proofratings'); ?>
			</label>
		</td>
	</tr>

	<tr id="badge-close-options">
		<th scope="row"><?php _e('Close option', 'proofratings') ?></th>
		<td>
			<label>
				<input name="proofratings_overall_ratings_rectangle[close_button]" value="no" type="hidden">
				<input class="checkbox-switch" name="proofratings_overall_ratings_rectangle[close_button]" value="yes" type="checkbox" <?php checked( 'yes', $overall_rectangle->close_button ) ?>>
			</label>
		</td>
	</tr>

	<tr id="badge-position">
		<th scope="row"><?php _e('Position', 'proofratings') ?></th>
		<td>
			<select name="proofratings_overall_ratings_rectangle[position]">
				<option value="left" <?php selected('left', $overall_rectangle->position) ?>><?php _e('Left', 'proofratings') ?></option>
				<option value="right" <?php selected('right', $overall_rectangle->position) ?>><?php _e('Right', 'proofratings') ?></option>
			</select>
		</td>
	</tr>

	<tr>
		<td style="padding-left: 0" colspan="2">
			<label><input name="proofratings_overall_ratings_rectangle[customize]" class="checkbox-switch checkbox-yesno" value="yes" type="checkbox" <?php checked( 'yes', $overall_rectangle->customize) ?>> Customize</label>
		</td>
	</tr>

	<tbody id="overall-ratings-customize-options" style="display: none">
		<tr>
			<td style="padding-left: 0" colspan="2">
				<?php echo do_shortcode( '[proofratings_overall_ratings type="rectangle"]');  ?>
			</td>
		</tr>

		<tr>
			<th scope="row"><?php _e('Star Color', 'proofratings') ?></th>
			<td>
				<input class="proofratings-color-field" type="text" name="proofratings_overall_ratings_rectangle[star_color]" value="<?php esc_attr_e($overall_rectangle->star_color) ?>">
			</td>
		</tr>

		<tr id="badge-hide-shadow">
			<th scope="row"><?php _e('Shadow', 'proofratings') ?></th>
			<td>
				<input name="proofratings_overall_ratings_rectangle[shadow]" value="no" type="hidden">
				<input class="checkbox-switch" name="proofratings_overall_ratings_rectangle[shadow]" value="yes" type="checkbox" <?php checked( 'yes', $overall_rectangle->shadow ) ?>>
			</td>
		</tr>

		<tr id="badge-shadow-color">
			<th scope="row"><?php _e('Shadow Color', 'proofratings') ?></th>
			<td>
				<input class="proofratings-color-field" type="text" name="proofratings_overall_ratings_rectangle[shadow_color]" value="<?php esc_attr_e($overall_rectangle->shadow_color) ?>">
			</td>
		</tr>

		<tr id="badge-shadow-hover-color">
			<th scope="row"><?php _e('Shadow Hover Color', 'proofratings') ?></th>
			<td>
				<input class="proofratings-color-field" type="text" name="proofratings_overall_ratings_rectangle[shadow_hover]" value="<?php esc_attr_e($overall_rectangle->shadow_hover) ?>">
			</td>
		</tr>

		<tr>
			<th scope="row"><?php _e('Background Color', 'proofratings') ?></th>
			<td>
				<input class="proofratings-color-field" type="text"  name="proofratings_overall_ratings_rectangle[background_color]"  value="<?php esc_attr_e($overall_rectangle->background_color) ?>" data-default-color="#fff">
			</td>
		</tr>

		<tr>
			<th scope="row"><?php _e('Review Text Color', 'proofratings') ?></th>
			<td>
				<input class="proofratings-color-field" type="text" 
					name="proofratings_overall_ratings_rectangle[review_text_color]" 
					value="<?php esc_attr_e($overall_rectangle->review_text_color) ?>">
			</td>
		</tr>

		<tr>
			<th scope="row"><?php _e('Review Background Color', 'proofratings') ?></th>
			<td>
				<input class="proofratings-color-field" type="text" 
					name="proofratings_overall_ratings_rectangle[review_background]" 
					value="<?php esc_attr_e($overall_rectangle->review_background) ?>" data-default-color="#212a3d">
			</td>
		</tr>
	</tbody>
</table>


<table id="floating-badge-pages" class="form-table">
	<caption>Page to show on</caption>
	<?php foreach (get_pages() as $page) : ?>
	<tr>
		<th scope="row"><?php echo $page->post_title ?></th>
		<td>	
		<?php
			$checked = !isset($overall_rectangle->pages[$page->ID]) || $overall_rectangle->pages[$page->ID] == 'yes'? 'checked' : '';
			printf('<input name="proofratings_overall_ratings_rectangle[pages][%s]" value="no" type="hidden">', $page->ID);
			printf(
				'<label><input class="checkbox-switch" name="proofratings_overall_ratings_rectangle[pages][%s]" value="yes" %s type="checkbox"></label>',
				$page->ID, $checked
			);
		?>
		</td>
	</tr>
	<?php endforeach; ?>
</table>