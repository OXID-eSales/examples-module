<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\ProductVote\Event;

use OxidEsales\ExamplesModule\ProductVote\DataObject\ProductVoteInterface;

/**
 * Interface for the ProductVoted event.
 * Defines the contract for accessing vote data from the event.
 */
interface ProductVotedEventInterface
{
    public function getProductVote(): ProductVoteInterface;
}
