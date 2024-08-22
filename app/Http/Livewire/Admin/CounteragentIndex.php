<?php

namespace App\Http\Livewire\Admin;

use App\Models\Counteragent;
use App\Models\CounteragentGroup;
use App\Models\Store;
use Livewire\Component;
use Livewire\WithPagination;

class CounteragentIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search;
    public $group_id;

    public function render()
    {
        return view('admin.counteragent.index_live', [
            'counteragents' => Counteragent::query()
                ->when($this->search, function ($q) {
                    return $q->where(function ($qq) {
                        return $qq->where('name', 'LIKE', "%$this->search%")
                            ->orWhere('bin', 'LIKE', "%$this->search%");
                    });
                })
                ->when($this->group_id, function ($q) {
                    $q->where('group_id', $this->group_id);
                })
                ->orderBy('counteragents.name')
                ->paginate(30),
            'groups' => CounteragentGroup::query()
                ->orderBy('name')
                ->get()

        ]);
    }
    public function delete($id)
    {
        Store::where('id', $id)->delete();
    }

}
