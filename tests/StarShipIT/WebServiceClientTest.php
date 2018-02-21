<?php
namespace Treloar\StarShipIT;

use PHPUnit\Framework\TestCase;
use Treloar\StarShipIT\Address;
use Treloar\StarShipIT\WebServiceClient;

class WebServiceClientTest extends TestCase
{
  protected function setUp() {
    // initialise the client with dev credentials
    $this->client = new WebServiceClient(
      'fa1bd2ea6f514ec694452d088ace12ca', // Trial account API key
      'a2b0680d463b47adaa7a068202837dd4', // Development Subscription key
      'https://api.starshipit.com/api/'
    );
  }

  /**
   * Tests whether client gets a suggestion for an incomplete
   * address that provides the minimum required fields
   */
  public function testSuggestion() {
    
    $address = new Address([
      "street"    => "125 O'Sullivan Beach Road",
      "post_code" => "5160",
      "country"   => "Australia",
    ]);

    $is_valid = $this->client->validateAddress($address);

    // The submitted address should be considered invalid by the server 
    $this->assertFalse($is_valid);

    // the server should return a single suggestion
    $expectedSuggestions = [
      [
        "street"    => "125 O'Sullivan Beach Road",
        "suburb"    => "Lonsdale",
        "city"      => "",
        "state"     => "SA",
        "post_code" => "5160",
        "country"   => "Australia"
      ],
    ];

    $this->assertEquals($expectedSuggestions, $address->suggestions, $canonicalize = true);
  }

  /**
   * Tests whether client gets no suggestions an incomplete
   * address that fails to provide a postcode
   */
  public function testNoSuggestionWithoutPostCode() {
    // an address that should fail to return any suggestions
    // since it lacks a postcode
    $address = new Address([
      "street"    => "125 O'Sullivan Beach Road",
      "country"   => "Australia",
    ]);

    $is_valid = $this->client->validateAddress($address);

    // The submitted address should be considered invalid by the server 
    $this->assertFalse($is_valid);

    // The server should return no suggestions
    $this->assertEquals(NULL, $address->suggestions, $canonicalize = true);
  }

  /**
   * Tests whether client gets shipping rates for a valid
   * address and weight
   */
  public function testRates() {
    // a known valid address
    $address = new Address([
      "street"    => "125 O'Sullivan Beach Road",
      "suburb"    => "Lonsdale",
      "city"      => "",
      "state"     => "SA",
      "post_code" => "5160",
      "country"   => "Australia"
    ]);

    $rates = $this->client->getRates($address, 25.0);
    // check if an array was returned
    $this->assertInternalType($rates, PHPUnit_IsType::TYPE_ARRAY);
  }
}