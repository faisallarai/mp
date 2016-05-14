<?php

namespace frontend\models;

use Yii;
use yii\helpers\Url;
use yii\db\ActiveRecord;
use common\models\Yiigun;
use common\components\MiscHelpers;

/**
 * This is the model class for table "mailgun_notification".
 *
 * @property integer $id
 * @property string $url
 * @property integer $created_at
 * @property integer $updated_at
 */
class MailgunNotification extends \yii\db\ActiveRecord
{
  const STATUS_PENDING = 0;
  const STATUS_READ = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mailgun_notification';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url',], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['url'], 'string', 'max' => 255],
        ];
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('frontend', 'ID'),
            'url' => Yii::t('frontend', 'Url'),
            'created_at' => Yii::t('frontend', 'Created At'),
            'updated_at' => Yii::t('frontend', 'Updated At'),
        ];
    }

    public function store($message_url) {
      // store the url from mailgun notification
        $mn = new MailgunNotification();
        $mn->status = MailgunNotification::STATUS_PENDING;
        $temp = str_ireplace('https://api.mailgun.net/v2/','',$message_url);
        $temp = str_ireplace('https://api.mailgun.net/v3/','',$temp);
        $mn->url = $temp;
        $mn->save();
    }

    public function process() {
      $items = MailgunNotification::find()->where(['status'=>MailgunNotification::STATUS_PENDING])->all();
      if (count($items)==0) {
        return false;
      }
      $yg = new Yiigun();
      foreach ($items as $m) {
        echo $m->id.'<br />';
        $response = $yg->get($m->url);
        //var_dump($response);
        // parse the meeting id
        $to_address = str_ireplace('@meetingplanner.io','',$response['To']);
        $to_address = str_ireplace('mp_','',$to_address);
        // verify meeting id is valid
        $sender = $response['Sender'];
        // verify sender is a participant or organizer to this meeting
        // add meeting note with log entry
        // mark as read
        echo $to_address;
        echo '<br><br>';
        echo $sender;
        echo '<br><br>';
        // to do - security clean post body
        $mn->status = MailgunNotification::STATUS_READ;
        $mn->update();
        echo '<br><br>';
      }
    }
}
