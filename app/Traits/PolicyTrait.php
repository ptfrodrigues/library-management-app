<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait PolicyTrait
{
    public function viewAny($user)
    {
        return $user->can($this->permission('view'));
    }

    public function view($user, $model)
    {
        return $user->can($this->permission('view'));
    }

    public function create($user)
    {
        return $user->can($this->permission('create'));
    }

    public function update($user, $model)
    {
        return $user->can($this->permission('edit'));
    }

    public function delete($user, $model)
    {
        return $user->can($this->permission('delete'));
    }

    public function viewInContext($user, $model, $context)
    {
        return $user->can($this->permission("view_{$context}"));
    }

    protected function permission($action)
    {
        $modelName = Str::snake(class_basename($this->model));
        return "{$action}_{$modelName}";
    }
}
