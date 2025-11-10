<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourtCase extends Model
{
    use HasFactory;

    protected $table = 'court_cases';

    protected $fillable = [
        'atty_initials',
        'stage_of_process',
        'client_name',
        'categoryId',
        'dateFrom',
        'dateTo',
        'referralChiro',
        'chiro',
        'doi',
        'preExistingInjuries',
        'facts',
        'injuries',
        'policeReport',
        'photos',
        'propertyDesctiption',
        '3pLiability',
        '3pCoverage',
        '3pLiabilityLimit',
        '1pCoverageLimits',
        'isDeleted',
        // 🆕 Added fields from your case info section
        'caseInfoStyle',
        'causeNumber',
        'courtCounty',
        'recordsServed',
        'filed',
        'served',
        'answer',
        'expertDesignationDeadlinesP',
        'discoveryResponsesP',
        'discoveryResponsesD',
        'discoveryPeriodEnds',
        'expertDesignationDeadlinesD',
        'deposP',
        'deposD',
        'docketCall',
        'billingServedDate',
        'noticeFiledDate',
        'trial',

        'firstpPermissionToRelease',
        'thirdpRelease',
        'firstpRelease',
        'pip',
        'statutoryLiens',
        'otherLiensSubrogationInterests',
        'disbursal',
        'checks',
        "todoNote"
    ];

    // Relationships
    public function todoSection()
    {
        return $this->hasMany(TodoSection::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_case', 'case_id', 'user_id')
                    ->withTimestamps()
                    ->wherePivot('isDeleted', 0);
    }

    public function userCases()
    {
        return $this->hasMany(UserCase::class, 'case_id', 'id');
    }

    public function categorie()
    {
        return $this->belongsTo(Categorie::class, 'categoryId');
    }

    public function caseStage()
    {
        return $this->belongsTo(CaseStage::class);
    }

    public function clients()
    {
        return $this->hasMany(CaseClient::class, 'caseId', 'id');
    }

    public function firstParties()
    {
        return $this->hasMany(CaseFirstP::class, 'caseId', 'id');
    }

    public function thirdParties()
    {
        return $this->hasMany(CaseThirdP::class, 'caseId', 'id');
    }

    public function defenseCounsels()
    {
        return $this->hasMany(CaseDefenseCounsel::class, 'caseId', 'id');
    }

    public function depositExpensesAdvs()
    {
        return $this->hasMany(CaseDepositExpensesAdv::class, 'caseId', 'id');
    }
}
