<?php

namespace App\Exports;

use App\Models\Expense;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExpensesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $month, $status, $userId, $search;

    public function __construct($month, $status, $userId, $search, $categoryId = null)
{
    $this->month = $month;
    $this->status = $status;
    $this->userId = $userId;
    $this->search = $search;
    $this->categoryId = $categoryId;
}

    public function collection()
{
    $query = Expense::with(['user', 'category']);

    // Month filter
    if ($this->month && str_contains($this->month, '-')) {
        [$year, $month] = explode('-', $this->month);

        $query->whereYear('expense_date', $year)
              ->whereMonth('expense_date', $month);
    }

    // Status filter
    if ($this->status) {
        $query->where('status', $this->status);
    }

    // User filter
    if ($this->userId) {
        $query->where('user_id', $this->userId);
    }

    // Search filter
    if ($this->search) {
        $query->where('title', 'like', '%' . $this->search . '%');
    }

    // ⭐ CATEGORY FILTER (MISSING BEFORE)
    if ($this->categoryId) {
        $query->where('category_id', $this->categoryId);
    }

    return $query->latest('expense_date')->get();
}

    public function headings(): array
    {
        return ['ID', 'Manager', 'Category', 'Title', 'Amount', 'Date', 'Status'];
    }

    public function map($expense): array
{
    return [
        $expense->id,
        $expense->user->name ?? 'N/A',
        $expense->category->name ?? 'N/A',
        $expense->title ?? '',
        $expense->amount ?? 0,
        optional($expense->expense_date)->format('Y-m-d'),
        $expense->status ?? '',
    ];
}
}


