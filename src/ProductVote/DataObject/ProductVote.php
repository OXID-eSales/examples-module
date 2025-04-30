<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\ProductVote\DataObject;

readonly class ProductVote implements ProductVoteInterface
{
    public function __construct(
        public string $productId,
        public string $userId,
        public bool $vote,
    ) {
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function isVoteUp(): bool
    {
        return $this->vote;
    }
}
