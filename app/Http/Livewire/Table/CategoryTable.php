<?php

namespace App\Http\Livewire\Table;

use App\Models\HideableColumn;
use App\Models\Category;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use Yudican\LaravelCrudGenerator\Livewire\Table\LivewireDatatable;

class CategoryTable extends LivewireDatatable
{
    protected $listeners = ['refreshTable'];
    public $hideable = 'select';
    public $table_name = 'tbl_categories';

    public function builder()
    {
        return Category::query();
    }

    public function columns()
    {
        return [
            Column::name('id')->label('No.'),
            Column::name('name')->label('Name')->searchable(),
            Column::name('layout.name')->label('Layout')->searchable(),
            Column::name('slug')->label('Slug')->searchable(),
            // Column::callback(['image'], function ($image) {
            //     return view('livewire.components.photo', [
            //         'image_url' => asset('storage/' . $image),
            //     ]);
            // })->label(__('Image')),

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
        $this->emit('getDataCategoryById', $id);
    }

    public function getId($id)
    {
        $this->emit('getCategoryId', $id);
        $this->emit('showModalConfirm', 'show');
    }

    public function refreshTable()
    {
        $this->emit('refreshLivewireDatatable');
    }
}
