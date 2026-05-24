<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'feedbacks';

    // Hằng số trạng thái
    const STATUS_UNREAD = 0; // Chưa xem
    const STATUS_READ   = 1; // Đã xem

    protected $fillable = [
        'account_id',
        'title',
        'content',
        'file_path',
        'reply_title',
        'reply',
        'reply_file_path',
        'status',
    ];

    protected $casts = [
        'status' => 'integer',
    ];

    /**
     * Quan hệ với Account
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Scope: lọc feedback chưa xem
     */
    public function scopeUnread($query)
    {
        return $query->where('status', self::STATUS_UNREAD);
    }

    /**
     * Scope: lọc feedback đã xem
     */
    public function scopeRead($query)
    {
        return $query->where('status', self::STATUS_READ);
    }

    /**
     * Kiểm tra feedback đã xem chưa
     */
    public function isRead(): bool
    {
        return $this->status === self::STATUS_READ;
    }
}
