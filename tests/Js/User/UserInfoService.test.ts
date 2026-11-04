import {afterEach, describe, expect, it, vi} from 'vitest';
import {UserInfoService} from '../../../node_build/js/User/UserInfoService';
import type {UserInfoServiceInterface} from '../../../node_build/js/User/UserInfoServiceInterface';

describe('UserInfoService', () => {
    afterEach(() => {
        vi.unstubAllGlobals();
    });

    it('requests the user info with the session token and returns it', async () => {
        const userInfoStub = { firstName: 'John', greetingUrl: 'index.php?cl=oeem_greeting' };
        const responseStub = { ok: true, json: async () => userInfoStub };

        const fetchSpy = vi.fn().mockResolvedValue(responseStub);
        vi.stubGlobal('fetch', fetchSpy);

        const sut = getSut();
        const result = await sut.getUserInfo('some token&');

        expect(fetchSpy).toHaveBeenCalledExactlyOnceWith('/api/user-info?stoken=some%20token%26', {
            credentials: 'same-origin',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        expect(result).toEqual(userInfoStub);
    });

    it('throws if the response is not ok', async () => {
        const responseStub = { ok: false, status: 403 };
        const fetchStub = vi.fn().mockResolvedValue(responseStub);
        vi.stubGlobal('fetch', fetchStub);
        const sut = getSut();

        await expect(sut.getUserInfo('someToken')).rejects
            .toThrow('User info request failed with status 403');
    });
});

function getSut(): UserInfoServiceInterface {
    return new UserInfoService();
}
