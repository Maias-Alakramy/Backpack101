<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;

class Student extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    use HasFactory;

    public static function boot()
    {
        parent::boot();
        static::deleting(function($obj) {
            Storage::delete(Str::replaceFirst('storage/','public/', $obj->image));
        });
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'image',
        'class_room_id',
        'code',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'class_room_id' => 'integer',
    ];

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class);
    }
/*
    public function setImageAttribute($value) {

        $image=$value;
        $input['image'] = $image->getClientOriginalName();
        $img = \Image::make($image->getRealPath());

        $destinationPath = public_path('/Images');
        $img->resize(750, 450, function ($constraint) {
        $constraint->aspectRatio();
        })->save($destinationPath.'/'.$input['image']);

        $destinationPath = public_path('/uploads/Images');

        $img->resize(100, 100, function ($constraint) {
        $constraint->aspectRatio();
        })->save($destinationPath.'/'.$input['image']);

        $image->move($destinationPath, $input['image']);
        $this->attributes['image'] = $input['image'];

    }
*/
}
