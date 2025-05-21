<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Integration\Greeting\Controller;

use OxidEsales\Eshop\Application\Model\User as EshopModelUser;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\ExamplesModule\Core\Module as ModuleCore;
use OxidEsales\ExamplesModule\Extension\Model\User as ModuleUser;
use OxidEsales\ExamplesModule\Greeting\Controller\GreetingController;
use OxidEsales\ExamplesModule\Greeting\Service\GreetingMessageServiceInterface;
use OxidEsales\ExamplesModule\Settings\Service\ModuleSettingsServiceInterface;
use OxidEsales\ExamplesModule\Tests\Integration\IntegrationTestCase;
use OxidEsales\ExamplesModule\Tracker\Model\TrackerModel;
use OxidEsales\ExamplesModule\Tracker\Repository\TrackerRepositoryInterface;
use PHPUnit\Framework\Attributes\CoversClass;

/*
 * We want to test controller behavior going 'full way'.
 * No mocks, we go straight to the database (full integration)).
 *
 * @todo: why no mocks? Unnecessary coupling. Whole system functionality should be checked with Acceptance test instead.
 * @todo: rework this fully to test only controller logic
 */
#[CoversClass(GreetingController::class)]
final class GreetingControllerTest extends IntegrationTestCase
{
    public const TEST_USER_ID = '_testuser';

    public const TEST_GREETING = 'oh dear';

    public const TEST_GREETING_UPDATED = 'shopping addict';

    public function setUp(): void
    {
        parent::setUp();

        $this->cleanUpTrackers();
        $this->cleanUpUsers();
    }

    public function tearDown(): void
    {
        Registry::getSession()->setUser(null);
        parent::tearDown();
    }

    /**
     * @dataProvider providerOeemGreeting
     */
    public function testUpdateGreeting(bool $hasUser, string $mode, string $expected, int $count): void
    {
        $moduleSettingsServiceStub = $this->createStub(ModuleSettingsServiceInterface::class);
        $moduleSettingsServiceStub
            ->method('isPersonalGreetingMode')
            ->willReturn($mode === ModuleSettingsServiceInterface::GREETING_MODE_PERSONAL);

        $trackerStub = $this->createMock(TrackerModel::class);
        $trackerStub->method('getCount')->willReturn($count);

        $trackerRepositoryMock = $this->createStub(TrackerRepositoryInterface::class);
        $trackerRepositoryMock
            ->method('getTrackerByUserId')
            ->with(self::TEST_USER_ID)
            ->willReturn($trackerStub);

        $greetingServiceMock = $this->createMock(GreetingMessageServiceInterface::class);
        if ($hasUser && $mode === ModuleSettingsServiceInterface::GREETING_MODE_PERSONAL) {
            $greetingServiceMock
                ->method('saveGreeting')
                ->willReturnCallback(function (EshopModelUser $user) use ($expected): bool {
                    $user->assign(['oeemgreeting' => $expected]);
                    $user->save();
                    return true;
                });
        } else {
            $greetingServiceMock->expects($this->never())->method('saveGreeting');
        }

        $sut = $this->getSut(
            moduleSettings: $moduleSettingsServiceStub,
            trackerRepository: $trackerRepositoryMock,
            greetingMessageService: $greetingServiceMock,
        );

        if ($hasUser) {
            $sut->setUser($this->createTestUser());
        }

        $sut->updateGreeting();

        /** @var ModuleUser $user */
        $user = $this->loadTestUser();
        $this->assertSame($expected, $user->getPersonalGreeting());

        $tracker = $this->get(TrackerRepositoryInterface::class)
            ->getTrackerByUserId(self::TEST_USER_ID);
        $this->assertSame($count, $tracker->getCount());
    }

    /**
     * @dataProvider providerRender
     */
    public function testRender(bool $hasUser, string $mode, array $expected): void
    {
        $this->createTestTracker($expected['counter']);

        $moduleSettingsServiceStub = $this->createStub(ModuleSettingsServiceInterface::class);
        $moduleSettingsServiceStub
            ->method('isPersonalGreetingMode')
            ->willReturn($mode === ModuleSettingsServiceInterface::GREETING_MODE_PERSONAL);

        $trackerStub = $this->createMock(TrackerModel::class);
        $trackerStub->method('getCount')->willReturn($expected['counter']);

        $trackerRepositoryMock = $this->createStub(TrackerRepositoryInterface::class);
        $trackerRepositoryMock
            ->method('getTrackerByUserId')
            ->with(self::TEST_USER_ID)
            ->willReturn($trackerStub);

        $sut = $this->getSut(
            moduleSettings: $moduleSettingsServiceStub,
            trackerRepository: $this->get(TrackerRepositoryInterface::class),
        );

        if ($hasUser) {
            $sut->setUser($this->createTestUser());
        }

        $this->assertSame('@oe_examples_module/templates/greetingtemplate', $sut->render());

        $viewData = $sut->getViewData();
        $this->assertSame($expected['greeting'], $viewData[ModuleCore::OEEM_GREETING_TEMPLATE_VARNAME]);
        $this->assertSame($expected['counter'], $viewData[ModuleCore::OEEM_COUNTER_TEMPLATE_VARNAME]);
    }

    public static function providerOeemGreeting(): array
    {
        return [
            'without_user_generic' => [
                'hasUser' => false,
                'mode' => ModuleSettingsServiceInterface::GREETING_MODE_GENERIC,
                'expected' => '',
                'count' => 0,
            ],
            'without_user_personal' => [
                'hasUser' => false,
                'mode' => ModuleSettingsServiceInterface::GREETING_MODE_PERSONAL,
                'expected' => '',
                'count' => 0,
            ],
            'with_user_generic' => [
                'hasUser' => true,
                'mode' => ModuleSettingsServiceInterface::GREETING_MODE_GENERIC,
                'expected' => self::TEST_GREETING,
                'count' => 0,
            ],
            'with_user_personal' => [
                'hasUser' => true,
                'mode' => ModuleSettingsServiceInterface::GREETING_MODE_PERSONAL,
                'expected' => self::TEST_GREETING_UPDATED,
                'count' => 1,
            ],
        ];
    }

    public static function providerRender(): array
    {
        return [
            'without_user_generic' => [
                'hasUser' => false,
                'mode' => ModuleSettingsServiceInterface::GREETING_MODE_GENERIC,
                'expected' => [
                    'greeting' => '',
                    'counter' => 0,
                ],
            ],
            'without_user_personal' => [
                'hasUser' => false,
                'mode' => ModuleSettingsServiceInterface::GREETING_MODE_PERSONAL,
                'expected' => [
                    'greeting' => '',
                    'counter' => 0,
                ],
            ],
            'with_user_generic' => [
                'hasUser' => true,
                'mode' => ModuleSettingsServiceInterface::GREETING_MODE_GENERIC,
                'expected' => [
                    'greeting' => '',
                    'counter' => 0,
                ],
            ],
            'with_user_personal' => [
                'hasUser' => true,
                'mode' => ModuleSettingsServiceInterface::GREETING_MODE_PERSONAL,
                'expected' => [
                    'greeting' => self::TEST_GREETING,
                    'counter' => 67,
                ],
            ],
        ];
    }

    private function createTestUser(): EshopModelUser
    {
        $user = oxNew(EshopModelUser::class);
        $user->assign(
            [
                'oxid' => self::TEST_USER_ID,
                'oeemgreeting' => self::TEST_GREETING,
            ]
        );
        $user->save();

        return $user;
    }

    private function createTestTracker(?int $count): void
    {
        $tracker = oxNew(TrackerModel::class);
        $tracker->assign(
            [
                'oxuserid' => self::TEST_USER_ID,
                'oxshopid' => 1,
                'oeemcount' => $count ?? rand(1, 100),
            ]
        );
        $tracker->save();
    }

    private function loadTestUser(): EshopModelUser
    {
        $user = oxNew(EshopModelUser::class);
        $user->load(self::TEST_USER_ID);
        return $user;
    }

    private function getSut(
        ?ModuleSettingsServiceInterface $moduleSettings = null,
        ?TrackerRepositoryInterface $trackerRepository = null,
        ?GreetingMessageServiceInterface $greetingMessageService = null,
    ): GreetingController {
        $moduleSettings ??= $this->createStub(ModuleSettingsServiceInterface::class);
        $trackerRepository ??= $this->createStub(TrackerRepositoryInterface::class);
        $greetingMessageService ??= $this->createStub(GreetingMessageServiceInterface::class);
        return new GreetingController(
            moduleSettings: $moduleSettings,
            trackerRepository: $trackerRepository,
            greetingService: $greetingMessageService,
        );
    }
}
