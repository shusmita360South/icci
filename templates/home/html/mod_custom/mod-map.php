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
const locations = [
  ['<h6><a target="_blank" href="https://www.assocamerestero.it/ccie/italian-chamber-of-commerce-vietnam-icham">Italian Chamber of Commerce in Vietnam (ICHAM)</a></h6>',10.789469000000, 106.701201000000, 4],
  ['<h6><a href="https://www.assocamerestero.it/ccie/italian-chamber-of-commerce-vietnam-icham">Italian Chamber of Commerce in Vietnam (ICHAM)</a></h6>',10.499113000000, -66.850004000000,4],




['<h6><a href="https://www.assocamerestero.it/ccie/camara-de-comercio-venezolano-italiana">Cámara de Comercio Venezolano-Italiana</a></h6>',47.488088000000, 19.056753000000,4],



['<h6><a href="https://www.assocamerestero.it/ccie/camera-commercio-italiana-lungheria">Camera di Commercio Italiana per l’Ungheria</a></h6>',38.425332000000, 27.134624000000,4],


['<h6><a href="https://www.assocamerestero.it/ccie/camera-commercio-italiana-izmir">Camera di Commercio Italiana di Izmir</a></h6>',41.031203000000, 28.974580000000,4],



['<h6><a href="https://www.assocamerestero.it/ccie/camera-commercio-industria-italiana-turchia">Camera di Commercio e Industria Italiana in Turchia</a></h6>',36.842106000000, 10.180574000000,4],



['<h6><a href="https://www.assocamerestero.it/ccie/camera-tuniso-italiana-commercio-industria">Camera Tuniso-Italiana di Commercio e Industria</a></h6>',13.748695000000, 100.547805000000,4],




['<h6><a href="https://www.assocamerestero.it/ccie/thai-italian-chamber-of-commerce">Thai-Italian Chamber of Commerce</a></h6>',47.358134000000, 8.532337000000,4],


['<h6><a href="https://www.assocamerestero.it/ccie/camera-commercio-italiana-svizzera">Camera di Commercio Italiana per la Svizzera</a></h6>',59.337082000000, 18.079419000000,4],



['<h6><a href="https://www.assocamerestero.it/ccie/camera-commercio-italiana-svezia">Camera di Commercio Italiana per la Svezia</a></h6>',-26.189309000000, 28.124291000000,4],




['<h6><a href="https://www.assocamerestero.it/ccie/camera-commercio-italo-sudafricana">Camera di Commercio Italo-Sudafricana</a></h6>',40.754536000000, -73.978951000000,4],




['<h6><a href="https://www.assocamerestero.it/ccie/italy-america-chamber-of-commerce">Italy-America Chamber of Commerce</a></h6>',25.763889000000, -80.190608000000,4],




['<h6><a href="https://www.assocamerestero.it/ccie/italy-america-chamber-of-commerce-southeast-inc">Italy-America Chamber of Commerce Southeast, Inc.</a></h6>',34.087928000000, -118.344654000000,4],




['<h6><a href="https://www.assocamerestero.it/ccie/italy-america-chamber-of-commerce-west-inc">Italy-America Chamber of Commerce West, Inc.</a></h6>',29.811775000000, -95.421010000000,4],




['<h6><a href="https://www.assocamerestero.it/ccie/italy-america-chamber-of-commerce-of-texas-inc">Italy-America Chamber of Commerce of Texas, Inc.</a></h6>',41.899983000000, -87.880024000000,4],




['<h6><a href="https://www.assocamerestero.it/ccie/italian-american-chamber-of-commerce-midwest">Italian American Chamber of Commerce Midwest</a></h6>',41.397060000000, 2.152129000000,4],


['<h6><a href="https://www.assocamerestero.it/ccie/camera-commercio-italiana-barcellona">Camera di Commercio Italiana - Barcellona</a></h6>',48.144647000000, 17.106629000000,4],


['<h6><a href="https://www.assocamerestero.it/ccie/camera-commercio-italo-slovacca">Camera di Commercio Italo-Slovacca</a></h6>',1.278020000000, 103.847999000000,4],



['<h6><a href="https://www.assocamerestero.it/ccie/italian-chamber-of-commerce-singapore">Italian Chamber of Commerce in Singapore</a></h6>',44.804864000000, 20.465701000000,4],


['<h6><a href="https://www.assocamerestero.it/ccie/camera-commercio-italo-serba">Camera di Commercio Italo-Serba</a></h6>',55.718424000000, 37.581357000000,4],



['<h6><a href="https://www.assocamerestero.it/ccie/camera-commercio-italo-russa-ccir">Camera di Commercio Italo-Russa (CCIR)</a></h6>',44.422786000000, 26.109570000000,4],



['<h6><a href="https://www.assocamerestero.it/ccie/camera-commercio-italiana-romania">Camera di Commercio Italiana per la Romania</a></h6>',18.460472000000, -69.928353000000,4],




['<h6><a href="https://www.assocamerestero.it/ccie/camara-de-comercio-dominico-italiana">Camara de Comercio Dominico-Italiana</a></h6>',50.086535000000, 14.417541000000,4],



['<h6><a href="https://www.assocamerestero.it/ccie/camera-commercio-dellindustria-italo-ceca">Camera di Commercio e dell&#039;Industria Italo-Ceca</a></h6>',51.514738000000, -0.142469000000,4],




['<h6><a href="https://www.assocamerestero.it/ccie/the-italian-chamber-of-commerce-and-industry-for-the-united-kingdom">The Italian Chamber of Commerce and Industry for the United Kingdom</a></h6>',25.322113000000, 51.533801000000,4],



['<h6><a href="https://www.assocamerestero.it/ccie/ibcq-camera-commercio-italiana-qatar">IBCQ - Camera di Commercio Italiana in Qatar</a></h6>',38.736667000000, -9.148618000000,4],



['<h6><a href="https://www.assocamerestero.it/ccie/camera-commercio-italiana-portogallo">Camera di Commercio Italiana per il Portogallo</a></h6>',52.230912000000, 20.989032000000,4],




['<h6><a href="https://www.assocamerestero.it/ccie/camera-commercio-dellindustria-italiana-polonia">Camera di Commercio e dell&#039;Industria Italiana in Polonia</a></h6>',-12.133620000000, -77.018460000000,4],




['<h6><a href="https://www.assocamerestero.it/ccie/camera-commercio-italiana-del-peru">Camera di Commercio Italiana del Perù</a></h6>',-25.300080000000, -57.627124000000,4],




['<h6><a href="https://www.assocamerestero.it/ccie/camara-de-comercio-italo-paraguaya">Camara de Comercio Italo-Paraguaya</a></h6>',-25.961135000000, 32.581917000000,4],




['<h6><a href="https://www.assocamerestero.it/ccie/camara-de-comercio-mocambique-italia">Câmara de Comércio Moçambique-Itália</a></h6>',52.382247000000, 4.894525000000,4],


['<h6><a href="https://www.assocamerestero.it/ccie/camera-commercio-italiana-lolanda">Camera di Commercio Italiana per l’Olanda</a></h6>',47.016479000000, 28.836586000000,4],



['<h6><a href="https://www.assocamerestero.it/ccie/camera-commercio-italiana-moldova">Camera di Commercio Italiana in Moldova</a></h6>',19.432028000000, -99.202881000000,4],




['<h6><a href="https://www.assocamerestero.it/ccie/camara-de-comercio-italiana-en-mexico-ac">Cámara de Comercio Italiana en Mexico, A.C.</a></h6>',33.588545000000, -7.630041000000,4],



['<h6><a href="https://www.assocamerestero.it/ccie/camera-commercio-italiana-marocco">Camera di Commercio Italiana in Marocco</a></h6>',35.900154000000, 14.493219000000,4],



['<h6><a href="https://www.assocamerestero.it/ccie/maltese-italian-chamber-of-commerce">Maltese-Italian Chamber of Commerce</a></h6>',3.152704000000, 101.712018000000,4],



['<h6><a href="https://www.assocamerestero.it/ccie/italy-malaysia-business-association-imba">Italy Malaysia Business Association - IMBA</a></h6>',49.609189000000, 6.119304000000,4],


['<h6><a href="https://www.assocamerestero.it/ccie/camera-commercio-italo-lussemburghese-asbl">Camera di Commercio Italo-Lussemburghese a.s.b.l.</a></h6>',32.063027000000, 34.763153000000,4],



['<h6><a href="https://www.assocamerestero.it/ccie/camera-commercio-industria-israel-italia">Camera di Commercio e Industria Israel-Italia</a></h6>',19.115832000000, 72.854468000000,4],



['<h6><a href="https://www.assocamerestero.it/ccie/iicci-the-indo-italian-chamber-of-commerce-and-industry">IICCI - The Indo-Italian Chamber of Commerce and Industry</a></h6>',14.581728000000, -90.516638000000,4],




['<h6><a href="https://www.assocamerestero.it/ccie/camara-de-comercio-industria-italo-guatemalteca">Camara de Comercio e Industria Italo-Guatemalteca</a></h6>',40.685194000000, 22.857260000000,4],



['<h6><a href="https://www.assocamerestero.it/ccie/camera-commercio-italo-ellenica-salonicco">Camera di Commercio Italo-Ellenica di Salonicco</a></h6>',37.982901000000, 23.734897000000,4],



['<h6><a href="https://www.assocamerestero.it/ccie/camera-commercio-italo-ellenica-atene">Camera di Commercio Italo-Ellenica - Atene</a></h6>',35.646491000000, 139.740416000000,4],




['<h6><a href="https://www.assocamerestero.it/ccie/camera-commercio-italiana-giappone">Camera di Commercio Italiana in Giappone</a></h6>',48.129969000000, 11.530357000000,4],


['<h6><a href="https://www.assocamerestero.it/ccie/camera-commercio-italo-tedesca">Camera di Commercio Italo-Tedesca</a></h6>',50.117183000000, 8.658368000000,4],


['<h6><a href="https://www.assocamerestero.it/ccie/camera-commercio-italiana-germania">Camera di Commercio Italiana per la Germania</a></h6>',43.703503000000, 7.276082000000,4]



['<h6><a href="https://www.assocamerestero.it/ccie/chambre-de-commerce-italienne-nice-sophia-antipolis-cote-dazur">Chambre de Commerce Italienne Nice, Sophia-Antipolis, Cote d&#039;Azur</a></h6>',45.767292000000, 4.835336000000,4],

['<h6><a href="https://www.assocamerestero.it/ccie/camera-commercio-italiana-lione">Camera di Commercio Italiana di Lione</a></h6>',60.216114000000, 24.874898000000,4],



['<h6><a href="https://www.assocamerestero.it/ccie/camera-commercio-italiana-finlandia">Camera di Commercio Italiana per la Finlandia</a></h6>',14.548417000000, 121.026250000000,4],




['<h6><a href="https://www.assocamerestero.it/ccie/italian-chamber-of-commerce-the-philippines-inc">Italian Chamber of Commerce in the Philippines Inc.</a></h6>',25.200466000000, 55.269213000000,4],



['<h6><a href="https://www.assocamerestero.it/ccie/camera-commercio-italiana-negli-emirati-arabi-uniti">Camera di Commercio Italiana negli Emirati Arabi Uniti</a></h6>',30.050199000000, 31.243971000000,4],



['<h6><a href="https://www.assocamerestero.it/ccie/camera-commercio-italiana-legitto">Camera di Commercio Italiana per l’Egitto</a></h6>',-0.192840000000, -78.490922000000,4],




['<h6><a href="https://www.assocamerestero.it/ccie/camara-de-comercio-italiana-del-ecuador">Camara de Comercio Italiana del Ecuador</a></h6>',55.672902000000, 12.574484000000,4],



['<h6><a href="https://www.assocamerestero.it/ccie/camera-commercio-italiana-danimarca">Camera di Commercio italiana in Danimarca</a></h6>',45.813268000000, 15.976020000000,4],


['<h6><a href="https://www.assocamerestero.it/ccie/camera-commercio-italo-croata">Camera di commercio italo croata</a></h6>',9.922778000000, -84.136099000000,4],



['<h6><a href="https://www.assocamerestero.it/ccie/camara-de-industria-y-comercio-italo-costarricense">Cámara de Industria y Comercio Ítalo-Costarricense</a></h6>',36.108692000000, 127.488058000000,4],




['<h6><a href="https://www.assocamerestero.it/ccie/italian-chamber-of-commerce-korea">Italian Chamber of Commerce in Korea</a></h6>',4.663481000000, -74.056337000000,4],



['<h6><a href="https://www.assocamerestero.it/ccie/camera-commercio-italiana-colombia">Camera di commercio italiana per la Colombia</a></h6>',22.284594000000, 114.153435000000,4],




['<h6><a href="https://www.assocamerestero.it/ccie/italian-chamber-of-commerce-hong-kong-and-macao">Italian Chamber of Commerce in Hong Kong and Macao</a></h6>',39.934778000000, 116.458969000000,4],




['<h6><a href="https://www.assocamerestero.it/ccie/camera-commercio-italiana-cina">Camera di Commercio Italiana in Cina</a></h6>',-33.418001000000, -70.603201000000,4],




['<h6><a href="https://www.assocamerestero.it/ccie/camara-de-comercio-italiana-de-chile-ag">Cámara de Comercio Italiana de Chile A.G.</a></h6>',49.285353000000, -123.114238000000,4],




['<h6><a href="https://www.assocamerestero.it/ccie/italian-chamber-of-commerce-canada-west">Italian Chamber of Commerce in Canada - West</a></h6>',43.655125000000, -79.414830000000,4],




['<h6><a href="https://www.assocamerestero.it/ccie/camera-commercio-italiana-dellontario">Camera di Commercio Italiana dell&#039;Ontario</a></h6>',45.505721000000, -73.572549000000,4],




['<h6><a href="https://www.assocamerestero.it/ccie/camera-commercio-italiana-canada">Camera di Commercio Italiana in Canada</a></h6>',42.698843000000, 23.322875000000,4],



['<h6><a href="https://www.assocamerestero.it/ccie/camera-commercio-italiana-bulgaria">Camera di Commercio Italiana in Bulgaria</a></h6>',-23.550421000000, -46.660034000000,4],





['<h6><a href="https://www.assocamerestero.it/ccie/camara-italo-brasileira-de-comercio-industria-agricultura-italcam">Câmara Ítalo-Brasileira de Comercio, Indústria e Agricultura - ITALCAM</a></h6>',-22.910465000000, -43.174078000000,4],





['<h6><a href="https://www.assocamerestero.it/ccie/camera-italo-brasiliana-commercio-industria-rio-de-janeiro">Camera Italo-Brasiliana di Commercio e Industria di Rio de Janeiro</a></h6>',-30.059882000000, -51.228663000000,4],




['<h6><a href="https://www.assocamerestero.it/ccie/camera-commercio-italiana-rio-grande-do-sul-brasile">Camera di Commercio Italiana Rio Grande do Sul - Brasile</a></h6>',-27.593247000000, -48.519486000000,4],




['<h6><a href="https://www.assocamerestero.it/ccie/camera-italiana-commercio-industria-sc">Camera Italiana Commercio e Industria - SC</a></h6>',-25.420209000000, -49.243455000000,4],





['<h6><a href="https://www.assocamerestero.it/ccie/camera-italo-brasiliana-commercio-industria-parana-italocam">Camera Italo-Brasiliana di Commercio e Industria di Parana (Italocam)</a></h6>',-19.939220000000, -43.928800000000,4],




['<h6><a href="https://www.assocamerestero.it/ccie/camera-italo-brasiliana-commercio-industria-ed-agricoltura-minas-gerais">Camera Italo-brasiliana di Commercio, Industria ed Agricoltura di Minas Gerais</a></h6>',50.832600000000, 4.347975000000,4],

['<h6><a href="https://www.assocamerestero.it/ccie/camera-commercio-belgo-italiana">Camera di Commercio Belgo-Italiana</a></h6>',-33.874386000000, 151.218565000000,4],





['<h6><a href="https://www.assocamerestero.it/ccie/italian-chamber-of-commerce-and-industry-australia-inc">Italian Chamber of Commerce and Industry in Australia inc.</a></h6>',-31.953079000000, 115.852655000000,4],





['<h6><a href="https://www.assocamerestero.it/ccie/italian-chamber-of-commerce-industry-australia-perth-inc">Italian Chamber of Commerce &amp; Industry in Australia - Perth Inc.</a></h6>',-37.798911000000, 144.968285000000,4],





['<h6><a href="https://www.assocamerestero.it/ccie/italian-chamber-of-commerce-and-industry-australia-melbourne-inc">Italian Chamber of Commerce and Industry in Australia - Melbourne Inc.</a></h6>',-32.944438000000, -60.648758000000,4],




['<h6><a href="https://www.assocamerestero.it/ccie/camara-de-comercio-italiana-de-rosario">Cámara de Comercio Italiana de Rosario</a></h6>',-32.887200000000, -68.852332000000,4],




['<h6><a href="https://www.assocamerestero.it/ccie/camera-commercio-italiana-mendoza">Camera di Commercio Italiana di Mendoza</a></h6>',-34.596708000000, -58.383266000000,4],




['<h6><a href="https://www.assocamerestero.it/ccie/camera-commercio-italiana-nella-repubblica-argentina">Camera di Commercio Italiana nella Repubblica Argentina</a></h6>',41.328271000000, 19.817970000000,4],



['<h6><a href="https://www.assocamerestero.it/ccie/camera-commercio-italiana-albania">Camera di Commercio Italiana in Albania</a></h6>',43.298493000000, 5.374340000000,4],


['<h6><a href="https://www.assocamerestero.it/ccie/camera-commercio-italiana-francia-marsiglia">Camera di Commercio Italiana per la Francia di Marsiglia</a></h6>',40.442820000000, -3.695728000000,4],



['<h6><a href="https://www.assocamerestero.it/ccie/camera-commercio-industria-italiana-spagna">Camera di Commercio e Industria Italiana per la Spagna</a></h6>',-27.517592000000, 153.078220000000,4],


['<h6><a target="_blank" href="https://www.assocamerestero.it/ccie/italian-chamber-of-commerce-and-industry-australia-icci-queensland-inc">Italian Chamber of Commerce and Industry in Australia (ICCI, Queensland) Inc.</a></h6>',-27.517592000000, 153.078220000000, 4]



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

  for (let i = 0; i < locations.length; i++) {
    const location = locations[i];
    const marker = new google.maps.Marker({
      position: { lat: location[1], lng: location[2] },
      map,
      icon: image,

      shape: shape,
      title: location[0],
      zIndex: location[3]
    });
    const infowindow = new google.maps.InfoWindow({
	    content: location[0]
	});
	marker.addListener("click", () => {
	    infowindow.open(map, marker);
	});

  }
  

}
</script>

