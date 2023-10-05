<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\ActivityLogRequest;
use App\Http\Resources\ActivityLogResource;
use App\Services\ActivityLogService;
use Billyranario\ProstarterKit\App\Core\ResponseHelper;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * @var ActivityLogService $activityLogService
     */
    private ActivityLogService $activityLogService;

    /**
     * @param ActivityLogService $activityLogService
     */
    public function __construct(
        ActivityLogService $activityLogService
    ) {
        $this->activityLogService = $activityLogService;
    }
    
    /**
     * Get paginated activity logs.
     * @param ActivityLogRequest $request
     */
    public function index(ActivityLogRequest $request): mixed 
    {
        $activityLogDto = $request->toDto();
        $serviceResponse = $this->activityLogService->getPaginatedActivityLogs($activityLogDto);

        if ($serviceResponse->isError()) {
            return ResponseHelper::error($serviceResponse->getMessage());
        }

        return ResponseHelper::resource(ActivityLogResource::class, $serviceResponse->getData());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
