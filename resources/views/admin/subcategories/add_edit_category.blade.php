@extends('layouts.admin')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Catalogues</h1>
          </div>

          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Categories</li>
            </ol>
          </div>
        </div>
      </div>
    </section>
    <section class="content">
      <div class="container-fluid">
        <form class="categoryForm" id="categoryForm"
        @if (empty($subcategorydata['id']))
        action="{{ url('admin/Sub_categories/add-edit-category') }}"
        @else
        action="{{ url('admin/Sub_categories/add-edit-category',$subcategorydata['id']) }}"
        @endif
        method="post" enctype="multipart/form-data">
            @csrf
          <div class="card card-default">
            <div class="card-header">
              <h3 class="card-title">{{ $title }}</h3>

         {{-- @if ($errors->any())
            <div class="alert alert-danger" style="margin-top: 10px;"></div>

         @endif --}}
              <div class="card-tools">
                {{-- <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button> --}}
              </div>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                      <label>اسم القسم الفرعي </label>
                      <input class="form-control" id="name" name="name" type="text" placeholder="ادخل اسم القسم الفرعي "
                      @if (!empty($subcategorydata['id']))
                        value="{{ $subcategorydata->name }}"
                    @else
                        value="{{ old('name') }}"
                      @endif
                      >
                      @error('name')
                      <span class="text-danger"> {{$message}}</span>
                      @enderror
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                      <label>القسم الرئيسي التابع له</label>
                      <select name="parent_id" id="parent_id" class="form-control select2" style="width: 100%;">
                        <option value=""></option>
                        @foreach ($getMainCategories as $getMainCategory)
                            <option value="{{ $getMainCategory ->id }}"
                            @if (!empty($subcategorydata['parent_id']) && $subcategorydata['parent_id'] == $getMainCategory ->id  )
                                selected
                            @endif

                            >{{ $getMainCategory ->name }}</option>
                        @endforeach
                      </select>
                      @error('parent_id')
                      <span class="text-danger"> {{$message}}</span>
                      @enderror
                    </div>
                  {{-- <div class="form-group">
                      <label for="exampleInputFile">Category Image</label>
                      <div class="input-group">
                        <div class="custom-file">
                          <input type="file" class="custom-file-label" name="category_image" id="category_image">
                          <label class="custom-file-label" for="category_image">Choose file</label>
                        </div>
                        <div class="input-group-append">
                          <span class="input-group-text" id="">Upload</span>
                        </div>
                      </div>
                      @error('category_image')
                      <span class="text-danger"> {{$message}}</span>
                      @enderror
                    </div> --}}
                </div>
              </div>
              {{-- <div class="row">
                <div class="col-12 col-sm-6">
                  <div class="form-group">
                      <label>Category Discount </label>
                      <input class="form-control" id="category_discount" name="category_discount" type="text" placeholder="Enter Admin Name ">

                    </div>
                  <div class="form-group">
                      <label>Category Description </label>
                      <textarea class="form-control" name="description" id="description" rows="3" placeholder="Enter ..."></textarea>

                    </div>
                  <div class="form-group">
                      <label>Meta Description </label>
                       <textarea class="form-control" name="meta_description" id="meta_description" rows="3" placeholder="Enter ..."></textarea>
                 </div>
                </div>
                <div class="col-12 col-sm-6">
                  <div class="form-group">
                      <label>Category URL </label>
                      <input class="form-control" id="url" name="url" type="text" placeholder="Enter Admin Name ">
                      @error('category_image')
                      <span class="url"> {{$message}}</span>
                      @enderror
                  </div>
                  <div class="form-group">
                      <label>Meta Title </label>
                      <textarea class="form-control" name="meta_title" id="meta_title" rows="3" placeholder="Enter ..."></textarea>
                  </div>
                  <div class="form-group">
                      <label>Meta Keywords </label>
                      <textarea class="form-control" name="meta_keywords" id="meta_keywords" rows="3" placeholder="Enter ..."></textarea>
                  </div>
                </div>
              </div> --}}
            </div>
            <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Submit </button>
            </div>
          </div>
        </form>

      </div>
    </section>
  </div>
@endsection
