<?php

namespace App\Models;

use App\Enums\TodoState;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;

    protected $casts = [
        'state' => TodoState::class,
    ];

    protected $fillable = [
        'title',
        'state',
        'deadline',
    ];

    public function scopePending($query)
    {
        return $query->where('state', TodoState::Pending);
    }

    public function scopeDone($query)
    {
        return $query->where('state', TodoState::Done);
    }

    public function markAsDone()
    {
        $this->update([
            'state' => TodoState::Done,
        ]);
    }

    public function markAsPending()
    {
        $this->update([
            'state' => TodoState::Pending,
        ]);
    }
}
