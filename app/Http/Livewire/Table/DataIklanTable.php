<?php

namespace App\Http\Livewire\Table;

use App\Models\HideableColumn;
use App\Models\DataIklan;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use Yudican\LaravelCrudGenerator\Livewire\Table\LivewireDatatable;

class DataIklanTable extends LivewireDatatable
{
    protected $listeners = ['refreshTable'];
    public $hideable = 'select';
    public $table_name = 'tbl_data_iklans';

    public function builder()
    {
        return DataIklan::query();
    }

    public function columns()
    {
        return [
            Column::name('id')->label('No.'),
            Column::name('jenisIklan.nama_jenis_iklan')->label('Jenis Iklan')->searchable(),
            Column::name('nama_iklan')->label('Nama Iklan')->searchable(),
            Column::name('kode_iklan')->label('Kode Iklan')->searchable(),
            Column::name('link')->label('Link')->searchable(),

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
        $this->emit('getDataDataIklanById', $id);
    }

    public function getId($id)
    {
        $this->emit('getDataIklanId', $id);
        $this->emit('showModalConfirm', 'show');
    }

    public function refreshTable()
    {
        $this->emit('refreshLivewireDatatable');
    }
}
