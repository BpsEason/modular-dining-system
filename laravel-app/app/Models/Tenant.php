<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Tenant extends Model
{
    use HasFactory;
    protected $fillable = ['name'];
    public function users()
    {
        return $this->hasMany(User::class);
    }
    public function customers()
    {
        return $this->hasMany(\Modules\CustomerProfile\Models\Customer::class);
    }
    public function tables()
    {
        return $this->hasMany(\Modules\PosCore\Models\Table::class);
    }
}
