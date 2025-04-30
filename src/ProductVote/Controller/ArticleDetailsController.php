<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\ProductVote\Controller;

use OxidEsales\Eshop\Application\Model\User;
use OxidEsales\ExamplesModule\ProductVote\Service\VoteServiceInterface;

/**
 * @extendable-class
 *
 * This is a brand new (module own) controller which extends from the
 * shop frontend controller class.
 */
class ArticleDetailsController extends ArticleDetailsController_parent
{
    public function voteUp(): void
    {
        $this->vote(true);
    }

    public function voteDown(): void
    {
        $this->vote(false);
    }

    public function resetVote(): void
    {
        /** @var User|null $user */
        $user = $this->getUser();
        if (!($user instanceof User)) {
            return;
        }

        $voteService = $this->getService(VoteServiceInterface::class);
        $voteService->resetProductVote($this->getProduct(), $user);
    }

    private function vote(bool $isUp): void
    {
        /** @var User|null $user */
        $user = $this->getUser();
        if (!($user instanceof User)) {
            return;
        }

        $voteService = $this->getService(VoteServiceInterface::class);
        $voteService->setProductVote($this->getProduct(), $user, $isUp);
    }
}
