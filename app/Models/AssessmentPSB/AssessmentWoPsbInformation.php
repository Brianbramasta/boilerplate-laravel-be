<?php

namespace App\Models\AssessmentPSB;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class AssessmentWoPsbInformation extends Model
{
    use HasUuids;

    protected $table = 'dt_assessment_wo_psb_informations';

    protected $fillable = [
        'assessment_information_id',
        'team_id',
        'team_name',
        'sales_distance',
        'technician_distance',
        'dropcore',
        'cable_status',
        'cable_history_id',
        'cable_detail_history_id',
        'psb_extra',
    ];

    protected function casts(): array
    {
        return [
            'psb_extra' => 'array',
        ];
    }

    public function information()
    {
        return $this->belongsTo(AssessmentInformation::class, 'assessment_information_id');
    }
}
