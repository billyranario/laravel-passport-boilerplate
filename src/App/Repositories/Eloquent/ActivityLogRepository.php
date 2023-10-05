<?php

namespace App\Repositories\Eloquent;

use App\Dtos\ActivityLogDto;
use App\Models\ActivityLog;
use App\Repositories\Contracts\ActivityLogRepositoryInterface;
use Billyranario\ProstarterKit\App\Helpers\LoggerHelper;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ActivityLogRepository implements ActivityLogRepositoryInterface
{
    /**
     * @var ActivityLog $activityLog
     */
    protected ActivityLog $activityLog;

    /**
     * @param ActivityLog $activityLog
     */
    public function __construct(
        ActivityLog $activityLog
    ) {
        $this->activityLog = $activityLog;
    }

    /**
     * Get paginate activity logs.
     * @param ActivityLogDto $activityLogDto
     * @return LengthAwarePaginator
     */
    public function paginate(ActivityLogDto $activityLogDto): LengthAwarePaginator
    {
        return $this->activityLog
            ->with($activityLogDto->getRelations())
            ->orderBy($activityLogDto->getOrderBy(), $activityLogDto->getOrderDirection())
            ->paginate($activityLogDto->getPerPage());
    }


    /**
     * Create activity log.
     * @param array $data
     * @return ActivityLog|bool
     */
    public function create(array $data): ActivityLog|bool
    {
        try {
            DB::beginTransaction();

            $activityLog = $this->activityLog->create($data);

            DB::commit();
            return $activityLog;
        } catch (\Throwable $th) {
            DB::rollBack();
            LoggerHelper::logThrowError($th);
            return false;
        }
    }
}
