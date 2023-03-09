<?php

namespace App\Http\Livewire\Master;

use App\Models\Category;
use App\Models\Layout;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Str;

class CategoryController extends Component
{
    use WithFileUploads;
    public $categorie_id;
    public $name;
    public $slug;
    public $layout_id;
    public $image;
    public $image_path;


    public $route_name = null;

    public $form_active = false;
    public $form = false;
    public $update_mode = false;
    public $modal = true;

    protected $listeners = ['getDataCategoryById', 'getCategoryId'];

    public function mount()
    {
        $this->route_name = request()->route()->getName();
    }

    public function render()
    {
        $this->slug = Str::slug($this->name);
        return view('livewire.master.categorie', [
            'layouts' => Layout::orderBy('name', 'ASC')->get(),
        ])->layout(config('crud-generator.layout'));
    }

    public function store()
    {
        $this->_validate();
        $uid = hash('crc32', Carbon::now()->format('U'));
        $data = [
            'name'  => $this->name,
            'layout_id'  => $this->layout_id,
            'slug'  => $this->slug . '-' . $uid,
        ];

        if ($this->image_path) {
            $image = $this->image_path->store('upload', 'public');
            $data = ['image' => $image];
        }

        Category::create($data);

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Disimpan']);
    }

    public function update()
    {
        $this->_validate();

        $data = [
            'name'  => $this->name,
            'slug'  => $this->slug,
            'layout_id'  => $this->layout_id,
        ];
        $row = Category::find($this->categorie_id);


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
        Category::find($this->categorie_id)->delete();

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Dihapus']);
    }

    public function _validate()
    {
        $rule = [
            'name'  => 'required',
            'slug'  => 'required',
            'layout_id'  => 'required',
        ];

        return $this->validate($rule);
    }

    public function getDataCategoryById($categorie_id)
    {
        $this->_reset();
        $row = Category::find($categorie_id);
        $this->categorie_id = $row->id;
        $this->name = $row->name;
        $this->slug = $row->slug;
        $this->layout_id = $row->layout_id;
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

    public function getCategoryId($categorie_id)
    {
        $row = Category::find($categorie_id);
        $this->categorie_id = $row->id;
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
        $this->categorie_id = null;
        $this->name = null;
        $this->slug = null;
        $this->layout_id = null;
        $this->image_path = null;
        $this->form = false;
        $this->form_active = false;
        $this->update_mode = false;
        $this->modal = true;
    }
}
