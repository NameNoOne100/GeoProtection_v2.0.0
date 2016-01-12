<?php

  namespace GeoProtection;
  
  use pocketmine\plugin\PluginBase;
  use pocketmine\event\Listener;
  use pocketmine\event\player\PlayerJoinEvent;
  
  class Main extends PluginBase implements Listener {
  
    public function onEnable() {

      $this->getServer()->getPluginManager()->registerEvents($this, $this);
      @mkdir($this->getDataFolder());
      chdir($this->getDataFolder());
      touch("users.txt");
      touch("logs.txt");
      touch("reason.txt");
      touch("message.txt");
      file_put_contents("users.txt", "user: ");
      file_put_contents("users.txt", "city: ", FILE_APPEND);
      file_put_contents("logs.txt", "logs:\n");
      file_put_contents("reason.txt", "reason: ");
      file_put_contents("message.txt", "message: ");
      
    }
    
    public function onJoin(PlayerJoinEvent $event) {
    
      $player = $event->getPlayer();
      $player_name = $player->getName();
      $player_ip = $player->getAddress();
      $file = file_get_contents("users.txt");
      $token = '#####';
      $file = str_replace("\n", $token, $file);
      
      if(preg_match('/(?P<match>user:(.)*)(' . $token . '){1}[^ ]+/Uu', $file, $matches)) {
      
        $match = str_replace($token, "\n", $matches['match']);
        
      }
      
      $file = str_replace($token, "\n", $file);
      $user = str_replace("user:", "", $match);
      $player_geo = json_decode(file_get_contents("http://ipinfo.io/$player_ip"));
      $player_city = $player_geo->city;
      $city = substr(strstr(file_get_contents("users.txt"), "city:"), strlen("city:"));
      $kick_reason = substr(strstr(file_get_contents("reason.txt"), "reason:"), strlen("reason:"));
      $message = substr(strstr(file_get_contents("message.txt"), "message:"), strlen("message:"));
      
      if($player_name == $user) {
      
        if($player_city != $city) {
        
          $player->kick($kick_reason);
          
          $this->getServer()->broadcastMessage($message);
          
        }
        
      }
      
    }
    
  }
  
?>
