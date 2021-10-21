<?php $sites_rectangle = get_proofratings_badges_rectangle(); ?>
<table class="form-table">
	<tr>
		<th scope="row" style="vertical-align:middle">
			<?php _e('Shortcode', 'proofratings') ?>
			<p class="description" style="font-weight: normal">Use shortcode where you want to display review widgets</p>
		</th>
		<td>
			<code class="shortocde-area">[proofratings_widgets style="rectangle"]</code>
		</td>
	</tr>
</table>

<label><input name="proofratings_badges_rectangle[customize]" class="checkbox-switch checkbox-yesno" value="yes" type="checkbox" <?php checked( 'yes', $sites_rectangle->customize) ?>> Customize (this will customize all badges)</label>
<div class="gap-30"></div>
<div id="rectangle-badge-customize">

<?php
echo do_shortcode( '[proofratings_widgets id="proofratings-badge-rectangle" style="rectangle"]');  ?>

<table class="form-table">
	<tr>
		<th scope="row">Star Color</th>
		<td><?php printf('<input class="proofratings-color-field" name="proofratings_badges_rectangle[star_color]" type="text" value="%s">', esc_attr( $sites_rectangle->star_color)) ?></td>
	</tr>

	<tr>
		<th scope="row">Text Color</th>
		<td><?php printf('<input class="proofratings-color-field" name="proofratings_badges_rectangle[text_color]" type="text" value="%s">', esc_attr( $sites_rectangle->text_color)) ?></td>
	</tr>

	<tr>
		<th scope="row">Review count text color</th>
		<td><?php printf('<input class="proofratings-color-field" name="proofratings_badges_rectangle[review_count_textcolor]" type="text" value="%s">', esc_attr( $sites_rectangle->review_count_textcolor)) ?></td>
	</tr>
								
	<tr>
		<th scope="row">Background Color</th>
		<td><?php printf('<input class="proofratings-color-field" name="proofratings_badges_rectangle[background]" type="text" value="%s">', esc_attr( $sites_rectangle->background)) ?></td>
	</tr>

	<input class="checkbox-switch" name="proofratings_badges_rectangle[shadow]" value="no" type="hidden">
	<tr>
		<th scope="row">Shadow</th>
		<td><input class="checkbox-switch" name="proofratings_badges_rectangle[shadow]" value="yes" type="checkbox" <?php checked( 'yes', $sites_rectangle->shadow) ?>></td>
	</tr>

	<tbody id="proofratings-badges-rectangle-shadow-options" style="display: none">
		<tr>
			<th scope="row">Shadow Color</th>
			<td><?php printf('<input class="proofratings-color-field" name="proofratings_badges_rectangle[shadow_color]" type="text" value="%s">', esc_attr( $sites_rectangle->shadow_color)) ?></td>
		</tr>

		<tr>
			<th scope="row">Shadow Hover Color</th>
			<td><?php printf('<input class="proofratings-color-field" name="proofratings_badges_rectangle[shadow_hover_color]" type="text" value="%s">', esc_attr($sites_rectangle->shadow_hover_color)) ?></td>
		</tr>
	</tbody>
</table>

</div>
