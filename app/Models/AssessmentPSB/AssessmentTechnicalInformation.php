<?php

namespace App\Models\AssessmentPSB;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssessmentTechnicalInformation extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'dt_assessment_technical_informations';

    protected $fillable = [
        'assessment_information_id',
        'area_id',
        'area_name',
        'area_code',
        'area_primary_id',
        'area_primary_name',
        'area_primary_code',
        'splitter_id',
        'splitter',
        'customer_port',
        'tech_extra',
    ];

    protected function casts(): array
    {
        return [
            'tech_extra' => 'array',
        ];
    }

    public function information()
    {
        return $this->belongsTo(AssessmentInformation::class, 'assessment_information_id');
    }
}
