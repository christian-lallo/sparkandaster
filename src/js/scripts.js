
//import axios from 'axios';
import particleNetwork from './ParticleNetwork';
import waypoint from './vendor/noframework.waypoints.js';
import fullpage from 'fullpage.js'
//import Reveal from 'reveal.js'

document.addEventListener("DOMContentLoaded", function(event) {


/**
	 * Mapbox
	 */


	 var fly = function(location) {
	 		map.flyTo(chapters[location]);
		}

		 var chapters = {
		    'detroit': {
		        center: [-83.033898, 42.329927],
		        bearing: 90,
		        zoom: 16,
		        pitch: 60,
		        speed: 1
		    },
		    'baltimore': {
		        bearing: 280,
		        center: [-76.6007238, 39.2824631],
		        zoom: 15.5,
		        speed: 1,
		        pitch: 70
		    },

		    'city-garage': {
		        bearing: 280,
		        center: [-76.6163805, 39.2627779],
		        zoom: 15.5,
		        speed: 1,
		        pitch: 70
		    },
		    
		    'union': {
		        bearing: 280,
		        center: [-76.6423730521845, 39.3320049563208],
		        zoom: 15.5,
		        speed: 1,
		        pitch: 70
		    },
		    
		    'katie': {
		        bearing: 90,
		        center: [-76.443405, 39.408139],
		        zoom: 16.3,
		        speed: 0.2
		     }
		    
		};



		var flyTrigger = document.querySelector('#iphone');
		
		flyTrigger.addEventListener('click', ( event )  => {

			fly('city-garage');
		});



	//Setup the Mapbox map instance and add markers
	
    var token = 'pk.eyJ1Ijoic3BhcmstYW5kLWFzdGVyIiwiYSI6ImNqM3E2ZWd3dzAwd3AycWxqdHF0bmdycXYifQ.k6dyMCh8jd6aXexneVQDfQ';

      var center = [-85, 40];

      mapboxgl.accessToken = token;
      
      var map = new mapboxgl.Map({
          container: 'map',
          style: 'mapbox://styles/spark-and-aster/cjkggw2lq2wkk2suv774rd9td', 
          center: center, 
          zoom: 2.5,
          bearing:0,
          pitch: 0 
      });
      
      
      
		var geojson = {
		  type: 'FeatureCollection',
		  features: [{
		    type: 'Feature',
		    geometry: {
		      type: 'Point',
		      coordinates: [-76.6163805, 39.2627779]
		    },
		    properties: {
		      title: 'Spark + Aster',
		      description: 'Baltimore'
		    }
		  },
		  {
		    type: 'Feature',
		    geometry: {
		      type: 'Point',
		      coordinates: [-83.033898, 42.329927]
		    },
		    properties: {
		      title: 'Spark + Aster',
		      description: 'Detroit'
		    }
		  }]
		};      
		
		// add markers to map
		geojson.features.forEach(function(marker) {
		
		  // create a HTML element for each feature
		  var el = document.createElement('div');
		  el.className = 'marker';
		  		
		  // make a marker for each feature and add to the map
		  new mapboxgl.Marker(el)
		  .setLngLat(marker.geometry.coordinates)
		  .addTo(map);

		  new mapboxgl.Marker(el)
		  .setLngLat(marker.geometry.coordinates)
		  .setPopup(new mapboxgl.Popup({ offset: 25 }) // add popups
		  .setHTML('<h3>' + marker.properties.title + '</h3><p>' + marker.properties.description + '</p>'))
		  .addTo(map);
		
		
		});      
		

      var logEvent = function(e) {
        console.log(e.type, e);
      };

      map.on("move", logEvent); // ie -- movestart moveend move

      window.runA = function() {
        map.easeTo({pitch: 50, bearing: 240, duration: 9500, zoom: 14});
      };

      window.resetA = function() { 
        map.easeTo({pitch: 0, bearing: 0, duration: 500, zoom: 8});
      };
  
  
  
  
	  // Stella animations - now triggered by scroll

	  	// Helper function to transform Stella based on value name
		function transformStella(stellaForm) {
			let stellaNodes = document.querySelectorAll('.node-stella');
			let nodeCount = stellaNodes.length;

			// First remove any existing stella icon classes
			for( let i = 0; i < nodeCount; i++ ) {
				stellaNodes[i].classList.remove('humanity','connection','inspiration','empathy','love');
			}

			// Now add the new icon class (if provided)
			if (stellaForm) {
				for( let i = 0; i < nodeCount; i++ ) {
					let element = stellaNodes[i];
					console.log("adding class "+stellaForm+" to element "+element.getAttribute('id'));
					element.classList.add(stellaForm);
				}
			}
		}


		// reset 
/*
		let resetStella = document.querySelector('#foo');
		resetStella.addEventListener('click', (event) => {
				let stellaNodes = document.querySelectorAll('.node-stella');
				let nodeCount = stellaNodes.length;
				for( let i = 0; i < nodeCount; i++ ) {					  		
				    stellaNodes[i].classList.remove('humanity','connection','inspiration','empathy');
				  }
		});
*/
  


	  // Fullpage

		new fullpage('#fullpage', {
			//options here
			autoScrolling:true,
			scrollHorizontally: false,
			licenseKey: 'OPEN-SOURCE-GPLV3-LICENSE',
			anchors: [
				'video',
				'we-understand',
				'what-we-do',
				'whats-in-a-name',
				'humanity',
				'empathy',
				'love',
				'connection',
				'inspiration',
				'ready-to-connect'
				],


			afterLoad: function(origin, destination, direction){

					let sectionEl = destination.item;
					let anchor = sectionEl.getAttribute('data-anchor');
					console.log('Section loaded:', anchor);

					// Trigger Stella transformations based on section
					switch(anchor) {
						case 'whats-in-a-name':
							transformStella(null); // Default/logo form
							break;
						case 'humanity':
							transformStella('humanity');
							break;
						case 'empathy':
							transformStella('empathy');
							break;
						case 'love':
							transformStella('love');
							break;
						case 'connection':
							transformStella('connection');
							break;
						case 'inspiration':
							transformStella('inspiration');
							break;
					}

					// Animate waypoint elements
					let sectionWaypoints = sectionEl.querySelectorAll('.waypoint-anim');

					for( let i = 0, len = sectionWaypoints.length; i < len; i++ ) {
					 	let element = sectionWaypoints[i];
					  	element.classList.add('anim--in');
					};

			},

			onLeave: function(origin, destination, direction){
				
					
					let sectionEl = origin.item;
					console.log(sectionEl.getAttribute('data-anchor'));

					let sectionWaypoints = sectionEl.querySelectorAll('.waypoint-anim');
				
					for( let i = 0, len = sectionWaypoints.length; i < len; i++ ) {					  		
					 	let element = sectionWaypoints[i];
					  	element.classList.remove('anim--in');
					  	element.classList.remove('humanity');
					};
		
			}
			
			
		});
		
		//methods
		//fullpage_api.setAllowScrolling(false);




	/**
	 * Captions
	 */
	let captions = document.querySelectorAll('.modifier--text-split');

	for( let i = 0, len = captions.length; i < len; ++i ) {

		let text = captions[i].textContent;

		captions[i].innerHTML = '';

		for( let j = 0; j < text.length; ++j ) {

			let isHidden = ( /\S/.test(text[j]) ) ? false : true;

			let d = ( j % 2 == 0 ) ? 3 : 5;
			captions[i].innerHTML += '<div><span class="delay-pt' + d + " " + ((isHidden) ? "is-hidden" : "") + '">' + (( !isHidden ) ? text[j] : '_') + '</span></div>';
		}
	}
	
	/**
	 * Scroll Reveal
	 */
	// get all waypoints in dom
	let allWaypoints = document.querySelectorAll('.waypoint-anim');

	for( let i = 0, len = allWaypoints.length; i < len; i++ ) {
	  		
	 	let element 		 = allWaypoints[i],
	 	    animationClass = element.getAttribute('data-animation'), // get the data-anim type or set the default
	 	    animTarget  	 = element.getAttribute('data-anim_target'), // if another el triggers the anim
	  		 offset  	    = element.getAttribute('data-offset'), // offset when anim triggers
	  		 group 			= element.getAttribute('data-group');

	  	// set anim class
	  	element.classList.add(animationClass, 'anim--pre');

	  	// define trigger point
		let trigger     = ( animTarget != null ) ? animTarget : element;
		let animOffset  = ( offset != null ) ? offset : '80%';
		let animGroup 		= ( group != null ) ? group: 'default';

	  	new Waypoint({
			element: trigger,
			group: animGroup,
	  	  	handler: function( __direction ) {

	  	  		// animate the el in
	  	  		element.classList.add('anim--in');
	  	  	},
			offset: animOffset
	  	});
	};

	// Particles
	
	const container = document.querySelector('#particles');

	if( container != undefined ) {

		const options = {
		      velocity: 0,
		      background: '#F4F4F4',
		      interactive: true,
		      particleImgs: [
		      	'../dist/img/icons/icon-artistry.png',
		      	'../dist/img/icons/icon-care.png',
		      	'../dist/img/icons/icon-connection.png',
		      	'../dist/img/icons/icon-empathy.png',
		      	'../dist/img/icons/icon-guidance.png',
		      	'../dist/img/icons/icon-inspiration.png',
		      	'../dist/img/icons/icon-joy.png',
		      	'../dist/img/icons/icon-love.png'

		      ]
		   }
		 
		new particleNetwork(container, options);
	} 
	
	/**
	 * Lightbox Actions
	 */
	let lightbox = document.querySelector('#lightbox');
	let lightboxCloseBtn = lightbox.querySelector('.btn--close');

	let clickables = document.querySelectorAll('.is-clickable');

	for( let i = 0; i < clickables.length; ++i ) {

		clickables[i].addEventListener('click', ( event ) => {
			
			lightbox.classList.add('is-active');

			axios.get( '/views/modules/modal-video.php', {
					params: { 
						vid: event.target.getAttribute('data-vid')
					}
				})
				.then( function( response ) {
				   
				   document.body.insertAdjacentHTML('afterbegin', response.data);

				})
				.catch(function( error ) {

				});
		});
	}

	/**
	 * Lightbox Close Button
	 */
	lightboxCloseBtn.addEventListener('click', ( event ) => {

		lightbox.classList.add('is-inactive');

		let modal = document.querySelector('.modal');

		if( modal != undefined ) {
			modal.classList.add('is-inactive');
		}

		window.setTimeout( () => {

			lightbox.classList.remove('is-active');
			lightbox.classList.remove('is-inactive');

			if( modal != undefined ) {
				modal.remove();
			}

		}, 400 );

	});

	/**
	 * Nav
	 */
	let siteHeader = document.querySelector('.site-header');
	let nav = document.querySelector('nav');
	let navBtn = document.querySelector('.btn--nav');

/*
	navBtn.addEventListener('click', ( event ) => {

		if( !nav.classList.contains('is-active') ) {

			siteHeader.classList.add('is-active');
			lightbox.classList.add('for-nav');
			lightbox.classList.add('is-active');
			nav.classList.add('is-active');

		}else{

			siteHeader.classList.remove('is-active');

			lightbox.classList.add('is-inactive');
			nav.classList.add('is-inactive');

			window.setTimeout( () => {

				lightbox.classList.remove('is-active');
				lightbox.classList.remove('is-inactive');
				lightbox.classList.remove('for-nav');
				nav.classList.remove('is-active');
				nav.classList.remove('is-inactive');

			}, 400 );
		}

	});
*/

	/**
	 * Canvas
	 */
	 
	 		
		

		
});