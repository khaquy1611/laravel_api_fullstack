<?php 
namespace App\Traits;

trait Query {
    public function scopeKeyword($query, $keyword) {
       if (!empty($keyword['q'])) {
        if (count($keyword['search'])) {
            foreach($keyword['field'] as $key => $val) {
               $query->orWhere($val, 'LIKE', '%' . $keyword['q'] . '%');
            }
        }else {
            $query->orWhere('name', 'LIKE', '%' . $keyword['q'] . '%');
         }
       }
       return $query;
    }

    public function scopeSimpleFilter($query, $simpleFilter) {
       if (count($simpleFilter)) {
          foreach($simpleFilter as $key => $val) {
            if ($val !== 0 && !empty($val) && !is_null($val)) {
                $query->where($key, $val);
            }
          }
       }
       return $query;
    }

    private function handleOperator($query , $complexFilter) {
        if (count($complexFilter)) {
            foreach($complexFilter as $field => $conditions) {
              foreach($conditions as $operator => $val) {
                  switch ($operator) {
                      case 'gt':
                          $query->where($field, '>', $val);
                          break;
                      case 'lt':
                          $query->where($field, '<', $val);
                          break;
                      case 'gte':
                          $query->where($field, '>=', $val);
                          break;
                      case 'lte':
                          $query->where($field, '<=', $val);
                          break;
                  }
            }
         }
        }
    }

    public function scopeComplexFilter($query, $complexFilter) {
       $this->handleOperator($query, $complexFilter);
       return $query;
    }
    
    public function scopeDateFilter($query, $dateFilter) {
        if (count($dateFilter)) {
            
            foreach($dateFilter as $field => $conditions) {
              foreach($conditions as $operator => $date) {
                  switch ($operator) {
                      case 'gt':
                          $query->whereDate($field, '>', \Carbon\Carbon::parse($date)->startOfDay());
                          break;
                      case 'lt':
                          $query->whereDate($field, '<', \Carbon\Carbon::parse($date)->startOfDay());
                          break;
                      case 'gte':
                          $query->whereDate($field, '>=', \Carbon\Carbon::parse($date)->startOfDay());
                          break;
                      case 'lte':
                          $query->whereDate($field, '<=', \Carbon\Carbon::parse($date)->startOfDay());
                          break;
                      case 'between':
                          list($start_date, $end_date) = explode(' - ', $date);
                          $query->whereBetween($field, [
                            \Carbon\Carbon::parse($start_date)->startOfDay(),
                            \Carbon\Carbon::parse($end_date)->endOfDay()
                          ]);
                          break;
                  }
            }
         }
        }
        return $query;
    }

    public function scopePermissionFilter($query, $permission) {
        $auth = auth('api')->user();
        if(isset($permission['view']) && $permission['view'] = 'own') {
            $query->where('user_id', $auth->id);
        }
        return $query;
    }
}