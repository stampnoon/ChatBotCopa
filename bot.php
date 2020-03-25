<?php

define('LINE_MESSAGE_CHANNEL_ID', '1653962671');
define('LINE_MESSAGE_CHANNEL_SECRET', 'd2dfde7d8d794de418721245d96de4cc');
define('LINE_MESSAGE_ACCESS_TOKEN', '/K+Rh34f9Gj/yHmNHTZWjoW/AKjzHTKPMVfz7HtX8IpqbsQQ8Ps0sY+w9RkOoL7OcaCLH+VM8dDjC0LYPevRZKBEqjm0iw2+RT8vb91IuPRxw2xiUNLFYh2zmRFHxEBiP/Ev22L+Gl299UB1IQ+cuAdB04t89/1O/w1cDnyilFU=');
define('LINE_USER_ID', 'Ua465cc5346a14189526aec3f177f0433');


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'vendor/autoload.php';
//include 'bot_settings.php';

/// การตั้งค่าเกี่ยวกับ bot ใน LINE Messaging API

use LINE\LINEBot;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;
use LINE\LINEBot\QuickReplyBuilder\QuickReplyMessageBuilder;
use LINE\LINEBot\QuickReplyBuilder\ButtonBuilder\QuickReplyButtonBuilder;
use LINE\LINEBot\TemplateActionBuilder\CameraRollTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\CameraTemplateActionBuilder;
use LINE\LINEBot\MessageBuilder\FlexMessageBuilder;
use LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ContainerBuilder\BubbleContainerBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\BoxComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\ButtonComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\TextComponentBuilder;
use LINE\LINEBot\MessageBuilder\ImagemapMessageBuilder;
use LINE\LINEBot\ImagemapActionBuilder\ImagemapMessageActionBuilder;
use LINE\LINEBot\ImagemapActionBuilder\ImagemapUriActionBuilder;
use LINE\LINEBot\ImagemapActionBuilder\AreaBuilder;
use LINE\LINEBot\MessageBuilder\Imagemap\BaseSizeBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;

$httpClient = new CurlHTTPClient(LINE_MESSAGE_ACCESS_TOKEN);
$bot = new LINEBot($httpClient, array('channelSecret' => LINE_MESSAGE_CHANNEL_SECRET));

$content = file_get_contents('php://input');

$hash = hash_hmac('sha256', $content, LINE_MESSAGE_CHANNEL_SECRET, true);
$signature = base64_encode($hash);

$events = $bot->parseEventRequest($content, $signature);
$eventObj = $events[0];

$eventType = $eventObj->getType();


$userId = NULL;
$sourceId = NULL;
$sourceType = NULL;
$replyToken = NULL;
$replyData = NULL;
$userImage = null;
$eventMessage = NULL;
$eventPostback = NULL;
$eventJoin = NULL;
$eventLeave = NULL;
$eventFollow = NULL;
$eventUnfollow = NULL;
$eventBeacon = NULL;
$eventAccountLink = NULL;
$eventMemberJoined = NULL;
$eventMemberLeft = NULL;

function startsWith($string, $startString)
{
    $len = strlen($startString);
    return (substr($string, 0, $len) === $startString);
}

function endsWith($haystack, $needle)
{
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }

    return (substr($haystack, -$length) === $needle);
}

switch ($eventType) {
    case 'message':
        $eventMessage = true;
        break;
    case 'postback':
        $eventPostback = true;
        break;
    case 'join':
        $eventJoin = true;
        break;
    case 'leave':
        $eventLeave = true;
        break;
    case 'follow':
        $eventFollow = true;
        break;
    case 'unfollow':
        $eventUnfollow = true;
        break;
    case 'beacon':
        $eventBeacon = true;
        break;
    case 'accountLink':
        $eventAccountLink = true;
        break;
    case 'memberJoined':
        $eventMemberJoined = true;
        break;
    case 'memberLeft':
        $eventMemberLeft = true;
        break;
}

if ($eventObj->isUserEvent()) {
    $userId = $eventObj->getUserId();
    $sourceType = "USER";
}

$sourceId = $eventObj->getEventSourceId();

if (is_null($eventLeave) && is_null($eventUnfollow) && is_null($eventMemberLeft)) {
    $replyToken = $eventObj->getReplyToken();
}

//======================================================================================
//============================ Initial object to use ===================================
//======================================================================================

// ----------------------------------------------------------------------------------------- QuickReply
// $textReplyToQuestion = new MessageTemplateActionBuilder(
//     'Text Show',
//     'Text Print'
// );

$textReplyToQuestion = new MessageTemplateActionBuilder(
    'สอบถาม',
    'สอบถาม'
);
$textBackOtherQuestion = new MessageTemplateActionBuilder(
    'ย้อนกลับ',
    'สอบถามเพิ่มเติม'
);
$textReplyToRegister = new MessageTemplateActionBuilder(
    'สมัคร',
    'สมัคร'
);
$textReplyBackRegister = new MessageTemplateActionBuilder(
    'ย้อนกลับ',
    'สมัคร'
);
$textReplyToContact = new MessageTemplateActionBuilder(
    'ติดต่อ',
    'ติดต่อ'
);
$textBackQuestion = new MessageTemplateActionBuilder(
    'ย้อนกลับ',
    'สอบถาม'
);
$textBackPromotion = new MessageTemplateActionBuilder(
    'ย้อนกลับ',
    'โปรโมชั่น'
);
$textBackRecommend = new MessageTemplateActionBuilder(
    'ย้อนกลับ',
    'คำแนะนำ'
);
$textBackGroup = new MessageTemplateActionBuilder(
    'ย้อนกลับ',
    'กลุ่ม'
);
$textBackDeposit = new MessageTemplateActionBuilder(
    'ย้อนกลับ',
    'ฝาก'
);
$textBackRegister = new MessageTemplateActionBuilder(
    'ย้อนกลับ',
    'สมาชิก'
);
$textBackAccount = new MessageTemplateActionBuilder(
    'ย้อนกลับ',
    'บัญชี'
);
$textBackWebsite = new MessageTemplateActionBuilder(
    'ย้อนกลับ',
    'เว็บไซต์'
);
$textBackProblem = new MessageTemplateActionBuilder(
    'ย้อนกลับ',
    'แจ้งปัญหา'
);
$textAddress = new MessageTemplateActionBuilder(
    'กรอกที่อยู่',
    'ต้องการ'
);
$textNotAddress = new MessageTemplateActionBuilder(
    'ไม่ต้องการ',
    'ไม่ต้องการ'
);
$textEditUser = new MessageTemplateActionBuilder(
    'แก้ไขหมายเลขยูส',
    'แก้ไขเลขยูส'
);
$textBackToAddress = new MessageTemplateActionBuilder(
    'ย้อนกลับ',
    'BAddress'
);
$textEditAddress = new MessageTemplateActionBuilder(
    'แก้ไขที่อยู่',
    'แก้ไขที่อยู่'
);

// ----------------------------------------------------------------------------------------- Flex button 

$quickReplyMain = new QuickReplyMessageBuilder(
    array(
        new QuickReplyButtonBuilder($textReplyToQuestion),
        new QuickReplyButtonBuilder($textReplyToRegister),
        new QuickReplyButtonBuilder($textReplyToContact),
    )
);
$quickReplyPromotion = new QuickReplyMessageBuilder(
    array(
        new QuickReplyButtonBuilder($textBackQuestion),
        new QuickReplyButtonBuilder($textReplyToQuestion),
        new QuickReplyButtonBuilder($textReplyToRegister),
        new QuickReplyButtonBuilder($textReplyToContact),
    )
);
$quickReplyRecommend = new QuickReplyMessageBuilder(
    array(
        new QuickReplyButtonBuilder($textBackQuestion),
        new QuickReplyButtonBuilder($textReplyToQuestion),
        new QuickReplyButtonBuilder($textReplyToRegister),
        new QuickReplyButtonBuilder($textReplyToContact),
    )
);
$quickReplyGroup = new QuickReplyMessageBuilder(
    array(
        new QuickReplyButtonBuilder($textBackQuestion),
        new QuickReplyButtonBuilder($textReplyToQuestion),
        new QuickReplyButtonBuilder($textReplyToRegister),
        new QuickReplyButtonBuilder($textReplyToContact),
    )
);
$quickReplyDeposit = new QuickReplyMessageBuilder(
    array(
        new QuickReplyButtonBuilder($textBackQuestion),
        new QuickReplyButtonBuilder($textReplyToQuestion),
        new QuickReplyButtonBuilder($textReplyToRegister),
        new QuickReplyButtonBuilder($textReplyToContact),
    )
);
$quickReplyRegister = new QuickReplyMessageBuilder(
    array(
        new QuickReplyButtonBuilder($textBackQuestion),
        new QuickReplyButtonBuilder($textReplyToQuestion),
        new QuickReplyButtonBuilder($textReplyToRegister),
        new QuickReplyButtonBuilder($textReplyToContact),
    )
);
$quickReplyAccount = new QuickReplyMessageBuilder(
    array(
        new QuickReplyButtonBuilder($textBackQuestion),
        new QuickReplyButtonBuilder($textReplyToQuestion),
        new QuickReplyButtonBuilder($textReplyToRegister),
        new QuickReplyButtonBuilder($textReplyToContact),
    )
);
$quickReplyWebsite = new QuickReplyMessageBuilder(
    array(
        new QuickReplyButtonBuilder($textBackQuestion),
        new QuickReplyButtonBuilder($textReplyToQuestion),
        new QuickReplyButtonBuilder($textReplyToRegister),
        new QuickReplyButtonBuilder($textReplyToContact),
    )
);

$quickReplySubPromotion = new QuickReplyMessageBuilder(
    array(
        new QuickReplyButtonBuilder($textBackPromotion),
        new QuickReplyButtonBuilder($textReplyToQuestion),
        new QuickReplyButtonBuilder($textReplyToRegister),
        new QuickReplyButtonBuilder($textReplyToContact),
    )
);

$quickReplyOtherQuestion = new QuickReplyMessageBuilder(
    array(
        new QuickReplyButtonBuilder($textBackOtherQuestion),
        new QuickReplyButtonBuilder($textReplyToQuestion),
        new QuickReplyButtonBuilder($textReplyToRegister),
        new QuickReplyButtonBuilder($textReplyToContact),
    )
);

$quickReplySubRecommend = new QuickReplyMessageBuilder(
    array(
        new QuickReplyButtonBuilder($textBackRecommend),
        new QuickReplyButtonBuilder($textReplyToQuestion),
        new QuickReplyButtonBuilder($textReplyToRegister),
        new QuickReplyButtonBuilder($textReplyToContact),
    )
);
$quickReplySubGroup = new QuickReplyMessageBuilder(
    array(
        new QuickReplyButtonBuilder($textBackGroup),
        new QuickReplyButtonBuilder($textReplyToQuestion),
        new QuickReplyButtonBuilder($textReplyToRegister),
        new QuickReplyButtonBuilder($textReplyToContact),
    )
);
$quickReplySubDeposit = new QuickReplyMessageBuilder(
    array(
        new QuickReplyButtonBuilder($textBackDeposit),
        new QuickReplyButtonBuilder($textReplyToQuestion),
        new QuickReplyButtonBuilder($textReplyToRegister),
        new QuickReplyButtonBuilder($textReplyToContact),
    )
);
$quickReplySubRegister = new QuickReplyMessageBuilder(
    array(
        new QuickReplyButtonBuilder($textBackRegister),
        new QuickReplyButtonBuilder($textReplyToQuestion),
        new QuickReplyButtonBuilder($textReplyToRegister),
        new QuickReplyButtonBuilder($textReplyToContact),
    )
);
$quickReplySubAccount = new QuickReplyMessageBuilder(
    array(
        new QuickReplyButtonBuilder($textBackAccount),
        new QuickReplyButtonBuilder($textReplyToQuestion),
        new QuickReplyButtonBuilder($textReplyToRegister),
        new QuickReplyButtonBuilder($textReplyToContact)
    )
);
$quickReplySubWebsite = new QuickReplyMessageBuilder(
    array(
        new QuickReplyButtonBuilder($textBackWebsite),
        new QuickReplyButtonBuilder($textReplyToQuestion),
        new QuickReplyButtonBuilder($textReplyToRegister),
        new QuickReplyButtonBuilder($textReplyToContact)
    )
);
$quickReplyProblem = new QuickReplyMessageBuilder(
    array(
        new QuickReplyButtonBuilder($textBackProblem),
        new QuickReplyButtonBuilder($textReplyToQuestion),
        new QuickReplyButtonBuilder($textReplyToRegister),
        new QuickReplyButtonBuilder($textReplyToContact)
    )
);
$quickReplyBackRegister = new QuickReplyMessageBuilder(
    array(
        new QuickReplyButtonBuilder($textReplyBackRegister),
        new QuickReplyButtonBuilder(new CameraTemplateActionBuilder('กล้องถ่ายรูป')),
        new QuickReplyButtonBuilder(new CameraRollTemplateActionBuilder('คลังรูปภาพ'))
    )
);
$quickReplyEditSlip = new QuickReplyMessageBuilder(
    array(
        new QuickReplyButtonBuilder(new CameraRollTemplateActionBuilder('แก้ไขสลิป'))
    )
);
$quickReplyUser = new QuickReplyMessageBuilder(
    array(
        new QuickReplyButtonBuilder($textAddress),
        new QuickReplyButtonBuilder($textNotAddress),
        new QuickReplyButtonBuilder($textEditUser),
    )
);
$quickEditUser = new QuickReplyMessageBuilder(
    array(
        new QuickReplyButtonBuilder($textEditUser),
    )
);
$quickReplyAddress = new QuickReplyMessageBuilder(
    array(
        new QuickReplyButtonBuilder($textBackToAddress),
        new QuickReplyButtonBuilder($textReplyToQuestion),
        new QuickReplyButtonBuilder($textReplyToRegister),
        new QuickReplyButtonBuilder($textReplyToContact)
    )
);
$quickReplyDetailUser = new QuickReplyMessageBuilder(
    array(
        new QuickReplyButtonBuilder($textEditAddress)
        // new QuickReplyButtonBuilder($textReplyToQuestion),
        // new QuickReplyButtonBuilder($textReplyToRegister),
        // new QuickReplyButtonBuilder($textReplyToContact)
    )
);

// ----------------------------------------------------------------------------------------- QuickReply
// ----------------------------------------------------------------------------------------- TextAll

$textPromotion1 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "โปรโมชั่นของแถม

- สมัครสมาชิก 200 รับโบนัส 30% 
- สมัครสมาชิก 300 รับหูฟังบลูทูธ 
- สมัครสมาชิก 500 รับเสื้อฮู้ด หรือ 
หูฟัง P47 Wireless Headphones
- สมัครสมาชิก 1000 รับ POD ไฟฟ้า 
หรือ หูฟัง Redmi Airdots

(ทุกการสมัครอย่าลืมแจ้ง คนแนะนำ เพื่อรับสิทธิ์นะคะ)",
                NULL,
                NULL,
                "md",
                NULL,
                NULL,
                true
            )
        )
    )
);

$textPromotion2 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "โปรโมชั่น 18+

ทุกการสมัคร 200 บาทขึ้นไป เข้ากลุ่มฟรี

-กลุ่มคลิปหลุด
-กลุ่มไลฟ์สดถอดหมด

(แจ้งขอเข้ากลุ่มหลังจากสมัครสมาชิกเรียบร้อยแล้ว)",
                NULL,
                NULL,
                "md",
                NULL,
                NULL,
                true
            )
        )
    )
);

$textOther1 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "ทำเทิร์นเท่าไหร่

- การสมัครครั้งแรกต้องมียอดเล่น 1.5 เท่าของยอดสมัคร สมมุติ พี่ฝากมา 500 บาทต้องมียอดเล่นให้เท่ากับ 750 หรือมากกว่า ถึงจะถอนได้ค่ะ ไม่นับรวมยอดค้างเล่น

- ฝากครั้งต่อไป 1 เท่าปกตินะค่ะ",
                NULL,
                NULL,
                "md",
                NULL,
                NULL,
                true
            )
        )
    )
);

$textOther2 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "ฝาก-ถอนขั้นต่ำเริ่มต้นที่ 100 ค่ะ",
                NULL,
                NULL,
                "md",
                NULL,
                NULL,
                true
            )
        )
    )
);

$textOther3 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "ไม่จำกัดจำนวนครั้งค่ะ",
                NULL,
                NULL,
                "md",
                NULL,
                NULL,
                true
            )
        )
    )
);

$textOther4 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "วิธีการฝากเงิน คลิกลิงค์ได้เลยค่ะ
___________________________________",
                NULL,
                NULL,
                NULL,
                NULL,
                NULL,
                true
            )
        )
    ),
    new BoxComponentBuilder(
        "horizontal",
        array(
            new ButtonComponentBuilder(
                new UriTemplateActionBuilder("วิธีฝากเงิน", "https://www.copa69.com/%e0%b8%a7%e0%b8%b4%e0%b8%98%e0%b8%b5%e0%b8%9d%e0%b8%b2%e0%b8%81%e0%b9%80%e0%b8%87%e0%b8%b4%e0%b8%99-copa69"),
                NULL,
                NULL,
                NULL,
                "primary"
            )
        )
    )
);

$textOther5 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "วิธีการถอนเงิน คลิกลิงค์ได้เลยค่ะ
___________________________________",
                NULL,
                NULL,
                NULL,
                NULL,
                NULL,
                true
            )
        )
    ),
    new BoxComponentBuilder(
        "horizontal",
        array(
            new ButtonComponentBuilder(
                new UriTemplateActionBuilder("วิธีถอนเงิน", "https://www.copa69.com/%e0%b8%a7%e0%b8%b4%e0%b8%98%e0%b8%b5%e0%b8%96%e0%b8%ad%e0%b8%99%e0%b9%80%e0%b8%87%e0%b8%b4%e0%b8%99-copa69"),
                NULL,
                NULL,
                NULL,
                "primary"
            )
        )
    )
);
// $textPromotion4 = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "มีเครดิตฟรีมั้ย ?
// ___________________________________

// เงินที่สมัครสามารถนำไปเล่นในเว็บได้
// เลยและได้ของแถมด้วยนะคะ 
// ___________________________________

// Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
//                 NULL,
//                 NULL,
//                 "md",
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     )
// );

// $textToRecommend = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "คำแนะนำ

// พิมพ์ r ตามด้วยหัวข้อที่ต้องการ เช่น r1
// ___________________________________

// หัวข้อปัญหาหรือเรื่องที่ต้องการสอบถาม
// 1. ใส่คนแนะนำว่าอะไร
// 2. ถ้าชวนเพื่อนมาสมัครจะได้อะไรมั้ย
// ___________________________________

// Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
//                 NULL,
//                 NULL,
//                 "md",
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     )
// );

// $textRecommend1 = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "ใส่คนแนะนำว่าอะไร ?
// ___________________________________

// SL99 แนะนำให้สมัครคะ 
// ___________________________________

// Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
//                 NULL,
//                 NULL,
//                 "md",
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     )
// );

// $textRecommend2 = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "ถ้าชวนเพื่อนมาสมัครพี่จะได้อะไรมั้ย ?
// ___________________________________

// ทางเรามีโปรโมชั่นชวนเพื่อนให้คะ
// ___________________________________

// Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
//                 NULL,
//                 NULL,
//                 "md",
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     )
// );

// $textToGroup = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "กลุ่ม/สูตร

// พิมพ์ g ตามด้วยหัวข้อที่ต้องการ เช่น g1
// ___________________________________

// หัวข้อปัญหาหรือเรื่องที่ต้องการสอบถาม
// 1. มีสูตรโกงบาคาร่าให้มั้ย
// 2. มีกลุ่มวิเคราะบอลด้วยมั้ย
// 3. เล่นบาคาร่ายังไง
// 4. แทงบอลยังไง
// ___________________________________

// Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
//                 NULL,
//                 NULL,
//                 "md",
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     )
// );

$textGroup1 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "สูตรโกงบาคาร่า

- แจ้ง Username และสลิปการโอน
___________________________________",
                NULL,
                NULL,
                NULL,
                NULL,
                NULL,
                true
            )
        )
    ),
    new BoxComponentBuilder(
        "horizontal",
        array(
            new ButtonComponentBuilder(
                new UriTemplateActionBuilder("สูตรบาคาร่า", "http://line.me/ti/p/@tong8"),
                NULL,
                NULL,
                NULL,
                "primary"
            )
        )
    )
);

$textGroup2 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "กลุ่มวิเคราะห์บอล

กลุ่มวิเคราะห์บอลคลิกลิงค์ได้เลยค่ะ
___________________________________",
                NULL,
                NULL,
                NULL,
                NULL,
                NULL,
                true
            )
        )
    ),
    new BoxComponentBuilder(
        "horizontal",
        array(
            new ButtonComponentBuilder(
                new UriTemplateActionBuilder("กลุ่มวิเคราะห์บอล", "https://line.me/ti/g2/fbDC6OmeUzJua6pFerS7GA?utm_source=invitation&utm_medium=link_copy&utm_campaign=default"),
                NULL,
                NULL,
                NULL,
                "primary"
            )
        )
    )
);

$textGroup3 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "กลุ่มนำเล่นบาคาร่า

กลุ่มนำเล่นบาคาร่า คลิกลิงค์ได้เลยค่ะ
___________________________________",
                NULL,
                NULL,
                NULL,
                NULL,
                NULL,
                true
            )
        )
    ),
    new BoxComponentBuilder(
        "horizontal",
        array(
            new ButtonComponentBuilder(
                new UriTemplateActionBuilder("กลุ่มนำเล่นบาคาร่า", "https://line.me/ti/g2/CriEOA1chT2KNumwKbBpaA?utm_source=invitation&utm_medium=link_copy&utm_campaign=default"),
                NULL,
                NULL,
                NULL,
                "primary"
            )
        )
    )
);

$textGroup4 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "วิธีการเข้าเล่นบาคาร่า คลิกลิงค์ได้เลยค่ะ

วิธีการเข้าเล่นบาคาร่า คลิกลิงค์ได้เลยค่ะ
___________________________________",
                NULL,
                NULL,
                NULL,
                NULL,
                NULL,
                true
            )
        )
    ),
    new BoxComponentBuilder(
        "horizontal",
        array(
            new ButtonComponentBuilder(
                new UriTemplateActionBuilder("วิธีเล่นบาคาร่า", "https://www.youtube.com/watch?v=8O8M8R2Kffg&feature=youtu.be"),
                NULL,
                NULL,
                NULL,
                "primary"
            )
        )
    )
);


$textGroup5 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "วิธีการเข้าแทงบอล

วิธีการเข้าแทงบอล คลิกลิงค์ได้เลยค่ะ
___________________________________",
                NULL,
                NULL,
                NULL,
                NULL,
                NULL,
                true
            )
        )
    ),
    new BoxComponentBuilder(
        "horizontal",
        array(
            new ButtonComponentBuilder(
                new UriTemplateActionBuilder("การเล่นบอล", "https://www.youtube.com/channel/UC0j3s6xKcdOX9OFP05W82Bg"),
                NULL,
                NULL,
                NULL,
                "primary"
            )
        )
    )
);

// $textToDeposit = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "ฝาก/ถอน

// พิมพ์ d ตามด้วยหัวข้อที่ต้องการ เช่น d1
// ___________________________________

// หัวข้อปัญหาหรือเรื่องที่ต้องการสอบถาม
// 1. ฝาก/ถอนขั้นต่ำเท่าไหร่
// 2. ครั้งต่อไปฝาก/ถอนยังไง
// 3. ฝาก/ถอนจำกัดครั้งมั้ย ถอนได้เร็วมั้ย
// 4. ถ้าฝากไปแล้วไม่เล่นถอนได้เลยมั้ย
// 5. โอนเงินเสร็จแล้วทำไงต่อ
// ___________________________________

// Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
//                 NULL,
//                 NULL,
//                 "md",
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     )
// );

// $textDeposit3 = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "ครั้งต่อไปฝาก/ถอนยังไง ?
// ___________________________________

// ฝาก/ถอนสามารถทำรายการผ่านหน้า
// เว็บได้เลยค่ะ
// ___________________________________

// Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
//                 NULL,
//                 NULL,
//                 "md",
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     )
// );

// $textDeposit5 = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "ฝาก/ถอน จำกัดครั้งมั้ย ถอนได้เร็วมั้ย ?
// ___________________________________

// ฝากถอนผ่านหน้าเว็บไม่จำกัดจำนวน
// ครั้งฝากถอนภายใน 5 วินาที
// ___________________________________

// Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
//                 NULL,
//                 NULL,
//                 "md",
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     )
// );

// $textDeposit2 = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "ถ้าฝากไปแล้วไม่เล่นถอนได้เลยมั้ย ?
// ___________________________________

// ไม่ได้ค่ะ ต้องมียอดเล่นให้ครบเทิร์น
// ถึงถอนออกได้ค่ะ
// ___________________________________

// Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
//                 NULL,
//                 NULL,
//                 "md",
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     )
// );

// $textDeposit4 = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "โอนเงินเสร็จแล้วทำไงต่อ ?
// ___________________________________

// รอแอดมินตรวจสอบสักครู่นะคะ เสร็จ
// แล้วแอดมินจะส่งเลขยูสเวอร์ให้คะ
// ___________________________________

// Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
//                 NULL,
//                 NULL,
//                 "md",
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     )
// );

// $textToRegister = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "การสมัคร

// พิมพ์ u ตามด้วยหัวข้อที่ต้องการ เช่น u1
// ___________________________________

// หัวข้อปัญหาหรือเรื่องที่ต้องการสอบถาม
// 1. เช้คได้ไหมว่าเคยสมัครไปหรือยัง
// 2. ถ้าเคยสมัครแล้ว แต่จะใช้บันชีแฟน
// สมัครอีกได้ไหม (แฟนนามสกุลเดียวกัน)
// 3. เคยสมัครสมาชิกแล้วสมัครใหม่ได้มั้ย
// 4. สมัครง่ายมั้ย
// 5. สมัครขั้นต่ำเท่าไหร่
// 6. สมัครยังไง
// 7. สมัคร 100 ได้ไหม
// ___________________________________

// Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
//                 NULL,
//                 NULL,
//                 "md",
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     )
// );

// $textRegister1 = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "เช้คได้ไหมว่าเคยสมัครไปหรือยัง ?
// ___________________________________

// ส่งข้อมูลให้แอดมินตรวจสอบได้เลยนะ
// คะถ้าเคยเป็นสมาชิกแล้วแอดมินจะแจ้ง
// เลขยูสให้คะ
// ___________________________________",
//                 NULL,
//                 NULL,
//                 NULL,
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     ),
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new ButtonComponentBuilder(
//                 new UriTemplateActionBuilder("ติดต่อแอดมิน", "https://line.me/R/ti/p/%40519uqyhc"),
//                 NULL,
//                 NULL,
//                 NULL,
//                 "primary"
//             )
//         )
//     )
// );

// $textRegister3 = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "ถ้าเคยสมัครแล้ว แต่จะใช้บัญชีแฟน
// สมัครอีกได้ไหม 
// (แฟนนามสกุลเดียวกัน) ?
// ___________________________________

// รอแอดมินตรวจสอบสักครู่นะคะ เสร็จ
// ได้คะพี่ขอแค่ชื่อคนสมัครกับชื่อบัญชี
// ที่ใช้โอนตรงกันและถ้าชื่อที่เคยสมัคร
// แล้วจะสมัครอีกไม่ได้ค่ะ
// ___________________________________

// Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
//                 NULL,
//                 NULL,
//                 "md",
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     )
// );

// $textRegister5 = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "เคยสมัครสมาชิกแล้วสมัครใหม่ได้มั้ย ?
// ___________________________________

// ไม่ได้ค่ะเพราะ 1 ชื่อสามารถสมัคร
// ได้แค่ 1 ยูสเซอร์เท่านั้นค่ะ
// ___________________________________

// Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
//                 NULL,
//                 NULL,
//                 "md",
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     )
// );

// $textRegister2 = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "สมัครง่ายมั้ย ?
// ___________________________________

// สมัครง่าย เล่นง่าย เล่นในมือถือได้
// ฝากถอนเงินได้ 24 ชม. เลยนะคะ
// ___________________________________

// Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
//                 NULL,
//                 NULL,
//                 "md",
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     )
// );

// $textRegister4 = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "สมัครขั้นต่ำเท่าไหร่ ?
// ___________________________________

// เปิดยูสฝากครั้งแรก 200 บาท ค่ะ
// ฝากครั้งต่อไป 100 บาท ค่ะ
// ___________________________________

// Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
//                 NULL,
//                 NULL,
//                 "md",
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     )
// );

// $textRegister6 = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "สมัครยังไง ?
// ___________________________________

// คลิกเมนูสมัครเพื่อสมัครสมาชิกค่ะ
// สมัครสมาชิกขั้นต่ำ 200 บาท 
// ได้รับโบโบนัสเพิ่ม 30% ค่ะ
// ___________________________________

// Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
//                 NULL,
//                 NULL,
//                 "md",
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     )
// );

// $textRegister7 = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "สมัคร100ได้มั้ย ?
// ___________________________________

// ได้คะ แต่ว่าจะไม่ได้รับโบนัส30%นะคะ
// ___________________________________

// Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
//                 NULL,
//                 NULL,
//                 "md",
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     )
// );

$textToAccount = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "บัญชีผู้ใช้

พิมพ์ a ตามด้วยหัวข้อที่ต้องการ เช่น a1
___________________________________

หัวข้อปัญหาหรือเรื่องที่ต้องการสอบถาม
1. ลืมเลขบันชีต้องทำยังไง
2. ทำไมทำรายการฝากไม่ได้สักที 
___________________________________

Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
                NULL,
                NULL,
                "md",
                NULL,
                NULL,
                true
            )
        )
    )
);

// $textAccount1 = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "ลืมเลขบันชีต้องทำยังไง ?
// ___________________________________

// คลิกลิ้งติดต่อขอเลขบัญชีกับแอดมินได้เลยค่ะ
// ___________________________________",
//                 NULL,
//                 NULL,
//                 NULL,
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     ),
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new ButtonComponentBuilder(
//                 new UriTemplateActionBuilder("ติดต่อแอดมิน", "https://line.me/R/ti/p/%40519uqyhc"),
//                 NULL,
//                 NULL,
//                 NULL,
//                 "primary"
//             )
//         )
//     )
// );

// $textAccount2 = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "ทำไมทำรายการฝากไม่ได้สักที ?
// ___________________________________

// กรอกข้อมูลให้ถูกต้องนะคะ ชื่อบัญชี
// ที่โอน เวลา และยอดเงิน 
// ___________________________________

// Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
//                 NULL,
//                 NULL,
//                 "md",
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     )
// );

// $textToWebsite = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "เกี่ยวกับเว็บไซต์

// พิมพ์ w ตามด้วยหัวข้อที่ต้องการ เช่น w1
// ___________________________________

// หัวข้อปัญหาหรือเรื่องที่ต้องการสอบถาม
// 1. ในเว็บมีอะไรให้เล่นบ้าง
// 2. เข้าเล่นยังไง
// ___________________________________

// Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
//                 NULL,
//                 NULL,
//                 "md",
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     )
// );

$textWebsite1 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "ภายในเว็บมีอะไรบ้าง ?

เล่นทุกอย่างได้ในยูสเดียว
บอล มวย หวย คาสิโน เกมส์ สล๊อต 
มีให้เลือกเล่นครบวงจร",
                NULL,
                NULL,
                "md",
                NULL,
                NULL,
                true
            )
        )
    )
);

$textWebsite2 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "วิธีเข้าหน้าเว็บ ?
                
วิธีเข้าหน้าเว็บ คลิกลิงค์ได้เลยค่ะ
___________________________________",
                NULL,
                NULL,
                NULL,
                NULL,
                NULL,
                true
            )
        )
    ),
    new BoxComponentBuilder(
        "horizontal",
        array(
            new ButtonComponentBuilder(
                new UriTemplateActionBuilder("เข้าสู่เว็บไซต์", "https://www.copa69.com/"),
                NULL,
                NULL,
                NULL,
                "primary"
            )
        )
    )
);

$textProblem1 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "ทำรายการ ฝาก-ถอน ไม่สำเร็จ

- กรุณารอสักครู่ระบบกำลังทารายการตามคิวนะคะ
หรือ สามารถติดต่อแอดมิน คลิกลิงค์ได้เลยค่ะ
___________________________________",
                NULL,
                NULL,
                NULL,
                NULL,
                NULL,
                true
            )
        )
    ),
    new BoxComponentBuilder(
        "horizontal",
        array(
            new ButtonComponentBuilder(
                new UriTemplateActionBuilder("ติดต่อแอดมิน", "https://line.me/R/ti/p/%40519uqyhc"),
                NULL,
                NULL,
                NULL,
                "primary"
            )
        )
    )
);

$textProblem2 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "เช็คว่าเคยสมัครไปหรือยัง ?

สามารถตรวจสอบได้ที่แอดมิน คลิกที่ลิงค์ได้เลยค่ะ
___________________________________",
                NULL,
                NULL,
                NULL,
                NULL,
                NULL,
                true
            )
        )
    ),
    new BoxComponentBuilder(
        "horizontal",
        array(
            new ButtonComponentBuilder(
                new UriTemplateActionBuilder("ติดต่อแอดมิน", "https://line.me/R/ti/p/%40519uqyhc"),
                NULL,
                NULL,
                NULL,
                "primary"
            )
        )
    )
);

$textProblem3 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "ลืม Username

- สามารถแจ้งขอ User ใหม่ได้ค่ะ คลิกที่ลิงค์ได้เลยค่ะ
___________________________________",
                NULL,
                NULL,
                NULL,
                NULL,
                NULL,
                true
            )
        )
    ),
    new BoxComponentBuilder(
        "horizontal",
        array(
            new ButtonComponentBuilder(
                new UriTemplateActionBuilder("ติดต่อแอดมิน", "https://line.me/R/ti/p/%40519uqyhc"),
                NULL,
                NULL,
                NULL,
                "primary"
            )
        )
    )
);


$textDetailPromotion1 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "สมัครสมาชิก 1000 รับ POD ไฟฟ้า
หรือ หูฟัง Redmi Airdots 

คลิกลิงค์เพื่อสมัครได้เลยค่ะ

*อย่าลืมแจ้ง JP99 แนะนำ เพื่อรับสิทธิ์นะคะ* 
*แจ้งรับของแถมจากคนแนะนำด้วยนะคะ*
___________________________________

สมัครเสร็จแล้ว แจ้งสลิปการโอนสมัคร
กลับมาที่นี่เพื่อรับของแถมได้เลยค่ะ",
                NULL,
                NULL,
                NULL,
                NULL,
                NULL,
                true
            )
        )
    ),
    new BoxComponentBuilder(
        "horizontal",
        array(
            new ButtonComponentBuilder(
                new UriTemplateActionBuilder("สมัครโปรโมชั่น", "https://line.me/R/ti/p/%40519uqyhc"),
                NULL,
                NULL,
                NULL,
                "primary"
            )
        )
    )
);
$textDetailPromotion2 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "สมัครสมาชิก 500 รับเสื้อฮู้ด หรือ
หูฟัง P47 Wireless Headphones

คลิกลิงค์เพื่อสมัครได้เลยค่ะ

*อย่าลืมแจ้ง JP99 แนะนำ เพื่อรับสิทธิ์นะคะ* 
*แจ้งรับของแถมจากคนแนะนำด้วยนะคะ*
___________________________________

สมัครเสร็จแล้ว แจ้งสลิปการโอนสมัคร
กลับมาที่นี่เพื่อรับของแถมได้เลยค่ะ",
                NULL,
                NULL,
                NULL,
                NULL,
                NULL,
                true
            )
        )
    ),
    new BoxComponentBuilder(
        "horizontal",
        array(
            new ButtonComponentBuilder(
                new UriTemplateActionBuilder("สมัครโปรโมชั่น", "https://line.me/R/ti/p/%40519uqyhc"),
                NULL,
                NULL,
                NULL,
                "primary"
            )
        )
    )
);
$textDetailPromotion3 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "สมัครสมาชิก 300 รับหูฟังบลูทูธ 

คลิกลิงค์เพื่อสมัครได้เลยค่ะ

*อย่าลืมแจ้ง JP99 แนะนำ เพื่อรับสิทธิ์นะคะ* 
*แจ้งรับของแถมจากคนแนะนำด้วยนะคะ*
___________________________________

สมัครเสร็จแล้ว แจ้งสลิปการโอนสมัคร
กลับมาที่นี่เพื่อรับของแถมได้เลยค่ะ",
                NULL,
                NULL,
                NULL,
                NULL,
                NULL,
                true
            )
        )
    ),
    new BoxComponentBuilder(
        "horizontal",
        array(
            new ButtonComponentBuilder(
                new UriTemplateActionBuilder("สมัครโปรโมชั่น", "https://line.me/R/ti/p/%40519uqyhc"),
                NULL,
                NULL,
                NULL,
                "primary"
            )
        )
    )
);
$textDetailPromotion4 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "สมัครสมาชิก 200 รับโบนัส 30%

คลิกลิ้งเพื่อสมัครได้เลยค่ะ

*อย่าลืมแจ้ง JP99 แนะนำ เพื่อรับสิทธิ์นะคะ* 
*แจ้งรับของแถมจากคนแนะนำด้วยนะคะ*
___________________________________

สมัครเสร็จแล้ว แจ้งสลิปการโอนสมัคร
กลับมาที่นี่เพื่อรับของแถมได้เลยค่ะ",
                NULL,
                NULL,
                NULL,
                NULL,
                NULL,
                true
            )
        )
    ),
    new BoxComponentBuilder(
        "horizontal",
        array(
            new ButtonComponentBuilder(
                new UriTemplateActionBuilder("สมัครโปรโมชั่น", "https://line.me/R/ti/p/%40519uqyhc"),
                NULL,
                NULL,
                NULL,
                "primary"
            )
        )
    )
);
// $textDetailPromotion5 = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "โปรโมชั่นที่ลูกค้าเลือก คือ
// ___________________________________

// สมัคร 1000 บาท ได้รับโทรศัพท์จิ๋ว 

// คลิกลิ้งเพื่อสมัครได้เลยค่ะ

// หลังสมัครเสร็จแนบสลิปพร้อมเลข
// ๊User ที่นี่ด้วยนะคะ
// ___________________________________
// Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
//                 NULL,
//                 NULL,
//                 NULL,
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     ),
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new ButtonComponentBuilder(
//                 new UriTemplateActionBuilder("สมัครโปรโมชั่น", "https://line.me/R/ti/p/%40519uqyhc"),
//                 NULL,
//                 NULL,
//                 NULL,
//                 "primary"
//             )
//         )
//     )
// );
// $textDetailPromotion6 = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "โปรโมชั่นที่ลูกค้าเลือก คือ
// ___________________________________

// สมัคร 500 บาท ได้รับเสื้อบอล EURO 

// คลิกลิ้งเพื่อสมัครได้เลยค่ะ

// หลังสมัครเสร็จแนบสลิปพร้อมเลข
// ๊User ที่นี่ด้วยนะคะ
// ___________________________________
// Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
//                 NULL,
//                 NULL,
//                 NULL,
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     ),
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new ButtonComponentBuilder(
//                 new UriTemplateActionBuilder("สมัครโปรโมชั่น", "https://line.me/R/ti/p/%40519uqyhc"),
//                 NULL,
//                 NULL,
//                 NULL,
//                 "primary"
//             )
//         )
//     )
// );
// $textDetailPromotion7 = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "โปรโมชั่นที่ลูกค้าเลือก คือ
// ___________________________________

// สมัคร 500 บาท ได้รับเสื้อฮูด Nike 

// คลิกลิ้งเพื่อสมัครได้เลยค่ะ

// หลังสมัครเสร็จแนบสลิปพร้อมเลข
// ๊User ที่นี่ด้วยนะคะ
// ___________________________________
// Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
//                 NULL,
//                 NULL,
//                 NULL,
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     ),
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new ButtonComponentBuilder(
//                 new UriTemplateActionBuilder("สมัครโปรโมชั่น", "https://line.me/R/ti/p/%40519uqyhc"),
//                 NULL,
//                 NULL,
//                 NULL,
//                 "primary"
//             )
//         )
//     )
// );
// $textDetailPromotion8 = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "โปรโมชั่นที่ลูกค้าเลือก คือ
// ___________________________________

// สมัคร 500 บาท ได้รับSmart Watch

// คลิกลิ้งเพื่อสมัครได้เลยค่ะ

// หลังสมัครเสร็จแนบสลิปพร้อมเลข
// ๊User ที่นี่ด้วยนะคะ
// ___________________________________
// Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
//                 NULL,
//                 NULL,
//                 NULL,
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     ),
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new ButtonComponentBuilder(
//                 new UriTemplateActionBuilder("สมัครโปรโมชั่น", "https://line.me/R/ti/p/%40519uqyhc"),
//                 NULL,
//                 NULL,
//                 NULL,
//                 "primary"
//             )
//         )
//     )
// );
// $textDetailPromotion9 = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "โปรโมชั่นที่ลูกค้าเลือก คือ
// ___________________________________

// สมัคร 500 บาท ได้รับลำโพง Bluetooth Mini

// คลิกลิ้งเพื่อสมัครได้เลยค่ะ

// หลังสมัครเสร็จแนบสลิปพร้อมเลข
// ๊User ที่นี่ด้วยนะคะ
// ___________________________________
// Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
//                 NULL,
//                 NULL,
//                 NULL,
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     ),
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new ButtonComponentBuilder(
//                 new UriTemplateActionBuilder("สมัครโปรโมชั่น", "https://line.me/R/ti/p/%40519uqyhc"),
//                 NULL,
//                 NULL,
//                 NULL,
//                 "primary"
//             )
//         )
//     )
// );
// $textDetailPromotion10 = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "โปรโมชั่นที่ลูกค้าเลือก คือ
// ___________________________________

// สมัคร 500 บาท ได้รับหูฟัง Bluetooth 

// คลิกลิ้งเพื่อสมัครได้เลยค่ะ

// หลังสมัครเสร็จแนบสลิปพร้อมเลข
// ๊User ที่นี่ด้วยนะคะ
// ___________________________________
// Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
//                 NULL,
//                 NULL,
//                 NULL,
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     ),
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new ButtonComponentBuilder(
//                 new UriTemplateActionBuilder("สมัครโปรโมชั่น", "https://line.me/R/ti/p/%40519uqyhc"),
//                 NULL,
//                 NULL,
//                 NULL,
//                 "primary"
//             )
//         )
//     )
// );
// $textDetailPromotion11 = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "โปรโมชั่นที่ลูกค้าเลือก คือ
// ___________________________________

// สมัคร 300 บาท ได้รับลำโพงสโมสรฟุตบอลโลก

// คลิกลิ้งเพื่อสมัครได้เลยค่ะ

// หลังสมัครเสร็จแนบสลิปพร้อมเลข
// ๊User ที่นี่ด้วยนะคะ
// ___________________________________
// Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
//                 NULL,
//                 NULL,
//                 NULL,
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     ),
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new ButtonComponentBuilder(
//                 new UriTemplateActionBuilder("สมัครโปรโมชั่น", "https://line.me/R/ti/p/%40519uqyhc"),
//                 NULL,
//                 NULL,
//                 NULL,
//                 "primary"
//             )
//         )
//     )
// );
// $textDetailPromotion12 = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "โปรโมชั่นที่ลูกค้าเลือก คือ
// ___________________________________

// สมัคร 300 บาท ได้รับกระเป๋าสะพาย
// ข้างลายสโมสรฟุตบอลโลก 

// คลิกลิ้งเพื่อสมัครได้เลยค่ะ

// หลังสมัครเสร็จแนบสลิปพร้อมเลข
// ๊User ที่นี่ด้วยนะคะ
// ___________________________________
// Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
//                 NULL,
//                 NULL,
//                 NULL,
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     ),
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new ButtonComponentBuilder(
//                 new UriTemplateActionBuilder("สมัครโปรโมชั่น", "https://line.me/R/ti/p/%40519uqyhc"),
//                 NULL,
//                 NULL,
//                 NULL,
//                 "primary"
//             )
//         )
//     )
// );
// $textDetailPromotion13 = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "โปรโมชั่นที่ลูกค้าเลือก คือ
// ___________________________________

// สมัคร 300 บาท ได้รับGame Handle 

// คลิกลิ้งเพื่อสมัครได้เลยค่ะ

// หลังสมัครเสร็จแนบสลิปพร้อมเลข
// ๊User ที่นี่ด้วยนะคะ
// ___________________________________
// Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
//                 NULL,
//                 NULL,
//                 NULL,
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     ),
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new ButtonComponentBuilder(
//                 new UriTemplateActionBuilder("สมัครโปรโมชั่น", "https://line.me/R/ti/p/%40519uqyhc"),
//                 NULL,
//                 NULL,
//                 NULL,
//                 "primary"
//             )
//         )
//     )
// );
// $textDetailPromotion14 = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "โปรโมชั่นที่ลูกค้าเลือก คือ
// ___________________________________

// สมัครฝาก 200 รับโบนัส 30%

// คลิกลิ้งเพื่อสมัครได้เลยค่ะ

// หลังสมัครเสร็จแนบสลิปพร้อมเลข
// ๊User ที่นี่ด้วยนะคะ
// ___________________________________
// Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
//                 NULL,
//                 NULL,
//                 NULL,
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     ),
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new ButtonComponentBuilder(
//                 new UriTemplateActionBuilder("สมัครโปรโมชั่น", "https://line.me/R/ti/p/%40519uqyhc"),
//                 NULL,
//                 NULL,
//                 NULL,
//                 "primary"
//             )
//         )
//     )
// );

$textGetUser = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "กรุณากรอก User ด้วยนะคะ

ตัวอย่าง user_co69ag9999
เช่น user_sa894567415",
                NULL,
                NULL,
                "md",
                NULL,
                NULL,
                true
            )
        )
    )
);

// $textToAddress = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "กรุณากรอกที่อยู่เพื่อทางเราจะทำ
// การจัดส่งสินค้า โดยลูกค้าเลือกที่
// จะกรอกหรือไม่กรอกก็ได้ค่ะ
// ___________________________________

// Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
//                 NULL,
//                 NULL,
//                 "md",
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     )
// );

// $textNotAddress = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "Copa69 ขอขอบคุณที่ใช้บริการค่ะ....
// ___________________________________",
//                 NULL,
//                 NULL,
//                 "md",
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     )
// );

$textAddress = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "กรุณากรอกที่อยู่ให้ครบถ้วนสมบูรณ์
*กรุณานำหน้าประโยคด้วยคำว่า 'ที่อยู่'
___________________________________

ตัวอย่าง: ที่อยู่ 111 หมู่1 ต.ตำบล
อ.อำเภอ จ.จังหวัด 11111",
                NULL,
                NULL,
                "md",
                NULL,
                NULL,
                true
            )
        )
    )
);

$textDetailUser = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "กรอกชื่อและเบอร์โทรเพื่อติดต่อ
*กรุณานำหน้าประโยคด้วยคำว่า 'เพิ่มเติม'
___________________________________

ตัวอย่าง: เพิ่มเติม นายเอ นามสม 0812345678",
                NULL,
                NULL,
                "md",
                NULL,
                NULL,
                true
            )
        )
    )
);
$textSendAddress = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "ขอบคุณค่ะ เดี๋ยวทางเราจะดำเนินการ
ส่งของตามที่อยู่นี้นะคะ..
___________________________________

Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
                NULL,
                NULL,
                "md",
                NULL,
                NULL,
                true
            )
        )
    )
);
$textNotKeyword = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "คุณพิมพ์ไม่ตรง Keyword ที่ต้องการค่ะ
กรุณาเลือกหัวข้อที่ต้องการและทำตาม
ขั้นตอนค่ะ
___________________________________

Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
                NULL,
                NULL,
                "md",
                NULL,
                NULL,
                true
            )
        )
    )
);
$textContact = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "หากมีข้อสงสัยนอกเหนือจากที่กล่าว
มาลูกค้าสามารถติดต่อกับ Admin ได้โดยตรงค่ะ

คลิกที่ลิ้งเพื่อติดต่อ Admin
___________________________________
Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
                NULL,
                NULL,
                NULL,
                NULL,
                NULL,
                true
            )
        )
    ),
    new BoxComponentBuilder(
        "horizontal",
        array(
            new ButtonComponentBuilder(
                new UriTemplateActionBuilder("ติดต่อ Admin", "https://line.me/R/ti/p/%40519uqyhc"),
                NULL,
                NULL,
                NULL,
                "primary"
            )
        )
    )
);

//======================================================================================
//============================== Working condition =====================================
//======================================================================================

// ----------------------------------------------------------------------------------------- TextAll

if (!is_null($events)) {
    $userMessage = strtolower($userMessage);
    if (!is_null($eventFollow)) {
        $imageMain = 'https://i.ibb.co/q7GNG0k/main.jpg?_ignore=';
        $replyData = new ImagemapMessageBuilder(
            $imageMain,
            'main',
            new BaseSizeBuilder(420, 1040),
            array(
                new ImagemapMessageActionBuilder(
                    'สอบถาม',
                    new AreaBuilder(4, 112, 337, 281)
                ),
                new ImagemapMessageActionBuilder(
                    'สมัคร',
                    new AreaBuilder(348, 112, 340, 283)
                ),
                new ImagemapMessageActionBuilder(
                    'ติดต่อ',
                    new AreaBuilder(693, 112, 338, 283)
                ),
            )
        );
    }

    if (!is_null($eventMessage)) {
        $typeMessage = $eventObj->getMessageType();
        $idMessage = $eventObj->getMessageId();
        if ($typeMessage == 'text') {
            $userMessage = $eventObj->getText();
        }
        if ($typeMessage == 'image') {
        }
    }

    // ----------------------------------------------------------------------------------------- MainMenu
    switch ($typeMessage) {
        case "text":
            if ($userMessage != null) {
                if ($userMessage == "ย้อนกลับMain" || $userMessage == "Main") {
                    $imageMain = 'https://i.ibb.co/q7GNG0k/main.jpg?_ignore=';
                    $replyData = new ImagemapMessageBuilder(
                        $imageMain,
                        'main',
                        new BaseSizeBuilder(420, 1040),
                        array(
                            new ImagemapMessageActionBuilder(
                                'สอบถาม',
                                new AreaBuilder(4, 112, 337, 281)
                            ),
                            new ImagemapMessageActionBuilder(
                                'สมัคร',
                                new AreaBuilder(348, 112, 340, 283)
                            ),
                            new ImagemapMessageActionBuilder(
                                'ติดต่อ',
                                new AreaBuilder(693, 112, 338, 283)
                            ),
                        )
                    );
                } else if ($userMessage == "สอบถาม" || $userMessage == "q" || $userMessage == "Q" || $userMessage == "ย้อนกลับQuestion") {
                    $imageMapUrl = 'https://i.ibb.co/1df4VCD/question.jpg?_ignore=';
                    $replyData = new ImagemapMessageBuilder(
                        $imageMapUrl,
                        'question',
                        new BaseSizeBuilder(510, 1040),
                        array(
                            new ImagemapMessageActionBuilder(
                                'โปรโมชั่น',
                                new AreaBuilder(4, 157, 515, 108)
                            ),
                            new ImagemapMessageActionBuilder(
                                'เว็บไซต์',
                                new AreaBuilder(4, 266, 515, 108)
                            ),
                            new ImagemapMessageActionBuilder(
                                'แจ้งปัญหา',
                                new AreaBuilder(4, 377, 515, 108)
                            ),
                            new ImagemapMessageActionBuilder(
                                'กลุ่ม',
                                new AreaBuilder(523, 157, 515, 108)
                            ),
                            new ImagemapMessageActionBuilder(
                                'สอบถามเพิ่มเติม',
                                new AreaBuilder(523, 266, 515, 108)
                            ),
                            new ImagemapMessageActionBuilder(
                                'ย้อนกลับMain',
                                new AreaBuilder(523, 379, 515, 108)
                            ),
                            // new ImagemapMessageActionBuilder(
                            //     'บัญชี',
                            //     new AreaBuilder(523, 379, 515, 108)
                            // ),
                            // new ImagemapMessageActionBuilder(
                            //     'สมาชิก',
                            //     new AreaBuilder(523, 266, 515, 108)
                            // ),
                            // new ImagemapMessageActionBuilder(
                            //     'ฝาก',
                            //     new AreaBuilder(4, 377, 515, 108)
                            // ),
                            // new ImagemapMessageActionBuilder(
                            //     'คำแนะนำ',
                            //     new AreaBuilder(7, 631, 510, 139)
                            // ),
                        ),
                        $quickReplyMain
                    );
                }
                //------------------------------------------------------------------------------------------ สอบถามเพิ่มเติม

                else if ($userMessage == "สอบถามเพิ่มเติม") {
                    $imagePromotion = 'https://i.ibb.co/GF6dMvw/q-other.jpg?_ignore=';
                    $replyData = new ImagemapMessageBuilder(
                        $imagePromotion,
                        'Other',
                        new BaseSizeBuilder(610, 1040),
                        array(
                            new ImagemapMessageActionBuilder(
                                'คำถาม:เพิ่มเติม1',
                                new AreaBuilder(4, 151, 513, 108)
                            ),
                            new ImagemapMessageActionBuilder(
                                'คำถาม:เพิ่มเติม3',
                                new AreaBuilder(4, 259, 513, 108)
                            ),
                            new ImagemapMessageActionBuilder(
                                'คำถาม:เพิ่มเติม5',
                                new AreaBuilder(4, 370, 513, 108)
                            ),
                            new ImagemapMessageActionBuilder(
                                'ย้อนกลับQuestion',
                                new AreaBuilder(4, 480, 513, 108)
                            ),
                            new ImagemapMessageActionBuilder(
                                'คำถาม:เพิ่มเติม2',
                                new AreaBuilder(523, 151, 513, 108)
                            ),
                            new ImagemapMessageActionBuilder(
                                'คำถาม:เพิ่มเติม4',
                                new AreaBuilder(523, 260, 513, 108)
                            ),
                            new ImagemapMessageActionBuilder(
                                'ย้อนกลับMain',
                                new AreaBuilder(523, 370, 513, 108)
                            ),
                        )
                    );
                } else if ($userMessage == "คำถาม:เพิ่มเติม1") {
                    $replyData = new FlexMessageBuilder("Turn", $textOther1, $quickReplyOtherQuestion);
                } else if ($userMessage == "คำถาม:เพิ่มเติม2") {
                    $replyData = new FlexMessageBuilder("Turn", $textOther2, $quickReplyOtherQuestion);
                } else if ($userMessage == "คำถาม:เพิ่มเติม3") {
                    $replyData = new FlexMessageBuilder("Turn", $textOther3, $quickReplyOtherQuestion);
                } else if ($userMessage == "คำถาม:เพิ่มเติม4") {
                    $replyData = new FlexMessageBuilder("Turn", $textOther4, $quickReplyOtherQuestion);
                } else if ($userMessage == "คำถาม:เพิ่มเติม5") {
                    $replyData = new FlexMessageBuilder("Turn", $textOther5, $quickReplyOtherQuestion);
                }

                // ----------------------------------------------------------------------------------------- Promotion

                else if ($userMessage == "โปรโมชั่น") {
                    $imagePromotion = 'https://i.ibb.co/60LLDdr/q-promotion.jpg?_ignore=';
                    $replyData = new ImagemapMessageBuilder(
                        $imagePromotion,
                        'qpromotion',
                        new BaseSizeBuilder(386, 1040),
                        array(
                            new ImagemapMessageActionBuilder(
                                'คำถาม:โปรโมชั่น1',
                                new AreaBuilder(5, 145, 513, 108)
                            ),
                            new ImagemapMessageActionBuilder(
                                'ย้อนกลับQuestion',
                                new AreaBuilder(5, 255, 513, 108)
                            ),
                            new ImagemapMessageActionBuilder(
                                'คำถาม:โปรโมชั่น2',
                                new AreaBuilder(523, 145, 513, 108)
                            ),
                            new ImagemapMessageActionBuilder(
                                'ย้อนกลับMain',
                                new AreaBuilder(523, 255, 513, 108)
                            ),
                        )
                    );
                } else if ($userMessage == "คำถาม:โปรโมชั่น1") {
                    $replyData = new FlexMessageBuilder("Promotion", $textPromotion1, $quickReplySubPromotion);
                    // $replyData = new ImagemapMessageBuilder(
                    //     'https://i.ibb.co/Dg7r1Rp/Npromotion.jpg?_ignore=',
                    //     'register',
                    //     new BaseSizeBuilder(1040, 1040),
                    //     array(
                    //         new ImagemapMessageActionBuilder(
                    //             'โปร1000บาท',
                    //             new AreaBuilder(11, 91, 1020, 232)
                    //         ),
                    //         new ImagemapMessageActionBuilder(
                    //             'โปร500บาท',
                    //             new AreaBuilder(11, 329, 1020, 232)
                    //         ),
                    //         new ImagemapMessageActionBuilder(
                    //             'โปร300บาท',
                    //             new AreaBuilder(11, 561, 1020, 232)
                    //         ),
                    //         new ImagemapMessageActionBuilder(
                    //             'โปร200บาท',
                    //             new AreaBuilder(11, 800, 1020, 232)
                    //         ),
                    //     ),
                    //     $quickReplySubPromotion
                    // );
                } else if ($userMessage == "คำถาม:โปรโมชั่น2" || $userMessage == "โปรโมชั่น18+") {
                    $replyData = new FlexMessageBuilder("Promotion18+", $textPromotion2, $quickReplySubPromotion);
                    // $replyData = new ImagemapMessageBuilder(
                    //     'https://i.ibb.co/kMgHs2J/Ads.jpg?_ignore=',
                    //     '18+',
                    //     new BaseSizeBuilder(1040, 1040),
                    //     array(
                    //         // new ImagemapMessageActionBuilder(
                    //         //     'เข้ากลุ่ม',
                    //         //     new AreaBuilder(344, 898, 345, 83)
                    //         // ), 
                    //         // new ImagemapUriActionBuilder(
                    //         //     'https://line.me/R/ti/p/%40519uqyhc',
                    //         //     new AreaBuilder(344, 898, 345, 83)
                    //         // )
                    //     ),
                    //     $quickReplySubPromotion
                    // );
                }

                // ----------------------------------------------------------------------------------------- Website

                else if ($userMessage == "เว็บไซต์") {
                    $imageWebsite = 'https://i.ibb.co/fSNv1Mq/q-website.jpg?_ignore=';
                    $replyData = new ImagemapMessageBuilder(
                        $imageWebsite,
                        'website',
                        new BaseSizeBuilder(400, 1040),
                        array(
                            new ImagemapMessageActionBuilder(
                                'คำถาม:เว็บ1',
                                new AreaBuilder(4, 145, 515, 108)
                            ),
                            new ImagemapMessageActionBuilder(
                                'ย้อนกลับQuestion',
                                new AreaBuilder(4, 255, 515, 108)
                            ),
                            new ImagemapMessageActionBuilder(
                                'คำถาม:เว็บ2',
                                new AreaBuilder(522, 145, 515, 108)
                            ),
                            new ImagemapMessageActionBuilder(
                                'ย้อนกลับMain',
                                new AreaBuilder(522, 255, 515, 108)
                            ),
                        )
                    );
                } else if ($userMessage == "คำถาม:เว็บ1") {
                    $replyData = new FlexMessageBuilder("Web1", $textWebsite1, $quickReplySubWebsite);
                } else if ($userMessage == "คำถาม:เว็บ2") {
                    $replyData = new FlexMessageBuilder("Web2", $textWebsite2, $quickReplySubWebsite);
                }

                // ----------------------------------------------------------------------------------------- Problem
                else if ($userMessage == "แจ้งปัญหา") {
                    $imageWebsite = 'https://i.ibb.co/T0kMpSt/q-problem.jpg?_ignore=';
                    $replyData = new ImagemapMessageBuilder(
                        $imageWebsite,
                        'Problem',
                        new BaseSizeBuilder(506, 1040),
                        array(
                            new ImagemapMessageActionBuilder(
                                'คำถาม:ปัญหา1',
                                new AreaBuilder(4, 149, 515, 108)
                            ),
                            new ImagemapMessageActionBuilder(
                                'คำถาม:ปัญหา3',
                                new AreaBuilder(4, 262, 515, 108)
                            ),
                            new ImagemapMessageActionBuilder(
                                'ย้อนกลับQuestion',
                                new AreaBuilder(4, 373, 515, 108)
                            ),
                            new ImagemapMessageActionBuilder(
                                'คำถาม:ปัญหา2',
                                new AreaBuilder(523, 149, 515, 108)
                            ),
                            new ImagemapMessageActionBuilder(
                                'ย้อนกลับMain',
                                new AreaBuilder(522, 262, 515, 108)
                            ),
                        )
                    );
                } else if ($userMessage == "คำถาม:ปัญหา1") {
                    $replyData = new FlexMessageBuilder("Problem1", $textProblem1, $quickReplyProblem);
                } else if ($userMessage == "คำถาม:ปัญหา2") {
                    $replyData = new FlexMessageBuilder("Problem2", $textProblem2, $quickReplyProblem);
                } else if ($userMessage == "คำถาม:ปัญหา3") {
                    $replyData = new FlexMessageBuilder("Problem3", $textProblem3, $quickReplyProblem);
                }

                // ----------------------------------------------------------------------------------------- Group

                else if ($userMessage == "กลุ่ม") {
                    $imageGroup = 'https://i.ibb.co/3SgJB2J/q-group.jpg?_ignore=';
                    $replyData = new ImagemapMessageBuilder(
                        $imageGroup,
                        'group',
                        new BaseSizeBuilder(610, 1040),
                        array(
                            new ImagemapMessageActionBuilder(
                                'คำถาม:กลุ่ม1',
                                new AreaBuilder(4, 151, 513, 108)
                            ),
                            new ImagemapMessageActionBuilder(
                                'คำถาม:กลุ่ม3',
                                new AreaBuilder(4, 262, 513, 108)
                            ),
                            new ImagemapMessageActionBuilder(
                                'คำถาม:กลุ่ม5',
                                new AreaBuilder(4, 373, 513, 108)
                            ),
                            new ImagemapMessageActionBuilder(
                                'ย้อนกลับQuestion',
                                new AreaBuilder(4, 488, 513, 108)
                            ),
                            new ImagemapMessageActionBuilder(
                                'คำถาม:กลุ่ม2',
                                new AreaBuilder(522, 151, 513, 108)
                            ),
                            new ImagemapMessageActionBuilder(
                                'คำถาม:กลุ่ม4',
                                new AreaBuilder(522, 262, 513, 108)
                            ),
                            new ImagemapMessageActionBuilder(
                                'ย้อนกลับMain',
                                new AreaBuilder(522, 377, 513, 108)
                            ),
                        )
                    );
                } else if ($userMessage == "คำถาม:กลุ่ม1") {
                    $replyData = new FlexMessageBuilder("Flex", $textGroup1, $quickReplySubGroup);
                } else if ($userMessage == "คำถาม:กลุ่ม2") {
                    $replyData = new FlexMessageBuilder("Flex", $textGroup2, $quickReplySubGroup);
                } else if ($userMessage == "คำถาม:กลุ่ม3") {
                    $replyData = new FlexMessageBuilder("Flex", $textGroup3, $quickReplySubGroup);
                } else if ($userMessage == "คำถาม:กลุ่ม4") {
                    $replyData = new FlexMessageBuilder("Flex", $textGroup4, $quickReplySubGroup);
                } else if ($userMessage == "คำถาม:กลุ่ม5") {
                    $replyData = new FlexMessageBuilder("Flex", $textGroup5, $quickReplySubGroup);
                }
                // ----------------------------------------------------------------------------------------- Group

                // ----------------------------------------------------------------------------------------- Promotion
                else if ($userMessage == "สมัคร") {
                    //$imageMapUrl = 'https://www.pic2free.com/uploads/20200319/22aff7616945ae9b1c4079d4501507b60a7b701a.jpg?_ignore=';
                    $imageMapUrl = 'https://i.ibb.co/Dg7r1Rp/Npromotion.jpg?_ignore=';
                    $replyData = new ImagemapMessageBuilder(
                        $imageMapUrl,
                        'register',
                        new BaseSizeBuilder(1040, 1040),
                        array(
                            new ImagemapMessageActionBuilder(
                                'โปร1000บาท',
                                new AreaBuilder(11, 91, 1020, 232)
                            ),
                            new ImagemapMessageActionBuilder(
                                'โปร500บาท',
                                new AreaBuilder(11, 329, 1020, 232)
                            ),
                            new ImagemapMessageActionBuilder(
                                'โปร300บาท',
                                new AreaBuilder(11, 561, 1020, 232)
                            ),
                            new ImagemapMessageActionBuilder(
                                'โปร200บาท',
                                new AreaBuilder(11, 800, 1020, 232)
                            ),
                        ),
                        $quickReplyMain
                    );
                }
                // ----------------------------------------------------------------------------------------- DetailPromotion
                else if ($userMessage == "โปร1000บาท") {
                    $replyData = new FlexMessageBuilder("Pro1000", $textDetailPromotion1, $quickReplyBackRegister);
                } else if ($userMessage == "โปร500บาท") {
                    $replyData = new FlexMessageBuilder("Pro500", $textDetailPromotion2, $quickReplyBackRegister);
                } else if ($userMessage == "โปร300บาท") {
                    $replyData = new FlexMessageBuilder("Pro300", $textDetailPromotion3, $quickReplyBackRegister);
                } else if ($userMessage == "โปร200บาท") {
                    $replyData = new FlexMessageBuilder("Pro200", $textDetailPromotion4, $quickReplyBackRegister);
                }

                // ----------------------------------------------------------------------------------------- Contact
                else if ($userMessage == "ติดต่อ") {
                    $replyData = new FlexMessageBuilder("Contact", $textContact, $quickReplyMain);
                }

                // ----------------------------------------------------------------------------------------- Recommend
                // else if ($userMessage == "คำแนะนำ") {
                //     $imageRecommend = 'https://www.pic2free.com/uploads/20200311/9d45060816145cff9ddf6c2bfd7ae9972fca71da.png?_ignore=';
                //     $replyData = new ImagemapMessageBuilder(
                //         $imageRecommend,
                //         'suggest',
                //         new BaseSizeBuilder(400, 1040),
                //         array(
                //             new ImagemapMessageActionBuilder(
                //                 'คำถาม:คำแนะนำ1',
                //                 new AreaBuilder(5, 146, 511, 105)
                //             ),
                //             new ImagemapMessageActionBuilder(
                //                 'คำถาม:คำแนะนำ2',
                //                 new AreaBuilder(524, 145, 510, 104)
                //             ),
                //             new ImagemapMessageActionBuilder(
                //                 'ย้อนกลับQuestion',
                //                 new AreaBuilder(8, 258, 508, 105)
                //             ),
                //             new ImagemapMessageActionBuilder(
                //                 'ย้อนกลับMain',
                //                 new AreaBuilder(255, 259, 510, 104)
                //             ),
                //         )
                //     );
                // } 
                // else if ($userMessage == "คำถาม:คำแนะนำ1") {
                //     $replyData = new FlexMessageBuilder("Flex", $textRecommend1, $quickReplySubRecommend);
                // } else if ($userMessage == "คำถาม:คำแนะนำ2") {
                //     $replyData = new FlexMessageBuilder("Flex", $textRecommend2, $quickReplySubRecommend);
                // }

                // ----------------------------------------------------------------------------------------- Recommend
                // ----------------------------------------------------------------------------------------- Deposit

                // else if ($userMessage == "ฝาก") {
                //     $imageDeposit = 'https://www.pic2free.com/uploads/20200311/aa0511085a9d1fb2a5cbe58cf308cef4e3b25fe0.png?_ignore=';
                //     $replyData = new ImagemapMessageBuilder(
                //         $imageDeposit,
                //         'withdraw',
                //         new BaseSizeBuilder(500, 1040),
                //         array(
                //             new ImagemapMessageActionBuilder(
                //                 'คำถาม:ฝาก1',
                //                 new AreaBuilder(5, 146, 512, 107)
                //             ),
                //             new ImagemapMessageActionBuilder(
                //                 'คำถาม:ฝาก2',
                //                 new AreaBuilder(520, 145, 514, 105)
                //             ),
                //             new ImagemapMessageActionBuilder(
                //                 'คำถาม:ฝาก3',
                //                 new AreaBuilder(4, 258, 512, 108)
                //             ),
                //             new ImagemapMessageActionBuilder(
                //                 'คำถาม:ฝาก4',
                //                 new AreaBuilder(521, 257, 511, 106)
                //             ),
                //             new ImagemapMessageActionBuilder(
                //                 'คำถาม:ฝาก5',
                //                 new AreaBuilder(5, 370, 510, 108)
                //             ),
                //             new ImagemapMessageActionBuilder(
                //                 'ย้อนกลับQuestion',
                //                 new AreaBuilder(522, 371, 509, 105)
                //             ),
                //         )
                //     );
                // } else if ($userMessage == "คำถาม:ฝาก1") {
                //     $replyData = new FlexMessageBuilder("Flex", $textDeposit1, $quickReplySubDeposit);
                // } else if ($userMessage == "คำถาม:ฝาก2") {
                //     $replyData = new FlexMessageBuilder("Flex", $textDeposit2, $quickReplySubDeposit);
                // } else if ($userMessage == "คำถาม:ฝาก3") {
                //     $replyData = new FlexMessageBuilder("Flex", $textDeposit3, $quickReplySubDeposit);
                // } else if ($userMessage == "คำถาม:ฝาก4") {
                //     $replyData = new FlexMessageBuilder("Flex", $textDeposit4, $quickReplySubDeposit);
                // } else if ($userMessage == "คำถาม:ฝาก5") {
                //     $replyData = new FlexMessageBuilder("Flex", $textDeposit5, $quickReplySubDeposit);
                // }

                // ----------------------------------------------------------------------------------------- Deposit
                // ----------------------------------------------------------------------------------------- Register

                // else if ($userMessage == "สมาชิก") {
                //     $imageRegister = 'https://www.pic2free.com/uploads/20200311/f660861d050ff2a1fe4aa8077b71aad6b18e463f.png?_ignore=';
                //     $replyData = new ImagemapMessageBuilder(
                //         $imageRegister,
                //         'member',
                //         new BaseSizeBuilder(620, 1040),
                //         array(
                //             new ImagemapMessageActionBuilder(
                //                 'คำถาม:สมาชิก1',
                //                 new AreaBuilder(5, 145, 512, 109)
                //             ),
                //             new ImagemapMessageActionBuilder(
                //                 'คำถาม:สมาชิก2',
                //                 new AreaBuilder(520, 147, 514, 105)
                //             ),
                //             new ImagemapMessageActionBuilder(
                //                 'คำถาม:สมาชิก3',
                //                 new AreaBuilder(5, 259, 511, 105)
                //             ),
                //             new ImagemapMessageActionBuilder(
                //                 'คำถาม:สมาชิก4',
                //                 new AreaBuilder(520, 259, 514, 104)
                //             ),
                //             new ImagemapMessageActionBuilder(
                //                 'คำถาม:สมาชิก5',
                //                 new AreaBuilder(5, 371, 511, 105)
                //             ),
                //             new ImagemapMessageActionBuilder(
                //                 'คำถาม:สมาชิก6',
                //                 new AreaBuilder(522, 369, 512, 106)
                //             ),
                //             new ImagemapMessageActionBuilder(
                //                 'คำถาม:สมาชิก7',
                //                 new AreaBuilder(6, 484, 509, 105)
                //             ),
                //             new ImagemapMessageActionBuilder(
                //                 'ย้อนกลับQuestion',
                //                 new AreaBuilder(521, 483, 511, 105)
                //             ),
                //         )
                //     );
                // } else if ($userMessage == "คำถาม:สมาชิก1") {
                //     $replyData = new FlexMessageBuilder("Flex", $textRegister1, $quickReplySubRegister);
                // } else if ($userMessage == "คำถาม:สมาชิก2") {
                //     $replyData = new FlexMessageBuilder("Flex", $textRegister2, $quickReplySubRegister);
                // } else if ($userMessage == "คำถาม:สมาชิก3") {
                //     $replyData = new FlexMessageBuilder("Flex", $textRegister3, $quickReplySubRegister);
                // } else if ($userMessage == "คำถาม:สมาชิก4") {
                //     $replyData = new FlexMessageBuilder("Flex", $textRegister4, $quickReplySubRegister);
                // } else if ($userMessage == "คำถาม:สมาชิก5") {
                //     $replyData = new FlexMessageBuilder("Flex", $textRegister5, $quickReplySubRegister);
                // } else if ($userMessage == "คำถาม:สมาชิก6") {
                //     $replyData = new FlexMessageBuilder("Flex", $textRegister6, $quickReplySubRegister);
                // } else if ($userMessage == "คำถาม:สมาชิก7") {
                //     $replyData = new FlexMessageBuilder("Flex", $textRegister7, $quickReplySubRegister);
                // }

                // ----------------------------------------------------------------------------------------- Register
                // ----------------------------------------------------------------------------------------- Account

                // else if ($userMessage == "บัญชี") {
                //     $imageAccount = 'https://www.pic2free.com/uploads/20200311/49668c2cca3199378b55cb85518433c4c8471dd4.png?_ignore=';
                //     $replyData = new ImagemapMessageBuilder(
                //         $imageAccount,
                //         'account',
                //         new BaseSizeBuilder(400, 1040),
                //         array(
                //             new ImagemapMessageActionBuilder(
                //                 'คำถาม:บัญชี1',
                //                 new AreaBuilder(5, 144, 511, 108)
                //             ),
                //             new ImagemapMessageActionBuilder(
                //                 'คำถาม:บัญชี2',
                //                 new AreaBuilder(521, 143, 512, 110)
                //             ),
                //             new ImagemapMessageActionBuilder(
                //                 'ย้อนกลับQuestion',
                //                 new AreaBuilder(4, 257, 514, 106)
                //             ),
                //             new ImagemapMessageActionBuilder(
                //                 'ย้อนกลับMain',
                //                 new AreaBuilder(521, 259, 511, 104)
                //             ),
                //         )
                //     );
                // } else if ($userMessage == "คำถาม:บัญชี1") {
                //     $replyData = new FlexMessageBuilder("Flex", $textAccount1, $quickReplySubAccount);
                // } else if ($userMessage == "คำถาม:บัญชี2") {
                //     $replyData = new FlexMessageBuilder("Flex", $textAccount2, $quickReplySubAccount);
                // }

                // ----------------------------------------------------------------------------------------- Account

                else if (strstr($userMessage, "แจ้งเลขยูส") == true || strstr($userMessage, "แก้ไขเลขยูส") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textGetUser, $quickReplyEditSlip);
                } else if (strstr($userMessage, "user_") == true || strstr($userMessage, "User_") == true  || strstr($userMessage, "USER_") == true || $userMessage == "แก้ไขที่อยู่") {
                    //$replyData = new FlexMessageBuilder("Flex", $textToAddress, $quickReplyUser);
                    $replyData = new FlexMessageBuilder("Flex", $textAddress, $quickEditUser);
                }
                // else if ($userMessage == "ไม่ต้องการ") {
                //     $replyData = new FlexMessageBuilder("Flex", $textNotAddress, $quickReplyMain);
                // } 
                // else if ($userMessage == "ต้องการ" || $userMessage == "ย้อนกลับAddress") {
                //     $replyData = new FlexMessageBuilder("Flex", $textAddress, $quickReplyAddress);
                // } 
                // else if (strstr($userMessage, "ที่อยู่") == true || strstr($userMessage, "อำเภอ") == true || strstr($userMessage, "อ.") == true || strstr($userMessage, "ตำบล") == true || strstr($userMessage, "ต.") == true || strstr($userMessage, "จังหวัด") == true || strstr($userMessage, "จ.") == true) {
                else if (strstr($userMessage, "ที่อยู่") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textDetailUser, $quickReplyDetailUser);
                } else if (strstr($userMessage, "เพิ่มเติม") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textSendAddress, $quickReplyMain);
                }
                // ========================== Test ===================================
                else if ($userMessage == "img") {
                    $imageMapUrl = 'https://i.ibb.co/Dg7r1Rp/Npromotion.jpg?_ignore=';
                    $replyData = new ImagemapMessageBuilder(
                        $imageMapUrl,
                        'register',
                        new BaseSizeBuilder(1040, 1040),
                        array(
                            new ImagemapMessageActionBuilder(
                                'โปร1000บาท',
                                new AreaBuilder(11, 91, 1020, 232)
                            ),
                            new ImagemapMessageActionBuilder(
                                'โปร500บาท',
                                new AreaBuilder(11, 329, 1020, 232)
                            ),
                            new ImagemapMessageActionBuilder(
                                'โปร300บาท',
                                new AreaBuilder(11, 561, 1020, 232)
                            ),
                            new ImagemapMessageActionBuilder(
                                'โปร200บาท',
                                new AreaBuilder(11, 800, 1020, 232)
                            ),
                        ),
                        $quickReplyMain
                    );
                } else if ($userMessage == "test") {
                    $responseProfile = $bot->getProfile(LINE_USER_ID);
                    $profile = $responseProfile->getJSONDecodedBody();
                    $textReplyMessage = $profile['displayName']; //can get 'displayName', 'userId', 'pictureUrl', 'statusMessage'
                    //$replyData = new TextMessageBuilder($textReplyMessage);
                    $replyData = new TextMessageBuilder('test') + new ImagemapMessageBuilder(
                        'https://i.ibb.co/Dg7r1Rp/Npromotion.jpg?_ignore=',
                        'register',
                        new BaseSizeBuilder(1040, 1040),
                        array(
                            new ImagemapMessageActionBuilder(
                                'โปร1000บาท',
                                new AreaBuilder(11, 91, 1020, 232)
                            ),
                            new ImagemapMessageActionBuilder(
                                'โปร500บาท',
                                new AreaBuilder(11, 329, 1020, 232)
                            ),
                            new ImagemapMessageActionBuilder(
                                'โปร300บาท',
                                new AreaBuilder(11, 561, 1020, 232)
                            ),
                            new ImagemapMessageActionBuilder(
                                'โปร200บาท',
                                new AreaBuilder(11, 800, 1020, 232)
                            ),
                        ),
                        $quickReplyMain
                    );
                }
                // else if ($userMessage == "push") {
                //     $responseProfile = $bot->getProfile(LINE_USER_ID);
                //     $profile = $responseProfile->getJSONDecodedBody();
                //     $UserName = $profile['displayName']; //can get 'displayName', 'userId', 'pictureUrl', 'statusMessage'
                //     $textUsername = new TextMessageBuilder($UserName);
                //     //Send to line_Bot2 
                //     $httpClient_push = new CurlHTTPClient('E8J7R3AojuWoZIwnVr1DnW7kINJiSxQxm300gBm2U4vtz38yaelGTD7dzL1PHhxLzRJopPKocwdVw4Em17nYAlzV8Ux+gOIAiT7oQiNac4D84OoMD9VZ1LVF72JQecvWhzfeDBWNcO7EMlft0cHmmQdB04t89/1O/w1cDnyilFU=');
                //     $bot_push = new LINEBot($httpClient_push, array('channelSecret' => 'a907165cb16817404ab203620cbe9fe6'));
                //     $response_push = $bot_push->pushMessage('U038a8b215cd7cc765f7a8380c2f86683', $textUsername);
                //     if ($response_push->isSucceeded()) {
                //         $replyData = new TextMessageBuilder('Send to Bot2 success');
                //     }
                // }
                elseif ($userMessage == "F") {
                    $actions = array(
                        // general message action
                        new MessageTemplateActionBuilder("ดูต่อ", "ควยลัน"),
                    );
                    $img_url = "https://i.ibb.co/KG4g477/979937-3261730880289-12041100-o.jpg";
                    $button = new ButtonTemplateBuilder("ควยลัน", "ฟหกฟหกฟหกฟหกฟหกฟหกฟหกหกดหกดหกดหกดกหดหกดฟ", $img_url, $actions);
                    $replyData = new TemplateMessageBuilder("Button template builder", $button);
                    break;
                }
                // ========================== Test===================================
                else {
                    $replyData = new FlexMessageBuilder("Flex", $textNotKeyword, $quickReplyMain);
                }
                break;
            }
        case "image":
            $replyData = new FlexMessageBuilder("Flex", $textGetUser, $quickReplyEditSlip);
            //$replyData = new TextMessageBuilder('This is image');
            break;
            // ----------------------------------------------------------------------------------------- Image
        default:
            break;
    }
    // ----------------------------------------------------------------------------------------- Image
    // ----------------------------------------------------------------------------------------- Respone

    $response = $bot->replyMessage($replyToken, $replyData);
    if ($response->isSucceeded()) {
        echo 'Succeeded!';
        return;
    }
    // Failed
    echo $response->getHTTPStatus() . ' ' . $response->getRawBody();

    // ----------------------------------------------------------------------------------------- Respone
}
