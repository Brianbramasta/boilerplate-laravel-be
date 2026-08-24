<?php

namespace App\Http\Controllers\Master\Dropdown;

use App\Http\Controllers\BaseController;
use App\Http\Docs\AuthDocs;
use App\Http\Docs\MasterDropdownDocs;
use App\Http\Resources\Master\Dropdown\AssessmentCategoryOptionResource;
use App\Http\Resources\Master\Dropdown\AssessmentOptionResource;
use App\Models\Master\Assessment;
use App\Models\Master\AssessmentCategory;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Response;

/**
 * Endpoint dropdown/lookup Master domain yang dipakai lintas fitur (bukan
 * spesifik satu form saja) — sama seperti RefController tapi untuk entity
 * Master (mt_assessments, mt_assessment_categories), bukan tabel referensi.
 */
#[Group('Master Dropdowns', 'Endpoint baca data dropdown Master (proses, jenis penilaian) — dipakai lintas fitur, mis. pembuatan master penilaian.')]
class MasterDropdownController extends BaseController
{
    #[Response(MasterDropdownDocs::ASSESSMENTS_SUCCESS, status: 200, description: 'Berhasil mengambil data proses (dropdown Proses)')]
    #[Response(AuthDocs::UNAUTHORIZED, status: 401, description: 'Token tidak dikirim, tidak valid, atau sudah expired')]
    public function assessments()
    {
        try {
            $data = Assessment::where('is_active', true)->orderBy('name')->get();

            return $this->successResponse(
                AssessmentOptionResource::collection($data),
                'Berhasil mengambil data proses'
            );
        } catch (\Throwable $th) {
            return api()->handle($th);
        }
    }

    #[Response(MasterDropdownDocs::CATEGORIES_SUCCESS, status: 200, description: 'Berhasil mengambil data jenis penilaian (dropdown Jenis)')]
    #[Response(AuthDocs::UNAUTHORIZED, status: 401, description: 'Token tidak dikirim, tidak valid, atau sudah expired')]
    public function categories()
    {
        try {
            $data = AssessmentCategory::where('is_active', true)->orderBy('name')->get();

            return $this->successResponse(
                AssessmentCategoryOptionResource::collection($data),
                'Berhasil mengambil data jenis penilaian'
            );
        } catch (\Throwable $th) {
            return api()->handle($th);
        }
    }
}
