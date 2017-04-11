<?php
namespace Modules\Acl;


class AclService {
    
    protected $table = "";
    protected $q;

    public function applyTo($q) {
        $this->checkTableSet();
        $this->q = $q;
        $aclDefs = \DB::table('acl_groups')
                ->where('table', $this->table)
                ->get();

        foreach($aclDefs as $acl) {
            $userId = $this->getAuthuser()->id;
            $allowed = \DB::table($acl->acl_res_table)
                    ->select($acl->acl_res_table.".id As id")
                    ->join($acl->acl_user_table, "$acl->acl_user_table.$acl->acl_res_field", "$acl->acl_res_table.$acl->acl_user_field")
                    ->where($acl->acl_user_table.".user_id", $userId)
                    ->get();
//            dd($allowed);
//
//                    ->where('table', $this->table)
//                    ->where('user_id', $userId)->get();
            $allowedIds = $allowed->pluck('id');
//            dd($allowedIds);
            
            if($acl->acl_key_type == "own") {
                $this->q = $this->viaOwnValue($acl, $allowedIds);
            }
            
            
        }
        
        return $this->q;
    }
    
    public function viaOwnValue($acl, $allowedIds) {
        
        return $this->q->whereIn('id', $allowedIds);
        
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
        
//        $authUser = \Auth::user();
        $authUser = (object) ['id' => 1];
        return $authUser;
        
    }

}