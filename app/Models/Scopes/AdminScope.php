<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class AdminScope implements Scope
{
    public function apply(Builder $builder, Model $model)
{
    if (Auth::guard('superadmin')->check()) {
        return;
    }

    if (Auth::guard('web')->check()) {

        // hanya apply kalau model memang punya id_admin
        if (\Schema::hasColumn($model->getTable(), 'id_admin')) {
            $builder->where($model->getTable().'.id_admin', Auth::id());
        }
    }
}
}