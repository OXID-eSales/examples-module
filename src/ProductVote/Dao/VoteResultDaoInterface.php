<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\ProductVote\Dao;

use OxidEsales\ExamplesModule\ProductVote\DataObject\VoteResultInterface;

interface VoteResultDaoInterface
{
    public function getProductVoteResult(string $productId): VoteResultInterface;
}
