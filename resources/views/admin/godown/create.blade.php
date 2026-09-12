@extends('layouts.admin')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Create Godown
                </div>
                <div class="panel-body">
                    <form action="{{ route('admin.godowns.store') }}" method="POST" class="row">
                        @csrf

                        <div class="form-group col-lg-3 {{ $errors->has('name') ? 'has-error' : '' }}">
                            <label for="name">Name*</label>
                            <input type="text" id="name" name="name" class="form-control" required>
                            @if($errors->has('name'))
                                <em class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </em>
                            @endif
                        </div>

                        <div class="form-group col-lg-3 {{ $errors->has('location') ? 'has-error' : '' }}">
                            <label for="location">Location*</label>
                            <input type="text" id="location" name="location" class="form-control" required>
                            @if($errors->has('location'))
                                <em class="invalid-feedback">
                                    {{ $errors->first('location') }}
                                </em>
                            @endif
                        </div>

                        <div class="form-group col-lg-3 {{ $errors->has('capacity') ? 'has-error' : '' }}">
                            <label for="capacity">Capacity</label>
                            <input type="number" id="capacity" name="capacity" class="form-control">
                            @if($errors->has('capacity'))
                                <em class="invalid-feedback">
                                    {{ $errors->first('capacity') }}
                                </em>
                            @endif
                        </div>

                        <div class="form-group col-lg-3 {{ $errors->has('status') ? 'has-error' : '' }}">
                            <label for="status">Status*</label>
                            <select id="status" name="status" class="form-control" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                            @if($errors->has('status'))
                                <em class="invalid-feedback">
                                    {{ $errors->first('status') }}
                                </em>
                            @endif
                        </div>

                        <div class="form-group col-lg-12">
                            <button class="btn btn-danger" type="submit">
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
