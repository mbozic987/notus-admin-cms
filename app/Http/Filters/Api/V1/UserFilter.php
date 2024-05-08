<?php

namespace App\Http\Filters\Api\V1;

class UserFilter extends QueryFilter
{
    protected $sortable = [
        'name',
        'email',
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at'
    ];

    // Include relationships in response
    public function include($value)
    {
        return $this->builder->with($value);
    }

    //Filter by set of id's
    public function id($value)
    {
        return $this->builder->whereIn('id', explode(',', $value));
    }

    //Filter by part of name
    public function name($value)
    {
        return $this->builder->where('name', 'like', '%'.$value.'%');
    }

    //Filter by part of email
    public function email($value)
    {
        return $this->builder->where('email', 'like', '%'.$value.'%');
    }

    //Filter by created_at date (must be YYYY-MM-DD format), can use two values and get all products between set dates
    public function createdAt($value)
    {
        $dates = explode(',', $value);

        if (count($dates) > 1) {
            return $this->builder->whereBetween('created_at', $value);
        }

        return $this->builder->whereDate('created_at', $value);
    }

    //Filter by updated_at date (must be YYYY-MM-DD format), can use two values and get all products between set dates
    public function updatedAt($value)
    {
        $dates = explode(',', $value);

        if (count($dates) > 1) {
            return $this->builder->whereBetween('updated_at', $value);
        }

        return $this->builder->whereDate('updated_at', $value);
    }
}
