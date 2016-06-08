<?

class News {

    public static function get($offset = 0, $count = 20, $userId = 0, $searchQuery = null, $flagModerView = null, $flagModer = 1, $flagVipRequest = null) {
        $news = array();

        if (!$offset and !$userId and !$searchQuery and $flagModerView === null and $flagModer == 1 and !$flagVipRequest) {
            $news = array_merge($news, self::getVipItems());
        }

        $news = array_merge($news, self::getDefaultItems($offset, (!$offset ? ($count - count($news)) : $count), $userId, $flagModerView, $flagModer, $flagVipRequest));

        return $news;
    }

    public function addOfferToNews($sectionId, $contentId, $data) {
        if (!Registry::get('config')->cacheNews) {
            return false;
        }

        $userData = User::getUserContacts();

        return DB::insert('news', array_merge(array(
            'section_id' => $sectionId,
            'content_id' => $contentId,
            'type' => Site::getSectionsTable($sectionId),
            'user_id' => User::isUser(),
            'user_name' => $userData['name'],
            'flag' => 1,
            'flag_moder' => User::isPostModeration($sectionId) ? 1 : 0,
        ), $data), 1);

        return false;
    }

    public function updateOfferOnNews($sectionId, $contentId, $data) {
        if (!Registry::get('config')->cacheNews) {
            return false;
        }

        return DB::update('news', $data, array(
            'section_id' => $sectionId,
            'content_id' => $contentId
        ));
    }

    public function deleteOfferOnNews($sectionId, $contentId) {
        if (!Registry::get('config')->cacheNews) {
            return false;
        }

        DB::delete('news', array(
            'section_id' => $sectionId,
            'content_id' => $contentId
        ));

        return true;
    }

    private function getDefaultItems($offset = 0, $count = 20, $userId = 0, $flagModerView = null, $flagModer = 1, $flagVipRequest = null) {
        return DB::select('*')->from('news')->where(array(
            'flag_moder' => $flagModer,
            'flag_moder_view' => $flagModerView,
            'flag_vip_add' => $flagVipRequest,
            'flag_show' => 1
        ))->orderBy(array(
            'date_add DESC'
        ))->limit($count, $offset)->getAssoc();
    }

    private function getVipItems() {
        return DB::select('*')->from('news')->where(array(
            'sort_id' => '0'
        ), '>')->orderBy(array(
            'sort_id',
            'RAND()'
        ))->limit(10) ->getAssoc();
    }
}