<?php
namespace ShowHealthPMMP;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityRegainHealthEvent;
use pocketmine\Player;

class Main extends PluginBase implements Listener {

    public function onEnable() {
        $this->saveDefaultConfig();
        $this->registerEvents();
        $this->getLogger()->info(TextFormat::GREEN . "ShowHealthPMMP Enabled");
    }

    public function onDisable() {
        $this->getLogger()->info(TextFormat::GREEN . "ShowHealthPMMP Disabled");
    }
    
    public function registerEvents() {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        if(is_dir($this->getServer()->getPluginPath() . "PureChat")){
            $this->getServer()->getPluginManager()->registerEvents(new GroupChange($this), $this);
        }
        elseif(is_dir($this->getServer()->getPluginPath() . "EssentialsPE")) {
            $this->getServer()->getPluginManager()->registerEvents(new NickChange($this), $this);
        }
    }
    
    public function translateColors($symbol, $message){
	
        $message = str_replace($symbol."0", TextFormat::BLACK, $message);
        $message = str_replace($symbol."1", TextFormat::DARK_BLUE, $message);
        $message = str_replace($symbol."2", TextFormat::DARK_GREEN, $message);
        $message = str_replace($symbol."3", TextFormat::DARK_AQUA, $message);
        $message = str_replace($symbol."4", TextFormat::DARK_RED, $message);
        $message = str_replace($symbol."5", TextFormat::DARK_PURPLE, $message);
        $message = str_replace($symbol."6", TextFormat::GOLD, $message);
        $message = str_replace($symbol."7", TextFormat::GRAY, $message);
        $message = str_replace($symbol."8", TextFormat::DARK_GRAY, $message);
        $message = str_replace($symbol."9", TextFormat::BLUE, $message);
        $message = str_replace($symbol."a", TextFormat::GREEN, $message);
        $message = str_replace($symbol."b", TextFormat::AQUA, $message);
        $message = str_replace($symbol."c", TextFormat::RED, $message);
        $message = str_replace($symbol."d", TextFormat::LIGHT_PURPLE, $message);
        $message = str_replace($symbol."e", TextFormat::YELLOW, $message);
        $message = str_replace($symbol."f", TextFormat::WHITE, $message);
	
        $message = str_replace($symbol."k", TextFormat::OBFUSCATED, $message);
        $message = str_replace($symbol."l", TextFormat::BOLD, $message);
        $message = str_replace($symbol."m", TextFormat::STRIKETHROUGH, $message);
        $message = str_replace($symbol."n", TextFormat::UNDERLINE, $message);
        $message = str_replace($symbol."o", TextFormat::ITALIC, $message);
        $message = str_replace($symbol."r", TextFormat::RESET, $message);
	
        return $message;
    }
    
    public function onJoin(PlayerJoinEvent $event) {
        $player = $event->getPlayer();
        $config = $this->getConfig()->getAll();
        if($config["Nametag"]["Enabled"] === true) {
            $this->getScheduler()->scheduleDelayedTask(new Task($this, $player), 1);
        }
    }
    
    public function onRespawn(PlayerRespawnEvent $event) {
        $player = $event->getPlayer();
        $config = $this->getConfig()->getAll();
        if($config["Nametag"]["Enabled"] === true) {
            $this->getScheduler()->scheduleDelayedTask(new Task($this, $player), 1);
        }
    }
    
 public function onHurt(EntityDamageEvent $event) {
        $player = $event->getEntity();
        $config = $this->getConfig()->getAll();
        if($config["Nametag"]["Enabled"] === true) {
            if ($player instanceof Player) {
                $this->getScheduler()->scheduleDelayedTask(new Task($this, $player), 1);
            }
        }
    }
    
    public function onHeal(EntityRegainHealthEvent $event) {
        $player = $event->getEntity();
        $config = $this->getConfig()->getAll();
        if($config["Nametag"]["Enabled"] === true) {
            $this->getScheduler()->scheduleDelayedTask(new Task($this, $player), 1);
        }
    }
    
    public function setHealthNametag(Player $player) {
        $config = $this->getConfig()->getAll();
        if($player instanceof Player) {
            $statusformat = ($config["Nametag"]["Format"]);
            if(is_dir($this->getServer()->getPluginPath() . "PureChat")){
                $name = $this->getServer()->getPluginManager()->getPlugin("PureChat")->getNametag($player, ($player->getLevel()->getName()));
                $player->setNameTag($this->translateColors("&", ($name . "\n" . (str_replace("@health", $this->getHealthStatus($player), $statusformat)))));
            }
            elseif(is_dir($this->getServer()->getPluginPath() . "EssentialsPE")) {
                $nick = ($this->getServer()->getPluginManager()->getPlugin("EssentialsPE")->getNewNick($player));
                $name = ($this->translateColors("&", ($nick . "\n" . (str_replace("@health", $this->getHealthStatus($player), $statusformat)))));
                $this->getServer()->getPluginManager()->getPlugin("EssentialsPE")->setNick($name);
            }
            else {
                $name = $player->getName();
                $player->setNameTag($this->translateColors("&", ($name . "\n" . (str_replace("@health", $this->getHealthStatus($player), $statusformat)))));
            }
        }
    }
    
    public function getHealthStatus(Player $player) {
        $config = $this->getConfig()->getAll();
        $symbol = $config["Symbol"];
        $currenthealth = ($player->getHealth());
        $usedhealth = (($player->getMaxHealth()) - ($player->getHealth()));
        $healthstatus = (($config["Health-Color"]) . (str_repeat($symbol, $currenthealth / 2)) . TextFormat::RESET . ($config["Used-Health-Color"]) . (str_repeat($symbol, $usedhealth / 2)));
        return $healthstatus;
    }

}
