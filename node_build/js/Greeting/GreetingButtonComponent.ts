import type { UserInfo } from '../User/types/UserInfo';
import type { UserInfoServiceInterface } from '../User/UserInfoServiceInterface';
import type { GreetingButtonComponentInterface } from './GreetingButtonComponentInterface';

export class GreetingButtonComponent implements GreetingButtonComponentInterface {
    constructor(
        private readonly userInfoService: UserInfoServiceInterface,
    ) {
    }

    async show(): Promise<void> {
        const wrapper = document.getElementById('oeem-greeting-btn-wrapper');
        const button = document.querySelector<HTMLAnchorElement>('#oeem-greeting-btn');
        if (!wrapper || !button || !wrapper.dataset.stoken) {
            return;
        }

        let userInfo: UserInfo;
        try {
            userInfo = await this.userInfoService.getUserInfo(wrapper.dataset.stoken);
        } catch {
            return;
        }

        button.textContent = userInfo.firstName;
        button.href = userInfo.greetingUrl;
        wrapper.classList.remove('d-none');
    }
}
