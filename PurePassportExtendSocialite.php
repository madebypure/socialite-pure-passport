<?php

namespace MadeByPure\PurePassport;

use SocialiteProviders\Manager\SocialiteWasCalled;

class PurePassportExtendSocialite
{
    public function handle(SocialiteWasCalled $socialiteWasCalled): void {

        $socialiteWasCalled->extendSocialite('purepassport', Provider::class);

    }
}
