<?php

namespace App\Filament\Resources\Enquiries\Actions;

use App\Enums\EnquiryStatus;
use App\Mail\Enquiries\EnquiryResponseMail;
use App\Models\Enquiry;
use BackedEnum;
use Closure;
use Filament\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Mail;




class RespondAction extends Action
{
  protected string | BackedEnum | Htmlable | Closure | false | null $icon = Heroicon::Envelope;
  protected string | Htmlable | Closure | null $label = "Respond via Email";

  protected string | array | Closure | null $color = 'info';

  public function name(?string $name): static
  {
    $this->name = $name ?? 'Reply Enquiry';

    return $this;
  }

  protected function setUp(): void
  {
    parent::setUp();

    $this->schema([
      TextInput::make('subject')
        ->required()
        ->maxLength(255),

      RichEditor::make('message')
        ->required()
        ->maxLength(65535),

      Checkbox::make('mark_as_resolved')
        ->label('Mark as Resolved with this response')
        ->helperText('This will update the status to Resolved after sending the response.'),

      Checkbox::make('mark_as_closed')
        ->label('Mark as Closed with this response')
        ->helperText('This will update the status to Closed after sending the response.'),
    ]);

    $this->modalHeading('Respond to Enquiry')
      ->modalSubmitActionLabel('Send Response')
      ->action(function (Enquiry $enquiry, array $data): void {
        // Send email
        Mail::to($enquiry->email)->queue(new EnquiryResponseMail($enquiry, $data['subject'], $data['message']));

        // Base update for response
        $newNote = "Timestamp: " . now()->format('M d, Y g:i A') . "\n" .
          "Action: Responded via email\n" .
          "Message: \"" . $data['message'] . "\"\n\n";

        $updates = [
          'admin_notes' => $newNote . $enquiry->admin_notes,
          'responded_at' => now(),
        ];

        // Handle optional resolution/closure
        if ($data['mark_as_resolved'] ?? false) {
            $updates['status'] = EnquiryStatus::Resolved->value;
            $updates['admin_notes'] = "Timestamp: " . now()->format('M d, Y g:i A') . "\n" .
                "Action: Marked as Resolved with the mail\n\n" ;
                
        } elseif ($data['mark_as_closed'] ?? false) {
            $updates['status'] = EnquiryStatus::Closed->value; // Assuming Closed enum exists; adjust if needed
            // If there's a markAsClosed method, call it here
            $updates['admin_notes'] = "Timestamp: " . now()->format('M d, Y g:i A') . "\n" .
                "Action: Marked as Closed with mail response\n\n";
        } else {
            $updates['status'] = EnquiryStatus::Processing->value;
        }

        // Apply updates
        $enquiry->update($updates);
      })
      ->successNotification(
        Notification::make()
          ->success()
          ->title('Response sent successfully')
      );

      $this->hidden(fn (Enquiry $enquiry) => $enquiry->status === EnquiryStatus::Resolved || $enquiry->status === EnquiryStatus::Closed);
  }
}