<?php

namespace App\Filament\Resources\Enquiries\Actions;

use App\Enums\EnquiryStatus;
use App\Models\Enquiry;
use BackedEnum;
use Closure;
use Filament\Actions\Action;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

class CloseAction extends Action
{
    protected string | BackedEnum | Htmlable | Closure | false | null $icon = Heroicon::ArchiveBox;

    protected string | Htmlable | Closure | null $label = "Close Enquiry";

    protected string | array | Closure | null $color = 'gray';

    public function name(?string $name): static
    {
        $this->name = $name ?? 'closeEnquiry';

        return $this;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->schema([
            
            Textarea::make('note')
                ->label('Admin Note (Optional)')
                ->rows(5)
                ->placeholder('Add any additional notes here...')
                ->maxLength(65535),
        ]);

        $this->modalHeading('Close Enquiry')
            ->modalDescription('Are you sure you want to close this enquiry?')
            ->modalSubmitActionLabel('Close')
            ->action(function (Enquiry $enquiry, array $data): void {
                // Optionally append to admin_notes
                $newNote = '';
                if (!empty($data['note'])) {
                    $newNote = "Timestamp: " . now()->format('M d, Y g:i A') . "\n" .
                        "Action: Added note on close\n" .
                        "Note: \"" . $data['note'] . "\"\n\n";
                }

                // Append closing note
                $newNote .= "Timestamp: " . now()->format('M d, Y g:i A') . "\n" .
                    "Action: Marked as Closed\n\n";

                $enquiry->update([
                    'status' => EnquiryStatus::Closed->value, // Adjust to Closed if enum differs
                    'admin_notes' => $newNote . $enquiry->admin_notes,
                ]);
            })
            ->successNotification(
                Notification::make()
                    ->success()
                    ->title('Enquiry closed successfully')
            );

            $this->hidden(fn (Enquiry $enquiry) => $enquiry->status === EnquiryStatus::Resolved || $enquiry->status === EnquiryStatus::Closed);
    }
}