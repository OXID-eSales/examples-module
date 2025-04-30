<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\ProductVote\DataObject;

readonly class VoteResult implements VoteResultInterface
{
    public function __construct(
        private string $productId,
        private int $voteUp,
        private int $voteDown,
    ) {
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getVoteUp(): int
    {
        return $this->voteUp;
    }

    public function getVoteDown(): int
    {
        return $this->voteDown;
    }
}
