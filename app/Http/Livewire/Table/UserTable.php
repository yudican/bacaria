<?php

namespace App\Http\Livewire\Table;

use App\Models\HideableColumn;
use App\Models\BannerCategory;
use App\Models\User;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use Yudican\LaravelCrudGenerator\Livewire\Table\LivewireDatatable;

class UserTable extends LivewireDatatable
{
    protected $listeners = ['refreshTable'];
    public $hideable = 'select';
    public $table_name = 'tbl_banner_categories';

    public function builder()
    {
        return User::query()->whereHas('roles', function ($query) {
            $query->where('role_type', '!=', 'superadmin');
        })->orderBy('name', 'ASC');
    }

    public function columns()
    {
        return [
            Column::name('id')->label('No.'),
            Column::name('name')->label('Nama')->searchable(),
            Column::name('email')->label('Email')->searchable(),
            Column::callback(['id', 'email'], function ($id, $email) {
                $user = User::find($id);
                if ($user) {
                    return $user->role->role_name;
                }
            })->label('Role')->searchable(),

            Column::callback(['id'], function ($id) {
                return view('crud-generator-components::action-button', [
                    'id' => $id,
                    'actions' => [
                        [
                            'type' => 'button',
                            'route' => 'getDataById(' . $id . ')',
                            'label' => 'Edit',
                        ],
                        [
                            'type' => 'button',
                            'route' => 'getId(' . $id . ')',
                            'label' => 'Hapus',
                        ]
                    ]
                ]);
            })->label(__('Aksi')),
        ];
    }

    public function getDataById($id)
    {
        $this->emit('getDataBannerCategoryById', $id);
    }

    public function getId($id)
    {
        $this->emit('getBannerCategoryId', $id);
        $this->emit('showModalConfirm', 'show');
    }

    public function refreshTable()
    {
        $this->emit('refreshLivewireDatatable');
    }
}
