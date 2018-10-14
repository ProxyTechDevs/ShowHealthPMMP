<?php
namespace ShowHealthPMMP;
use pocketmine\event\Listener;
use EssentialsPE\Events\PlayerNickChangeEvent;
use ShowHealthPMMP\Main;

class NickChange implements Listener {

    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
    }
    
    public function onNickChange(PlayerNickChangeEvent $event) {
        $this->plugin->broadcastMessage("A Players Nick Has Changed");
        $this->player = $event->getPlayer();
        $this->config = $this->plugin->getConfig()->getAll();
        if($this->config["Nametag"]["Enabled"] === true) {
            $this->plugin->getScheduler()->scheduleDelayedTask(new Task($this, $this->player), 1);
        }
    }

}
