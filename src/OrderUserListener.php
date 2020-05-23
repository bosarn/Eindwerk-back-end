<?php


namespace App;


use App\Entity\Orders;

use phpDocumentor\Reflection\Type;
use Symfony\Component\Security\Core\Security;

class OrderUserListener
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
             $orders->setUser($this->security->getUser());
             $details = $orders->getDetails();

         $result = array();
             foreach($details as $detail ) {
                 $pongo = $detail->getQuantity();
                 array_push($result,"Quantity:".$pongo);
                 $bongo = $detail->getObjects()->getName();
                 array_push($result,"Object:".$bongo);
                 $preinvoice = $detail->getObjects()->getPrice();

                 foreach($preinvoice as $invoice){
                     $bingo = $invoice->getValue();
                     array_push($result,"Prices:".$bingo);
                 }
             }
            $resultyes = implode(" ",$result);

            $orders->setInvoice($resultyes);
        }
        // TODO Fill in Invoice

    }

}