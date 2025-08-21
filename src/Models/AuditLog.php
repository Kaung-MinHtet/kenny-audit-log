<?php

namespace Kenny\Src\AuditLog\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User; // Assuming User model is in App\Models for clarity in package usage

class AuditLog extends Model
{
    use HasFactory;

    protected $guarded = []; // Allow mass assignment for all attributes

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'audit_logs';

    /**
     * Get the auditable model that owns the AuditLog.
     */
    public function auditable(): BelongsTo
    {
        return $this->morphTo();
    }

    /**
     * Get the user that performed the action.
     */
    public function user(): BelongsTo
    {
        // Assuming your User model is App\Models\User
        // You might want to make this configurable in a real package
        return $this->belongsTo(User::class);
    }
}
