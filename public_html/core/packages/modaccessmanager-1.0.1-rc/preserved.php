<?php return array (
  'a400989fa17681e405692cfa2e174f4e' => 
  array (
    'criteria' => 
    array (
      'name' => 'modaccessmanager',
    ),
    'object' => 
    array (
      'name' => 'modaccessmanager',
      'path' => '{core_path}components/modaccessmanager/',
      'assets_path' => '',
    ),
  ),
  '273880f67cc02cd9269c17a0189d7570' => 
  array (
    'criteria' => 
    array (
      'name' => 'Manager',
    ),
    'object' => 
    array (
      'id' => 2,
      'name' => 'Manager',
      'description' => 'Limited access to the site only for managers',
      'parent' => 1,
      'rank' => 0,
      'dashboard' => 1,
    ),
  ),
  '4c11ae9f0658e503721972343ff5cfcb' => 
  array (
    'criteria' => 
    array (
      'name' => 'system',
    ),
    'object' => 
    array (
      'id' => 1,
      'name' => 'system',
      'private_memgroup' => 0,
      'private_webgroup' => 0,
    ),
  ),
  'e79aeae0f9e41c93e4ca428fa9d046e4' => 
  array (
    'criteria' => 
    array (
      'name' => 'Manager',
    ),
    'object' => 
    array (
      'id' => 3,
      'name' => 'Manager',
      'description' => 'for the manager',
      'authority' => 9,
    ),
  ),
  '1d4b7e88033533e5e3076d34c3df1a6a' => 
  array (
    'criteria' => 
    array (
      'name' => 'Manager',
    ),
    'object' => 
    array (
      'id' => 13,
      'name' => 'Manager',
      'description' => 'The security policy for a manager',
      'parent' => 0,
      'template' => 1,
      'class' => '',
      'data' => '{"about":false,"access_permissions":false,"actions":false,"change_password":true,"change_profile":true,"charsets":true,"class_map":true,"components":true,"content_types":false,"countries":true,"create":true,"credits":true,"customize_forms":false,"dashboards":false,"database":false,"database_truncate":false,"delete_category":false,"delete_chunk":false,"delete_context":false,"delete_document":true,"delete_eventlog":false,"delete_plugin":false,"delete_propertyset":false,"delete_role":false,"delete_snippet":false,"delete_template":false,"delete_tv":false,"delete_user":true,"directory_chmod":false,"directory_create":true,"directory_list":true,"directory_remove":true,"directory_update":true,"edit_category":true,"edit_chunk":true,"edit_context":false,"edit_document":true,"edit_locked":true,"edit_plugin":false,"edit_propertyset":false,"edit_role":false,"edit_snippet":false,"edit_template":false,"edit_tv":false,"edit_user":true,"element_tree":false,"empty_cache":true,"error_log_erase":true,"error_log_view":true,"export_static":false,"file_create":false,"file_list":true,"file_manager":true,"file_remove":true,"file_tree":false,"file_update":false,"file_upload":true,"file_view":true,"flush_sessions":true,"frames":true,"help":false,"home":true,"import_static":false,"languages":false,"lexicons":false,"list":true,"load":true,"logout":true,"logs":false,"menu_reports":false,"menu_security":false,"menu_site":true,"menu_support":false,"menu_system":true,"menu_tools":true,"menu_user":true,"menus":false,"messages":true,"namespaces":false,"new_category":true,"new_chunk":true,"new_context":false,"new_document":true,"new_document_in_root":true,"new_plugin":false,"new_propertyset":false,"new_role":false,"new_snippet":false,"new_static_resource":false,"new_symlink":false,"new_template":false,"new_tv":false,"new_user":false,"new_weblink":false,"packages":false,"policy_delete":false,"policy_edit":false,"policy_new":false,"policy_save":false,"policy_template_delete":false,"policy_template_edit":false,"policy_template_new":false,"policy_template_save":false,"policy_template_view":false,"policy_view":false,"property_sets":false,"providers":false,"publish_document":true,"purge_deleted":true,"remove":true,"remove_locks":true,"resource_duplicate":true,"resource_quick_create":true,"resource_quick_update":true,"resource_tree":true,"resourcegroup_delete":false,"resourcegroup_edit":false,"resourcegroup_new":false,"resourcegroup_resource_edit":false,"resourcegroup_resource_list":false,"resourcegroup_save":false,"resourcegroup_view":false,"save":true,"save_category":true,"save_chunk":true,"save_context":false,"save_document":true,"save_plugin":false,"save_propertyset":false,"save_role":false,"save_snippet":false,"save_template":true,"save_tv":true,"save_user":false,"search":false,"settings":false,"source_delete":false,"source_edit":false,"source_save":false,"source_view":true,"sources":false,"steal_locks":true,"tree_show_element_ids":true,"tree_show_resource_ids":true,"undelete_document":true,"unlock_element_properties":true,"unpublish_document":true,"usergroup_delete":false,"usergroup_edit":false,"usergroup_new":false,"usergroup_save":false,"usergroup_user_edit":false,"usergroup_user_list":false,"usergroup_view":true,"view":true,"view_category":false,"view_chunk":false,"view_context":false,"view_document":true,"view_element":true,"view_eventlog":false,"view_offline":true,"view_plugin":false,"view_propertyset":false,"view_role":false,"view_snippet":false,"view_sysinfo":false,"view_template":true,"view_tv":true,"view_unpublished":true,"view_user":true,"workspaces":false}',
      'lexicon' => 'permissions',
    ),
  ),
  '6bc9974bb391ff3267bfafc350005d38' => 
  array (
    'criteria' => 
    array (
      'category' => 'system',
    ),
    'object' => 
    array (
      'id' => 28,
      'parent' => 0,
      'category' => 'system',
      'rank' => 0,
    ),
  ),
  '460c398db7e009bda7ec0a395a52d1ee' => 
  array (
    'criteria' => 
    array (
      'name' => 'modAccessManager',
    ),
    'object' => 
    array (
      'id' => 21,
      'source' => 1,
      'property_preprocess' => 0,
      'name' => 'modAccessManager',
      'description' => '',
      'editor_type' => 0,
      'category' => 28,
      'cache_type' => 0,
      'plugincode' => 'switch ($modx->event->name) {

    case \'OnCacheUpdate\':
        if ($modx->context->key == \'mgr\') {

            $modUserGroup = $modx->getObject(\'modUserGroup\', array(\'name\' => \'Manager\'));
            $group_id = $modUserGroup->get(\'id\');

            $modAccessPolicy = $modx->getObject(\'modAccessPolicy\', array(\'name\' => \'Manager\'));
            $policy_id = $modAccessPolicy->get(\'id\');

            $modAccessPolicyContext = $modx->getObject(\'modAccessPolicy\', array(\'name\' => \'Context\'));
            $policy_context_id = $modAccessPolicyContext->get(\'id\');


            $c = $modx->newQuery(\'modAccessContext\');
            $c->where(array(\'principal\' => $group_id, \'policy:!=\' => $policy_id));
            if ($objectList = $modx->getCollection(\'modAccessContext\', $c)) {
                foreach ($objectList as $object) {
                    $policy = $object->get(\'policy\');
                    if ($policy_context_id == $policy) continue;
                    $object->remove();
                }
            }


            /* search policy access */
            $data = array();
            $c = $modx->newQuery(\'modAccessPolicy\');
            $c->where(array(\'lexicon:!=\' => \'permissions\'));
            if ($objectList = $modx->getCollection(\'modAccessPolicy\', $c)) {
                foreach ($objectList as $object) {
                    $id = $object->get(\'id\');
                    $data[] = array(
                        \'target\' => \'mgr\',
                        \'principal_class\' => \'modUserGroup\',
                        \'principal\' => $group_id,
                        \'authority\' => 9,
                        \'policy\' => $id,
                    );

                }
            }

            foreach ($data as $key => $row) {
                if (!$tmp = $modx->getObject(\'modAccessContext\', $row)) {
                    $tmp = $modx->newObject(\'modAccessContext\');
                    $tmp->fromArray($row);
                    $tmp->save();
                }
            }

        }

    break;

}',
      'locked' => 0,
      'properties' => NULL,
      'disabled' => 0,
      'moduleguid' => '',
      'static' => 0,
      'static_file' => 'core/components/modaccessmanager/elements/plugins/plugin.modaccessmanager.php',
      'content' => 'switch ($modx->event->name) {

    case \'OnCacheUpdate\':
        if ($modx->context->key == \'mgr\') {

            $modUserGroup = $modx->getObject(\'modUserGroup\', array(\'name\' => \'Manager\'));
            $group_id = $modUserGroup->get(\'id\');

            $modAccessPolicy = $modx->getObject(\'modAccessPolicy\', array(\'name\' => \'Manager\'));
            $policy_id = $modAccessPolicy->get(\'id\');

            $modAccessPolicyContext = $modx->getObject(\'modAccessPolicy\', array(\'name\' => \'Context\'));
            $policy_context_id = $modAccessPolicyContext->get(\'id\');


            $c = $modx->newQuery(\'modAccessContext\');
            $c->where(array(\'principal\' => $group_id, \'policy:!=\' => $policy_id));
            if ($objectList = $modx->getCollection(\'modAccessContext\', $c)) {
                foreach ($objectList as $object) {
                    $policy = $object->get(\'policy\');
                    if ($policy_context_id == $policy) continue;
                    $object->remove();
                }
            }


            /* search policy access */
            $data = array();
            $c = $modx->newQuery(\'modAccessPolicy\');
            $c->where(array(\'lexicon:!=\' => \'permissions\'));
            if ($objectList = $modx->getCollection(\'modAccessPolicy\', $c)) {
                foreach ($objectList as $object) {
                    $id = $object->get(\'id\');
                    $data[] = array(
                        \'target\' => \'mgr\',
                        \'principal_class\' => \'modUserGroup\',
                        \'principal\' => $group_id,
                        \'authority\' => 9,
                        \'policy\' => $id,
                    );

                }
            }

            foreach ($data as $key => $row) {
                if (!$tmp = $modx->getObject(\'modAccessContext\', $row)) {
                    $tmp = $modx->newObject(\'modAccessContext\');
                    $tmp->fromArray($row);
                    $tmp->save();
                }
            }

        }

    break;

}',
    ),
  ),
  '2273535f81a56a8a8b34ee0d9b393f2e' => 
  array (
    'criteria' => 
    array (
      'pluginid' => 21,
      'event' => 'OnCacheUpdate',
    ),
    'object' => 
    array (
      'pluginid' => 21,
      'event' => 'OnCacheUpdate',
      'priority' => 0,
      'propertyset' => 0,
    ),
  ),
);