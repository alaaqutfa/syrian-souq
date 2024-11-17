@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <h5 class="mb-0 h6">{{ translate('Tax Information') }}</h5>
    </div>

    <div class="row">
        <div class="col-lg-6 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('update Tax Info') }}</h5>
                </div>
                <div class="card-body p-0">
                    <form class="p-4" action="{{ route('tax.update', $tax->id) }}" method="POST">
                        <input name="_method" type="hidden" value="PATCH">
                        @csrf
                        <div class="form-group row">
                            <div class="col-lg-3 mb-2">
                                <label class="control-label">{{ translate('Name') }}</label>
                            </div>
                            <div class="col-lg-9 mb-2">
                                <input type="text" class="form-control" name="name"
                                    placeholder="{{ translate('Name') }}" value="{{ $tax->name }}" required>
                            </div>
                            <label class="col-sm-3 mb-2 control-label" for="name">
                                {{ translate('Tax Type') }}
                            </label>
                            <div class="col-sm-9 mb-2">
                                <select class="form-control aiz-selectpicker" name="tax_type">
                                    <option value="amount" @if ($tax->tax_type == 'amount') selected @endif>
                                        {{ translate('Flat') }}</option>
                                    <option value="percent" @if ($tax->tax_type == 'percent') selected @endif>
                                        {{ translate('Percent') }}</option>
                                </select>
                            </div>
                            <label class="col-sm-3 mb-2 control-label" for="name">
                                {{ translate('Value') }}
                            </label>
                            <div class="col-sm-9 mb-2">
                                <input type="number" placeholder="{{ translate('Value') }}" id="tax_value"
                                    name="tax_value" class="form-control" value="{{ $tax->tax_value }}" required>
                            </div>
                        </div>

                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary">{{ translate('Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
