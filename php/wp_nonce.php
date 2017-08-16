
<?php
/**
  * WP_Nonce.php
  *
  * The main source file, containing the WP_Nonce class.
  *
  * @license   http://www.opensource.org/licenses/mit-license.php MIT
  * @author Manoah Verdier
  * @version 1.0.0 from 07/12/2017
  */

/**
  * WP_Nonce
  * The Nonce class, representing Nonces and nonces management.
  *
  * Model class representing nonces : creation and utilisation.
  * Includes static management of nonces : general settings and verifiers.
  *
  */

class WP_Nonce{
  
  /** var int $lifetime representing the lifetime of nonces in wordpress, in seconds. Wordpress cuts it in half*/
  public static $lifetime = 24*3600; /*The default lifetime is a day*/
  /** var string $message the message displayed when the check fails*/
  public static $message = "Are you sure you want to do this?";
  
  /** var string $name is the name of the nonce, useful at least  for Urls and Fields*/
  protected $name         ; 
  /** var string $action is the description of the action performed using the nonce.*/
  protected $action       ; 
  
  /**
    * Initializes all attributes
    *
    * @param string $action optional
    * @param string $name   optional
    */
  public function __contruct($action = -1, $name = "_wp_nonce"){
    $this->name   = $name  ;
    $this->action = $action;
  }
  
  /**
    * Simple getter for the name.
    *
    * @return string
    */
  public function getName(){
    return $this->name;
  }
  
  /**
    * Simple setter for the name.
    *
    * @param string $name required
    *
    * @return void
    */
  public function setName($name){
    $this->name = $name;
  }

  /**
    * Simple getter for the action.
    *
    * @return string
    */
  public function getAction(){
    return $this->action;
  }
  
  /**
    * Simple setter for the action.
    *
    * @param string $action required
    *
    * @return void
    */
  public function setAction($action){
    $this->action = $action;
  }  
  
  /**
    * Generating and returning the nonce
    *
    * Uses the action of the instance to call the wordpress function wp_create_nonce and generate the nonce
    *
    * @return string the nonce text
    */
  public function getNonce(){
    return wp_create_nonce( $this->action );
  }
  
  /**
    * Generating and returning the url paired with the nonce.
    *
    * Uses the action and the name of the instance and a the bareUrl (required) to call
    * the wordpress function wp_nonce_url and generate the url.
    * 
    * @param string $bareUrl required
    *
    * @return string the url
    */
  public function getNonceUrl($bareUrl){
    return wp_nonce_url($bareUrl,$this->action,$this->name);
  }
  
  /**
    * Generating and returning the field codes to insert in the form
    *
    * Uses the action and the name of the instance and two booleans parameters to call
    * the wordpress function wp_nonce_field and generate the code, which is either returned or printed
    * as the wordpress function allow to set it.
    * 
    * @param boolean $referer optional weither to check the referer or not
    * @param boolean $echo    optional weither to print or return the fields code
    *
    * @return string the code if required with the $echo boolean
    */
  public function getNonceField($referer=true,$echo=true){
    /*If the user wants the code printed, nothing to return*/
    if($echo)
      wp_nonce_field($this->action,$this->name,$referer,$echo);
    else
      return wp_nonce_field($this->action,$this->name,$referer,$echo);
  }
  
  /**
    * Simple destructor to release variables.
    *
    * @return void
    */
  public function __destroy(){
    unset($this->name  );
    unset($this->action);
  }
  
  
  /**
    * Classical toString method, displays name and action.
    *
    * @return string
    */
  public function __toString(){
    return "Wordpress Nonce Object - Name : ".$this->name." action : ".$this->action;
  }
  
  /**
    * Displaying the "Are you sure message" or its replacement calling the wordpress corresponding version
    *
    * @return string
    */
  public function ays()
  {
    wp_nonce_ays($action);
  }
  
  /* STATIC CHECKING METHODS */
  
  /**
    * Static function checking a nonce in the backend calling the wordpress function check_admin_referer
    *
    * @param string $action optional
    * @param string $nonce  optional the nonce text
    *
    * @return string
    */
  public static function checkAdminNonce($action=-1,$nonce="_wp_nonce"){
    return check_admin_referer($action,$nonce);
  }
  
  /**
    * Static function checking a nonce sent via ajax calling the wordpress function check_ajax_referer
    *
    * @param string  $action optional
    * @param string  $nonce  optional
    * @param boolean $die
    *
    * @return string
    */
  public static function checkAjaxNonce($action=-1,$nonce=false,$die=true){
    return check_ajax_referer($action,$nonce,$die);
  }
  
  /**
    * Static function checking a "normal" nonce given the text and the action
    *
    * @param string  $nonce  required
    * @param string  $action optional
    *
    * @return string
    */
  public static function checkNonce($nonce,$action=-1){
    return wp_verify_nonce($nonce,$action); 
  }
 
  /**
    * Static function checking a nonce url given the url and the nonce name
    *
    * @param string  $url  required
    * @param string  $name optional
    *
    * @return string
    */
  public static function checkNonce($url,$name,$action=-1){
    $url_content = parse_url($url);
    if($url_content != ""){
     parse_str($url_content,$url_param);
    }
    
    if(sizeof($url_param)>0 && $url_param[$name]!=""){
     return wp_verify_nonce($url_param[$name],$action); 
    }
   
    return false
  }
  
  /* STATIC CONFIG METHODS */
  
  /**
    * Static function setting the lifetime of nonces (in seconds), the default being 1 day
    *
    * The new value is stored in the static variable $lifetime, for reference only (info available via getter).
    * The real change is made via the add_filter function.
    * The change is made only if the new value is positive.
    *
    * @param int  $newLifetime required in seconds.
    *
    * @return boolean
    */
  public static function setLifetime($newLifetime){
    if($newLifetime>0){
      self::$lifetime = $newLifetime;
      add_filter( 'nonce_life', function () { return $newLifetime; } );
      return true;
    }
    return false;
  }
  
  /**
    * Static function, a getter returning the nonces lifetime.
    *
    * @return void
    */
  public static function getLifetime(){
    return self::$lifetime;
  }
  
  /**
    * Static function setting the message displayed when the check fails.
    *
    * The new value is stored in the static variable $message, for reference (getter) and to be available inside 
    * the nonceMessage function, which is called via the translation message to replace the default message.
    * The addFilter function bind the nonceMessage function to the getText action.
    *
    * @param string  $newMessage required in seconds.
    *
    * @return void
    */
  public static function setMessage($newMessage){
    self::$message = $newMessage;
    add_filter('gettext', array( 'WP_Nonce', 'nonceMessage' ));
  }
  
  /**
    * Static private function returns the changed message if required, any text given in parameter else.
    *
    * This function is called everytime a text (subject to translation) is called. If the text to translate is 
    * the ays message, it is replaced with the static variable $message. If not, the text is returned unchanged.
    *
    * @param string  $translation required.
    *
    * @return string
    */
  private static function nonceMessage($translation){
    if ($translation == 'Are you sure you want to do this?')
      return self::$message;
    else
      return $translation;
  } 
  
  /**
    * Static function, a getter returning the nonces default message
    *
    * @return void
    */
  public static function getMessage(){
    return self::$message;
  }
  
  /**
    * Static private function sets additionnal controls for backend nonces.
    *
    * This function checks if the function name given corresponds to an existing function, and if yes, bind it
    * via the wordpress function add_action.
    *
    * @param string  $functionName required.
    *
    * @return void
    */
  public static function setAdditionalAdminControls($functionName){
    if(function_exists($functionName)
      add_action( 'check_admin_referer', "$functionName", 10, 2 );
  }
  
  /**
    * Static private function sets additionnal controls for ajax nonces.
    *
    * This function checks if the function name given corresponds to an existing function, and if yes, bind it
    * via the wordpress function add_action.
    *
    * @param string  $functionName required.
    *
    * @return void
    */
  public static function setAdditionalAjaxControls($functionName){
    if(function_exists($functionName)
      add_action( 'check_ajax_referer', "$functionName", 10, 2 );
  }
  
}

