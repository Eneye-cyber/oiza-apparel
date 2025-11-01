<?php

namespace App\Filament\Resources\Enquiries\Actions;

use App\Enums\EnquiryStatus;
use App\Models\Enquiry;
use BackedEnum;
use Closure;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

class ResolveAction extends Action
{
  protected string | BackedEnum | Htmlable | Closure | false | null $icon = Heroicon::CheckBadge;

  protected string | Htmlable | Closure | null $label = "Mark Resolved";

  protected string | array | Closure | null $color = 'primary';

  public function name(?string $name): static
  {
    $this->name = $name ?? 'markAsResolved';

    return $this;
  }

  protected function setUp(): void
  {
    parent::setUp();

    $this->requiresConfirmation()
      ->modalHeading('Confirm Resolution')
      ->modalDescription('Are you sure this issue has been resolved?')
      ->modalSubmitActionLabel('Yes, Resolve')
      ->action(function (Enquiry $enquiry): void {
        $enquiry->markAsResolved();

        // Optional: Update admin_notes for consistency with RespondAction
        $newNote = "Timestamp: " . now()->format('M d, Y g:i A') . "\n" .
          "Action: Marked as Resolved\n\n";

        $enquiry->update([
          'status' => EnquiryStatus::Resolved->value, // Assuming Resolved enum exists; adjust if needed
          'admin_notes' => $newNote . $enquiry->admin_notes,
        ]);
      })
      ->successNotification(
        Notification::make()
          ->success()
          ->title('Enquiry marked as resolved')
      );
      $this->hidden(fn (Enquiry $enquiry) => $enquiry->status === EnquiryStatus::Resolved || $enquiry->status === EnquiryStatus::Closed);
  }
}
