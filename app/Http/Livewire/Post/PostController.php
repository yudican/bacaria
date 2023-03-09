<?php

namespace App\Http\Livewire\Post;

use App\Models\Category;
use App\Models\DataIklan;
use App\Models\Post;
use App\Models\PostTag;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Str;

class PostController extends Component
{
    use WithFileUploads;
    public $post_id;
    public $approved_user_id;
    public $author_id;
    public $category_id;
    public $content;
    public $editor;
    public $image;
    public $image_path;
    public $caption;
    public $rejected_user_id;
    public $slug;
    public $status;
    public $comment_status;
    public $publish_status;
    public $reject_reason;
    public $title;
    public $type;
    public $uid_post;
    public $tags;

    public $post;


    public $route_name = null;

    public $form_active = false;
    public $form = true;
    public $update_mode = false;
    public $modal = false;

    protected $listeners = ['getDataPostById', 'getPostId'];

    public function mount()
    {
        $this->route_name = request()->route()->getName();
    }

    public function render()
    {
        $this->slug = Str::slug($this->title);
        return view('livewire.post.post', [
            'categories' => Category::orderBy('created_at', 'ASC')->get(),
        ])->layout(config('crud-generator.layout'));
    }

    public function store()
    {
        $this->_validate();
        try {
            DB::beginTransaction();
            $uid = hash('crc32', Carbon::now()->format('U'));
            $data = [
                'author_id'  => auth()->user()->id,
                'category_id'  => $this->category_id,
                'content'  => $this->content,
                'editor'  => $this->editor,
                'slug'  => $this->slug . '-' . $uid,
                'status'  => $this->status,
                'comment_status'  => $this->comment_status,
                'title'  => $this->title,
                'caption'  => $this->caption,
                'type'  => $this->type,
                'uid_post'  => $uid
            ];

            if ($this->image_path) {
                $image = $this->image_path->store('upload/post', 'public');
                $data['image'] = $image;
            }

            $post = Post::create($data);
            $tags = explode(',', $this->tags);
            $tagsLists = [];
            foreach ($tags as $key => $value) {
                $tagsLists[] = [
                    'post_id' => $post->id,
                    'name' => $value,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }

            PostTag::insert($tagsLists);
            DB::commit();
            $this->_reset();
            return $this->emit('showAlert', ['msg' => 'Data Berhasil Disimpan']);
        } catch (\Throwable $th) {
            DB::rollback();
            dd($th->getMessage());
            $this->_reset();
            return $this->emit('showAlertError', ['msg' => 'Data Gagal Disimpan']);
        }
    }

    public function update()
    {
        $this->_validate();

        try {
            DB::beginTransaction();
            $data = [
                'category_id'  => $this->category_id,
                'content'  => $this->content,
                'editor'  => $this->editor,
                'image'  => $this->image,
                'slug'  => $this->slug,
                'status'  => $this->status,
                'caption'  => $this->caption,
                'comment_status'  => $this->comment_status,
                'title'  => $this->title,
                'type'  => $this->type,
            ];
            $row = Post::find($this->post_id);

            if ($this->image_path) {
                $image = $this->image_path->store('upload/post', 'public');
                $data = ['image' => $image];
                if (Storage::exists('public/' . $this->image)) {
                    Storage::delete('public/' . $this->image);
                }
            }

            $row->update($data);
            $row->postTags()->delete();
            $tags = explode(',', $this->tags);
            $tagsLists = [];
            foreach ($tags as $key => $value) {
                $tagsLists[] = [
                    'post_id' => $row->id,
                    'name' => $value,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }

            PostTag::insert($tagsLists);
            DB::commit();
            $this->_reset();
            return $this->emit('showAlert', ['msg' => 'Data Berhasil Diupdate']);
        } catch (\Throwable $th) {
            DB::rollback();

            $this->_reset();
            return $this->emit('showAlertError', ['msg' => 'Data Gagal Diupdate']);
        }
    }

    public function delete()
    {
        Post::find($this->post_id)->delete();

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Dihapus']);
    }

    public function _validate()
    {
        $rule = [
            'category_id'  => 'required',
            'content'  => 'required',
            'editor'  => 'required',
            'slug'  => 'required',
            'status'  => 'required',
            'caption'  => 'required',
            'title'  => 'required',
            'type'  => 'required',
            'comment_status'  => 'required',
        ];

        return $this->validate($rule);
    }

    public function getDataPostById($post_id)
    {
        $this->_reset();
        $row = Post::find($post_id);
        $this->post_id = $row->id;
        $this->approved_user_id = $row->approved_user_id;
        $this->author_id = $row->author_id;
        $this->category_id = $row->category_id;
        $this->content = $row->content;
        $this->editor = $row->editor;
        $this->image = $row->image;
        $this->rejected_user_id = $row->rejected_user_id;
        $this->slug = $row->slug;
        $this->status = $row->status;
        $this->caption = $row->caption;
        $this->comment_status = $row->comment_status;
        $this->publish_status = $row->publish_status;
        $this->reject_reason = $row->reject_reason;
        $this->title = $row->title;
        $this->type = $row->type;
        $this->tags = $row->postTags()->implode('name', ',');
        $this->uid_post = $row->uid_post;
        if ($this->form) {
            $this->form_active = true;
            $this->emit('loadForm');
        }
        if ($this->modal) {
            $this->emit('showModal');
        }
        $this->update_mode = true;
    }

    public function getPostId($post_id)
    {
        $row = Post::find($post_id);
        $this->post_id = $row->id;
        $this->post = $row;
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

    public function handleReject()
    {
        $this->validate([
            'reject_reason' => 'required'
        ]);


        $row = Post::find($this->post_id);
        $row->update([
            'publish_status' => 'rejected',
            'reject_reason' => $this->reject_reason,
            'rejected_user_id' => Auth::user()->id
        ]);

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Ditolak']);
    }

    public function _reset()
    {
        $this->emit('closeModal');
        $this->emit('refreshTable');

        $this->post_id = null;
        $this->approved_user_id = null;
        $this->author_id = null;
        $this->category_id = null;
        $this->content = null;
        $this->editor = null;
        $this->image_path = null;
        $this->image = null;
        $this->rejected_user_id = null;
        $this->slug = null;
        $this->caption = null;
        $this->status = null;
        $this->comment_status = null;
        $this->publish_status = null;
        $this->reject_reason = null;
        $this->title = null;
        $this->type = null;
        $this->tags = null;
        $this->uid_post = null;
        $this->form = true;
        $this->form_active = false;
        $this->update_mode = false;
        $this->modal = false;
    }
}
