<?php

namespace App\Http\Livewire\Master;

use App\Models\JenisIklan;
use Livewire\Component;


class JenisIklanController extends Component
{

    public $jenis_iklan_id;
    public $nama_jenis_iklan;
    public $kode_jenis_iklan;
    public $description;



    public $route_name = null;

    public $form_active = false;
    public $form = false;
    public $update_mode = false;
    public $modal = true;

    protected $listeners = ['getDataJenisIklanById', 'getJenisIklanId'];

    public function mount()
    {
        $this->route_name = request()->route()->getName();
    }

    public function render()
    {
        return view('livewire.master.jenis-iklan')->layout(config('crud-generator.layout'));
    }

    public function store()
    {
        $this->_validate();

        $data = [
            'nama_jenis_iklan'  => $this->nama_jenis_iklan,
            'kode_jenis_iklan'  => $this->kode_jenis_iklan,
            'description'  => $this->description
        ];

        JenisIklan::create($data);

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Disimpan']);
    }

    public function update()
    {
        $this->_validate();

        $data = [
            'nama_jenis_iklan'  => $this->nama_jenis_iklan,
            'kode_jenis_iklan'  => $this->kode_jenis_iklan,
            'description'  => $this->description
        ];
        $row = JenisIklan::find($this->jenis_iklan_id);



        $row->update($data);

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Diupdate']);
    }

    public function delete()
    {
        JenisIklan::find($this->jenis_iklan_id)->delete();

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Dihapus']);
    }

    public function _validate()
    {
        $rule = [
            'nama_jenis_iklan'  => 'required',
            'kode_jenis_iklan'  => 'required',
            'description'  => 'required'
        ];

        return $this->validate($rule);
    }

    public function getDataJenisIklanById($jenis_iklan_id)
    {
        $this->_reset();
        $row = JenisIklan::find($jenis_iklan_id);
        $this->jenis_iklan_id = $row->id;
        $this->nama_jenis_iklan = $row->nama_jenis_iklan;
        $this->kode_jenis_iklan = $row->kode_jenis_iklan;
        $this->description = $row->description;
        if ($this->form) {
            $this->form_active = true;
            $this->emit('loadForm');
        }
        if ($this->modal) {
            $this->emit('showModal');
        }
        $this->update_mode = true;
    }

    public function getJenisIklanId($jenis_iklan_id)
    {
        $row = JenisIklan::find($jenis_iklan_id);
        $this->jenis_iklan_id = $row->id;
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
        $this->jenis_iklan_id = null;
        $this->nama_jenis_iklan = null;
        $this->kode_jenis_iklan = null;
        $this->description = null;
        $this->form = false;
        $this->form_active = false;
        $this->update_mode = false;
        $this->modal = true;
    }
}
