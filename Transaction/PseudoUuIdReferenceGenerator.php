<?php

namespace Barbondev\Payment\PayPointHostedBundle\Transaction;

use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class PseudoUuIdReferenceGenerator
 *
 * @package Barbondev\Payment\PayPointHostedBundle\Transaction
 * @author Ashley Dawson <ashley.dawson@barbon.com>
 */
class PseudoUuIdReferenceGenerator implements ReferenceGeneratorInterface
{
    /**
     * @var ObjectManager
     */
    private $em;

    /**
     * Constructor
     *
     * @param ObjectManager $em
     */
    public function __construct(ObjectManager $em)
    {
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    public function generate()
    {
        do {
            $uuid = $this->generatePseudoUuId();
            $transaction = $this->em->getRepository('JMS\Payment\CoreBundle\Entity\FinancialTransaction')->findOneBy(array(
                'referenceNumber' => $uuid,
            ));
        }
        while ($transaction);

        return $uuid;
    }

    /**
     * Generate a pseudo UUID
     *
     * @return string
     */
    private function generatePseudoUuId()
    {
        $data = openssl_random_pseudo_bytes(16);

        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}