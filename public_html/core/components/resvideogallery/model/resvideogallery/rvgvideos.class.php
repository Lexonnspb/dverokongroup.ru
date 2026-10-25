<?php
class RvgVideos extends xPDOSimpleObject {
    /** @var Rvg $rvg */
    public $rvg;

    public function getRvg() {
        if(!$this->rvg) {
            if (!$this->rvg = $this->xpdo->getService('resvideogallery', 'Rvg', MODX_CORE_PATH . 'components/resvideogallery/model/resvideogallery/')) {
                return null;
            }
        }
        return $this->rvg;
    }

    public function saveThumb() {
        $this->getRvg();
        if (!$this->rvg) {
            return 'Could not load class ResVideoGallery!';
        }

        $targetDir = $this->rvg->config['thumbsPath'] . $this->get('resource_id') . '/';

        if(!is_dir($targetDir)) {
            if (!$this->xpdo->cacheManager->writeTree($targetDir)) {
                $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, '[ResVideoGallery] Could not create directory' . $targetDir);
                return '';
            }
        }

        $src = '';
        $oldThumb = '';
        if($this->choice_thumb) {
            $oldThumb = $this->thumb;
            $src = $this->choice_thumb;
        } else if ($this->primary_thumb) {
            $src =  $this->rvg->loadImageFromUrl($this->primary_thumb);
        }

        if($src) {
            if (!$phpThumb = $this->xpdo->getService('modphpthumb','modPhpThumb', MODX_CORE_PATH . 'model/phpthumb/', array())) {
                return 'Could not load class modPhpThumb!';
            }

            $phpThumb = new modPhpThumb($this->xpdo);
            $phpThumb->initialize();
            $phpThumb->setSourceFilename($src);
            $options = $this->xpdo->fromJSON($this->xpdo->getOption('resvideogallery.thumb_options',null,'{"w":360,"h":270,"q":90,"zc":"1","f":"jpg","bg":"000000"}'));

            if(empty($options['f'])) {
                $options['f']  = 'jpg';
            }

            foreach ($options as $k => $v) {
                $phpThumb->setParameter($k, $v);
            }

            $filename = $this->get('resource_id') . '/' .md5(microtime(true).$src) . '.' . $phpThumb->getParameter('f');
            $this->set('thumb', $filename);
            $dst = $this->rvg->config['thumbsPath'] . $filename;

            /*$this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $dst);
            return true;*/

            if ($phpThumb->GenerateThumbnail()) {
                // imageinterlace($this->phpThumb->gdimg_output, true);
                if (!$phpThumb->renderToFile($dst)) {
                    $this->xpdo->log(modX::LOG_LEVEL_INFO, '[ResVideoGallery] phpThumb messages for "' . $src . '" | '.$dst.' . ' . print_r($phpThumb->debugmessages, 1));
                    return false;
                }
            } else {
                $this->xpdo->log(modX::LOG_LEVEL_ERROR, '[ResVideoGallery] Could not generate thumbnail for "' . $src . '" | '.$dst.'. ' . print_r($phpThumb->debugmessages, 1));
                return false;
            }

            if(!empty($this->choice_thumb) && $oldThumb) {
                $file = $this->rvg->config['thumbsPath'] . $oldThumb;
                if(file_exists($file)) {
                    unlink($file);
                }
            }

        }

        return true;
    }

    /**
     * @param array|string $k
     * @param null $format
     * @param null $formatTemplate
     *
     * @return mixed
     */
    public function get($k, $format = null, $formatTemplate = null) {
        if (strtolower($k) == 'tags') {
            $tags = array();
            $q = $this->xpdo->newQuery('RvgVideosTags', array('video_id' => $this->get('id')));
            $q->select('tag');
            if ($q->prepare() && $q->stmt->execute()) {
                $tags = $q->stmt->fetchAll(PDO::FETCH_COLUMN);
            }
            return $tags;
        }
        else {
            return parent::get($k, $format, $formatTemplate);
        }
    }

    /**
     * @param null $cacheFlag
     *
     * @return bool
     */
    public function save($cacheFlag = null) {
        $this->saveThumb();
        $save = parent::save($cacheFlag);
        $tags = parent::get('tags');

        if (is_array($tags)) {
            $id = $this->get('id');
            $table = $this->xpdo->getTableName('RvgVideosTags');
            $this->xpdo->exec("DELETE FROM {$table} WHERE `video_id` = $id;");

            if (!empty($tags)) {
                $values = array();
                foreach ($tags as $tag) {
                    $tag = trim($tag);
                    if (!empty($tag)) {
                        $values[] = '(' . $id . ',"' . $tag . '")';
                    }
                }
                if (!empty($values)) {
                    $this->xpdo->exec("INSERT INTO {$table} (`video_id`,`tag`) VALUES " . implode(',', $values));
                }
            }
        }
        return $save;
    }

    /**
     * @param array $ancestors
     *
     * @return bool
     */
    public function remove(array $ancestors = array()) {
        if($this->getRvg()) {
            $file = $this->rvg->config['thumbsPath'] . $this->get('thumb');
            if (file_exists($file)) {
                unlink($file);
            } else {
                $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, '[ResVideoGallery] remove thumb. Not find file ' . $file);
            }
        } else {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, '[ResVideoGallery] could not load class ResVideoGallery!');
        }
        return parent::remove($ancestors);
    }

}