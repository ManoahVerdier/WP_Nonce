```php

<?php

class wp_nonce{
  public static $lifetime = 24*3600; /*The default lifetime is a day, in seconds*/
  public static $message = "Are you sure you want to do this?";
  
  protected $name         ; /*A name for the nonce*/
  protected $action       ; /*The action that will be given to construct nonces*/
  
  public function __contruct($action = -1, $name = "_wp_nonce"){
    $this->name   = $name  ;
    $this->action = $action;
  }
  
  public function getName(){
    return $this->name;
  }
  
  public function setName($name){
    $this->name = $name;
  }

  public function setAction($action){
    $this->action = $action;
  }  
  
  public function getAction(){
    return $this->action;
  }
  
  public function getNonce(){
    return wp_create_nonce( $this->action );
  }
  
  public function getNonceUrl($bareUrl){
    return wp_nonce_url($bareUrl,$this->action,$this->name);
  }
  
  public function getNonceField($referer=true,$echo=true){
    if($echo)
      wp_nonce_field($this->action,$this->name,$referer,$echo);
    else
      return wp_nonce_field($this->action,$this->name,$referer,$echo);
  }
  
  public function __destroy(){
    unset($this->name  );
    unset($this->action);
  }
  
  public function __toString(){
    return "Wordpress Nonce Object - Name : ".$this->name." action : ".$this->action;
  }
  
  public function ays()
  {
    wp_nonce_ays($action);
  }
  
  public static function checkAdminNonce($action=-1,$nonce="_wp_nonce"){
    return check_admin_referer($action,$nonce);
  }
  
  public static function checkAjaxNonce($action=-1,$nonce=false,$die=true){
    return check_ajax_referer($action,$nonce,$die);
  }
  
  public static function checkNonce($action,$nonce){
    return wp_verify_nonce($nonce,$action); 
  }
  
  public static function setLifetime($newLifetime){
    if($newLifetime>0){
      self::$lifetime = $newLifetime;
      add_filter( 'nonce_life', function () { return $newLifetime; } );
      return true;
    }
    return false;
  }
  
  public static function getLifetime(){
    return self::$lifetime;
  }
  
  public static function setMessage($newMessage){
    self::$message = $newMessage;
    add_filter('gettext', array( 'WP_Nonce', 'nonceMessage' ));
  }

  private static function nonceMessage($translation){
    if ($translation == 'Are you sure you want to do this?')
      return self::$message;
    else
      return $translation;
  } 
  
  public static function getMessage(){
    return self::$message;
  }
  
  public static function setAdditionalAdminControls($functionName){
    if(function_exists($functionName)
      add_action( 'check_admin_referer', "$functionName", 10, 2 );
  }
  
  public static function setAdditionalAjaxControls($functionName){
    if(function_exists($functionName)
      add_action( 'check_ajax_referer', "$functionName", 10, 2 );
  }
  
}

```
