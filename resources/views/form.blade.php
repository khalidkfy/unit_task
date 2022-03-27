@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <home-form inline-template :home="{{isset($home) ? $home->toJson() : 'null'}}">
          <div class="card">
            <div class="card-header">
              <a href="{{route('home')}}" class="btn btn-success">home</a>

            </div>

            <div class="card-body">
              @if (session('status'))
                <div class="alert alert-success" role="alert">
                  {{ session('status') }}
                </div>
              @endif
                <div class="form-group row">
                  <fg-input v-model="name" label="name" class="col-md-6 my-3" name="name" required></fg-input>
                  <fg-input v-model="size" label="size" class="col-md-6 my-3" name="size" required></fg-input>
                  <fg-input v-model="sale_agent" label="sale agent" class="col-md-6 my-3" name="sale_agent" required></fg-input>
                  <fg-input v-model="city" label="city" class="col-md-6 my-3" name="city" required></fg-input>
                  <fg-input v-model="price" label="price" class="col-md-6 my-3" name="price" type="number" required></fg-input>
                  <fg-input v-model="bedrooms_count" label="bedrooms count" class="col-md-6 my-3" name="bedrooms_count" type="number" required></fg-input>
                  <fg-input v-model="bathrooms_count" label="bathrooms count" class="col-md-6 my-3" name="bathrooms_count" type="number" required></fg-input>
                  <fg-textarea v-model="desc" label="Description" class="col-md-6 my-3" name="desc" required></fg-textarea>
                  <div class="my-3 col-md-12">
                      <h4>facilities</h4>
                      <button @click="add()" class="btn btn-success">add</button>
                      <div v-for="(fac, index) in facs" class="my-2">
                        <fg-input v-model="fac.name" :name="`facs.${index}.name`"></fg-input>
                        <button v-if="index > 0" @click="remove(index)" class="btn btn-danger">x</button>
                      </div>
                  </div>
                  <img-upload v-model="cover_image" ref="imgUpload" old="{{isset($home) ? asset($home->cover_image) : 'null'}}" name="cover_image" class="col-md-4  my-3" label="{{__("Image")}}"></img-upload>
                  <div class="mt-5">
                    <h4>images gallery</h4>
                    <multi-imgs ref="imgs" v-model="imgs"  label="upload imgs" class="col-md-4 my-3"></multi-imgs>

                  </div>
                </div>
            </div>
            <div class="card-footer">
              <button @click="save()" class="btn btn-primary">save</button>
            </div>
          </div>
        </home-form>
      </div>
    </div>
  </div>
@endsection

