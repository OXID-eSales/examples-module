<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\ProductVote\Event;

use OxidEsales\ExamplesModule\ProductVote\DataObject\ProductVoteInterface;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Event dispatched after a product vote has been set.
 * This is an example of a custom module event that allows other modules
 * or extensions to react to product voting actions.
 *
 * Use cases for subscribers:
 * - Send notifications
 * - Update statistics
 * - Trigger external integrations
 * - Log voting activity
 */
final class ProductVotedEvent extends Event implements ProductVotedEventInterface
{
    public function __construct(
        private readonly ProductVoteInterface $productVote
    ) {
    }

    public function getProductVote(): ProductVoteInterface
    {
        return $this->productVote;
    }
}
