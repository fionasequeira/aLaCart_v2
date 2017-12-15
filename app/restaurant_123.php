<!DOCTYPE html>
<html>
<head>
    <link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet">
    <title>TweetMap: Filter By location</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <meta http-equiv="Content-Type" content="text/html"; charset="utf-8" />
    <link rel="stylesheet" type="text/css" href="home.css">
</head>

<body>
  <?php include('navbar.php'); 
  $input = $_POST['locationtag'];
  ?>
  <div align="center"class="container">
    <p align="center">T    W    E    E    T             BY        LOCATION</p>
    <div align="center"id="googleMap" style="width:90%;height:600px;"></div>
    <script type="text/javascript" >
    function myMap() {
      <?php
        $zipcode = $_POST["name"];
        $url = "http://maps.googleapis.com/maps/api/geocode/json?address=".$zipcode."&sensor=false";
        $details=file_get_contents($url);
        $result = json_decode($details,true);

        $lat=$result['results'][0]['geometry']['location']['lat'];

        $lng=$result['results'][0]['geometry']['location']['lng'];

        echo " var requested = [" .$lat.",".$lng."];";
        ?>

      var gmarkers = [];
      var requested_lat = requested[0];
      var requested_long = requested[1];
      requestedlatlngset = new google.maps.LatLng(requested_lat, requested_long);
      var map = new google.maps.Map(document.getElementById('googleMap'), {
        center: requestedlatlngset,
        zoom: 15
        });

      var iconBase = 'https://maps.google.com/mapfiles/kml/shapes/';
      var dragmarker = new google.maps.Marker({
        draggable: true,
        position: requestedlatlngset,
        map: map,
        draggable : true,
        title: "Your location"
      });
      <?php
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, 'https://search-messi-kkvckwfx7xc3at4b4r2ilv2tii.us-east-1.es.amazonaws.com/fiona/goo/_search?size=10000');
        $json = curl_exec($ch);
        curl_close($ch);

        $obj = json_decode($json,true);
        // $get = file_get_contents('search.json');
        // $obj = json_decode($get, true);
        $length = count($obj["hits"]["hits"]);
        echo ' var locations = [ ';
        for ($i=0; $i < $length; $i++) {
          $jsonn = $obj["hits"]["hits"][$i]["_source"];
          $latitude = $jsonn["lat"];
          $longitude = $jsonn["long"];
          $name = $jsonn["name"];
          $url =$jsonn["url"];
          echo '['.$latitude.','.$longitude.',"'.$name.'","'.$url.'"],';
          // echo $latitude;
          // echo $Longitude;
        } 
        echo '];';
      ?>
      
      // var infomarker, i
      for (var i =0; i < locations.length; i++) {
            var name = locations[i][2];
            var url = locations[i][3];

        existinglatlong = new google.maps.LatLng(locations[i][0], locations[i][1]);

        // if ((google.maps.geometry.spherical.computeDistanceBetween(requestedlatlngset,existinglatlong)) <= 10) {

        var infomarker = new google.maps.Marker({  
          map: map, 
          title: "loan", 
          icon: iconBase + "info-i_maps.png",
          position: latlngset  
          });
        map.setCenter(infomarker.getPosition())
        var content = "<u>Name:</u>" + name + "<br/><u>URL :</u>" + url
        var infowindow = new google.maps.InfoWindow()

        google.maps.event.addListener(infomarker,'click', (function(infomarker,content,infowindow){ 
          return function() {
            infowindow.setContent(content);
            infowindow.open(map,infomarker);
          };
        }) (infomarker,content,infowindow)); 
        gmarkers.push(infomarker);
      }
}
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD-nsMPz4dIJ4lHSr8GB3pwK2KlUt51VVE&callback=myMap&libraries=geometry"></script>
    <div id="latlong">
      <p align="center">Latitude: <input size="20" type="text" id="latbox" name="lat"> Longitude: <input size="20" type="text" id="lngbox" name="lng" ></p>
  </div>
</div>
</body>
</html>