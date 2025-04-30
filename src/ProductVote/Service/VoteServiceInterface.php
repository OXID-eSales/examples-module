<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\ProductVote\Service;

use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\Eshop\Application\Model\User;
use OxidEsales\ExamplesModule\ProductVote\DataObject\ProductVoteInterface;
use OxidEsales\ExamplesModule\ProductVote\DataObject\VoteResultInterface;

interface VoteServiceInterface
{
    public function getProductVote(Article $product, User $user): ?ProductVoteInterface;
    public function setProductVote(Article $product, User $user, bool $vote): void;
    public function resetProductVote(Article $product, User $user): void;

    public function getProductVoteResult(Article $product): VoteResultInterface;
}
