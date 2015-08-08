var map4000, service4000, infoWindow4000,map2;
var markers = [];
var currentImg; 
var totalImg; 
var autocomplete;
var MARKER_PATH = imgdefault;
var hostnameRegexp = new RegExp('^https?://.+?/');
var zoomm=14;
var refreshIntervalId; 
 
 
 
function resizeMap4000() {
	 	var myMap = document.getElementById('map-canvas4000');
     	var btnResize = document.getElementById('btnResize4000');
     	var rightColumnDiv = document.getElementById('rightColumnDiv');
 
     	if(fsmode=="fullscreen") {
			 
 			myMap.style.top = "0";
			myMap.style.left = "0";
			myMap.style.position = "fixed";
			myMap.style.height = "100%";
			myMap.style.width = "100%";
			 google.maps.event.trigger(map4000, 'resize');
			rightColumnDiv.style.right = "0";
			rightColumnDiv.style.top = "0";
			 
			rightColumnDiv.style.position = "fixed";
			
		 
			fsmode = "normal view";
			btnResize.src=img2;
			 map4000.setCenter(new google.maps.LatLng(lat4000,lng4000));
     	} else {
 	 
			btnResize.src=img1;
			myMap.style.top = "";
			myMap.style.left = "";
			myMap.style.paddingBottom = "26.25%";
			myMap.style.overflow = "hidden";
			myMap.style.position = "relative";
			myMap.style.height = "400px";
			myMap.style.width = "100%";
			 
			rightColumnDiv.style.right = "";
			rightColumnDiv.style.top = "";
			 
			rightColumnDiv.style.position = "absolute";
			google.maps.event.trigger(map4000, 'resize');
			fsmode = "fullscreen";
			 map4000.setCenter(new google.maps.LatLng(lat4000,lng4000));
     	}
   
            
   	 
	 
	}
 
/**
 	* Function for creating google maps with hotels 
 	*/	
function setMap4000(lat,lng) {
	 
	  var llc = new google.maps.LatLng(lat,lng);
                
	  map4000 = new google.maps.Map(document.getElementById('map-canvas4000'), {
		center: llc,
		zoom: zoomm
	  });
	   
	   var request4000 = {
		location: llc,
		radius: 3500,
		types: typesb
	  };
	 
  		infowindow4000 = new google.maps.InfoWindow({
		  		content: document.getElementById('info-content4000')
		  	});
		  	google.maps.event.addListener(infowindow4000,'closeclick',function(){
		  clearInterval(refreshIntervalId);
		   
		});
	  		service4000 = new google.maps.places.PlacesService(map4000);
	  		service4000.nearbySearch(request4000, callback4000);
		 
	       
	    google.maps.event.addListener(map4000,'dragend',function() {
			 lat = map4000.getCenter().lat();
			 lng = map4000.getCenter().lng();
			  var llc = new google.maps.LatLng(lat,lng);
			 var request4000 = {
				location: llc,
				radius: 3500,
				types: typesb
			  };
			var  infowindow4000 = new google.maps.InfoWindow({
		  		content: document.getElementById('info-content4000')
		  	});
		  	
		  	google.maps.event.addListener(infowindow4000,'closeclick',function(){
		  clearInterval(refreshIntervalId);
		 
		});
	  		service4000 = new google.maps.places.PlacesService(map4000);
	  		service4000.nearbySearch(request4000, callback4000);
			  
		});
	   
	 if (search!="") { 
	  
	 
	
		document.getElementById('searchcontent').style.display="inline"
 
	 	var input = document.getElementById('pac-input4000');

	  	var types = document.getElementById('type-selector');
	  	
	   //map4000.controls[google.maps.ControlPosition.TOP_LEFT].push(document.getElementById('btnResize4000'));
map4000.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
	  	var autocomplete = new google.maps.places.Autocomplete(input);
	  	autocomplete.bindTo('bounds', map4000);
	 
	  	service4000 = new google.maps.places.PlacesService(map4000);
	  	service4000.nearbySearch(request4000, callback4000);
 
		

		google.maps.event.addListener(autocomplete, 'place_changed', function() {
		 
	
			var place = autocomplete.getPlace();
			if (!place.geometry) {
			  window.alert("Autocomplete's returned place contains no geometry");
			  return;
			}

			 
			  map4000.setCenter(place.geometry.location);
			  map4000.setZoom(zoomm);   
			   var marker = new google.maps.Marker({
				position: place.geometry.location,
				map: map4000
				}); 
  
	 
			var request4000 = {
				location: place.geometry.location,
				radius: 3500,
				types: typesb
			  };
			var  infowindow4000 = new google.maps.InfoWindow({
		  		content: document.getElementById('info-content4000')
		  	});
		  	 google.maps.event.addListener(infowindow4000,'closeclick',function(){
		  clearInterval(refreshIntervalId);
		  
		});
	  		service4000 = new google.maps.places.PlacesService(map4000);
	  		service4000.nearbySearch(request4000, callback4000);
	  		 
			 
	  });
	 }  
	  		 
	}



/**
 	* Function for initializing google maps 
 	*/	
function initialize4000() {
 
  if (address4000!='' && lat4000=='' && lng4000=='') {
   
 	var geocoder = new google.maps.Geocoder();
  
 	geocoder.geocode({ 'address': address4000 }, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                  
                    lat4000 = results[0].geometry.location.lat();
                     lng4000 = results[0].geometry.location.lng();
 
 					 setMap4000(lat4000,lng4000);
                } else {
                    alert("Request failed.")
                }
            });
            
           
  } else {
 
  	setMap4000(lat4000,lng4000);
  }
  
}
 
/**
 	* Function for creating google maps with hotels
 	*/	
	function callback4000(results, status) {
 
	  if (status == google.maps.places.PlacesServiceStatus.OK) {
			clearResults4000();
		   clearMarkers4000();
		for (var i = 0; i < results.length; i++) {
		//console.log(results[i]);
	  var markerLetter = String.fromCharCode('A'.charCodeAt(0) + i);
			var photos = results[i].photos;
			var markerIcon = MARKER_PATH;
			if (photos) {
				markerIcon = photos[0].getUrl({'maxWidth': 50, 'minHeight': 50});
 
			}
		
			/* 
			markers[i] = new google.maps.Marker({
			  position: results[i].geometry.location,
			  animation: google.maps.Animation.DROP,
			  icon: markerIcon
			});
			*/
			var ido = Math.floor((Math.random()*40000)+1);
			 
		 
			markers[i] = new google.maps.Marker({
				  position: results[i].geometry.location,
				  map: map4000,
				  icon: imgdefault
			  });
	   
			markers[i].placeResult = results[i];
		 
			google.maps.event.addListener(markers[i], 'click', showInfoWindow4000);
			setTimeout(dropMarker4000(i), i * 100);
	 
		}
		var t=i+1;
		var center = map4000.getCenter();
		var geocoder = new google.maps.Geocoder();
		geocoder.geocode({"latLng":center},function(data,status){
 			if(status == google.maps.GeocoderStatus.OK){
 				var add = data[1].formatted_address; //this is the full address
				 
				markers[t] = new google.maps.Marker({
						position: center,
						map: map4000,
						 title: add
  					});
  					markers[t].placeResult = null;
			}
		})
		
		 
				 
  		
  		 
		 addResult4000();
	  	}
	}
	
	 
/**
 	* Function for clearing results on listing
 	*/		
	
	function clearResults4000() {
	  var results = document.getElementById('results');
	  while (results.childNodes[0]) {
		results.removeChild(results.childNodes[0]);
	  }
	}
	
/**
 	* Function for clearing markers on map
 	*/		
	function clearMarkers4000() {
	  for (var i = 0; i < markers.length; i++) {
		if (markers[i]) {
		  markers[i].setMap(null);
		}
	  }
	  markers = [];
	}
/**
 	* Function for dropping markers on map 
 	*/		
	function dropMarker4000(i) {
	  return function() {
		markers[i].setMap(map4000);
	  };
	}

 
/**
 	* Change row color background for result table
 	*/	 
	function clickRow4000(id){
        google.maps.event.trigger(markers[id], 'click');
        document.getElementById("idrow"+id).style.backgroundColor="#F5F6CE";
    }
    
    /**
 	* Function for adding results on listing
 	*/	
	function addResult4000() {
	  var i;
	  var distance;
	  var arrayMarker = new Array();
	  var center = map4000.getCenter();
	   for (i in markers) {
	  	 if (markers[i]) {
	   		if (markers[i].placeResult) {
				var rs = markers[i].placeResult;
				var markerLatLng = rs.geometry.location;
				var distance = parseInt(google.maps.geometry.spherical.computeDistanceBetween(center, markerLatLng)); 
				arrayMarker.push([[distance,markers[i],i]]);
			}	
			}
	   }
	 
	
		 var col=0;
		 var asc=1;
		  arrayMarker.sort(function(a, b){
			return (parseInt(a[col]) == parseInt(b[col])) ? 0 : ((parseInt(a[col]) > parseInt(b[col])) ? asc : -1*asc);
		});
     
	  var results = document.getElementById('results');
	 var j;
	 var i;
     for (j in arrayMarker) {
     
 		var distance = arrayMarker[j][0][0];
 		var marker = arrayMarker[j][0][1];
 		var i = arrayMarker[j][0][2];
	 	var rs = marker.placeResult;
	 	 
		var tr = document.createElement('tr');
		tr.setAttribute("id","idrow"+i);
	  	tr.style.backgroundColor = (j % 2 == 0 ? '#F0F0F0' : '#FFFFFF');
 	  	var nameTd = document.createElement('td');
	    var titled = document.createElement('div');
	    
	    titled.innerHTML='<span style="cursor:pointer; color: #cf4d35;" onclick="clickRow4000('+i+')"><b >' + rs.name;
	    if (rs.photos) {
	    	 titled.innerHTML+= "&nbsp;&#128247;";
	    }
	    titled.innerHTML+= '</b></span>';
	    
	    nameTd.appendChild(titled);
	      
	       var texth = '';
		  var imgh = "";
		  var ratingHtml = '';
		  if (rs.rating) {
	   
			for (var h = 0; h < 5; h++) {
			  if (rs.rating < (h + 0.5)) {
				ratingHtml += '&#10025;';
			  } else {
				ratingHtml += '&#10029;';
			  }
		  
			}
			  texth +=  ratingHtml+"<br>";
		  }
		  texth+=rs.vicinity+"<br>";
	   texth+=distance +' mt.';
	    texth+='<div id="spanwebid'+rs.place_id+'"></div>';
	  	texth+='<div id="spanphoneid'+rs.place_id+'"></div>';	 
	 
	   var bt2 = document.createElement('span'); 
	       bt2.innerHTML = texth;
	  
	  //var name = document.innerHTML(text);
	   nameTd.appendChild(bt2);
 
     

	  tr.appendChild(nameTd);
	 
	  results.appendChild(tr);
	 	 
		
	  }
	
 
	}
	
	
	
	 
	 
	
	/**
 	* show infowindow on map
 	*/	
	function showInfoWindow4000() {
		if (document.getElementById('info-content4000') && document.getElementById('info-content4000').style.display=="none") {
			document.getElementById('info-content4000').style.display = 'block';
		}

	  var marker = this;
	  service4000.getDetails({placeId: marker.placeResult.place_id},
		  function(place, status) {
		   
			if (status != google.maps.places.PlacesServiceStatus.OK) {
			  return;
			}
			infowindow4000.open(map4000, marker);
			buildIWContent4000(place);
		  });
	}
	
	/**
 	* Function for sliding images on infowindow
 	*/	
	function viewImg4000() {
		for(var i=0;i<totalImg;i++) {
			document.getElementById("imgslider"+i).style.display="none";
		}
		if (currentImg>=totalImg) {
			currentImg=0;
		}
		document.getElementById("imgslider"+currentImg).style.display="block";
		currentImg++;
	 
	 
		 
	}
	/**
 	* Function for loading the place information into the HTML elements used by the info window.
 	*/	
	 
	function buildIWContent4000(place) {
		 var html='';
		  clearInterval(refreshIntervalId);
		 currentImg=0;
		 totalImg=0;
	 var info = "";
		if (place.photos) {
	 	     var ff="";
			 for(var v=0;v<place.photos.length;v++) {
				 ff = place.photos[v].getUrl({'maxWidth': 200, 'maxHeight': 200});
			 
				 if (ff!="") {
					html += '<img class="mapsslider" style="display:none;" id="imgslider'+v+'" width="200px" src="'+ff+'"/>';
				 }
			 }	
			totalImg = v;	
		}
		if (html!="") {
			document.getElementById('iw-image').style.display = '';
			document.getElementById('slider').innerHTML = html;
		   	viewImg4000();
			  refreshIntervalId = setInterval(function() { viewImg4000();}, 4000);
			 
		} else {
			document.getElementById('iw-image').style.display = 'none';
			document.getElementById('slider').innerHTML = "";
		}
		
		  info = '<b><a target="_blank" href="' + place.url + '">' + place.name + '</a></b>';
		  if (place.rating) {
				var ratingHtml = '';
				for (var i = 0; i < 5; i++) {
				  if (place.rating < (i + 0.5)) {
					ratingHtml += '&#10025;';
				  } else {
					ratingHtml += '&#10029;';
				  }
			}
			  info+="<br>"+ratingHtml;
	  	}
		info+="<br>Address: "+place.vicinity;
		if (place.formatted_phone_number) {
			var phone="<br>Phone: "+'<a href="tel:'+place.formatted_phone_number+'">'+place.formatted_phone_number+'</a>';
			info+=phone;
			document.getElementById('spanphoneid'+place.place_id).innerHTML = phone;
		}
		
 
	  if (place.website) {
		var website = place.website;
	 
		if (website.indexOf("http")==-1) {
		  website = 'http://' + website + '/';
		}
		website='<a href="'+website+'" target="_blank">'+website+"</a>";
		info+="<br>Website: "+website
		document.getElementById('spanwebid'+place.place_id).innerHTML = "Website: "+website;
	  } 
	  document.getElementById('iw-info').innerHTML = info;
	}


 
  // initialise google maps
	google.maps.event.addDomListener(window, 'load', initialize4000);

 