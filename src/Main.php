<?php

namespace App;

use Auth0\SDK\JWTVerifier;

class Main {

  protected $token;
  protected $tokenInfo;

  public function setCurrentToken($token) {

    try {
      $verifier = new JWTVerifier([
        'supported_algs' => ['RS256'],
        'valid_audiences' => [getenv('AUTH0_AUDIENCE')],
        'authorized_iss' => ['https://' . getenv('AUTH0_DOMAIN') . '/']
      ]);

      $this->token = $token;
      $this->tokenInfo = $verifier->verifyAndDecode($token);
    }
    catch(\Auth0\SDK\Exception\CoreException $e) {
      throw $e;
    }
  }

  public function checkScope($scope){
    if ($this->tokenInfo){
      $scopes = explode(" ", $this->tokenInfo->scope);
      foreach ($scopes as $s){
        if ($s === $scope)
          return true;
      }
    }

    return false;
  }

  // public function publicEndpoint() {
  //   return array(
  //     "status" => "ok",
  //     "message" => "Hello from a public endpoint! You don't need to be authenticated to see this."
  //   );
  // }

  // public function privateEndpoint() {
  //   return array(
  //     "status" => "ok",
  //     "message" => "Hello from a private endpoint! You need to be authenticated to see this."
  //   );
  // }

  // public function privateScopedEndpoint() {
  //   return array(
  //     "status" => "ok",
  //     "message" => "Hello from a private endpoint! You need to be authenticated and a scope of read:messages to see this."
  //   );
  // }

  public function images($page = null, $perPage = null) {
    if(!file_exists(\getcwd(). '/src/files/images.csv')) {
      return array();
    }
    return $this->parseCSV(\getcwd(). '/src/files/images.csv', compact('page', 'perPage'));
  }

  private function parseCSV($path, $pagination = null) {
    $images = array();
    if(isset($pagination) && is_numeric($pagination['page']) && is_numeric($pagination['perPage'])) {
      $page = $pagination['page'];
      $perPage = $pagination['perPage'];
      $start = ($page - 1) * $perPage;
      $end = $page * $perPage;
    }
    $count = 0;
    $file = fopen($path, 'r');
    while (($line = fgetcsv($file)) !== FALSE) {

      if(!isset($start) || !isset($end) || ($count < $end && $count >= $start)) {

        if((isset($start) && isset($end)) && $count >= $end) break;
      
        if(strpos($line[0], getenv('IMG_URL')) >= 0) {
          $currentLine = \str_replace(getenv('IMG_URL'), '', $line[0]);
          list($currentImage['id'], $currentImage['width'], $currentImage['height']) = explode('/', $currentLine);
          $images[] = (object)$currentImage;
        }

      }
      $count++;
    }
    fclose($file);

    return $images;
    
  }

}