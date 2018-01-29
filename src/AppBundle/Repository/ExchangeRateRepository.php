<?php

namespace AppBundle\Repository;
use AppBundle\Entity\ExchangeRate;

/**
 * ExchangeRateRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ExchangeRateRepository extends \Doctrine\ORM\EntityRepository
{
    // mise à jour des taux avec la commande

    public function updateExchangeRate($rates)
    {
        foreach ($rates as $key => $value) {
            $this->getEntityManager()->createQueryBuilder()
                ->update(ExchangeRate::class, 'exchangeRate')
                ->set('exchangeRate.rate',':value')
                ->where('exchangeRate.device = :currency')
                ->setParameters([
                        'value' => $value,
                        'currency' => $key
                ])
                ->getQuery()
                ->execute()
            ;
        }

    }
}