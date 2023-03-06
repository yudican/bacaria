<?php

namespace App\Http\Livewire\Table;

use App\Models\HideableColumn;
use App\Models\JenisIklan;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use Yudican\LaravelCrudGenerator\Livewire\Table\LivewireDatatable;

class JenisIklanTable extends LivewireDatatable
{
    protected $listeners = ['refreshTable'];
    public $hideable = 'select';
    public $table_name = 'tbl_jenis_iklans';

    public function builder()
    {
        return JenisIklan::query();
    }

    public function columns()
    {
        return [
            Column::name('id')->label('No.'),
            Column::name('nama_jenis_iklan')->label('Nama Jenis Iklan')->searchable(),
            Column::name('kode_jenis_iklan')->label('Kode Jenis Iklan')->searchable(),
            Column::name('description')->label('Description')->searchable(),

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
        $this->emit('getDataJenisIklanById', $id);
    }

    public function getId($id)
    {
        $this->emit('getJenisIklanId', $id);
        $this->emit('showModalConfirm', 'show');
    }

    public function refreshTable()
    {
        $this->emit('refreshLivewireDatatable');
    }
}
