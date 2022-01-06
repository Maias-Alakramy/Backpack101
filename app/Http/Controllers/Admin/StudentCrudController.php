<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StudentRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class StudentCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class StudentCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\BulkDeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Student::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/student');
        CRUD::setEntityNameStrings('student', 'students');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('code');
        CRUD::column('name');
        CRUD::column('classRoom');

        $this->addCustomCrudFilters();

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(StudentRequest::class);

        CRUD::field('name');
        CRUD::field('class_room_id')
            ->type('select')->model('App\Models\ClassRoom')
            ->attribute('number')->label('Class Room')
            ->entity('classRoom');

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    protected function addCustomCrudFilters()
    {
        CRUD::addFilter([
            'name'  => 'class_room',
            'type'  => 'select2',
            'label' => 'Class'
          ], function () {
            return \App\Models\ClassRoom::all()->keyBy('id')->pluck('number', 'id')->toArray();
          }, function ($value) { // if the filter is active
            CRUD::addClause('where', 'class_room_id', $value);
        });
        
        $this->crud->addFilter([
            'name'       => 'number',
            'type'       => 'range',
            'label'      => 'Range of student code',
            'label_from' => 'min value',
            'label_to'   => 'max value'
          ],
          false,
          function($value) { // if the filter is active
              $range = json_decode($value);
              if ($range->from) {
                  $this->crud->addClause('where', 'code', '>=', (float) $range->from);
              }
              if ($range->to) {
                  $this->crud->addClause('where', 'code', '<=', (float) $range->to);
              }
          });
    }

    private function myHash1($value)
    {
        $h = 0;
        for ($i = 0; $i < strlen($value); $i++) {
            $h += $i*ord($value[$i]);
            $h %= 1009;
        }
        return $h+1;
    }
    private function myHash2($value)
    {
        $h = 0;
        for ($i = 0; $i < strlen($value); $i++) {
            $h += 5*$i*ord($value[$i]);
            $h %= 2003;
        }
        return $h+1;
    }
    private function myHash3($value)
    {
        $h = 0;
        for ($i = 0; $i < strlen($value); $i++) {
            $h += 3*$i*ord($value[$i]);
            $h %= 2503;
        }
        return $h+1;
    }

    //override the CRUD create function
    public function store(StudentRequest $request)
    {
        $model = \App\Models\Student::all();
        $student = new \App\Models\Student();
        $student->name = $request->name;
        $student->class_room_id = $request->class_room_id;
        $hash = $this->myHash1($request->name);
        if ($model->where('code', $hash)->count() > 0) {
            $hash = $this->myHash2($request->name);
            if($model->where('code', $hash)->count() > 0){
                $hash = $this->myHash3($request->name);
                if($model->where('code', $hash)->count() > 0){
                    $hash = $this->myHash1($request->name) + $this->myHash2($request->name) + $this->myHash3($request->name);
                    $hash %= 2503;
                    if($model->where('code', $hash)->count() > 0){
                        $hash = 0;
                    }
                }
            }
        }
        
        $student->code = $hash;

        $student->save();
        return redirect('/admin/student');
    }
}
