<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')
            ->where('role', 'volunteer')
            ->whereNotIn('id', DB::table('volunteer_applications')->select('user_id')->where('status', 'Approved'))
            ->update(['role' => 'citizen', 'volunteer_id' => null]);

        $approvedVolunteerIds = DB::table('users')
            ->join('volunteer_applications', 'volunteer_applications.user_id', '=', 'users.id')
            ->where('volunteer_applications.status', 'Approved')
            ->where('users.role', 'volunteer')
            ->whereNull('users.volunteer_id')
            ->select('users.id')
            ->distinct()
            ->orderBy('users.id')
            ->get();

        foreach ($approvedVolunteerIds as $volunteer) {
            DB::table('users')
                ->where('id', $volunteer->id)
                ->update(['volunteer_id' => 'SUR-VOL-'.str_pad((string) $volunteer->id, 6, '0', STR_PAD_LEFT)]);
        }
    }

    public function down(): void
    {
        // Keep assigned IDs stable if this migration is rolled back.
    }
};
