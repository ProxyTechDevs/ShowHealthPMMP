<?php
namespace ShowHealthPMMP;
use pocketmine\scheduler\Task as PluginTask;
use pocketmine\Player;
use ShowHealthPMMP\Main;
use pocketmine\entity\Entity;
use pocketmine\utils\TextFormat;

class Task extends PluginTask{

    public function __construct(Main $plugin, Player $player){
        $this->plugin = $plugin;
        $this->player = $player;
    }
   
	public function onRun(int $tick){
		foreach($this->plugin->getServer()->getOnlinePlayers() as $player){
      $prcnt = ($player->getHealth()/ $player->getMaxHealth()) * 20;
      $player->getDataPropertyManager()->setString(Entity::DATA_SCORE_TAG, TextFormat::BOLD . TextFormat::RED."❤ ".$prcnt."%");
		}
	}
}
