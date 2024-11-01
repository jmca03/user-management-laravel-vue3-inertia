<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

trait Pagination
{
    public function paginate(Builder|Model &$model, Request $request): LengthAwarePaginator|Collection
    {
        $page = $request->query('page', 1);
        $limit = $request->query('limit', 10);

        // If -1 get all
        if ($limit === -1) {
            return $model->get();
        }

        return $model->paginate($limit);
    }
}
