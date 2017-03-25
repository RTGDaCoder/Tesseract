<?php

# Tesseract
# All rights reserved

namespace pocketmine\level\weather;

use pocketmine\event\level\WeatherChangeEvent;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\network\protocol\LevelEventPacket;
use pocketmine\Player;
use pocketmine\level\format\io\LevelProvider;

class Weather {
    
    const CLEAR = 0;
    const SUNNY = 0;
    const RAINY = 1;
    const RAIN = 1;
    const RAINY_THUNDER = 2;
    const THUNDER = 2;
    
    public $level;
    
    public $provider;
    
    public $server;
    
    public $weatherEnabled;
    public $weather = 0;
    public $weatherDuration;
    
    public function __construct(Level $level, LevelProvider $provider) {
        
        $this->level = $level;
        $this->provider = $provider;
        $this->server = $level->getServer();
        
    }
    
    public function onTick() {
        
        if(!$this->weatherEnabled) {
            return;
        }
        
        if($this->weatherDuration <= 0) {
            $this->toggleWeather();
        }
        
        if($this->getWeather() ===  self::RAIN or mt_rand(0, 300)) {
            $this->setWeather(self::THUNDER);
        }
        
    }
    
    public function onDuration() {
        return $this->weatherDuration;
    }
    
    public function setWeather($weatherId, $duration = null) {
        
        if($weatherId === $this->weather) {
            return;
        }
        
        if($duration === null) {
            return $duration = mt_rand(300, 6000);
        }
        
        $lvl = $this->getLevel();
        $this->getServer()->getPluginManager()->callEvent($e = new WeatherChangeEvent($lvl, $weatherId, $duration));
        
        if($e->isCancelled()) {
            return;
        }
        
        $this->weather = $e->getWeather();
        $this->duration = $e->getDuration();
        
        $this->sendWeather();
        
    }
    
    public function getWeather() {
        return $this->weather;
    }
    
    public function setWeatherEnabled($value = bool) {
        $this->weatherEnabled = $value;
    }
    
    public function toggleWeather() {
        
        switch($this->weather) {
            
            case self::CLEAR:
                
                if(mt_rand(0, 100) > 100) {
                    $this->setWeather(self::RAINY_THUNDER);
                    break;
                }
                
                break;
                
            case self::RAIN:
            case self::RAINY:
            case self::RAINY_THUNDER:
                
                $this->setWeather(self::CLEAR);
                
                break;
            
        }
        
    }
    
    // from pocketmine
    
    public function sendWeather(array $players = []){
        
	$players = count($players) > 0 ? $players : $this->getLevel()->getPlayers();
	$pk = new LevelEventPacket();
	$pk->evid = $this->weather === self::RAIN ? 3001 : $this->weather === self::RAIN_AND_THUNDER ? 3002 : 3003;
	$pk->data = 90000; //Not sure if this is default.
        
	foreach($players as $p){
		$p->dataPacket($pk);
	}
    }
    
    public function strikeLightning(Vector3 $pos, $yaw, $pitch, array $metadata = []) {
        
        $pk = new \pocketmine\network\protocol\AddEntityPacket;
        $pk->type = 95;
        $pk->eid = \pocketmine\entity\Entity::$entityCount++;
        $pk->x = $pos->x;
        $pk->y = $pos->y;
        $pk->z = $pos->z;
        $pk->yaw = $yaw;
        $pk->pitch = $pitch;
        $pk->metadata = $metadata;
        
            foreach($this->getLevel()->getPlayers() as $pl) {
                $pl->dataPacket($pk);
            }

    }
    
    // No more PMMP code
    
    public function nowRaining() {
        return $this->weather == self::RAIN;
    }
    
    public function getWeatherName($word) {
        
        if (int($weather) > 3) {
            return self::CLEAR;
        }
        
        switch($this->word) {
            
            case self::CLEAR:
                
                return "Clear";
                
            case self::RAIN:
            case self::RAINY:
            case self::RAINY_THUNDER:
                
                return "Rain";
            
        }
        
    }
    
    public function getEvid(int $id) {
        return $id === self::NORMAL ? 3003 : 3001;
    }
    
    public function getLevel() : Level {
        return $this->level;
    }
    
    public function getServer() : Server {
        return $this->server;
    }
    
    public function getWeatherFromString($word) {
        
        switch(strtolower($word)) {
            
            case "clear":
                
                return self::CLEAR;
                
                break;
            
            case "thunder":
            case "rain":
                
                return self::RAIN;
                
                break;
             
        }
        
    }
    
}