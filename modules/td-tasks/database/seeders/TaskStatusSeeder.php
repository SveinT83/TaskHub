<?php
use Illuminate\Database\Seeder;
use App\Models\TaskStatus;

class TaskStatusSeeder extends Seeder
{
    public function run()
    {
        $statuses = ['Not Started', 'In Progress', 'Completed', 'On Hold'];

        foreach ($statuses as $status) {
            TaskStatus::create(['name' => $status]);
        }
    }
}
