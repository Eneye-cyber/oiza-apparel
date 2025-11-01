<?php

namespace App\Models;

use App\Enums\EnquiryStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
  //
  use HasFactory;

  protected $fillable = [
    'name',
    'email',
    'phone',
    'subject',
    'message',
    'admin_notes',
    'responded_at',
    'status'
  ];

  protected $casts = [
    'status' => EnquiryStatus::class,
    'responded_at' => 'datetime',
  ];

  public function markAsResolved(): void
  {
    $this->update([
      'status' => EnquiryStatus::Resolved->value,
      'responded_at' => now(),
    ]);
  }

  public function isNew(): bool
  {
    return $this->status === EnquiryStatus::New->value;
  }
}
