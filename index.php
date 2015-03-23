<?php
use Zend\Http\Request;
use Zend\Http\Headers;
use Phrases\Application;
use Phrases\Persistence;
use Phrases\Entity\Phrase;
use Phrases\Http\Response\Send;

require __DIR__ . '/vendor/autoload.php';

$phrase = new Phrase('Lorem ipsum', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod.');
$persistance = new Persistence\Memory([$phrase]);

$request = new Request;
$request->setMethod('GET');
$request->setUri('http://localhost/');
$request->setHeaders(Headers::fromString('Accept: plain/text'));

$app = new Application($persistance, $request);
$response = $app->fetchResponse();

Send::response($response);
