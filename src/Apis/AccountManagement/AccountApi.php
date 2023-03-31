<?php

namespace Victorycodedev\MetaapiCloudPhpSdk\Apis\AccountManagement;

use Victorycodedev\MetaapiCloudPhpSdk\Resources\AccountManagement\Account;

trait AccountApi
{

    public function readAccountById(string $accountId)
    {
        $response = $this->instantiateAccount();
        return $response->readAccountById($accountId);
    }


    private function instantiateAccount()
    {
        return new Account();
    }
}
