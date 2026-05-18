<?php

namespace App\Exports;

use App\Models\DiaryEntry;
use App\Models\DiaryProfile;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DiaryProfileExport implements FromQuery, WithHeadings, WithMapping
{
    protected $profile;
    protected $status;
    protected $month;
    protected $year;

    public function __construct(DiaryProfile $profile, $status = null, $month = null, $year = null)
    {
        $this->profile = $profile;
        $this->status = $status;
        $this->month = $month;
        $this->year = $year;
    }

    public function query()
    {
        $query = DiaryEntry::query()->where('diary_profile_id', $this->profile->id);

        if ($this->status === 'cleared') {
            $query->where('is_cleared', true);
        } elseif ($this->status === 'pending') {
            $query->where('is_cleared', false);
        }

        if ($this->month) {
            $query->whereMonth('created_at', $this->month);
        }
        if ($this->year) {
            $query->whereYear('created_at', $this->year);
        }

        return $query->latest();
    }

    public function headings(): array
    {
        return [
            'Parchi #',
            'Title',
            'Price (PKR)',
            'Status',
            'Date Created',
        ];
    }

    public function map($entry): array
    {
        return [
            $entry->id,
            $entry->title,
            $entry->price,
            $entry->is_cleared ? 'Cleared' : 'Pending',
            $entry->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
