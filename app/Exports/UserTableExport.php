<?php

namespace App\Exports;

use App\User;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class UserTableExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $list = User::select( 'id', 'name', 'email', 'user_type', 'ug_name' ,'status' )
                ->leftJoin('user_groups', 'users.user_group', '=', 'user_groups.ug_id');
            
        $list= $list->get()->toArray();
        return collect($list);
    }

    public function headings(): array
    {
        return [
            'Id',
            'Name',
            'Email',
            'Type',
            'User group',
            'Status',
        ];
    }
}
