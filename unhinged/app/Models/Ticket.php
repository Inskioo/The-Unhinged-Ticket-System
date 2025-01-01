<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model{
    use HasFactory;

    public $timestamps = false;

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

    protected function serializeDate(\DateTimeInterface $date){
        return $date->format('d/m/Y H:i');
    }

    public function scopebyPriority($query, $priority){
        return $query->where('priority', $priority);
    }

    public function scopebyStatus($query, $status){
        return $query->where('status', $status);
    }

    public function scopebyType($query, $type){
        return $query->where('type', $type);
    }

    public function scopebySupportAssigned($query, $assignedTo = null){ 
        if ($assignedTo === null) { 
            return $query->whereNotNull('assigned_to'); 
        } 
            return $query->where('assigned_to', $assignedTo);
    }

    public function scopebyUnhingedHuman($query, $userId){
        return $query->where('user_id', $userId);
    }

    public function scopeByResolved($query){
        return $query->where('status', 'resolved');
    }
    
    public function scopeByNotResolved($query) {
        return $query->where('status', '!=', 'resolved');
    }
    
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assignedTo(){
        return $this->belongsTo(User::class, 'assigned_to');
    }


}