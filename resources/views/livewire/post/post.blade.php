<div class="page-inner">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title text-capitalize">
                        <a href="{{route('dashboard')}}">
                            <span><i class="fas fa-arrow-left mr-3"></i>tbl posts</span>
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
                    <x-text-field type="text" name="title" label="Title" />
                    <x-select name="category_id" label="Category">
                        <option value="">Select Category</option>
                        @foreach ($categories as $item)
                        <option value="{{$item->id}}">{{$item->name}}</option>
                        @endforeach
                    </x-select>
                    <x-text-field type="text" name="editor" label="Editor" placeholder="ex: aldo;yuda" />
                    <x-input-image foto="{{$image}}" path="{{optional($image_path)->temporaryUrl()}}" name="image_path" label="Image" />
                    <x-textarea name="caption" label="Caption" placeholder="Image caption" />
                    <x-select name="comment_status" label="Comment Status">
                        <option value="">Select Status</option>
                        <option value="open">open</option>
                        <option value="close">close</option>
                    </x-select>
                    <x-select name="status" label="Status">
                        <option value="">Select Status</option>
                        <option value="draft">draft</option>
                        <option value="publish">publish</option>
                    </x-select>
                    <x-select name="type" label="Type">
                        <option value="">Select Type</option>
                        <option value="post">Post</option>
                        <option value="story">Story</option>
                    </x-select>
                    <x-text-field type="text" name="tags" label="Tags" placeholder="pisahkan dengan tanda koma (,). ex: news,sport" />
                    <div wire:ignore class="form-group @error('content')has-error has-feedback @enderror">
                        <label for="content" class="text-capitalize">Content</label>
                        <textarea wire:model="content" id="content" class="form-control"></textarea>
                        @error('content')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    {{-- <x-select name="publish_status" label="Publish Status">
                        <option value="">Select Status</option>
                        <option value="rejected">rejected</option>
                        <option value="published">published</option>
                        <option value="waiting">waiting</option>
                    </x-select> --}}



                    <div class="form-group">
                        <button class="btn btn-primary pull-right" wire:click="{{$update_mode ? 'update' : 'store'}}">Simpan</button>
                    </div>
                </div>
            </div>
            @else
            <livewire:table.post-table params="{{$route_name}}" />
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


        {{-- Modal reject --}}
        <div id="reject-modal" wire:ignore.self class="modal fade" tabindex="-1" permission="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
            <div class="modal-dialog" permission="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="my-modal-title">Konfirmasi Reject Post</h5>
                    </div>
                    <div class="modal-body">
                        <x-textarea name="reject_reason" label="Caption" placeholder="Image caption" />
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger btn-sm" wire:click="$emit('showModalReject','hide')"><i class="fa fa-times pr-2"></i>Batal</a>
                            <button type="submit" wire:click="handleReject" class="btn btn-primary btn-sm"><i class="fa fa-check pr-2"></i>Ya, Tolak</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal detail --}}
        <div id="detail-modal" wire:ignore.self class="modal fade" tabindex="-1" permission="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
            <div class="modal-dialog  modal-lg" permission="document">
                @if ($post)
                <div class="modal-content modal-lg">
                    <div class="modal-header">
                        <h5 class="modal-title" id="my-modal-title">Detail Post Preview</h5>
                    </div>
                    <div class="modal-body">
                        <div class="mb-2">
                            <strong>Judul:</strong>
                            <p>{{$post->title}}</p>
                        </div>
                        <div class="mb-2">
                            <strong>Kategori:</strong>
                            <p>{{$post->category_name}}</p>
                        </div>
                        <div class="mb-2">
                            <strong>Author:</strong>
                            <p>{{$post->author_name}}</p>
                        </div>
                        <div class="mb-2">
                            <strong>Tanggal:</strong>
                            <p>{{$post->created_at}}</p>
                        </div>
                        <div class="mb-2">
                            <strong>Type:</strong>
                            <p>{{$post->type}}</p>
                        </div>
                        <div class="mb-2">
                            <strong>Status:</strong>
                            <p>{{$post->status}}</p>
                        </div>
                        <div class="mb-2">
                            <strong>Publish Status:</strong>
                            <p>{{$post->publish_status}}</p>
                        </div>
                        <div class="mb-2">
                            <strong>Comment Status:</strong>
                            <p>{{$post->comment_status}}</p>
                        </div>
                        <div class="mb-2">
                            <strong>Image:</strong>
                            <p><a href="{{$post->image_path}}" target="_blank" class="btn btn-link">Lihat Gambar</a></p>
                        </div>
                        <div class="mb-2">
                            <strong>Caption:</strong>
                            <p>{{$post->caption}}</p>
                        </div>
                        <div class="mb-2">
                            <strong>Editor:</strong>
                            <p>{{$post->editor}}</p>
                        </div>
                        <div class="mb-2">
                            <strong>Tag:</strong>
                            <p>{{$post->tags}}</p>
                        </div>
                        <div class="mb-2">
                            <strong>Approved Admin:</strong>
                            <p>{{$post->approved_user_name}}</p>
                        </div>
                        <div class="mb-2">
                            <strong>Rejected Admin:</strong>
                            <p>{{$post->rejected_user_name}}</p>
                        </div>
                        <div class="mb-2">
                            <strong>Alasan Reject:</strong>
                            <p>{{$post->reject_reason}}</p>
                        </div>
                        <div class="mb-2">
                            <strong>Isi:</strong>
                            <p><button wire:click="$emit('showModalContent','show')" class="btn btn-link">Lihat detail</button></p>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-danger btn-sm" wire:click="$emit('showModalDetail','hide')"><i class="fa fa-times pr-2"></i>Tutup</a>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Modal content --}}
        <div id="content-modal" wire:ignore.self class="modal fade" tabindex="-1" permission="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
            <div class="modal-dialog modal-lg" permission="document">
                @if ($post)
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="my-modal-title">{{$post->title}}</h5>
                    </div>
                    <div class="modal-body">
                        {!! $post->content !!}
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger btn-sm" wire:click="$emit('showModalContent','hide')"><i class="fa fa-times pr-2"></i>Tutup</a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    @push('scripts')
    <script src="{{asset('assets/js/plugin/summernote/summernote-bs4.min.js')}}"></script>


    <script>
        document.addEventListener('livewire:load', function(e) {
            window.livewire.on('loadForm', (data) => {
                $('#content').summernote({
            placeholder: 'content',
            fontNames: ['Arial', 'Arial Black', 'Comic Sans MS', 'Courier New'],
            tabsize: 2,
            height: 300,
            callbacks: {
                        onChange: function(contents, $editable) {
                            @this.set('content', contents);
                        }
                    }
            });
                
            });

            window.livewire.on('closeModal', (data) => {
                $('#confirm-modal').modal('hide')
                $('#reject-modal').modal('hide')
                $('#detail-modal').modal('hide')
                $('#content-modal').modal('hide')
            });

            window.livewire.on('showModalConfirm', (data) => {
                $('#confirm-modal').modal(data)
            });

            window.livewire.on('showModalReject', (data) => {
                $('#reject-modal').modal(data)
            });

            window.livewire.on('showModalDetail', (data) => {
                $('#detail-modal').modal(data)
            });

            window.livewire.on('showModalContent', (data) => {
                if (data == 'show') {
                    $('#content-modal').modal('show')
                    $('#detail-modal').modal('hide')
                }

                if (data == 'hide') {
                    $('#content-modal').modal('hide')
                    $('#detail-modal').modal('show')
                }
            });
        })
    </script>
    @endpush
</div>