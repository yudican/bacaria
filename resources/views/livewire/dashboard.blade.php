<div class="page-inner">
    <div class="row">
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body ">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-primary bubble-shadow-small">
                                <i class="flaticon-interface-6"></i>
                            </div>
                        </div>
                        <div class="col col-stats ml-3 ml-sm-0">
                            <div class="numbers">
                                <p class="card-category">Post</p>
                                <h4 class="card-title">{{$total_post}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-info bubble-shadow-small">
                                <i class="flaticon-users"></i>
                            </div>
                        </div>
                        <div class="col col-stats ml-3 ml-sm-0">
                            <div class="numbers">
                                <p class="card-category">Editor</p>
                                <h4 class="card-title">{{$total_editor}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-success bubble-shadow-small">
                                <i class="flaticon-users"></i>
                            </div>
                        </div>
                        <div class="col col-stats ml-3 ml-sm-0">
                            <div class="numbers">
                                <p class="card-category">Reporter</p>
                                <h4 class="card-title">{{$total_reporter}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-secondary bubble-shadow-small">
                                <i class="flaticon-users"></i>
                            </div>
                        </div>
                        <div class="col col-stats ml-3 ml-sm-0">
                            <div class="numbers">
                                <p class="card-category">Member</p>
                                <h4 class="card-title">{{$total_member}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h1 class="header-title">Kategori Terpopuler</h1>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach ($popular_categories as $key => $item)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>{{$key+1}}. {{$item->name}}</span>
                            <span class="badge badge-primary badge-pill">{{$item->posts_count}}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h1 class="header-title">Berita terbaru</h1>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach ($post_new as $key => $item)
                        <li class="list-group-item">
                            <span>{{$key+1}}. {{$item->title}}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h1 class="header-title">Daftar Pengunjung Terbaru</h1>
                </div>
                <div class="card-body ">
                    {{-- create table visitor --}}
                    <div class=" table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="text-bold" width="5%">No</th>
                                    <th class="font-bold">IP Address</th>
                                    <th class="text-bold">Kota</th>
                                    <th class="text-bold">Provinsi</th>
                                    <th class="text-bold">Negara</th>
                                    <th class="text-bold">Platform</th>
                                    <th class="text-bold">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($visitors as $key => $item)
                                <tr>
                                    <th>{{$key+1}}</th>
                                    <td>{{$item->ip_address}}</td>
                                    <td>{{$item->city}}</td>
                                    <td>{{$item->region}}</td>
                                    <td>{{$item->country}}</td>
                                    <td>{{$item->device}}/{{$item->user_agent}}</td>
                                    <td>{{$item->created_at}}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada data</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h1 class="header-title">Member Terbaru</h1>
                </div>
                <div class="card-body table-responsive">
                    {{-- create table visitor --}}
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-bold" width="5%">No</th>
                                <th class="text-bold">Nama</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($new_members as $key => $item)
                            <tr>
                                <th scope="row">{{$key+1}}</th>
                                <td>{{$item->name}}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center">Tidak ada data</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>