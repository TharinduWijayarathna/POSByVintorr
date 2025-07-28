<?php

namespace domain\Services\UnitOfMeasureService;

use App\Models\Unit;

class UnitOfMeasureService
{
    protected $unit;

    public function __construct()
    {
        $this->unit = new Unit();
    }

 
    public function all()
    {
        return $this->unit->get();
    }

    public function delete(int $uom_id)
    {
        return $this->unit->find($uom_id)->delete();
    }


    public function get($uom_id)
    {
        return $this->unit->where('id', $uom_id)->first();
    }

    public function store(array $data)
    {
        $data['name'] = ucwords($data['name']);
        $units = $this->unit->where('title', $data['name'])->first();
        if (!$units) {

            $this->unit->create([
                'title' => $data['name']
            ]);
        } else {
            return "This unit already exists";
        }
    }

    public function update($id, $data)
    {
        $data['name'] = ucwords($data['name']);
        $uom = $this->unit->find($id);

        $uom->update([
            'title' => $data['name'],
        ]);
    }
}
