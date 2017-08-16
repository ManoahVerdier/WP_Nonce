<?php
/**
  * test_WP_nonce.php
  *
  * The test file, performing unit tests on WP_nonce.php methods
  *
  * @license   http://www.opensource.org/licenses/mit-license.php MIT
  * @author Manoah Verdier
  * @version 1.0.0 from 08/16/2017
  */

/**
  * test_WP_nonce
  * The testing class, extending Wordpress built-in unit test class WP_UnitTestCase 
  *
  * Unit test class creating a nonce, and performing tests and verifications on it.
  * Tests includes : created url, fields and their verifications.
  *
  */
  public class test_WP_nonce extends WP_UnitTestCase{
     /** static var string $default_URL representing the default (bare) url to be giver to some tested methods*/
    public static $default_URL = "https://github.com/ManoahVerdier/WP_Nonce/blob/master/php/wp_nonce.php";
    
    /** var WP_Nonce $wp_nonce, an instance of WP_Nonce used for the tests*/
    public $wp_nonce;
    
    /**
      * Just intanciating the wp_nonce in the simplest way (default action =-1 and default name = "_wp_nonce"
      *
      */
    public function __contruct(){
      self::$wp_nonce = new WP_Nonce();
    }
    
    /**
      * Testing the generated nonce.
      * Checking it isn't null or empty
      */
    public function testSimpleNonce(){
      $nonce = self::wp_nonce->getNonce();
      $this->assertNotNull($nonce);
      $this->assertFalse($nonce == "");
    }
    
    /**
      * Testing the generated nonce url.
      * Checking it isn't null or empty
      */
    public function testUrlNonce(){
      $nonce_url = self::wp_nonce->getNonceUrl(test_WP_nonce::default_URL);
      $this->assertNotNull($nonce_url);
      $this->assertFalse($nonce_url == "");
    }
    
    /**
      * Creating a simple nonce and testing its verification
      */
    public function testVerifyNonce(){
      $nonce = self::wp_nonce->getNonce();
      $this->assertTrue(WP_Nonce::checkNonce($nonce));
    }
    
    
    /**
      * Creating a URL nonce and testing its verification
      */
    public function testVerifyUrlNonce(){
      $nonce_url = self::wp_nonce->getNonceUrl(test_WP_nonce::default_URL);
      $this->assertTrue(WP_Nonce::checkNonceURL($nonce_url,self::wp_nonce->name,self::wp_nonce->action));
    }
  }
?>
