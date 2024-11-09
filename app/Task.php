<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected  $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'completed',
        'due_date',
    ];
    protected $casts = [
        'completed' => 'boolean', // Cast pour assurer que completed est toujours un booléen
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
