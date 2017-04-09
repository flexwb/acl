<?php
namespace Modules\Acl;


class AclService {
    
    protected $table;
    protected $q;

    public function applyTo($q) {
        $this->q = $q;
        $aclDefs = \DB::table('acl_groups')->where('table', $this->table)->get();
        foreach($aclDefs as $acl) {
            $userId = $this->getAuthuser()->id;
            $allowed = \DB::table('user_acl')->where('table', $this->table)
                    ->where('user_id', $userId)->get();
            $allowedIds = $allowed->pluck('acl_group_id');
            
            if($acl->acl_key_type == "own") {
                $this->q = $this->viaOwnValue($acl, $allowedIds);
            }
            
            
        }
        
        return $this->q;
    }
    
    public function viaOwnValue($acl, $allowedIds) {
        
        return $this->q->whereIn($acl->acl_ref_key, $allowedIds);
        
    }
    
    public function forRes($table) {
        $this->table = $table;
        return $this;
    }
    
    public function getAuthUser() {
        
        $id= \Request::get('id');
        return (object) ['id' => $id];
        
    }

}