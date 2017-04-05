<?php

/**
 * Factions-PP
 *
 * Copyright (C) 2017 dihydrogen-monoxide
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author Jack Noordhuis
 *
 */

namespace dihydrogen\monoxide\factions;

use dihydrogen\monoxide\factions\command\FactionsCommand;
use pocketmine\plugin\PluginBase;

class FactionsPP extends PluginBase {

	/** @var FactionsCommand */
	private $factionsCommand;

	/** @var EventListener */
	private $eventListener;

	public function onEnable() {
		$this->getServer()->getCommandMap()->register("fpp", $this->factionsCommand = new FactionsCommand($this));
		$this->eventListener = new EventListener($this);
	}

	public function onDisable() {
		$this->factionsCommand->unregister($this->getServer()->getCommandMap());
		$this->eventListener->close();
	}

	public function getEventListener() : EventListener {
		return $this->eventListener;
	}

}