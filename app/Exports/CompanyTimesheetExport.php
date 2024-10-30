<?php

namespace App\Exports;

use App\Models\Timesheet;
use App\Models\RcUsers;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;


class CompanyTimesheetExport implements FromCollection, WithHeadings, WithMapping
{
    protected $status;
    protected $companyEmail;

    public function __construct($status = null, $companyEmail)
    {
        $this->status = $status;
        $this->companyEmail = $companyEmail;
    }

    public function collection()
    {
        // Get users reporting to the company
        $company_users = RcUsers::where('reportingTo', $this->companyEmail)->get();
        $userEmails = $company_users->pluck('email')->toArray();

        // Base query
        $query = Timesheet::whereIn('user_email', $userEmails);

        // Apply status filter if specified
        if ($this->status) {
            $query->where('status', $this->status);
        }

        return $query->get()->map(function ($timesheet) use ($company_users) {
            $timesheet->name = $company_users->firstWhere('email', $timesheet->user_email)->name ?? 'N/A';
            return $timesheet;
        });
    }

    public function headings(): array
    {
        return [
            'Day',
            'Name',
            'Email',
            'Cost Center',
            'Currency',
            'Date',
            'Start Time',
            'Close Time',
            'Break Start',
            'Break End',
            'Timezone',
            'Status',
            'Work Time'
        ];
    }

    public function map($timesheet): array
    {
        return [
            $timesheet->day,
            $timesheet->name,
            $timesheet->user_email,
            $timesheet->cost_center,
            $timesheet->currency,
            $timesheet->date,
            $timesheet->start_time,
            $timesheet->close_time,
            $timesheet->break_start,
            $timesheet->break_end,
            $timesheet->timezone,
            ucfirst($timesheet->status),
            $timesheet->work_time
        ];
    }
}