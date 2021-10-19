<?php
	$sites_square = wp_parse_args(get_option('proofratings_badges_sites_square'), [
		'customize' => 'no',
		'star_color' => '',
		'text_color' => '',
		'review_count_textcolor' => '',
		'background' => '',
		'shadow' => 'yes',
		'shadow_color' => '',
		'shadow_hover_color' => '',
	]);

?>
<table class="form-table">
	<tr>
		<th scope="row" style="vertical-align:middle">
			<?php _e('Shortcode', 'proofratings') ?>
			<p class="description" style="font-weight: normal">Use shortcode where you want to display review widgets</p>
		</th>
		<td>
			<code class="shortocde-area">[proofratings_widgets badge_style="sites_square"]</code>
		</td>
	</tr>
</table>

<label><input name="proofratings_badges_sites_square[customize]" class="checkbox-switch checkbox-yesno" value="yes" type="checkbox" <?php checked( 'yes', $sites_square['customize']) ?>> Customize</label>
<div class="gap-30"></div>
<div id="sites-square-badge-customize">

<?php
echo do_shortcode( '[proofratings_widgets id="proofratings-badge-sites-square"]');  ?>


<table class="form-table">
	<tr>
		<th scope="row">Star Color</th>
		<td><?php printf('<input class="proofratings-color-field" name="proofratings_badges_sites_square[star_color]" type="text" value="%s">', esc_attr( $sites_square['star_color'])) ?></td>
	</tr>

	<tr>
		<th scope="row">Text Color</th>
		<td><?php printf('<input class="proofratings-color-field" name="proofratings_badges_sites_square[text_color]" type="text" value="%s">', esc_attr( $sites_square['text_color'])) ?></td>
	</tr>

	<tr>
		<th scope="row">Review count text color</th>
		<td><?php printf('<input class="proofratings-color-field" name="proofratings_badges_sites_square[review_count_textcolor]" type="text" value="%s">', esc_attr( $sites_square['review_count_textcolor'])) ?></td>
	</tr>
								
	<tr>
		<th scope="row">Background Color</th>
		<td><?php printf('<input class="proofratings-color-field" name="proofratings_badges_sites_square[background]" type="text" value="%s">', esc_attr( $sites_square['background'])) ?></td>
	</tr>

	<tr>
		<th scope="row">Shadow</th>
		<td><input class="checkbox-switch" name="proofratings_badges_sites_square[shadow]" value="yes" type="checkbox" <?php checked( 'yes', $sites_square['shadow']) ?>></td>
	</tr>

	<tbody id="proofratings-badges-sites-square-shadow-options" style="display: none">
		<tr>
			<th scope="row">Shadow Color</th>
			<td><?php printf('<input class="proofratings-color-field" name="proofratings_badges_sites_square[shadow_color]" type="text" value="%s">', esc_attr( $sites_square['shadow_color'])) ?></td>
		</tr>

		<tr>
			<th scope="row">Shadow Hover Color</th>
			<td><?php printf('<input class="proofratings-color-field" name="proofratings_badges_sites_square[shadow_hover_color]" type="text" value="%s">', esc_attr($sites_square['shadow_hover_color'])) ?></td>
		</tr>
	</tbody>

</table>

</div>
