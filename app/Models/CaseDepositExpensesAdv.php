<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseDepositExpensesAdv extends Model
{
    protected $table = 'case_deposit_expenses_adv';
    protected $fillable = [
        'caseId', 'depositName', 'depositPrice',
        'depositDate', 'depositCheckNumber',
        'expensesName', 'expensesPrice', 'expensesDate',
        'expensesCheck', 'advancesAmount', 'advancesName',
        'advancesDate', 'advancesCheckNumber'
    ];

    public function courtCase()
    {
        return $this->belongsTo(CourtCase::class, 'caseId', 'id');
    }
}
