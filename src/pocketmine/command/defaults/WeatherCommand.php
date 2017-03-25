<?php

/*
 *
 *  _____   _____   __   _   _   _____  __    __  _____
 * /  ___| | ____| |  \ | | | | /  ___/ \ \  / / /  ___/
 * | |     | |__   |   \| | | | | |___   \ \/ /  | |___
 * | |  _  |  __|  | |\   | | | \___  \   \  /   \___  \
 * | |_| | | |___  | | \  | | |  ___| |   / /     ___| |
 * \_____/ |_____| |_|  \_| |_| /_____/  /_/     /_____/
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author iTX Technologies
 * @link https://itxtech.org
 *
 */

namespace pocketmine\command\defaults;

use pocketmine\command\CommandSender;
use pocketmine\event\TranslationContainer;
use pocketmine\level\Level;
use pocketmine\level\weather\Weather;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class WeatherCommand extends VanillaCommand{
    
        public $level;

	public function __construct($name){
		parent::__construct(
			$name,
			"%pocketmine.command.weather.description",
			"%pocketmine.command.weather.usage"
		);
		$this->setPermission("pocketmine.command.weather");
                
	}

	public function execute(CommandSender $sender, $currentAlias, array $args){
		if(!$this->testPermission($sender)){
                    return true;
		}
                
                if(count($args) < 1) {
                    $sender->sendMessage("[Tesseract] Usage: /weather set < clear | rain >");
                }
                
                if ($args[0] === "set") {
                    
                    if (count($args[1] !== 2)) {
                        
                        $sender->sendMessage("[Tesseract] Usage: /weather set < clear | rain >");
                        
                    }
                    
                }
                
                switch(strtolower($args[1])) { // strtolower for accurate reading
                    
                    case "clear":
                        
                        Level::getWeatherManager()->setWeather(0, null);
                        $sender->sendMessage("Weather set to Clear");
                        
                        break;
                    
                    case "rain":
                        
                        Level::getWeatherManager()->setWeather(0, null);
                        $sender->sendMessage("Weather set to Rain");
                        
                        break;
                    
                    
                }
                
        }
}
