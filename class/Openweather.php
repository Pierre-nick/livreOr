<?php
class Openweather{

    private $apiKey;

    public function __construct(string $apiKey){

        $this->apiKey = $apiKey;
    }

    public function getForecast(string $city): ?array{
        $curl = curl_init("api.openweathermap.org/data/2.5/forecast/daily?q={$city}&cnt=7&appid={$this->apiKey}&units=metric&lang=fr");
        curl_setopt_array($curl,[
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 1
        ]);
        var_dump(curl_getinfo($curl,CURLINFO_HTTP_CODE));
        die();
        $data = curl_exec($curl);
        if($data === false || curl_getinfo($curl, CURLINFO_HTTP_CODE) !== 200){
            return null;
        }
        $results = [];
        $data = json_decode($data,true);
        foreach($data['list'] as $day){
            $results = [
                'temp :' => $day['temp']['day'],
                'description' => $day['weather'][0]['description'],
                'date' => new DateTime('@'.$day['dt'])
            ];
        }
        return $results;
    }
}