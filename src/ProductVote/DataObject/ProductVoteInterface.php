<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\ProductVote\DataObject;

interface ProductVoteInterface
{
    public function getProductId(): string;
    public function getUserId(): string;
    public function isVoteUp(): bool;
}
