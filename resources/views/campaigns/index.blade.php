@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-4">
            Campaign List
        </h2>

        <form action="{{ route('campaigns.index') }}" method="GET">
            <div class="row mb-4">
                <div class="col-md-3">
                    <label for="brand">Brand</label>
                    <select name="brand" id="brand" class="form-control">
                        <option value="">All Brands</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" @if(isset($filters['brand']) && $filters['brand'] == $brand->id) selected @endif>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="start_date">Start Date</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $start_date }}">
                </div>
                <div class="col-md-3">
                    <label for="end_date">End Date</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $end_date }}">
                </div>
                <div class="col-md-3 pt-5">
                    <button type="submit" class="btn btn-primary mt-1">Search</button>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">
                        <a href="{{ route(Route::currentRouteName(), array_merge(request()->query(), ['sort_by' => 'campaign_name', 'order_by' => ($sort_by === 'campaign_name' && $order_by === 'asc') ? 'desc' : 'asc'])) }}">
                            Campaign Name
                        </a>
                    </th>
                    <th scope="col">
                        <a href="{{ route(Route::currentRouteName(), array_merge(request()->query(), ['sort_by' => 'brand_name', 'order_by' => ($sort_by === 'brand_name' && $order_by === 'asc') ? 'desc' : 'asc'])) }}">
                            Brand Name
                        </a>
                    </th>
                    <th scope="col">
                        <a href="{{ route(Route::currentRouteName(), array_merge($filters, ['sort_by' => 'impressions_count', 'order_by' => ($sort_by === 'impressions_count' && $order_by === 'asc') ? 'desc' : 'asc'])) }}">
                            Impressions
                        </a>
                    </th>
                    <th scope="col">
                        <a href="{{ route(Route::currentRouteName(), array_merge($filters, ['sort_by' => 'interactions_count', 'order_by' => ($sort_by === 'interactions_count' && $order_by === 'asc') ? 'desc' : 'asc'])) }}">
                            Interactions
                        </a>
                    </th>
                    <th scope="col">
                        <a href="{{ route(Route::currentRouteName(), array_merge($filters, ['sort_by' => 'conversions_count', 'order_by' => ($sort_by === 'conversions_count' && $order_by === 'asc') ? 'desc' : 'asc'])) }}">
                            Conversions
                        </a>
                    </th>
                    <th scope="col">
                        <a href="{{ route(Route::currentRouteName(), array_merge($filters, ['sort_by' => 'conversion_rate', 'order_by' => ($sort_by === 'conversion_rate' && $order_by === 'asc') ? 'desc' : 'asc'])) }}">
                            Conversion Rate (%)
                        </a>
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($campaigns as $campaign)
                    <tr>
                        <td>{{ $campaign->campaign_name }}</td>
                        <td>{{ $campaign->brand_name }}</td>
                        <td>{{ $campaign->impressions_count }}</td>
                        <td>{{ $campaign->interactions_count }}</td>
                        <td>{{ $campaign->conversions_count }}</td>
                        <td>
                            @if($campaign->impressions_count > 0)
                                {{ number_format(($campaign->conversions_count / $campaign->impressions_count) * 100, 2) }}%
                            @else
                                0.00%
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="my-5">
            {{ $campaigns->appends(array_merge($filters, $sorting))->links() }}
        </div>
    </div>
@endsection
