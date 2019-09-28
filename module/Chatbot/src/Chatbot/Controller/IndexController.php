<?php

namespace Chatbot\Controller;

use Zend\Db\Adapter\Adapter;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Zend\Mail\Message as Message;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mail\Transport\Sendmail as Sendmail;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;

use PurchaseOrder\Model\PurchaseOrderEntity;
use PurchaseOrder\Model\PurchaseOrderItemEntity;

class IndexController extends AbstractActionController
{
  public function getPurchaseOrderMapper()
  {
    $sm = $this->getServiceLocator();
    return $sm->get('PurchaseOrderMapper');
  }

  public function getPurchaseOrderItemMapper()
  {
    $sm = $this->getServiceLocator();
    return $sm->get('PurchaseOrderItemMapper');
  }

  public function getUserMapper()
  {
    $sm = $this->getServiceLocator();
    return $sm->get('UserMapper');
  }

  public function indexAction()
  {
    $viewModel = new ViewModel();
    $viewModel->setVariables(array('key' => 'value'))
              ->setTerminal(true);
    return $viewModel;
  }

  public function serverAction()
  {
    $config = [
        // Your driver-specific configuration
        // "telegram" => [
        //    "token" => "TOKEN"
        // ]
    ];

    // Load the driver(s) you want to use
    DriverManager::loadDriver(\BotMan\Drivers\Web\WebDriver::class);

    // Create an instance
    $botman = BotManFactory::create($config);

    // Give the bot something to listen for.
    $botman->hears('hello', function (BotMan $bot) {
      $bot->reply('Welcome to ULI PH eos-Procurement. Do you want to create new purchase order? just say <strong>create po</strong> or <strong>create purchase order</strong>. Or ask about the status of your purchase order? just say <strong>purchase order status PO#</strong>');
    });

    $botman->hears('create purchase order', function (BotMan $bot) {
      $_SESSION['PO']['items'] = array();
      $reply = " Enter item by saying <strong>item name,quantity,unit price</strong>. Example item bulb,1,50";
      $bot->reply($reply);
    });

    $botman->hears('create po', function (BotMan $bot) {
      $_SESSION['PO']['items'] = array();
      $reply = " Enter item by saying <strong>item name,quantity,unit price</strong>. Example item bulb,1,50";
      $bot->reply($reply);
    });

    $botman->hears('item {name},{quantity},{price}', function (BotMan $bot, $name, $quantity, $price) {
      $reply = "";

      if(empty($quantity) || !is_numeric($quantity)){
        $reply = "Invalid quantity.";
      }

      if(empty($price) || !is_numeric($price)){
        $reply = "Invalid price.";
      }

      if(empty($reply)){
        $_SESSION['PO']['items'][] = array(
          'item' => $name,
          'quantity' => $quantity,
          'price' => $price,
        );

        $reply = "Item added!";
        $reply .= " Do you want to add more? Enter item by saying <strong>item name, quantity, unit price</strong>. Example item bulb,1,50. If not say <strong>order confirmed</strong>";
      }

      $bot->reply($reply);
    });

    $botman->hears('order confirmed', function (BotMan $bot) {
      $reply = "Invalid order";

      if(count($_SESSION['PO']['items']) > 0){
        $authService = $this->serviceLocator->get('auth_service');

        $purchaseOrder = new PurchaseOrderEntity();
        $purchaseOrder->setStatus('pending');
        $purchaseOrder->setComments(null);
        $purchaseOrder->setCreatedUserId($authService->getIdentity()->id);
        $this->getPurchaseOrderMapper()->save($purchaseOrder);

        foreach ($_SESSION['PO']['items'] as $row) {
          $purchaseOrderItem = new PurchaseOrderItemEntity();
          $purchaseOrderItem->setPurchaseOrderId($purchaseOrder->getId());
          $purchaseOrderItem->setItem($row['item']);
          $purchaseOrderItem->setQuantity($row['quantity']);
          $purchaseOrderItem->setUnitPrice($row['price']);
          $this->getPurchaseOrderItemMapper()->save($purchaseOrderItem);

          $reply = "Purchase order successfully created!" . " <strong>PO# " . $purchaseOrder->getId() . "</strong>";
        }

        $subject = "New Purchase Order From ULI PH. PO#" . $purchaseOrder->getId();
        $message = "New Purchase Order From ULI PH. PO# " . $purchaseOrder->getId() . " Pls. visit <a href=\"https://uhack2017.gigamike.net/\">http://uhack2017.gigamike.net/</a>";

        $text = new MimePart($message);
        $text->type = "text/plain";

        $html = new MimePart($message);
        $html->type = "text/html";

        $body = new MimeMessage();
        $body->setParts(array($text, $html));

        $mail = new  Message();
        $mail->setFrom('engineer@gigamike.net');
        $mail->addTo('supplier@gigamike.net');
        $mail->setEncoding("UTF-8");
        $mail->setSubject($subject);
        $mail->setBody($body);

        $transport = new SmtpTransport();
        $options   = new SmtpOptions(array(
          'host'              => 'email-smtp.us-east-1.amazonaws.com',
          'port'              => 587,
          'connection_class'  => 'login',
          'connection_config' => array(
            'username' => 'AKIA3GABCPBOQJUCCXOY',
            'password' => 'BAmT8/z5OdlNXmefovH4t1sVXFefWCYcpPPf5KixYx3k',
            'ssl'      => 'tls',
          ),
        ));
        $transport->setOptions($options);
        $transport->send($mail);
      }

      $bot->reply($reply);
    });

    // Start listening
    $botman->listen();

    return $this->getResponse();
  }

  private function _curl($url, $post = null, $headers = array()){
    $ch = curl_init();

		$countPost = count($post);
		if(!is_null($post)){
			curl_setopt($ch, CURLOPT_POST, $countPost);
  		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		}

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		if(count($headers) > 0){
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		}

    $curlHeader = curl_getinfo($ch);
    $curlResult = curl_exec($ch);
    $curlErrorMessage = curl_error($ch);
    $curlErrorNo = curl_errno($ch);
    curl_close($ch);

    $results = array();
    $results['headers'] = $curlHeader;
    $results['error_number'] = $curlErrorNo;
    $results['error_message'] =$curlErrorMessage;
    $results['result'] = $curlResult;

    return $results;
  }
}
