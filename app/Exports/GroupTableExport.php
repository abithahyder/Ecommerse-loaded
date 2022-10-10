<?php

namespace App\Exports;

use App\user_groups;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class GroupTableExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        
        $list = user_groups::select( 'ug_id', 'ug_name', 'created_at' );
        $list= $list->get()->toArray();

        return collect($list);
    }

    public function headings(): array
    {
        return [
            'Id',
            'Name',
            'Created date',
        ];
    }
}
