<?php
$xpdo_meta_map['RvgVideosTags']= array (
  'package' => 'resvideogallery',
  'version' => '1.1',
  'table' => 'rvg_videos_tags',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'video_id' => NULL,
    'tag' => NULL,
  ),
  'fieldMeta' => 
  array (
    'video_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
      'index' => 'index',
    ),
    'tag' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
    ),
  ),
  'indexes' => 
  array (
    'video' => 
    array (
      'alias' => 'video',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'video_id' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
        'tag' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
  'aggregates' => 
  array (
    'Video' => 
    array (
      'class' => 'RvgVideos',
      'local' => 'video_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
