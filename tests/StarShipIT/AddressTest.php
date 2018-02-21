<?php
namespace Treloar\StarShipIT;

use PHPUnit\Framework\TestCase;
use Treloar\StarShipIT\Address;

class AddressTest extends TestCase
{
  public function testCountryCode() {
    // try an easy one
    $address = new Address([ "country"   => "Australia" ]);
    $this->assertEquals('AU', $address->getCountryCode());

    // try a trickier one
    $address = new Address([ "country"   => "Lao People's Democratic Republic" ]);
    $this->assertEquals('LA', $address->getCountryCode());

    // send a dud
    $address = new Address([ "country"   => "Autobot Moon Base One" ]);
    $this->assertEquals(false, $address->getCountryCode());
  }

  public function testAssoc() {
    // try an easy one
    $testAddress = [
      "street"    => "125 O'Sullivan Beach Road",
      "suburb"    => "Lonsdale",
      "city"      => "",
      "state"     => "SA",
      "post_code" => "5160",
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