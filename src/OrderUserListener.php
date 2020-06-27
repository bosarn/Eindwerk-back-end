<?php


namespace App;


use App\Entity\Orders;
use App\Entity\User;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;


class OrderUserListener extends AbstractController
{
    /**
     * @var Security
     */
    private $security;
    /**
     * @var MailerInterface
     */
    private $mailer;

    public function __construct(Security $security, MailerInterface $mailer)
    {
        $this->security = $security;
        $this->mailer = $mailer;
    }

    /**
     * @param Orders $orders
     * @return \Symfony\Component\HttpFoundation\JsonResponse|void
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function prePersist(Orders $orders )
    {
        if($orders->getUser()){
            return;
        }
        // listener catches validation of user
        // fills in invoice with data of order
        if($user = $this->security->getUser())
        {
            $user = $this->security->getUser();
             $orders->setUser($user);
            $uploads_directory=$this->getParameter('uploads_directory');
            $details = $orders->getDetails();

            $options = new Options();
            $options->set('isRemoteEnabled', TRUE);
            $dompdf = new Dompdf($options);

            $context = stream_context_create([
                'ssl' => [
                    'verify_peer' => FALSE,
                    'verify_peer_name' => FALSE,
                    'allow_self_signed'=> TRUE
                ]
            ]);
            $dompdf->setHttpContext($context);

            $uniquestring = md5(uniqid());
            $invoiceid = 'system/public/uploads/invoices/Invoice-order-'.$uniquestring.'.pdf' ;

            $html = $this->renderView('pdf.html.twig',[
                'order' => $orders,
                'details' => $details,
                'orderid' => $uniquestring,
                'user' => $user
            ]);

            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $output = $dompdf->output();


            file_put_contents($invoiceid, $output);
            $orders->setInvoice($invoiceid);

            $email = $user->getEmail();
            $mailservice = (new Email())
                ->from('3dprintdomaininfo@gmail.com')
                ->to('bosmansarnoo@gmail.com')
                ->subject('Thank you for your purchase, from 3D-Print-Domain!')
                ->text('Thank you for your purchase! You will find the invoice in the attachments')

                ->attachFromPath($invoiceid);
            $this->mailer->send($mailservice);
        }

    }


}
