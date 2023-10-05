<?php

namespace App\Services;

use App\Constants\ServiceResponseMessages;
use App\Dtos\ActivityLogDto;
use App\Repositories\Eloquent\ActivityLogRepository;
use Billyranario\ProstarterKit\App\Core\ServiceResponse;
use Billyranario\ProstarterKit\App\Helpers\LoggerHelper;

class ActivityLogService
{
    /**
     * @var ActivityLogRepository $activityLogRepository
     */
    private ActivityLogRepository $activityLogRepository;

    /**
     * @param ActivityLogRepository $activityLogRepository
     */
    public function __construct(
        ActivityLogRepository $activityLogRepository
    ) {
        $this->activityLogRepository = $activityLogRepository;
    }

    /**
     * Get paginated activity logs.
     * @param ActivityLogDto $activityLogDto
     * @return ServiceResponse
     */
    public function getPaginatedActivityLogs(ActivityLogDto $activityLogDto): ServiceResponse
    {
        if ($activityLogs = $this->activityLogRepository->paginate($activityLogDto)) {
            return ServiceResponse::success(ServiceResponseMessages::SUCCESS, $activityLogs);
        }
        return ServiceResponse::error(ServiceResponseMessages::ERROR);
    }

    /**
     * Create activity log.
     * @param ActivityLogDto $activityLogDto
     * @return ServiceResponse
     */
    public function create(ActivityLogDto $activityLogDto): ServiceResponse
    {
        $data = [];
        if ($activityLog = $this->activityLogRepository->create($data)) {
            return ServiceResponse::success(ServiceResponseMessages::SUCCESS, $activityLog);
        }
        return ServiceResponse::error(ServiceResponseMessages::ERROR);
    }
}