<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\ProductVote\Widget;

use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\Eshop\Application\Model\User;
use OxidEsales\ExamplesModule\ProductVote\Service\VoteServiceInterface;

/**
 * @extendable-class
 *
 * This is a brand new (module own) controller which extends from the
 * shop frontend controller class.
 */
class ArticleDetails extends ArticleDetails_parent
{
    public function render()
    {
        $this->oeemPrepareVoteData();
        return parent::render();
    }

    public function oeemPrepareVoteData(): void
    {
        $product = $this->getProduct();

        /** @var User|null $user */
        $user = $this->getUser();

        $voteService = $this->getService(VoteServiceInterface::class);

        if ($user instanceof User) {
            $this->_aViewData['productVote'] = $voteService->getProductVote($product, $user);
        }

        $this->_aViewData['productVoteResult'] = $voteService->getProductVoteResult($product);
    }
}
