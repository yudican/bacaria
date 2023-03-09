<?php

namespace App\Http\Livewire\Table;

use App\Models\HideableColumn;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use Yudican\LaravelCrudGenerator\Livewire\Table\LivewireDatatable;

class PostTable extends LivewireDatatable
{
    protected $listeners = ['refreshTable'];
    public $hideable = 'select';
    public $table_name = 'tbl_posts';

    public function builder()
    {
        $user = auth()->user();
        if ($user->hasRole(['admin', 'superadmin'])) {
            return Post::query();
        } else {
            return Post::where('author_id', $user->id);
        }
        return Post::query();
    }

    public function columns()
    {
        return [
            Column::name('id')->label('No.'),
            Column::name('title')->label('Title')->searchable(),
            Column::name('author.name')->label('Author')->searchable(),
            Column::name('status')->label('Status')->searchable(),
            Column::name('publish_status')->label('Status Publish')->searchable(),
            Column::name('type')->label('Type')->searchable(),
            Column::name('created_at')->label('Tanggal')->searchable(),

            Column::callback(['id', 'publish_status'], function ($id, $publish_status) {
                $user = auth()->user();
                $actions = [
                    [
                        'type' => 'button',
                        'route' => 'getDataById(' . $id . ')',
                        'label' => 'Edit',
                    ],
                    [
                        'type' => 'button',
                        'route' => 'showDetail(' . $id . ')',
                        'label' => 'Detail',
                    ],

                ];

                if ($user->hasRole(['admin', 'superadmin']) && $publish_status == 'waiting') {
                    $actions[] = [
                        'type' => 'button',
                        'route' => 'approve(' . $id . ')',
                        'label' => 'Approve',
                    ];
                    $actions[] = [
                        'type' => 'button',
                        'route' => 'showModalReject(' . $id . ')',
                        'label' => 'Reject',
                    ];
                }

                $actions[] = [
                    'type' => 'button',
                    'route' => 'getId(' . $id . ')',
                    'label' => 'Hapus',
                ];

                return view('crud-generator-components::action-button', [
                    'id' => $id,
                    'actions' => $actions
                ]);
            })->label(__('Aksi')),
        ];
    }

    public function getDataById($id)
    {
        $this->emit('getDataPostById', $id);
    }

    public function getId($id)
    {
        $this->emit('getPostId', $id);
        $this->emit('showModalConfirm', 'show');
    }

    public function showDetail($id)
    {
        $this->emit('getPostId', $id);
        $this->emit('showModalDetail', 'show');
    }

    public function showModalReject($id)
    {
        $this->emit('getPostId', $id);
        $this->emit('showModalReject', 'show');
    }

    public function refreshTable()
    {
        $this->emit('refreshLivewireDatatable');
    }

    public function approve($id)
    {
        $row = Post::find($id);
        $row->update([
            'publish_status' => 'published',
            'approved_user_id' => Auth::user()->id
        ]);

        $this->emit('showAlert', ['msg' => 'Data berhasil diapprove']);
        $this->emit('closeModal');
        $this->emit('refreshLivewireDatatable');
    }
}
