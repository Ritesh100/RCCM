<?php

namespace App\Exports;

use App\Models\Timesheet;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UserTimesheetExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    protected $status;
    protected $userEmail;

    public function __construct($status = null, $userEmail)
    {
        $this->status = $status;
        $this->userEmail = $userEmail;
    }

    public function query()
    {
        $query = Timesheet::query()
            ->where('user_email', $this->userEmail);
            
        if ($this->status) {
            $query->where('status', $this->status);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'Day',
            'Reporting To',
            'Cost Center',
            'Currency',
            'Date',
            'Start Time',
            'Close Time',
            'Break Start',
            'Break End',
            'Timezone',
            'Work Time',
            'Status'
        ];
    }

    public function map($timesheet): array
    {
        return [
            $timesheet->day,
            $timesheet->reportingTo,
            $timesheet->cost_center,
            $timesheet->currency,
            $timesheet->date,
            $timesheet->start_time,
            $timesheet->close_time,
            $timesheet->break_start,
            $timesheet->break_end,
            $timesheet->timezone,
            $timesheet->work_time,
            ucfirst($timesheet->status)
        ];
    }
}