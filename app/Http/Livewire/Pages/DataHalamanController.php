<?php

namespace App\Http\Livewire\Pages;

use App\Models\Category;
use App\Models\DataHalaman;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Str;

class DataHalamanController extends Component
{
    use WithFileUploads;
    public $data_halaman_id;
    public $category_id;
    public $judul;
    public $slug;
    public $banner;
    public $isi;
    public $banner_path;


    public $route_name = null;

    public $form_active = false;
    public $form = true;
    public $update_mode = false;
    public $modal = false;

    protected $listeners = ['getDataDataHalamanById', 'getDataHalamanId'];

    public function mount()
    {
        $this->route_name = request()->route()->getName();
    }

    public function render()
    {
        $this->slug = Str::slug($this->judul);
        return view('livewire.pages.data-halaman', [
            'category' => Category::all()
        ])->layout(config('crud-generator.layout'));
    }

    public function store()
    {
        $this->_validate();

        $data = [
            'category_id'  => $this->category_id,
            'judul'  => $this->judul,
            'slug'  => $this->slug,
            'isi'  => $this->isi
        ];

        if ($this->banner_path) {
            $banner = $this->banner_path->store('upload', 'public');
            $data = ['banner' => $banner];
        }

        DataHalaman::create($data);

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Disimpan']);
    }

    public function update()
    {
        $this->_validate();

        $data = [
            'category_id'  => $this->category_id,
            'judul'  => $this->judul,
            'slug'  => $this->slug,
            'isi'  => $this->isi
        ];
        $row = DataHalaman::find($this->data_halaman_id);


        if ($this->banner_path) {
            $banner = $this->banner_path->store('upload', 'public');
            $data = ['banner' => $banner];
            if (Storage::exists('public/' . $this->banner)) {
                Storage::delete('public/' . $this->banner);
            }
        }

        $row->update($data);

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Diupdate']);
    }

    public function delete()
    {
        DataHalaman::find($this->data_halaman_id)->delete();

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Dihapus']);
    }

    public function _validate()
    {
        $rule = [
            'category_id'  => 'required',
            'judul'  => 'required',
            'slug'  => 'required',
            'isi'  => 'required'
        ];

        // if (!$this->update_mode) {
        //     $rule['banner_path'] = 'required|image';
        // }

        return $this->validate($rule);
    }

    public function getDataDataHalamanById($data_halaman_id)
    {
        $this->_reset();
        $row = DataHalaman::find($data_halaman_id);
        $this->data_halaman_id = $row->id;
        $this->category_id = $row->category_id;
        $this->judul = $row->judul;
        $this->slug = $row->slug;
        $this->banner = $row->banner;
        $this->isi = $row->isi;
        if ($this->form) {
            $this->form_active = true;
            $this->emit('loadForm');
        }
        if ($this->modal) {
            $this->emit('showModal');
        }
        $this->update_mode = true;
    }

    public function getDataHalamanId($data_halaman_id)
    {
        $row = DataHalaman::find($data_halaman_id);
        $this->data_halaman_id = $row->id;
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
        $this->data_halaman_id = null;
        $this->category_id = null;
        $this->judul = null;
        $this->slug = null;
        $this->banner_path = null;
        $this->isi = null;
        $this->form = true;
        $this->form_active = false;
        $this->update_mode = false;
        $this->modal = false;
    }
}
