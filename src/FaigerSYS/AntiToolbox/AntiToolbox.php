<?php
namespace FaigerSYS\AntiToolbox;

use pocketmine\plugin\PluginBase;

use pocketmine\utils\TextFormat as CLR;

use pocketmine\network\mcpe\protocol\LoginPacket;

use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketReceiveEvent;

class AntiToolbox extends PluginBase implements Listener {
	
	/** @var string */
	private $kickMessage;
	
	public function onEnable() {
		$this->getLogger()->info(CLR::GOLD . 'AntiToolbox loading...');
		
		@mkdir($path = $this->getDataFolder());
		$this->saveResource($file = 'settings.yml');
		$settings = yaml_parse_file($path . $file);
		
		$this->kickMessage = $settings['kick-msg'];
		
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		
		$this->getLogger()->info(CLR::GOLD . 'AntiToolbox loaded!');
	}
	
	public function onReceive(DataPacketReceiveEvent $e) {
		$pk = $e->getPacket();
		if ($pk instanceof LoginPacket) {
			if ($pk->clientId === 0) { // When using Toolbox it sets to 0. I would prefer to compare it with clientId from RakLib session, but it's not possible at the moment
				$e->setCancelled(true);
				$e->getPlayer()->close('', $this->kickMessage);
			}
		}
	}
	
}
