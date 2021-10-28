<?php $badges_popup = get_proofratings_badges_popup(); ?>

<label><input name="proofratings_badges_popup[customize]" class="checkbox-switch checkbox-yesno" value="yes" type="checkbox" <?php checked( 'yes', $badges_popup->customize) ?>> Customize (this will customize all badges)</label>

<table id="popup-badge-customize" class="form-table">
	<tr>
		<td style="padding-left: 0" colspan="2">
			<?php echo do_shortcode( '[proofratings_badges_popup]');  ?>
		</td>
	</tr>

	<tr>
		<th scope="row"><?php _e('Star Color', 'proofratings') ?></th>
		<td>
			<input class="proofratings-color-field" type="text" name="proofratings_badges_popup[star_color]" value="<?php esc_attr_e($badges_popup->star_color) ?>">
		</td>
	</tr>

	<tr>
		<th scope="row"><?php _e('Review Text Color', 'proofratings') ?></th>
		<td>
			<input class="proofratings-color-field" type="text" name="proofratings_badges_popup[review_text_color]" value="<?php esc_attr_e($badges_popup->review_text_color) ?>">
		</td>
	</tr>

	<tr>
		<th scope="row"><?php _e('Review Background Color', 'proofratings') ?></th>
		<td>
			<input class="proofratings-color-field" type="text" name="proofratings_badges_popup[review_text_background]" value="<?php esc_attr_e($badges_popup->review_text_background) ?>">
		</td>
	</tr>

	<tr>
		<th scope="row"><?php _e('View Review Color', 'proofratings') ?></th>
		<td>
			<input class="proofratings-color-field" type="text" name="proofratings_badges_popup[view_review_color]" value="<?php esc_attr_e($badges_popup->view_review_color) ?>">
		</td>
	</tr>
</table>