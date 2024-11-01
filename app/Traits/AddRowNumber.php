<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

trait AddRowNumber
{
    /**
     * Scope for adding row number
     *
     * @param \Illuminate\Database\Eloquent\Builder $model
     * @param string                                $column (Optional) - The basis for the row number
     * @param bool                                  $desc (Optional) - The sorting for the row number
     *
     * @return void
     */
    public function scopeRowNumber(Builder $model, string $column = 'id', bool $desc = false): void
    {
        $sorting = $desc ? 'DESC' : 'ASC';

        // Check if there are any existing selections
        $selects = $model->getQuery()->columns;

        // If no selects are specified, add SELECT *
        if (empty($selects)) {
            $model->select('*'); // Add SELECT *
        }

        $model->addSelect(
            DB::raw("ROW_NUMBER() OVER (ORDER BY {$column} {$sorting}) AS row_number")
        );
    }
}
