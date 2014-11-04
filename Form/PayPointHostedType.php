<?php

namespace Barbondev\Payment\PayPointHostedBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class PayPointHostedType
 *
 * @package Barbondev\Payment\PayPointHostedBundle\Form
 * @author Ashley Dawson <ashley.dawson@barbon.com>
 */
class PayPointHostedType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'paypoint_hosted';
    }
}