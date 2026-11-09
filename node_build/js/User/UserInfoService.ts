import type { UserInfo } from './types/UserInfo';
import type { UserInfoServiceInterface } from './UserInfoServiceInterface';

export class UserInfoService implements UserInfoServiceInterface {
    async getUserInfo(sessionToken: string): Promise<UserInfo> {
        const response = await fetch('/api/user-info?stoken=' + encodeURIComponent(sessionToken), {
            credentials: 'same-origin',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!response.ok) {
            throw new Error('User info request failed with status ' + response.status);
        }

        return response.json() as Promise<UserInfo>;
    }
}
