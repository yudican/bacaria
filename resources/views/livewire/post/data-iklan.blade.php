<div class="page-inner">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title text-capitalize">
                        <a href="{{route('dashboard')}}">
                            <span><i class="fas fa-arrow-left mr-3"></i>data iklans</span>
                        </a>
                        <div class="pull-right">
                            @if ($form_active)
                            <button class="btn btn-danger btn-sm" wire:click="toggleForm(false)"><i class="fas fa-times"></i> Cancel</button>
                            @else
                            @if (auth()->user()->hasTeamPermission($curteam, $route_name.':create'))
                            <button class="btn btn-primary btn-sm" wire:click="{{$modal ? 'showModal' : 'toggleForm(true)'}}"><i class="fas fa-plus"></i> Add
                                New</button>
                            @endif
                            @endif
                        </div>
                    </h4>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            @if ($form_active)
            <div class="card">
                <div class="card-body">
                    <x-select name="jenis_iklan_id" label="Jenis Iklan">
                        <option value="">Pilih Jenis Iklan</option>
                        @foreach ($jenis_iklan as $item)
                        <option value="{{$item->id}}">{{$item->nama_jenis_iklan}}</option>
                        @endforeach
                    </x-select>
                    <x-text-field type="text" name="nama_iklan" label="Nama Iklan" />

                    @if ($kode_jenis_iklan == 'ads-feed')
                    <x-select name="category_id" label="Kategori">
                        <option value="">Pilih Kategori</option>
                        @foreach ($categories as $category)
                        <option value="{{$category->id}}">{{$category->name}}</option>
                        @endforeach

                    </x-select>

                    @endif

                    @if (!in_array($kode_jenis_iklan, ['ads-feed', 'ads-popup','ads-content']))
                    <x-select name="kode_iklan" label="Kode Iklan">
                        <option value="">Pilih Kode Iklan</option>
                        <option value="SIDPOST">Sidebar Post</option>
                        <option value="SIDRIGHT">Sidebar Right </option>
                        <option value="SIDLEFT">Sidebar Left</option>
                        <option value="BANNER">Iklan Banner</option>
                        <option value="POPUP">Iklan Pop Up</option>
                    </x-select>
                    @endif

                    @if ($kode_jenis_iklan == 'google-ads')
                    <div>
                        <x-text-field type="text" name="ads_slot_id" label="Ads Slot Id" />
                        <x-text-field type="text" name="ads_client_id" label="Ads Client ID" />
                    </div>
                    @else
                    <div>
                        <x-input-image foto="{{$image}}" path="{{optional($image_path)->temporaryUrl()}}" name="image_path" label="Image" />
                        <x-text-field type="text" name="link" label="Link" />
                    </div>
                    @endif

                    <div class="form-group">
                        <button class="btn btn-primary pull-right" wire:click="{{$update_mode ? 'update' : 'store'}}">Simpan</button>
                    </div>
                </div>
            </div>
            @else
            <livewire:table.data-iklan-table params="{{$route_name}}" />
            @endif

        </div>

        {{-- Modal confirm --}}
        <div id="confirm-modal" wire:ignore.self class="modal fade" tabindex="-1" permission="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
            <div class="modal-dialog" permission="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="my-modal-title">Konfirmasi Hapus</h5>
                    </div>
                    <div class="modal-body">
                        <p>Apakah anda yakin hapus data ini.?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" wire:click='delete' class="btn btn-danger btn-sm"><i class="fa fa-check pr-2"></i>Ya, Hapus</button>
                        <button class="btn btn-primary btn-sm" wire:click='_reset'><i class="fa fa-times pr-2"></i>Batal</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')



    <script>
        document.addEventListener('livewire:load', function(e) {
            window.livewire.on('loadForm', (data) => {
                
                
            });

            window.livewire.on('closeModal', (data) => {
                $('#confirm-modal').modal('hide')
            });

            window.livewire.on('showModalConfirm', (data) => {
                $('#confirm-modal').modal(data)
            });
        })
    </script>
    @endpush
</div>