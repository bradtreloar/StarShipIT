<?php
namespace Treloar\StarShipIT;

use Treloar\StarShipIT\Exception\RequestFailedException;

class WebServiceClient
{
  // the base uri for all api requests
  protected $uri;

  // API key belonging to a StarShipIT account
  protected $apiKey;

  // Subscription key belonging to a StarShipIT account subscription
  protected $apimKey;

  /**
   * @param $apiKey
   *    API key belonging to a StarShipIT account
   * @param $apimKey   
   *    Subscription key belonging to a StarShipIT account subscription
   * @param $uri   
   *    The base URI for the API
   */
  public function __construct($apiKey, $apimKey, $uri) {
    $this->apiKey = $apiKey;
    $this->apimKey = $apimKey;
    $this->uri = $uri;
    $this->client = new \GuzzleHttp\Client([
      'base_uri' => $this->uri,
    ]);
  }

  /**
   * Sands an HTTP request to the web service and
   * 
   * @param $method
   *    The HTTP method to be used for the request
   * @param $path
   *    The path for the endpoint
   * @param $body
   *    The data to be sent with the request
   * 
   * @return
   *    The response data
   */
  public function request($method, $path, $options) {
    $options = array_merge($options, [
      'headers' => [
        'StarShipIT-Api-Key' => $this->apiKey,
        'Ocp-Apim-Subscription-Key' => $this->apimKey,
      ],
    ]);

    $response =  $this->client->request($method, $path, $options, ['debug' => true]);
    return $response;
  }

  /**
   * Calls the Shipping Rates endpoint
   */
  public function getRates($address, $weight) {
    $query = array_merge($address->getAssoc(true), [ 'weight' => $weight ]);
    // send request
    $response = $this->request('GET', 'rates', [ 'query' => $query ]);
    $data = json_decode($response->getBody(), true);

    if ($data['success']) {
      return $data['rates'];
    }
    else {
      throw new RequestFailedException(
        'The Rates API call was unsuccessful',
        $data['errors']
      );
    }
  }

  /**
   * Calls the Address Validation endpoint.
   * Adds suggestions to address if any are returned.
   * 
   * @param Treloar\StarShipIT\Address the address to validate
   * 
   * @return boolean true if the address is valid
   */
  public function validateAddress(&$address) {
    $query = array_merge($address->getAssoc(true), [ 'weight' => $weight ]);
    // send request
    $response = $this->request('GET', 'address/validate', [ 'query' => $query ]);
    $data = json_decode($response->getBody(), true);

    if ($data['success']) {
      // save suggestions if exist and address is invalid
      if (!$data['valid'] && isset($data['suggestions']))
        $address->suggestions = $data['suggestions'];
      // return validity of address
      return $data['valid'];
    }
    else {
      // include server's error messages in the exception
      throw new RequestFailedException(
        'The Address Validation API call was unsuccessful',
        $data['errors']
      );
    }
  }
}