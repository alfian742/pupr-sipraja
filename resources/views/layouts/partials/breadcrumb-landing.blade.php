<nav aria-label="breadcrumb" class="bg-light">
    <div class="container px-4 py-5">
        <div class="d-flex justify-content-center align-items-center mb-2">
            <ol class="breadcrumb">
                {!! generateLandingBreadcrumb($title ?? null) !!}
            </ol>
        </div>

        <h1 class="display-5 fw-bold mb-2 text-center">
            {{ isset($title) ? $title : '' }}
        </h1>
    </div>
</nav>
