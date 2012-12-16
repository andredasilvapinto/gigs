<?php
/**
 * Demonstration of the OAuth authorize flow only. You would typically do this
 * when an unknown user is first using your application and you wish to make
 * requests on their behalf.
 *
 * Instead of storing the token and secret in the session you would probably
 * store them in a secure database with their logon details for your website.
 *
 * When the user next visits the site, or you wish to act on their behalf,
 * you would use those tokens and skip this entire process.
 *
 * Instructions:
 * 1) If you don't have one already, create a Twitter application on
 *      https://dev.twitter.com/apps
 * 2) From the application details page copy the consumer key and consumer
 *      secret into the place in this code marked with (YOUR_CONSUMER_KEY
 *      and YOUR_CONSUMER_SECRET)
 * 3) Visit this page using your web browser.
 *
 * @author themattharris
 */
//require 'themattharris/tmhOAuth.php';
//require 'themattharris/tmhUtilities.php';

class TwitterOAuth {

	private $twitterHandler;

	public function __construct($handler) {
		$this->twitterHandler = $handler;
	}

	public static function createNewOAuthTwitter() {
		$handler = new tmhOAuth(array(
            'consumer_key'      => GigsConfig::TWITTER_APP_OAUTH_CUSTOMER_KEY,
            'consumer_secret'   => GigsConfig::TWITTER_APP_OAUTH_CUSTOMER_SECRET,
        ));

		return new TwitterOAuth($handler);
	}
	
	public static function createTwitterOAuthHandler($accessToken, $secretAccessToken) {
		$handler = new tmhOAuth(array(
            'consumer_key'      => GigsConfig::TWITTER_APP_OAUTH_CUSTOMER_KEY,
            'consumer_secret'   => GigsConfig::TWITTER_APP_OAUTH_CUSTOMER_SECRET,
            'user_token' => $accessToken,
            'user_secret' => $secretAccessToken,
        ));

		return new TwitterOAuth($handler);
	}

	public function outputError() {
		return 'There was an error: ' . $this->twitterHandler->response['response'] . PHP_EOL;
	}

// Step 1: Request a temporary token
	public function request_token($callbackURL) {
		$code = $this->twitterHandler->request(
				'POST', $this->twitterHandler->url('oauth/request_token', ''),
				array(
					//'oauth_callback' => tmhUtilities::php_self(false)
					'oauth_callback' => $callbackURL
				)
		);
		
		if ($code == 200) {
			Yii::app()->session['twitterOAuth'] = $this->twitterHandler->extract_params($this->twitterHandler->response['response']);
			$this->authorize();
		} else {
			$this->outputError();
		}
	}

// Step 2: Direct the user to the authorize web page
	private function authorize() {
		$authurl = $this->twitterHandler->url("oauth/authorize", '') .
				"?oauth_token=" . Yii::app()->session['twitterOAuth']['oauth_token'];
		
		Yii::app()->request->redirect($authurl);

		// in case the redirect doesn't fire
		echo '<p>To complete the OAuth flow please visit URL: <a href="' . $authurl . '">' . $authurl . '</a></p>';
	}

// Step 3: This is the code that runs when Twitter redirects the user to the callback. Exchange the temporary token for a permanent access token
	public function access_token() {
		$this->twitterHandler->config['user_token']  = Yii::app()->session['twitterOAuth']['oauth_token'];
		$this->twitterHandler->config['user_secret'] = Yii::app()->session['twitterOAuth']['oauth_token_secret'];

		$code = $this->twitterHandler->request(
				'POST', $this->twitterHandler->url('oauth/access_token', ''),
				array(
					'oauth_verifier' => $_REQUEST['oauth_verifier']
				)
		);

		if ($code == 200) {
			unset(Yii::app()->session['oauth']);
			//Yii::app()->session['access_token']
			return $this->twitterHandler->extract_params($this->twitterHandler->response['response']);
		} else {
			return null;
		}
	}

// Step 4: Now the user has authenticated, do something with the permanent token and secret we received
	/*function verify_credentials() {
		$this->twitterHandler->config['user_token'] = $_SESSION['access_token']['oauth_token'];
		$this->twitterHandler->config['user_secret'] = $_SESSION['access_token']['oauth_token_secret'];

		$code = $this->twitterHandler->request(
				'GET', $this->twitterHandler->url('1/account/verify_credentials')
		);

		if ($code == 200) {
			$resp = json_decode($this->twitterHandler->response['response']);
			echo '<h1>Hello ' . $resp->screen_name . '</h1>';
			echo '<p>The access level of this token is: ' . $this->twitterHandler->response['headers']['x_access_level'] . '</p>';
		} else {
			outputError();
		}
	}*/

	function send_tweet($text) {
		$code = $this->twitterHandler->request(
				'POST', $this->twitterHandler->url('1/statuses/update'),
				array(
					'status' => $text
				));

		if ($code == 200) {
			return $this->twitterHandler->response['response'];
		} else {
			return null;
		}
	}

	/* if (isset($_REQUEST['start'])) :
	  request_token($tmhOAuth);
	  elseif (isset($_REQUEST['oauth_verifier'])) :
	  access_token($tmhOAuth);
	  elseif (isset($_REQUEST['verify'])) :
	  verify_credentials($tmhOAuth);
	  elseif (isset($_REQUEST['wipe'])) :
	  wipe();
	  elseif (isset($_REQUEST['send'])) :
	  send_tweet($tmhOAuth, '"Stop multi-tasking. No, seriously—stop." www.youtube.com/watch?v=ZWOwMzGq-IY');
	  endif; */
}
?>