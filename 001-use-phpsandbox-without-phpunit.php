<?php

include 'vendor/autoload.php';

$sandbox = new \PHPSandbox\PHPSandbox();
$sandbox->whitelist('functions', ['print_r']);

ob_start();

try{
  $sandbox->importJSON(array('code' => '<?php echo 124; ?>'));
}catch(\PHPSandbox\Error $err){
  echo "{\"type\":\"error\",\"message\":\"",$err->getMessage()."\"}";
  die;
}

$sandbox->setValidationErrorHandler(function(PHPSandbox\Error $error, PHPSandbox\PHPSandbox $sandbox){
    echo $error->getCode(),"\n";
    if($error->getCode() == PHPSandbox\Error::PARSER_ERROR){ //PARSER_ERROR == 1 
      echo "{\"type\":\"error\",\"message\":\"Parser Error\"}";       
      exit;
    }     
});

try{
  $sandbox->execute();
}catch(\PHPSandbox\Error $err){
  echo "{\"type\":\"error\",\"message\":\"",$err->getMessage()."\"}";
  die;  
}

