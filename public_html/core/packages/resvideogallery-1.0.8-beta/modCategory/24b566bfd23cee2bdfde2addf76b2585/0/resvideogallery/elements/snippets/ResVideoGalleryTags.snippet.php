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

$tpl = !empty($tpl) ? $tpl : 'resVideoGalleryTagsTpl';
$tplTag = !empty($tplTag) ? $tplTag : 'resVideoGalleryTagTpl';
$cacheKeyParents = isset($parents) ? md5($parents) : '';
$parents = !empty($parents) ? explode(',', $parents) : array();
$excludeResources = !empty($excludeResources) ? explode(',', $excludeResources) : array();
$expires = !empty($expires) ? $expires : $modx->getOption('cacheExpires', $scriptProperties, 86400);
$selectedTags = isset($_GET['filter']) ? explode(',', $_GET['filter']) : array();

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

$ms2g = isset($ms2g) ? $ms2g : '';
$sortdir = isset($sortdir) ? $sortdir : 'ASC';
$sortby = isset($sortby) ? $sortby : 'tag';
$limit = isset($limit) ? (integer)$limit : 0;
$offset = isset($offset) ? (integer)$offset : 0;
$sortbyEscaped = !empty($sortbyEscaped) ? true : false;
$where = !empty($where) ? $modx->fromJSON($where) : array();


if (!$q = $rvg->getQueryTags()) return '';
$q->groupby('tag');

if ($ms2g) {
    if ($qF = $rvg->getQueryTags($ms2g))
        $qF->groupby('tag');
}

if (!empty($resources)) $q->where(array('resource_id:IN' => $resources));

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
            $q->sortby($sort, $dir);
            if ($qF) $qF->sortby($sort, $dir);
        }
    }
}

$totalV = $modx->getCount('RvgVideos', $q);
$totalF = $qF ? $modx->getCount('msResourceFile', $qF) : 0;

$total = $totalV + $totalF;

if (!empty($limit)) {
    if (!empty($ms2g)) {
        if ($ms2g == 'first') {
            $qF->limit($limit, $offset);
            $diff = $totalF - $offset;
            if ($diff < $limit) {
                if ($diff > 0) {
                    $q->limit($limit - $diff, 0);
                } else {
                    $qF = null;
                    $q->limit($limit, $offset - $totalF);
                }
            } else {
                $q = null;
            }
        } else {
            $q->limit($limit, $offset);
            $diff = $totalV - $offset;
            if ($diff < $limit) {
                if ($diff > 0) {
                    $qF->limit($limit - $diff, 0);
                } else {
                    $q = null;
                    $qF->limit($limit, $offset - $totalV);
                }
            } else {
                $qF = null;
            }
        }

    } else {
        $q->limit($limit, $offset);
    }
}
if (!empty($debug)) {
    if ($q) {
        $q->prepare();
        $modx->log(modX::LOG_LEVEL_ERROR, $q->toSQL());
    }

    if ($qF) {
        $qF->prepare();
        $modx->log(modX::LOG_LEVEL_ERROR, $qF->toSQL());
    }
}

$out = '';
$tags = array();

if (!empty($ms2g)) {
    if ($ms2g == 'first') {
        $tags = $rvg->executeQueryTags($qF);
        $tags = array_merge($tags, $rvg->executeQueryTags($q));
    } else {
        $tags = $rvg->executeQueryTags($q);
        $out .= array_merge($tags, $rvg->executeQueryTags($qF));
    }
} else {
    $tags = $rvg->executeQueryTags($q);
}

$verify = array();

if ($tags) {
    foreach ($tags as $idx => $tag) {
        if (!$ms2g || !in_array($tag['tag'], $verify)) {
            $verify[] = $tag['tag'];
            $tag['active'] = 0;
            if (!empty($selectedTags) && in_array($tag['tag'], $selectedTags)) $tag['active'] = 1;
            $out .= $rvg->getChunk($tplTag, $tag);
        }
    }
}

return $out ? $rvg->getChunk($tpl, array('tags' => $out)) : '';