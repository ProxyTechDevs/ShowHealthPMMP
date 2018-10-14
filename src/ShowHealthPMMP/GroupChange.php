<?php
namespace ShowHealthPMMP;
use pocketmine\event\Listener;
use _64FF00\PurePerms\event\PPGroupChangedEvent;
use ShowHealthPMMP\Main;

class GroupChange implements Listener {

    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
    }
    
    public function onGroupChange(PPGroupChangedEvent $event) {
        $this->player = $event->getPlayer();
        $this->config = $this->plugin->getConfig()->getAll();
        if($this->config["Nametag"]["Enabled"] === true) {
            $this->plugin->getScheduler()->scheduleDelayedTask(new Task($this->plugin, $this->player), 1);
        }
    }

}
