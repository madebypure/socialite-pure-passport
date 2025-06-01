<?php

namespace MadeByPure\PurePassport;

use GuzzleHttp\RequestOptions;
use Illuminate\Support\Arr;
use SocialiteProviders\Manager\OAuth2\AbstractProvider;
use SocialiteProviders\Manager\OAuth2\User;

class Provider extends AbstractProvider
{
    public const IDENTIFIER = 'PUREPASSPORT';

    protected $scopeSeparator = ' ';

    public static function additionalConfigKeys(): array {

        return [

            'host',
            'authorize_uri',
            'token_uri',
            'userinfo_uri',
            'userinfo_key',
            'user_id',
            'user_name',
            'user_email',
            'user_avatar',
            'user_role',
            'guzzle',

        ];

    }

    protected function getAuthUrl($state): string {

        return $this->buildAuthUrlFromBase($this->getPurePassportUrl('authorize_uri'), $state);

    }

    protected function getTokenUrl(): string {

        return $this->getPurePassportUrl('token_uri');

    }

    /**
     * Get the raw user for the given access token.
     *
     * @param  string  $token
     * @return array
     */
    protected function getUserByToken($token): array {

        $response = $this->getHttpClient()->get($this->getPurePassportUrl('userinfo_uri'), [
            RequestOptions::HEADERS => [
                'Authorization' => 'Bearer '.$token,
            ],
        ]);

        return json_decode((string) $response->getBody(), true);

    }

    /**
     * Map the raw user array to a Socialite User instance.
     *
     * @param  array  $user
     * @return \Laravel\Socialite\User
     */
    protected function mapUserToObject(array $user): \Laravel\Socialite\User {

        $key = $this->getConfig('userinfo_key');
        $data = ($key === null) === true ? $user : Arr::get($user, $key, []);

        return (new User)->setRaw($data)->map([
            'id'        =>  $this->getUserData($data, 'id'),
            'name'      =>  $this->getUserData($data, 'name'),
            'email'     =>  $this->getUserData($data, 'email'),
            'avatar'    =>  $this->getUserData($data, 'avatar'),
            'role'      =>  $this->getUserData($data, 'role'),
        ]);

    }

    protected function getPurePassportUrl($type): string {

        return 'https://passport.madebypure.net/' . ltrim($this->getConfig($type, Arr::get([
            'authorize_uri' =>  'oauth/authorize',
            'token_uri'     =>  'oauth/token',
            'userinfo_uri'  =>  'api/v1/user',
        ], $type)), '/');

    }

    protected function getUserData($user, $key): string {

        return Arr::get($user, $this->getConfig('user_'.$key, $key));

    }
}
