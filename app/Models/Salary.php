<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    //
    public function receipt()
    {
        return $this->hasOne(Receipt::class, 'salary_id', 'id');
    }
}
