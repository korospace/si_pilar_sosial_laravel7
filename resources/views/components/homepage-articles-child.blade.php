
@foreach($articles as $row)
<div class="col-md-6 col-xl-3 mb-5">
    <div class="card h-100 shadow" style="overflow: hidden;border-radius: 10px 10px 10px 10px">
        <div style="background-image:url('{{ $row->public_thumbnail }}');background-position: center;background-size: cover;overflow: hidden;border-radius: 0 0 10px 10px;">
            <img src="{{ asset('images/default-thumbnail.webp') }}" alt="" class="w-100" style="opacity: 0;">
        </div>
        <a href='{{ route('homepage.articles.detail', $row->slug) }}' class="card_title_news">
            <p>{{ $row->title }}</p>
        </a>
        <div class='hr_wraper'>
            <hr class='overlay' />
        </div>
        <div class='creator_releasedate'>
            <p>
                <i class="fa fa-user pr-1"></i>
                {{ $row->creator->name }}
            </p>
            <p>
                <i class="fa fa-calendar pr-1"></i>
                {{ $row->public_release_date }}
            </p>
        </div>
        <div class="card_excerpt_news">
            <p>{{ $row->excerpt }}</p>
        </div>
        <a href='{{ route('homepage.articles.detail', $row->slug) }}' class='detail_link'>selengkapnya...</a>
    </div>
</div>
@endforeach

{!! $articles->links('components.homepage-articles-pagination') !!}
