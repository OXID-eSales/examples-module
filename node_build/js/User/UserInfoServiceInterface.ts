import type { UserInfoInterface } from './UserInfoInterface';

export interface UserInfoServiceInterface {
    getUserInfo(sessionToken: string): Promise<UserInfoInterface>;
}
