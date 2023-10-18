<?php

namespace App\Models;

use App\Enums\TodoState;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'state',
        'deadline',
    ];

    public function scopePending($query)
    {
        return $query->where('state', TodoState::PENDING);
    }

    public function scopeDone($query)
    {
        return $query->where('state', TodoState::DONE);
    }

    public function markAsDone()
    {
        $this->update([
            'state' => TodoState::DONE,
        ]);
    }

    public function markAsPending()
    {
        $this->update([
            'state' => TodoState::PENDING,
        ]);
    }

    public function markAsArchived()
    {
        $this->update([
            'state' => TodoState::ARCHIVED,
        ]);
    }
}
