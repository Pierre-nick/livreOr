<?php
require_once 'class/Openweather.php';
$weather = new openweather('665a34a9f53c09297495edc0767ed1cb');
$forecast = $weather->getForecast('Paris,fr');
var_dump($forecast);