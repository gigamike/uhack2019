<?php

namespace Cron\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Mail\Message as Message;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mail\Transport\Sendmail as Sendmail;

class IndexController extends AbstractActionController
{
  /*
  *
  * /Applications/MAMP/bin/php/php7.1.31/bin/php /Users/michaelgerardgalon/Sites/hackathons/uhack2019.gigamike.net/public_html/index.php cron-test
  * /usr/bin/php /var/www/dish2019.gigamike.net/public_html/index.php cron-test
  */
  public function indexAction()
  {
    $subject = "New Purchase Order From ULI PH";
    $message = "New Purchase Order From ULI PH. Purchase Order No. 1 Pls. visit <a href=\"http://uhack2017.gigamike.net/\">http://uhack2017.gigamike.net/</a>";

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
}
