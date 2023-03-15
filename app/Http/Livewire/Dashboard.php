<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use App\Models\Visitor;
use Livewire\Component;
use Google_Client;
use Google_Service_Analytics;

class Dashboard extends Component
{
    public $popular_categories = [];
    public $post_new = [];
    public $visitors = [];
    public $new_members = [];

    public $total_post = 0;
    public $total_editor = 0;
    public $total_reporter = 0;
    public $total_member = 0;
    public function mount()
    {
        $this->popular_categories = Category::withCount('posts')->orderBy('posts_count', 'DESC')->take(5)->get();
        $this->post_new = Post::orderBy('created_at', 'DESC')->take(5)->get();
        $this->total_post = Post::count();
        $this->total_editor = User::whereHas('roles', function ($query) {
            $query->where('role_type', 'editor');
        })->count();
        $this->total_reporter = User::whereHas('roles', function ($query) {
            $query->where('role_type', 'reporter');
        })->count();
        $this->total_member = User::whereHas('roles', function ($query) {
            $query->where('role_type', 'member');
        })->count();

        $this->visitors = Visitor::orderBy('created_at', 'DESC')->take(5)->get();
        $this->new_members = User::whereHas('roles', function ($query) {
            $query->where('role_type', 'member');
        })->orderBy('created_at', 'DESC')->take(5)->get();
    }
    public function render()
    {
        return view('livewire.dashboard');
    }
}
