<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Transaction extends Model
{
    use HasFactory;

    protected $primaryKey = 'invoice_number';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];
    protected $attributes = [
        'paid' => 0,
        'cancel' => 0,
        'note' => '',
        'pelanggan' => ''
    ];

    protected $table = 'transactions';

    public function products(){
        return $this->hasMany(ProductTransaction::class, 'invoice_number', 'invoice_number' );
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function scopePaid($query){
        $query->where('paid', 1);
    }

    public function scopeMonth($query){
        $query->where(DB::raw('LEFT(created_at, 7)'), date('Y-m'));
    }

    public function scopeToday($query){
        $query->where(DB::raw('LEFT(created_at, 10)'), date('Y-m-d'));
    }
}
