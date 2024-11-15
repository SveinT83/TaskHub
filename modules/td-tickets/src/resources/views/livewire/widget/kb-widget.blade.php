<div class="card mt-3" wire:init="searchArticles">
    <div class="card-header">
        <p>KB Articles <i>(Demo)</i></p>
    </div>
    <div class="card-body">
        @if (count($articles) === 0)
            <p>Searching for matching articles...</p>
        @else
            <div class="accordion" id="accordionKbWidget">
                @foreach ($articles as $index => $article)
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button {{ $index === 0 ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="collapse{{ $index }}">
                                {{ $article->title }}
                            </button>
                        </h2>
                        <div id="collapse{{ $index }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" data-bs-parent="#accordionKbWidget">
                            <div class="accordion-body">
                                {!!$article->content!!}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
