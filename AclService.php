<?php
namespace Modules\Acl;


class AclService {
    
    protected $table = "";
    protected $q;
    protected $allowedIds = [];

    public function applyTo($q) {
        $this->checkTableSet();
        $this->q = $q;
        $aclDefs = \DB::table('acl_groups')
                ->where('table', $this->table)
                ->get();



        if($aclDefs->isEmpty()) {
            return $this->q;
        }

       
       // dd($aclDefs);
        foreach($aclDefs as $acl) {
//            dd($acl);
            $userId = $this->getAuthuser()->id;
           // dd($userId);
            if($acl->acl_key_type == '1-n') {
                $allowed = \DB::table($acl->acl_res_table)
                        ->select($acl->acl_res_table.".id As id")
                        ->join($acl->acl_user_table, "$acl->acl_res_table.$acl->acl_res_field", "$acl->acl_user_table.$acl->acl_res_field")
                        ->where($acl->acl_user_table.".user_id", $userId)
                        ->get();
            }

            if($acl->acl_key_type == 'n-n') {
                $allowed = \DB::table($acl->acl_res_table)
                        ->select($acl->acl_res_table.".".$acl->acl_res_field." as id")
                        ->join($acl->acl_user_table, "$acl->acl_res_table.$acl->acl_res_field", "$acl->acl_user_table.$acl->acl_res_field")
                        ->where($acl->acl_user_table.".user_id", $userId)
                        ->get();
            }


            $allowedIds = $allowed->pluck('id');
//            dd($allowedIds);
            
            if($acl->acl_key_type == "own") {
                $this->viaOwnValue($acl, $allowedIds);
            } else {
                $this->viaForeignValue($acl, $allowedIds);
            }
            
            
        }
        
        $this->q->whereIn('id', $this->allowedIds);
        
        
        return $this->q;
    }
    
    public function viaOwnValue($acl, $allowedIds) {
        
        $this->addAppendAllowedIds($allowedIds);
       
        
    }
    
    public function addAppendAllowedIds($allowedIds) {
        
        if(empty($this->allowedIds)) {
            $this->allowedIds = $allowedIds;
        } else {
            $this->allowedIds = $this->allowedIds->merge($allowedIds);
            
        }
        
    }
    
    public function viaForeignValue($acl, $userAllowedIds) {
        
       // dd($userAllowedIds);
        $joinDef = $this->findParentJoinDef($acl);
        if($acl->acl_key_type == '1-n') {
            $allowedIds = \DB::table($joinDef->table)
                    ->select($joinDef->table.'.id as id')
                    ->join($joinDef->link_table, $joinDef->table.".".$joinDef->field, $joinDef->link_table.".".$joinDef->link_field)
                    ->whereIn($joinDef->field, $userAllowedIds)
                    ->get()->pluck('id');
        }

        if($acl->acl_key_type == 'n-n') {
            $allowedIds = \DB::table($joinDef->table)
                    ->select($joinDef->link_table.'.id as id')
                    ->join($joinDef->link_table, $joinDef->table.".".$joinDef->field, $joinDef->link_table.".".$joinDef->link_field)
                    ->whereIn($acl->acl_res_field, $userAllowedIds)
                    ->get()->pluck('id');

        }
        
        $this->addAppendAllowedIds($allowedIds);
        
    }
    
    public function findParentJoinDef($acl) {
        
        if($acl->acl_key_type == '1-n') {
            $joinDef = \DB::table('eds_fields')->where('table', $acl->table)->where('link_table', $acl->acl_res_table)->first();
        }
                

        if($acl->acl_key_type == 'n-n') {
            $joinDef = \DB::table('eds_fields')->where('table', $acl->acl_res_table)->where('link_table', $acl->table)->first();
        }

        if(!empty($joinDef)) {
            return $joinDef;
        } else {
            // dd('can not find acl def');
            abort(500,'can not find join def in acl AclService.php');
        }
    }
    
    public function forRes($table) {
        $this->table = $table;
        return $this;
    }

    /**
     *
     * @throws Exception if table is not set
     */
    public function checkTableSet() {
        if($this->table == "") {
            throw new Exception("table in ACL Service not set");
        }
        return false;
    }
    
    public function getAuthUser() {
        
        $authUser = \Auth::user();
//        $authUser = (object) ['id' => 1];
        return $authUser;
        
    }

}