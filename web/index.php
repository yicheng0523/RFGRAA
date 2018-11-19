<?php

/**
 * Copyright 2016 LINE Corporation
 *
 * LINE Corporation licenses this file to you under the Apache License,
 * version 2.0 (the "License"); you may not use this file except in compliance
 * with the License. You may obtain a copy of the License at:
 *
 *   https://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */

require_once('./LINEBotTiny.php');



$channelAccessToken = getenv('LINE_CHANNEL_ACCESSTOKEN');
$channelSecret = getenv('LINE_CHANNEL_SECRET');
$googledataspi = "https://spreadsheets.google.com/feeds/list/1ARv7PRmjKrHXxMpdFwuNvwPshXs9hhBTzKJUsMcYytg/od6/public/values?alt=json";

$client = new LINEBotTiny($channelAccessToken, $channelSecret);
foreach ($client->parseEvents() as $event) {
    switch ($event['type']) {
        case 'message':
            $message = $event['message'];
            
            $json = file_get_contents($googledataspi);
            $data = json_decode($json, true);
            $result = array();
            foreach ($data['feed']['entry'] as $item) {
                $keywords = explode(',', $item['gsx$keyword']['$t']);
                foreach ($keywords as $keyword) {
                    if (mb_strpos($message['text'], $keyword) !== false) {
                        $candidate = array(
                            'thumbnailImageUrl' => $item['gsx$photourl']['$t'],
                            'title' => $item['gsx$title']['$t'],
                            'text' => $item['gsx$text']['$t'],
                            'actions' => array(
                                array(
                                    'type' => 'uri',
                                    'label' => $item['gsx$urltxt']['$t'],
                                    'uri' => $item['gsx$url']['$t'],
                                    ),
                                ),
                            );
                        array_push($result, $candidate);
                    }
                }
            }
            switch ($message['type']) {
                case 'text':
                    $client->replyMessage(array(
                        'replyToken' => $event['replyToken'],
                        'messages' => array(
                            array(
                                'type' => 'text',
                                'text' => '小助理發現了關鍵字，提供你下列資訊：',
                            ),
                            array(
                                'type' => 'template',
                                'altText' => '彩飄盟小助理資訊',
                                'template' => array(
                                    'type' => 'carousel',
                                    'columns' => $result,
                                ),
                            ),

                        ),
                    ));
            }

            
            switch ($message['type']) {
                case 'text':
/**
==============================
歡迎設定
==============================
*/
if (strtolower($message['text']) == "歡迎新朋友" || $message['text'] == "歡迎"){
    $client->replyMessage(array(
        'replyToken' => $event['replyToken'],
        'messages' => array(
            array(
                'type' => 'text', // 訊息類型 (文字)
                'text' => '你好！這裡是「彩飄性別權益推動聯盟」，我是彩飄盟小助理，有問題歡迎隨時用「小助理」或「彩飄盟」呼叫我唷！' // 回復訊息
            ),
            array(
                'type' => 'text', // 訊息類型 (文字)
                'text' => '在開始之前，請先閱讀群組公約～' // 回復訊息
            ),
            array(
                'type' => 'text', // 訊息類型 (文字)
                'text' => '本群組的設立主要用途在於推廣與討論【性別平等教育】相關議題，以及【婚姻平權】等活動的推動；因此群組包含了所有關心這些議題與活動的人，請抱持感恩及相互尊重的心，和我們一起為性別平等及婚姻平權，盡一份心力！

下列事項更請你留意：
★本群組【非】交友、約砲及色情平台
★本群組【非】專屬男同性戀族群
★本群組包含了所有的性別族群，請學習【相互尊重】
★【嚴禁】無關性平、婚平等政治議題
★【嚴禁】非團隊官方公告之團購、販售、廣告、商業行為、聚會活動等資訊的發布
★【嚴禁】有私訊騷擾其他群友之行為
★【嚴禁】發布任何使人不適之影音圖像
★記事本為公告性平及婚平資訊用，請勿張貼無關訊息

違反上述規則者：
★第一次團隊會發出警告
★第二次強制退群並設立黑名單封鎖

請尊重支持性平、婚平的朋友們使用這個平台的權利，讓我們共創更友善且無歧視的社會，謝謝你！

和我們一起
成為風，讓彩虹旗飄揚！

彩飄性別權益推動聯盟
總召 連方廷
副總召 曾翊成' // 回復訊息
            ),
            array(
                'type' => 'template', // 訊息類型 (模板)
                'altText' => '歡迎來到彩飄性別權益推動聯盟！', // 替代文字
                'template' => array(
                    'type' => 'carousel', // 類型 (旋轉木馬)
                    'columns' => array(
                        array(
                            'thumbnailImageUrl' => 'https://i.imgur.com/hd32upY.png',
                            'title' => '彩飄性別權益推動聯盟',
                            'text' => '成為風，讓彩虹旗飄揚',
                            'actions' => array(
                                array(
                                    'type' => 'uri',
                                    'label' => '官方網站',
                                    'uri' => 'https://sites.google.com/view/rfgraa'
                                ),
                                array(
                                    'type' => 'uri',
                                    'label' => '粉專點讚，設為搶先看',
                                    'uri' => 'https://www.facebook.com/RFGRAA'
                                ),
                                array(
                                    'type' => 'uri',
                                    'label' => '加入彩飄盟Line@生活圈',
                                    'uri' => 'https://line.me/R/ti/p/@fvk1609b'
                                )
                            )
                        ),
                        array(
                            'thumbnailImageUrl' => 'https://i.imgur.com/hd32upY.png',
                            'title' => '彩虹補給站',
                            'text' => '彩飄盟友善環境',
                            'actions' => array(
                                array(
                                    'type' => 'uri',
                                    'label' => '彩虹補給站名冊',
                                    'uri' => 'https://docs.google.com/spreadsheets/d/e/2PACX-1vTjQTmR2T-mCgAYXveamDLNpE0nmyQd04HwBr_GCHFa7NwCW4ncvDwABnHQ0IymmgqEiyeIIJLtbYvP/pubhtml?gid=0&single=true'
                                ),
                                array(
                                    'type' => 'uri',
                                    'label' => '站點地圖',
                                    'uri' => 'https://drive.google.com/open?id=139uI9wmK6R-MAXnXXGVvzMKztQty2pYK&usp=sharing'
                                ),
                                array(
                                    'type' => 'uri',
                                    'label' => '加入我們成為友善環境',
                                    'uri' => 'https://goo.gl/forms/LrZB4AAJTqkQkn8F3'
                                )
                            )
                        ),
                        array(
                            'thumbnailImageUrl' => 'https://i.imgur.com/hd32upY.png',
                            'title' => 'YouTube頻道',
                            'text' => '成為風，讓彩虹旗飄揚',
                            'actions' => array(
                                array(
                                    'type' => 'uri',
                                    'label' => '彩飄盟官方YouTube頻道',
                                    'uri' => 'https://goo.gl/9YVwr6'
                                ),
                                array(
                                    'type' => 'uri',
                                    'label' => '記得訂閱頻道',
                                    'uri' => 'https://goo.gl/9YVwr6'
                                ),
                                array(
                                    'type' => 'uri',
                                    'label' => '並打開鈴鐺！',
                                    'uri' => 'https://goo.gl/9YVwr6'
                                )
                            )
                        ),
                        array(
                            'thumbnailImageUrl' => 'https://i.imgur.com/hd32upY.png',
                            'title' => '彩飄盟之歌-彩虹旗',
                            'text' => '唱出彩虹旗・世界更美麗',
                            'actions' => array(
                                array(
                                    'type' => 'uri',
                                    'label' => '彩飄盟之歌ＭＶ',
                                    'uri' => 'https://youtu.be/4lF1wbAqLt4'
                                ),
                                array(
                                    'type' => 'uri',
                                    'label' => '彩飄盟之歌線上聽',
                                    'uri' => 'https://streetvoice.com/crimson_math_i31/songs/543314/'
                                ),
                                array(
                                    'type' => 'uri',
                                    'label' => '歌曲原創粉絲專頁',
                                    'uri' => 'https://www.facebook.com/rainbowflagsong/'
                                )
                            )
                        )
                    )
                )
            )
        )
    ));
}
/**
==============================
彩飄盟
==============================
*/
if (strtolower($message['text']) == "彩飄" || $message['text'] == "彩飄盟"){
    $client->replyMessage(array(
        'replyToken' => $event['replyToken'],
        'messages' => array(
            array(
                'type' => 'template', // 訊息類型 (模板)
                'altText' => '歡迎來到彩飄性別權益推動聯盟！', // 替代文字
                'template' => array(
                    'type' => 'carousel', // 類型 (旋轉木馬)
                    'columns' => array(
                        array(
                            'thumbnailImageUrl' => 'https://i.imgur.com/hd32upY.png',
                            'title' => '彩飄性別權益推動聯盟',
                            'text' => '成為風，讓彩虹旗飄揚',
                            'actions' => array(
                                array(
                                    'type' => 'uri',
                                    'label' => '官方網站',
                                    'uri' => 'https://sites.google.com/view/rfgraa'
                                ),
                                array(
                                    'type' => 'uri',
                                    'label' => '粉專點讚，設為搶先看',
                                    'uri' => 'https://www.facebook.com/RFGRAA'
                                ),
                                array(
                                    'type' => 'uri',
                                    'label' => '加入彩飄盟Line@生活圈',
                                    'uri' => 'https://line.me/R/ti/p/@fvk1609b'
                                )
                            )
                        ),
                        array(
                            'thumbnailImageUrl' => 'https://i.imgur.com/hd32upY.png',
                            'title' => '彩虹補給站',
                            'text' => '彩飄盟友善環境',
                            'actions' => array(
                                array(
                                    'type' => 'uri',
                                    'label' => '彩虹補給站名冊',
                                    'uri' => 'https://docs.google.com/spreadsheets/d/e/2PACX-1vTjQTmR2T-mCgAYXveamDLNpE0nmyQd04HwBr_GCHFa7NwCW4ncvDwABnHQ0IymmgqEiyeIIJLtbYvP/pubhtml?gid=0&single=true'
                                ),
                                array(
                                    'type' => 'uri',
                                    'label' => '站點地圖',
                                    'uri' => 'https://drive.google.com/open?id=139uI9wmK6R-MAXnXXGVvzMKztQty2pYK&usp=sharing'
                                ),
                                array(
                                    'type' => 'uri',
                                    'label' => '加入我們成為友善環境',
                                    'uri' => 'https://goo.gl/forms/LrZB4AAJTqkQkn8F3'
                                )
                            )
                        ),
                        array(
                            'thumbnailImageUrl' => 'https://i.imgur.com/hd32upY.png',
                            'title' => 'YouTube頻道',
                            'text' => '成為風，讓彩虹旗飄揚',
                            'actions' => array(
                                array(
                                    'type' => 'uri',
                                    'label' => '彩飄盟官方YouTube頻道',
                                    'uri' => 'https://goo.gl/9YVwr6'
                                ),
                                array(
                                    'type' => 'uri',
                                    'label' => '記得訂閱頻道',
                                    'uri' => 'https://goo.gl/9YVwr6'
                                ),
                                array(
                                    'type' => 'uri',
                                    'label' => '並打開鈴鐺！',
                                    'uri' => 'https://goo.gl/9YVwr6'
                                )
                            )
                        ),
                        array(
                            'thumbnailImageUrl' => 'https://i.imgur.com/hd32upY.png',
                            'title' => '彩飄盟之歌-彩虹旗',
                            'text' => '唱出彩虹旗・世界更美麗',
                            'actions' => array(
                                array(
                                    'type' => 'uri',
                                    'label' => '彩飄盟之歌ＭＶ',
                                    'uri' => 'https://youtu.be/4lF1wbAqLt4'
                                ),
                                array(
                                    'type' => 'uri',
                                    'label' => '彩飄盟之歌線上聽',
                                    'uri' => 'https://streetvoice.com/crimson_math_i31/songs/543314/'
                                ),
                                array(
                                    'type' => 'uri',
                                    'label' => '歌曲原創粉絲專頁',
                                    'uri' => 'https://www.facebook.com/rainbowflagsong/'
                                )
                            )
                        )
                    )
                )
            )
        )
    ));
}
/**
==============================
小助理
==============================
*/
if (strtolower($message['text']) == "小助理" || $message['text'] == "小助理"){
    $client->replyMessage(array(
        'replyToken' => $event['replyToken'],
        'messages' => array(
            array(
                'type' => 'template', // 訊息類型 (模板)
                'altText' => '歡迎來到彩飄性別權益推動聯盟！', // 替代文字
                'template' => array(
                    'type' => 'carousel', // 類型 (旋轉木馬)
                    'columns' => array(
                        array(
                            'thumbnailImageUrl' => 'https://i.imgur.com/hd32upY.png',
                            'title' => '彩飄性別權益推動聯盟',
                            'text' => '成為風，讓彩虹旗飄揚',
                            'actions' => array(
                                array(
                                    'type' => 'uri',
                                    'label' => '官方網站',
                                    'uri' => 'https://sites.google.com/view/rfgraa'
                                ),
                                array(
                                    'type' => 'uri',
                                    'label' => '粉專點讚，設為搶先看',
                                    'uri' => 'https://www.facebook.com/RFGRAA'
                                ),
                                array(
                                    'type' => 'uri',
                                    'label' => '加入彩飄盟Line@生活圈',
                                    'uri' => 'https://line.me/R/ti/p/@fvk1609b'
                                )
                            )
                        ),
                        array(
                            'thumbnailImageUrl' => 'https://i.imgur.com/hd32upY.png',
                            'title' => '彩虹補給站',
                            'text' => '彩飄盟友善環境',
                            'actions' => array(
                                array(
                                    'type' => 'uri',
                                    'label' => '彩虹補給站名冊',
                                    'uri' => 'https://docs.google.com/spreadsheets/d/e/2PACX-1vTjQTmR2T-mCgAYXveamDLNpE0nmyQd04HwBr_GCHFa7NwCW4ncvDwABnHQ0IymmgqEiyeIIJLtbYvP/pubhtml?gid=0&single=true'
                                ),
                                array(
                                    'type' => 'uri',
                                    'label' => '站點地圖',
                                    'uri' => 'https://drive.google.com/open?id=139uI9wmK6R-MAXnXXGVvzMKztQty2pYK&usp=sharing'
                                ),
                                array(
                                    'type' => 'uri',
                                    'label' => '加入我們成為友善環境',
                                    'uri' => 'https://goo.gl/forms/LrZB4AAJTqkQkn8F3'
                                )
                            )
                        ),
                        array(
                            'thumbnailImageUrl' => 'https://i.imgur.com/hd32upY.png',
                            'title' => 'YouTube頻道',
                            'text' => '成為風，讓彩虹旗飄揚',
                            'actions' => array(
                                array(
                                    'type' => 'uri',
                                    'label' => '彩飄盟官方YouTube頻道',
                                    'uri' => 'https://goo.gl/9YVwr6'
                                ),
                                array(
                                    'type' => 'uri',
                                    'label' => '記得訂閱頻道',
                                    'uri' => 'https://goo.gl/9YVwr6'
                                ),
                                array(
                                    'type' => 'uri',
                                    'label' => '並打開鈴鐺！',
                                    'uri' => 'https://goo.gl/9YVwr6'
                                )
                            )
                        ),
                        array(
                            'thumbnailImageUrl' => 'https://i.imgur.com/hd32upY.png',
                            'title' => '彩飄盟之歌-彩虹旗',
                            'text' => '唱出彩虹旗・世界更美麗',
                            'actions' => array(
                                array(
                                    'type' => 'uri',
                                    'label' => '彩飄盟之歌ＭＶ',
                                    'uri' => 'https://youtu.be/4lF1wbAqLt4'
                                ),
                                array(
                                    'type' => 'uri',
                                    'label' => '彩飄盟之歌線上聽',
                                    'uri' => 'https://streetvoice.com/crimson_math_i31/songs/543314/'
                                ),
                                array(
                                    'type' => 'uri',
                                    'label' => '歌曲原創粉絲專頁',
                                    'uri' => 'https://www.facebook.com/rainbowflagsong/'
                                )
                            )
                        )
                    )
                )
            )
        )
    ));
}



                    break;
                
            }
            break;
        default:
            error_log("Unsupporeted event type: " . $event['type']);
            break;
    }
};
