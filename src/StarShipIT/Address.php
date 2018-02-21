<?php
namespace Treloar\StarShipIT;

use GuzzleHttp\Client;
use Treloar\StarShipIT\Country;

class Address
{
  protected $orderId;
  protected $street;
  protected $suburb;
  protected $city;
  protected $state;
  protected $postCode;
  protected $country;

  // address suggestions
  public $suggestions;


  public function __construct($address) {
    $this->orderId  = isset($address['order_id'])  ? $address['order_id'] : null;
    $this->street   = isset($address['street'])    ? $address['street'] : null;
    $this->suburb   = isset($address['suburb'])    ? $address['suburb'] : null;
    $this->city     = isset($address['city'])      ? $address['city'] : null;
    $this->state    = isset($address['state'])     ? $address['state'] : null;
    $this->postCode = isset($address['post_code']) ? $address['post_code'] : null;
    $this->country  = isset($address['country'])   ? $address['country'] : null;
    if ($this->country)
      $this->getCountryCode();
  }

  /**
   * Builds an assoc array from this address
   */
  public function getAssoc($countryCode = false) {
    $address = array();

    // only add query params for those attributes that have been set
    if ($this->orderId)  $address['order_id']  = $this->orderId;
    if ($this->street)   $address['street']    = $this->street;
    if ($this->suburb)   $address['suburb']    = $this->suburb;
    if ($this->city)     $address['city']      = $this->city;
    if ($this->state)    $address['state']     = $this->state;
    if ($this->postCode) $address['post_code'] = $this->postCode;
    if ($this->country)
      // return either the ISO ALPHA-2 country code or the country name
      if ($countryCode)
        // generate the country code if it doesn't exist yet
        $address['country_code'] = $this->countryCode ? $this->countryCode : $this->getCountryCode;
      else
        $address['country'] = $this->country;

    return $address;
  }

  /**
   * Gets the ISO ALPHA-2 country code
   */
  public function getCountryCode() {
    // don't even try to get the code if no country is set on this address
    if (!$this->country)
      return null;
    
    $this->countryCode = Country::getISOAlpha2Code($this->country);
    return $this->countryCode;
  }
}