<?php

namespace Modules\Acl\Controllers;


use Illuminate\Http\Request;
use Modules\Base\Controller\BaseController;
use Modules\Flexwb\Services\ValidatorService;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class AclController extends BaseController {

    protected $notifications = [];

    public function __construct(
    \App\Repositories\QueryBuilderRepository $repository
    ) {
        $this->repository = $repository;
        $this->validator = new ValidatorService();
    }

    public function resIndex(Request $request, $topRes) {

        return $this->repository->index($request, $topRes);
    }


    function createTable(Request $request) {
        $tableNameRaw = $request->get('table');
        $tableName = str_plural(str_singular($tableNameRaw));
        $aclTableName = $tableName."_acl";

        if (!Schema::hasTable($aclTableName)) {
            $created = Schema::create($aclTableName, function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger("res_id");
                $table->unsignedInteger("acl_group_id");
                $table->timestamps();
            });
            
            $notification = 'ACL table '.$aclTableName.' created';
            array_push($this->notifications, ['type' => 'success', 'msg' => $notification ]);

            return response()
                    ->json(['acl_table' => $aclTableName])
                    ->setStatusCode(200, "created ACL table")
                    ->header('x-notifications', json_encode($this->notifications));
        } else {

            $errorMsg = 'ACL table '.$aclTableName.' already Exists';
            array_push($this->notifications, ['type' => 'error', 'msg' => $errorMsg ]);

            return response()
                    ->json(['error' => $errorMsg])
                    ->setStatusCode(422, $errorMsg)
                    ->header('x-notifications', json_encode($this->notifications));


        }

    }

    public function createAclTable($topRes, $topId) {

        $dataAndRelations = $this->repository->get($topRes, $topId);
        if(!empty($dataAndRelations)) {
            $data = $dataAndRelations['data'];
            $relations = $dataAndRelations['relations'];
            return response()->json($data)->header('x-relations', json_encode($relations));
        } else {
            
            $errorMsg = 'Resource '.$topRes.' with id '.$topId.' NOT found';
            array_push($this->notifications, ['type' => 'error', 'msg' => $errorMsg ]);
            return response()
                    ->json(['error' => $errorMsg])
                    ->setStatusCode(404, $errorMsg)
                    ->header('x-relations', json_encode([]))
                    ->header('x-notifications', json_encode($this->notifications));
        }
        
    }

//    public function makeAclTable($topRes, $topId) {
//
//        $dataAndRelations = $this->repository->get($topRes, $topId);
//        if(!empty($dataAndRelations)) {
//            $data = $dataAndRelations['data'];
//            $relations = $dataAndRelations['relations'];
//            return response()->json($data)->header('x-relations', json_encode($relations));
//        } else {
//
//            $errorMsg = 'Resource '.$topRes.' with id '.$topId.' NOT found';
//            array_push($this->notifications, ['type' => 'error', 'msg' => $errorMsg ]);
//            return response()
//                    ->json(['error' => $errorMsg])
//                    ->setStatusCode(404, $errorMsg)
//                    ->header('x-relations', json_encode([]))
//                    ->header('x-notifications', json_encode($this->notifications));
//        }
//
//    }
//
//
//
//    public function resRelations($resName) {
//
//        return $fks = \DB::table('eds_fields')->where('table', '=', $resName)->where('key_type', '=', 'fk')->get();
//    }

}
