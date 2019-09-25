<?php

declare(strict_types=1);

namespace Setono\SyliusGiftCardPlugin\Doctrine\ORM;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Setono\SyliusGiftCardPlugin\Model\GiftCardInterface;
use Setono\SyliusGiftCardPlugin\Repository\GiftCardRepositoryInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Core\Model\OrderItemUnitInterface;
use Sylius\Component\Order\Model\OrderInterface;

final class GiftCardRepository extends EntityRepository implements GiftCardRepositoryInterface
{
    public function createListQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('o');
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findOneEnabledByCodeAndChannel(string $code, ChannelInterface $channel): ?GiftCardInterface
    {
        return $this->createQueryBuilder('o')
            ->where('o.code = :code')
            ->andWhere('o.channel = :channel')
            ->andWhere('o.active = true')
            ->setParameter('code', $code)
            ->setParameter('channel', $channel)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findOneByCode(string $code): ?GiftCardInterface
    {
        return $this->createQueryBuilder('o')
            ->where('o.code = :code')
            ->setParameter('code', $code)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findActiveByCurrentOrder(OrderInterface $order): array
    {
        return $this->createQueryBuilder('o')
            ->where('o.currentOrder = :order')
            ->andWhere('o.active = true')
            ->setParameter('order', $order)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findOneByOrderItemUnit(OrderItemUnitInterface $orderItemUnit): ?GiftCardInterface
    {
        return $this->createQueryBuilder('o')
            ->where('o.orderItemUnit = :orderItemUnit')
            ->setParameter('orderItemUnit', $orderItemUnit)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
