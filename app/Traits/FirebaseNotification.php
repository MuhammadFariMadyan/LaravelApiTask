<?php

namespace App\Common;

use App\Utils\AppConstant;
use Illuminate\Support\Facades\Log;
use LaravelFCM\Message\PayloadNotificationBuilder;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Facades\FCM;
use LaravelFCM\Message\Topics;

trait FirebaseNotification
{

    protected function sendToSingle($fcmToken, $message, $status, $deviceType)
    {
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60 * 20)
            ->setPriority('high');

        $notificationBuilder = new PayloadNotificationBuilder();
        $notificationBuilder->setBody($message . $deviceType)
            ->setSound('default');

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['statusCode' => $status, 'messageText' => $message]);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();
        $downstreamResponse = null;
        switch ($deviceType) {
            case "android":
                $downstreamResponse = FCM::sendTo($fcmToken, $option, null, $data);
                break;
            case "iphone":
            case "iPad":
                $downstreamResponse = FCM::sendTo($fcmToken, $option, $notification, $data);
                break;
            default:
                $downstreamResponse = FCM::sendTo($fcmToken, $option, $notification, $data);
                break;
        }
        return $downstreamResponse->numberSuccess();
    }

    protected function sendToTopics($topicName, $message, $status, $footerStatus)
    {
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60 * 20)
            ->setPriority('high');

        $notificationBuilder = new PayloadNotificationBuilder();
        $notificationBuilder->setBody($message)
            ->setSound('default');
        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['statusCode' => $status, 'messageText' => $message, 'footerStatus' => $footerStatus]);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        $topic = new Topics();
        $topic->topic($topicName)->andTopic(AppConstant::OS_ANDROID);
        $topicResponse = FCM::sendToTopic($topic, $option, $notification, $data);

        $topic = new Topics();
        $topic->topic($topicName)->andTopic(AppConstant::OS_IOS);
        $topicResponse = FCM::sendToTopic($topic, $option, $notification, $data);

        return $topicResponse->isSuccess();
    }

    protected function sendBlankPushToTopics($status, $footerStatus)
    {
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60 * 20)->setPriority('high');

        $notificationBuilder = new PayloadNotificationBuilder();
        $notificationBuilder->setBody("")->setSound(null);

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['statusCode' => $status, "footerStatus" => $footerStatus]);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        $topic = new Topics();
        $topic->topic(AppConstant::OS_ANDROID);
        $topicResponse = FCM::sendToTopic($topic, $option, null, $data);

        $topic = new Topics();
        $topic->topic(AppConstant::OS_IOS);
        $topicResponse = FCM::sendToTopic($topic, $option, $notification, $data);

        return $topicResponse->isSuccess();
    }
}

?>
