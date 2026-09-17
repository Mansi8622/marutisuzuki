@extends('admin.messenger.template')

@section('title', $title)

@section('messenger-content')
<style>.list-group{border-radius:13px;overflow:hidden;box-shadow:0 8px 22px rgba(16,24,40,.06)}.list-group-item{border-color:#edf1f5!important;padding:17px 18px!important;transition:.2s}.list-group-item:hover{background:#f5f8ff}.list-group-item a{color:#273b59;font-weight:600;text-decoration:none}.list-group-item .btn-danger{border-radius:6px;font-weight:700}</style>
<div class="row">
    <div class="col-md-12">
        <div class="list-group">
            @forelse($topics as $topic)
                <div class="row list-group-item">
                    <div class="col-xs-4 col-md-4">
                        <a href="{{ route('admin.messenger.showMessages', [$topic->id]) }}">
                            @php($receiverOrCreator = $topic->receiverOrCreator())
                                @if($topic->hasUnreads())
                                    <strong>
                                        {{ $receiverOrCreator !== null ? $receiverOrCreator->email : '' }}
                                    </strong>
                                @else
                                    {{ $receiverOrCreator !== null ? $receiverOrCreator->email : '' }}
                                @endif
                        </a>
                    </div>
                    <div class="col-xs-5 col-md-5">
                        <a href="{{ route('admin.messenger.showMessages', [$topic->id]) }}">
                            @if($topic->hasUnreads())
                                <strong>
                                    {{ $topic->subject }}
                                </strong>
                            @else
                                {{ $topic->subject }}
                            @endif
                        </a>
                    </div>
                    <div class="col-xs-2 text-right">{{ $topic->created_at->diffForHumans() }}</div>
                    <div class="col-xs-1 text-center">
                        <form action="{{ route('admin.messenger.destroyTopic', [$topic->id]) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');">
                            <input type="hidden" name="_method" value="DELETE">
                            @csrf
                            <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                        </form>
                    </div>
                </div>
                @empty
                <div class="row list-group-item">
                    {{ trans('global.you_have_no_messages') }}
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
