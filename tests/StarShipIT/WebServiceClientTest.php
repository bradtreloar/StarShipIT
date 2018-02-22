<?php
namespace Treloar\StarShipIT;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Constraint\IsType as PHPUnit_IsType;
use Treloar\StarShipIT\Address;
use Treloar\StarShipIT\WebServiceClient;

class WebServiceClientTest extends TestCase
{
  /**
   * Initialises WebServiceClient to be reused
   * across multiple tests
   */
  protected function setUp() {
    // initialise the client with dev credentials
    // defined as constants in bootstrap file
    $this->client = new WebServiceClient(API_KEY, APIM_KEY, 'https://api.starshipit.com/api/');
  }

  /**
   * Tests whether client gets a suggestion for an incomplete
   * address that provides the minimum required fields
   */
  public function testSuggestion() {
    // this address contains the minimum required fields
    // to return a suggestion from the server
    $address = new Address([
      "street"    => "Parliament Drive",
      "post_code" => "2600",
      "country"   => "Australia",
    ]);

    $is_valid = $this->client->validateAddress($address);

    // The submitted address should be considered invalid by the server 
    $this->assertFalse($is_valid);

    // the server should return a single suggestion
    $expectedSuggestions = [
      [
        "street"    => "Parliament Drive",
        "suburb"    => "Capital Hill",
        "city"      => "",
        "state"     => "ACT",
        "post_code" => "2600",
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
      "street"    => "Parliament Drive",
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
      "street"    => "Parliament Drive",
      "suburb"    => "Capital Hill",
      "city"      => "",
      "state"     => "ACT",
      "post_code" => "2600",
      "country"   => "Australia"
    ]);

    $rates = $this->client->getRates($address, 25.0);
    // check if an array was returned
    $this->assertInternalType(PHPUnit_IsType::TYPE_ARRAY, $rates);
  }
}