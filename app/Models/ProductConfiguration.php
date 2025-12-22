<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductConfiguration extends Model
{
    use HasFactory;

    protected $fillable = [
        'insurance_name',
        'claim_number',
        'bi_adjuster',
        'tel',
        'email',
        'fax',
    ];
}
