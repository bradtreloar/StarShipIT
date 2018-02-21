<?php
namespace Treloar\StarShipIT;

use PHPUnit\Framework\TestCase;
use Treloar\StarShipIT\Address;

class AddressTest extends TestCase
{
  /**
   * Tests whether Address returns correct country code
   * given a country name
   */
  public function testCountryCode() {
    // try an easy one
    $address = new Address([ "country" => "Australia" ]);
    $this->assertEquals('AU', $address->getCountryCode());

    // try one with spaces and an apostrophe
    $address = new Address([ "country" => "Lao People's Democratic Republic" ]);
    $this->assertEquals('LA', $address->getCountryCode());

    // try a dud
    $address = new Address([ "country" => "Autobot Moon Base One" ]);
    $this->assertEquals(false, $address->getCountryCode());
  }

  /**
   * Tests whether Address returns associative array
   * of itself, both with country name and ISO ALPHA-2 country code
   */
  public function testAssoc() {
    $testAddress = [
      "street"    => "Parliament Drive",
      "suburb"    => "Capital Hill",
      "city"      => "",
      "state"     => "ACT",
      "post_code" => "2600",
      "country"   => "Australia"
    ];

    $address = new Address($testAddress);
    $address->getCountryCode();

    // test country version
    unset($testAddress['city']);
    $this->assertEquals($testAddress, $address->getAssoc());

    // test country code version
    unset($testAddress['country']);
    $testAddress['country_code'] = 'AU';
    $this->assertEquals($testAddress, $address->getAssoc(true));
  }
}