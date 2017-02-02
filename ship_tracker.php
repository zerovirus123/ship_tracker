<!-- Title: First Steamship Vessel Tracker -->
<!-- Description: Tracks the 14 carrier vessels owned by the ship. -->
<!-- Author:  Jing Guang Sia (special thanks to Johnny). -->
<!-- Date: 17th August 2015	-->

<!DOCTYPE html>
<html>

	<head id="stuff">
	
		<script src="http://maps.googleapis.com/maps/api/js"> </script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"> </script>
		<script src="jquery.shining.js"></script>
		<link rel="stylesheet" type="text/css" href="styling.css">
	
		<meta charset="UTF-8">	
			
		<title> First Steamship Vessel Tracker </title> 
				
		<div id="header">
			<div>
				<img id="logo" src="http://i.imgur.com/4jRDmTMt.jpg">
			</div>
			<h1 id="h1"> First Steamship Co. Vessel Tracker </h1>
		</div>
		
		<script>
			
			//Allows for a pleasing fade in effect when webpage loads
			$(document).ready(function(){
				$('#header').hide().fadeIn(2000);
				$('#text-box').hide().fadeIn(2000);
				$('#footer').hide().fadeIn(2000);
				$('#googleMap').hide().fadeIn(2000);
				$('#body').hide().fadeIn(2000);
			});
			
			//Header bling bling effect
			$(document).ready(function(){
				$("#h1").mouseover(function()
					{$(this).shineText();}	
				);
			});
			
			var map; //map object made global so all methods can access it
			var markerArray = []; //stores all the vessel markers onto the map
			
			//icons for the ship and headquarter markers
			var hq_icon = "http://i.imgur.com/Uvkd1z8.png";
	        var ship_icon = "http://i.imgur.com/ubs6Vp5.png";

			//Does nothing...
			function doNothing(){};
		
			<!-- Creates the map and coordinate objects, and placing them onto the map.-->
			function initialize(){
				
				//Coordinates of headquarters
				var FirsteamHQ = {lat: 25.025922, lng: 121.543449};
				  
				//Gmaps properties
				var mapProp = {
				    center: new google.maps.LatLng(25.025922, 121.543449),
					zoom: 5,
					mapTypeId: google.maps.MapTypeId.ROADMAP
				};
				  
				//Instantiating a new map object
				map = new google.maps.Map(document.getElementById("googleMap"), mapProp);
				map.setOptions({minZoom: 5, maxZoom: 100000});
			  
			    //NOTE: variable will lose their values when outside of the callback function's scope
				downloadUrl("ship_track_ajax.php", function(data){
					var JSON_text = data.responseText;
					var markers = JSON.parse(JSON_text); //this is a JSON object. An array of associative arrays
					
					//Making the marker objects and stuff them into an array
					for (var index = 0; index < markers.length; index++){
					   //Marker information
					   var name = markers[index].name;
					   var point = new google.maps.LatLng(
						  markers[index].lat,
						  markers[index].lng);
						  
					   //creating markers with above values
					   var marker = new google.maps.Marker({
						  map: map,
						  position: point,
						  icon: {
							 url: ship_icon,
							 scaledSize: new google.maps.Size(40, 32) // pixels							 
						  },
						  title: name
					   });
						markerArray.push(marker); //add the markers into the array
						 // Places all the ship markers NOTE: Must be placed within downloadURL
				        showOverlays();
					}
				});
				  
				  //Headquarters Marker Object
				  var HQMarker = new google.maps.Marker({
						position: FirsteamHQ,
						map: map,
						title: "Taipei Headquarters",
						icon: hq_icon
				  });
				  
				  //Info Window for the headquarters
				  var HQwindow = new google.maps.InfoWindow({
					    content: HQMarker.title,
					    position: FirsteamHQ
				  }); 
				  
				  //Opens the HQ info window if it is clicked
				  HQMarker.addListener('click', function() {
						 HQwindow.open(map, HQMarker)
			      });
				  
				  //Place headquarter marker on Map
				  HQMarker.setMap(map);
				    
			}     // end of initiaize()
			
			//Listens to DOM events NOTE: DO NOT PUT THIS IN INITIALIZE, OTHERWISE MAP DOES NOT LOAD
			google.maps.event.addDomListener(window, 'load', initialize);			
				
			//Downloads the URL for the intermediary XML file with the ship markers
			//Parameters: url - path to the PHP script generating the ship markers
			//            callback - Function invoked when XML is returned to JavaScript
			function downloadUrl(url, callback) {
				 var request = window.ActiveXObject ?
					 new ActiveXObject('Microsoft.XMLHTTP'):
					 new XMLHttpRequest;

				 request.onreadystatechange = function() {
				   if (request.readyState == 4) { //4 means everything is fine. Refer to doc.
					 request.onreadystatechange = doNothing;
					 callback(request, request.status);
				   }
				 };
				request.open('GET', url, true);
				request.send(null);
			}
			
			//Places ship markers onto the map, based on data within marker
			function showOverlays() {
			   var index;
			   if (markerArray) {
				    for (index = 0; index < markerArray.length; index++){  
						 var infowindow = new google.maps.InfoWindow({
							 content: markerArray[index].title,
							 position: markerArray[index].position
						 }); 			 
					 markerArray[index].setMap(map);
					 bindInfoWindow(markerArray[index], map, infowindow);
				    }; // end of for loop  
			  }
			   else{
					alert("Carrier vessels marker cannot be loaded properly."); //error detection
			   }
			}
			
			//Bind the InfoWindow with respective markers
			//Parameters: marker has name and coordinate info on ships
			//            map refers to the Google Maps Object
			//            InfoWindow is the small window that displays the ship's name when the icon is clicked
			function bindInfoWindow(marker, map, infowindow) {
				google.maps.event.addListener(marker, 'click', function() {
					infowindow.open(map, marker);
				});
            } 	
			
			//Handles exiting the webpage event
			$(window).bind('beforeunload', function(){
				return 'Do you want to leave this web page?';
			});
			
			//Handles tab animation
			(function($) {
				var tabs =  $(".tabs li a"); //set to tabs class
				tabs.click(function() {
					var content = $(this).hash.replace('/','');
					tabs.removeClass("active");
					$(this).addClass("active");
				$("#content").find('p').hide();
				$(content).fadeIn(200);
				});

			})(jQuery);
			
		</script>
		
	</head>

	<noscript> 
			Your browser does not support JavaScript. Please try another browser.
	</noscript>

	<body id="body">
	
		<div id="googleMap"></div>
		
		<div class="wrap"> <!-- The wrapper for the tabs and content box. Allows it to be manipulated as a whole -->
		
		    <!--The tab groups are assembled here -->
			<ul class="tabs">
				<li class="tab"><a class="active "href="#/one"> About </a></li>
			    <li class="tab"><a href="#/two"> Vessel Info </a> </li>
			</ul>
		
		    <!--Tab contents are assembled here-->
			<div id="content" >
				<p id="one">
					First Steamship Co. Ltd currently has 14 operational carrier vessels. Click on the ship markers for more info, 
					 or click <a href="http://www.firsteam.com.tw/en/index.php">here</a> to visit the company's main page.
				</p>
				<p id="two">
				   Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
				</p>
			</div>
		</div>
			
	</body>
	
	<div class="footerWrap">
		<div class="footer">
			<div class="footerContent">
			<p>
			 <l> Address/ 14th Fl, &nbsp;No 237, &nbsp;Fuhsing South Road, &nbsp; Section 2, &nbsp;Taipei, &nbsp;Taiwan,  &nbsp;ROC </l> <br/>
		     <l> &nbsp;Copyright 2015 &copy First Steamship Co., Ltd. &nbsp; All Rights Reserved </l> <br/>
		     <l> &nbsp; Tel: <a href="tel:+022706911"> 02-27069911&nbsp; </a> Fax: <a href="tel:+0227029922"> 02-27029922&nbsp;</a>  E-mail: <a href="mailto:name@email.com"> fss@firsteam.com.tw </a></l>
			</p>
        </div>     
    </div>
	
	</div>
	
	<script>
	  //Credit to Alex Lime on Codepen.io.
	  //This jquery allows the content box to switched to the selected tabs. 
	  //Because this script only works after the tabs and content box is rendered, 
	  //therefore this code has to be placed below the tabs' rendering code.
	  
	 (function($) {

		var tabs =  $(".tabs li a");
	  
		tabs.click(function() {
			var content = this.hash.replace('/','');
			tabs.removeClass("active");
			$(this).addClass("active");
		$("#content").find('p').hide();
		$(content).fadeIn(200);
		});

	})(jQuery);
	</script>
	
</html>