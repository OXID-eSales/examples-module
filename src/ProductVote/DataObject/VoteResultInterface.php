<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\ProductVote\DataObject;

interface VoteResultInterface
{
    public function getProductId(): string;
    public function getVoteUp(): int;
    public function getVoteDown(): int;
}
