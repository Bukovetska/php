<?php

class WorldCityModel {
    public function __construct(
        public int $id,
        public string $city,
        public string $cityAscii,
        public float $lat,
        public float $lng,
        public string $country,
        public string $iso2,
        public string $iso3,
        public string $adminName,
        public string $capital,
        public int $population
    ) {}

    public function getCityWithCountry(): string {
        return "{$this->city} ({$this->country})";
    }

    public function getFlag(): string {
        if (empty($this->iso2)) {
            return '';
        }

        $code = strtolower($this->iso2);

        return "<img 
            src='https://flagcdn.com/24x18/{$code}.png' 
            alt='{$this->iso2}' 
            width='24' 
            height='18' 
            style='vertical-align: middle;' 
        />";
    }
}