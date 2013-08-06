<?php
/**
 * Stub of Frontend Data Sync Access Point
 *
 * Expects only Post requests with two actions:
 *   1. subscribe a listener on some channels (channels represented as URL)
 *      Request
 *          $_POST['action'] = 'subscribe';
 *          $_POST['payload'] = '[
 *              "//somehost.com/entity/type/1",
 *              "//somehost.com/entity/type/2",
 *              "//somehost.com/some/other/entity/type/4"
 *          ]';
 *      Response is a JSON
 *          [
 *              {
 *                  "url": "//somehost.com/entity/type/1",
 *                  "token": "MzU5NDUyOWI5YWI5"
 *              },
 *              {
 *                  "url": "//somehost.com/entity/type/2",
 *                  "token": "ZjZhMzJkYjU0ZTgy"
 *              },
 *              {
 *                  "url": "//somehost.com/some/other/entity/type/4",
 *                  "error": "Forbidden" // client has no access to provided channel
 *              }
 *          ]
 *
 *   2. check updates for channels
 *      Request
 *          $_POST['action'] = 'fetchUpdates';
 *          $_POST['payload'] = '["MzU5NDUyOWI5YWI5", "ZjZhMzJkYjU0ZTgy"]';
 *      Response is a JSON
 *          [
 *              {
 *                  "url": "//somehost.com/entity/type/2",
 *                  "attributes": {
 *                      "first_name": "John",
 *                      "last_name": "Doe",
 *                  }
 *              }
 *          ]
 */
namespace SyncAP;

if (empty($_POST['action']) || empty($_POST['payload'])) {
    header('HTTP/1.0 400 Bad Request', true, 400);
    exit();
}

$controller = new Controller;
$action = sprintf("%sAction", $_POST['action']);
$payload = json_decode($_POST['payload'], true);

if (!is_callable(array($controller, $action)) || is_null($payload)) {
    header('HTTP/1.0 400 Bad Request', true, 400);
    exit();
} else {
    call_user_func(array($controller, $action), $payload);
}


class Controller
{
    public function subscribeAction(array $channels)
    {
        $response = array();
        $user = $this->getUser();
        foreach ($channels as $channel) {
            $subscription = $this->createSubscription($user, $channel);
            $item = array('url' => $channel);
            if ($subscription) {
                $item['token'] = $subscription->getToken();
            } else {
                $item['error'] = 'Forbidden';
            }
            $response[] = $item;
        }

        header('Content-type: application/json', true);
        echo json_encode($response);
    }

    public function fetchUpdatesAction(array $tokens)
    {
        $subscriptions = new SubscriptionCollection();
        $subscriptions->fetch(array('token => ?' => $tokens, 'sid' => $this->getUser()->getSID()));

        $response = array();
        /** @var array $update */
        foreach ($this->fetchUpdates($subscriptions) as $update) {
            $response[] = array(
                'url' => $update['channel'],
                'attributes' => json_decode($update['json'], true),
            );
        }

        header('Content-type: application/json', true);
        echo json_encode($response);

        // call save to update field updated_at in order to prevent expiring
        $subscriptions->save();
    }

    /**
     * Creates subscription for a pair of arguments user and channel
     * if user has no permission to listen to channel - returns null
     *
     * @param User $user
     * @param $channel
     * @return null|Subscription
     */
    protected function createSubscription(User $user, $channel)
    {
        $subscription = null;
        if ($user->isPermittedSubscription($channel)) {
            $subscription = new Subscription;
            $subscription->save(
                array(
                    'channel' => $channel,
                    'sid' => $user->getSID()
                )
            );
        }
        return $subscription;
    }

    public function fetchUpdates(SubscriptionCollection $subscription)
    {
        /**
         * Model has fields in database
         *  - channel
         *  - created_at
         *  - json
         */
        //@TODO implement fetching updates for corresponded subscriptions collection
        // where subscription.channel = update.channel and subscription.updated_at <= update.created_at
//        return array();
        return array(
            array(
                'channel' => '//somehost.com/entity/type/1',
                'json' => '{"first_name": "John","last_name": "Doe"}'
            ),
            array(
                'channel' => '//somehost.com/entity/type/2',
                'json' => '{"first_name": "Jane","last_name": "Doe"}'
            )
        );
    }

    /**
     * Fetches current user
     *
     * @return User
     */
    protected function getUser()
    {
        return new User;
    }
}


class User
{
    protected $sid;

    /**
     * Fetches users information from current session
     */
    public function __construct()
    {
        //@TODO define session id
        $this->sid = '';
    }

    /**
     * Check if current user has permission to listen to corresponded channel
     *
     * @param string $channel
     * @return bool
     */
    public function isPermittedSubscription($channel)
    {
        //@TODO implement user's permission check for corresponded channel
        return rand() % 10 !== 0;
    }

    /**
     * Returns sid property's value
     *
     * @return string
     */
    public function getSID()
    {
        return $this->sid;
    }
}


class Subscription
{
    /**
     * Model has fields in database
     *  - channel
     *  - token
     *  - sid
     *  - created_at
     *  - updated_at
     */

    // blowfish secret key should be taken from configuration
    const BLOWFISH_SECRET = 'ODcyNGZkMWMyYjhl';

    // values of database fields
    protected $attributes = array();

    /**
     * Generate unique token for a subscription
     *
     * @return string
     */
    protected function generateToken()
    {
        $sequence = sprintf(
            '%s:%d:%s',
            $this->attributes['channel'],
            $this->attributes['created_at'],
            self::BLOWFISH_SECRET
        );
        return substr(md5($sequence), 0, 16);
    }

    /**
     * Generates token and performs saving action
     *
     * @param array $attributes
     */
    public function save(array $attributes)
    {
        $attributes = array_diff_key($attributes, array_flip(array('updated_at', 'created_at', 'token')));
        $this->attributes = array_merge($this->attributes, $attributes);
        $this->attributes['updated_at'] = time();
        if (empty($this->attributes['created_at'])) {
            $this->attributes['created_at'] = $this->attributes['updated_at'];
        }
        if (empty($this->attributes['token'])) {
            $this->attributes['token'] = $this->generateToken();
        }

        //@TODO perform saving action
    }

    /**
     * Returns value of token attribute
     *
     * @return null|string
     */
    public function getToken()
    {
        return !empty($this->attributes['token'])? $this->attributes['token']: null;
    }

    /**
     * Returns value of sid attribute
     *
     * @return null|string
     */
    public function getSID()
    {
        return !empty($this->attributes['sid'])? $this->attributes['sid']: null;
    }
}

class SubscriptionCollection
{
    protected $items = array();

    public function fetch($params)
    {
        //@TODO perform select query by passed params
        // fills in an `items` property with Subscription instances
    }

    public function save()
    {
        //@TODO perform mass save actions for selected subscriptions
        // call save to update field updated_at in order to prevent expiring
    }
}