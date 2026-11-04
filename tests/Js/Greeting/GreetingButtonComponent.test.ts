import { describe, expect, it, vi } from 'vitest';
import { GreetingButtonComponent } from '../../../node_build/js/Greeting/GreetingButtonComponent';
import type { GreetingButtonComponentInterface } from '../../../node_build/js/Greeting/GreetingButtonComponentInterface';
import type { UserInfoServiceInterface } from '../../../node_build/js/User/UserInfoServiceInterface';

describe('GreetingButtonComponent', () => {
    it('shows the button with the user first name and greeting link', async () => {
        document.body.innerHTML = getGreetingButtonHtml();
        const userInfoStub = { firstName: 'John', greetingUrl: 'index.php?cl=oeem_greeting' };
        const userInfoServiceSpy = { getUserInfo: vi.fn().mockResolvedValue(userInfoStub) };

        const sut = getSut(userInfoServiceSpy);
        await sut.show();

        expect(userInfoServiceSpy.getUserInfo).toHaveBeenCalledExactlyOnceWith('someToken');

        const button = document.getElementById('oeem-greeting-btn')!;
        expect(button.textContent).toBe('John');
        expect(button.getAttribute('href')).toBe('index.php?cl=oeem_greeting');

        const wrapper = document.getElementById('oeem-greeting-btn-wrapper')!;
        expect(wrapper.classList.contains('d-none')).toBe(false);
    });

    it('keeps the button hidden if the request fails', async () => {
        document.body.innerHTML = getGreetingButtonHtml();
        const userInfoServiceStub = { getUserInfo: vi.fn().mockRejectedValue(new Error('Network error')) };

        const sut = getSut(userInfoServiceStub);
        await sut.show();

        const wrapper = document.getElementById('oeem-greeting-btn-wrapper')!;
        expect(wrapper.classList.contains('d-none')).toBe(true);
    });

    it.each([
        {
            missing: 'the wrapper',
            html: '<a id="oeem-greeting-btn" href="">Update greeting</a>',
        },
        {
            missing: 'the button',
            html: '<span id="oeem-greeting-btn-wrapper" class="d-none" data-stoken="someToken"></span>',
        },
        {
            missing: 'the session token',
            html: '<span id="oeem-greeting-btn-wrapper" class="d-none"><a id="oeem-greeting-btn" href="">Update greeting</a></span>',
        },
    ])('does not request user info if $missing is missing', async ({ html }) => {
        document.body.innerHTML = html;
        const userInfoServiceSpy = { getUserInfo: vi.fn() };
        const sut = getSut(userInfoServiceSpy);

        await sut.show();

        expect(userInfoServiceSpy.getUserInfo).not.toHaveBeenCalled();
    });
});

function getSut(
    userInfoService: UserInfoServiceInterface,
): GreetingButtonComponentInterface {
    return new GreetingButtonComponent(
        userInfoService,
    );
}

function getGreetingButtonHtml(): string {
    return `
        <span id="oeem-greeting-btn-wrapper" class="d-none" data-stoken="someToken">
            <a id="oeem-greeting-btn" href="">Update greeting</a>
        </span>
    `;
}
