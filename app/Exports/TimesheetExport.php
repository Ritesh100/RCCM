<?php 
namespace App\Exports;

use App\Models\Timesheet;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TimesheetExport implements FromCollection, WithHeadings
{
    protected $status;

    public function __construct($status = null)
    {
        $this->status = $status;
    }

    public function collection()
    {
        // Fetch timesheets based on status
        $timesheets = $this->status ? Timesheet::where('status', $this->status)->get() : Timesheet::all();

        // Map the timesheet data
        return $timesheets->map(function ($timesheet, $index) {
            return [
                'S.No.' => $index + 1,  // Serial number
                'Day' => $timesheet->day, // Day of the week
                'Cost Center' => $timesheet->cost_center, // Cost center
                'Date' => $timesheet->date, // Date of the entry
                'Start Time' => $timesheet->start_time, // Start time of the work
                'Close Time' => $timesheet->close_time, // Close time of the work
                'Break Start' => $timesheet->break_start, // Break start time
                'Break End' => $timesheet->break_end, // Break end time
                'Time Zone' => $timesheet->timezone, // Timezone information
                'Status' => $timesheet->status, // Status of the timesheet (e.g., approved, pending)
                'Email' => $timesheet->user_email, // Email of the user
                'Created At' => $timesheet->created_at, // Timestamp of creation
                'Updated At' => $timesheet->updated_at, // Timestamp of last update
                'Currency' => $timesheet->currency, // Currency information
            ];
        });
    }

    public function headings(): array
    {
        return [
            'S.No.',           // Column 1: Serial number
            'Day',            // Column 3: Day of the week
            'Cost Center',    // Column 4: Cost center
            'Date',           // Column 5: Date of the entry
            'Start Time',     // Column 6: Start time of the work
            'Close Time',     // Column 7: Close time of the work
            'Break Start',    // Column 8: Break start time
            'Break End',      // Column 9: Break end time
            'Time Zone',      // Column 10: Timezone information
            'Status',         // Column 11: Status of the timesheet
            'Email',          // Column 12: Email of the user
            'Created At',     // Column 13: Timestamp of creation
            'Updated At',     // Column 14: Timestamp of last update
            'Currency',       // Column 16: Currency information
        ];
    }
}
