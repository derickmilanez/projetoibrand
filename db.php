<?php
$connection=mysqli_connect ("localhost", 'root', '','db');
if (!$connection) {
    die('Not connected : ' . mysqli_connect_error());
}
