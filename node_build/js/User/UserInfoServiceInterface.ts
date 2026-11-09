import type { UserInfo } from './types/UserInfo';

export interface UserInfoServiceInterface {
    getUserInfo(sessionToken: string): Promise<UserInfo>;
}
