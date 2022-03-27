<?php

use Illuminate\Support\Arr;

const PER_PAGE = 10;

function saveMultipleSizeImage($image, $direction)
{
  $img_name = $image->hashName('');
  $img = \Image::make($image);

  $img->resize(2000, null, function ($constraint) {
    $constraint->aspectRatio();
  });

  \Illuminate\Support\Facades\Storage::disk('public')->put($direction . '/2000/' . $img_name, (string)$img->encode());

//    $img->resize(1000, null, function ($constraint) {
//        $constraint->aspectRatio();
//    });
//    \Illuminate\Support\Facades\Storage::disk('public')->put($direction.'/1000/'.$img_name, (string) $img->encode());

  $img->resize(600, null, function ($constraint) {
    $constraint->aspectRatio();
  });
  \Illuminate\Support\Facades\Storage::disk('public')->put($direction . '/600/' . $img_name, (string)$img->encode());

  $img->resize(300, null, function ($constraint) {
    $constraint->aspectRatio();
  });
  \Illuminate\Support\Facades\Storage::disk('public')->put($direction . '/300/' . $img_name, (string)$img->encode());

  return $img_name;
}

function fileExists($direction)
{
  return File::exists('/' . $direction);
}

function saveCustomSizeImage($image, $direction, $width, $height)
{
  $img_name = $image->hashName('');
  $img = \Image::make($image);

  File::exists(public_path() . '/' . $direction) or File::makeDirectory(public_path() . '/' . $direction, 755, true);

  $img->resize($width, $height, function ($constraint) {
    $constraint->aspectRatio();
  })->save(public_path() . '/' . $direction . '/' . $img_name);

  return $img_name;
}

function saveFile($file, $direction)
{
  $mime = $file->getClientOriginalExtension();
  $dir = '/uploads/' . $direction . '/' . date('Y') . '/' . date('m');
  File::exists(public_path() . '/uploads/' . $direction . '/') or File::makeDirectory(public_path() . '/uploads/' . $direction, 0755, true);
  File::exists(public_path() . '/' . $dir) or File::makeDirectory(public_path() . $dir, 0755, true);
  $file_name = rand(10000, 99999) . '.' . $mime;
  $file->move(public_path() . $dir, $file_name);
  return $dir . '/' . $file_name;
}

function errorResponse($messages, $status = 404)
{
  return response()->json([
      'error_message' => $messages,
  ], $status);
}

function uploadImage($img, $folder, $height = 900, $width = 900)
{
  File::exists(public_path() . '/uploads/' . $folder) or File::makeDirectory(public_path() . '/uploads/' . $folder, 0755, true);

  $path = $img->hashName('uploads/' . $folder);

  \Image::make($img)->resize($height, $width, function ($constraint) {
    $constraint->aspectRatio();
  })->save(public_path($path));

  return $path;
}

function moveImgFromTemp($folder, $path_of_img, $name_of_img)
{

  File::exists(public_path() . '/' . $folder) or File::makeDirectory(public_path() . '/' . $folder, 0755, true);

  if (File::exists($path_of_img)) {
    File::move(public_path($path_of_img), public_path() . '/' . $folder . '/' . $name_of_img);
  }
  return $folder . '/' . $name_of_img;
}

function prepareResult($status, $data, $msg, $code)
{
  return response(['status' => $status, 'data' => $data, 'message' => $msg], $code);
}

function moveMultiFilesFromTemp($array_of_files, $folder)
{
  $files = [];
  File::exists(public_path() . $folder) or File::makeDirectory(public_path() . $folder, 0755, true);
  foreach ($array_of_files as $file) {
    if (File::exists(public_path() . $file['path'])) {
      File::move(public_path($file['path']), public_path() . $folder . $file['unique_name']);
      $final_path = $folder . $file['unique_name'];
      $files[] = $final_path;
    }
  }
  return $files;
}

function uploadFile($file, $direction)
{
  $mime = $file->getClientOriginalExtension();
  $dir = '/uploads/' . $direction . '/' . date('Y') . '/' . date('m');
  File::exists(public_path() . '/uploads/' . $direction . '/') or File::makeDirectory(public_path() . '/uploads/' . $direction, 0755, true);
  File::exists(public_path() . '/' . $dir) or File::makeDirectory(public_path() . $dir, 0755, true);
  $file_name = $file->getClientOriginalName();
  $file_unique_name = rand(10000, 99999) . '.' . $mime;
  $file->move(public_path() . $dir, $file_unique_name);

  return ['path' => $dir . '/' . $file_unique_name, 'name' => $file_name, 'unique_name' => $file_unique_name];
}
