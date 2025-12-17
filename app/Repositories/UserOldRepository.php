<?php
namespace App\Repositories;

use App\Models\OrderMaster;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserOldRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return User::class;
    }

    public function findByParams($params){

        return $this->model
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('id', $params['id']);
            })
            ->first()
            ;
    }

    public function findById($id){
        return $this->model
            ->where('id', $id)
            ->first()
            ;
    }

    public function search($request){

        $s_role  = $request->s_role ?? null;
        $s_email  = $request->s_email ?? null;
        $s_name  = $request->s_name ?? null;

        $query = $this->model
            ->when(!empty($s_role), function ($query) use ($s_role) {
                $query->where('role', $s_role);
            })
            ->when(!empty($s_name), function ($query) use ($s_name) {
                $query->where(function ($sub) use ($s_name) {
                    $sub->where('name', 'like', "%{$s_name}%")
                        ->orWhere('name_kana', 'like', "%{$s_name}%");
                });
            })
            ->when(!empty($s_email), function ($query) use ($s_email) {
                $query->where('email', 'LIKE', "%$s_email%");
            })
            ->orderBy('id', 'DESC')
        ;

        if($request->is_all){
            return $query->get();
        }

        return $query->paginate(config('constants.paginate') ?? 10);

    }
}
