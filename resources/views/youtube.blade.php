<div class="video-services"
    data-width="{{ $width ?? '560' }}"
    data-height="{{ $height ?? '315' }}"
    data-url="https://www.youtube-nocookie.com/embed/{{ $video->id }}?autoplay=1"
>
    <div class="video" style="background-image: url('{{ route('video-services.thumbnail', ['service' => 'youtube', 'id' => $video->id, 'file' => 'maxresdefault.jpg']) }}')" id="">
        <div class="overlay">
            <div class="play" title="{!! trans('video-services::texts.google_privacy_text', ['link' => trans('video-services::texts.google_privacy_link_text')]) !!}"></div>

            @if(! isset($notext))
                <div class="describe">
                    <span class="play">{{ trans('video-services::texts.play_text') }}</span>
                    <div class="small">
                        <span>{!! trans('video-services::texts.google_privacy_text', ['link' => trans('video-services::texts.google_privacy_link', ['link_text' => trans('video-services::texts.google_privacy_link_text')])]) !!}</span>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <noscript>
        <a href="https://youtu.be/{{ $video->id }}"><img src="{{ route('video-services.thumbnail', ['service' => 'youtube', 'id' => $video->id, 'file' => 'maxresdefault.jpg']) }}" alt="{{ $video->title }}" /></a>
    </noscript>
</div>
