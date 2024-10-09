<?php
  session_start();
  include("connection.php");
  if(!isset($_SESSION['user_session']))
  {
    header("Location: index.html");
  }
  $sql = "SELECT * FROM user_records WHERE id='".$_SESSION['user_session']."'";
  $resultset = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($resultset);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD8qE7UJKblyrSKsbA3R_T6nJlAzTbqHrE&libraries=places" type="text/javascript"></script>
    <title>Document</title>
</head>
<style>

  .container {
    width: -webkit-fill-available;
  }

  .gm-style-iw button {
    display:none !important;
  }

  .popupmode {
    display: none;
    width: 250px;
    background-color: #ffffff;
    color: #000;
    text-align: center;
    border-radius: 6px;
    padding: 2px;
    position: absolute;
    z-index: 1;
    right:50px;
    top: 0%;
    height: 60px;
    border: 1px solid #000;
    box-shadow: 1px 4px 8px rgba(0, 0, 0, 0.5);
  }

  .popupmode::after {
    content: "";
    position: absolute;
    top: 10%;
    left: 0%;
    margin-left: -11px;
    border-width: 5px;
    border-style: solid;
    transform: rotate(90deg);
    border-color: #fff transparent transparent transparent;
  }

  .popupmode div img
  {
    padding: 2px;
  }

  .popupmode div
  {
    display: inline-block;
    border-left:1px solid #fff;
    font-weight:bold;
  }

</style>
<body>
    <div class="container">
      <div id="navbar" class="navbar-collapse collapse">
        <div style="display: flex; align-items: center; justify-content: space-between;">
          <ul class="nav navbar-nav navbar-left">
            <h1 style="font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;">12 Jyotirlingas Coordinates List</h1>
          </ul>
          <ul class="nav navbar-nav navbar-right">            
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                <h3><span class="glyphicon glyphicon-user"></span>&nbsp;Hi <?php echo $row['username']; ?>&nbsp;<span class="caret"></span></h3>
              </a>
              <ul class="dropdown-menu">
                <li><a href="profileedit.php"><span class="glyphicon glyphicon-pencil"></span>&nbsp;Update Profile</a></li>
                <li><a href="passwordreset.php"><span class="glyphicon glyphicon-remove"></span>&nbsp;Reset Password</a></li>
                <li><a href="#" onclick="confirmSignout(event)"><span class="glyphicon glyphicon-log-out"></span>&nbsp;Sign Out</a></li>
              </ul>
            </li>
          </ul>
        </div>
      </div>	
      <div id="map" style="height: 90vh;"></div>	
      <div class="repeatertype" style="padding-left:0px; cursor: pointer;">
        <div class="text-center" style="padding:4px; border-radius: 4px; margin-top: 17%; position: absolute; right: 25px;top: 0px;background: #ffffff;">
            <img id="mapmodeimg" alt="image" src="img/layers.png" style="width:32px; height:30px" onclick="showchangemode();">
            <div class="m-t-xs font-bold" style="padding-right:15px;"></div>
            <span class="popupmode" id="popupmode">
              <div style="border-left:0px solid #fff !important;" onclick="setModeType(1)"><img alt="image" src="img/layers.png" style="width:30px;"><br>Default</div>
              <div onclick="setModeType(2);" style="border-left: 2px solid #000;padding-left:5px;"><img alt="image" src="img/satellite.png" style="width:30px;"><br>Satellite</div>
              <div onclick="setModeType(3);" style="border-left: 2px solid #000;padding-left:5px;"><img alt="image" src="img/terrain.png" style="width:30px;"><br>Terrain</div>   
              <div onclick="setModeType(4);" style="border-left: 2px solid #000;padding-left:5px;"><img alt="image" src="img/hybrid.png" style="width:30px;"><br>Hybrid</div>         
            </span>
        </div>
      </div>
    </div>
</body>
<script>
    
    $('document').ready(function() {
			initMap();
    });

    function confirmSignout(event) {
      event.preventDefault(); 
      if (confirm("Are you sure you want to sign out?")) {
          window.location.href="logout.php";
      }
    }

    var map;
    function initMap() {
      map = new google.maps.Map(document.getElementById("map"), {
        zoom: 5,
        fullscreenControl: true,
        fullscreenControlOptions: {
          position: google.maps.ControlPosition.LEFT_TOP,
        },
        zoomControl: true,
        zoomControlOptions: {
          position: google.maps.ControlPosition.RIGHT_TOP,
        },
        streetViewControl: false,
        mapTypeControl: false,
      });
      navigator.geolocation.getCurrentPosition(function(position) {
        var currentLocation = {
          lat: position.coords.latitude,
          lng: position.coords.longitude
        };
        var indiaMiddleLocation = {lat: 20.5937, lng: 78.9629};
        map.setCenter(indiaMiddleLocation);
        const shivaJyothirlinga = [
          [{ lat: currentLocation.lat, lng: currentLocation.lng}, "DarshanV"],
          [{ lat: 20.888028, lng: 70.401389 }, "Somnath Jyotirlinga"],
          [{ lat: 16.074167, lng: 78.868056 }, "Mallikarjuna Jyotirlinga"],
          [{ lat: 23.182905, lng: 75.768291 }, "Mahakaleshwar Jyotirlinga"],
          [{ lat: 22.2454388, lng: 76.1484893 }, "Omkareshwar Jyotirlinga"],
          [{ lat: 24.4924825, lng: 86.6999678 }, "Vaidyanath Jyotirlinga"],
          [{ lat: 19.071939, lng: 73.5359402 }, "Bhimashankar Jyotirlinga"],
          [{ lat: 9.288004530086106, lng: 79.31615358167303 }, "Rameshwaram Jyotirlinga"], 
          [{ lat: 22.335982207249227, lng: 69.08707575092544 }, "Nageshwar Jyotirling"], 
          [{lat: 25.31086423895165, lng: 83.01067703881259}, "Kashi Vishwanath Jyotirlinga"],
          [{lat: 19.93216830209555, lng: 73.53082155765644 }, "Trimbakeshwar Jyotirlinga"], 
          [{lat: 30.735424456888964, lng: 79.06696468591248}, "Kedarnath Jyotirlinga"],
          [{lat: 20.024961033651874, lng: 75.16947384479418 }, "Grishneshwar Jyotirlinga"],
        ];
        const customIconUrl = "img/om.png";
        var infowindow = new google.maps.InfoWindow();
        shivaJyothirlinga.forEach(([position, title], i) => {
          var marker = new google.maps.Marker({
            position,
            map,
            title: 'DarshanV'
          });
          if (i >= 1) {
            var customMarker = new google.maps.Marker({
              position: {
                  lat: position.lat,
                  lng: position.lng,
              },
              map,
              icon: {
                  url: customIconUrl,
                  scaledSize: new google.maps.Size(40, 40),
                  anchor: new google.maps.Point(12, 52),
              },
            });
            customMarker.setZIndex(google.maps.Marker.MAX_ZINDEX + 1);
            [marker, customMarker].forEach(function(markerItem) {
              markerItem.addListener('click', function() {
                map.setZoom(9);
                map.panTo(this.getPosition());
              });
              markerItem.addListener('mouseover', function() {
                infowindow.setContent(title);
                infowindow.open(map, markerItem);
              });
              markerItem.addListener('mouseout', function() {
                  infowindow.close();
              });
            });
          }
        });
      });
    }

    let modestatus = true;
    function showchangemode() {
      if (modestatus) {
        $("#popupmode").show();
      } else {
        $("#popupmode").hide();
      }
      modestatus = !modestatus;
    }

    function setModeType(mode) {
      modestatus = true;
      const mapStyles = {
        1: google.maps.MapTypeId.ROADMAP,
        2: google.maps.MapTypeId.SATELLITE,
        3: google.maps.MapTypeId.TERRAIN,
        4: google.maps.MapTypeId.HYBRID,
      };
      const modeImages = {
        1: "img/layers.png",
        2: "img/satellite.png",
        3: "img/terrain.png",
        4: "img/hybrid.png",
      };
      const selectedStyle = mapStyles[mode];
      const selectedImage = modeImages[mode];
      console.log("selectedStyle: " + selectedStyle + " " + mode);
      if (selectedStyle && selectedImage) {
        $("#mapmodeimg").attr("src", selectedImage);
        map.setMapTypeId(selectedStyle);
      }
    }

  </script>
</html>