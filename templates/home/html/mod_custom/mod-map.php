<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_custom
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>


<div class="mod-custom mod-map section-padding-tb light-bg" id="about" >
	<div class="grid-container">
		<div uk-grid>
			<div class="uk-width-1-1 uk-width-1-3@s">
				<div class="content">
					<h2><?php echo $module->title; ?></h2>
					<?php echo $module->content; ?>
					<div class="icon-outer uk-margin-medium-top">
						<div class="item">
							<span class="icon icon-network"></span>
							<div class="item-content">
								<h2>79</h2>
								<p>Network of Chambers</p>
							</div>
						</div>
						<div class="item">
							<span class="icon icon-country"></span>
							<div class="item-content">
								<h2>56</h2>
								<p>Country presence</p>
							</div>
						</div>
						<div class="item">
							<span class="icon icon-members"></span>
							<div class="item-content">
								<h2>20,000</h2>
								<p>Worldwide members</p>
							</div>
						</div>
						<div class="item">
							<span class="icon icon-business"></span>
							<div class="item-content">
								<h2>300,000</h2>
								<p>Businesses</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="uk-width-1-1 uk-width-2-3@s">
				<div id="map"></div>
			</div>
		</div>
	</div>

</div>

<script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAvavqTeZXc48iALmnGfypI6ZuJ1jNW-lE&callback=initMap&libraries=&v=weekly"
      defer
    ></script>

<script>
function initMap() {
  const map = new google.maps.Map(document.getElementById("map"), {
    zoom: 2, 
    center: { lat: 24.694545, lng: 17.181569 },
    styles: [
			    {
			        "featureType": "administrative",
			        "elementType": "labels.text.fill",
			        "stylers": [
			            {
			                "color": "#444444"
			            }
			        ]
			    },
			    {
			        "featureType": "landscape",
			        "elementType": "all",
			        "stylers": [
			            {
			                "color": "#f2f2f2"
			            }
			        ]
			    },
			    {
			        "featureType": "poi",
			        "elementType": "all",
			        "stylers": [
			            {
			                "visibility": "off"
			            }
			        ]
			    },
			    {
			        "featureType": "road",
			        "elementType": "all",
			        "stylers": [
			            {
			                "saturation": -100
			            },
			            {
			                "lightness": 45
			            }
			        ]
			    },
			    {
			        "featureType": "road.highway",
			        "elementType": "all",
			        "stylers": [
			            {
			                "visibility": "simplified"
			            }
			        ]
			    },
			    {
			        "featureType": "road.arterial",
			        "elementType": "labels.icon",
			        "stylers": [
			            {
			                "visibility": "off"
			            }
			        ]
			    },
			    {
			        "featureType": "transit",
			        "elementType": "all",
			        "stylers": [
			            {
			                "visibility": "off"
			            }
			        ]
			    },
			    {
			        "featureType": "water",
			        "elementType": "all",
			        "stylers": [
			            {
			                "color": "#46bcec"
			            },
			            {
			                "visibility": "on"
			            }
			        ]
			    },
			    {
			        "featureType": "water",
			        "elementType": "geometry.fill",
			        "stylers": [
			            {
			                "color": "#edfaff"
			            }
			        ]
			    }
			]
  });
  setMarkers(map);
}





// Data for the markers consisting of a name, a LatLng and a zIndex for the
// order in which these markers should display on top of each other.
const beaches = [
  ["Bondi Beach", -33.890542, 151.274856, 4],
  ["Coogee Beach", -33.923036, 151.259052, 5],
  ["Cronulla Beach", -34.028249, 151.157507, 3],
  ["Manly Beach", -33.80010128657071, 151.28747820854187, 2],
  ["Maroubra Beach", -33.950198, 151.259302, 1]
];

function setMarkers(map) {
  // Adds markers to the map.
  // Marker sizes are expressed as a Size of X,Y where the origin of the image
  // (0,0) is located in the top left of the image.
  // Origins, anchor positions and coordinates of the marker increase in the X
  // direction to the right and in the Y direction down.
  const image = {
    url:
      "/images/location.png",
    // This marker is 20 pixels wide by 32 pixels high.
    size: new google.maps.Size(20, 32),
    // The origin for this image is (0, 0).
    origin: new google.maps.Point(0, 0),
    // The anchor for this image is the base of the flagpole at (0, 32).
    anchor: new google.maps.Point(0, 32)
  };
  // Shapes define the clickable region of the icon. The type defines an HTML
  // <area> element 'poly' which traces out a polygon as a series of X,Y points.
  // The final coordinate closes the poly by connecting to the first coordinate.
  const shape = {
    coords: [1, 1, 1, 20, 18, 20, 18, 1],
    type: "poly"
  };

  for (let i = 0; i < beaches.length; i++) {
    const beach = beaches[i];
    const marker = new google.maps.Marker({
      position: { lat: beach[1], lng: beach[2] },
      map,
      icon: image,

      shape: shape,
      title: beach[0],
      zIndex: beach[3]
    });
    const infowindow = new google.maps.InfoWindow({
	    content: beach[0]
	});
	marker.addListener("click", () => {
	    infowindow.open(map, marker);
	});

  }
  

}
</script>
