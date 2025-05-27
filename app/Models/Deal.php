<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Deal extends Model
{
    use SoftDeletes, HasUuids, HasFactory;

    protected $fillable = [
        'contact_id',
        'title',
        'amount',
        'currency',
        'status'
    ];
    protected $hidden = ['deleted_at'];

    protected $casts = [
        'amount' => 'decimal:2'
    ];

    public const STATUS_OPEN = 'open';
    public const STATUS_WON = 'closed-won';
    public const STATUS_LOST = 'closed-lost';

    public static function statuses(): array 
    {
        return [
            self::STATUS_OPEN => 'Open',
            self::STATUS_WON => 'Closed Won',
            self::STATUS_LOST => 'Closed Lost'
        ];
    }


    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function getCompanyAttribute()
    {
        return $this->contact->company;   
    }
}