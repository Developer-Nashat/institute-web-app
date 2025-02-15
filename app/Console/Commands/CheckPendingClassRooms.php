<?php

namespace App\Console\Commands;

use App\Filament\Resources\AffiliationClassRoomResource;
use App\Models\AffiliationClassRoom;
use App\Models\User;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckPendingClassRooms extends Command
{
    protected $signature = 'check:pending-class-rooms';
    protected $description = 'Check for pending and active class rooms starting or ending today';

    public function handle()
    {
        $this->checkPendingRooms();
        $this->checkActiveRooms();
        $this->info('Successfully checked pending and active classrooms.');
    }

    private function checkPendingRooms()
    {
        $pendingRooms = AffiliationClassRoom::where('status', 'pending')
            ->whereDate('start_date', today())
            ->get();

        if ($pendingRooms->isEmpty()) {
            $this->info('No pending rooms starting today.');
            return;
        }

        $users = User::all();
        $this->info("Found {$pendingRooms->count()} pending rooms for today.");
        $this->info("Notifying {$users->count()} users.");

        foreach ($pendingRooms as $room) {
            if ($this->notificationAlreadySent($room->id)) {
                $this->info("Notification already sent for room ID: {$room->id}");
                continue;
            }

            $this->notifyUsers($users, $room, $pendingRooms->count());
        }
    }

    private function checkActiveRooms()
    {
        $activeRooms = AffiliationClassRoom::where('status', 'active')
            ->whereDate('end_date', today())
            ->get();

        if ($activeRooms->isEmpty()) {
            $this->info('No active rooms ending today.');
            return;
        }

        foreach ($activeRooms as $room) {
            $room->update(['status' => 'completed']);
        }

        $this->info("Updated status of {$activeRooms->count()} rooms to 'completed'.");
    }

    private function notificationAlreadySent($roomId): bool
    {
        return DB::table('notifications')
            ->where('type', 'like', '%AffiliationClassRoom%')
            ->where('data->record_id', $roomId)
            ->exists();
    }

    private function notifyUsers($users, $room, $roomCount)
    {
        foreach ($users as $user) {
            $this->info("Sending notification to user: {$user->name}");
            Notification::make()
                ->title('Pending Room Activation Required')
                ->body("There are {$roomCount} rooms needing activation today.")
                ->actions([
                    Action::make('view')
                        ->label('View List')
                        ->url(AffiliationClassRoomResource::getUrl('view', ['record' => $room->id])),
                    Action::make('activate')
                        ->label('Activate')
                        ->color('info')
                        ->action(function () use ($room) {
                            $room->update(['status' => 'active']);
                            DB::table('notifications')
                                ->where('data->record_id', $room->id)
                                ->delete();
                        })
                ])
                ->warning()
                ->sendToDatabase($user);
        }
    }
}
