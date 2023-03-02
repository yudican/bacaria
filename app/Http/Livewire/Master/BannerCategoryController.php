<?php

namespace App\Http\Livewire\Master;

use App\Models\BannerCategory;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class BannerCategoryController extends Component
{
    use WithFileUploads;
    public $banner_categorie_id;
    public $category_id;
    public $image;
    public $link;
    public $placement;
    public $image_path;

    public $route_name = null;

    public $form_active = false;
    public $form = false;
    public $update_mode = false;
    public $modal = true;

    protected $listeners = ['getDataBannerCategoryById', 'getBannerCategoryId'];

    public function mount()
    {
        $this->route_name = request()->route()->getName();
    }

    public function render()
    {
        return view('livewire.master.banner-categorie', [
            'categories' => Category::all()
        ])->layout(config('crud-generator.layout'));
    }

    public function store()
    {
        $this->_validate();
        $data = [
            'category_id'  => intval($this->category_id),
            'link'  => $this->link,
            'placement'  => $this->placement
        ];
        if ($this->image_path) {
            $image = $this->image_path->store('upload', 'public');
            $data['image'] = $image;
        }

        BannerCategory::create($data);

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Disimpan']);
    }

    public function update()
    {
        $this->_validate();

        $data = [
            'category_id'  => $this->category_id,
            'link'  => $this->link,
            'placement'  => $this->placement
        ];
        $row = BannerCategory::find($this->banner_categorie_id);


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
        BannerCategory::find($this->banner_categorie_id)->delete();

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Dihapus']);
    }

    public function _validate()
    {
        $rule = [
            'category_id'  => 'required',
            'link'  => 'required',
            'placement'  => 'required'
        ];

        // required if create mode
        if (!$this->update_mode) {
            $rule['image_path'] = 'required|image';
        }

        return $this->validate($rule);
    }

    public function getDataBannerCategoryById($banner_categorie_id)
    {
        $this->_reset();
        $row = BannerCategory::find($banner_categorie_id);
        $this->banner_categorie_id = $row->id;
        $this->category_id = $row->category_id;
        $this->image = $row->image;
        $this->link = $row->link;
        $this->placement = $row->placement;
        if ($this->form) {
            $this->form_active = true;
            $this->emit('loadForm');
        }
        if ($this->modal) {
            $this->emit('showModal');
        }
        $this->update_mode = true;
    }

    public function getBannerCategoryId($banner_categorie_id)
    {
        $row = BannerCategory::find($banner_categorie_id);
        $this->banner_categorie_id = $row->id;
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
        $this->banner_categorie_id = null;
        $this->category_id = null;
        $this->image = null;
        $this->image_path = null;
        $this->link = null;
        $this->placement = null;
        $this->form = false;
        $this->form_active = false;
        $this->update_mode = false;
        $this->modal = true;
    }
}
