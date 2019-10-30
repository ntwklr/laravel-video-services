<div class="video-services"
    data-width="{{ $width or '560' }}"
    data-height="{{ $height or '315' }}"
    data-url="https://www.youtube-nocookie.com/embed/{{ $video->id }}"
>
    <div class="video" style="background-image: url('{{ route('video-services.thumbnail', ['service' => 'youtube', 'id' => $video->id, 'file' => 'maxresdefault.jpg']) }}')" id="">
        <div class="overlay">
            @if(! isset($notext))
                <div class="play">
                    <span>{{ trans('video-services::texts.play_text') }}</span>
                    <div class="small">
                        <span>{!! trans('video-services::texts.google_privacy_text', ['link' => trans('video-services::texts.google_privacy_link', ['link_text' => trans('video-services::texts.google_privacy_link_text')])]) !!}</span>
                    </div>
                </div>
            @else
                <div class="play" title="{!! trans('video-services::texts.google_privacy_text', ['link' => trans('video-services::texts.google_privacy_link_text')]) !!}"></div>
            @endif
        </div>
    </div>
    <noscript>
        <a href="https://youtu.be/{{ $video->id }}"><img src="{{ route('video-services.thumbnail', ['service' => 'youtube', 'id' => $video->id, 'file' => 'maxresdefault.jpg']) }}" alt="{{ $video->title }}" /></a>
    </noscript>
</div>
