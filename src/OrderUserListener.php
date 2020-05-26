<?php


namespace App;


use App\Entity\Orders;

use App\Entity\User;
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
            $result = array();
            $user = $this->security->getUser();
             $orders->setUser($user);
            // fill in user
            array_push($result,
            '---Invoice for : '.$user->getUsername().'---    /nl
            /nl
            /nl
            /nl
            Date ordered:'
            .date_format($orders->getDate(),'d/m/y'),
            $orders->getShippingAdress().
            '/nl
            /nl
            /nl
            Order: '
            );
             // Get order details, objects, prices
             $details = $orders->getDetails();


             foreach($details as $detail ) {

                 $objectDetail = $detail->getQuantity();
                 $price = $detail->getObjects()->getPrice()->first();
                 $objectName = $detail->getObjects()->getName();

                 array_push($result,"Object:".$objectName);
                 array_push($result,"Quantity:".$objectDetail);
                 array_push($result,"Prices:".$price->getValue().'<hr>');

             }
            $finalInvoice = implode(" ",$result);

            $orders->setInvoice($finalInvoice);
        }

    }

}