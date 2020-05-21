<?php


namespace App;


use App\Entity\Orders;
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

        if($user = $this->security->getUser())
        {
             $orders->setUser($this->security->getUser());
        }

    }

}