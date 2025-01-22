<?php

namespace App\Exports;

use App\Models\DonationHistory;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DonationHistoryExport implements FromQuery, WithHeadings
{
    use Exportable;

    public function headings(): array
    {
        return [
            'Email',
            'Name',
            'Donation',
            'Nominal',
            'Status',
            'Created',
        ];
    }

    public function query()
    {   
        return DonationHistory::select(['users.email', 'users.name', 'donations.title', 'nominal', 'donation_histories.status', 'donation_histories.created_at'])
            ->with(['user', 'donation'])
            ->join('users', 'donation_histories.user_id', 'users.id')
            ->join('donations', 'donation_histories.donation_id', 'donations.id')
            ->latest();
    }
}
