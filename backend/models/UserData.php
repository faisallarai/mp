<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use common\models\User;
use frontend\models\Meeting;
use frontend\models\Participant;
use frontend\models\UserPlace;
use frontend\models\Friend;

/**
 * This is the model class for table "user_data".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $source
 * @property integer $count_meetings
 * @property integer $count_meetings_last30
 * @property integer $count_meeting_participant
 * @property integer $count_meeting_participant_last30
 * @property integer $count_places
 * @property integer $count_friends
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $user
 */
class UserData extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_data';
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
    public function rules()
    {
        return [
            [['user_id' ], 'required'],
            [['user_id', 'source', 'count_meetings', 'count_meetings_last30', 'count_meeting_participant', 'count_meeting_participant_last30', 'count_places', 'count_friends', 'created_at', 'updated_at'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'user_id' => Yii::t('backend', 'User ID'),
            'source' => Yii::t('backend', 'Source'),
            'count_meetings' => Yii::t('backend', 'Count Meetings'),
            'count_meetings_last30' => Yii::t('backend', 'Count Meetings Last30'),
            'count_meeting_participant' => Yii::t('backend', 'Count Meeting Participant'),
            'count_meeting_participant_last30' => Yii::t('backend', 'Count Meeting Participant Last30'),
            'count_places' => Yii::t('backend', 'Count Places'),
            'count_friends' => Yii::t('backend', 'Count Friends'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @inheritdoc
     * @return UserDataQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserDataQuery(get_called_class());
    }

    public static function reset() {
      UserData::find()->deleteAll();
    }

    public static function calculate() {
      $all = User::find()->all();
      foreach ($all as $u) {
        // create new record for user or update old one
        $ud = UserData::findOne($u->id);
        if (is_null($ud)) {
          $ud = new UserData();
          $ud->user_id = $u->id;
          //var_dump($ud->validate());
          //var_dump($ud->getErrors());
          $ud->save();
        }
        $user_id = $u->id;
        // determine source

        $monthago = mktime(0, 0, 0)-(60*60*24*30);
        // count meetings they've organized
        $ud->count_meetings = Meeting::find()->where(['owner_id'=>$user_id])->count();
        $ud->count_meetings_last30 = Meeting::find()->where(['owner_id'=>$user_id])->andWhere('created_at>='.$monthago)->count();
        // count meetings they were invited to
        $ud->count_meeting_participant = Participant::find()->where(['participant_id'=>$user_id])->count();
        $ud->count_meeting_participant_last30 = Participant::find()->where(['participant_id'=>$user_id])->andWhere('created_at>='.$monthago)->count();
        // count places and Friends
        $ud->count_places = UserPlace::find()->where(['user_id'=>$user_id])->count();
        $ud->count_friends = Friend::find()->where(['user_id'=>$user_id])->count();
        $ud->update();
      }
    }
}
