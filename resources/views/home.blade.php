@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <a href="{{route('home.create')}}" class="btn btn-success">new</a>
                    <a href="{{route('home.scrap')}}" class="btn btn-success">fetch data</a>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <home-index inline-template>
                        <div>
                            <form @submit.prevent="refreshTable()" action="">
                                <div class="row">
                                    <div class="col">
                                        <fg-input label="city" v-model="city"></fg-input>

                                    </div>
                                    <div class="col">
                                        <fg-input label="description" v-model="desc"></fg-input>

                                    </div>
                                    <div class="col">
                                        <button type="submit" class="btn btn-info">search</button>

                                    </div>
                                </div>
                            </form>
                            <div class="table-responsive">
                                <table-component
                                        :data="fetchData"
                                        :show-caption="false"
                                        ref="table"
                                        :show-filter="false"
                                        filter-no-results="No Data"
                                >
                                    <table-column show="name" label="{{__('Name')}}"></table-column>
                                    <table-column show="size" label="{{__('Size')}}"></table-column>
                                    <table-column show="city" label="{{__('City')}}"></table-column>
                                    <table-column show="price" label="{{__('Price')}}"></table-column>
                                    <table-column show="sale_agent" label="{{__('Price')}}"></table-column>
                                    <table-column show="bedrooms_count" label="{{__('bedrooms count')}}"></table-column>
                                    <table-column show="bathrooms_count" label="{{__('bathrooms count')}}"></table-column>
                                    <table-column label="{{__('Operations')}}" :sortable="false" :filterable="false">
                                        <template slot-scope="row">
                                            <a :href="`/homes/${row.id}/edit`" class="btn btn-primary btn-sm mx-2" title="Edit">
                                                edit
                                            </a>
                                            <button @click="deleteItem(`/homes/${row.id}/delete`, 'homeDeleted')"
                                                    class="btn btn-danger mx-2 btn-sm" title="Delete">
                                                delete
                                            </button>
                                        </template>
                                    </table-column>
                                </table-component>
                            </div>
                        </div>
                    </home-index>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
