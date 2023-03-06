<?php

namespace App\Http\Livewire\Master;

use App\Models\Layout;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Str;

class LayoutController extends Component
{
    use WithFileUploads;
    public $layout_id;
    public $name;
    public $slug;
    public $image;
    public $image_path;


    public $route_name = null;

    public $form_active = false;
    public $form = false;
    public $update_mode = false;
    public $modal = true;

    protected $listeners = ['getDataLayoutById', 'getLayoutId'];

    public function mount()
    {
        $this->route_name = request()->route()->getName();
    }

    public function render()
    {
        $this->slug = Str::slug($this->name);
        return view('livewire.master.layout')->layout(config('crud-generator.layout'));
    }

    public function store()
    {
        $this->_validate();

        $data = [
            'name'  => $this->name,
            'slug'  => $this->slug,
        ];

        if ($this->image_path) {
            $image = $this->image_path->store('upload', 'public');
            $data = ['image' => $image];
        }

        Layout::create($data);

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Disimpan']);
    }

    public function update()
    {
        $this->_validate();

        $data = [
            'name'  => $this->name,
            'slug'  => $this->slug,
        ];
        $row = Layout::find($this->layout_id);


        if ($this->image_path) {
            $image = $this->image_path->store('upload', 'public');
            $data = ['image' => $image];
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
        Layout::find($this->layout_id)->delete();

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Dihapus']);
    }

    public function _validate()
    {
        $rule = [
            'name'  => 'required',
            'slug'  => 'required'
        ];

        if (!$this->update_mode) {
            $rule['image_path'] = 'required|image';
        }

        return $this->validate($rule);
    }

    public function getDataLayoutById($layout_id)
    {
        $this->_reset();
        $row = Layout::find($layout_id);
        $this->layout_id = $row->id;
        $this->name = $row->name;
        $this->slug = $row->slug;
        $this->image = $row->image;
        if ($this->form) {
            $this->form_active = true;
            $this->emit('loadForm');
        }
        if ($this->modal) {
            $this->emit('showModal');
        }
        $this->update_mode = true;
    }

    public function getLayoutId($layout_id)
    {
        $row = Layout::find($layout_id);
        $this->layout_id = $row->id;
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
        $this->layout_id = null;
        $this->name = null;
        $this->slug = null;
        $this->image_path = null;
        $this->form = false;
        $this->form_active = false;
        $this->update_mode = false;
        $this->modal = true;
    }
}
