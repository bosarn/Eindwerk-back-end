<?php


namespace App;


use App\Entity\Orders;
use App\Entity\User;
use Dompdf\Dompdf;
use Dompdf\Options;
use phpDocumentor\Reflection\Type;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;

class OrderUserListener extends AbstractController
{
    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @param Orders $orders
     *
     */
    public function prePersist(Orders $orders)
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
            $invoiceid = 'uploads/invoices/Invoice-order-'.$uniquestring.'.pdf' ;

            $html = $this->renderView('pdf.html.twig',[
                'order' => $orders,
                'details' => $details,
                'orderid' => $uniquestring
            ]);

            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $output = $dompdf->output();

            // todo get id does not work for some reason, too bad! Change to uniqueid on construct?

            file_put_contents($invoiceid, $output);
            $orders->setInvoice($invoiceid);

        }

    }

}
