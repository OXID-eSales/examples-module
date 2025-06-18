<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

/**
 * Metadata version
 */
$sMetadataVersion = '2.1';

/**
 * Module information
 */
$aModule = [
    'id'          => 'oe_examples_module',
    'title'       => 'OXID eSales Examples Module',
    'description' => 'Module with examples for the most common use cases',
    'thumbnail'   => 'pictures/logo.png',
    'version'     => '1.0.0',
    'author'      => '',
    'url'         => '',
    'email'       => '',
    'extend'      => [
        \OxidEsales\Eshop\Application\Controller\StartController::class => \OxidEsales\ExamplesModule\Extension\Controller\StartController::class,
        \OxidEsales\Eshop\Application\Controller\ArticleDetailsController::class => \OxidEsales\ExamplesModule\ProductVote\Controller\ArticleDetailsController::class,
        \OxidEsales\Eshop\Application\Component\Widget\ArticleDetails::class => \OxidEsales\ExamplesModule\ProductVote\Widget\ArticleDetails::class,
        \OxidEsales\Eshop\Application\Model\Basket::class => \OxidEsales\ExamplesModule\Extension\Model\Basket::class,
        \OxidEsales\Eshop\Application\Model\User::class => \OxidEsales\ExamplesModule\Extension\Model\User::class,
    ],
    'events' => [
        'onActivate' => '\OxidEsales\ExamplesModule\Core\ModuleEvents::onActivate',
        'onDeactivate' => '\OxidEsales\ExamplesModule\Core\ModuleEvents::onDeactivate'
    ],
    'settings' => [
        //TODO: add help texts for settings to explain possibilities and point out which ones only serve as example
        /** Main */
        [
            'group'       => 'oeexamplesmodule_main',
            'name'        => 'oeexamplesmodule_GreetingMode',
            'type'        => 'select',
            'constraints' => 'generic|personal',
            'value'       => 'generic'
        ],
        [
            'group' => 'oeexamplesmodule_main',
            'name'  => 'oeexamplesmodule_BrandName',
            'type'  => 'str',
            'value' => 'Testshop'
        ],
        [
            'group' => 'oeexamplesmodule_main',
            'name'  => 'oeexamplesmodule_LoggerEnabled',
            'type'  => 'bool',
            'value' => false
        ],
        [
            'group' => 'oeexamplesmodule_main',
            'name'  => 'oeexamplesmodule_Timeout',
            'type'  => 'num',
            'value' => 30
            //'value' => 30.5
        ],
        [
            'group' => 'oeexamplesmodule_main',
            'name'  => 'oeexamplesmodule_Categories',
            'type'  => 'arr',
            'value' => ['Sales', 'Manufacturers']
        ],
        [
            'group' => 'oeexamplesmodule_main',
            'name'  => 'oeexamplesmodule_Channels',
            'type'  => 'aarr',
            'value' => ['1' => 'de', '2' => 'en']
        ],
        [
            'group'    => 'oeexamplesmodule_main',
            'name'     => 'oeexamplesmodule_Password',
            'type'     => 'password',
            'value'    => 'changeMe',
            'position' => 3
        ]
    ],
];
