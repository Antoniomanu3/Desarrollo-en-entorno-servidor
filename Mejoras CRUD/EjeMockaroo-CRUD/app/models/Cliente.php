<?php

class Cliente
{

    private $id;
    private $first_name;
    private $last_name;
    private $email;
    private $gender;
    private $ip_address;
    private $telefono;

    function getImagen()
    {

        $id = str_pad($this->id, 8, "0", STR_PAD_LEFT);
        $imagen = "app/uploads/$id.jpg";

        if (file_exists($imagen)) {
            return './' . $imagen;
        } else {
            return "https://robohash.org/$id";
        }
    }

    function getFlag()
    {
        $data = $this->getLocation();
        if (isset($data->countryCode)) {
            return "https://www.countryflagicons.com/SHINY/64/" . $data->countryCode . ".png";
        } else {
            return "https://images.squarespace-cdn.com/content/v1/525ead10e4b03a9509e1cbf7/1420559877270-B5HF462FOTF7ZEF0SEP2/mv-flag-glitch-gif.gif?format=1000w";
        }
    }
    function getMap(){
        $data = $this->getLocation();
        if (isset($data->lat)) {
            return "https://maps.google.com/maps?z=15&output=embed&q=$data->lat,$data->lon";
        }
        return "no location found";
    }

    function getLocation(){
        $ipURL = "http://ip-api.com/json/" . $this->ip_address;
        $json = file_get_contents($ipURL);
        $data = json_decode($json);

        //return both country code and coordinates in an object
        $location = new stdClass();
        if(isset($data->countryCode)){
            $location->countryCode = $data->countryCode;
            $location->lat = $data->lat;
            $location->lon = $data->lon;
        }


        return $location;

    }

    function __set($name, $value)
    {
        if (property_exists($this, $name)) {
            $this->$name = $value;
        }
    }

    function &__get($name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }
    }

}