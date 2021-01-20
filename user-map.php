<?php
session_start();
$_SESSION["senha"] = null;
include_once 'header.php';
include 'locations_model.php';
?>

    <script type="text/javascript"
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAlR9oeiHY0HOakr2VxMgzZB57WFIC-Nzw&libraries=places">
    </script>
    <input
      id="endereco"
      class="controls"
      type="text"
      placeholder="Digite o endereço"
    />
     <div id="map"></div>
     <script>
      var markers = [];
      var red_icon =  'http://maps.google.com/mapfiles/ms/icons/red-dot.png' ;
        var purple_icon =  'http://maps.google.com/mapfiles/ms/icons/purple-dot.png' ;
        var yellow_icon =  'http://maps.google.com/mapfiles/ms/icons/yellow-dot.png' ;
        var orange_icon =  'http://maps.google.com/mapfiles/ms/icons/orange-dot.png' ;
        var color;
        var locations = <?php get_confirmed_locations() ?>;
        var infowindow;
        var myOptions = {
            zoom: 12,
            center: new google.maps.LatLng(-23.5489, -46.6388),
            mapTypeId: 'roadmap'
        };

        const map = new google.maps.Map(document.getElementById("map"), myOptions);
        const input = document.getElementById("endereco");
        const searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
        map.addListener("bounds_changed", () => {
          searchBox.setBounds(map.getBounds());
        });
        searchBox.addListener("places_changed", () => {
          const places = searchBox.getPlaces();

          if (places.length == 0) {
            return;
          }

          const bounds = new google.maps.LatLngBounds();
          places.forEach((place) => {
            if (!place.geometry) {
              console.log("Returned place contains no geometry");
              return;
            }
            const icon = {
              url: place.icon,
              size: new google.maps.Size(71, 71),
              origin: new google.maps.Point(0, 0),
              anchor: new google.maps.Point(17, 34),
              scaledSize: new google.maps.Size(25, 25),
            };
           
              var markerId = getMarkerUniqueId(place.geometry.location.lat(), place.geometry.location.lng()); 
              var marker = new google.maps.Marker({
                map,
                animation: google.maps.Animation.DROP,
                icon,
                title: place.name,
                position: place.geometry.location,
                id: 'marker_' + markerId,
                html: "    <div id='info_"+markerId+"'>\n" +
                "        <table class=\"map1\">\n" +
                "           <tr>\n" +
                "                <td><a>Tipo de Ocorrência:</a></td>\n"+ 
                "                <td><select id='tipoOcorrencia'><option value='Roubo'>Roubo</option><option value='Furto'>Furto</option><option value='Violencia Sexual'>Violência Sexual</option></select></td>\n"+
                "           <tr>\n" +
                "            <tr>\n" +
                "                <td><a>Descrição:</a></td>\n" +
                "                <td><textarea  id='manual_description' placeholder='Descrição'></textarea></td></tr>\n" +
                "            <tr><td></td><td><input type='button' value='Salvar' onclick='saveData("+place.geometry.location.lat()+","+place.geometry.location.lng()+")'/></td></tr>\n" +
                "        </table>\n" +
                "    </div>"
              }) 
             
             markers[place.geometry.location] = marker;
             bindMarkerinfo(marker);

                 

            if (place.geometry.viewport) {
              bounds.union(place.geometry.viewport);
            } else {
              bounds.extend(place.geometry.location);
            }
          });
          map.fitBounds(bounds);
        });

         var bindMarkerinfo = function(marker) {
            google.maps.event.addListener(marker, "click", function (point) {
                
                infowindow = new google.maps.InfoWindow();
                infowindow.setContent(marker.html);
                infowindow.open(map, marker);
            });
        };

        var i ; var confirmed = 0;
        for (i = 0; i < locations.length; i++) {
            if(locations[i][4] === '1' && locations[i][5] === 'Roubo'){
                color = orange_icon;
            }else if(locations[i][4] === '1' && locations[i][5] === 'Furto'){
                color = yellow_icon;
            }else if(locations[i][4] === '1' && locations[i][5] === 'Violencia Sexual'){
                color = red_icon;
            }else{
                color = purple_icon;
            }
            marker = new google.maps.Marker({
                position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                map: map,
                icon :   color,
                html: "<div>\n" +
                "<table class=\"map1\">\n" +
                "<tr>\n" +
                "<td><a>Tipo de Ocorrência:</a></td>\n"+ 
                "<td><select disabled id='tipoOcorrencia'><option>" + locations[i][5] + "</option></select></td>\n"+
                "<tr>\n" +
                "<tr>\n" +
                "<td><a>Description:</a></td>\n" +
                "<td><textarea disabled id='manual_description' placeholder='Descrição'>"+locations[i][3]+"</textarea></td></tr>\n" +
                "</table>\n" +
                "</div>"
            });

            google.maps.event.addListener(marker, 'click', (function(marker, i) {
                return function() {
                    infowindow = new google.maps.InfoWindow();
                    confirmed =  locations[i][4] === '1' ?  'checked'  :  0;
                    $("#confirmed").prop(confirmed,locations[i][4]);
                    $("#id").val(locations[i][0]);
                    $("#description").val(locations[i][3]);
                    $("#tipoOcorrencia").val(locations[i][5]);
                    $("#form").show();
                    infowindow.setContent(marker.html);
                    infowindow.open(map, marker);
                }
            })(marker, i));
        }


        function downloadUrl(url, callback) {
            var request = window.ActiveXObject ?
                new ActiveXObject('Microsoft.XMLHTTP') :
                new XMLHttpRequest;

            request.onreadystatechange = function() {
                if (request.readyState == 4) {
                    callback(request.responseText, request.status);
                }
            };

            request.open('GET', url, true);
            request.send(null);
        }


      function saveData(lat, lng) {
            var description = document.getElementById('manual_description').value;
            var tipoOcorrencia = document.getElementById('tipoOcorrencia').value;
            var url = 'locations_model.php?add_location&description=' + description + '&lat=' + lat + '&lng=' + lng + '&tipoOcorrencia=' + tipoOcorrencia;
            downloadUrl(url, function(data, responseCode) {
                if (responseCode === 200  && data.length > 1) {
                    var markerId = getMarkerUniqueId(lat,lng);
                    var manual_marker = markers[markerId];
                    infowindow.close();
                    infowindow.setContent("<div style=' color: purple; font-size: 25px;'> Esperando o admin confirmar</div>");
                    infowindow.open(map, manual_marker);

                }else{
                    console.log(responseCode);
                    console.log(data);
                    infowindow.setContent("<div style='color: red; font-size: 25px;'>Erro de Inserção</div>");
                }
            });
        }


        function downloadUrl(url, callback) {
            var request = window.ActiveXObject ?
                new ActiveXObject('Microsoft.XMLHTTP') :
                new XMLHttpRequest;

            request.onreadystatechange = function() {
                if (request.readyState == 4) {
                    callback(request.responseText, request.status);
                }
            };

            request.open('GET', url, true);
            request.send(null);
        }

        var getMarkerUniqueId= function(lat, lng) {
            return lat + '_' + lng;
        };
    </script>


    <?php
include_once 'footer.php';

?>