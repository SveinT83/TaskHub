<?php

namespace Modules\FacebookPostingModule\Repositories;

use Modules\FacebookPostingModule\Services\FacebookApiService;

class FacebookRepository
{
    protected $facebookApiService;

    public function __construct(FacebookApiService $facebookApiService)
    {
        $this->facebookApiService = $facebookApiService;
    }

    public function getUserGroups()
    {
        return $this->facebookApiService->getUserGroups();
    }
}
