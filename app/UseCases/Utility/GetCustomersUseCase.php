<?php

namespace App\UseCases\Utility;

use App\Repositories\Utility\UtilityRespository;
use Illuminate\Database\Eloquent\Collection;

class GetCustomersUseCase
{
    private $repository;
    public function __construct(UtilityRespository $utilityRespository)
    {
        $this->repository = $utilityRespository;
    }

    public function __invoke(): Collection
    {
        return $this->repository->getCustomers();
    }
}
