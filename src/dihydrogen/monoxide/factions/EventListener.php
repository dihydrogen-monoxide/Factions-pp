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

use pocketmine\event\Listener;

class EventListener implements Listener {

	/** @var FactionsPP */
	private $plugin = null;

	/** @var bool */
	protected $closed = false;

	public function __construct(FactionsPP $plugin) {
		$this->plugin = $plugin;
		$plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
	}

	public function getPlugin() : FactionsPP {
		return $this->plugin;
	}

	/**
	 * Ensure all references to other objects are destroyed to prevent memory leaks
	 */
	public function close() {
		if(!$this->closed) {
			$this->closed = true;
			unset($this->plugin);
		}
	}

	public function __destruct() {
		$this->close();
	}

}