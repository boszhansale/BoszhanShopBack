<?php

namespace App\Http\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $roleId;

    public $search;

    public $sort = 'id';

    public $sortBy = 'desc';

    public function render()
    {
        return view('admin.user.index_live', [
            'users' => User::select('users.*')
                ->when($this->search, function ($q) {
                    return $q->where(function ($qq) {
                        return $qq->where('users.name', 'LIKE', '%'.$this->search.'%')
                            ->orWhere('users.login', 'LIKE', '%'.$this->search.'%')
                            ->orWhere('users.id', 'LIKE', '%'.$this->search.'%');
                    });
                })
                ->when($this->roleId, function ($q) {
                    return $q->where('users.role_id', $this->roleId);
                })
                ->groupBy('users.id')
                ->orderBy('users.status')
                ->orderBy($this->sort, $this->sortBy)

                ->get(),
        ]);
    }

    public function statusChange($userId, $status)
    {
        User::whereId($userId)->update([
            'status' => $status,
        ]);
    }
}
