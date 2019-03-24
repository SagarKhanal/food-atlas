=== Nearby Places Search ===
Contributors: Dipankar Biswas
Tags: Google map place search, admin, radius, location, google map shortcode
Tested up to: 4.7
Stable tag: trunk


=== Description ===

Nearby Places Search: This Plugin integrates with the Google Places and GMap.

Shows a list of places of a certain type and configurable from the back-end 
settings within the specified radius for specified location.

=== Requirement ===

Google Maps API Key.


== Installation ===
= To Install: =

1. Download and unzip the files into a folder on your hard drive.

2. Upload the `/nearby_places_search/` folder to the `/wp-content/plugins/` folder on your site.

3. Activate this plugin from `/wp-admin/plugins.php` page.

4. After activation done copy the shortcode [nearby_places_search_code],Paste the Shortcode into Your Page or Post.



=== Shortcode ===

Shortcode : [nearby_places_search_code]

=== Usage ===

1. In path (wp-admin/options-general.php?page=searchsettings) configure the search settings for 
places. 

2. You can change the type of place, the center point and the radius from the 
center point in the configuration page.

3. Copy the shortcode [nearby_places_search_code],Paste the Shortcode into Your Page , Post or any custom post type.



=== Configuration Settings ===

1. Location Types - Location Type is restricted to select only one value.
See https://developers.google.com/places/documentation/supported_types 
for supported types. 
Default values set as: Atm|Bank|Hospital|Park|Restaurant|School

2. Google API Authentication Method - 'API Key' or 'Google Maps API for Work'
As per method selection add 'Google Maps API Key'.

3. Location - The latitude/longitude around which to retrieve the information 
of place. This must be specified as latitude,longitude.
Default value set to 18.5204, 73.8567 (Pune, Maharashtra, India).

4. Radius - Defines the distance (in meters) within which to return place 
results. The maximum allowed radius is 50000 meters. Default set to 1000 meter.

Note : Default configuration value define at 'config/nearby_places_search_config.php'

== Screenshots ==

1. Admin Search Settings configuration page.
2. Frontend Search page.

=== Reference link ===

https://developers.google.com/places/web-service/search#PlaceSearchRequests