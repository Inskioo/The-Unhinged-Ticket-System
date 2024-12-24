<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model{
    use hasFactory;

    protected $fillable = [
        'user_id',
        'assigned_to',
        'subject',
        'content',
        'status',
        'priority',
        'type',
        'created_at',
        'resolved_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'resolved_at' => 'datetime'
    ];

    public function scopebyPriority($query, $priority){
        return $query->where('priority', $priority);
    }

    public function scopebyStatus($query, $status){
        return $query->where('status', $status);
    }

    public function scopebyType($query, $type){
        return $query->where('type', $type);
    }

    public function scopebySupportAssigned($query, $assignedTo){
        return $query->where('assigned_to', $assignedTo);
    }

    public function scopebyUnhingedHuman($query, $userId){
        return $query->where('user_id', $userId);
    }

    public function scopebyResolved($query){
        return $query->whereNotNull('resolved_at');
    }

    public function scopebyNotResolved($query){
        return $query->whereNull('resolved_at');
    }


}