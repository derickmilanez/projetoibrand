<html>
<head>
    <title>Sistema</title>
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
</head>
<body>
<style>

    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
    }
    
    #map {
        height: 100%;
    }

    button.gm-ui-hover-effect {
        visibility: hidden;
    }
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<?php
session_start();
if($_SESSION['senha'] == '12345'){

}else{
    header("Location:verification.php");
}
include_once 'locations_model.php';
?>


<div id="map"></div>

<script>
    var map;
    var marker;
    var infowindow;
    var red_icon =  'http://maps.google.com/mapfiles/ms/icons/red-dot.png' ;
    var purple_icon =  'http://maps.google.com/mapfiles/ms/icons/purple-dot.png' ;
    var yellow_icon =  'http://maps.google.com/mapfiles/ms/icons/yellow-dot.png' ;
    var orange_icon =  'http://maps.google.com/mapfiles/ms/icons/orange-dot.png' ;
    var color;
    var locations = <?php get_all_locations() ?>;

    function initMap() {
        var sp = {lat: -23.5489, lng: -46.6388};
        infowindow = new google.maps.InfoWindow();
        map = new google.maps.Map(document.getElementById('map'), {
            center: sp,
            zoom: 12
        });


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
                html: document.getElementById('form')
            });

            google.maps.event.addListener(marker, 'click', (function(marker, i) {
                return function() {
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
    }

    function saveData() {
        var confirmed = document.getElementById('confirmed').checked ? 1 : 0;
        var id = document.getElementById('id').value;
        var url = 'locations_model.php?confirm_location&id=' + id + '&confirmed=' + confirmed ;
        downloadUrl(url, function(data, responseCode) {
            if (responseCode === 200  && data.length > 1) {
                infowindow.close();
                window.location.reload(true);
            }else{
                infowindow.setContent("<div style='color: purple; font-size: 25px;'>Erros de Inserção</div>");
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


</script>

<div style="display: none" id="form">
    <table class="map1">
        <tr>
            <td><a>Tipo de Ocorrência:</a></td>
            <td><textarea disabled id='tipoOcorrencia'></textarea></td>
        </tr>
        <tr>
            <input name="id" type='hidden' id='id'/>
            <td><a>Descrição:</a></td>
            <td><textarea disabled id='description' placeholder='Descrição'></textarea></td>
        </tr>
        <tr>
            <td><b>Confirmar localização?:</b></td>
            <td><input id='confirmed' type='checkbox' name='confirmed'></td>
        </tr>

        <tr><td></td><td><input type='button' value='Salvar' onclick='saveData()'/></td></tr>
    </table>
</div>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?language=en&key=AIzaSyAlR9oeiHY0HOakr2VxMgzZB57WFIC-Nzw&callback=initMap">
</script>