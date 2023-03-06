<?php

// define permissions
if (!function_exists('permissionLists')) {
  function permissionLists()
  {
    $permissions = [
      'create' => 'Create',
      // 'read' => 'Read',
      'update' => 'Update',
      'delete' => 'Delete',
    ];
    return $permissions;
  }
}

if (!function_exists('getImage')) {
  function getImage($image)
  {
    if ($image) {
      return asset('storage/' . $image);
    } else {
      return asset('assets/img/loader.svg');
    }
  }
}
