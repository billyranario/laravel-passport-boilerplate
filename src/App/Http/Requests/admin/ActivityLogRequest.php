<?php

namespace App\Http\Requests\admin;

use App\Dtos\ActivityLogDto;
use Billyranario\ProstarterKit\App\Http\Requests\BaseRequest;

class ActivityLogRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }

    /**
     * Transfer request to data transfer object.
     * @return ActivityLogDto
     */
    public function toDto(): ActivityLogDto
    {
        $activityLogDto = new ActivityLogDto();

        if (!empty($this->getInputAsString('sortBy'))) {
            $activityLogDto->setOrderBy($this->getInputAsString('sortBy'));
        }

        if (!empty($this->getInputAsString('sortDirection'))) {
            $activityLogDto->setOrderDirection($this->getInputAsString('sortDirection'));
        }

        if (!empty($this->getInputAsInt('perPage'))) {
            $activityLogDto->setPerPage($this->getInputAsInt('perPage'));
        }

        if ($this->getInputAsArrayFromCommaSeparatedString('relations')) {
            $activityLogDto->setRelations($this->getInputAsArrayFromCommaSeparatedString('relations'));
        }

        return $activityLogDto;
    }
}
