<?php
namespace Treloar\StarShipIT\Exception;

class RequestFailedException extends \Exception
{
  protected $errors;
  
  /**
   * @param array $errors the errors array returned by the server
   */
  public function __construct($message = null, $errors = []) {
    $this->errors = $errors;
    parent::__construct($message);
  }

  public function __toString() {
    return __CLASS__ . ": [{$this->message}. Errors: " . serialize($this-errors) . "\n";
  }
}