<?php
/**
 * ResVideoGallery
 * @package resvideogallery
 */

/**
 * @var modX $modx
 * @var array $scriptProperties
 * @var Rvg $rvg
 */

require_once $modx->getOption('resvideogallery.core_path', null, $modx->getOption('core_path') . 'components/resvideogallery/') . 'model/resvideogallery/rvg.class.php';
$rvg = new Rvg($modx, $scriptProperties);

if (!is_object($rvg) || !($rvg instanceof Rvg)) return '';

$depth = 10;
$tpl = !empty($tpl) ? $tpl : 'resVideoGalleryTpl';
$tplRow = !empty($tplRow) ? $tplRow : 'resVideoGalleryRowTpl';
$tplTag = !empty($tplTag) ? $tplTag : 'resVideoGalleryTagTpl';
$tplEmbed = !empty($tplEmbed) ? $tplEmbed : 'resVideoGalleryEmbedTpl';
$autoPlay = isset($autoPlay) ? $autoPlay : 1;
$cacheKeyParents = isset($parents) ? md5($parents) : '';
$parents = !empty($parents) ? explode(',', $parents) : array();
$excludeResources = !empty($excludeResources) ? explode(',', $excludeResources) : array();
$expires = !empty($expires) ? $expires : $modx->getOption('cacheExpires', $scriptProperties, 86400);

if (empty($parents)) {
    $resources = (!empty($resources) || $resources === '0') ? explode(',', $resources) : array($modx->resource->get('id'));
    array_walk($resources, 'trim');
    $resources = array_unique($resources);
} else {
    $resources = array();
    if (!$resources = $modx->cacheManager->get($cacheKeyParents)) {
        $resources = array();
        foreach ($parents as $parent) {
            $resources = array_merge($resources, $modx->getChildIds($parent, $depth));
        }
        $modx->cacheManager->set($cacheKeyParents, $resources, $expires);
    }
}

if (!empty($excludeResources)) {
    $resources = array_diff($resources, $excludeResources);
}

$theme = isset($theme) ? $theme : 'base';
$ajax = empty($ajax) ? 0 : 1;
$ms2g = isset($ms2g) ? $ms2g : '';
$ms2gThumb = isset($ms2gThumb) ? $ms2gThumb : '360x270';
$sortdir = isset($sortdir) ? $sortdir : 'DESC';
$sortby = isset($sortby) ? $sortby : 'rank';
$limit = isset($limit) ? (integer)$limit : 12;
$includeTags = isset($includeTags) ? $includeTags : 0;
$offset = isset($offset) ? (integer)$offset : 0;
$sortbyEscaped = !empty($sortbyEscaped) ? true : false;
$totalVar = !empty($totalVar) ? $totalVar : 'total';
$where = !empty($where) ? $modx->fromJSON($where) : array();
$whereV = !empty($whereV) ? $modx->fromJSON($whereV) : $where;
$whereF = !empty($whereF) ? $modx->fromJSON($whereF) : $where;
$options = $modx->fromJSON($modx->getOption('resvideogallery.thumb_options', null, '{"w":360,"h":270,"q":90,"zc":"1","f":"jpg","bg":"000000"}'));
$dbCacheFlag = !isset($dbCacheFlag) ? false : $dbCacheFlag;
$filter = isset($filter) ? $filter : (isset($_GET['filter']) ? $_GET['filter'] : null);

$jquery = filter_var($modx->getOption('resvideogallery.jquery', null, 0), FILTER_VALIDATE_BOOLEAN);


if (is_string($dbCacheFlag) || is_numeric($dbCacheFlag)) {
    if ($dbCacheFlag == '0') {
        $dbCacheFlag = false;
    } elseif ($dbCacheFlag == '1') {
        $dbCacheFlag = true;
    } else {
        $dbCacheFlag = (integer)$dbCacheFlag;
    }
}

$whereV['RvgVideos.resource_id:IN'] = $resources;

if (empty($showInactive)) {
    $whereV['RvgVideos.active'] = 1;
}

$queryV = $modx->newQuery('RvgVideos', null, $dbCacheFlag);
$queryV->innerJoin('modResource', 'Resource', 'RvgVideos.resource_id = Resource.id');
$queryV->select(array(
    'RvgVideos.*'
));


if (!empty($filter)) {
    $filter = array_map('trim', explode(',', str_replace('"', '', $filter)));
}

if (!empty($tags)) {
    $tags = array_map('trim', explode(',', str_replace('"', '', $tags)));
    if (!empty($filter)) {
        $tags = array_merge($tags, $filter);
    }
} elseif (!empty($filter)) {
    $tags = $filter;
}

if (!empty($tags)) {
    $postfixQueryTags = !empty($filter) ? 'COUNT(DISTINCT Tag.tag) = ' . count($tags) : '';
    $tags = implode('","', $tags);
    $queryV->innerJoin('RvgVideosTags', 'Tag', 'RvgVideos.id = Tag.video_id AND Tag.tag IN ("' . $tags . '")');
   if($postfixQueryTags) $queryV->having($postfixQueryTags);
}


if (!empty($whereV)) {
    $queryV->where($whereV);
}

$queryV->groupby('RvgVideos.id');


$totalV = $modx->getCount('RvgVideos', $queryV);
$totalF = 0;
$queryF = null;

if (!empty($ms2g)) {
    /* @var ms2Gallery $ms2Gallery */
    $ms2Gallery = $modx->getService('ms2gallery', 'ms2Gallery', MODX_CORE_PATH . 'components/ms2gallery/model/ms2gallery/');

    $whereF['msResourceFile.resource_id:IN'] = $resources;

    if (empty($ms2gThumb)) {
        $whereF['msResourceFile.parent'] = 0;
    } else {
        $whereF['msResourceFile.path:LIKE'] = '%' . $ms2gThumb . '%';
    }

    if (empty($showInactive)) {
        $whereF['msResourceFile.active'] = 1;
    }

    if (!empty($filetype)) {
        $whereF['msResourceFile.type:IN'] = array_map('trim', explode(',', $filetype));
    }

    $queryF = $modx->newQuery('msResourceFile', null, $dbCacheFlag);
    $queryF->innerJoin('modResource', 'Resource', 'msResourceFile.resource_id = Resource.id');
    $queryF->select(array(
        'msResourceFile.*'
    ));

    if (!empty($tags)) {
        $queryF->innerJoin('msResourceFileTag', 'Tag', 'msResourceFile.id = Tag.file_id AND Tag.tag IN ("' . $tags . '")');
        if (!empty($postfixQueryTags)) $queryV->having($postfixQueryTags);
    }

    if (!empty($whereF)) {
        $queryF->where($whereF);
    }

    $queryF->groupby('msResourceFile.id');

    $totalF = $modx->getCount('msResourceFile', $queryF);
}

$total = $totalV + $totalF;

$modx->setPlaceholder($totalVar, $total);

if (!empty($sortby)) {
    if (strpos($sortby, '{') === 0) {
        $sorts = $modx->fromJSON($sortby);
    } else {
        $sorts = array($sortby => $sortdir);
    }
    if (is_array($sorts)) {
        while (list($sort, $dir) = each($sorts)) {
            if ($sortbyEscaped) $sort = $modx->escape($sort);
            if (!empty($sortbyAlias)) $sort = $modx->escape($sortbyAlias) . ".{$sort}";
            if (!empty($queryV)) $queryV->sortby($sort, $dir);
            if (!empty($queryF)) $queryF->sortby($sort, $dir);
        }
    }
}

if (!empty($limit)) {
    if (!empty($ms2g)) {
        if ($ms2g == 'first') {
            $queryF->limit($limit, $offset);
            $diff = $totalF - $offset;
            if ($diff < $limit) {
                if ($diff > 0) {
                    $queryV->limit($limit - $diff, 0);
                } else {
                    $queryF = null;
                    $queryV->limit($limit, $offset - $totalF);
                }
            } else {
                $queryV = null;
            }
        } else {
            $queryV->limit($limit, $offset);
            $diff = $totalV - $offset;
            if ($diff < $limit) {
                if ($diff > 0) {
                    $queryF->limit($limit - $diff, 0);
                } else {
                    $queryV = null;
                    $queryF->limit($limit, $offset - $totalV);
                }
            } else {
                $queryF = null;
            }
        }

    } else {
        $queryV->limit($limit, $offset);
    }
}


if (!empty($debug)) {
    if ($queryV) {
        $queryV->prepare();
        $modx->log(modX::LOG_LEVEL_ERROR, $queryV->toSQL());
    }

    if ($queryV) {
        $queryV->prepare();
        $modx->log(modX::LOG_LEVEL_ERROR, $queryV->toSQL());
    }
}

$output = '';
$params = array();

$data = array(
    'theme' => $theme,
    'tplRow' => $tplRow,
    'tplTag' => $tplTag,
    'includeTags' => $includeTags,
    'width' => $options['w'],
    'height' => $options['h'],
);

if (!empty($ms2g)) {
    if ($ms2g == 'first') {
        $output = $rvg->renderQuery($queryF, $data, true);
        $output .= $rvg->renderQuery($queryV, $data);
    } else {
        $output = $rvg->renderQuery($queryV, $data);
        $output .= $rvg->renderQuery($queryF, $data, true);
    }
} else {
    $output = $rvg->renderQuery($queryV, $data);
}

$params = array_merge($scriptProperties, array(
    'total' => $total,
    'offset' => $offset ? $offset : $limit,
    'limit' => $limit,
    'ms2gThumb' => $ms2gThumb,
    'resources' => implode(',', $resources),
));

if (empty($ajax) || (empty($offset) && $filter === null)) {
    if ($jquery) $modx->regClientScript($rvg->config['jsUrl'] . 'web/lib/jquery-2.2.4.min.js');
    $rvg->regHheme($theme);
} else if (!empty($ajax) && (!empty($offset) || $filter !== null)) {
    return array(
        'total' => $total,
        'data' => $output,
    );
}



$output = $rvg->getChunk($tpl, array(
    'rows' => $output,
    'theme' => $theme,
    'isMore' => ($ajax && $total > $limit && empty($offset) && $filter === null) ? 1 : 0,
    'tplEmbed' => $tplEmbed,
    'tplTag' => $tplTag,
    'autoPlay' => $autoPlay,
    'params' => $modx->toJSON($params)
));

return $output;