@extends('global.body')

@section('content')
    <x-hero-banner class="l-hero text-bg-primary d-flex align-items-center justify-content-center">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="mb-4">
                    <img src="{{ $asset->path('images/logo-cw-h.svg') }}" alt="Windwalker logo"
                        style="height: 75px">
                </h1>
                <p class="fs-3">
                    The Next Generation PHP Framework
                </p>

                <ul class="fs-6">
                    <li>
                        Modern <strong>PHP 8.x</strong> environment.
                    </li>
                    <li>
                        <strong>31</strong> useful standalone components.
                    </li>
                    <li>
                        Powerful <strong>DI</strong> engine.
                    </li>
                    <li>
                        <strong>Domain-driven</strong> file structure.
                    </li>
                    <li>
                        Built-in <strong>ViewModel</strong> and <strong>ORM</strong>.
                    </li>
                    <li>
                        Better <strong>IDE</strong> friendly supports.
                    </li>
                </ul>

                <p class="mt-5">
                    <a class="btn btn-light btn-lg"
                        href="{{ $uri->path('documentation') }}">
                        <i class="fa-solid fa-file-lines"></i>
                        4.x Documentation
                    </a>
                    <a class="btn btn-dark btn-lg" target="_blank"
                        href="https://github.com/windwalker-io/framework">
                        <i class="fa-brands fa-github"></i>
                        GitHub
                    </a>
                </p>
            </div>

            <div class="col-lg-6">
                <div>
                    <div class="browser-window">
                        <div class="top-bar">
                            <div class="circles gap-2">
                                <div class="circle circle--red"></div>
                                <div class="circle circle--yellow"></div>
                                <div class="circle circle--green"></div>
                            </div>
                        </div>
                        <div class="content">
                            <pre><code class="language-php">#[ViewModel(
    layout: 'article-list',
    js: 'article-list.js'
)]
class ArticleListView implements ViewModelInterface
{
    public function __construct(
        #[Autowire]
        protected ArticleRepository $repository,
    ) {}

    public function prepare(AppContext $app, View $view): array
    {
        [$id, $page] = $app->input('id', 'page')->values()->dump();

        $items = $this->repository->getListSelector()
            ->where('category_id', $id)
            ->page($page);

        return compact('items');
    }
}</code></pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-hero-banner>

    <div class="container">
        <!-- Example row of columns -->
        <div class="row my-4">
            <div class="col-md-4">
                <h2>Heading</h2>
                <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
                <p><a class="btn btn-secondary" href="{{ $uri->path() }}article/article.html"
                        role="button">View details &raquo;</a></p>
            </div>
            <div class="col-md-4">
                <h2>Heading</h2>
                <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
                <p><a class="btn btn-secondary" href="{{ $uri->path() }}article/article.html"
                        role="button">View details &raquo;</a></p>
            </div>
            <div class="col-md-4">
                <h2>Heading</h2>
                <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
                <p><a class="btn btn-secondary" href="{{ $uri->path() }}article/article.html"
                        role="button">View details &raquo;</a></p>
            </div>
        </div>
    </div> <!-- /container -->
@stop
