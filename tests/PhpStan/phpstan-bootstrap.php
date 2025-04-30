<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

class_alias(
    \OxidEsales\Eshop\Application\Model\User::class,
    \OxidEsales\ExamplesModule\Extension\Model\User_parent::class
);

class_alias(
    \OxidEsales\Eshop\Application\Controller\StartController::class,
    \OxidEsales\ExamplesModule\Extension\Controller\StartController_parent::class
);

class_alias(
    \OxidEsales\Eshop\Application\Model\Basket::class,
    \OxidEsales\ExamplesModule\Extension\Model\Basket_parent::class
);

class_alias(
    \OxidEsales\Eshop\Application\Controller\ArticleDetailsController::class,
    \OxidEsales\ExamplesModule\ProductVote\Controller\ArticleDetailsController_parent::class
);

class_alias(
    \OxidEsales\Eshop\Application\Component\Widget\ArticleDetails::class,
    \OxidEsales\ExamplesModule\ProductVote\Widget\ArticleDetails_parent::class
);
