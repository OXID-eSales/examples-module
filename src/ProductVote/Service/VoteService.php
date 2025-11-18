<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\ProductVote\Service;

use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\Eshop\Application\Model\User;
use OxidEsales\ExamplesModule\ProductVote\Dao\ProductVoteDaoInterface;
use OxidEsales\ExamplesModule\ProductVote\Dao\VoteResultDaoInterface;
use OxidEsales\ExamplesModule\ProductVote\DataObject\ProductVote;
use OxidEsales\ExamplesModule\ProductVote\DataObject\ProductVoteInterface;
use OxidEsales\ExamplesModule\ProductVote\DataObject\VoteResultInterface;
use OxidEsales\ExamplesModule\ProductVote\Event\ProductVotedEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

readonly class VoteService implements VoteServiceInterface
{
    public function __construct(
        private ProductVoteDaoInterface $productVoteDao,
        private VoteResultDaoInterface $voteResultDao,
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function getProductVote(Article $product, User $user): ?ProductVoteInterface
    {
        return $this->productVoteDao->getProductVote($product->getId(), $user->getId());
    }

    public function setProductVote(Article $product, User $user, bool $vote): void
    {
        $productVote = new ProductVote($product->getId(), $user->getId(), $vote);
        $this->productVoteDao->setProductVote($productVote);

        // Dispatch custom event to allow other modules/subscribers to react to voting
        $event = new ProductVotedEvent($productVote);
        $this->eventDispatcher->dispatch($event);
    }

    public function resetProductVote(Article $product, User $user): void
    {
        $this->productVoteDao->resetProductVote($product->getId(), $user->getId());
    }

    public function getProductVoteResult(Article $product): VoteResultInterface
    {
        return $this->voteResultDao->getProductVoteResult($product->getId());
    }
}
