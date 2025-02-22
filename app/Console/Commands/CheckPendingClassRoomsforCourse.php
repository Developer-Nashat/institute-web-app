<?php

namespace App\Console\Commands;

use App\Filament\Resources\CourseResource;
use App\Models\Course;
use Illuminate\Console\Command;
use App\Models\User;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class CheckPendingClassRoomsforCourse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-pending-class-roomsfor-course';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for pending and active class rooms starting or ending today';


    /**
     * Execute the console command.
     */

    public function handle()
    {
        $this->checkPendingRooms();
        $this->checkActiveRooms();
        $this->info('Successfully checked pending and active classrooms.');
    }

    private function checkPendingRooms()
    {
        $pendingRooms = Course::where('status', 'pending')
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
        $activeRooms = Course::where('status', 'active')
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
                ->title('قاعة تدريب معلقة بحاجة الي تفعيل')
                ->body("{$roomCount} قاعات تدريب معلقة يجب تفعيلها اليوم.")
                ->actions([
                    Action::make('view')
                        ->label('View List')
                        ->url(CourseResource::getUrl('view', ['record' => $room->id])),
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
