<?php

namespace App\Models\AssessmentPSB;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssessmentPicInformation extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'dt_assessment_pic_informations';

    protected $fillable = [
        'assessment_information_id',
        'role',
        'nip',
        'nick_name',
        'full_name',
        'is_primary',
    ];

    public function information()
    {
        return $this->belongsTo(AssessmentInformation::class, 'assessment_information_id');
    }
}
