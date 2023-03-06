<?php

namespace App\Http\Livewire\Table;

use App\Models\HideableColumn;
use App\Models\DataHalaman;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use Yudican\LaravelCrudGenerator\Livewire\Table\LivewireDatatable;

class DataHalamanTable extends LivewireDatatable
{
    protected $listeners = ['refreshTable'];
    public $hideable = 'select';
    public $table_name = 'tbl_data_halaman';

    public function builder()
    {
        return DataHalaman::query();
    }

    public function columns()
    {
        return [
            Column::name('id')->label('No.'),
            Column::name('category.name')->label('Category')->searchable(),
            Column::name('judul')->label('Judul')->searchable(),
            // Column::name('slug')->label('Slug')->searchable(),
            Column::callback(['banner'], function ($image) {
                return view('livewire.components.photo', [
                    'image_url' => asset('storage/' . $image),
                ]);
            })->label(__('Banner')),
            // Column::name('isi')->label('Isi')->searchable(),

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
        $this->emit('getDataDataHalamanById', $id);
    }

    public function getId($id)
    {
        $this->emit('getDataHalamanId', $id);
        $this->emit('showModalConfirm', 'show');
    }

    public function refreshTable()
    {
        $this->emit('refreshLivewireDatatable');
    }
}
