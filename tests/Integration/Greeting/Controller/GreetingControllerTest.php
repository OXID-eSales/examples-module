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
use OxidEsales\ExamplesModule\Greeting\Controller\GreetingController;
use OxidEsales\ExamplesModule\Greeting\Infrastructure\Repository\UserRepositoryInterface;
use OxidEsales\ExamplesModule\Greeting\Service\GreetingMessageServiceInterface;
use OxidEsales\ExamplesModule\Greeting\Settings\GreetingSettingsInterface;
use OxidEsales\ExamplesModule\Greeting\Transput\SaveGreetingRequestInterface;
use OxidEsales\ExamplesModule\Tests\Integration\IntegrationTestCase;
use OxidEsales\ExamplesModule\Tracker\Infrastructure\Repository\TrackerRepositoryInterface;
use OxidEsales\ExamplesModule\Tracker\Model\TrackerModel;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;

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

    #[Test]
    public function testUpdateGreetingWithPersonalModeOn(): void
    {
        $greetingSettingsStub = $this->createConfiguredStub(GreetingSettingsInterface::class, [
            'isPersonalGreetingMode' => true,
        ]);

        $saveGreetingRequestStub = $this->createConfiguredStub(SaveGreetingRequestInterface::class, [
            'getGreetingMessage' => $exampleMessage = uniqid('message_'),
        ]);

        $greetingServiceSpy = $this->createMock(GreetingMessageServiceInterface::class);
        $greetingServiceSpy->expects($this->once())
            ->method('saveGreetingForCurrentUser')
            ->with($exampleMessage);

        $sut = $this->getSut(
            greetingSettings: $greetingSettingsStub,
            greetingMessageService: $greetingServiceSpy,
            saveGreetingRequest: $saveGreetingRequestStub,
        );

        $sut->updateGreeting();
    }

    #[Test]
    public function testUpdateGreetingWithPersonalModeOff(): void
    {
        $greetingSettingsStub = $this->createConfiguredStub(GreetingSettingsInterface::class, [
            'isPersonalGreetingMode' => false,
        ]);

        $greetingServiceSpy = $this->createMock(GreetingMessageServiceInterface::class);
        $greetingServiceSpy->expects($this->never())->method('saveGreetingForCurrentUser');

        $sut = $this->getSut(
            greetingSettings: $greetingSettingsStub,
            greetingMessageService: $greetingServiceSpy,
        );

        $sut->updateGreeting();
    }

    /**
     * @dataProvider providerRender
     */
    public function testRender(bool $hasUser, string $mode, array $expected): void
    {
        $this->createTestTracker($expected['counter']);

        $greetingSettingsStub = $this->createStub(GreetingSettingsInterface::class);
        $greetingSettingsStub
            ->method('isPersonalGreetingMode')
            ->willReturn($mode === GreetingSettingsInterface::GREETING_MODE_PERSONAL);

        $trackerStub = $this->createMock(TrackerModel::class);
        $trackerStub->method('getCount')->willReturn($expected['counter']);

        $trackerRepositoryMock = $this->createStub(TrackerRepositoryInterface::class);
        $trackerRepositoryMock
            ->method('getTrackerByUserId')
            ->with(self::TEST_USER_ID)
            ->willReturn($trackerStub);

        $userRepositoryStub = $this->createStub(UserRepositoryInterface::class);
        if ($hasUser) {
            $userRepositoryStub->method('getActiveUser')->willReturn($this->createTestUser());
        }

        $sut = $this->getSut(
            greetingSettings: $greetingSettingsStub,
            trackerRepository: $this->get(TrackerRepositoryInterface::class),
            userRepository: $userRepositoryStub,
        );

        $this->assertSame('@oe_examples_module/templates/greetingtemplate', $sut->render());

        $viewData = $sut->getViewData();
        $this->assertSame($expected['greeting'], $viewData[ModuleCore::OEEM_GREETING_TEMPLATE_VARNAME]);
        $this->assertSame($expected['counter'], $viewData[ModuleCore::OEEM_COUNTER_TEMPLATE_VARNAME]);
    }

    public static function providerRender(): array
    {
        return [
            'without_user_generic' => [
                'hasUser' => false,
                'mode' => GreetingSettingsInterface::GREETING_MODE_GENERIC,
                'expected' => [
                    'greeting' => '',
                    'counter' => 0,
                ],
            ],
            'without_user_personal' => [
                'hasUser' => false,
                'mode' => GreetingSettingsInterface::GREETING_MODE_PERSONAL,
                'expected' => [
                    'greeting' => '',
                    'counter' => 0,
                ],
            ],
            'with_user_generic' => [
                'hasUser' => true,
                'mode' => GreetingSettingsInterface::GREETING_MODE_GENERIC,
                'expected' => [
                    'greeting' => '',
                    'counter' => 0,
                ],
            ],
            'with_user_personal' => [
                'hasUser' => true,
                'mode' => GreetingSettingsInterface::GREETING_MODE_PERSONAL,
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

    private function getSut(
        ?GreetingSettingsInterface $greetingSettings = null,
        ?TrackerRepositoryInterface $trackerRepository = null,
        ?GreetingMessageServiceInterface $greetingMessageService = null,
        ?SaveGreetingRequestInterface $saveGreetingRequest = null,
        ?UserRepositoryInterface $userRepository = null,
    ): GreetingController {
        $greetingSettings ??= $this->createStub(GreetingSettingsInterface::class);
        $trackerRepository ??= $this->createStub(TrackerRepositoryInterface::class);
        $greetingMessageService ??= $this->createStub(GreetingMessageServiceInterface::class);
        $saveGreetingRequest ??= $this->createStub(SaveGreetingRequestInterface::class);
        $userRepository ??= $this->createStub(UserRepositoryInterface::class);

        return new GreetingController(
            greetingSettings: $greetingSettings,
            trackerRepository: $trackerRepository,
            greetingService: $greetingMessageService,
            saveGreetingRequest: $saveGreetingRequest,
            userRepository: $userRepository,
        );
    }
}
