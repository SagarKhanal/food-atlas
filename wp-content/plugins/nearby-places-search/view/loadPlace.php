<form action="#" method="post" id="nearby-places-search-search-form" accept-charset="UTF-8"><div id="container"><div id="map-content"><div id="address_cls"><div class="form-item form-type-textfield form-item-address">
<label for="edit-address" class="edit_address"><?php  _e( "Address");?></label>
<input type="text" id="edit-address" name="address" value="<?php print $settings['location_name'] ? esc_attr( $settings['location_name'] ) : DEFAULT_LAT;?>" size="60" maxlength="128" class="form-text" /><button id="cust_btn" type="button" class="cust_btn"><?php  _e( "Search");?></button></div></div>   
<div id="map-container" ></div><div id="bottom-section"><input id="latitude" type="hidden" name="latitude" value="<?php print $settings['location_latitude'] ? esc_attr( $settings['location_latitude'] ) : DEFAULT_LAT;?>" /><input id="longitude" type="hidden" name="longitude" value="<?php print $settings['location_longitude'] ? esc_attr( $settings['location_longitude'] ) : DEFAULT_LAT;?>" /></div></div><div id="navbar"><div class="form-item form-type-radios form-item-types"><label for="edit-types"><?php  _e( "Location Types");?></label>
<div id="edit-types" class="form-radios radio_btn">
<?php 
	if( count($settings['location_type']) > 0) {
		$_location_types = $settings['location_type'];
	} else {
		// default location type
		$_location_types = array(
								'atm' => esc_attr('Atm'),
								'bank' => esc_attr('Bank'),
								'hospital' => esc_attr('Hospital'),
								'park' => esc_attr('Park'),
								'restaurant' => esc_attr('Restaurant'),
								'school' => esc_attr('School')
		);	
	}
	foreach($_location_types as $value):?>
		<div class="form-item form-type-radio form-item-types">
			<input class="radio_btn form-radio" type="radio" id="edit-types-airport" name="types" value="<?php print $value;?>" />  <label class="option" for="edit-types-airport"> <?php print  ucwords(str_replace('_', ' ', $value));?></label>
		</div>
	<?php endforeach;?>
</div></div></div></div></form>