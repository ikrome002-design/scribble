<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 mb-3 text-center my-4">
    @foreach ($plans as $p)
        @php
            $key = $loop->index;
            $bg = [['bg-light', '', ''], ['bg-primary', 'text-white'], ['bg-secondary', 'text-white'], ['bg-orange', 'text-white'], ['bg-success', 'text-white']];
        @endphp
        <div class="col">
            <div class="card mb-4 rounded-3 shadow-sm h-100">
                <div class="card-header py-3 {{ $bg[$key][0] ?? '' }}">
                    @if ($p->popular == 'Yes')
                        <h4 class="my-0 font-weight-normal position-relative me-2 text-white"> <span
                                class="position-absolute top-0 start-100 translate-middle badge text-dark rounded-pill bg-warning">
                                Popular
                            </span>
                        </h4>
                    @endif
                    <h3 class="my-0 fw-normal {{ $bg[$key][1] ?? '' }}">{{ $p->name }}</h3>
                </div>
                <div class="card-body">

                    <h4 class="card-title pricing-card-title h1">{{ number_format($p->price, 0, '.', '') }}<small
                            class="text-muted fw-light">/mo</small></h4>
                    <ul class="list-group mb-5">

                        @foreach ($p->planFeatures as $f)
                            <li class="list-group-item py-2 m-0">{{ $f->feature }}</li>
                        @endforeach

                    </ul>
                    <a href="{{ url('/register') }}"><button type="submit"
                            class="btn rounded-pill  w-100 btn-lg border {{ $bg[$key][1] ?? 'text-white' }} {{ $bg[$key][0] ?? 'bg-success' }}"
                            name="sub"><i class="fa fa-signing"></i>
                            {{ $p->price > 0 ? 'Sign up Now' : 'Sign Up For Free' }}</button></a>
                </div>
            </div>
        </div>
    @endforeach
</div>
