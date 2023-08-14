<?php


namespace ItsFestivalVN;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\Config;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use jojoe77777\FormAPI\CustomForm;

class Report extends PluginBase implements Listener{

    public function onEnable():void{
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->report = new Config($this->getDataFolder() . "report.yml", Config::YAML);
    }

    public function onCommand(CommandSender $player, Command $cmd, string $label, array $args) : bool {
    	switch($cmd->getName()){
    		case "report":
    		    if(!$player instanceof Player){
    		    	$player->sendMessage("§6Please, Use This Command In Game");
    		    	return true;
    		    }else{
    		    	$this->ReportMenu($player);
    		    }
    		break;
    	}
    	return true;
    }

    public function ReportMenu($player){
        $form = new CustomForm(function(Player $player, $data){
            if($data === null){
                return true;
            }
            if(!isset($data[0])){
            	$player->sendMessage("§6Please, Enter Name");
                return true;
            }
            if(!isset($data[1])){
            	$player->sendMessage("§6Please, Enter Reason");
                return true;
            }
            $this->report->set($player->getName(), ["Name" => $data[0], "Reason" => $data[1]]);
			$this->report->save();
            $player->sendMessage("§6You Reported Player Name§e ". $data[0] ." §6With Reason §e". $data[1] ."");
            $player->sendMessage("§6Thank You For Reporting, Please Wait For Admin To Solve It");
            $this->getServer()->getLogger()->notice("Player ". $player->getName() ." Just Submitted a Complaint, Go to Report.yml");
        });
        $form->setTitle("§6Report");
        $form->addInput("§6Enter Name:", "ItsFestivalVN");
        $form->addInput("§6Reason:", "Hacking, Fly, ...");
        $form->sendToPlayer($player);
    }
}
