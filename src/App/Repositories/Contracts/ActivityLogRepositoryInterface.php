<?php

namespace App\Repositories\Contracts;

use App\Models\ActivityLog;

interface ActivityLogRepositoryInterface
{

    /**
     * Create a activity log
     *
     * @param array $data
     * @return ActivityLog|bool
     */
    public function create(array $data): ActivityLog|bool;

}
