<?php

namespace App\Http\Livewire\Post;

use App\Models\DataIklan;
use App\Models\JenisIklan;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class DataIklanController extends Component
{
    use WithFileUploads;
    public $data_iklan_id;
    public $jenis_iklan_id;
    public $nama_iklan;
    public $kode_iklan;
    public $image;
    public $link;
    public $image_path;


    public $route_name = null;

    public $form_active = false;
    public $form = true;
    public $update_mode = false;
    public $modal = false;

    protected $listeners = ['getDataDataIklanById', 'getDataIklanId'];

    public function mount()
    {
        $this->route_name = request()->route()->getName();
    }

    public function render()
    {
        return view('livewire.post.data-iklan', [
            'jenis_iklan' => JenisIklan::all()
        ])->layout(config('crud-generator.layout'));
    }

    public function store()
    {
        $this->_validate();

        $data = [
            'jenis_iklan_id'  => $this->jenis_iklan_id,
            'nama_iklan'  => $this->nama_iklan,
            'kode_iklan'  => $this->kode_iklan,
            'link'  => $this->link
        ];

        if ($this->image_path) {
            $image = $this->image_path->store('upload', 'public');
            $data['image'] = $image;
        }

        DataIklan::create($data);

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Disimpan']);
    }

    public function update()
    {
        $this->_validate();

        $data = [
            'jenis_iklan_id'  => $this->jenis_iklan_id,
            'nama_iklan'  => $this->nama_iklan,
            'kode_iklan'  => $this->kode_iklan,
            'link'  => $this->link
        ];
        $row = DataIklan::find($this->data_iklan_id);


        if ($this->image_path) {
            $image = $this->image_path->store('upload', 'public');
            $data['image'] = $image;
            if (Storage::exists('public/' . $this->image)) {
                Storage::delete('public/' . $this->image);
            }
        }

        $row->update($data);

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Diupdate']);
    }

    public function delete()
    {
        DataIklan::find($this->data_iklan_id)->delete();

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Dihapus']);
    }

    public function _validate()
    {
        $rule = [
            'jenis_iklan_id'  => 'required',
            'nama_iklan'  => 'required',
            'kode_iklan'  => 'required',
            'link'  => 'required',
        ];

        if (!$this->update_mode) {
            $rule['image_path'] = 'required|image';
        }

        return $this->validate($rule);
    }

    public function getDataDataIklanById($data_iklan_id)
    {
        $this->_reset();
        $row = DataIklan::find($data_iklan_id);
        $this->data_iklan_id = $row->id;
        $this->jenis_iklan_id = $row->jenis_iklan_id;
        $this->nama_iklan = $row->nama_iklan;
        $this->kode_iklan = $row->kode_iklan;
        $this->image = $row->image;
        $this->link = $row->link;
        if ($this->form) {
            $this->form_active = true;
            $this->emit('loadForm');
        }
        if ($this->modal) {
            $this->emit('showModal');
        }
        $this->update_mode = true;
    }

    public function getDataIklanId($data_iklan_id)
    {
        $row = DataIklan::find($data_iklan_id);
        $this->data_iklan_id = $row->id;
    }

    public function toggleForm($form)
    {
        $this->_reset();
        $this->form_active = $form;
        $this->emit('loadForm');
    }

    public function showModal()
    {
        $this->_reset();
        $this->emit('showModal');
    }

    public function _reset()
    {
        $this->emit('closeModal');
        $this->emit('refreshTable');
        $this->data_iklan_id = null;
        $this->jenis_iklan_id = null;
        $this->nama_iklan = null;
        $this->kode_iklan = null;
        $this->image_path = null;
        $this->image = null;
        $this->link = null;
        $this->form = true;
        $this->form_active = false;
        $this->update_mode = false;
        $this->modal = false;
    }
}
