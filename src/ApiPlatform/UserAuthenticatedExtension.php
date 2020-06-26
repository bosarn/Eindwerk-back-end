<?php


namespace App\ApiPlatform;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use Doctrine\ORM\QueryBuilder;
use App\Entity\User;
use Symfony\Component\Security\Core\Security;


class UserAuthenticatedExtension implements QueryCollectionExtensionInterface
{
    private $security;

    public function __construct(Security $security)
    {
        // Avoid calling getUser() in the constructor: auth may not
        // be complete yet. Instead, store the entire Security object.
        $this->security = $security;
    }

    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null )
    {
        if ($resourceClass !== User::class) {
           return;
        }

        if( $user = $this->security->getUser()->getid() === null) {
            return;
        }

// get only the user that is authenticated
        $user = $this->security->getUser()->getid();
        $rootAlias = $queryBuilder->getRootAliases()[0];
        $queryBuilder->andWhere(sprintf('%s.id = :identifier', $rootAlias))
                        ->setParameter('identifier', $user);

    }

}