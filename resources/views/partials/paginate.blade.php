@if(is_object($data) && class_basename(get_class($data)) == 'LengthAwarePaginator' && $data->lastPage() > 1)
    {{-- Collection is paginated, so render that --}}
    <div class='card-footer'>
        {{ $data->appends(request()->all())->links() }}
    </div>
@endif