@extends('global.body')

@push('script')
    <script src="{{ $asset->path('js/home.js') }}"></script>
@endpush

@section('content')
    <x-hero-banner class="l-hero text-bg-primary d-flex align-items-center justify-content-center">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0">
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

        <div class="c-banner-arrow">
            <i class="fa-solid fa-angles-down fa-2x"></i>
        </div>
    </x-hero-banner>

    <main class="">

        <section class="container l-section l-section--features my-7">
            <h2 class="l-section__title mb-6 fw-bold text-center">FEATURES</h2>

            <!-- Feature Row -->
            <div class="row">
                <!-- Feature -->
                <div class="feature-item col-md-4 text-center">
                    <div class="feature-icon">
                        <i class="fa-solid fa-face-grin-wide fa-4x" style="color: var(--bs-teal)"></i>
                    </div>
                    <h3 class="feature-title mt-4 mt-4">
                        Easy but Powerful
                    </h3>
                    <p class="text-secondary">
                        Learning a new framework is hard,
                        we provide simple and semantic interface to help developers understand this framework.
                    </p>
                </div>
                <!-- Feature -->
                <div class="feature-item col-md-4 text-center">
                    <div class="feature-icon">
                        <i class="fa-solid fa-layer-group fa-4x" style="color: var(--bs-purple)"></i>
                    </div>
                    <h3 class="feature-title mt-4">
                        Fully Decoupled
                    </h3>
                    <p class="text-secondary">
                        Windwalker is a set of PHP tools, you can easily install them by composer without too many dependencies.
                    </p>
                </div>
                <!-- Feature -->
                <div class="feature-item col-md-4 text-center">
                    <div class="feature-icon">
                        <i class="fa-solid fa-expand fa-4x" style="color: var(--bs-orange)"></i>
                    </div>
                    <h3 class="feature-title mt-4">
                        Extendable
                    </h3>
                    <p class="text-secondary">
                        The package system helps us organize our classes and routing to build large enterprise level applications.
                    </p>
                </div>
            </div>

            <!-- Feature Row -->
            <div class="row mt-5">
                <!-- Feature -->
                <div class="feature-item col-md-4 text-center">
                    <div class="feature-icon">
                        <i class="fa-solid fa-arrows-to-circle fa-4x" style="color: var(--bs-danger)"></i>
                    </div>
                    <h3 class="feature-title mt-4">
                        Standard
                    </h3>
                    <p class="text-secondary">
                        We follow PSR standard, you can easily integrate 3rd party middlewares or caching library into Windwalker.
                    </p>
                </div>
                <!-- Feature -->
                <div class="feature-item col-md-4 text-center">
                    <div class="feature-icon">
                        <i class="fa-solid fa-hat-wizard fa-4x" style="color: var(--bs-indigo)"></i>
                    </div>
                    <h3 class="feature-title mt-4">
                        Rapid Development
                    </h3>
                    <p class="text-secondary">
                        Windwalker is a RAD framework, building a usable system prototype with powerful UI is very fast.
                    </p>
                </div>
                <!-- Feature -->
                <div class="feature-item col-md-4 text-center">
                    <div class="feature-icon">
                        <i class="fa-solid fa-laptop-code fa-4x" style="color: var(--bs-info)"></i>
                    </div>
                    <h3 class="feature-title mt-4">
                        IDE friendly
                    </h3>
                    <p class="text-secondary">
                        Class searching, auto-completion and many useful IDE functions are working well with IoC.
                    </p>
                </div>
            </div>

        </section>

        <section class="container l-section l-section--features my-7">
            <h2 class="l-section__title mb-4 fw-bold text-center">GETTING STARTED</h2>

            <div class="mt-4 row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <pre><code class="language-bash">$ composer create-project windwalker/starter</code></pre>
                </div>
            </div>

            <div class="mt-4 text-center">
                <a class="btn btn-primary btn-lg"
                    href="{{ $uri->path('documentation') }}">
                    <i class="fa-solid fa-file-lines"></i>
                    Documentation
                </a>
            </div>
        </section>

    </main> <!-- /container -->
@stop
