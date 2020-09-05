<?php
        namespace App\Controller;
        use Framework\Shared;
        use Framework\Request\RequestHandler;
        use Framework\Lib\Security\Data\FilterDataFactory;
        use Framework\Error\WebViewError;
        use App\Model\WebServices\Github\Api;
        use App\Model\UserModel;

Class Index extends Shared\Controller
{
        private $github;
        /**
                * @method indexAction.
                * @description Generate Login Page.
        */
        
        public function __construct()
        {
                parent::__construct();
                $this->github = new Api();
        }
        public function defaultAction()
        {
                $this->rIn("id","index/overviewPage");
                $this->renderLayout('Header'); 
                $this->actionView->setDataInView(["login_url"=>(object)['url'=>BASE_URL. LINK_SIGN . "index"], "session"=>$this->session]);
                $this->render();
                $this->renderLayout('Footer');
        }
        /**
         * 1 - user click github button => redirected to github to auth. (--Done).
         * 2 - check if user already has registered before.
                * if true  => redirect to welcome page.
                * if false => let user choose any password then register him/her to our system.
                        * then redirect him/her to welcome page.
         */
        public function githubCallBackAction()
        {
            $this->rIn("id","index/overviewPage");
            /** If no tokens saved .. save it. */
            if( false == $this->github->tokenExists() )
            {
                RequestHandler::getRequest();
                if(RequestHandler::get('redirect'))
                {
                  /** Save New tokens. */
                  $tokens = $this->github->saveTokens(RequestHandler::get('code'), RequestHandler::get('state'));
                }
            }
            /** Request User information. */
            $user_data = $this->github->requestUserData();

            if (!is_object($user_data))
            {
                /** Something goes wrong. */

                /** TODO: error message in index. */
                $this->rOut("github_id", "index");
            }
            /** open session to store basic data about user. */
            $this->session->setSession('github_id',$user_data->id);
            $this->session->setSession('username',$user_data->login);
            $this->session->setSession('email',$user_data->email);
            
            $user = new UserModel(); 
            $user = $user->getFinder()->checkLoginData($user_data->id);

            if(false == $user)
            {
                /** New User Let him/her put a new password. */
                $this->rOut("redirect","index/newPassword");
            }
            else
            {
                /** User exists redirect him/her to app. */
                $this->session->setSession('id',$user['id']);
                $this->rIn("id","index/overviewPage");
            }
        }

        public function newPasswordAction()
        {
           $this->rIn("id","index/overviewPage");
           $this->rOut("github_id", "index");
           
           RequestHandler::postRequest();

           if (RequestHandler::post('password'))
           {
                $user = new UserModel();
                $addUser = $user->getFinder()->addNewUser(
                        $this->session->getSession('username'),
                        password_hash(RequestHandler::post('password'), PASSWORD_DEFAULT),
                        $this->session->getSession('email'),
                        $this->session->getSession('github_id')
                );
                $this->session->setSession('id',$this->session->getSession('github_id'));
                $this->rIn("id","index/overviewPage");
           }

           /** Password view. */
           $this->renderLayout('Header'); 
           $this->render();
           $this->renderLayout('Footer');

        }

        public function githubBridgeAction()
        {
                $this->rIn("id","index/overviewPage");
                $this->github->generateUrl();
        }
        


        public function normalLoginAction()
        {
                RequestHandler::postRequest();

                $cred     = RequestHandler::post('email');
                $password = RequestHandler::post('password');

                if ( $cred != false && $password != false )
                {
                        /** check user. */
                        $user = new UserModel();

                        if (stripos($cred , "@") != false)
                        {
                                /** User enter email. */
                                $checkuser = $user->getFinder()->getUserByEmail($cred);
                        }
                        else
                        {
                                /** User enter username. */
                                $checkuser = $user->getFinder()->getUserByUserName($cred);
                        }

                        $this->verfiyUser($checkuser, $password);
                }
                else
                {
                   $this->rOut("id", "index");    
                }
        }
        public function overviewPageAction()
        {
              $this->rOut("id", "index");
              $this->actionView->setDataInView(["user"=>(object)['username'=>$this->session->getSession("username"), 'url'=>BASE_URL. LINK_SIGN . "index" . DS ."logout"]]);
              $this->render();
        }

        public function logoutAction()
        {
                if($this->session->getSession('id'))
                {

                   $this->session->unsetSession('id');
                   $this->session->unsetSession('email');
                   $this->session->unsetSession('github_id');
                   $this->session->unsetSession('username');
                   $this->session->unsetSession('auth.token');
                }
                $this->rOut("id", "index");
        }


        private function verfiyUser($user, $password)
        {
                if ( false  == $user )
                {
                        /** Not found. */
                        $this->session->setSession('error',"username/email not exists.");
                        $this->rOut("id", "index");
                }
                else
                {
                        if(password_verify($password, $user['password']))
                        {
                                /** Correct cred redirect user. */
                                $this->session->setSession('id',$user['id']);
                                $this->session->setSession('github_id',$user['id']);
                                $this->session->setSession('username',$user['username']);
                                $this->session->setSession('email',$user['email']);

                                $this->rIn("id", "index/overviewPage");
                        }
                        else
                        {
                                $this->session->setSession('error',"username/email or password can not match our records.");
                                $this->rOut("id", "index");
                        }
                }
        }
}