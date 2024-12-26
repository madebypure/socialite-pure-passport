# Socialite for Pure Passport

```bash
composer require madebypure/socialite-pure-passport
```
> ⚠️ Note: this is a fork of Socialite Providers's "Laravel Passport" provider. This is an early version and may not be
> fully functional yet.

## Installation & Basic Usage

Please see the [Base Installation Guide](https://socialiteproviders.com/usage/), then follow the provider specific instructions below.

### Add configuration to `config/services.php`

```php
'purepassport' => [    
  'client_id'       =>  env('PUREPASSPORT_CLIENT_ID'),  
  'client_secret'   =>  env('PUREPASSPORT_CLIENT_SECRET'),  
  'redirect'        =>  env('PUREPASSPORT_REDIRECT_URI')
],
```

### Add provider event listener

#### Laravel 11+

In Laravel 11, the default `EventServiceProvider` provider was removed. Instead, add the listener using the `listen` method on the `Event` facade, in your `AppServiceProvider` `boot` method.

* Note: You do not need to add anything for the built-in socialite providers unless you override them with your own providers.

```php
Event::listen(function (\SocialiteProviders\Manager\SocialiteWasCalled $event) {
    $event->extendSocialite('purepassport', \MadeByPure\PurePassport\Provider::class);
});
```
<details>
<summary>
Laravel 10 or below
</summary>
Configure the package's listener to listen for `SocialiteWasCalled` events.

Add the event to your `listen[]` array in `app/Providers/EventServiceProvider`. See the [Base Installation Guide](https://socialiteproviders.com/usage/) for detailed instructions.

```php
protected $listen = [
    \SocialiteProviders\Manager\SocialiteWasCalled::class => [
        // ... other providers
        \MadeByPure\PurePassport\PurePassportExtendSocialite::class.'@handle',
    ],
];
```
</details>

### Usage

You should now be able to use the provider like you would regularly use Socialite (assuming you have the facade installed):

```php
return Socialite::driver('purepassport')->redirect();
```

### Returned User fields

- ``id``
- ``name``
- ``email``
- ``avatar``
- ``role``
